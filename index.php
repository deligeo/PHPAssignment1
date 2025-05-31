
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
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
                <th>Photo</th>
                <th>&nbsp;</th> <!-- for edit button -->
                <th>&nbsp;</th> <!-- for delete button -->
            </tr>

            <?php foreach ($books as $book):?>
                <tr>
                    <td><?php echo $book['title'];?></td>
                    <td><?php echo $book['author'];?></td>
                    <td><?php echo $book['isbn'];?></td>
                    <td><?php echo $book['year'];?></td>
                    <td><?php echo $book['pages'];?></td>
                    <td><?php echo $book['price'];?></td>
                    <td><img src="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" alt="<?php echo htmlspecialchars('./images/' . $book['imageName']);?>" style="width:100px; height:auto;" /></td>
                        <td>
                            <form action="update_book_form.php" method="post">
                                <input type="hidden" name="book_id"
                                    value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Update" />
                            </form>
                        </td> <!-- for edit button -->
                        <td>
                            <form action="delete_book.php" method="post">
                                <input type="hidden" name="book_id"
                                    value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Delete" />
                            </form>
                        </td> <!-- for delete button -->
                    </tr>
                </tr>
            <?php endforeach; ?>
            <td colspan="9" id="addBook">
                <a href="add_book_form.php">Add Book</a>
            </td>
        </table>
    </main>
    <?php include ("footer.php"); ?>
</body>
</html>