<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$uid      = intval($_SESSION['user_id']);
$bio      = mysqli_real_escape_string($conn, trim($_POST['bio']      ?? ''));
$location = mysqli_real_escape_string($conn, trim($_POST['location'] ?? ''));
<<<<<<< HEAD

// ── Avatar upload ─────────────────────────────────────────────────────
$new_avatar = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg','image/png','image/webp'];
    $type    = mime_content_type($_FILES['profile_image']['tmp_name']);
    if (in_array($type, $allowed)) {
        $dir = 'uploads/avatars/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ext      = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $filename = 'avatar_' . $uid . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dir . $filename)) {
            // Delete old avatar
            $old = @mysqli_fetch_assoc(mysqli_query($conn, "SELECT profile_image FROM users WHERE id=$uid"));
            if ($old && !empty($old['profile_image']) && file_exists(__DIR__ . '/' . $old['profile_image'])) {
                @unlink(__DIR__ . '/' . $old['profile_image']);
            }
            $new_avatar = mysqli_real_escape_string($conn, $dir . $filename);
=======
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
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
        }
    }
}

<<<<<<< HEAD
// ── Banner upload ─────────────────────────────────────────────────────
$new_banner = null;
if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg','image/png','image/webp'];
    $type    = mime_content_type($_FILES['banner_image']['tmp_name']);
    if (in_array($type, $allowed)) {
        $dir = 'uploads/banners/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ext      = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
        $filename = 'banner_' . $uid . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $dir . $filename)) {
            // Delete old banner
            $old = @mysqli_fetch_assoc(mysqli_query($conn, "SELECT banner_image FROM users WHERE id=$uid"));
            if ($old && !empty($old['banner_image']) && file_exists(__DIR__ . '/' . $old['banner_image'])) {
                @unlink(__DIR__ . '/' . $old['banner_image']);
            }
            $new_banner = mysqli_real_escape_string($conn, $dir . $filename);
        }
    }
}

// ── Build UPDATE query ────────────────────────────────────────────────
$set_parts = ["bio='$bio'", "location='$location'"];
if ($new_avatar) $set_parts[] = "profile_image='$new_avatar'";
if ($new_banner) $set_parts[] = "banner_image='$new_banner'";

$set_sql = implode(', ', $set_parts);
$ok = @mysqli_query($conn, "UPDATE users SET $set_sql WHERE id=$uid");

header($ok ? "Location: edit_profile.php?success=1" : "Location: edit_profile.php?error=1");
exit();
?>
=======
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
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
