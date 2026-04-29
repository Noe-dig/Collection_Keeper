<?php
include_once "connection.php";
session_start();

if (isset($_POST['signup'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';

    if (!empty($email) && !empty($password) && !empty($name)) {
        
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        
        if ($check->fetch()) {
            $error = "That email is already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$email, $hashedPassword, $name])) {
                $_SESSION['loggedInUser'] = $email;
                $_SESSION['userID'] = $pdo->lastInsertId();
                
                header("Location: index.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Keeper</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <div class="form-container">
            <h1>Create Account</h1>
            
            <?php if (isset($error)): ?>
                <p class="error" style="color: red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="post">
                <label for="email">Email:</label>
                <input type="email" name="email" required placeholder="email@domain.com">

                <label for="name">Name:</label>
                <input type="text" name="name" required placeholder="your name">

                <label for="password">Password:</label>
                <input type="password" name="password" required minlength="6">
                
                <input type="submit" name="signup" value="Register">
            </form>
            
            <p>Already have an account? <a href="login.php">Log in here</a></p>
        </div>
    </main>
</body>
</html>