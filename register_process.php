<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $full_name  = $_POST['full_name'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (student_id, full_name, password) VALUES ('$student_id', '$full_name', '$password')";

    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?registered=1");
    } else {
        header("Location: register.php?error=1");
    }
}
?>
