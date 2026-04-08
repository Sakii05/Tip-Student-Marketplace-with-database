<?php
include 'db.php';
session_start();
$id = intval($_GET['id']);
// Safety check: Only delete if the user owns it
mysqli_query($conn, "DELETE FROM products WHERE id = $id AND seller_id = " . intval($_SESSION['user_id']));
header("Location: index.php");
?>
