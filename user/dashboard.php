<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: /das/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
      <link rel="icon" href="images/iaa_logo.png">
</head>
<body>
    <h1>Welcome, User <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <p>This is the user dashboard.</p>
    <a href="/das/logout.php">Logout</a>
</body>
</html>
