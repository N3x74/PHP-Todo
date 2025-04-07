<?php

function loadConfig() {
    $file = __DIR__ . '/../storage/config.json';
    if (!file_exists($file)) {
        saveConfig(['timezone' => 'UTC']);
    }
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

function saveConfig($config) {
    $file = __DIR__ . '/../storage/config.json';
    file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT));
}

function setTimezone($timezone) {
    global $color;

    if (!in_array($timezone, timezone_identifiers_list())) {
        echo $color::RED . "[-] The timezone is wrong" . $color::RESET . PHP_EOL;
        exit;
    }
    $config = loadConfig();
    $config['timezone'] = $timezone;
    saveConfig($config);
    echo $color::GREEN . "[+] The time zone is set" . $color::RESET . PHP_EOL;
}

function getTimezone() {
    $config = loadConfig();
    return $config['timezone'] ?? 'UTC';
}

function loadTasks() {
    $file = __DIR__ . '/../storage/tasks.json';
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

function saveTasks($tasks) {
    $file = __DIR__ . '/../storage/tasks.json';
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
}

function printTaskList($tasks) {
    global $color;
    if (empty($tasks)) {
        echo $color::YELLOW . "[!] No tasks found" . $color::RESET . PHP_EOL;
        return;
    }

    foreach ($tasks as $index => $task) {
        $status = $task['done'] ? $color::GREEN . "✓" . $color::RESET : $color::RED . "×" . $color::RESET;
        $createdTime = new DateTime();
        $createdTime->setTimestamp($task['created_at']);
        $createdTime->setTimezone(new DateTimeZone(getTimezone()));
        $formattedTime = $createdTime->format('Y-m-d H:i:s');

        echo "{$index}) [{$status}] in {$formattedTime}" . PHP_EOL . "  " . $color::GREEN . ">" . $color::RESET . " {$task['title']}" . PHP_EOL . PHP_EOL;
    }
}
