
<?php
    session_start();
    require("database.php");
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();

    $statement1->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Manager - Home</title>
    <link rel="stylesheet" type="txt/css" href="css/main.css"/>
</head>
<body>
    <?php include("header.php"); ?>
    <main>
        <h2>Book List</h2>
        <table>
            <tr>
                <th>Title</th>
              <th>Author</th>
              <th>ISBN</th>
              <th>Year</th>
              <th>Pages</th>
              <th>Price</th>
            </tr>

            <?php foreach ($books as $book):?>
                <tr>
                    <td><?php echo $book['title'];?></td>
                    <td><?php echo $book['author'];?></td>
                    <td><?php echo $book['isbn'];?></td>
                    <td><?php echo $book['year'];?></td>
                    <td><?php echo $book['pages'];?></td>
                    <td><?php echo $book['price'];?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php include ("footer.php"); ?>
</body>
</html>