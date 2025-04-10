<?php
// public_html/scripts/env_parser.php

function parseEnv($path = __DIR__ . '/../../.env') {
    $vars = [];

    if (!file_exists($path)) return $vars;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;

        [$name, $value] = explode('=', $line, 2);
        $vars[trim($name)] = trim($value);
    }

    return $vars;
}
?>

