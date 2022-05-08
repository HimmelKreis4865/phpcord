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

namespace phpcord\message\component;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use phpcord\event\interaction\ButtonPressEvent;
use phpcord\interaction\Interaction;
use phpcord\message\PartialEmoji;
use phpcord\utils\enum\EnumParameterHandle;
use phpcord\utils\enum\EnumToolsTrait;
use function array_pop;
use function array_shift;

/**
 * $label The text that appears on the button
 * $customId an identifier for interaction response
 * $emoji The emoji added to the button
 * $disabled if set to true, the button will be unable to click
 *
 * @method static Button PRIMARY(?string $label, string $customId, ?PartialEmoji $emoji = null)
 * @method static Button SECONDARY(?string $label, string $customId, ?PartialEmoji $emoji = null)
 * @method static Button SUCCESS(?string $label, string $customId, ?PartialEmoji $emoji = null)
 * @method static Button DANGER(?string $label, string $customId, ?PartialEmoji $emoji = null)
 * @method static Button LINK(?string $label, string $url, ?PartialEmoji $emoji = null)
 */
class Button implements IChildComponent {
	use EnumToolsTrait;
	
	private bool $disabled = false;
	
	/**
	 * @param int $style
	 * @param string|null $label
	 * @param PartialEmoji|null $emoji
	 * @param string|null $customId
	 * @param string|null $url
	 */
	private function __construct(private int $style, private ?string $label, private ?PartialEmoji $emoji, private ?string $customId = null, private ?string $url = null) { }
	
	/**
	 * @return Button
	 */
	public function disable(): Button {
		$this->disabled = true;
		return $this;
	}
	
	protected static function make(): void {
		self::register('PRIMARY', new EnumParameterHandle(fn(mixed ...$parameter) => new Button(1, array_shift($parameter), @$parameter[1], array_shift($parameter), null, array_pop($parameter) ?? false)));
		self::register('SECONDARY', new EnumParameterHandle(fn(mixed ...$parameter) => new Button(2, array_shift($parameter), @$parameter[1], array_shift($parameter), null, array_pop($parameter) ?? false)));
		self::register('SUCCESS', new EnumParameterHandle(fn(mixed ...$parameter) => new Button(3, array_shift($parameter), @$parameter[1], array_shift($parameter), null, array_pop($parameter) ?? false)));
		self::register('DANGER', new EnumParameterHandle(fn(mixed ...$parameter) => new Button(4, array_shift($parameter), @$parameter[1], array_shift($parameter), null, array_pop($parameter) ?? false)));
		self::register('LINK', new EnumParameterHandle(fn(mixed ...$parameter) => new Button(5, array_shift($parameter), @$parameter[1], null, array_shift($parameter), array_pop($parameter) ?? false)));
	}
	
	public static function onInteract(Interaction $interaction): void {
		(new ButtonPressEvent($interaction, $interaction->getData()->getCustomId()))->call();
	}
	
	#[ArrayShape(['type' => "int", 'style' => "int", 'label' => "null|string", 'emoji' => "null|\phpcord\message\PartialEmoji", 'custom_id' => "null|string", 'url' => "null|string", 'disabled' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'type' => ComponentTypes::BUTTON(),
			'style' => $this->style,
			'label' => $this->label,
			'emoji' => $this->emoji,
			'custom_id' => $this->customId,
			'url' => $this->url,
			'disabled' => $this->disabled
		];
	}
}