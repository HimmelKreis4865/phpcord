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

namespace phpcord\message\sendable;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\message\sendable\parts\EmbedAuthor;
use phpcord\message\sendable\parts\EmbedField;
use phpcord\message\sendable\parts\EmbedFooter;
use phpcord\message\sendable\parts\EmbedMedia;
use phpcord\message\sendable\parts\EmbedProvider;
use phpcord\utils\Collection;
use phpcord\utils\Color;
use phpcord\utils\Timestamp;
use function is_int;

class Embed implements JsonSerializable {

	/** @var string|null $title */
	private ?string $title = null;
	
	/** @var string|null $description */
	private ?string $description = null;
	
	/** @var string|null $url */
	private ?string $url = null;
	
	/** @var Timestamp|null $timestamp */
	private ?Timestamp $timestamp = null;
	
	/** @var Color|null $color */
	private ?Color $color = null;
	
	/** @var EmbedFooter|null $footer */
	private ?EmbedFooter $footer = null;
	
	/** @var EmbedMedia|null $image */
	private ?EmbedMedia $image = null;
	
	/** @var EmbedMedia|null $thumbnail */
	private ?EmbedMedia $thumbnail = null;
	
	/** @var EmbedMedia|null $video */
	private ?EmbedMedia $video = null;
	
	/** @var EmbedProvider|null $provider */
	private ?EmbedProvider $provider = null;
	
	/** @var EmbedAuthor|null $author */
	private ?EmbedAuthor $author = null;
	
	/**
	 * @var Collection $fields
	 * @phpstan-var	Collection<EmbedField>
	 */
	private Collection $fields;
	
	public function __construct() {
		$this->fields = new Collection();
	}
	
	public static function new(): Embed {
		return new Embed();
	}
	
	public function addField(EmbedField $field): Embed {
		$this->fields->set($field->getName(), $field);
		return $this;
	}
	
	/**
	 * @param string $title
	 *
	 * @return Embed
	 */
	public function setTitle(string $title): Embed {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @param string $description
	 *
	 * @return Embed
	 */
	public function setDescription(string $description): Embed {
		$this->description = $description;
		return $this;
	}
	
	/**
	 * @param EmbedAuthor $author
	 *
	 * @return Embed
	 */
	public function setAuthor(EmbedAuthor $author): Embed {
		$this->author = $author;
		return $this;
	}
	
	/**
	 * @param Color $color
	 *
	 * @return Embed
	 */
	public function setColor(Color $color): Embed {
		$this->color = $color;
		return $this;
	}
	
	/**
	 * @param EmbedFooter $footer
	 *
	 * @return Embed
	 */
	public function setFooter(EmbedFooter $footer): Embed {
		$this->footer = $footer;
		return $this;
	}
	
	/**
	 * @param EmbedMedia $image
	 *
	 * @return Embed
	 */
	public function setImage(EmbedMedia $image): Embed {
		$this->image = $image;
		return $this;
	}
	
	/**
	 * @param EmbedProvider $provider
	 *
	 * @return Embed
	 */
	public function setProvider(EmbedProvider $provider): Embed {
		$this->provider = $provider;
		return $this;
	}
	
	/**
	 * @param EmbedMedia $thumbnail
	 *
	 * @return Embed
	 */
	public function setThumbnail(EmbedMedia $thumbnail): Embed {
		$this->thumbnail = $thumbnail;
		return $this;
	}
	
	/**
	 * @param Timestamp|string|int $timestamp
	 *
	 * @return Embed
	 */
	public function setTimestamp(Timestamp|string|int $timestamp): Embed {
		$this->timestamp = ($timestamp instanceof Timestamp ? $timestamp : (is_int($timestamp) ? Timestamp::fromTimestamp($timestamp) : Timestamp::fromDate($timestamp)));
		if (!$this->timestamp) throw new InvalidArgumentException('Timestamp ' . $timestamp . 'is invalid!');
		return $this;
	}
	
	/**
	 * @param string $url
	 *
	 * @return Embed
	 */
	public function setUrl(string $url): Embed {
		$this->url = $url;
		return $this;
	}
	
	/**
	 * @param EmbedMedia $video
	 *
	 * @return Embed
	 */
	public function setVideo(EmbedMedia $video): Embed {
		$this->video = $video;
		return $this;
	}
	
	#[Pure] public function jsonSerialize(): array {
		return [
			'title' => $this->title,
			'description' => $this->description,
			'url' => $this->url,
			'timestamp' => $this->timestamp,
			'color' => $this->color?->dec(),
			'footer' => $this->footer,
			'image' => $this->image,
			'thumbnail' => $this->thumbnail,
			'video' => $this->video,
			'provider' => $this->provider,
			'author' => $this->author,
			'fields' => array_values($this->fields->asArray())
		];
	}
}