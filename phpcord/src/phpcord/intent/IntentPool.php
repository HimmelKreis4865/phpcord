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

namespace phpcord\intent;

use phpcord\intent\impl\ChannelHandler;
use phpcord\intent\impl\ClientHandler;
use phpcord\intent\impl\GuildHandler;
use phpcord\intent\impl\InteractionHandler;
use phpcord\intent\impl\MemberHandler;
use phpcord\intent\impl\MessageHandler;
use phpcord\intent\impl\RoleHandler;
use phpcord\intent\impl\VoiceHandler;
use phpcord\runtime\network\packet\MessageBuffer;
use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use function var_dump;

final class IntentPool {
	use SingletonTrait;
	
	/**
	 * @var Collection $intentHandlers
	 * @phpstan-var	Collection<IntentHandler>
	 */
	private Collection $intentHandlers;
	
	public function __construct() {
		$this->intentHandlers = new Collection();
		
		$this->register(new ClientHandler(), Intents::READY());
		$this->register(new GuildHandler(), Intents::GUILD_CREATE(), Intents::GUILD_UPDATE(), Intents::GUILD_DELETE(), Intents::GUILD_EMOJIS_UPDATE(), Intents::GUILD_BAN_ADD(), Intents::GUILD_BAN_REMOVE());
		$this->register(new MemberHandler(), Intents::GUILD_MEMBER_ADD(), Intents::GUILD_MEMBER_REMOVE(), Intents::GUILD_MEMBER_UPDATE());
		$this->register(new MessageHandler(), Intents::MESSAGE_CREATE(), Intents::MESSAGE_UPDATE(), Intents::MESSAGE_DELETE());
		$this->register(new VoiceHandler(), Intents::VOICE_STATE_UPDATE(), Intents::VOICE_SERVER_UPDATE());
		$this->register(new InteractionHandler(), Intents::INTERACTION_CREATE());
		$this->register(new ChannelHandler(), Intents::CHANNEL_CREATE(), Intents::CHANNEL_UPDATE(), Intents::CHANNEL_DELETE(), Intents::CHANNEL_PINS_UPDATE(), Intents::TYPING_START());
		$this->register(new RoleHandler(), Intents::GUILD_ROLE_CREATE(), Intents::GUILD_ROLE_UPDATE(), Intents::GUILD_ROLE_DELETE());
	}
	
	/**
	 * Registers an intent handler
	 *
	 * @internal
	 *
	 * @param IntentHandler $handler
	 * @param string ...$intents
	 *
	 * @return void
	 */
	public function register(IntentHandler $handler, string ...$intents): void {
		foreach ($intents as $intent) $this->intentHandlers->set($intent, $handler);
	}
	
	public function dispatch(MessageBuffer $buffer): void {
		$buffer = $buffer->packetIntent();
		$this->intentHandlers->get($buffer->name())?->handle($buffer);
	}
}