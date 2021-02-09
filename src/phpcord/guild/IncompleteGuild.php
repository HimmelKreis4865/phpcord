<?php

namespace phpcord\guild;

class IncompleteGuild {
	/** @var string $id */
	protected $id;
	
	/** @var string $name */
	protected $name;
	
	/** @var mixed $splash todo: what is that? */
	protected $splash;
	
	/** @var string|null $banner */
	protected $banner;
	
	/** @var string|null $description */
	protected $description;
	
	/** @var string|null $icon */
	protected $icon;
	
	/** @var array $features */
	protected $features = [];
	
	/** @var int $verification_level */
	protected $verification_level = 0;
	
	/** @var string|null $vanity_url */
	protected $vanity_url = null;
	
	/**
	 * IncompleteGuild constructor.
	 * @param string $id
	 * @param string $name
	 * @param mixed $splash
	 * @param string|null $banner
	 * @param string|null $description
	 * @param string|null $icon
	 * @param array $features
	 * @param int $verification_level
	 * @param string|null $vanity_url
	 */
	public function __construct(string $id, string $name, $splash, string $banner = null, string $description = null, string $icon = null, array $features = [], int $verification_level = 0, ?string $vanity_url = null) {
		$this->id = $id;
		$this->name = $name;
		$this->splash = $splash;
		$this->banner = $banner;
		$this->description = $description;
		$this->icon = $icon;
		$this->features = $features;
		$this->description = $description;
		$this->verification_level = $verification_level;
		$this->vanity_url = $vanity_url;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * @return string|null
	 */
	public function getBanner(): ?string {
		return $this->banner;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @return array
	 */
	public function getFeatures(): array {
		return $this->features;
	}
	
	/**
	 * @return string|null
	 */
	public function getIcon(): ?string {
		return $this->icon;
	}
	
	/**
	 * @return mixed
	 */
	public function getSplash() {
		return $this->splash;
	}
	
	/**
	 * @return string|null
	 */
	public function getVanityUrl(): ?string {
		return $this->vanity_url;
	}
	
	/**
	 * @return int
	 */
	public function getVerificationLevel(): int {
		return $this->verification_level;
	}
}


