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

namespace phpcord\guild;

use JetBrains\PhpStorm\Pure;
use phpcord\async\completable\Completable;
use phpcord\channel\ChannelBuilder;
use phpcord\channel\GuildChannel;
use phpcord\Discord;
use phpcord\event\voice\VoiceDeafEvent;
use phpcord\event\voice\VoiceJoinEvent;
use phpcord\event\voice\VoiceLeaveEvent;
use phpcord\event\voice\VoiceMoveEvent;
use phpcord\event\voice\VoiceMuteEvent;
use phpcord\event\voice\VoiceUndeafEvent;
use phpcord\event\voice\VoiceUnmuteEvent;
use phpcord\guild\auditlog\AuditLog;
use phpcord\guild\components\Invite;
use phpcord\guild\components\Webhook;
use phpcord\guild\data\MFALevel;
use phpcord\guild\data\NSFWLevel;
use phpcord\guild\data\SystemChannelFlags;
use phpcord\guild\data\VerificationLevel;
use phpcord\guild\permissible\Permission;
use phpcord\http\RestAPI;
use phpcord\image\Icon;
use phpcord\image\ImageData;
use phpcord\interaction\slash\PartialSlashCommand;
use phpcord\message\Emoji;
use phpcord\scheduler\Scheduler;
use phpcord\user\User;
use phpcord\utils\CDN;
use phpcord\utils\Collection;
use phpcord\guild\permissible\Role;
use phpcord\utils\Color;
use phpcord\utils\Factory;
use phpcord\utils\Timestamp;
use phpcord\voice\VoiceState;
use function in_array;
use function json_encode;

class Guild extends GuildBase {
	
	/**
	 * @var Collection $members
	 * @phpstan-var Collection<GuildMember>
	 */
	private Collection $members;
	
	/**
	 * @var Collection $channels
	 * @phpstan-var Collection<GuildChannel>
	 */
	private Collection $channels;
	
	/**
	 * @var Collection $emojis
	 * @phpstan-var Collection<Emoji>
	 */
	private Collection $emojis;
	
	/**
	 * @var Collection $roles
	 * @phpstan-var Collection<Role>
	 */
	private Collection $roles;
	
	/**
	 * @var Collection $voiceStates
	 * @phpstan-var Collection<VoiceState>
	 */
	private Collection $voiceStates;
	
	/**
	 * Contains the raw data sent in the update executed on the next tick after calling a function that modifies the guild
	 * @internal
	 *
	 * @var array
	 */
	private array $updateData = [];
	
	/** @var Completable|null $updateCompletable */
	private ?Completable $updateCompletable = null;
	
	/**
	 * @param int $id
	 * @param string $name
	 * @param int $ownerId
	 * @param GuildMember[] $members
	 * @param GuildChannel[] $channels
	 * @param Role[] $roles
	 * @param int $maxMembers
	 * @param Emoji[] $emojis
	 * @param Timestamp|null $joinedAt
	 * @param int|null $afkChannelId
	 * @param string|null $preferredLocale
	 * @param int $mfaLevel
	 * @param int|null $systemChannelId
	 * @param int $systemChannelFlags
	 * @param Icon|null $icon
	 * @param int $nsfwLevel
	 * @param int $memberCount
	 * @param int|null $applicationId
	 * @param string|null $vanityUrl
	 * @param Icon|null $banner
	 * @param int $premiumTier
	 * @param int|null $publicUpdatesChannelId
	 * @param string|null $description
	 * @param int $rulesChannelId
	 * @param bool $nsfw
	 * @param int $verificationLevel
	 * @param int $premiumSubscriptionCount
	 * @param string[] $features
	 * @param Icon|null $splash
	 * @param Icon|null $discoverySplash
	 */
	private function __construct(int $id, string $name, private int $ownerId, array $members, array $channels, array $roles, private int $maxMembers, array $emojis, private ?Timestamp $joinedAt, private ?int $afkChannelId, private ?string $preferredLocale, private int $mfaLevel, private ?int $systemChannelId, private int $systemChannelFlags, private ?Icon $icon, private int $nsfwLevel, private int $memberCount, private ?int $applicationId, private ?string $vanityUrl, private ?Icon $banner, private int $premiumTier, private ?int $publicUpdatesChannelId, private ?string $description, private ?int $rulesChannelId, private bool $nsfw, private int $verificationLevel, private int $premiumSubscriptionCount, private array $features, private ?Icon $splash, private ?Icon $discoverySplash) {
		$this->members = new Collection($members);
		$this->channels = new Collection($channels);
		$this->roles = new Collection($roles);
		$this->emojis = new Collection($emojis);
		$this->voiceStates = new Collection();
		parent::__construct($id, $name);
	}
	
