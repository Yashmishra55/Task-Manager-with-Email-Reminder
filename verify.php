<?php
include 'functions.php';

$verified = false;
$message = '';

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $code = $_GET['code'];

    if (verifySubscription($email, $code)) {
        $verified = true;
        $message = "✅ Subscription verified! You’ll now receive task reminders.";
    } else {
        $message = "❌ Invalid or expired verification link.";
    }
} else {
    $message = "❌ Missing email or code in verification link.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify Subscription</title>
</head>
<body>
    <h2>Task Planner - Email Verification</h2>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="index.php">Back to Task Planner</a>
</body>
</html>
