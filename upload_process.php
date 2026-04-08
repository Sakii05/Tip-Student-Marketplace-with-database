<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_id   = $_SESSION['user_id'];
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $image_path  = NULL;

    // ── Handle image upload ──────────────────────────────
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $file_type     = mime_content_type($_FILES['image']['tmp_name']);
        $file_size     = $_FILES['image']['size'];

        if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) {
            // Create uploads folder if it doesn't exist
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate a unique filename to avoid collisions
            $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename   = 'product_' . time() . '_' . $seller_id . '.' . strtolower($ext);
            $dest       = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = $dest;
            }
        }
    }
    // ────────────────────────────────────────────────────

    $img_val = $image_path ? "'" . mysqli_real_escape_string($conn, $image_path) . "'" : "NULL";

    $sql = "INSERT INTO products (seller_id, title, description, price, image_path) 
            VALUES ('$seller_id', '$title', '$description', '$price', $img_val)";

    if (mysqli_query($conn, $sql)) {
        header("Location: upload.php?success=1");
    } else {
        // Fallback: try without image_path column (if column doesn't exist yet)
        $sql2 = "INSERT INTO products (seller_id, title, description, price) 
                 VALUES ('$seller_id', '$title', '$description', '$price')";
        if (mysqli_query($conn, $sql2)) {
            header("Location: upload.php?success=1");
        } else {
            header("Location: upload.php?error=1");
        }
    }
}
?>
