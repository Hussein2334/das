<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if (in_array($action, ['approved', 'blocked'])) {
        $id = mysqli_real_escape_string($conn, $id);
        $action = mysqli_real_escape_string($conn, $action);
        $update_sql = "UPDATE announcements SET status = '$action' WHERE announcement_id = $id";
        mysqli_query($conn, $update_sql);
        header("Location: announcement.php");
        exit();
    }
}

// Include department name
$sql = "SELECT a.*, u.name AS posted_by, d.name AS department_name
        FROM announcements a 
        JOIN users u ON a.created_by = u.id 
        LEFT JOIN department d ON u.department_id = d.id
        ORDER BY a.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Announcements</title>
    <link rel="icon" href="../images/iaa_logo.png">
    <style>
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
        .cards {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 1.5rem;
        }
        .card {
          background: white;
          padding: 1.5rem;
          border-radius: 10px;
          box-shadow: 0 0 8px rgba(0,0,0,0.05);
          transition: transform 0.2s ease-in-out;
        }
        .card:hover {
          transform: translateY(-5px);
        }
        .card img, .card embed {
          max-width: 100%;
          border-radius: 8px;
          margin-top: 10px;
        }
        .card h3 {
          font-size: 24px;
          color: #004080;
          margin-bottom: 1rem;
        }
        .card p {
          font-size: 20px;
          font-weight: bold;
          color: #333;
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
        .status {
          margin-top: 10px;
          font-size: 14px;
          color: #666;
        }
        .action-buttons a {
          padding: 8px 16px;
          text-decoration: none;
          border-radius: 5px;
          margin-right: 10px;
          color: white;
        }
        .approve {
          background: #28a745;
        }
        .block {
          background: #dc3545;
        }
        .edit {
          background: #ffc107;
          color: black;
        }
        .delete {
          background: #ff4d4d;
        }
        .delete:hover {
          background: #cc0000;
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
    <div class="header">
        <h1>Manage Announcements</h1>
        <div class="user">Logged in as: <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></div>
    </div>

    <div class="cards">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                
                <?php 
                $imagePath = "../images/" . $row['image'];
                $fileExt = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                if (!empty($row['image']) && file_exists($imagePath)) {
                    if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo "<img src=\"$imagePath\" alt=\"Image\">";
                    } elseif ($fileExt === 'pdf') {
                        echo "<embed src=\"$imagePath\" type=\"application/pdf\" width=\"100%\" height=\"300px\">";
                    } else {
                        echo "<p><em>Attached File: {$row['image']}</em></p>";
                    }
                }
                ?>
                
                <div class="status">
                    By <strong><?= htmlspecialchars($row['posted_by']) ?></strong>
                    <?php if (!empty($row['department_name'])): ?>
                        from <strong><?= htmlspecialchars($row['department_name']) ?></strong>
                    <?php endif; ?>
                    on <?= $row['created_at'] ?><br>
                    Status: <strong><?= strtoupper($row['status']) ?></strong>
                </div>
                <div class="action-buttons" style="margin-top: 10px;">
                    <?php if ($row['status'] !== 'approved'): ?>
                        <a class="approve" href="?id=<?= $row['announcement_id'] ?>&action=approved">Approve</a>
                    <?php endif; ?>
                    <?php if ($row['status'] !== 'blocked'): ?>
                        <a class="block" href="?id=<?= $row['announcement_id'] ?>&action=blocked">Block</a>
                    <?php endif; ?>
                    <a class="edit" href="edit_announcement.php?id=<?= $row['announcement_id'] ?>">Edit</a>
                    <a class="delete" href="delete_announcement.php?id=<?= $row['announcement_id'] ?>" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
