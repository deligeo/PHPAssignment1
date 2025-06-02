<?php
    session_start();    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Library Manager - Error</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Error</h2>
            <p>
                <?php echo $_SESSION["add_error"]; ?> 
            </p>

            <p><a href="add_book_form.php">Add Book</a></p>
            <p><a href="index.php">View Book List</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>