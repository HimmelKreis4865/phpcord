<?php

namespace phpcord\guild\component;

use InvalidArgumentException;
use function count;

class ActionRow extends MessageComponent {
	
	/** @var LabeledMessageComponent[] $components */
	protected $components = [];
	
	public const COMPONENT_LIMIT = 5;
	
	public function __construct(array $components = []) {
		parent::__construct(self::TYPE_ACTION_ROW);
		$this->components = array_filter($components, function ($key): bool {
			return ($key instanceof LabeledMessageComponent and $key->canBeStacked());
		});
		if (count($this->components) > self::COMPONENT_LIMIT)
			throw new InvalidArgumentException("An action row cannot contain more than 5 components!");
	}
	
	/**
	 * Adds a component to the list
	 *
	 * @api
	 *
	 * @param LabeledMessageComponent $component
	 */
	public function addComponent(LabeledMessageComponent $component) {
		if (count($this->components) >= self::COMPONENT_LIMIT)
			throw new InvalidArgumentException("An action row cannot contain more than 5 components!");
		if ($component->canBeStacked()) $this->components[] = $component;
	}
	
	public function encode(): array {
		return array_merge(parent::encode(), ["components" => array_map(function(LabeledMessageComponent $component) {
			return $component->encode();
		}, $this->components)]);
	}
	
	public static function fromArray(array $data): ActionRow {
		$row = new ActionRow();
		foreach ($data["components"] ?? [] as $value) {
			if (isset($value["type"]) and ($class = MessageComponent::getClassById($value["type"])) !== null) {
				$component = @$class::fromArray($value);
				if ($component instanceof LabeledMessageComponent) $row->addComponent($component);
			}
		}
		return $row;
	}
}