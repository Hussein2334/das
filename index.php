<?php
include 'connection.php';

// Fetch latest 4 approved announcements
$sql = "
    SELECT a.title, a.message, a.image, a.created_at, d.name AS department
    FROM announcements a
    JOIN department d ON a.department_id = d.id
    WHERE a.status = 'approved'
    ORDER BY a.created_at DESC
    LIMIT 4
";
$result = mysqli_query($conn, $sql);
$announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Department Announcement System - About</title>
  <link rel="icon" href="images/iaa_logo.png" />
  <style>
    /* Base styling */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fb;
      color: #333;
      margin: 0;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: #004080;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo {
      height: 50px;
      width: auto;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    header h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #e0e7ff;
      white-space: nowrap;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      transition: background-color 0.3s ease;
      background: rgba(255 255 255 / 0.1);
    }

    nav a:hover,
    nav a:focus {
      background: #00264d;
    }

    /* Main container */
    main {
      max-width: 900px;
      margin: 2rem auto;
      padding: 0 1rem;
      flex-grow: 1;
    }

    /* Hero section */
    .hero {
      text-align: center;
      margin-bottom: 2rem;
    }
    .hero h2 {
      color: #004080;
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    .hero p {
      color: #555;
      font-size: 1.1rem;
    }

    /* Announcements cards container */
    .announcement-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.5rem;
      margin-top: 1rem;
    }

    /* Single card */
    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-bottom: 1px solid #ddd;
      flex-shrink: 0;
    }

    .card-body {
      padding: 1rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: #004080;
      margin-bottom: 0.3rem;
      flex-shrink: 0;
    }

    .card-meta {
      font-size: 0.9rem;
      color: #777;
      margin-bottom: 0.7rem;
      flex-shrink: 0;
    }

    .card-message {
      font-size: 1rem;
      color: #444;
      flex-grow: 1;
    }

    /* Button */
    .btn {
      display: inline-block;
      background-color: #004080;
      color: white;
      padding: 0.6rem 1.5rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      margin-top: 1.5rem;
      transition: background-color 0.3s ease;
    }
    .btn:hover,
    .btn:focus {
      background-color: #00264d;
    }

    /* Black footer */
    footer {
      text-align: center;
      padding: 1rem 2rem;
      background: #000000;
      color: white;
      font-weight: 600;
      margin-top: auto;
    }

    /* Responsive */
    @media (max-width: 600px) {
      header {
        justify-content: center;
      }
      nav {
        gap: 0.8rem;
      }
      main {
        margin: 1rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="images/iaa_logo.png" alt="Department Logo" class="logo" />
      <h1>Department Announcement System</h1>
    </div>
    <nav>
      <a href="index.php">Home</a>
      <a href="view_all_announcements.php">Announcements</a>
      <a href="about.php" aria-current="page">About</a>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    </nav>
  </header>

  <main>
    <section class="hero">
      <h2>Welcome to the Announcement Portal</h2>
      <p>Stay updated with the latest department news and events.</p>
    </section>

    <section class="announcements">
      <h3>Recent Announcements</h3>
      <div class="announcement-cards">
        <?php if (count($announcements) > 0): ?>
          <?php foreach ($announcements as $a): ?>
            <div class="card">
              <?php if (!empty($a['image'])): ?>
                <img src="images/<?= htmlspecialchars($a['image']) ?>" alt="Announcement Image" />
              <?php else: ?>
                <img src="images/default.jpg" alt="Default Image" />
              <?php endif; ?>
              <div class="card-body">
                <div class="card-title"><?= htmlspecialchars($a['title']) ?></div>
                <div class="card-meta"><?= htmlspecialchars($a['department']) ?> | <?= date('M d, Y', strtotime($a['created_at'])) ?></div>
                <div class="card-message"><?= htmlspecialchars(substr($a['message'], 0, 100)) ?>...</div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No announcements available right now.</p>
        <?php endif; ?>
      </div>
      <a href="view_all_announcements.php" class="btn">View All Announcements</a>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Department Announcement System. All rights reserved.</p>
  </footer>
</body>
</html>
