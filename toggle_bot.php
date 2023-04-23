<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT uit_aan FROM telegram_bot WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bot_info = $result->fetch_assoc();
$stmt->close();

$new_status = $bot_info['uit_aan'] == 0 ? 1 : 0;

$sql = "UPDATE telegram_bot SET uit_aan = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $new_status, $user_id);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php");
?>
