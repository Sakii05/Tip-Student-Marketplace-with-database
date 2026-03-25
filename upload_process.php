<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_id   = intval($_SESSION['user_id']);
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price       = floatval($_POST['price']);
    $image_path  = NULL;

    // Verify user exists
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE id=$seller_id"));
    if (!$check) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $file_type     = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($file_type, $allowed_types)) {
            // Use absolute path for saving
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $filename = 'product_' . time() . '_' . $seller_id . '.' . $ext;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                // Save only the relative web path: uploads/filename.jpg
                $image_path = 'uploads/' . $filename;
            }
        }
    }

    $img_val = $image_path
        ? "'" . mysqli_real_escape_string($conn, $image_path) . "'"
        : "NULL";

    $sql = "INSERT INTO products (seller_id, title, description, price, image_path)
            VALUES ($seller_id, '$title', '$description', $price, $img_val)";

    if (mysqli_query($conn, $sql)) {
        header("Location: upload.php?success=1");
    } else {
        $sql2 = "INSERT INTO products (seller_id, title, description, price)
                 VALUES ($seller_id, '$title', '$description', $price)";
        mysqli_query($conn, $sql2);
        header("Location: upload.php?success=1");
    }
    exit();
}
?>