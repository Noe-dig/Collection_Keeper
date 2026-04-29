<?php
include_once "connection.php";
session_start();

if (isset($_SESSION['loggedInUser'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $query = $pdo->prepare("SELECT id, email, password FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedInUser'] = $user['email'];
            $_SESSION['userID'] = $user['id'];
            
            header("Location: index.php");
            exit;
        } else {
            $error = "<p class=\"error\">Username or password is invalid.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collection Keeper</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <div class="login-container">
            <h1>Login</h1>

            <?php if (isset($error)) echo $error; ?>

            <form method="post" action="">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="email@domain.com">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <button type="submit" name="submit">Login</button>
            </form>

            <p>
                <a href="signup.php">No account? Sign up here</a><br>
                <a href="index.php">Continue without an account</a>
            </p>
        </div>
    </main>
</body>
</html>