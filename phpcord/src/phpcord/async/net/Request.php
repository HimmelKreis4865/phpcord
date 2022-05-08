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

namespace phpcord\async\net;

use phpcord\async\completable\Completable;
use phpcord\exception\InternetException;
use phpcord\logger\Logger;
use phpcord\utils\Utils;
use RuntimeException;
use function curl_error;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function curl_setopt_array;
use function explode;
use function http_build_query;
use function is_string;
use function json_encode;
use function str_starts_with;
use function substr;
use function var_dump;
use const CURLINFO_HEADER_SIZE;
use const CURLINFO_RESPONSE_CODE;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_HEADER;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYHOST;
use const CURLOPT_SSL_VERIFYPEER;

final class Request {
	
	private static function create(string $url, string $requestMethod, array $options = [], array $headers = []): Completable {
		return Completable::async(function($url, $options, $headers, $requestMethod): Response {
			$ch = curl_init($url);
			curl_setopt_array($ch, ([
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HEADER => true,
					CURLOPT_HTTPHEADER => Utils::parseHeaders($headers)
				] + $options));
			
			$result = curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
			if (!$result) throw new InternetException('HTTP Request to ' . $requestMethod . ' ' . $url . ' failed: ' . curl_error($ch), $code ?? 0);
			
			$header = explode("\r\n", substr($result, 0, ($len = curl_getinfo($ch, CURLINFO_HEADER_SIZE))));
			$body = substr($result, $len);
			
			if (str_starts_with($code, '4')) {
				(new Logger('Internet'))->warning('HTTP request to ' . $requestMethod . ' ' . $url . ' failed (' . $code . '): ' . $body);
				throw new InternetException('Request to ' . $url . ' failed (' . $code . '): ' . $body);
			}
			
			return new Response($code, $body, $header);
		}, $url, $options, $headers, $requestMethod);
	}
	
	public static function get(string $url, array $headers = []): Completable {
		return self::create($url, 'GET', [], $headers);
	}
	
	public static function post(string $url, array|string $post_fields, array $headers = []): Completable {
		return self::create($url, 'POST', [
			CURLOPT_POSTFIELDS => (is_string($post_fields) ? $post_fields : json_encode($post_fields)),
			CURLOPT_POST => true
		], $headers);
	}
	
	public static function patch(string $url, array|string $post_fields, array $headers = []): Completable {
		return self::create($url, 'PATCH', [
			CURLOPT_POSTFIELDS => (is_string($post_fields) ? $post_fields : json_encode($post_fields)),
			CURLOPT_CUSTOMREQUEST => 'PATCH',
		], $headers);
	}
	
	public static function put(string $url, array|string $post_fields, array $headers = []): Completable {
		return self::create($url, 'PUT', [
			CURLOPT_POSTFIELDS => (is_string($post_fields) ? $post_fields : json_encode($post_fields)),
			CURLOPT_CUSTOMREQUEST => 'PUT',
		], $headers);
	}
	
	public static function delete(string $url, array $headers = []): Completable {
		return self::create($url, 'PATCH', [
			CURLOPT_CUSTOMREQUEST => 'DELETE',
		], $headers);
	}
}