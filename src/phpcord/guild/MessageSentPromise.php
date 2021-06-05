<?php

namespace phpcord\guild;

use phpcord\Discord;

class MessageSentPromise {
	/** @var string|null $id */
	protected $id;

	/** @var string|null $channelId */
	protected $channelId;

	/** @var null | callable $answerCallable */
	public $answerCallable = null;

	
	/** @var GuildMessage $message */
	protected $message;

	/**
	 * MessageSentPromise constructor.
	 *
	 * @param GuildMessage $message
	 */
	public function __construct(GuildMessage $message) {
		$this->channelId = $message->getChannelId();
		$this->message = $message;
	}

	/**
	 * Sets an answer handler to fetch the next message the member $member is typing
	 *
	 * @api
	 *
	 * @param GuildMember|string $member
	 * @param callable $answerCallable Contains 2 parameters, (BaseTextChannel|DMChannel) $channel and (GuildMessage) $message
	 */
	public function setAnswerHandler($member, callable $answerCallable): void {
		if ($member instanceof GuildMember) $member = $member->getId();
		$this->id = $member;
		$this->answerCallable = $answerCallable;
		$this->storePromise();
	}
	
	/**
	 * @internal
	 */
	protected function storePromise() {
		Discord::$lastInstance->answerHandlers[$this->channelId . ":" . $this->id] = $this;
	}
	
	/**
	 * Tries to fetch the message the promise belongs to
	 *
	 * @api
	 *
	 * @return GuildMessage
	 */
	public function getMessage(): GuildMessage {
		return $this->message;
	}
}