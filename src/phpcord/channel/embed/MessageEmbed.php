<?php

namespace phpcord\channel\embed;

use phpcord\channel\embed\components\RGB;
use phpcord\channel\Sendable;
use phpcord\utils\ArrayUtils;
use function var_dump;

class MessageEmbed implements Sendable {
	
	/** @var array $data */
	public $data = [];
	
	/**
	 * Sets the title of the embed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $title
	 *
	 * @return MessageEmbed
	 */
	public function setTitle(string $title): self {
		$this->data["title"] = $title;
		return $this;
	}
	/**
	 * Sets the description of the embed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $description
	 *
	 * @return MessageEmbed
	 */
	public function setDescription(string $description): self {
		$this->data["description"] = $description;
		return $this;
	}
	/**
	 * Sets the url of the embed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $url
	 *
	 * @return MessageEmbed
	 */
	public function setURL(string $url): self {
		$this->data["url"] = $url;
		return $this;
	}
	
	/**
	 * Sets the color of an embed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param ColorUtils|RGB|string|int|array $color
	 *
	 * @return MessageEmbed
	 */
	public function setColor($color): self {
		$this->data["color"] = ColorUtils::createFromCustomData($color)->decimal;
		return $this;
	}

	/**
	 * Changes the timestamp to the given timestamp
	 *
	 * @warning We are not validating this timestamp yet! You might get some errors here
	 *
	 * @todo Add validation
	 *
	 * @param string $timestamp
	 *
	 * @return MessageEmbed
	 */
	public function setTimestamp(string $timestamp): self {
		$this->data["timestamp"] = $timestamp;
		return $this;
	}
	
	/**
	 * Changes the footer of an embed
	 * Optional parameters can be left out, text is needed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $text
	 * @param string|null $iconUrl
	 * @param string|null $proxyIconURL
	 *
	 * @return MessageEmbed
	 */
	public function setFooter(string $text, ?string $iconUrl = null, ?string $proxyIconURL = null): self {
		$this->data["footer"] = ["text" => $text, "icon_url" => $iconUrl, "proxy_icon_url" => $proxyIconURL];
		return $this;
	}
	
	/**
	 * Sets the image of the embed
	 * This image is shown as an extra image you're also able to send with normal discord application
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $url
	 * @param string|null $proxy_url
	 * @param int|null $width
	 * @param int|null $height
	 *
	 * @return MessageEmbed
	 */
	public function setImage(string $url, ?string $proxy_url = null, int $width = null, int $height = null): self {
		$this->data["image"] = ["url" => $url, "proxy_url" => $proxy_url, "width" => $width, "height" => $height];
		return $this;
	}
	
	/**
	 * Changes the thumbnail shown in the top left of the embed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $url
	 * @param string|null $proxy_url
	 * @param int|null $width
	 * @param int|null $height
	 *
	 * @return MessageEmbed
	 */
	public function setThumbnail(string $url, ?string $proxy_url = null, int $width = null, int $height = null): self {
		$this->data["thumbnail"] = ["url" => $url, "proxy_url" => $proxy_url, "width" => $width, "height" => $height];
		var_dump("thumbnail set");
		return $this;
	}
	
	/**
	 * Adds a Video URL to the embed
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $url
	 * @param int|null $width
	 * @param int|null $height
	 *
	 * @return MessageEmbed
	 */
	public function setVideo(string $url, int $width = null, int $height = null): self {
		$this->data["video"] = ["url" => $url, "width" => $width, "height" => $height];
		return $this;
	}
	
	/**
	 * @todo What is that for?
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $name
	 * @param string|null $url
	 *
	 * @return MessageEmbed
	 */
	public function setProvider(string $name, ?string $url = null): self {
		$this->data["provider"] = ["name" => $name, "url" => $url];
		return $this;
	}
	
	/**
	 * Changes the author of the embed
	 * @todo What is that for?
	 * Can only be overwritten, not modified
	 *
	 * @api
	 *
	 * @param string $name
	 * @param string|null $url
	 * @param string|null $iconUrl
	 * @param string|null $proxyIconUrl
	 *
	 * @return MessageEmbed
	 */
	public function setAuthor(string $name, ?string $url = null, ?string $iconUrl = null, ?string $proxyIconUrl = null): self {
		$this->data["author"] = ["name" => $name, "url" => $url, "icon_url" => $iconUrl, "proxy_icon_url", $proxyIconUrl];
		return $this;
	}
	
	/**
	 * Adds a field to the embed
	 * Can be modified
	 *
	 * @api
	 *
	 * @param string $name
	 * @param string $value
	 * @param bool $inline
	 *
	 * @return $this
	 */
	public function addField(string $name, string $value, bool $inline = false): self {
		$this->data["fields"][] = ["name" => $name, "value" => $value, "inline" => $inline];
		return $this;
	}
	
	/**
	 * Returns the whole message as array
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function getFormattedData(): string {
		return json_encode(["embed" => ArrayUtils::filterNullRecursive($this->data)]);
	}
	
	public function getContentType(): string {
		return "application/json";
	}
}