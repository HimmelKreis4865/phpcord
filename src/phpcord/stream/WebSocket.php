<?php

namespace phpcord\stream;

use phpcord\thread\Thread;
use phpcord\utils\MainLogger;
use RuntimeException;
use function base64_encode;
use function fclose;
use function fread;
use function fsockopen;
use function fwrite;
use function get_resource_id;
use function get_resource_type;
use function is_resource;
use function openssl_random_pseudo_bytes;
use function socket_get_status;
use function socket_set_blocking;
use function socket_set_nonblock;
use function stream_set_blocking;
use function stream_set_timeout;
use function strpos;
use function substr;

class WebSocket {
	/** @var resource $stream */
	public $stream;
	
	/**
	 * WebSocket constructor.
	 *
	 * @param string $address
	 * @param int $port
	 * @param bool $nonBlock
	 * @param bool $set
	 */
	public function __construct(string $address, int $port, bool $nonBlock = false, bool $set = true) {
		if (!$set) return;
		$this->stream = fsockopen($address, $port, $error_code, $error_message);
		//socket_set_blocking($this->stream, false);
		if ($error_code !== 0 or !$this->stream)
			throw new RuntimeException("Failed to connect to websocket [$error_code] $error_message");
		
		if ($nonBlock) stream_set_blocking($this->stream, false);
		$this->sendHeaders();
	}
	
	public static function fromStream($stream): WebSocket {
		$sock = new WebSocket("-", 1, false, false);
		$sock->stream = $stream;
		return $sock;
	}
	
	public function isInvalid(): bool {
		return (!is_resource($this->stream) or socket_get_status($this->stream)["timed_out"] ?? false);
	}
	
	public function invalidate(): void {
		$this->close();
	}
	
	public function close(): void {
		if (is_resource($this->stream)) fclose($this->stream);
		$this->stream = null;
	}
	
	protected function sendHeaders(): bool {
		$key = base64_encode(openssl_random_pseudo_bytes(16));
		$header = "GET / HTTP/1.1\r\n"
			. "Host: gateway.discord.gg\r\n"
			. "pragma: no-cache\r\n"
			. "Upgrade: Websocket\r\n"
			. "Connection: Upgrade\r\n"
			. "Sec-WebSocket-Key: $key\r\n"
			. "Sec-WebSocket-Version: 13\r\n";
		
		if (!fwrite($this->stream, $header . "\r\n")) return false;
		$response_header = fread($this->stream, 4096);
		if (!$response_header or strpos(substr($response_header, 0, 25), "101") === false) return false;
		return true;
	}
	
	/**
	 * Reads from the websocket connection
	 *
	 * @internal
	 *
	 * @return string|null
	 */
	public function read(): ?string {
		do {
			$header = fread($this->stream, 2);
			if (!$header) {
				return false;
			}
			if (!isset($data)) $data = "";
			
			$opcode = intval(ord($header[0]) & 0x0F);
			$final = ord($header[0]) & 0x80;
			$masked = ord($header[1]) & 0x80;
			$payload_len = ord($header[1]) & 0x7F;
			if ($payload_len >= 0x7E) {
				$ext_len = 2;
				if ($payload_len == 0x7F) $ext_len = 8;
				$header = fread($this->stream, $ext_len);
				if (!$header) return false;
				
				$payload_len = 0;
				for ($i = 0; $i < $ext_len; $i++)
					$payload_len += ord($header[$i]) << ($ext_len - $i - 1) * 8;
			}
			
			if ($masked) {
				$mask = fread($this->stream, 4);
				if (!$mask) return false;
			}
			$frame_data = '';
			do {
				$frame = fread($this->stream, $payload_len);
				if (!$frame) return false;
				$payload_len -= strlen($frame);
				$frame_data .= $frame;
			} while ($payload_len > 0);
			
			// todo: is this needed?
			if ($opcode == 9) {
				fwrite($this->stream, chr(0x8A) . chr(0x80) . pack("N", rand(1, 0x7FFFFFFF)));
				continue;
			} elseif ($opcode < 3) {
				$data_len = strlen($frame_data);
				if ($masked) {
					for ($i = 0; $i < $data_len; $i++) {
						$data .= $frame_data[$i] ^ $mask[$i % 4];
					}
					continue;
				}
				$data .= $frame_data;
			}
		} while (!$final);
		
		return $data;
	}
	
	/**
	 * Sends a buffer to the connection
	 *
	 * @internal
	 *
	 * @param string $buffer
	 *
	 * @return bool
	 */
	public function write(string $buffer): bool {
		MainLogger::logDebug("sending $buffer");
		$header = chr(0x80 | 0x01);
		
		if (strlen($buffer) < 126) {
			$header .= chr(0x80 | strlen($buffer));
		} elseif (strlen($buffer) < 0xFFFF) {
			$header .= chr(0x80 | 126) . pack("n", strlen($buffer));
		} else {
			$header .= chr(0x80 | 127) . pack("N", 0) . pack("N", strlen($buffer));
		}
		
		$mask = pack("N", rand(1, 0x7FFFFFFF));
		$header .= $mask;
		
		for ($i = 0; $i < strlen($buffer); $i++)
			$buffer[$i] = chr(ord($buffer[$i]) ^ ord($mask[$i % 4]));
		
		return (fwrite($this->stream, $header . $buffer) >= strlen($buffer));
	}
}