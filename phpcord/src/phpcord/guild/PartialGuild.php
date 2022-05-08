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
use phpcord\guild\components\WelcomeScreen;
use phpcord\image\Icon;
use phpcord\utils\CDN;
use phpcord\utils\Utils;

class PartialGuild extends GuildBase {
	
	/**
	 * @param int $id
	 * @param string $name
	 * @param Icon|null $icon
	 * @param Icon|null $splash
	 * @param Icon|null $banner
	 * @param string|null $description
	 * @param array $features
	 * @param int $verificationLevel
	 * @param string|null $vanityUrl
	 * @param WelcomeScreen|null $welcomeScreen
	 * @param bool $nsfw
	 */
	#[Pure] public function __construct(int $id, string $name, private ?Icon $icon, private ?Icon $splash, private ?Icon $banner, private ?string $description, private array $features, private int $verificationLevel, private ?string $vanityUrl, private ?WelcomeScreen $welcomeScreen, private bool $nsfw = false) {
		parent::__construct($id, $name);
	}
	
	/**
	 * @return Icon|null
	 */
	public function getIcon(): ?Icon {
		return $this->icon;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getSplash(): ?Icon {
		return $this->splash;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getBanner(): ?Icon {
		return $this->banner;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @see GuildFeatures for a list of all available features
	 *
	 * @return array
	 */
	public function getFeatures(): array {
		return $this->features;
	}
	
	/**
	 * @return int
	 */
	public function getVerificationLevel(): int {
		return $this->verificationLevel;
	}
	
	/**
	 * @return string|null
	 */
	public function getVanityUrl(): ?string {
		return $this->vanityUrl;
	}
	
	/**
	 * @return WelcomeScreen|null
	 */
	public function getWelcomeScreen(): ?WelcomeScreen {
		return $this->welcomeScreen;
	}
	
	/**
	 * @return bool
	 */
	public function isNsfw(): bool {
		return $this->nsfw;
	}
	
	public static function fromArray(array $array): ?static {
		if (!Utils::contains($array, 'id', 'name')) return null;
		return new PartialGuild($array['id'], $array['name'], (@$array['icon'] ? new Icon($array['icon'], CDN::GUILD_ICON($array['id'], $array['icon'])) : null), ($array['splash'] ? new Icon($array['splash'], CDN::GUILD_SPLASH($array['id'], $array['splash'])) : null), ($array['banner'] ? new Icon($array['banner'], CDN::GUILD_BANNER($array['id'], $array['banner'])) : null), @$array['description'], $array['features'] ?? [], $array['verification_level'] ?? 0, @$array['vanity_url_code'], (@$array['welcome_screen'] ? WelcomeScreen::fromArray($array['welcome_screen']) : null), @$array['nsfw'] ?? false);
	}
}