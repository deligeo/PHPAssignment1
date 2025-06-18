<?php
session_start();
require_once('database.php');

$error = '';
$show_form = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_name'])) {
    $user_name = trim($_POST['user_name']);
    $query = "SELECT * FROM registrations WHERE userName = :userName";
    $statement = $db->prepare($query);
    $statement->bindValue(':userName', $user_name);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();

    if ($user) {
        $_SESSION['reset_user'] = $user_name;
        header("Location: reset_password.php");
        exit;
    } else {
        $error = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Manager - Forgot Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="forgot_password.php">
        <label for="user_name">Enter your username:</label><br>
        <input type="text" id="user_name" name="user_name" required><br><br>
        <input type="submit" value="Continue">
    </form>
</body>
</html>
