<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Only show announcements created by the logged-in user
$sql = "SELECT a.*, d.name AS department_name
        FROM announcements a 
        LEFT JOIN users u ON a.created_by = u.id 
        LEFT JOIN department d ON u.department_id = d.id
        WHERE a.created_by = $user_id
        ORDER BY a.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Announcements</title>
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
  background: #002a5c;
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
  background: #004080;
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
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 1.5rem;
}
.card {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  transition: transform 0.2s ease-in-out;
}
.card:hover {
  transform: translateY(-5px);
}
.card img, .card embed {
  max-width: 100%;
  border-radius: 8px;
  margin-top: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.card h3 {
  font-size: 22px;
  color: #002a5c;
  margin-bottom: 0.5rem;
}
.card p {
  font-size: 17px;
  color: #333;
  margin-bottom: 1rem;
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
  display: inline-block;
  padding: 8px 14px;
  text-decoration: none;
  border-radius: 6px;
  margin-right: 8px;
  font-size: 14px;
  font-weight: 500;
  color: white;
  transition: background 0.3s;
}
.approve {
  background: #28a745;
}
.approve:hover {
  background: #218838;
}
.block {
  background: #dc3545;
}
.block:hover {
  background: #c82333;
}
.edit {
  background: #ffc107;
  color: black;
}
.edit:hover {
  background: #e0a800;
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
    <h2>User Panel</h2>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="announcement.php">Manage Announcements</a>
      <a href="add_announcement.php">Add Announcements</a>
      <a href="change_password.php">Change Password</a>
      <a href="../logout.php" class="logout">Logout</a>
    </nav>
  </div>

<div class="main">
    <div class="header">
        <h1>My Announcements</h1>
        <div class="user">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></div>
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
                    Department: <strong><?= htmlspecialchars($row['department_name']) ?></strong><br>
                    Date: <?= $row['created_at'] ?><br>
                    Status: <strong><?= strtoupper($row['status']) ?></strong>
                </div>

                <div class="action-buttons" style="margin-top: 10px;">
                    <a class="edit" href="edit_announcement.php?id=<?= $row['announcement_id'] ?>">Edit</a>
                    <a class="delete" href="delete_announcement.php?id=<?= $row['announcement_id'] ?>" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
