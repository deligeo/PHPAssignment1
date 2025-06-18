<?php
session_start();
require_once("database.php");

// Get book ID from GET
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
if (!$book_id) {
    header("Location: index.php");
    exit;
}

// Fetch book info
$query = 'SELECT b.*, t.bookType FROM books b LEFT JOIN types t ON b.typeID = t.typeID WHERE bookID = :book_id';
$statement = $db->prepare($query);
$statement->bindValue(':book_id', $book_id);
$statement->execute();
$book = $statement->fetch();
$statement->closeCursor();

if (!$book) {
    echo "book not found.";
    exit;
}

// Convert _100 image to _400 version
$imageName = $book['imageName'];
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);

if (str_ends_with($baseName, '_100')) {
    $baseName = substr($baseName, 0, -4);
}
$imageName_400 = $baseName . '_400' . $extension;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    
</head>
<body>
    <?php include("header.php"); ?>

    <div class="container">
        <h2>Book Details</h2>

        <img class="book-image" src="<?php echo htmlspecialchars('./images/' . $imageName_400); ?>" 
             alt="<?php echo htmlspecialchars($book['title'] . ' by ' . $book['author']); ?>" />

        <div class="book-info">
            <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
            <p><strong>Year:</strong> <?php echo htmlspecialchars($book['year']); ?></p>
            <p><strong>Pages:</strong> <?php echo htmlspecialchars($book['pages']); ?></p>
            <p><strong>Price:</strong> <?php echo htmlspecialchars($book['price']); ?></p>
            <p><strong>book Type:</strong> <?php echo htmlspecialchars($book['bookType']); ?></p>
        </div>

        <a class="back-link" href="index.php">← Back to Book List</a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>