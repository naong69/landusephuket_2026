<?php
// env('NAME'): read a setting from the environment (set by docker-compose from .env),
// or, when running without Docker (e.g. XAMPP), from the project's .env file.
function env($name, $default = null) {
	static $file = null;

	$value = getenv($name);
	if ($value !== false && $value !== '') return $value;

	if ($file === null) {
		$file = [];
		$path = dirname(__DIR__) . '/.env';
		if (is_readable($path)) {
			foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
				$line = trim($line);
				if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
				list($k, $v) = explode('=', $line, 2);
				$file[trim($k)] = trim(trim($v), "\"'");
			}
		}
	}
	return $file[$name] ?? $default;
}
