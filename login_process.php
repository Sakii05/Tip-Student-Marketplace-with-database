<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password   = $_POST['password'];

    $sql    = "SELECT * FROM users WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: index.php");
    } else {
        header("Location: login.php?error=1");
    }
}
?>
