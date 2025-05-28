<?php
session_start();
include '../connection.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get user ID from URL
if (!isset($_GET['id'])) {
    header("Location: admin_users.php");
    exit();
}

$user_id = intval($_GET['id']); // sanitize as integer

// Fetch user data
$user_id_esc = mysqli_real_escape_string($conn, $user_id);
$query = "SELECT name, email, role FROM users WHERE id = $user_id_esc";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // User not found, redirect
    header("Location: admin_users.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
$name = $user['name'];
$email = $user['email'];
$role = $user['role'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $new_email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $new_role = mysqli_real_escape_string($conn, $_POST['role']);

    $update_sql = "UPDATE users SET name = '$new_name', email = '$new_email', role = '$new_role' WHERE id = $user_id_esc";
    mysqli_query($conn, $update_sql);

    header("Location: admin_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit User</title>
  <link rel="icon" href="../images/iaa_logo.png">
  <style>
    /* Your CSS unchanged */
    * {
      margin: 0;
      padding: 0;
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

    .logout {
      display: block;
      margin-top: 2rem;
      padding: 10px;
      background: #ff4d4d;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 5px;
    }

    .logout:hover {
      background: #cc0000;
    }

    .main {
      margin-left: 220px;
      padding: 2rem;
      width: calc(100% - 220px);
    }

    h2 {
      color: #004080;
      margin-bottom: 20px;
    }

    form {
      max-width: 500px;
      background: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      padding: 10px 20px;
      background: #004080;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background: #003060;
    }

    a.back {
      display: inline-block;
      margin-top: 10px;
      color: #004080;
      text-decoration: none;
    }

    a.back:hover {
      text-decoration: underline;
    }
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
  <h2>Edit User</h2>
  <form method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />

    <label for="role">Role:</label>
    <select id="role" name="role" required>
      <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
      <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
    </select>

    <button type="submit">Update User</button>
    <a href="admin_users.php" class="back">Cancel</a>
  </form>
</div>

</body>
</html>
