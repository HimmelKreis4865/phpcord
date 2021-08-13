<?php

namespace phpcord\command;

use phpcord\channel\BaseTextChannel;
use phpcord\guild\GuildMessage;
use phpcord\interaction\Interaction;
use phpcord\utils\ArrayUtils;
use InvalidArgumentException;
use function array_keys;
use function array_merge;
use function array_shift;
use function count;
use function explode;
use function str_replace;
use function substr;
use function var_dump;

final class GlobalCommandMap extends CommandMap {
	
	public function executeHandler(Interaction $interaction): void {
		if (isset($this->handlers[$interaction->getData()->getName()])) ($this->handlers[$interaction->getData()->getName()])($interaction);
	}
}