	/**
	 * @return Collection<GuildMember>
	 */
	public function getMembers(): Collection {
		return $this->members;
	}
	
	/**
	 * @return Collection<GuildChannel>
	 */
	public function getChannels(): Collection {
		return $this->channels;
	}
	
	/**
	 * @return Collection<Role>
	 */
	public function getRoles(): Collection {
		return $this->roles;
	}
	
	/**
	 * @return Collection<Emoji>
	 */
	public function getEmojis(): Collection {
		return $this->emojis;
	}
	
	/**
	 * @return int
	 */
	public function getMaxMembers(): int {
		return $this->maxMembers;
	}
	
	/**
	 * Returns the timestamp when the bot joined on the server
	 *
	 * @return Timestamp|null
	 */
	public function getBotJoinedAt(): ?Timestamp {
		return $this->joinedAt;
	}
	
	/**
	 * @return int|null
	 */
	public function getApplicationId(): ?int {
		return $this->applicationId;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @return int
	 */
	public function getNsfwLevel(): int {
		return $this->nsfwLevel;
	}
	/**
	 * @return int|null
	 */
	public function getAfkChannelId(): ?int {
		return $this->afkChannelId;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getBanner(): ?Icon {
		return $this->banner;
	}
	
	/**
	 * @see VerificationLevel
	 *
	 * @return int
	 */
	public function getVerificationLevel(): int {
		return $this->verificationLevel;
	}
	
	/**
	 * @return int
	 */
	public function getOwnerId(): int {
		return $this->ownerId;
	}
	
	#[Pure] public function isOwner(User|int $user_or_id): bool {
		return ($this->getOwnerId() === ($user_or_id instanceof User ? $user_or_id->getId() : $user_or_id));
	}
	
	/**
	 * @return string|null
	 */
	public function getVanityUrl(): ?string {
		return $this->vanityUrl;
	}
	
	/**
	 * @return int|null
	 */
	public function getSystemChannelId(): ?int {
		return $this->systemChannelId;
	}
	
	/**
	 * @see SystemChannelFlags
	 *
	 * @return int
	 */
	public function getSystemChannelFlags(): int {
		return $this->systemChannelFlags;
	}
	
	/**
	 * @see SystemChannelFlags
	 *
	 * @param int $flag
	 *
	 * @return bool
	 */
	#[Pure] public function hasSystemChannelFlag(int $flag): bool {
		return (($this->getSystemChannelFlags() & $flag) === $flag);
	}
	
	/**
	 * @return int
	 */
	public function getRulesChannelId(): int {
		return $this->rulesChannelId;
	}
	
	/**
	 * @return int|null
	 */
	public function getPublicUpdatesChannelId(): ?int {
		return $this->publicUpdatesChannelId;
	}
	
	/**
	 * @return int 0-3
	 */
	public function getPremiumTier(): int {
		return $this->premiumTier;
	}
	
	/**
	 * @return int
	 */
	public function getPremiumSubscriptionCount(): int {
		return $this->premiumSubscriptionCount;
	}
	
