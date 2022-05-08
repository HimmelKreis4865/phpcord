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

namespace phpcord\channel;

use phpcord\async\completable\Completable;
use phpcord\channel\helper\SearchFilter;
use phpcord\message\Message;
use phpcord\message\Sendable;

interface TextChannel {
	
	public function getId(): int;
	
	public function getLastMessageId(): ?int;
	
	/**
	 * @param int $id
	 *
	 * @return Completable
	 */
	public function fetchMessage(int $id): Completable;
	
	/**
	 * @param SearchFilter|null $filter
	 *
	 * @return Completable
	 */
	public function fetchMessages(SearchFilter $filter = null): Completable;
	
	/**
	 * @param Sendable $sendable
	 *
	 * @return Completable
	 */
	public function send(Sendable $sendable): Completable;
	
	/**
	 * Indicates that the bot is typing in the certain channel
	 *
	 * @return Completable
	 */
	public function triggerTyping(): Completable;
	
	/**
	 * @param int $id
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function deleteMessage(int $id, string $reason = null): Completable;
	
	/**
	 * @param SearchFilter $filter
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function bulkDelete(SearchFilter $filter, string $reason = null): Completable;
	
	/**
	 * Called when a message in this channel is deleted
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function onMessageDelete(int $id): void;
}