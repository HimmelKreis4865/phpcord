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

namespace phpcord\channel\types\dm;

use phpcord\user\User;
use phpcord\utils\Factory;

class GroupDMChannel extends DMChannel {
	
	/**
	 * @param int $id
	 * @param int $lastMessageId
	 * @param User[] $recipients
	 * @param int $ownerId
	 */
	public function __construct(int $id, int $lastMessageId, array $recipients, private int $ownerId) {
		parent::__construct($id, $lastMessageId, $recipients);
	}
	
	/**
	 * @return int
	 */
	public function getOwnerId(): int {
		return $this->ownerId;
	}
	
	public function getOwner(): ?User {
		return $this->getRecipients()->get($this->getOwnerId());
	}
	
	public static function fromArray(array $array): ?self {
		return new GroupDMChannel($array['id'], $array['last_message_id'], Factory::createUserArray($array['recipients']), $array['owner_id']);
	}
	
	public function replaceBy(DMChannel $channel): void {
		if (!$channel instanceof GroupDMChannel) return;
		$this->ownerId = $channel->getOwnerId();
		parent::replaceBy($channel);
	}
}