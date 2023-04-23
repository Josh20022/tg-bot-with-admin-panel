<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$bot_id = 1;

// Get bot info
$sql = "SELECT * FROM telegram_bot WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bot_id);
$stmt->execute();
$result = $stmt->get_result();
$bot_info = $result->fetch_assoc();
$stmt->close();

if ($user_id == 1) {
    $sql = "UPDATE telegram_bot SET
            pauze_seconden = ?,
            message = ?,
            photo1 = ?,
            photo2 = ?
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi",
        $_POST['pauze_seconden'],
        $_POST['message'],
        $photo1_path,
        $photo2_path,
        $bot_id
    );
} elseif ($user_id == 2) {
    $sql = "UPDATE telegram_bot SET
            api_id = ?,
            api_hash = ?,
            telefoon_nummer = ?
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi",
        $_POST['api_id'],
        $_POST['api_hash'],
        $_POST['telefoon_nummer'],
        $bot_id
    );
}

// Validate and process form data
// ...

// Process file uploads
$target_dir = "uploads/";
$photo1_path = $target_dir . "1.*";
$photo2_path = $target_dir . "2.*";

// Delete old files
foreach (glob($photo1_path) as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}
foreach (glob($photo2_path) as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

// Move new files
$photo1_path = $target_dir . "1." . pathinfo($_FILES["photo1"]["name"], PATHINFO_EXTENSION);
$photo2_path = $target_dir . "2." . pathinfo($_FILES["photo2"]["name"], PATHINFO_EXTENSION);
move_uploaded_file($_FILES["photo1"]["tmp_name"], $photo1_path);
move_uploaded_file($_FILES["photo2"]["tmp_name"], $photo2_path);

// Update telegram_bot table
$stmt->execute();
$stmt->close();

header("Location: dashboard.php");
?>
