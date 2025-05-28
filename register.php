<?php
include 'connection.php';

$departments = [];
$result = mysqli_query($conn, "SELECT id, name FROM department ORDER BY name");
while ($row = mysqli_fetch_assoc($result)) {
    $departments[] = $row;
}

// Message to be shown in JS
$alertMessage = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $emp = $_POST['emp'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'] ?? '';
    $department = $_POST['department'] ?? '';
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = 'user';

    if (!empty($name) && !empty($email) && !empty($emp) && !empty($phone) && !empty($gender) && !empty($department) && !empty($password) && !empty($confirm)) {
        if ($password === $confirm) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (name, email, emp, phone, gender, department_id, password, role) 
                      VALUES ('$name', '$email', '$emp', '$phone', '$gender', '$department', '$hashed', '$role')";

            if (mysqli_query($conn, $query)) {
                $alertMessage = "Registration successful. Redirecting to login...";
                echo "<script>
                    alert('$alertMessage');
                    window.location.href = 'login.php';
                </script>";
                exit;
            } else {
                $alertMessage = "Error: " . mysqli_error($conn);
            }
        } else {
            $alertMessage = "Passwords do not match.";
        }
    } else {
        $alertMessage = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #aaa;
        }

        h2 {
            text-align: center;
            color: #004080;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #003366;
        }

        p {
            text-align: center;
        }

        a {
            color: #004080;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>

    <form method="post" onsubmit="return validateForm()">
        <label>Full Name</label>
        <input type="text" name="name" id="name">

        <label>Email</label>
        <input type="email" name="email" id="email">

        <label>Employee Number</label>
        <input type="text" name="emp" id="emp">

        <label>Phone</label>
        <input type="text" name="phone" id="phone">

        <label>Gender</label>
        <input type="radio" name="gender" value="M"> Male
        <input type="radio" name="gender" value="F"> Female

        <label>Department</label>
        <select name="department">
            <option value="">-- Select Department --</option>
            <?php foreach ($departments as $d): ?>
                <option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label>Password</label>
        <input type="password" name="password" id="password">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password">

        <input type="submit" name="submit" value="Register">
    </form>

    <p>Already registered? <a href="login.php">Login here</a></p>
</div>

<script>
function validateForm() {
    const password = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;

    if (password !== confirm) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}

<?php if (!empty($alertMessage)): ?>
    alert("<?php echo $alertMessage; ?>");
<?php endif; ?>
</script>

</body>
</html>
