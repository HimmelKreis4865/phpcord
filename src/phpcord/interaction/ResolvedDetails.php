<?php

namespace phpcord\interaction;

use phpcord\command\SubCommand;
use phpcord\utils\ChannelInitializer;
use phpcord\utils\GuildSettingsInitializer;
use phpcord\utils\MemberInitializer;
use phpcord\utils\SingletonTrait;
use function strval;

final class ResolvedDetails {
	use SingletonTrait;
	
	public array $users = [];
	
	public array $channels = [];
	
	public array $roles = [];
	
	public static function fromArray(string $guildId, array $data): ResolvedDetails {
		$instance = new ResolvedDetails();
		[$instance->users, $instance->channels, $instance->roles] = self::parseResolved($guildId, $data);
		return $instance;
	}
	
	private static function parseResolved(string $guildId, array $resolved): array {
		$users = [];
		$roles = [];
		$channels = [];
		if (isset($resolved["users"])) {
			foreach ($resolved["users"] as $k => $user) {
				if (isset($resolved["members"][$k])) {
					$m = MemberInitializer::createMember(($resolved["members"][$k] + ["user" => $user]), $guildId);
				} else {
					$m = MemberInitializer::createUser($user, $guildId);
				}
				$users[strval($k)] = $m;
			}
		}
		if (isset($resolved["roles"])) {
			foreach ($resolved["roles"] as $k => $role) {
				$roles[$k] = GuildSettingsInitializer::createRole($guildId, $role);
			}
		}
		if (isset($resolved["channels"])) {
			foreach ($resolved["channels"] as $k => $channel) {
				$channels[$k] = ChannelInitializer::createIncomplete($guildId, $channel);
			}
		}
		return [$users, $roles, $channels];
	}
	
	public function parseOptions(array $options): array {
		$c = [];
		foreach ($options as $data) {
			$c[$data["name"]] = new SubCommandResult($data["type"], $data["name"], match ($data["type"]) {
				SubCommand::USER => $this->users[$data["value"]] ?? $data["value"],
				SubCommand::ROLE => $this->roles[$data["value"]] ?? $data["value"],
				SubCommand::CHANNEL => $this->channels[$data["value"]] ?? $data["value"],
				SubCommand::MENTIONABLE => $this->users[$data["value"]] ?? $this->roles[$data["value"]] ?? $data["value"],
				default => $data["value"]
			});
		}
		return $c;
	}
}