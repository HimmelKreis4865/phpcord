<?php

namespace phpcord\guild\component;

abstract class MessageComponent {

	public const TYPE_ACTION_ROW = 1;
	
	public const TYPE_BUTTON = 2;
	
	protected $type;
	
	public function __construct(int $type) {
		$this->type = $type;
	}
	
	public function canBeStacked(): bool {
		return false;
	}
	
	public static function getClassById(int $id): ?string {
		return [ self::TYPE_ACTION_ROW => ActionRow::class, self::TYPE_BUTTON => Button::class ][$id];
	}
	
	public function encode(): array {
		return ["type" => $this->type];
	}
}