<?php

namespace phpcord\utils\theme;

use phpcord\utils\InstantiableTrait;
use Volatile;

class ThemeStorage extends Volatile {
	use InstantiableTrait;
	
	/** @var Theme $theme */
	protected $theme;
	
	/**
	 * @param Theme $theme
	 */
	public function setTheme(Theme $theme): void {
		$this->theme = $theme;
	}
	
	/**
	 * @return Theme
	 */
	public function getTheme(): Theme {
		return $this->theme;
	}
}