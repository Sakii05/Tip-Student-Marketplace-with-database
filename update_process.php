<?php
include 'db.php';
if ($_POST) {
    $id    = intval($_POST['id']);
    $title = $_POST['title'];
    $price = $_POST['price'];
    mysqli_query($conn, "UPDATE products SET title='$title', price='$price' WHERE id=$id");
    header("Location: index.php");
}
?>
