<?php
session_start();
include '../connection.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']); // sanitize as integer
    if ($deleteId != $_SESSION['user_id']) {
        $deleteId = mysqli_real_escape_string($conn, $deleteId);
        $delete_sql = "DELETE FROM users WHERE id = $deleteId";
        mysqli_query($conn, $delete_sql);
    }
}

// Fetch users
$result = mysqli_query($conn, "SELECT id, name, email, role FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="icon" href="../images/iaa_logo.png">
  <style>
    /* your existing CSS unchanged */
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
    .main {
      margin-left: 220px;
      width: calc(100% - 220px);
      padding: 2rem;
    }
    h2 {
      text-align: center;
      color: #004080;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 12px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ccc;
    }
    th {
      background-color: #004080;
      color: white;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    form {
      display: inline;
    }
    button {
      padding: 6px 12px;
      background: #d9534f;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background: #c9302c;
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
    .role-badge {
      padding: 5px 8px;
      border-radius: 5px;
      font-size: 12px;
      color: white;
    }
    .admin {
      background-color: #5cb85c;
    }
    .user {
      background-color: #5bc0de;
    }
    .edit-btn {
      padding: 6px 12px;
      background: #0275d8;
      color: white;
      border-radius: 4px;
      text-decoration: none;
      margin-left: 5px;
    }
    .edit-btn:hover {
      background: #025aa5;
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

<!-- Main Content -->
<div class="main">
  <h2>User Management</h2>

  <table>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Action</th>
    </tr>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td><span class='role-badge " . ($row['role'] === 'admin' ? 'admin' : 'user') . "'>" . ucfirst($row['role']) . "</span></td>";
            echo "<td>";

            if ($row['id'] != $_SESSION['user_id']) {
                echo "<form method='POST' style='display:inline-block;'>
                        <input type='hidden' name='delete_id' value='" . $row['id'] . "' />
                        <button type='submit' onclick=\"return confirm('Are you sure you want to delete this user?')\">Delete</button>
                      </form>";
            } else {
                echo "You";
            }

            echo "<a class='edit-btn' href='edit_users.php?id=" . $row['id'] . "'>Edit</a>";

            echo "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No users found.</td></tr>";
    }
    ?>
  </table>
</div>

</body>
</html>
