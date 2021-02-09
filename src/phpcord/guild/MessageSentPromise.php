<?php

namespace phpcord\guild;

use phpcord\Discord;

class MessageSentPromise {
	/** @var string|null $id */
	protected $id;

	/** @var string|null $channelId */
	protected $channelId;

	/** @var string|null $guildId */
	protected $guildId;

	/** @var null | callable $answerCallable */
	public $answerCallable = null;

	/** @var bool $failed */
	public $failed;

	/**
	 * MessageSentPromise constructor.
	 *
	 * @param bool $failed
	 * @param string|null $guildId
	 * @param string|null $channelId
	 */
	public function __construct(bool $failed, string $guildId = null, string $channelId = null) {
		$this->channelId = $channelId;
		$this->guildId = $guildId;
		$this->failed = $failed;
	}

	/**
	 * @param GuildMember|string $member
	 * @param callable $answerCallable
	 */
	public function setAnswerHandler($member, callable $answerCallable): void {
		if ($member instanceof GuildMember) $member = $member->getId();
		if ($this->isFailed()) return;
		$this->id = $member;
		$this->answerCallable = $answerCallable;
		$this->storePromise();
	}

	protected function storePromise() {
		Discord::$lastInstance->answerHandlers[$this->channelId . ":" . $this->id] = $this;
	}

	/**
	 * @return bool
	 */
	public function isFailed(): bool {
		return $this->failed;
	}
}