<?php
session_start();
include 'connection.php';

$alertMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT id, name, password, role FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];

                $redirect = $row['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php';
                echo "<script>
                    alert('Login successful!');
                    window.location.href = '$redirect';
                </script>";
                exit;
            } else {
                $alertMessage = "Invalid password";
            }
        } else {
            $alertMessage = "User not found";
        }
    } else {
        $alertMessage = "Please fill in both fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Department Announcement System</title>
  <link rel="icon" href="images/iaa_logo.png" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
    }

    .login-container {
      max-width: 400px;
      margin: 60px auto;
      padding: 2rem;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 10px;
      text-align: center;
    }

    .logo {
      width: 80px;
      height: 80px;
      margin-bottom: 15px;
    }

    .login-container h2 {
      color: #004080;
      margin-bottom: 1.5rem;
    }

    form input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    form button {
      width: 100%;
      padding: 12px;
      background: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    form button:hover {
      background: #003366;
    }

    p {
      margin-top: 1rem;
    }

    p a {
      color: #004080;
      text-decoration: none;
    }

    p a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-container">
  <img src="images/iaa_logo.png" alt="Logo" class="logo" />
  <h2>Login</h2>
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
  </form>
  <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<?php if (!empty($alertMessage)): ?>
<script>
  alert("<?= $alertMessage ?>");
</script>
<?php endif; ?>

</body>
</html>
