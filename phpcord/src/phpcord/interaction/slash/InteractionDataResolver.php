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

namespace phpcord\interaction\slash;

use phpcord\channel\Channel;
use phpcord\guild\GuildMember;
use phpcord\guild\permissible\Role;
use phpcord\message\MessageAttachment;
use phpcord\user\User;
use phpcord\utils\Collection;

class InteractionDataResolver {
	
	/**
	 * @var Collection $users
	 * @phpstan-var Collection<User>
	 */
	private Collection $users;
	
	/**
	 * @var Collection $channels
	 * @phpstan-var Collection<Channel>
	 */
	private Collection $channels;
	
	/**
	 * @var Collection $roles
	 * @phpstan-var Collection<Role>
	 */
	private Collection $roles;
	
	/**
	 * @var Collection $members
	 * @phpstan-var Collection<GuildMember>
	 */
	private Collection $members;

	/**
	 * @var Collection $attachments
	 * @phpstan-var Collection<MessageAttachment>
	 */
	private Collection $attachments;
	
	/**
	 * @param User[] $users
	 * @param Channel[] $channels
	 * @param Role[] $roles
	 * @param GuildMember[] $members
	 * @param MessageAttachment[] $attachments
	 */
	public function __construct(array $users, array $channels, array $roles, array $members, array $attachments) {
		$this->users = new Collection($users);
		$this->channels = new Collection($channels);
		$this->roles = new Collection($roles);
		$this->members = new Collection($members);
		$this->attachments = new Collection($attachments);
	}
	
	/**
	 * @return Collection<User>
	 */
	public function getUsers(): Collection {
		return $this->users;
	}
	
	/**
	 * @return Collection<Channel>
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
	 * @return Collection<GuildMember>
	 */
	public function getMembers(): Collection {
		return $this->members;
	}

	/**
	 * @return Collection<MessageAttachment>
	 */
	public function getAttachments(): Collection {
		return $this->attachments;
	}
	
	public static function fromArray(array $array): InteractionDataResolver {
		[$users, $channels, $roles, $members, $attachments] = [[], [], [], [], []];
		
		foreach ($array['users'] ?? [] as $user) {
			$user = User::fromArray($user);
			if ($user) $users[$user->getId()] = $user;
		}
		foreach ($array['members'] ?? [] as $id => $member) {
			if (!isset($users[$id])) continue;
			$member = GuildMember::fromArray(($member + ['user' => $users[$id]->jsonSerialize(), 'guild_id' => @$array['guild_id']]));
			if ($member) $members[$member->getId()] = $member;
		}
		foreach ($array["attachments"] ?? [] as $id => $attachment) {
			$attachment = MessageAttachment::fromArray($attachment);
			if ($attachment) $attachments[$attachment->getId()] = $attachment;
		}

		return new InteractionDataResolver($users, $channels, $roles, $members, $attachments);
	}
}