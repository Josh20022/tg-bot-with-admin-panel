<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM telegram_bot WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bot_info = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        form {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        input[type="submit"]:hover {
            background-color: #303f9f;
        }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <?php if ($bot_info['uit_aan'] == 0): ?>
        <form action="process_edit.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <label for="api_id">API ID:</label>
            <input type="text" name="api_id" id="api_id" value="<?php echo htmlspecialchars($bot_info['api_id']); ?>">
            <br>
        
            <label for="api_hash">API Hash:</label>
            <input type="text" name="api_hash" id="api_hash" value="<?php echo htmlspecialchars($bot_info['api_hash']); ?>">
            <br>
        
            <label for="pauze_seconden">Pauze seconden:</label>
            <input type="number" name="pauze_seconden" id="pauze_seconden" value="<?php echo htmlspecialchars($bot_info['pauze_seconden']); ?>">
            <br>
        
            <label for="telefoon_nummer">Telefoon nummer:</label>
            <input type="text" name="telefoon_nummer" id="telefoon_nummer" value="<?php echo htmlspecialchars($bot_info['telefoon_nummer']); ?>">
            <br>
        
            <label for="message">Message:</label>
            <textarea name="message" id="message" maxlength="5000"><?php echo htmlspecialchars($bot_info['message']); ?></textarea>
            <br>
            <label for="photo1">Photo 1:</label>
            <input type="file" name="photo1" id="photo1">
            <br>
            <label for="photo2">Photo 2:</label>
            <input type="file" name="photo2" id="photo2">
            <br>
            <input type="submit" value="Opslaan">
        </form>
        <form action="toggle_bot.php" method="post">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="submit" value="Zet aan">
        </form>
    <?php else: ?>
        <p>Bot is actief. Kan niet bewerken.</p>
        <form action="toggle_bot.php" method="post">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="submit" value="Zet uit">
        </form>
    <?php endif; ?>
</body>
</html>
