<?php
include 'functions.php';

// Handle form actions before any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        addTask(trim($_POST['task-name']));
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['delete_task']) && isset($_POST['task_id'])) {
        deleteTask($_POST['task_id']);
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['toggle_complete']) && isset($_POST['task_id'])) {
        markTaskAsCompleted($_POST['task_id'], !isTaskCompleted($_POST['task_id']));
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['email'])) {
        subscribeEmail(trim($_POST['email']));
        $message = "📧 Verification email sent. Please check your inbox.";
    }
}

$tasks = getAllTasks();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Task Planner</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Task Planner</h2>

  <?php if (!empty($message)): ?>
    <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <!-- Task Form -->
  <form method="POST" action="index.php">
    <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required />
    <button type="submit" id="add-task">Add Task</button>
  </form>

  <!-- Task List -->
  <ul class="tasks-list">
    <?php foreach ($tasks as $task): ?>
      <li class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
        <form method="POST" action="index.php" style="display:inline;">
          <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
          <input type="hidden" name="toggle_complete" value="1">
          <input type="checkbox" class="task-status" onchange="this.form.submit()" <?php echo $task['completed'] ? 'checked' : ''; ?>>
        </form>
        <?php echo htmlspecialchars($task['name']); ?>
        <form method="POST" action="index.php" style="display:inline;">
          <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
          <button class="delete-task" name="delete_task">Delete</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Subscription Form -->
  <h3>Subscribe for Task Reminders</h3>
  <form method="POST" action="index.php">
    <input type="email" name="email" required />
    <button id="submit-email" type="submit">Submit</button>
  </form>
</body>
</html>
