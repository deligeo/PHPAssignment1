<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Manager - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Login</h2>

        <?php
        $form_disabled = false;

        if (isset($_SESSION['login_locked']) && $_SESSION['login_locked'] === true) {
            $remaining = $_SESSION['remaining_time'];
            echo "
                <p id='lockout-msg' style='color: red; font-weight: bold;'>
                    Account locked. Try again in <span id='countdown'>$remaining</span> seconds.
                </p>
            ";
            $form_disabled = true;

            // Clear session lockout indicators after use
            unset($_SESSION['login_locked']);
            unset($_SESSION['remaining_time']);
            unset($_SESSION['login_error']);
        } elseif (isset($_SESSION['login_error'])) {
            echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
            unset($_SESSION['login_error']);
        }
        ?>

        <form action="login.php" method="post" id="login_form" enctype="multipart/form-data">
            <div id="data">
                <label>Username:</label>
                <input type="text" name="user_name" <?php if ($form_disabled) echo 'disabled'; ?> /><br />

                <label>Password:</label>
                <input type="password" name="password" <?php if ($form_disabled) echo 'disabled'; ?> /><br />                    
            </div>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Login" <?php if ($form_disabled) echo 'disabled'; ?> /><br />
            </div>
        </form>
        <p><a href="register_contact_form.php">Register</a> | <a href="forgot_password.php">Forgot Password?</a></p>

        <!-- <p><a href="register_contact_form.php">Register</a></p> -->
    </main>

    <?php include("footer.php"); ?>

    <script>
    const countdownElem = document.getElementById("countdown");
    if (countdownElem) {
        let timeLeft = parseInt(countdownElem.textContent);

        const timer = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(timer);
                document.getElementById("lockout-msg").textContent = "You can now try logging in again.";
                location.reload(); // Reloads to enable form again
            } else {
                countdownElem.textContent = timeLeft;
            }
        }, 1000);
    }
    </script>
</body>
</html>
