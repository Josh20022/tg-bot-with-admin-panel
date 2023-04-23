<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Validate and process form data
// ...

// Process file uploads
$target_dir = "uploads/";
$photo1_path = $target_dir . basename($_FILES["photo1"]["name"]);
$photo2_path = $target_dir . basename($_FILES["photo2"]["name"]);

move_uploaded_file($_FILES["photo1"]["tmp_name"], $photo1_path);
move_uploaded_file($_FILES["photo2"]["tmp_name"], $photo2_path);

// Update telegram_bot table
$sql = "UPDATE telegram_bot SET
        api_id = ?,
        api_hash = ?,
        pauze_seconden = ?,
        telefoon_nummer = ?,
        message = ?,
        photo1 = ?,
        photo2 = ?
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssissssi",
    $_POST['api_id'],
    $_POST['api_hash'],
    $_POST['pauze_seconden'],
    $_POST['telefoon_nummer'],
    $_POST['message'],
    $photo1_path,
    $photo2_path,
    $user_id
);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php");
?>
