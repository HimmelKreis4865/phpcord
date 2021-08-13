<?php

namespace phpcord\guild;

use Exception;
use JetBrains\PhpStorm\Pure;
use OutOfBoundsException;
use phpcord\channel\ChannelType;
use phpcord\channel\embed\ColorUtils;
use phpcord\channel\TextChannel;
use phpcord\channel\VoiceChannel;
use phpcord\command\GuildCommandMap;
use phpcord\command\SlashCommand;
use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;
use phpcord\utils\CacheLevels;
use phpcord\utils\IntUtils;
use InvalidArgumentException;
use phpcord\utils\Math;
use phpcord\utils\Permission;
use phpcord\task\Promise;
use function array_filter;
use function array_map;
use function is_int;
use function is_null;
use function is_string;
use function str_replace;
use function strlen;
use function strval;
use function substr;

class Guild {
	
	protected const BASE_ICON_URL = "https://cdn.discordapp.com/icons/%id%/%icon%.png";
	
	/** @var array|GuildEmoji[] $emojis */
	protected array $emojis = [];

	/** @var AuditLog|null $auditlog */
	protected ?AuditLog $auditlog = null;

	/** @var GuildBanList|null $banList */
	protected ?GuildBanList $banList = null;
	
	/** @var GuildCommandMap $commandMap */
	protected GuildCommandMap $commandMap;

