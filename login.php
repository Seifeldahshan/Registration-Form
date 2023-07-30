<?php
session_start();

// Function to check if the given username and password are valid
function isValidLogin($username, $password)
{
    $file = fopen("users.txt", "r");
    while (($line = fgets($file)) !== false) {
        list($name, $email, $stored_password, $room, $profile_picture) = explode(",", rtrim($line));
        if ($name === $username && password_verify($password, $stored_password)) {
            fclose($file);
            return true;
        }
    }
    fclose($file);
    return false;
}

if (isset($_SESSION["username"])) {
    header("Location: welcome.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (isValidLogin($username, $password)) {
        $_SESSION["username"] = $username;
        header("Location: welcome.php");
        exit();
    } else {
        $login_error = "Wrong username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">

        <?php if (isset($login_error)) { ?>
            <p class="error-message"><?php echo $login_error; ?></p>
        <?php } ?>
    </form>
</body>
</html>
