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

namespace phpcord\interaction\component;

use JetBrains\PhpStorm\ArrayShape;
use phpcord\interaction\Interaction;
use phpcord\message\component\ComponentTypes;
use phpcord\utils\enum\EnumParameterHandle;
use phpcord\utils\enum\EnumTrait;

/**
 * A one line text window:
 * @method static TextInput SHORT(string $customId, string $label, int $minLength = 0, int $maxLength = 4000, ?string $defaultValue = null, ?string $placeholder = null, bool $required = true)
 *
 * A multi line text window:
 * @method static TextInput PARAGRAPH(string $customId, string $label, int $minLength = 0, int $maxLength = 4000, ?string $defaultValue = null, ?string $placeholder = null, bool $required = true)
 */
class TextInput implements IModalComponent {
	use EnumTrait;
	
	/**
	 * @param int $style
	 * @param string $customId
	 * @param string $label
	 * @param int $minLength
	 * @param int $maxLength
	 * @param string|null $defaultValue
	 * @param string|null $placeholder
	 * @param bool $required
	 */
	protected function __construct(private int $style, private string $customId, private string $label, private int $minLength = 0, private int $maxLength = 4000, private ?string $defaultValue = null, private ?string $placeholder = null, private bool $required = true) { }
	
	protected static function make(): void {
		self::register('SHORT', new EnumParameterHandle(fn(mixed ...$parameter) => new TextInput(1, array_shift($parameter), array_shift($parameter), $parameter[0] ?? 0, $parameter[1] ?? 4000, @$parameter[2], @$parameter[3], $parameter[4] ?? true)));
		self::register('PARAGRAPH', new EnumParameterHandle(fn(mixed ...$parameter) => new TextInput(2, array_shift($parameter), array_shift($parameter), $parameter[0] ?? 0, $parameter[1] ?? 4000, @$parameter[2], @$parameter[3], $parameter[4] ?? true)));
	}
	
	#[ArrayShape(['type' => "int", 'custom_id' => "string", 'label' => "string", 'style' => "int", 'min_length' => "int", 'max_length' => "int", 'value' => "null|string", 'placeholder' => "null|string", 'required' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'type' => ComponentTypes::TEXT_INPUT(),
			'custom_id' => $this->customId,
			'label' => $this->label,
			'style' => $this->style,
			'min_length' => $this->minLength,
			'max_length' => $this->maxLength,
			'value' => $this->defaultValue,
			'placeholder' => $this->placeholder,
			'required' => $this->required
		];
	}
	
	public static function onInteract(Interaction $interaction): void {
		// todo: is any action here required?
	}
}