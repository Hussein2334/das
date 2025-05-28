<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: announcement.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch the announcement
$query = "SELECT * FROM announcements WHERE announcement_id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) !== 1) {
    echo "Announcement not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

// Fetch departments
$dept_result = mysqli_query($conn, "SELECT * FROM department");
$departments = [];
while ($dept = mysqli_fetch_assoc($dept_result)) {
    $departments[] = $dept;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $department_id = intval($_POST['department_id']);
    $image_name = $row['image']; // existing image name

    if (isset($_FILES['images']) && $_FILES['images']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $file_type = $_FILES['images']['type'];
        $file_tmp = $_FILES['images']['tmp_name'];
        $original_name = basename($_FILES['images']['name']);
        $new_name = time() . '_' . $original_name;
        $upload_path = "../images/" . $new_name;

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_name = $new_name;
            } else {
                echo "<p style='color:red;'>Failed to upload file.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid file type. Allowed types: JPEG, PNG, GIF, PDF.</p>";
        }
    }

    $update_sql = "UPDATE announcements SET 
        title='$title', 
        message='$message', 
        image='$image_name',
        department_id=$department_id
        WHERE announcement_id = $id";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: announcement.php");
        exit();
    } else {
        echo "<p style='color:red;'>Failed to update announcement: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement</title>
    <link rel="icon" href="../images/iaa_logo.png">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 220px;
            background: #004080;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #003060;
        }

        .topbar {
            margin-left: 220px;
            padding: 15px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .form-container {
            max-width: 700px;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            margin-bottom: 1rem;
            color: #004080;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background: #003060;
        }

        img, embed {
            max-width: 100%;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .note {
            font-size: 0.9rem;
            color: #888;
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

<div class="topbar">
    <h3>Edit Announcement</h3>
</div>

<div class="content">
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>

            <label>Message:</label>
            <textarea name="message" rows="6" required><?= htmlspecialchars($row['message']) ?></textarea>

            <label>Department:</label>
            <select name="department_id" required>
                <option value="">-- Select Department --</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $dept['id'] == $row['department_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dept['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Current Image/File:</label><br>
            <?php 
            $file_path = "../images/" . $row['image'];
            $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

            if (!empty($row['image']) && file_exists($file_path)):
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])):
            ?>
                    <img src="<?= $file_path ?>" alt="Current Image"><br>
            <?php elseif ($extension === 'pdf'): ?>
                    <embed src="<?= $file_path ?>" type="application/pdf" width="100%" height="400px" />
            <?php else: ?>
                    <p><em>File uploaded: <?= htmlspecialchars($row['image']) ?></em></p>
            <?php endif; else: ?>
                <p><em>No image/file available.</em></p>
            <?php endif; ?>

            <label>Change Image/File (optional):</label>
            <input type="file" name="images" class="input-file">
            <p class="note">Accepted: JPG, PNG, GIF, PDF</p>

            <button type="submit">Update Announcement</button>
        </form>
    </div>
</div>

</body>
</html>
