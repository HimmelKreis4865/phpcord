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

use phpcord\async\completable\Completable;
use phpcord\channel\Channel;
use phpcord\channel\helper\TextChannelBaseTrait;
use phpcord\channel\TextChannel;
use phpcord\http\RestAPI;
use phpcord\user\User;
use phpcord\utils\Collection;
use phpcord\utils\Factory;

class DMChannel extends Channel implements TextChannel {
	use TextChannelBaseTrait;
	
	/**
	 * @var Collection $recipients
	 * @phpstan-var Collection<User>
	 */
	private Collection $recipients;
	
	/**
	 * @param int $id
	 * @param int $lastMessageId
	 * @param User[] $recipients
	 */
	public function __construct(int $id, private int $lastMessageId, array $recipients) {
		parent::__construct($id);
		$this->recipients = new Collection($recipients);
	}
	
	public function getLastMessageId(): ?int {
		return $this->lastMessageId;
	}
	
	/**
	 * @return Collection<User>
	 */
	public function getRecipients(): Collection {
		return $this->recipients;
	}
	
	public static function fromArray(array $array): ?self {
		return new DMChannel($array['id'], $array['last_message_id'], Factory::createUserArray($array['recipients']));
	}
	
	/**
	 * @return Completable<DMChannel>
	 */
	protected function internalFetch(): Completable {
		return RestAPI::getInstance()->getChannel($this->getId())->then(fn(DMChannel $channel) => $this->replaceBy($channel));
	}
	
	public function onMessageDelete(int $id): void {
		if ($id === $this->getLastMessageId()) $this->fetch();
	}
	
	public function replaceBy(DMChannel $channel): void {
		$this->lastMessageId = $channel->getLastMessageId();
		$this->recipients = clone $channel->getRecipients();
	}
}