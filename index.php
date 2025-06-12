
<?php
    session_start();

    if (!isset($_SESSION["isLoggedIn"])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");
    $queryBooks = '
        SELECT b.*, t.bookType
        FROM books b
        LEFT JOIN types t ON b.typeID = t.typeID
    ';

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
                <th>&nbsp;</th> <!-- for update button -->
                <th>&nbsp;</th> <!-- for delete button -->
                <th>&nbsp;</th> <!-- for view details -->
            </tr>

            <?php foreach ($books as $book):?>
                <tr>
                
                    <td><?php echo htmlspecialchars($book['title']);?></td>
                    <td><?php echo htmlspecialchars($book['author']);?></td>
                    <td><?php echo htmlspecialchars($book['isbn']);?></td>
                    <td><?php echo htmlspecialchars($book['year']);?></td>
                    <td><?php echo htmlspecialchars($book['pages']);?></td>
                    <td><?php echo htmlspecialchars($book['price']);?></td>
                    <td><img src="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" 
                             alt="<?php echo htmlspecialchars('./images/' . $book['imageName']);?>" /></td>
                    <td>
                        <form action="update_book_form.php" method="post">
                            <input type="hidden" name="book_id"
                                value="<?php echo $book['bookID']; ?>" />
                            <input type="submit" value="Update" />
                        </form>
                    </td> <!-- for update button -->
                    <td>
                        <form action="delete_book.php" method="post">
                            <input type="hidden" name="book_id"
                                value="<?php echo $book['bookID']; ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td> <!-- for delete button -->
                    <td>
                        <form action="book_details.php" method="post">
                            <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                            <input type="submit" value="View Details" />
                        </form>
                        </td>
                </tr>
            <?php endforeach; ?>
            <td colspan="10" id="addBook">
                <a href="add_book_form.php">Add Book</a>
            </td>
        </table>
        <p id="logOut"><a href="logout.php">Logout</a></p>
    </main>
    <?php include ("footer.php"); ?>
</body>
</html>