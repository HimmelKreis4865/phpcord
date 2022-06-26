# phpcord v3

<a href="https://discord.gg/WzRcuK9gBS"><img src="https://img.shields.io/discord/808294266886553601?label=discord&color=7289DA&logo=discord" alt="Discord" /></a>

## Into
Phpcord is an unofficial [Discord API](https://discord.com/developers/docs) wrapper that allows you to simply create bots for Discord servers.

v3 is the successor of v1 (and phpcord v2 which was never finished)

The leading difference between those two versions (v1 -> v3) is mainly a better network handling, faster tick and asynchronous webapi - requests.

v3 also supports way more features than the older versions, it supports (except voice) nearly every single feature available in the latest discord version, which makes it a great alternative to other discord libraries.

##Features

✅ Asynchronous

✅ Fast and reliable - Tick speed increased + much more performant with less resources

✅ Slash Command and Message Component support

✅ Discord Threads

✅ Easy and documented API

❌ Voice Support (not yet, this might follow in a future update)

## Installation
You can simply download this repository from GitHub and drop it into your local folder, it does not require any composer installations but can be installed via composer.
Edit index.php as a starting point for your projects or create a new file.

## API Documentation
*Soon.*

## Requirements
 - PHP Version: >= [8.0](https://www.php.net/downloads)
 - Required Extensions: [ext-sockets](https://www.php.net/manual/sockets.installation.php), [ext-pthreads](https://pecl.php.net/package/pthreads), [ext-curl](https://www.php.net/manual/curl.installation.php), [ext-openssl](https://www.php.net/manual/openssl.installation.php)
 - 1GB RAM (real usage depends on bot size)