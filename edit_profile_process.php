<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$uid      = intval($_SESSION['user_id']);
$bio      = mysqli_real_escape_string($conn, trim($_POST['bio']      ?? ''));
$location = mysqli_real_escape_string($conn, trim($_POST['location'] ?? ''));
$new_path = null;

// Handle profile picture upload
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $type    = mime_content_type($_FILES['profile_image']['tmp_name']);
    if (in_array($type, $allowed)) {
        $dir = 'uploads/avatars/';
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $ext      = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $filename = 'avatar_' . $uid . '_' . time() . '.' . $ext;
        $dest     = $dir . $filename;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
            // Remove old avatar file
            $old = @mysqli_fetch_assoc(mysqli_query($conn, "SELECT profile_image FROM users WHERE id=$uid"));
            if ($old && !empty($old['profile_image']) && file_exists($old['profile_image'])) {
                @unlink($old['profile_image']);
            }
            $new_path = mysqli_real_escape_string($conn, $dest);
        }
    }
}

// Build query — include profile_image only if a new one was uploaded
if ($new_path) {
    $ok = @mysqli_query($conn,
        "UPDATE users SET bio='$bio', location='$location', profile_image='$new_path' WHERE id=$uid");
} else {
    $ok = @mysqli_query($conn,
        "UPDATE users SET bio='$bio', location='$location' WHERE id=$uid");
}

if ($ok) {
    header("Location: edit_profile.php?success=1");
} else {
    header("Location: edit_profile.php?error=1");
}
exit();
?>
