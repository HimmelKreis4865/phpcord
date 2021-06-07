<?php

namespace phpcord\utils\theme;

final class DefaultTheme extends Theme {
	
	public function getDateFormat(string $hour, string $minute, string $second, string $millisecond): string {
		return "§l[$hour:$minute:$second.$millisecond]";
	}
	
	public function getInfoFormat(string $message): string {
		return "§2[INFO] §D$message";
	}
	
	public function getNoticeFormat(string $message): string {
		return "§t[NOTICE] §D$message";
	}
	
	public function getWarningFormat(string $message): string {
		return "§o[WARNING] $message";
	}
	
	public function getDebugFormat(string $message): string {
		return "§y[DEBUG] §B$message";
	}
	
	public function getErrorFormat(string $message): string {
		return "§f[ERROR] $message";
	}
	
	public function getEmergencyFormat(string $message): string {
		return "§a[EMERGENCY] $message";
	}
	
	public function getResetFormat(): string {
		return "§8";
	}
}