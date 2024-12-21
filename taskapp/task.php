<?php

// Define a constant for the tasks file (tasks.json)
define("TASKS_FILE", "tasks.json");

// Function to load tasks from the tasks.json file
function loadTasks(): array {
    if (!file_exists(TASKS_FILE)) {
        return [];
    }
    $data = file_get_contents(TASKS_FILE);
    return $data ? json_decode($data, true) : [];
}

// Function to save tasks to the tasks.json file
function saveTasks(array $tasks): void {
    file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

// Function to handle redirect after an action
function redirect(): void {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Load tasks from the tasks.json file
$tasks = loadTasks();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle task addition
    if (isset($_POST['task']) && !empty(trim($_POST['task']))) {
        $tasks[] = [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ];
        saveTasks($tasks);
        redirect();
    }
    // Handle task deletion
    if (isset($_POST['delete'])) {
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks); // Re-index the array
        saveTasks($tasks);
        redirect();
    }
    // Handle task toggle (mark as done/undone)
    if (isset($_POST['toggle'])) {
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
        saveTasks($tasks);
        redirect();
    }
}
?>

<!-- HTML UI -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        /* Apple-inspired minimalist design */
        :root {
            --primary-color: #007aff; /* Apple blue */
            --background-color: #f9f9f9; /* Light background */
            --text-color: #1d1d1f; /* Dark text color */
            --button-bg: #f4f4f4; /* Light button background */
            --button-hover: #e0e0e0; /* Button hover effect */
            --input-border: #d1d1d6; /* Input border color */
            --border-radius: 8px; /* Smooth corners */
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .container {
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: var(--text-color);
        }

        .task-list {
            list-style: none;
            padding: 0;
        }

        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f7f7f7;
            border-radius: var(--border-radius);
            transition: background-color 0.2s ease;
        }

        .task-item:hover {
            background-color: #e9e9e9;
        }

        .task {
            font-size: 16px;
            color: var(--text-color);
            word-wrap: break-word;
        }

        .task-done {
            text-decoration: line-through;
            color: #a6a6a6;
        }

        .button {
            background-color: var(--button-bg);
            color: var(--text-color);
            border: 1px solid var(--input-border);
            padding: 8px 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .button:hover {
            background-color: var(--button-hover);
        }

        .task-actions button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: var(--primary-color);
            font-size: 14px;
        }

        .task-actions button:hover {
            color: #0051a8;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 16px;
            border: 1px solid var(--input-border);
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        form {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>To-Do App</h1>

        <!-- Add Task Form -->
        <form method="POST">
            <input type="text" name="task" placeholder="Enter a new task" required>
            <button type="submit" class="button">Add Task</button>
        </form>

        <!-- Task List -->
        <ul class="task-list">
            <?php if (empty($tasks)): ?>
                <li>No tasks yet. Add one above!</li>
            <?php else: ?>
                <?php foreach ($tasks as $index => $task): ?>
                    <li class="task-item">
                        <!-- Task Text -->
                        <div class="task <?= $task['done'] ? 'task-done' : '' ?>">
                            <?= htmlspecialchars($task['task']) ?>
                        </div>

                        <div class="task-actions">
                            <!-- Toggle task completion -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="toggle" value="<?= $index ?>">
                                <button type="submit">Mark as <?= $task['done'] ? 'Undone' : 'Done' ?></button>
                            </form>

                            <!-- Delete task -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="delete" value="<?= $index ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

</body>
</html>
