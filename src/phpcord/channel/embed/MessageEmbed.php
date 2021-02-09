<?php

namespace phpcord\channel\embed;

use phpcord\channel\Sendable;
use phpcord\utils\ArrayUtils;

class MessageEmbed implements Sendable {
	/** @var array $data */
	private $data = [];

	public function setTitle(string $title): self {
		$this->data["title"] = $title;
		return $this;
	}

	public function setDescription(string $description): self {
		$this->data["description"] = $description;
		return $this;
	}

	public function setURL(string $url): self {
		$this->data["url"] = $url;
		return $this;
	}

	public function setColor($color): self {
		$this->data["color"] = ColorUtils::createFromCustomData($color)->decimal;
		return $this;
	}

	/**
	 * @warning We are not validating this timestamp yet! You might get some errors here
	 *
	 * @todo Add validation
	 *
	 * @param string $timestamp
	 *
	 * @return $this
	 */
	public function setTimestamp(string $timestamp): self {
		$this->data["timestamp"] = ColorUtils::createFromCustomData($timestamp);
		return $this;
	}

	public function setFooter(string $text, ?string $iconUrl = null, ?string $proxyIconURL = null): self {
		$this->data["footer"] = ["text" => $text, "icon_url" => $iconUrl, "proxy_icon_url" => $proxyIconURL];
		return $this;
	}

	public function setImage(string $url, ?string $proxy_url = null, int $width = null, int $height = null): self {
		$this->data["image"] = ["url" => $url, "proxy_url" => $proxy_url, "width" => $width, "height" => $height];
		return $this;
	}

	public function setThumbnail(string $url, ?string $proxy_url = null, int $width = null, int $height = null): self {
		$this->data["thumbnail"] = ["url" => $url, "proxy_url" => $proxy_url, "width" => $width, "height" => $height];
		return $this;
	}

	public function setVideo(string $url, int $width = null, int $height = null): self {
		$this->data["video"] = ["url" => $url, "width" => $width, "height" => $height];
		return $this;
	}

	public function setProvider(string $name, ?string $url = null): self {
		$this->data["provider"] = ["name" => $name, "url" => $url];
		return $this;
	}

	public function setAuthor(string $name, ?string $url = null, ?string $iconUrl = null, ?string $proxyIconUrl = null): self {
		$this->data["author"] = ["name" => $name, "url" => $url, "icon_url" => $iconUrl, "proxy_icon_url", $proxyIconUrl];
		return $this;
	}

	public function addField(string $name, string $value, bool $inline = false): self {
		$this->data["fields"][] = ["name" => $name, "value" => $value, "inline" => $inline];
		return $this;
	}

	public function getJsonData(): array {
		return ["embed" => ArrayUtils::filterNullRecursive($this->data)];
	}
}


