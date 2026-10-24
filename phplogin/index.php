<?php
session_start();

$error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Enter both your username and password.";
    } else {
        try {
            require_once __DIR__ . "/connection.php";

            $sql = "SELECT password FROM login WHERE username = :username LIMIT 1";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":username", $username);
            $statement->execute();
            $storedPassword = $statement->fetchColumn();

            if ($storedPassword !== false && $storedPassword === $password) {
                session_regenerate_id(true);
                $_SESSION["logged_in_user"] = $username;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $exception) {
            $error = "Unable to connect to the login database. Check your database and table setup.";
        }
    }
}

$loggedInUser = "";
if (isset($_SESSION["logged_in_user"])) {
    $loggedInUser = $_SESSION["logged_in_user"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php if ($loggedInUser !== ""): ?>
            <h1>Login successful</h1>
            <p class="message">Welcome, <?php echo htmlspecialchars($loggedInUser, ENT_QUOTES, "UTF-8"); ?>.</p>

            <form method="post" action="">
                <button type="submit" name="logout" value="1">Log out</button>
            </form>
        <?php else: ?>
            <h1>Welcome back</h1>
            <p>Sign in to continue.</p>

            <form method="post" action="">
                <label for="username">Username</label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    autocomplete="username"
                    required
                    value="<?php echo htmlspecialchars($username, ENT_QUOTES, "UTF-8"); ?>"
                >

                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >

                <?php if ($error !== ""): ?>
                    <p class="message error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>

                <button type="submit">Log in</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>