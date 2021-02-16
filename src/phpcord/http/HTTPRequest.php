<?php

declare(strict_types=1);

namespace phpcord\http;

use phpcord\Discord;
use phpcord\utils\MainLogger;
use function array_merge;
use function count;
use function file_get_contents;
use function strpos;
use function stream_context_create;
use function urlencode;

class HTTPRequest {
	
	/** @var string type for post requests */
	public const REQUEST_POST = "POST";
	
	/** @var string type for get request */
	public const REQUEST_GET = "GET";
	
	/** @var string type for put requests: mostly used for discord */
	public const REQUEST_PUT = "PUT";
	
	/** @var string type for delete requests: mostly used for discord */
	public const REQUEST_DELETE = "DELETE";
	
	/** @var string type for patching requests: mostly used for modifications */
	public const REQUEST_PATCH = "PATCH";
	
	/** @var array $http */
	public $http = [];
	
	/** @var string $url */
	public $url;

	/**
	 * HTTPRequest constructor.
	 *
	 * @param string $url
	 * @param string $requestType
	 */
	public function __construct(string $url, string $requestType = self::REQUEST_POST) {
		$this->url = $url;
		$this->http["method"] = $requestType;
	}
	
	/**
	 * Adds a Header to http request
	 *
	 * @api
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function addHeader(string $key, string $value) {
		if (!isset($this->http["header"])) $this->http["header"] = "";
		$this->http["header"] .= "$key: $value\r\n";
	}
	
	/**
	 * Sets a raw get peace to the url, e.g: https://example.com?test=value&key=test
	 *
	 * @api
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function addRawGet(string $key, string $value) {
		if (strpos($this->url, "?") === false) {
			$this->url .= "?" . $key . "=" . urlencode($value);
			return;
		}
		$this->url .= "&" . $key . "=" . $value;
	}
	
	/**
	 * Add custom http data, mostly used in post requests
	 *
	 * @api
	 *
	 * @param string $key
	 * @param $data
	 */
	public function addHTTPData(string $key, $data) {
		$this->http[$key] = $data;
	}
	
	/**
	 * Changes the content type directly, can be done manually too
	 *
	 * @api
	 *
	 * @param string $type
	 */
	public function setContentType(string $type = "application/json") {
		$this->addHeader("Content-Type", $type);
	}
	
	/**
	 * Submits the request and certain results
	 *3
	 * @warning Throws an unhandled exception on failure
	 *
	 * @api
	 *
	 * @return string|null|bool
	 */
	public function submit() {
		$array = [
			"http" => $this->http
		];
		if (count(Discord::getInstance()->sslSettings) > 0) $array = array_merge($array, ["ssl" => Discord::getInstance()->sslSettings]);
		$context = stream_context_create($array);
		return file_get_contents($this->url, false, $context);
	}
}


