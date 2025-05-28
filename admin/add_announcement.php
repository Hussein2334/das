<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all departments
$departments = [];
$result = mysqli_query($conn, "SELECT id, name FROM department ORDER BY name");
while ($row = mysqli_fetch_assoc($result)) {
    $departments[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $created_by = $_SESSION['user_id'];
    $status = 'pending';
    $image_name = NULL;

    // 🟡 Get department ID
    $department_id = isset($_POST['department']) && is_numeric($_POST['department']) ? intval($_POST['department']) : 'NULL';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($image_tmp, "../images/" . $image_name);
    }

    // ✅ Insert the announcement with department_id
    $sql = "INSERT INTO announcements (title, message, created_by, image, status, department_id) 
            VALUES ('$title', '$message', $created_by, " . 
            ($image_name ? "'$image_name'" : "NULL") . ", '$status', $department_id)";
    
    mysqli_query($conn, $sql);

    header("Location: announcement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Announcement</title>
    <link rel="icon" href="../images/iaa_logo.png">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f4f4; }
        .sidebar { width: 220px; background: #004080; height: 100vh; position: fixed; padding-top: 20px; color: white; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.5rem; }
        .sidebar a { display: block; color: white; padding: 12px 20px; text-decoration: none; }
        .sidebar a:hover { background: #003060; }
        .topbar { margin-left: 220px; padding: 15px; background: #fff; border-bottom: 1px solid #ddd; }
        .content { margin-left: 220px; padding: 20px; }
        .form-container { max-width: 700px; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin-bottom: 1rem; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #004080; color: white; padding: 10px 20px; border: none; border-radius: 5px; }
        button:hover { background: #003060; }
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

<div class="topbar">
    <h3>Add Announcement</h3>
</div>

<div class="content">
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required>

            <label>Message:</label>
            <textarea name="message" rows="6" required></textarea>

            <label>Target Department:</label>
            <select name="department" required>
                <option value="">-- Select Department --</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>Image (optional):</label>
            <input type="file" name="image">

            <button type="submit">Post Announcement</button>
        </form>
    </div>
</div>

</body>
</html>
