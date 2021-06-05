<?php

namespace phpcord\guild\component;

use BadMethodCallException;
use phpcord\guild\Emoji;
use function var_dump;

class Button extends LabeledMessageComponent {
	
	/** @var int blue / purple color */
	public const STYLE_PRIMARY = 1;
	
	/** @var int grey color */
	public const STYLE_SECONDARY = 2;
	
	/** @var int green color */
	public const STYLE_SUCCESS = 3;
	
	/** @var int red color */
	public const STYLE_DANGER = 4;
	
	/** @var int gray color with link icon */
	public const STYLE_LINK = 5;
	
	
	/** @var int $style */
	protected $style = self::STYLE_PRIMARY;
	
	/** @var Emoji|null $emoji */
	protected $emoji;
	
	/** @var string|null $customId a custom name that is not shown on the button, see label for that */
	protected $customId = null;
	
	/** @var string|null $url the url the button should refer to */
	protected $url = null;
	
	/** @var bool $disabled */
	protected $disabled = false;
	
	public function __construct(string $label, int $style = self::STYLE_PRIMARY) {
		$this->style = $style;
		parent::__construct(self::TYPE_BUTTON, $label);
	}
	
	public function setCustomId(string $customId): Button {
		if ($this->style === self::STYLE_LINK)
			throw new BadMethodCallException("Cannot set a custom id on a link");
		$this->customId = $customId;
		return $this;
	}
	
	public function setUrl(string $url): Button {
		if ($this->style !== self::STYLE_LINK)
			throw new BadMethodCallException("Cannot set a url on non-linked buttons");
		$this->url = $url;
		return $this;
	}
	
	public function setEmoji(?Emoji $emoji = null): Button {
		$this->emoji = $emoji;
		return $this;
	}
	
	public function setDisabled(bool $disabled = true): Button {
		$this->disabled = $disabled;
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getCustomId(): ?string {
		return $this->customId;
	}
	
	/**
	 * @return Emoji|null
	 */
	public function getEmoji(): ?Emoji {
		return $this->emoji;
	}
	
	/**
	 * @return mixed
	 */
	public function getLabel(): string {
		return $this->label;
	}
	
	/**
	 * @return int
	 */
	public function getStyle(): int {
		return $this->style;
	}
	
	/**
	 * @return string|null
	 */
	public function getUrl(): ?string {
		return $this->url;
	}
	
	
	public function encode(): array {
		$base = array_merge(parent::encode(), ["style" => $this->style, "disabled" => $this->disabled]);
		if ($this->url !== null) $base["url"] = $this->url;
		if ($this->customId !== null) $base["custom_id"] = $this->customId;
		if ($this->emoji !== null) $base["emoji"] = $this->emoji->asArray();
		return $base;
	}
	
	public static function fromArray(array $data): ?Button {
		if (!isset($data["label"])) return null;
		$button = new Button($data["label"], $data["style"] ?? self::STYLE_PRIMARY);
		if (isset($data["disabled"])) $button->setDisabled($data["disabled"]);
		if (isset($data["url"]) and $button->getStyle() === self::STYLE_LINK) $button->setUrl($data["url"]);
		if (isset($data["custom_id"]) and $button->getStyle() !== self::STYLE_LINK) $button->setCustomId($data["custom_id"]);
		if (isset($data["emoji"])) $button->setEmoji(new Emoji($data["emoji"]["name"] ?? "none", @$data["emoji"]["id"], $data["emoji"]["animated"] ?? false));
		return $button;
	}
}