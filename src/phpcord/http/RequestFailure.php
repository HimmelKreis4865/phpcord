<?php

namespace phpcord\http;

class RequestFailure {
	
	/** @var int $response_code */
	protected $response_code;
	
	/** @var string $error_string */
	protected $error_string;
	
	/**
	 * RequestFailure constructor.
	 *
	 * @param int $response_code
	 * @param string $error_string
	 */
	public function __construct(int $response_code, string $error_string) {
		$this->response_code = $response_code;
		$this->error_string = $error_string;
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