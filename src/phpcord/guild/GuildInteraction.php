<?php

namespace phpcord\guild;

class GuildInteraction {
	
	public const TYPE_PING = 1;
	
	public const TYPE_COMMAND = 2;
	
	/** @var string $id */
	protected $id;
	
	/** @var string $token */
	protected $token;
	
	/** @var int $type */
	protected $type;
	
	protected $data;
	
	/** @var string $guildId */
	protected $guildId;
	
	/** @var string $channelId */
	protected $channelId;
	
	/** @var GuildMember|null $member */
	protected $member;
	
	public function __construct(string $id, string $token, string $guildId, string $channelId, GuildMember $member = null, int $type = self::TYPE_COMMAND) {
		$this->id = $id;
		$this->token = $token;
		$this->guildId = $guildId;
		$this->channelId = $channelId;
		$this->member = $member;
		$this->type = $type;
	}
}