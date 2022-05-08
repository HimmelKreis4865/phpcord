<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\runtime\network\socket;

use Closure;
use JetBrains\PhpStorm\ArrayShape;
use phpcord\Discord;
use phpcord\runtime\network\Network;
use phpcord\runtime\network\packet\MessageBuffer;
use phpcord\runtime\network\MessageSender;
use phpcord\runtime\network\packet\Packet;
use phpcord\runtime\tick\Ticker;
use phpcord\utils\error\ErrorHook;
use phpcord\utils\InternetAddress;
use RuntimeException;
use Throwable;
use function chr;
use function error_get_last;
use function fclose;
use function fread;
use function fwrite;
use function implode;
use function ord;
use function pack;
use function rand;
use function socket_get_status;
use function str_contains;
use function stream_context_create;
use function stream_get_meta_data;
use function stream_set_blocking;
use function stream_set_timeout;
use function stream_socket_client;
use function strlen;
use function var_dump;
use const STREAM_CLIENT_CONNECT;

final class WebSocket {
	
	public const HEADER_FINAL = 0x80;
	
	public const HEADER_BINARY = 0x02;
	
	public const HEADER_TEXT = 0x01;
	
	/** @var resource $stream */
	private $stream;
	
	private ?string $errStr = null;
	
	/**
	 * @param InternetAddress $netAddress
	 * @param string $path
	 * @param array $headers
	 * @param resource|null $context
	 */
	public function __construct(protected InternetAddress $netAddress, string $path = '/', array $headers = [], $context = null) {
		$err = null; // to prevent ide problems
		ErrorHook::getInstance()->trap(Closure::fromCallable('stream_socket_client'), 'tls://' . $this->netAddress, $err, $this->errStr, 10, STREAM_CLIENT_CONNECT, $context ?? stream_context_create(['ssl' => $this->getInternalSSLOptions()]))->success(function($stream): void {
			$this->stream = $stream;
		})->fail(function (Throwable $err): void {
			throw new RuntimeException('Failed to connect to gateway: ' . $err->getMessage());
		});
		
		stream_set_timeout($this->stream, 1);
		fwrite($this->stream, implode("\r\n", [
			'GET ' . $path . ' HTTP/1.1',
			'Host: ' . $this->netAddress->getIp(),
			'pragma: no-cache',
			'Upgrade: WebSocket',
			'Connection: Upgrade',
			'Sec-WebSocket-Key: ' . base64_encode(openssl_random_pseudo_bytes(16)),
			'Sec-WebSocket-Version: 13',
			...$headers
		]) . "\r\n\r\n");
		if (!($result = fread($this->stream, 2048)) or !str_contains($result, ' 101 '))
			throw new RuntimeException('Failed to connect to ' . $this->netAddress . ': (' . $err . ') ' . $this->errStr);
		stream_set_blocking($this->stream, false);
	}
	
	/**
	 * @internal
	 *
	 * @param string $buffer
	 *
	 * @return bool
	 */
	public function write(string $buffer): bool {
		$header = chr(self::HEADER_FINAL | self::HEADER_BINARY);
		$header .= (strlen($buffer) < 126 ? chr(self::HEADER_FINAL | strlen($buffer)) : (strlen($buffer) < 0xFFFF ? chr(self::HEADER_FINAL | 126) . pack("n", strlen($buffer)) : chr(self::HEADER_FINAL | 127) . pack("N",0) . pack("N",strlen($buffer))));
		$header .= $mask = pack("N", rand(1, 0x7FFFFFFF));
		
		for ($i = 0; $i < strlen($buffer); $i++) $buffer[$i] = chr(ord($buffer[$i]) ^ ord($mask[$i % 4]));
		$return = false;
		ErrorHook::getInstance()->trap(Closure::fromCallable('fwrite'), $this->stream, ($str = $header . $buffer))->success(function (int|bool|null $result) use (&$return, $str): void {
			$return = (strlen($str) === $result);
			if (!$result) {
				Network::getInstance()->getLogger()->warning('Websocket connection is gone.');
				Network::getInstance()->getGateway()->open();
			}
		})->fail(function($err): void {
			Network::getInstance()->getLogger()->warning('Websocket connection is gone.');
			Network::getInstance()->getGateway()->open();
		});
		return $return;
	}
	
	/**
	 * @internal
	 *
	 * @return MessageBuffer|false
	 */
	public function read(): MessageBuffer|false {
		$buffer = new MessageBuffer('');
		do {
			$header = -1;
			ErrorHook::getInstance()->trap(Closure::fromCallable('fread'), $this->stream, 2)->fail(function(): void {
				Network::getInstance()->getLogger()->warning('Websocket connection is gone.');
				Network::getInstance()->getGateway()->open();
			})->success(function(string $result) use (&$header): void { $header = $result; });
			if (!$header or $header === -1) return false;
			
			[$opcode, $final, $masked, $payload_len] = [ord($header[0]) & 0x0f, ord($header[0]) & 0x80, ord($header[1]) & 0x80, ord($header[1]) & 0x7f];
			
			if ($payload_len >= 0x7e){
				$ext_len = ($payload_len === 0x7f ? 8 : 2);
				$header = fread($this->stream, $ext_len);
				if (!$header) return false;
				
				$payload_len = 0;
				for ($i = 0; $i < $ext_len; $i++) $payload_len += ord($header[$i]) << ($ext_len-$i-1)*8;
			}
			
			if ($masked) {
				$mask = fread($this->stream,4);
				if (!$mask) return false;
			}
			
			$frame_data = '';
			while ($payload_len > 0){
				$frame = fread($this->stream,$payload_len);
				if (!$frame) return false;
				$payload_len -= strlen($frame);
				$frame_data .= $frame;
			}
			if ($opcode === 8) {
				$this->close();
				return false;
			}
			$data_len = strlen($frame_data);
			if ($masked)
				for ($i = 0; $i < $data_len; $i++) $buffer->buffer .= $frame_data[$i] ^ $mask[$i % 4];
			else
				$buffer->buffer .= $frame_data;
		} while (!$final);
		return $buffer;
	}
	
	/**
	 * @return array
	 */
	#[ArrayShape(['verify_peer' => "false", 'verify_peer_name' => "false", 'SNI_enabled' => "false"])] private function getInternalSSLOptions(): array {
		return [
			'verify_peer' => false,
			'verify_peer_name' => false,
			'SNI_enabled' => false
		];
	}
	
	public function isAlive(): bool {
		return ($this->stream !== null);
	}
	
	public function close(): void {
		ErrorHook::getInstance()->trap(Closure::fromCallable('fclose'), $this->stream);
		$this->stream = null;
	}
}