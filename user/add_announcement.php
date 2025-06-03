<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// Get the user's department from the database
$user_id = $_SESSION['user_id'];
$user_result = mysqli_query($conn, "SELECT department_id FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_result);
$user_department_id = $user['department_id'];

// Fetch department name for display
$dept_result = mysqli_query($conn, "SELECT name FROM department WHERE id = $user_department_id");
$dept = mysqli_fetch_assoc($dept_result);
$department_name = $dept['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $created_by = $_SESSION['user_id'];
    $status = 'pending';
    $image_name = NULL;

    $department_id = $user_department_id;

    if (!empty($_FILES['image']['name'])) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($image_tmp, "../images/" . $image_name);
    }

    $sql = "INSERT INTO announcements (title, message, created_by, image, status, department_id) 
            VALUES ('$title', '$message', $created_by, " . 
            ($image_name ? "'$image_name'" : "NULL") . ", '$status', $department_id)";
    
    mysqli_query($conn, $sql);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Announcement - User</title>
<link rel="icon" href="../images/iaa_logo.png" />
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

  .sidebar nav a:hover,
  .sidebar nav a.active {
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

  .form-container {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 8px rgba(0,0,0,0.05);
    max-width: 700px;
  }

  label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #004080;
  }

  input[type="text"],
  textarea,
  select {
    width: 100%;
    padding: 10px;
    margin-bottom: 1.5rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
    resize: vertical;
  }

  input[type="file"] {
    margin-bottom: 1.5rem;
  }

  button {
    background: #004080;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background: #003060;
  }

  select[disabled] {
    background-color: #eee;
    color: #555;
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
    <h1>Add Announcement</h1>
    <div class="user">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></div>
  </div>

  <div class="form-container">
    <form method="POST" enctype="multipart/form-data">
      <label for="title">Title:</label>
      <input id="title" type="text" name="title" required>

      <label for="message">Message:</label>
      <textarea id="message" name="message" rows="6" required></textarea>

      <label for="department">Target Department:</label>
      <select id="department" name="department_display" disabled>
        <option selected><?= htmlspecialchars($department_name) ?></option>
      </select>
      <input type="hidden" name="department" value="<?= $user_department_id ?>">

      <label for="image">Image (optional):</label>
      <input id="image" type="file" name="image" accept="image/*">

      <button type="submit">Post Announcement</button>
    </form>
  </div>
</div>

</body>
</html>
