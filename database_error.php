
<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Manager - Database Error</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>
  <?php include("header.php"); ?>
  <main>
    <h2>Database Error</h2>
    
    <p>There was an error connecting to the database.</p>
    <p>The database must be installed.</p>
    <p>MySQL must be running.</p>
    <p>Error Message: <?php echo $_SESSION["database_error"];?>.</p>

    <p><a href="index.php">View Book List</a></p>
  </main>
  <?php include ("footer.php"); ?>
</body>
</html>