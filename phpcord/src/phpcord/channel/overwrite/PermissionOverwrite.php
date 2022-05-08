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

namespace phpcord\channel\overwrite;

use JetBrains\PhpStorm\Pure;
use phpcord\async\completable\Completable;
use phpcord\channel\GuildChannel;
use phpcord\guild\permissible\data\DualPermissionData;
use phpcord\guild\permissible\data\IPermissionData;
use phpcord\guild\permissible\PermissionUpdateTrait;

abstract class PermissionOverwrite {
	use PermissionUpdateTrait;
	
	/** @var DualPermissionData $permissionData */
	private DualPermissionData $permissionData;
	
	/**
	 * @param GuildChannel $channel
	 * @param int $allowBit
	 * @param int $denyBit
	 */
	#[Pure] public function __construct(private GuildChannel $channel, int $allowBit, int $denyBit) {
		$this->permissionData = new DualPermissionData($allowBit, $denyBit);
	}
	
	/**
	 * @return GuildChannel
	 */
	public function getChannel(): GuildChannel {
		return $this->channel;
	}
	
	/**
	 * @return DualPermissionData
	 */
	public function getPermissionData(): IPermissionData {
		return $this->permissionData;
	}
	
	/**
	 * @return Completable
	 */
	protected function syncToDiscord(): Completable {
		return $this->channel->triggerUpdate();
	}
	
	/**
	 * @param GuildChannel $channel
	 * @param array $array
	 *
	 * @return static|null
	 */
	abstract public static function fromArray(GuildChannel $channel, array $array): ?self;
}