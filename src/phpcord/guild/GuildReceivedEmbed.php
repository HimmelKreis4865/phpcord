<?php

namespace phpcord\guild;
/**
 * Class GuildReceivedEmbed
 *
 * This class is a nearly untouched embed that we received, you shouldn't expect too much from it ;D
 *
 * @package DiscordPHP\guild
 */
class GuildReceivedEmbed {
	public $guild_id;

	public $id;

	public $title = null;

	public $fields = [];

	public $description = null;

	public $url = null;

	public $thumbnail = null;

	public $color = 0;

	public $timestamp = null;

	public $footer = null;

	public $image = null;

	public $video = null;

	public $provider = null;

	public $author = null;

	/**
	 * GuildReceivedEmbed constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string|null $title
	 * @param array $fields
	 * @param string|null $description
	 * @param string|null $url
	 * @param array|null $thumbnail
	 * @param int $color
	 * @param string|null $timestamp
	 * @param array|null $footer
	 * @param array|null $image
	 * @param array|null $video
	 * @param array|null $provider
	 * @param array|null $author
	 */
	public function __construct(string $guild_id, string $id, ?string $title = null, array $fields = [], ?string $description = null, ?string $url = null, ?array $thumbnail = null, int $color = 0, ?string $timestamp = null, ?array $footer = null, ?array $image = null, ?array $video = null, ?array $provider = null, ?array $author = null) {
		$this->guild_id = $guild_id;
		$this->id = $id;
		$this->title = $title;
		$this->fields = $fields;
		$this->description = $description;
		$this->url = $url;
		$this->thumbnail = $thumbnail;
		$this->timestamp = $timestamp;
		$this->color = $color;
		$this->footer = $footer;
		$this->image = $image;
		$this->video = $video;
		$this->provider = $provider;
		$this->author = $author;
	}

	/**
	 * @return array|null
	 */
	public function getAuthor(): ?array {
		return $this->author;
	}

	/**
	 * @return int
	 */
	public function getColor(): int {
		return $this->color;
	}

	/**
	 * @return array
	 */
	public function getFields(): array {
		return $this->fields;
	}

	/**
	 * @return array|null
	 */
	public function getFooter(): ?array {
		return $this->footer;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return array|null
	 */
	public function getImage(): ?array {
		return $this->image;
	}

	/**
	 * @return array|null
	 */
	public function getProvider(): ?array {
		return $this->provider;
	}

	/**
	 * @return array|null
	 */
	public function getThumbnail(): ?array {
		return $this->thumbnail;
	}

	/**
	 * @return string|null
	 */
	public function getTimestamp(): ?string {
		return $this->timestamp;
	}

	/**
	 * @return string|null
	 */
	public function getTitle(): ?string {
		return $this->title;
	}

	/**
	 * @return string|null
	 */
	public function getUrl(): ?string {
		return $this->url;
	}

	/**
	 * @return array|null
	 */
	public function getVideo(): ?array {
		return $this->video;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
}


