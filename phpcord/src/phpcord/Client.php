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

namespace phpcord;

use phpcord\application\Application;
use phpcord\async\completable\Completable;
use phpcord\channel\types\dm\DMChannel;
use phpcord\guild\components\Invite;
use phpcord\guild\Guild;
use phpcord\http\RestAPI;
use phpcord\interaction\slash\PartialSlashCommand;
use phpcord\runtime\network\Network;
use phpcord\runtime\network\packet\PresencePacket;
use phpcord\user\User;
use phpcord\utils\Collection;
use phpcord\utils\presence\Status;
use phpcord\utils\RtcRegion;
use phpcord\utils\time\TimePeriod;
use phpcord\utils\Timestamp;

final class Client {
	
	/**
	 * @var Collection $guilds
	 * @phpstan-var Collection<Guild>
	 */
	private Collection $guilds;
	
	/**
	 * @var Collection $rtcRegions
	 * @phpstan-var Collection<RtcRegion>
	 */
	private Collection $rtcRegions;
	
	/**
	 * @var Collection $channels
	 * @phpstan-var Collection<DMChannel>
	 */
	private Collection $channels;
	
	/**
	 * @var Timestamp $startup
	 */
	private Timestamp $startup;
	
	/**
	 * @param User $user
	 * @param Application $application
	 */
	public function __construct(private User $user, private Application $application) {
		$this->guilds = new Collection();
		$this->rtcRegions = new Collection();
		$this->channels = new Collection();
		$this->startup = Timestamp::now();
		RestAPI::getInstance()->getVoiceRegions()->then(fn(array $regions) => $this->rtcRegions->fill($regions));
	}
	
	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}
	
	/**
	 * @return Application
	 */
	public function getApplication(): Application {
		return $this->application;
	}
	
	/**
	 * @return Collection<Guild>
	 */
	public function getGuilds(): Collection {
		return $this->guilds;
	}
	
	/**
	 * @return Collection<RtcRegion>
	 */
	public function getRtcRegions(): Collection {
		return $this->rtcRegions;
	}
	
	/**
	 * @return Collection<DMChannel>
	 */
	public function getChannels(): Collection {
		return $this->channels;
	}
	
	/**
	 * @param int $id
	 *
	 * @return Completable<DMChannel>
	 */
	public function getChannel(int $id): Completable {
		if ($this->getChannels()->contains($id)) {
			$c = $this->getChannels()->get($id);
			if ($c->isFetching()) return $c->newFetchHook();
			return Completable::completed($c);
		}
		return RestAPI::getInstance()->getChannel($id);
	}
	
	public function setStatus(Status $status): void {
		Network::getInstance()->getGateway()->sendPacket(new PresencePacket($status));
	}
	
	/**
	 * @return Timestamp
	 */
	public function getStartup(): Timestamp {
		return $this->startup;
	}
	
	public function getUptime(): TimePeriod {
		return new TimePeriod(Timestamp::now()->diff($this->startup));
	}
	
	/**
	 * @param string $code
	 * @param bool $withCounts
	 * @param bool $withExpiration
	 *
	 * @return Completable<Invite>
	 */
	public function fetchInvite(string $code, bool $withCounts = true, bool $withExpiration = true): Completable {
		return RestAPI::getInstance()->getInvite($code, $withCounts, $withExpiration);
	}
	
	public function getPing(): int {
		return Network::getInstance()->getGateway()->getPing();
	}
	
	/**
	 * @return Completable<array<PartialSlashCommand>>
	 */
	public function fetchSlashCommands(): Completable {
		return RestAPI::getInstance()->getGlobalSlashCommands($this->getApplication()->getId());
	}
}