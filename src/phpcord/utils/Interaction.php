<?php

namespace phpcord\utils;

class Interaction {
	
	protected $version;
	
	protected $type;
	
	protected $token;
	
	protected $message;
	
	protected $member;
	
	protected $id;
	
	protected $guildId;
	
	protected $data;
	
	protected $channelId;
	
	protected $applicationId;
	
	public function __construct(int $version, int $type, string $token, string $id, string $guildId, array $data, string $channelId, string $applicationId) {
		$this->version = $version;
		$this->type = $type;
		$this->token = $token;
		$this->id = $id;
		$this->guildId = $guildId;
		$this->data = $data;
		$this->channelId = $channelId;
		$this->applicationId = $applicationId;
	}
	
	public static function fromArray(array $data): Interaction {
		return new Interaction($data["version"] ?? 1, $data["type"] ?? 3, $data["token"], $data["id"], $data["guild_id"], $data["data"] ?? [], $data["channel_id"], $data["application_id"]);
	}
	
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getApplicationId(): string {
		return $this->applicationId;
	}
	
	/**
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}
	
	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
	
	/**
	 * @return mixed
	 */
	public function getMember() {
		return $this->member;
	}
	
	/**
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}
	
	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * @return int
	 */
	public function getVersion(): int {
		return $this->version;
	}
}