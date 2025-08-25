<?php

if (!function_exists('env')) {
    $GLOBALS['_env_cache'] = null;

    function env(string $key, $default = null)
    {
        if ($GLOBALS['_env_cache'] === null) {
            $envPath = realpath(__DIR__ . '/../../.env');

            if (!file_exists($envPath)) {
                $GLOBALS['_env_cache'] = [];
                return $default;
            }

            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $env = [];

            foreach ($lines as $line) {
                if (trim($line) === '' || str_starts_with(trim($line), '#')) {
                    continue;
                }

                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    $env[trim($name)] = trim($value);
                }
            }

            $GLOBALS['_env_cache'] = $env;
        }

        return $GLOBALS['_env_cache'][$key] ?? $default;
    }
}

if (!function_exists('logToFile')) {
    /**
     * Log data to a file
     *
     * @param mixed $data
     * @param string|null $file Optional file path, default logs/log.txt
     * @return void
     */
    function logToFile($data, ?string $file = null): void
    {
        $file = $file ?? __DIR__ . '/../../logs/log.log';
        $dir = dirname($file);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $text = '[' . date('Y-m-d H:i:s') . '] ' .
            (is_string($data) ? $data : json_encode($data, JSON_PRETTY_PRINT)) . "\n";

        file_put_contents($file, $text, FILE_APPEND);
    }
}
