<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        $user_id_safe = mysqli_real_escape_string($conn, $user_id);
        $result = mysqli_query($conn, "SELECT password FROM users WHERE id = $user_id_safe");

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = $row['password'];

            if (!password_verify($current_password, $hashed_password)) {
                $error = "Current password is incorrect.";
            } else {
                $new_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $new_hashed_safe = mysqli_real_escape_string($conn, $new_hashed);

                $update_sql = "UPDATE users SET password = '$new_hashed_safe' WHERE id = $user_id_safe";
                if (mysqli_query($conn, $update_sql)) {
                    $success = "Password updated successfully.";
                } else {
                    $error = "Failed to update password.";
                }
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Admin Panel</title>
    <link rel="icon" href="../images/iaa_logo.png">
    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fb;
        }

        .sidebar {
            width: 220px;
            background: #004080;
            color: white;
            padding: 2rem 1rem;
            position: fixed;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 22px;
        }

        .sidebar nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar nav a:hover {
            background: #003060;
        }

        .sidebar .logout {
            display: block;
            margin-top: 2rem;
            padding: 10px;
            background: #ff4d4d;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar .logout:hover {
            background: #cc0000;
        }

        .main {
            margin-left: 220px;
            width: calc(100% - 220px);
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 28px;
            color: #004080;
        }

        .header .user {
            font-size: 16px;
            color: #555;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        h2 {
            color: #004080;
            margin-bottom: 20px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #004080;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #003060;
        }

        .message {
            margin-bottom: 15px;
            text-align: center;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

  <div class="sidebar">
    <h2>Admin Panel</h2>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="admin_users.php">Manage Users</a>
      <a href="announcement.php">Manage Announcements</a>
      <a href="add_announcement.php">Add Announcements</a>
      <a href="change_password.php">Change Password</a>
      <a href="../logout.php" class="logout">Logout</a>
    </nav>
  </div>

<div class="main">
    <div class="header">
        <h1>Change Password</h1>
        <div class="user">Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></div>
    </div>

    <div class="container">
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>

            <label>New Password:</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="Change Password">
        </form>
    </div>
</div>

</body>
</html>
