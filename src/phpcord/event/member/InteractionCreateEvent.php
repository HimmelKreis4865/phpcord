<?php

namespace phpcord\event\member;

use phpcord\interaction\Interaction;

class InteractionCreateEvent extends MemberEvent {
	
	public function __construct(protected Interaction $interaction) {
		parent::__construct($this->getInteraction()->getMember());
	}
	
	/**
	 * @return Interaction
	 */
	public function getInteraction(): Interaction {
		return $this->interaction;
	}
}