	/**
	 * @return string|null
	 */
	public function getPreferredLocale(): ?string {
		return $this->preferredLocale;
	}
	
	/**
	 * @see MFALevel
	 *
	 * @return int
	 */
	public function getMfaLevel(): int {
		return $this->mfaLevel;
	}
	
	/**
	 * @return bool
	 */
	public function isNsfw(): bool {
		return $this->nsfw;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getDiscoverySplash(): ?Icon {
		return $this->discoverySplash;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getSplash(): ?Icon {
		return $this->splash;
	}
	
	/**
	 * @return int
	 */
	public function getMemberCount(): int {
		return $this->memberCount;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getIcon(): ?Icon {
		return $this->icon;
	}
	
	/**
	 * @see GuildFeatures
	 *
	 * @return string[]
	 */
	public function getFeatures(): array {
		return $this->features;
	}
	
	private function modify(array $data): Completable {
		$this->updateData += $data;
		Scheduler::getInstance()->executeOnNextTick($this, fn() => RestAPI::getInstance()->updateGuild($this->getId(), $this->updateData)->then(fn($result) => $this->updateCompletable->complete($result))->catch(fn($err) => $this->updateCompletable->complete($err)));
		return $this->updateCompletable ??= Completable::sync();
	}
	
	//todo: NOW ! EMOJIS
	
	public function setName(string $name): Completable {
		return $this->modify([ 'name' => $name ]);
	}
	
	public function setDescription(string $description): Completable {
		return $this->modify([ 'description' => $description ]);
	}
	
	public function setAfkChannelId(int $channelId): Completable {
		return $this->modify([ 'afk_channel_id' => $channelId ]);
	}
	
	public function setAfkTimeout(int $seconds): Completable {
		return $this->modify([ 'afk_timeout' => $seconds ]);
	}
	
	public function setIcon(ImageData $icon): Completable {
		return $this->modify([ 'icon' => $icon->encode() ]);
	}
	
	public function setOwnerId(int $newOwner): Completable {
		return $this->modify([ 'owner_id' => $newOwner ]);
	}
	
	public function setSplash(ImageData $splash): Completable {
		return $this->modify([ 'splash' => $splash->encode() ]);
	}
	
	public function setDiscoverySplash(ImageData $discoverySplash): Completable {
		return $this->modify([ 'discovery_splash' => $discoverySplash->encode() ]);
	}
	
	public function setBanner(ImageData $banner): Completable {
		return $this->modify([ 'banner' => $banner->encode() ]);
	}
	
	public function setSystemChannelId(int $channelId): Completable {
		return $this->modify([ 'system_channel_id' => $channelId ]);
	}
	
	public function setSystemChannelFlags(int $flags): Completable {
		return $this->modify([ 'system_channel_flags' => $flags ]);
	}
	
	public function setRulesChannelId(int $channelId): Completable {
		return $this->modify([ 'rules_channel_id' => $channelId ]);
	}
	
	public function setPublicUpdatesChannelId(int $channelId): Completable {
		return $this->modify([ 'public_updates_channel_id' => $channelId ]);
	}
	
	public function setPreferredLocale(string $preferredLocale): Completable {
		return $this->modify([ 'preferred_locale' => $preferredLocale ]);
	}
	
	public function setFeatures(array $features): Completable {
		return $this->modify([ 'features' => $features ]);
	}
	
	public function createEmoji(string $name, ImageData $image, array $roles = [], ?string $reason = null): Completable {
		return RestAPI::getInstance()->createEmoji($this->getId(), [
			'name' => $name,
			'image' => $image->encode(),
			'roles' => $roles
		], $reason);
	}
	
	/**
	 * @see GuildFeatures
	 *
	 * @param string $feature
	 *
	 * @return bool
	 */
	#[Pure] public function hasFeature(string $feature): bool {
		return in_array($feature, $this->getFeatures(), true);
	}
	
	/**
	 * @return Collection<VoiceState>
	 */
	public function getVoiceStates(): Collection {
		return $this->voiceStates;
	}
	
	private function loadVoiceStates(array $voiceStates): void {
		$this->voiceStates->fill($voiceStates);
	}
	
	public function onVoiceStateUpdate(VoiceState $state): void {
		if (!$this->voiceStates->contains($state->getUserId())) {
			$this->voiceStates->set($state->getUserId(), $state);
			(new VoiceJoinEvent($state))->call();
			return;
		}
		/** @var VoiceState $old */
		$old = $this->voiceStates->get($state->getUserId());
		$this->voiceStates->set($state->getUserId(), $state);
		
		$eventClass = null;
		$parameter = null;
		switch (true) {
			case ($state->getChannelId() === null):
				$eventClass = VoiceLeaveEvent::class;
				$this->voiceStates->unset($state->getUserId());
				break;
			case ($state->getChannelId() !== $old->getChannelId()):
				$eventClass = VoiceMoveEvent::class;
				break;
			case (($state->isMuted() and !$old->isMuted()) or ($state->isSelfMute() and !$old->isSelfMute())):
				$eventClass = VoiceMuteEvent::class;
				$parameter = $state->isSelfMute();
				break;
			case ((!$state->isMuted() and $old->isMuted()) or (!$state->isSelfMute() and $old->isSelfMute())):
				$eventClass = VoiceUnmuteEvent::class;
				$parameter = !$state->isSelfMute();
				break;
			case (($state->isDeaf() and !$old->isDeaf()) or ($state->isSelfDeaf() and !$old->isSelfDeaf())):
				$eventClass = VoiceDeafEvent::class;
				$parameter = $state->isSelfDeaf();
				break;
			case (!$state->isDeaf() and $old->isDeaf() or (!$state->isSelfDeaf() and $old->isSelfDeaf())):
				$eventClass = VoiceUndeafEvent::class;
				$parameter = !$state->isSelfDeaf();
				break;
		}
		if ($eventClass) (new $eventClass($state, $old, $parameter))->call();
	}
	
	/**
	 * @param int $id
	 *
	 * @return Completable<GuildChannel>
	 */
	public function getChannel(int $id): Completable {
		/** @var GuildChannel $c */
		$c = $this->channels->get($id);
		if ($c === null) return Completable::completed(null);
		if ($c->isFetching()) return $c->newFetchHook();
		return Completable::completed($c);
	}
	
	/**
	 * @param string $name
	 * @param Color $color
	 * @param Permission|null $permission
	 * @param bool $hoist
	 * @param bool $mentionable
	 * @param ImageData|null $imageData The imagedata of the icon, only available if the guild has unlocked ROLE_ICONS feature
	 *
	 * @return Completable<Role>
	 */
	public function createRole(string $name, Color $color, ?Permission $permission, bool $hoist = false, bool $mentionable = false, ?ImageData $imageData = null): Completable {
		return RestAPI::getInstance()->createRole($this->getId(), [
			'name' => $name,
			'color' => $color->dec(),
			'permissions' => $permission?->getPermissionBit() ?? 0,
			'hoist' => $hoist,
			'mentionable' => $mentionable,
			'icon' => $imageData?->encode()
		]);
	}
	
	/**
	 * @param ChannelBuilder $builder
	 *
	 * @return Completable<GuildChannel>
	 */
	public function createChannel(ChannelBuilder $builder): Completable {
		return RestAPI::getInstance()->createChannel($this->getId(), json_encode($builder));
	}
	
	/**
	 * @return Completable<AuditLog>
	 */
	public function fetchAuditLog(): Completable {
		return RestAPI::getInstance()->getAuditLog($this->getId());
	}
	
	/**
	 * @return Completable<array<Invite>>
	 */
	public function fetchInvites(): Completable {
		return RestAPI::getInstance()->getGuildInvites($this->getId());
	}
	
	/**
	 * @return Completable<array<Ban>>
	 */
	public function fetchBans(): Completable {
		return RestAPI::getInstance()->getGuildBans($this->getId());
	}
	
	/**
	 * @return Completable<array<Webhook>>
	 */
	public function fetchWebhooks(): Completable {
		return RestAPI::getInstance()->getGuildWebhooks($this->getId());
	}
	
	/**
	 * @return Completable<array<PartialSlashCommand>>
	 */
	public function fetchSlashCommands(): Completable {
		return RestAPI::getInstance()->getGuildSlashCommands(Discord::getInstance()->getClient()->getApplication()->getId(), $this->getId());
	}
	
	public function replaceBy(Guild $guild): void {
		$this->name = $guild->name;
		$this->features = $guild->features;
		$this->banner = $guild->banner;
		$this->discoverySplash = $guild->discoverySplash;
		$this->splash = $guild->splash;
		$this->icon = $guild->icon;
		$this->systemChannelFlags = $guild->systemChannelFlags;
		$this->description = $guild->description;
		$this->publicUpdatesChannelId = $guild->publicUpdatesChannelId;
		$this->rulesChannelId = $guild->rulesChannelId;
		$this->afkChannelId = $guild->afkChannelId;
		$this->maxMembers = $guild->maxMembers;
		$this->memberCount = $guild->memberCount;
		$this->nsfw = $guild->nsfw;
		$this->nsfwLevel = $guild->nsfwLevel;
		$this->verificationLevel = $guild->verificationLevel;
		$this->systemChannelId = $guild->systemChannelId;
		$this->vanityUrl = $guild->vanityUrl;
		$this->premiumTier = $guild->premiumTier;
		$this->premiumSubscriptionCount = $guild->premiumSubscriptionCount;
		$this->preferredLocale = $guild->preferredLocale;
		$this->mfaLevel = $guild->mfaLevel;
	}
	
	public static function fromArray(array $array): ?static {
		$guild = new Guild($array['id'], $array['name'], $array['owner_id'], Factory::createMemberArray($array['id'], $array['members']), (Factory::createChannelArray($array['id'], $array['channels']) + Factory::createChannelArray($array['id'], $array['threads'])), Factory::createRoleArray($array['id'], $array['roles']), $array['max_members'] ?? 500000, Factory::createEmojiArray($array['id'], $array['emojis'] ?? []), (@$array['joined_at'] ? Timestamp::fromDate($array['joined_at']) : null), @$array['afk_channel_id'], @$array['preferred_locale'], $array['mfa_level'] ?? MFALevel::NONE(), $array['system_channel_id'], $array['system_channel_flags'], (@$array['icon'] ? new Icon($array['icon'], CDN::GUILD_ICON($array['id'], $array['icon'])) : null), $array['nsfw_level'] ?? NSFWLevel::DEFAULT(), $array['member_count'] ?? -1, @$array['application_id'], @$array['vanity_url'], (@$array['banner'] ? new Icon($array['banner'], CDN::GUILD_BANNER($array['id'], $array['banner'])) : null), $array['premium_tier'] ?? 0, @$array['public_updates_channel_id'], @$array['description'], @$array['rules_channel_id'], $array['nsfw'] ?? false, $array['verification_level'] ?? VerificationLevel::NONE(), $array['premium_subscription_count'] ?? 0, $array['features'] ?? [], (@$array['splash'] ? new Icon($array['splash'], CDN::GUILD_SPLASH($array['id'], $array['splash'])) : null), (@$array['discovery_splash'] ? new Icon($array['discovery_splash'], CDN::GUILD_SPLASH($array['id'], $array['discovery_splash'])) : null));
		if (@$array['voice_states']) $guild->loadVoiceStates(Factory::createVoiceStateArray(array_map(fn(array $ar) => ($ar + ['guild_id' => $guild->getId()]), $array['voice_states'])));
		return $guild;
	}
}