<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
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
    <form action="process_login.php" method="post">
        <h1>Login</h1>
        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Wachtwoord:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
