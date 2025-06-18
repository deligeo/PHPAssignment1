<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['reset_user'])) {
    header("Location: forgot_password.php");
    exit;
}

$error = '';
$success = '';
$user_name = $_SESSION['reset_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if ($new_pass !== $confirm_pass) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $query = "UPDATE registrations SET password = :password WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':password', $hashed);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        unset($_SESSION['reset_user']);
        $success = "Password successfully updated. <a href='login_form.php'>Login now</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Library Manager - Reset Password</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>
    <h2>Reset Password for <?= htmlspecialchars($user_name) ?></h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php else: ?>
        <form method="POST" action="reset_password.php">
            <label for="new_password">New Password:</label><br>
            <input type="password" id="new_password" name="new_password" required><br><br>

            <label for="confirm_password">Re-type New Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>

            <input type="submit" value="Reset Password">
        </form>
    <?php endif; ?>
</body>
</html>
