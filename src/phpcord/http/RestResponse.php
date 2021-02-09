<?php

namespace phpcord\http;

use function explode;
use function is_null;
use function is_numeric;
use function str_replace;
use function var_dump;

class RestResponse {
	/** @var mixed $raw_data */
	protected $raw_data;

	protected $failed = false;

	/**
	 * RestResponse constructor.
	 *
	 * @param mixed $raw_data
	 */
	public function __construct($raw_data = null) {
		$this->raw_data = $raw_data;
	}

	public function fail(): self {
		$this->failed = true;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isFailed(): bool {
		return $this->failed;
	}

	/**
	 * @return mixed
	 */
	public function getRawData() {
		var_dump($this->raw_data);
		return $this->raw_data;
	}
	
	public static function findError(string $message): ?string {
		$message = str_replace(["HTTP/1.0", "HTTP/1.2", "HTTP/1.3", "HTTP/1.4", "HTTP/2.0"], "HTTP/1.1", $message);
		$error = @explode("HTTP/1.1", $message)[1];
		if (is_null($error)) return null;
		return $error;
	}
}


