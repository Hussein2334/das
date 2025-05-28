<?php
session_start();
include '../connection.php'; // Adjust path if needed

// Ensure admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Query total users
$userCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($res) $userCount = $res->fetch_assoc()['total'];

// Query total announcements
$announcementCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM announcements");
if ($res) $announcementCount = $res->fetch_assoc()['total'];

// Query pending announcements
$pendingCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM announcements WHERE status = 'pending'");
if ($res) $pendingCount = $res->fetch_assoc()['total'];

// Query total departments
$departmentCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM department");
if ($res) $departmentCount = $res->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
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
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
      text-align: center;
      transition: transform 0.2s ease-in-out;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      font-size: 18px;
      color: #004080;
      margin-bottom: 1rem;
    }

    .card p {
      font-size: 22px;
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

    .chart-container {
      margin-top: 3rem;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
      max-width: 700px;
    }

    h2 {
      color: #004080;
      margin-bottom: 1rem;
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
      <h1>Dashboard</h1>
      <div class="user">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></div>
    </div>

    <div class="cards">
      <div class="card">
        <h3>Total Users</h3>
        <p><?= $userCount ?></p>
      </div>
      <div class="card">
        <h3>Announcements</h3>
        <p><?= $announcementCount ?></p>
      </div>
      <div class="card">
        <h3>Departments</h3>
        <p><?= $departmentCount ?></p>
      </div>
      <div class="card">
        <h3>Pending</h3>
        <p><?= $pendingCount ?></p>
      </div>
    </div>

    <!-- Bar Chart Section -->
    <div class="chart-container">
      <h2>System Overview (Bar Chart)</h2>
      <canvas id="overviewChart"></canvas>
    </div>
  </div>

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('overviewChart').getContext('2d');

    const overviewChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Users', 'Announcements', 'Pending', 'Departments'],
        datasets: [{
          label: 'Count',
          data: [<?= $userCount ?>, <?= $announcementCount ?>, <?= $pendingCount ?>, <?= $departmentCount ?>],
          backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8'],
          borderColor: ['#0056b3', '#1c7430', '#e0a800', '#117a8b'],
          borderWidth: 1,
          borderRadius: 5
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });
  </script>
</body>
</html>
