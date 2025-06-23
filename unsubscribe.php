<?php
include 'functions.php';

$message = '';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    unsubscribeEmail($email);
    $message = "✅ You have been unsubscribed from task reminders.";
} else {
    $message = "❌ Invalid unsubscribe request.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
</head>
<body>
    <h2>Task Planner - Unsubscribe</h2>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="index.php">Back to Task Planner</a>
</body>
</html>
