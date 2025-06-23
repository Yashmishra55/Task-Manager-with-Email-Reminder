<?php

function getData($filename) {
    $file = __DIR__ . '/' . $filename;
    if (!file_exists($file)) {
        file_put_contents($file, '');
    }
    $content = file_get_contents($file);
    return $content ? json_decode($content, true) : [];
}

function saveData($filename, $data) {
    $file = __DIR__ . '/' . $filename;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function addTask($task_name) {
    $tasks = getAllTasks();
    foreach ($tasks as $task) {
        if (strtolower($task['name']) === strtolower($task_name)) return;
    }

    $tasks[] = [
        'id' => uniqid(),
        'name' => $task_name,
        'completed' => false
    ];
    saveData('tasks.txt', $tasks);
}

function getAllTasks() {
    return getData('tasks.txt');
}

function isTaskCompleted($task_id) {
    $tasks = getAllTasks();
    foreach ($tasks as $task) {
        if ($task['id'] === $task_id) {
            return $task['completed'];
        }
    }
    return false;
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed;
            break;
        }
    }
    saveData('tasks.txt', $tasks);
}

function deleteTask($task_id) {
    $tasks = getAllTasks();
    $tasks = array_filter($tasks, fn($task) => $task['id'] !== $task_id);
    saveData('tasks.txt', array_values($tasks));
}

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function subscribeEmail($email) {
    $pending = getData('pending_subscriptions.txt');
    $code = generateVerificationCode();
    $pending[$email] = [
        'code' => $code,
        'timestamp' => time()
    ];
    saveData('pending_subscriptions.txt', $pending);

    $verification_link = "http://localhost:8000/verify.php?email=" . urlencode($email) . "&code=$code";
    $subject = "Verify subscription to Task Planner";
    $message = "
        <p>Click the link below to verify your subscription to Task Planner:</p>
        <p><a id=\"verification-link\" href=\"$verification_link\">Verify Subscription</a></p>
    ";
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com\r\n";
    mail($email, $subject, $message, $headers);
}

function verifySubscription($email, $code) {
    $pending = getData('pending_subscriptions.txt');
    if (!isset($pending[$email]) || $pending[$email]['code'] !== $code) {
        return false;
    }

    unset($pending[$email]);
    saveData('pending_subscriptions.txt', $pending);

    $subscribers = getData('subscribers.txt');
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        saveData('subscribers.txt', $subscribers);
    }
    return true;
}

function unsubscribeEmail($email) {
    $subscribers = getData('subscribers.txt');
    $subscribers = array_filter($subscribers, fn($e) => $e !== $email);
    saveData('subscribers.txt', array_values($subscribers));
}

function sendTaskReminders() {
    $subscribers = getData('subscribers.txt');
    $tasks = getAllTasks();
    $pending_tasks = array_filter($tasks, fn($task) => !$task['completed']);

    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}

function sendTaskEmail($email, $pending_tasks) {
    $subject = "Task Planner - Pending Tasks Reminder";
    $message = "<h2>Pending Tasks Reminder</h2><p>Here are the current pending tasks:</p><ul>";

    foreach ($pending_tasks as $task) {
        $message .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }

    $unsubscribe_link = "http://localhost:8000/unsubscribe.php?email=" . urlencode($email);
    $message .= "</ul><p><a id=\"unsubscribe-link\" href=\"$unsubscribe_link\">Unsubscribe from notifications</a></p>";

    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com\r\n";
    mail($email, $subject, $message, $headers);
}
