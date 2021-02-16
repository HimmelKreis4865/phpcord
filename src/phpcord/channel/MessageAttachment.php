<?php

namespace phpcord\channel;

use RuntimeException;
use function file_get_contents;
use function substr;

class MessageAttachment implements Sendable {
	
	/** @var string the boundary needed for multipart formdata */
	protected const BOUNDARY = "----PHPCordFileBoundary";
	
	/** @var array $fields */
	protected $fields = [
		"tts" => [
			"content" => false
		],
		"content" => [
			"content" => ""
		]
	];
	
	/**
	 * Sets a file to the attachment
	 *
	 * @api
	 *
	 * @param string $filename
	 * @param string|null $content can be left out, system will try to find the file then
	 */
	public function setFile(string $filename, string $content = null) {
		if ($content === null) {
			$content = @file_get_contents($filename);
			if ($content === null) throw new RuntimeException("Could not get filecontent for file $filename! Please supply a valid path!");
		}
		$this->fields["file"] = [
			"content" => $content,
			"filename" => $filename
		];
	}
	
	/**
	 * Changes the message content of the attachment and gives you the option to turn TTS on
	 *
	 * @api
	 *
	 * @param string $content
	 *
	 * @param bool $tts
	 */
	public function setContent(string $content, bool $tts = false) {
		$this->fields["content"] = [
			"content" => $content
		];
		$this->fields["tts"] = [
			"content" => $tts ? 'true' : 'false'
		];
	}
	
	/**
	 * Returns the proper formatted multipart formdata ready for sending
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getFormattedData(): string {
		$body = '';
		
		foreach ($this->fields as $name => $field) {
			$body .= self::BOUNDARY . "\n";
			$body .= "Content-Disposition: form-data; name={$name}";
			
			if (isset($field['filename'])) {
				$body .= "; filename={$field['filename']}";
			}
			$body .= "\n";
			
			if (isset($field['headers'])) {
				foreach ($field['headers'] as $header => $value) {
					$body .= $header . ': ' . $value."\n";
				}
			}
			$body .= "\n" . $field['content'] . "\n";
		}
		$body .= self::BOUNDARY . "--\n";
		
		return $body;
	}
	
	/**
	 * Returns the content type, multipart/form-data with some specifications here
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getContentType(): string {
		return 'multipart/form-data; boundary=' . substr(self::BOUNDARY, 2);
	}
}