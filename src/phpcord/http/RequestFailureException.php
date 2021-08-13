<?php

namespace phpcord\http;

use Exception;

class RequestFailureException extends Exception {
	
	/** @var int $response_code */
	protected $response_code;
	
	/** @var string $error_string */
	protected $error_string;
	
	/**
	 * RequestFailure constructor.
	 *
	 * @param string $error_string
	 * @param int $response_code
	 */
	public function __construct(string $error_string, int $response_code = 404) {
		$this->response_code = $response_code;
		$this->error_string = $error_string;
		parent::__construct("[$response_code] $error_string");
	}
	
	/**
	 * @return int
	 */
	public function getResponseCode(): int {
		return $this->response_code;
	}
	
	/**
	 * @return string
	 */
	public function getErrorString(): string {
		return $this->error_string;
	}
}