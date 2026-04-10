<?php
include 'db.php';
session_start();

<<<<<<< HEAD
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
=======
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
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad

    if (mysqli_query($conn, $sql)) {
        header("Location: upload.php?success=1");
    } else {
<<<<<<< HEAD
        $sql2 = "INSERT INTO products (seller_id, title, description, price)
                 VALUES ($seller_id, '$title', '$description', $price)";
        mysqli_query($conn, $sql2);
        header("Location: upload.php?success=1");
    }
    exit();
}
?>
=======
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
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
