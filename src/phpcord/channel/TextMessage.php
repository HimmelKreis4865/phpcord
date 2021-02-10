<?php

namespace phpcord\channel;

use phpcord\channel\embed\MessageEmbed;

class TextMessage implements Sendable {
	/** @var array $data */
	private $data = [];
	
	/**
	 * TextMessage constructor.
	 *
	 * @param string $content the message as string
	 */
	public function __construct(string $content) {
		$this->data["content"] = $content;
	}
	
	/**
	 * Set TTS to enabled / disabled
	 *
	 * @api
	 *
	 * @param bool $value
	 *
	 * @return $this
	 */
	public function setTTS(bool $value): self {
		$this->data["tts"] = $value;
		return $this;
	}
	
	/**
	 * Adds an embed to the message
	 *
	 * @param MessageEmbed $embed
	 *
	 * @return TextMessage
	 */
	public function addEmbed(MessageEmbed $embed): self {
		// removing the embed data for making an old embed (if existed) invisible
		if (isset($this->data["embed"])) unset($this->data["embed"]);
		$this->data["embed"] = $embed->getJsonData()["embed"];
		return $this;
	}

	/**
	 * @todo: What is that?
	 *
	 * @param $nonce
	 *
	 * @return $this
	 */
	public function setNonce($nonce): self {
		if (is_int($nonce) or is_string($nonce)) $this->data["nonce"] = $nonce;
		return $this;
	}
	
	/**
	 * Returns the JSON data needed for sending via RESTAPI
	 *
	 * @internal
	 *
	 * @return array
	 */
	public function getJsonData(): array {
		return $this->data;
	}
}