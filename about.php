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

    /* Container */
    .container {
      max-width: 900px;
      background: white;
      margin: 2rem auto;
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      flex-grow: 1;
    }

    h1, h2 {
      color: #004080;
      margin-bottom: 1rem;
      font-weight: 700;
    }

    h2 {
      margin-top: 2rem;
      font-size: 1.3rem;
      border-bottom: 3px solid #004080;
      padding-bottom: 0.3rem;
      width: fit-content;
    }

    p, ul {
      font-size: 1rem;
      margin-bottom: 1.5rem;
      color: #444;
    }

    ul {
      padding-left: 1.25rem;
      list-style-type: disc;
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

    /* Responsive adjustments */
    @media (max-width: 600px) {
      header {
        justify-content: center;
      }

      nav {
        gap: 0.8rem;
      }

      .container {
        margin: 1rem 1rem;
        padding: 1.5rem;
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

  <main class="container">
    <h1>About the System</h1>
    <p>
      The <strong>Department Announcement System</strong> is a centralized platform that improves communication among departments, lecturers, and students by managing announcements efficiently.
    </p>

    <h2>Key Features</h2>
    <ul>
      <li>Role-based login: Admin, Lecturer, Student</li>
      <li>Admins approve, block, or delete announcements</li>
      <li>Lecturers submit announcements with optional images</li>
      <li>Students view approved announcements in real time</li>
    </ul>

    <h2>System Users</h2>
    <ul>
      <li><strong>Admin:</strong> Manages users and all announcements</li>
      <li><strong>Lecturer:</strong> Posts announcements to students</li>
      <li><strong>Student:</strong> Views department-specific announcements</li>
    </ul>

    <h2>System Goals</h2>
    <p>
      This system aims to eliminate delays caused by manual announcements and ensure all users have timely access to department news and updates.
    </p>
  </main>

  <footer>
    &copy; <?= date("Y") ?> Department Announcement System. All rights reserved.
  </footer>
</body>
</html>
