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

namespace phpcord\channel\helper;

use phpcord\utils\NonInstantiableTrait;

final class SearchFilter {
	use NonInstantiableTrait;
	
	private array $options = [];
	
	public function setAfter(int $id): SearchFilter {
		$this->options['after'] = $id;
		return $this;
	}
	
	public function setBefore(int $id): SearchFilter {
		$this->options['before'] = $id;
		return $this;
	}
	
	public function setAround(int $id): SearchFilter {
		$this->options['around'] = $id;
		return $this;
	}
	
	public function setLimit(int $count): SearchFilter {
		$this->options['limit'] = $count;
		return $this;
	}
	
	public function asArray(): array {
		return $this->options;
	}
	
	public static function new(): SearchFilter {
		return new SearchFilter();
	}
}