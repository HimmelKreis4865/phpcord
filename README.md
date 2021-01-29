# DiscordPHP

## Introduction
This project is an open source project for your discord bot.
Its API is optimized to be as understandable and easy to use as possible, unlike lots of other libraries.

## Basic usage
### Your first bot
```php
<?php

use DiscordPHP\Discord;

// including discord's loader file, no need for other files
require_once "src\DiscordPHP\Discord.php";

$discord = new Discord(__DIR__, [
	// These listed parameters are optional, you don't have to set them!
	"debugMode" => false,
	// Your bot token for the application, needed for websocket and restapi
	"token" => "Your Token"
]);
// if you already passed the token above, leave this parameter out
$discord->login("Your Token");
```

### Registering events
To register an Event you use `$discord->registerEvents(EventListener);`

```php
<?php

use DiscordPHP\Discord;
use DiscordPHP\event\EventListener;
use DiscordPHP\event\member\MemberAddEvent;

require_once "src\DiscordPHP\Discord.php";

$discord = new Discord(__DIR__);

$discord->registerEvents(new class implements EventListener {
	// listen for events here, example:
	public function onMemberAdd(MemberAddEvent $event) {
		echo "Member " . $event->getMember()->getTag() . PHP_EOL;
	}
});

$discord->login("Your Token");
```

### Initialize CommandHandler and (un)register Commands
The libary comes up with a built-in command handler, you don't have to use it, but we can recommend it
```php
<?php

use DiscordPHP\channel\BaseTextChannel;use DiscordPHP\command\Command;use DiscordPHP\Discord;use DiscordPHP\guild\GuildMessage;

require_once "src\DiscordPHP\Discord.php";

$discord = new Discord(__DIR__);

// Enables the CommandMap, leaving this out will cause an exception
$discord->enableCommandMap();
// Add the prefix you need, can be longer than one char too
$discord->getCommandMap()->addPrefix(".");
$discord->getCommandMap()->addPrefix("DiscordBot|");

$discord->getCommandMap()->register(new class extends Command {
	public function __construct() {
		// First parameter is the name of the command, second is an Array with all Aliases
		parent::__construct("test", ["help", "list"]);
	}
	
	public function execute(BaseTextChannel $channel, GuildMessage $message, array $args): void {
		// do anything you want inside of your command
		$channel->send("Wow! Thanks for executing my command!");
	}
});

$discord->login("Your Token");
```
