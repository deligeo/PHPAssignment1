<?php
session_start();
require_once('database.php');

$user_input = trim($_POST['user_identifier']);

// Look up by username or email
$query = "SELECT * FROM registrations WHERE userName = :input";
$statement = $db->prepare($query);
$statement->bindValue(':input', $user_input);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if ($user) {
    // Simulate sending an email or prompt to reset
    // For now: set a session message and redirect

    // In a real app: generate token, send email with reset link

    $_SESSION['forgot_success'] = "Password reset instructions have been sent to your email address.";
    header("Location: page_forgot_password.php");
    exit;
} else {
    $_SESSION['forgot_error'] = "No account found with that username or email.";
    header("Location: forgot_password.php");
    exit;
}
