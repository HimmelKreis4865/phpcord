<?php

namespace phpcord\channel;

use phpcord\channel\embed\MessageEmbed;

class TextMessage implements Sendable {
	private $data = [];

	public function __construct(string $content) {
		$this->data["content"] = $content;
	}

	public function setTTS(bool $value): self {
		$this->data["tts"] = $value;
		return $this;
	}

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

	public function getJsonData(): array {
		return $this->data;
	}
}


