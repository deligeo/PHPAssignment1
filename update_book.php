<?php
session_start();

require_once('database.php');
require_once('image_util.php');

// Get book ID
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

// Get other form data
$title = filter_input(INPUT_POST, 'title');
$author = filter_input(INPUT_POST, 'author');
$isbn = filter_input(INPUT_POST, 'isbn');
$year = filter_input(INPUT_POST, 'year');
$pages = filter_input(INPUT_POST, 'pages');
$price = filter_input(INPUT_POST, 'price');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

// Get uploaded image (if any)
$image = $_FILES['image'];

// Get current book record to check current image name
$query = 'SELECT * FROM books WHERE bookID = :bookID';
$statement = $db->prepare($query);
$statement->bindValue(':bookID', $book_id);
$statement->execute();
$book = $statement->fetch();
$statement->closeCursor();

$old_image_name = $book['imageName'];
$base_dir = 'images/';
$image_name = $old_image_name;

// Check for duplicate ISBN in other books
$queryBooks = 'SELECT * FROM books';
$statement1 = $db->prepare($queryBooks);
$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($books as $b) {
    if ($isbn == $b["isbn"] && $book_id != $b["bookID"]) {
        $_SESSION["add_error"] = "Invalid data, Duplicate ISBN. Try again.";
        header("Location: error.php");
        die();
    }
}

// Validate input
if ($title == null || $author == null || $isbn == null ||
    $year == null || $pages == null || $price == null || $type_id == null) {
    $_SESSION["add_error"] = "Invalid book data. Check all fields and try again.";
    header("Location: error.php");
    die();
}

// If new image is uploaded
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;

    move_uploaded_file($image['tmp_name'], $upload_path);

    // Process and create _100 and _400 versions
    process_image($base_dir, $original_filename);

    // Create new image name with _100
    $dot_pos = strrpos($original_filename, '.');
    $new_image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
    $image_name = $new_image_name;

    // Delete old images if they are not the placeholder
    if ($old_image_name !== 'placeholder_100.jpg') {
        $old_base = substr($old_image_name, 0, strrpos($old_image_name, '_100'));
        $old_ext = substr($old_image_name, strrpos($old_image_name, '.'));
        $original = $old_base . $old_ext;
        $img100 = $old_base . '_100' . $old_ext;
        $img400 = $old_base . '_400' . $old_ext;

        foreach ([$original, $img100, $img400] as $file) {
            $path = $base_dir . $file;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}

// Update book in database
$update_query = '
    UPDATE books
    SET title = :title,
        author = :author,
        isbn = :isbn,
        year = :year,
        pages = :pages,
        price = :price,
        typeID = :typeID,
        imageName = :imageName
    WHERE bookID = :bookID';

$statement = $db->prepare($update_query);
$statement->bindValue(':title', $title);
$statement->bindValue(':author', $author);
$statement->bindValue(':isbn', $isbn);
$statement->bindValue(':year', $year);
$statement->bindValue(':pages', $pages);
$statement->bindValue(':price', $price);
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':bookID', $book_id);
$statement->execute();
$statement->closeCursor();

// Store book title for confirmation message
$_SESSION["bookName"] = $title;

// Redirect to confirmation
header("Location: update_confirmation.php");
die();
?>