<?php
    session_start();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Library Manager - Update Confirmation</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Book Update Confirmation</h2>
            <p>
                Thank you, for adding  <?php echo $_SESSION["bookTitle"]; ?> in the Library Manager.
                We look forward to working with you.
            </p>
            
            <p><a href="index.php">Back to Home</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>
