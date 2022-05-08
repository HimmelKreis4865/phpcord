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

namespace phpcord\user;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\image\Icon;
use phpcord\utils\CDN;
use phpcord\utils\Utils;
use function var_dump;

class User implements JsonSerializable {
	
	/**
	 * @param int $id
	 * @param string $username
	 * @param string $discriminator
	 * @param Icon|null $avatar
	 * @param int $flags
	 * @param bool $bot
	 * @param string|null $email
	 * @param bool $verified
	 */
	public function __construct(private int $id, private string $username, private string $discriminator, private ?Icon $avatar, private int $flags = 0, private bool $bot = false, private ?string $email = null, private bool $verified = true) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getAvatar(): ?Icon {
		return $this->avatar;
	}
	
	/**
	 * @return string
	 */
	public function getDiscriminator(): string {
		return $this->discriminator;
	}
	
	public function getTag(): string {
		return $this->username . '#' . $this->discriminator;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->username;
	}
	
	/**
	 * @return string|null
	 */
	public function getEmail(): ?string {
		return $this->email;
	}
	
	/**
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}
	
	/**
	 * @return bool
	 */
	public function isBot(): bool {
		return $this->bot;
	}
	
	/**
	 * @return bool
	 */
	public function isVerified(): bool {
		return $this->verified;
	}
	
	/**
	 * @param array $array
	 *
	 * @return User|null
	 */
	public static function fromArray(array $array): ?User {
		if (!Utils::contains($array, 'id', 'username', 'discriminator', 'avatar')) return null;
		return new User($array['id'], $array['username'], $array['discriminator'], (($array['avatar'] ?? false) ? new Icon($array['avatar'], CDN::USER_AVATAR($array['id'], $array['avatar'])) : null), $array['flags'] ?? 0, $array['bot'] ?? false, @$array['email'], $array['verified'] ?? true);
	}
	
	#[Pure]
	#[ArrayShape(['id' => "int", 'username' => "string", 'discriminator' => "string", 'public_flags' => "int", 'bot' => "bool", 'avatar' => "string", 'email' => "null|string", 'verified' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'username' => $this->username,
			'discriminator' => $this->discriminator,
			'public_flags' => $this->flags,
			'bot' => $this->bot,
			'avatar' => $this->avatar->getHash(),
			'email' => $this->email,
			'verified' => $this->verified
		];
	}
}