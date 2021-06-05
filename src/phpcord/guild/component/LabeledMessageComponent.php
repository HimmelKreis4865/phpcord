<?php

namespace phpcord\guild\component;

use function array_merge;

abstract class LabeledMessageComponent extends MessageComponent {
	
	/** @var $label */
	protected $label;
	
	/**
	 * LabeledMessageComponent constructor.
	 *
	 * @param int $type
	 * @param string $label
	 */
	public function __construct(int $type, string $label) {
		parent::__construct($type);
		$this->label = $label;
	}
	
	public function encode(): array {
		return array_merge(parent::encode(), ["label" => $this->label]);
	}
	
	public function canBeStacked(): bool {
		return true;
	}
}