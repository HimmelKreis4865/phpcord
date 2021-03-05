<?php

namespace phpcord\stream;

use phpcord\utils\MainLogger;
use function error_get_last;
use function fwrite;
use function base64_encode;
use function openssl_random_pseudo_bytes;
use function stream_set_blocking;
use function stream_socket_client;
use function stream_context_create;
use function stream_set_timeout;
use function stripos;
use function fread;
use function ord;
use function intval;
use function pack;
use function chr;
use function is_int;
use function get_resource_type;
use function strpos;
use function strtolower;
use function is_null;
use function fclose;
use function usleep;

class StreamHandler implements WriteableInterface, ReadableInterface {
	
	/** @var resource|null $stream */
	public $stream;
	
	/** @var null | array $data */
	protected $data = null;
	
	public $expired = false;

	/**
	 * @param string $host
	 * @param int $port
	 * @param array $headers
	 * @param int $timeout
	 * @param bool $ssl
	 * @param null $context
	 *
	 * @return false|resource
	 */
	public function connect(string $host = '', int $port = 80, $headers = [], int $timeout = 1, bool $ssl = true, $context = null) {
		$this->expired = false;
		$this->data = ["host" => $host, "port" => $port, "headers" => $headers, "timeout" => $timeout, "ssl" => $ssl, "context" => $context];
		
		$key = base64_encode(openssl_random_pseudo_bytes(16));
		$header = "GET / HTTP/1.1\r\n"
			. "Host: $host\r\n"
			. "pragma: no-cache\r\n"
			. "Upgrade: WebSocket\r\n"
			. "Connection: Upgrade\r\n"
			. "Sec-WebSocket-Key: $key\r\n"
			. "Sec-WebSocket-Version: 13\r\n";

		if (!empty($headers)) foreach ($headers as $h) $header .= $h . "\r\n";
		$header .= "\r\n";

		$host = $host ? $host : "127.0.0.1";
		$port = $port < 1 ? ($ssl ? 443 : 80) : $port;
		$address = ($ssl ? 'ssl://' : '') . $host . ':' . $port;
		$ctx = $context ?? stream_context_create();
		
		$sp = stream_socket_client($address, $errno, $str, $timeout, STREAM_CLIENT_CONNECT, $ctx);

		if (!$sp) return false;
		
		//stream_set_blocking($sp, false);
		
		stream_set_timeout($sp, $timeout);
		
		if (!fwrite($sp, $header)) return false;
		
		$response_header = fread($sp, 1024);

		if (stripos($response_header, ' 101 ') === false || stripos($response_header, 'Sec-WebSocket-Accept: ') === false) {
			return false;
		}

		$this->stream = $sp;
		return $sp;
	}

	public function write($data, bool $final = true) {
		if ($this->isExpired()) return false;
		MainLogger::logDebug("[WebSocket] Sending: $data");
		$header = chr(($final ? 0x80 : 0) | 0x01); // 0x01 text mode

		if (strlen($data) < 126) {
			$header .= chr(0x80 | strlen($data));
		} elseif (strlen($data) < 0xFFFF) {
			$header .= chr(0x80 | 126) . pack("n", strlen($data));
		} else {
			$header .= chr(0x80 | 127) . pack("N", 0) . pack("N", strlen($data));
		}

		$mask = pack("N", rand(1, 0x7FFFFFFF));
		$header .= $mask;

		for ($i = 0; $i < strlen($data); $i++)
			$data[$i] = chr(ord($data[$i]) ^ ord($mask[$i % 4]));

		return fwrite($this->stream, $header . $data);
	}

	/**
	 * @internal
	 *
	 * @return false|string
	 */
	public function read() {
		if ($this->isExpired()) return false;

		$data = "";

		do {
			$header = fread($this->stream, 2);
			if (!$header) {
				return false;
			}

			$opcode = intval(ord($header[0]) & 0x0F);
			$final = ord($header[0]) & 0x80;
			$masked = ord($header[1]) & 0x80;
			$payload_len = ord($header[1]) & 0x7F;
			if ($payload_len >= 0x7E) {
				$ext_len = 2;
				if ($payload_len == 0x7F) $ext_len = 8;
				$header = fread($this->stream, $ext_len);
				if (!$header) {
					return false;
				}

				$payload_len = 0;
				for ($i = 0; $i < $ext_len; $i++)
					$payload_len += ord($header[$i]) << ($ext_len - $i - 1) * 8;
			}

			if ($masked) {
				$mask = fread($this->stream, 4);
				if (!$mask) {
					return false;
				}
			}
			$frame_data = '';
			do {
				$frame = fread($this->stream, $payload_len);
				if (!$frame) {
					return false;
				}
				$payload_len -= strlen($frame);
				$frame_data .= $frame;
			} while ($payload_len > 0);
			
			// todo: is this needed?
			if ($opcode == 9) {
				fwrite($this->stream, chr(0x8A) . chr(0x80) . pack("N", rand(1, 0x7FFFFFFF)));
				continue;
			} elseif ($opcode < 3) {
				$data_len = strlen($frame_data);
				if ($masked)
					for ($i = 0; $i < $data_len; $i++)
						$data .= $frame_data[$i] ^ $mask[$i % 4];
				else
					$data .= $frame_data;

			} else
				continue;

		} while (!$final);

		return $data;
	}

	public function isExpired(): bool {
		if (is_array(error_get_last())) {
			if (strpos(error_get_last()["file"], "StreamHandler.php") !== false) return true;
		}
		return ($this->expired or is_int($this->stream) or (is_null($this->stream) or (strtolower(get_resource_type($this->stream)) !== "stream")));
	}
	
	public function expire(): void {
		$this->expired = true;
	}

	public function close(): void {
		if (!is_null($this->stream)) @fclose($this->stream);
		$this->stream = null; // preventing any issues with invalid streams and stuff
	}
	
	public function reconnect(): bool {
		$this->close();
		if (($d = $this->data) === null) return false;
		usleep(1000 * 250);
		return !is_bool($this->connect($d["host"], $d["port"], $d["headers"], $d["timeout"], $d["ssl"], $d["context"]));
	}
}