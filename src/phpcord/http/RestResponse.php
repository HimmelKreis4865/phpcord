<?php

namespace phpcord\http;

class RestResponse {
	/** @var mixed $raw_data */
	protected $raw_data;

	/** @var bool $failed */
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
		return $this->raw_data;
	}
}