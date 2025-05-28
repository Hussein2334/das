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
      margin: 80px auto;
      padding: 2rem;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 8px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #004080;
    }

    .login-container form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .login-container form button {
      width: 100%;
      padding: 10px;
      background: #004080;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .login-container form button:hover {
      background: #003366;
    }

    .login-container p {
      text-align: center;
      margin-top: 1rem;
    }

    .login-container p a {
      color: #004080;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Login</h2>
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" />
    <input type="password" name="password" placeholder="Password" />
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
