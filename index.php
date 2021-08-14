<?php

use phpcord\Discord;
use phpcord\intents\IntentsManager;

require_once __DIR__ . DIRECTORY_SEPARATOR . "vendor/autoload.php";
$discord = new Discord(__DIR__, [ "debug_mode" => true, "file_log" => true ]);

$discord->setIntents(IntentsManager::allIntentsSum());
$discord->registerEvents(new EventListener());

$discord->login("your token");