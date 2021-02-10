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
	/** @var string $guild_id */
	public $guild_id;

	/** @var string $id */
	public $id;

	/** @var string|null $title */
	public $title = null;

	/** @var array $fields */
	public $fields = [];

	/** @var string|null $description */
	public $description = null;

	/** @var string|null $url */
	public $url = null;

	/** @var array|null $thumbnail */
	public $thumbnail = null;

	/** @var int $color */
	public $color = 0;

	/** @var string|null $timestamp */
	public $timestamp = null;

	/** @var array|null $footer */
	public $footer = null;

	/** @var array|null $image */
	public $image = null;

	/** @var array|null $video */
	public $video = null;

	/** @var array|null $provider */
	public $provider = null;

	/** @var array|null $author */
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
	 * Returns the author of the embed, must not be the author of the message
	 *
	 * @api
	 *
	 * @return array|null
	 */
	public function getAuthor(): ?array {
		return $this->author;
	}

	/**
	 * Returns the decimal color code
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getColor(): int {
		return $this->color;
	}

	/**
	 * Returns an array with all fields
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getFields(): array {
		return $this->fields;
	}

	/**
	 * Returns a Footer "object", array structure
	 *
	 * @api
	 *
	 * @return array|null
	 */
	public function getFooter(): ?array {
		return $this->footer;
	}

	/**
	 * Returns the GuildID of the embed
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * Returns the MessageID of the embed
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns an image "object", array structure
	 *
	 * @api
	 *
	 * @return array|null
	 */
	public function getImage(): ?array {
		return $this->image;
	}

	/**
	 * Returns an provider "object", array structure
	 *
	 * @api
	 *
	 * @return array|null
	 */
	public function getProvider(): ?array {
		return $this->provider;
	}
	
	/**
	 * Returns a Thumbnail "object", array structure
	 *
	 * @api
	 *
	 * @return array|null
	 */
	public function getThumbnail(): ?array {
		return $this->thumbnail;
	}

	/**
	 * Returns the timestamp of the embed content
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getTimestamp(): ?string {
		return $this->timestamp;
	}

	/**
	 * Returns the title of the embed
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getTitle(): ?string {
		return $this->title;
	}

	/**
	 * Returns the url of the embed
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getUrl(): ?string {
		return $this->url;
	}

	/**
	 * Returns a video "object", array structure
	 *
	 * @api
	 *
	 * @return array|null
	 */
	public function getVideo(): ?array {
		return $this->video;
	}

	/**
	 * Returns the description of the embed
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
}