<?php

require_once __DIR__ . '/helpers/colors.php';
require_once __DIR__ . '/helpers/functions.php';

$color = new Colors();
$command = $argv[1] ?? null;

switch ($command) {
    case 'add':
        $title = $argv[2] ?? null;
        if (empty($title)) {
            echo $color::RED . "[-] Task not entered" . $color::RESET . PHP_EOL;
            exit;
        }
        $tasks = loadTasks();
        $tasks[] = ['title' => $title, 'done' => false, 'created_at' => time()];
        saveTasks($tasks);
        echo $color::GREEN . "[+] New task created" . $color::RESET . PHP_EOL;
        break;

    case 'list':
        $tasks = loadTasks();
        printTaskList($tasks);
        break;

    case 'done':
        $id = $argv[2] ?? null;
        $tasks = loadTasks();
        if (!isset($tasks[$id])) {
            echo $color::RED . "[-] Task not found" . $color::RESET . PHP_EOL;
            exit;
        }
        $tasks[$id]['done'] = true;
        saveTasks($tasks);
        echo $color::GREEN . "[+] Task {$id} completed" . $color::RESET . PHP_EOL;
        break;

    case 'delete':
        $id = $argv[2] ?? null;
        $tasks = loadTasks();
        if (!isset($tasks[$id])) {
            echo $color::RED . "[-] Task not found" . $color::RESET . PHP_EOL;
            exit;
        }
        $deleted = $tasks[$id]['title'];
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        saveTasks($tasks);
        echo $color::GREEN . "[+] Task {$id} was deleted" . $color::RESET . PHP_EOL;
        break;

    case "clear":
        saveTasks([]);
        break;

    case 'set-timezone':
        $timezone = $argv[2] ?? null;
        if (!$timezone) {
            echo $color::RED . "[-] timezone not entered" . $color::RESET . PHP_EOL;
            exit;
        }
        setTimezone($timezone);
        break;

    default:
        echo Colors::CYAN . "Available commands:\n" . Colors::RESET;
    
        echo "  " . Colors::GREEN . "php todo.php add \"Task title\"" . Colors::RESET;
        echo "        → " . Colors::YELLOW . "Add a new task\n" . Colors::RESET;
        
        echo "  " . Colors::GREEN . "php todo.php list" . Colors::RESET;
        echo "                    → " . Colors::YELLOW . "Show task list\n" . Colors::RESET;
        
        echo "  " . Colors::GREEN . "php todo.php done <id>" . Colors::RESET;
        echo "               → " . Colors::YELLOW . "Mark task as done\n" . Colors::RESET;
        
        echo "  " . Colors::GREEN . "php todo.php delete <id>" . Colors::RESET;
        echo "             → " . Colors::YELLOW . "Delete a task\n" . Colors::RESET;
        
        echo "  " . Colors::GREEN . "php todo.php clear" . Colors::RESET;
        echo "                   → " . Colors::YELLOW . "Clearing tasks\n" . Colors::RESET;
        
        echo "  " . Colors::GREEN . "php todo.php set-timezone <timezone>" . Colors::RESET;
        echo " → " . Colors::YELLOW . "Set the default timezone " . Colors::CYAN . '(e.g. "Asia/Tehran")' . Colors::RESET . "\n";
}