	/**
	 * Guild constructor.
	 * Does not allow you to join any server, just used for cache
	 *
	 * @param string $name
	 * @param string $id
	 * @param string|null $owner_id
	 * @param string|null $icon
	 * @param string|null $banner
	 * @param string|null $afk_channel
	 * @param string|null $rules_channel_id
	 * @param GuildChannel[] $channels
	 * @param GuildMember[] $members
	 * @param array $roles
	 * @param string|null $description
	 * @param int $member_count
	 * @param string $preferred_locale
	 * @param string $region
	 * @param int $default_message_notification
	 * @param int $verification_level
	 * @param int $max_members
	 * @param GuildEmoji[] $emojis
	 * @param string|null $vanity_url
	 * @param string|null $system_channel_id
 	 * @param string|null $public_updates_channel_id
	 * @param int|null $premium_subscription_count
	 * @param array|null $features
	 * @param GuildWelcomeScreen|null $welcomeScreen
	 * @param int $premium_tier
	 */
	#[Pure] public function __construct(protected string $name, protected string $id, protected ?string $owner_id, protected ?string $icon = null, protected ?string $banner = null, protected ?string $afk_channel = null, protected ?string $rules_channel_id = null, protected array $channels = [], protected array $members = [], protected array $roles = [], protected ?string $description = null, protected int $member_count = 10000, protected string $preferred_locale = "en-US", protected string $region = "europe", protected int $default_message_notification = 0, protected int $verification_level = 0, protected int $max_members = 10000, array $emojis = [], protected ?string $vanity_url = null, protected ?string $system_channel_id = null, protected ?string $public_updates_channel_id = null, protected ?int $premium_subscription_count = 0, protected ?array $features = null, protected ?GuildWelcomeScreen $welcomeScreen = null, protected int $premium_tier = 0) {
		$_emojis = [];
		foreach ($emojis as $emoji) {
			if ($emoji instanceof GuildEmoji) $_emojis[$emoji->getId()] = $emoji;
		}
		$this->emojis = $_emojis;
		$this->commandMap = new GuildCommandMap($this->getId());
	}

	/**
	 * Returns the id of the guild
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the name of the guild
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	
	/**
	 * Returns a channel by id
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return GuildChannel
	 */
	public function getChannel(string $id): ?GuildChannel {
		return @$this->channels[$id];
	}
	
	/**
	 * Adds a channel to the cache
	 * Does not affect the discord guild
	 *
	 * @see Guild::createChannel() for channel creations
	 *
	 * @internal
	 *
	 * @param GuildChannel $channel
	 */
	public function addChannel(GuildChannel $channel) {
		$this->updateChannel($channel);
	}
	
	/**
	 * Refreshing the cache after changes on the channel
	 * Does not affect the discord guild
	 *
	 * @see GuildChannel::update() for updating a channel on the guild
	 *
	 * @internal
	 *
	 * @param GuildChannel $channel
	 */
	public function updateChannel(GuildChannel $channel) {
		if (CacheLevels::canCache(CacheLevels::TYPE_CHANNEL)) $this->channels[$channel->getId()] = $channel;
	}
	
	/**
	 * Removes a channel from the cache
	 * Does not affect the discord guild
	 *
	 * @see GuildChannel::delete() or
	 * @see Guild::deleteChannel() for deleting a channel on the guild
	 *
	 * @internal
	 *
	 * @param string|GuildChannel $channel
	 */
	public function removeChannel(GuildChannel|string $channel) {
		if ($channel instanceof GuildChannel) $channel = $channel->getId();
		if (isset($this->channels[$channel])) unset($this->channels[$channel]);
	}
	
	/**
	 * Returns a member by ID, null if not found
	 *
	 * @api
	 *
	 * @param string|int $id
	 *
	 * @return GuildMember|null
	 */
	public function getMemberById(string|int $id): ?GuildMember {
		if (!is_string($id) and !is_int($id)) throw new InvalidArgumentException("IDs can only be made by string or int");
		$id = strval($id);
		return @$this->members[$id];
	}
	
	/**
	 * Returns a member by tag (username#discriminator)
	 *
	 * @api
	 *
	 * @param string $tag
	 *
	 * @return GuildMember|null
	 */
	public function getMemberByTag(string $tag): ?GuildMember {
		foreach ($this->members as $guildMember) {
			if ($guildMember->tag === $tag) return $guildMember;
		}
		return null;
	}
	
	/**
	 * Returns all found members under a specific username
	 *
	 * @warning May needs much ram and cpu for looping through all members!
	 *
	 * @api
	 *
	 * @param string $username
	 *
	 * @return array
	 */
	public function getMembersByExactUsername(string $username): array {
		return array_filter($this->members, function($key) use ($username) {
			return ($key->username === $username);
		});
	}
	
	/**
	 * Returns all found members under a specific username, no need for full names, just the beginning shell be right
	 *
	 * @warning May needs much ram and cpu for looping through all members!
	 *
	 * @api
	 *
	 * @param string $username
	 *
	 * @return array
	 */
	public function getMembersByUsername(string $username): array {
		return array_filter($this->members, function($key) use ($username) {
			return (substr($key->username, strlen($username)) === $username);
		});
	}
	
	/**
	 * Returns all found members under a specific discriminator
	 *
	 * @warning May needs much ram and cpu for looping through all members!
	 *
	 * @api
	 *
	 * @param string $discriminator
	 *
	 * @return array
	 */
	public function getMembersByDiscriminator(string $discriminator): array {
		return array_filter($this->members, function($key) use ($discriminator) {
			return ($key->discriminator === $discriminator);
		});
	}
	
	/**
	 * Returns an array with all members stored in cache
	 *
	 * @api
	 *
	 * @return GuildMember[]
	 */
	public function getMembers(): array {
		return $this->members;
	}
	
	/**
	 * Adds a member to the cache
	 *
	 * @internal
	 *
	 * @param GuildMember $member
	 */
	public function addMember(GuildMember $member) {
		$this->updateMember($member);
	}
	
	/**
	 * Adds / updates a member in the cache
	 *
	 * @internal
	 *
	 * @param GuildMember $member
	 */
	public function updateMember(GuildMember $member) {
		if (CacheLevels::canCache(CacheLevels::TYPE_MEMBERS)) $this->members[$member->getId()] = $member;
	}
	
	/**
	 * Removes a member from the cache
	 *
	 * @internal
	 *
	 * @param User|string $member
	 */
	public function removeMember(User|string $member) {
		if ($member instanceof User) $member = $member->getId();
		if (isset($this->members[$member])) unset($this->members[$member]);
	}
	
	/**
	 * Returns an AuditLog instance
	 * Tries to load from cache or fetch from REST-API
	 *
	 * Filled with @see AuditLogEntry instances
	 *
	 * @api
	 *
	 * @return Promise
	 */
	public function getAuditLog(): Promise {
		return RestAPIHandler::getInstance()->getAuditLog($this->getId());
	}

	/**
	 * fixme
	 *
	 * Returns the banlist of the guild
	 * Tries to fetch it from cache or get it from RESTAPI
	 *
	 * Filled with @see GuildBanEntry instances
	 *
	 * @api
	 *
	 * @return Promise
	 *
	public function getBanList(): Promise {
		$banList = $this->banList;
		if ($banList instanceof GuildBanList) return new Promise(function (Resolver $resolver, Rejecter $rejecter, GuildBanList $banList) {
			$resolver($banList);
		}, $banList);
		
		$result = RestAPIHandler::getInstance()->getBans($this->getId());
		
		return $result->then(function (GuildBanList $banList) {
			$this->banList = $banList;
		});
	}
	*/
	
	/**
	 * Bans a user from the guild, internal use only, @see User::ban() for API instructions
	 *
	 * @internal
	 *
	 * @param User|string $user
	 * @param string|null $reason
	 * @param int|null $messageDeleteDays
	 *
	 * @return Promise
	 */
	public function addBan(User|string $user, ?string $reason = null, ?int $messageDeleteDays = null): Promise {
		if ($messageDeleteDays !== null and !IntUtils::isInRange($messageDeleteDays, 0, 7))
			throw new OutOfBoundsException("Ban message delete duration cannot be out of the range between 0 - 7");
		if ($user instanceof User) $user = $user->getId();
		$result = RestAPIHandler::getInstance()->addBan($this->getId(), $user, $reason, $messageDeleteDays);
		return $result->then(function () use ($user, $reason) {
			$this->banList->addBan(new GuildBanEntry($this->getMemberById($user), $reason));
		});
	}
	
	/**
	 * Returns the member count of the guild (should be updated on adds / removes)
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getMemberCount(): int {
		return $this->member_count;
	}
	
	/**
	 * Returns the complete url of the icon or null if default icon is selected
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getIconUrl(): ?string {
		if ($this->getIcon() === null) return null;
		return str_replace(["%id%", "%icon%"], [$this->getId(), $this->getIcon()], self::BASE_ICON_URL);
	}
	
	/**
	 * Returns the raw icon hash, @see getIconUrl() for a complete url
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getIcon(): ?string {
		return $this->icon;
	}
	
	/**
	 * Unbans a player so he can join again
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function removeBan(string $id): Promise {
		$result = RestAPIHandler::getInstance()->removeBan($this->getId(), $id);
		return $result->then(function () use ($id) {
			$this->banList->removeBan($id);
		});
	}
	
	/**
	 * Returns all webhooks of the guild (= all webhooks of all channels)
	 *
	 * @see TextChannel::getWebhooks() for webhooks of a specific channel
	 *
	 * @api
	 *
	 * @return Promise
	 */
	public function getWebhooks(): Promise {
		return RestAPIHandler::getInstance()->getWebhooksByChannel($this->getId());
	}
	
	/**
	 * Returns whether a member is the owner of the guild or not
	 *
	 * @api
	 *
	 * @param string|int|User $member
	 *
	 * @return bool
	 */
	#[Pure] public function isOwner(string|int|User $member): bool {
		if ($member instanceof User) {
			if ($member->getGuildId() !== $this->getId()) return false;
			$member = $member->getId();
		}
		if (is_int($member)) $member = strval($member);
		if (is_string($member)) return ($member === $this->owner_id);
		return false;
	}
	
	/**
	 * Returns the ID of the guild's owner
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getOwnerId(): ?string {
		return $this->owner_id;
	}
	
	/**
	 * Returns the boost level / premium tier of the guild
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getPremiumTier(): int {
		return $this->premium_tier;
	}
	
	/**
	 * Returns the number of boosts given to the guild
	 *
	 * @api
	 *
	 * @return int|null
	 */
	public function getPremiumSubscriptionCount(): ?int {
		return $this->premium_subscription_count;
	}
	
	/**
	 * Creates a Channel and returns the channel (on success) or null (on failure)
	 *
	 * @api
	 *
	 * @param string $name
	 * @param int $type
	 * @param int|null $position
	 * @param array|null $permissionOverwrites
	 * @param string|null $parentID
	 * @param string|null $topic
	 * @param int|null $userLimit
	 * @param int|null $rateLimit
	 * @param int|null $bitrate
	 * @param bool $nsfw
	 *
	 * @return Promise
	 */
	public function createChannel(string $name, int $type = 0, ?int $position = null, ?array $permissionOverwrites = null, ?string $parentID = null, ?string $topic = null, ?int $userLimit = null, ?int $rateLimit = null, ?int $bitrate = null, bool $nsfw = false): Promise {
		$query = ["name" => $name, "type" => $type];
		if (is_int($position)) $query["position"] = $position;
		if (is_array($position)) $query["position"] = array_map(function ($key) {
			return $key->encode();
		}, array_filter($permissionOverwrites, function($key) {
			return ($key instanceof GuildPermissionOverwrite);
		}));
		if (!is_null($parentID) and ($type !== ChannelType::TYPE_CATEGORY)) $query["parent_id"] = $parentID;
		if (!is_null($topic) and (strlen($topic) <= 1024)) $query["topic"] = $topic;
		if (is_int($userLimit) and ($type === ChannelType::TYPE_VOICE) and ($userLimit >= 0) and ($userLimit <= 99)) $query["user_limit"] = $userLimit;
		if (is_int($rateLimit) and ($type === ChannelType::TYPE_TEXT) and ($rateLimit >= 0) and ($rateLimit <= 21600)) $query["rate_limit"] = $rateLimit;
		if (is_int($bitrate) and ($type === ChannelType::TYPE_VOICE) and ($bitrate >= 8000) and ($bitrate <= 128000)) $query["bitrate"] = $bitrate;
		if ($nsfw and ($type === ChannelType::TYPE_TEXT)) $query["nsfw"] = $nsfw;
		
		return RestAPIHandler::getInstance()->createChannel($this->getId(), $query);
	}
	
	/**
	 * Returns an array with all bots on the guild
	 *
	 * @api
	 *
	 * @return GuildMember[]
	 */
	public function getBots(): array {
		return array_filter($this->getMembers(), function(GuildMember $member) {
			return $member->isBot();
		});
	}
	
	/**
	 * Returns the creation date of the guild
	 *
	 * @api
	 *
	 * @param string $timezone
	 * @param string $format
	 * -> https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters
	 * Check this link for a list of all valid formats
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function getCreationDate(string $timezone = "Europe/London", string $format = "H:i:s d.m.Y"): string {
		return Math::getCreationDate($this->getId(), $timezone, $format);
	}
	
	/**
	 * Removes a channel from the guild (NOT CACHE)
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function deleteChannel(string $id): Promise {
		return RestAPIHandler::getInstance()->deleteChannel($id);
	}
	
	
	/**
	 * Returns an array with all custom emojis of the guild
	 *
	 * @api
	 *
	 * @return GuildEmoji[]
	 */
	public function getEmojis(): array {
		return $this->emojis;
	}
	
	/**
	 * Returns an array with all emojis by a name
	 *
	 * @api
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public function getEmojisByName(string $name): array {
		return array_filter($this->getEmojis(), function($emoji) use ($name) {
			return ($emoji->getName() === $name);
		});
	}
	
	/**
	 * Returns an emoji by id or null if it doesn't exist
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return GuildEmoji|null
	 */
	#[Pure] public function getEmojiById(string $id): ?GuildEmoji {
		foreach ($this->getEmojis() as $emoji) {
			if ($emoji->getId() === $id) return $emoji;
		}
		return null;
	}
	
	/**
	/**
	 * Returns an array with all channels the guild has in cache
	 *
	 * @api
	 *
	 * @return GuildChannel[]
	 */
	public function getChannels(): array {
		return $this->channels;
	}
	
	/**
	 * Removes a member from voice CACHE!
	 *
	 * @internal
	 *
	 * @param string $id
	 *
	 * @return string|null the id of the channel the user was in
	 */
	public function removeMemberFromVoice(string $id): ?string {
		foreach (array_filter($this->getChannels(), function($channel) {
			return ($channel instanceof VoiceChannel);
		}) as $channel) {
			/** @var VoiceChannel $channel */
			if (isset($channel->users[$id])) {
				unset($channel->users[$id]);
				return $channel->getId();
			}
		}
		return null;
	}
	
	/**
	 * Creates a role on the guild (NOT CACHE)
	 *
	 * @api
	 *
	 * @param string $name
	 * @param Permission|null $permission
	 * @param int $color
	 * @param bool $hoist
	 * @param bool $mentionable
	 *
	 * @return Promise
	 */
	public function addRole(string $name = "new role", ?Permission $permission = null, $color = 0x000000, bool $hoist = false, bool $mentionable = false): Promise {
		if (is_null($permission)) {
			$permission = "0";
		} else {
			$permission = $permission->toString();
		}
		
		$color = ColorUtils::createFromCustomData($color)->decimal;
		
		return RestAPIHandler::getInstance()->createRole($this->getId(), $name, $color, $permission, $hoist, $mentionable);
	}
	
	/**
	 * Returns the role from the cache or null, if it's not found
	 *
	 * @api
	 *
	 * @param string|GuildRole $id
	 *
	 * @return GuildRole|null
	 */
	public function getRole($id): ?GuildRole {
		if ($id instanceof GuildRole) return $id;
		return @$this->roles[$id];
	}
	
	/**
	 * Returns whether there is a role with the given ID or not
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function hasRole(string $id): bool {
		return ($this->getRole($id) instanceof GuildRole);
	}
	
	/**
	 * Returns an array with all roles the guild has in cache
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getRoles(): array {
		return $this->roles;
	}
	
	/**
	 * Changes the nick of your bot application, you CANNOT use @link GuildMember::setNick()
	 *
	 * @warning does not work like how it should yet
	 *
	 * @api
	 *
	 * @param string|null $nick
	 *
	 * @return Promise
	 */
	public function setBotNick(?string $nick): Promise {
		return RestAPIHandler::getInstance()->setBotNick(Discord::getInstance()->getClient()->getUser()->getId(), $nick);
	}
	
	/**
	 * Returns an array with all roles that include the name
	 * Notice: Case insensitive
	 *
	 * @api
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public function getRolesByName(string $name): array {
		return array_filter($this->roles, function(GuildRole $role) use ($name) {
			return (strtolower(substr($role->getName(), 0, strlen($name))) === strtolower($name));
		});
	}
	
	/**
	 * Returns an array with all roles that are equaling $name
	 * Note: Case sensitive
	 *
	 * @api
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public function getRolesByExactName(string $name): array {
		return array_filter($this->roles, function(GuildRole $role) use ($name) {
			return ($name === $role->getName());
		});
	}
	
	/**
	 * Deletes a role from the guild (NOT CACHE)
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function deleteRole(string $id): Promise {
		return RestAPIHandler::getInstance()->deleteRole($this->getId(), $id);
	}
	
	/**
	 * Fetches all guild invites from restapi
	 *
	 * @api
	 *
	 * @return Promise
	 */
	public function fetchInvites(): Promise {
		return RestAPIHandler::getInstance()->getGuildInvites($this->getId());
	}
	
	/**
	 * Fetches a channel from the guild
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function fetchChannel(string $id): Promise {
		return RestAPIHandler::getInstance()->fetchChannel($id);
	}
	
	/**
	 * Fetches a member from the guild
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function fetchMember(string $id): Promise {
		return RestAPIHandler::getInstance()->fetchMember($this->getId(), $id);
	}
	
	/**
	 * @return GuildCommandMap
	 */
	public function getCommandMap(): GuildCommandMap {
		return $this->commandMap;
	}
	
	public function registerSlashCommand(SlashCommand $command): Promise {
		return RestAPIHandler::getInstance()->registerSlashCommand($this->getId(), Discord::getInstance()->getApplicationId(), $command->encode());
	}
	
	public function getSlashCommands(): Promise {
		return RestAPIHandler::getInstance()->getSlashCommands($this->getId(), Discord::getInstance()->getApplicationId());
	}
}