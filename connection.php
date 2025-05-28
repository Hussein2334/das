<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'das';

$conn = mysqli_connect($server, $user, $pass, $db);
if (mysqli_connect_errno()) {
    printf('
    ', mysqli_connect_error());
    exit(1);
}
?>