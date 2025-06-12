<?php
session_start();

$title = filter_input(INPUT_POST, 'title');
$author = filter_input(INPUT_POST, 'author');
$isbn = filter_input(INPUT_POST, 'isbn');
$year = filter_input(INPUT_POST, 'year');
$pages = filter_input(INPUT_POST, 'pages');
$price = filter_input(INPUT_POST, 'price');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['image'];

require_once('database.php');
require_once('image_util.php');

$base_dir = 'images/';

// Check for duplicate title
$queryBooks = 'SELECT * FROM books';
$statement1 = $db->prepare($queryBooks);
$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($books as $book) {
    if ($title === $book["title"]) {
        $_SESSION["add_error"] = "Invalid data, Duplicate Title. Try again.";
        header("Location: error.php");
        die();
    }
}

if ($title == null || $author == null ||
    $isbn == null || $year == null ||
    $price == null || $type_id === null) {
        $_SESSION["add_error"] = "Invalid book data, Check all fields and try again.";
        header("Location: error.php");
        die();
    }

$image_name = '';  // default empty

if ($image && $image['error'] === UPLOAD_ERR_OK) {
    // Process new image
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    // Save _100 version in DB
    $dot_pos = strrpos($original_filename, '.');
    $name_100 = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
    $image_name = $name_100;
} else {
    // Use placeholder
    $placeholder = 'placeholder.jpg';
    $placeholder_100 = 'placeholder_100.jpg';
    $placeholder_400 = 'placeholder_400.jpg';

    if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
        process_image($base_dir, $placeholder);
    }

    $image_name = $placeholder_100;
}

$query = 'INSERT INTO books
            (title, author, isbn, year, pages, price, typeID, imageName)
            VALUES
            (:title, :author, :isbn, :year, :pages, :price, :typeID, :imageName)';

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':author', $author);
$statement->bindValue(':isbn', $isbn);
$statement->bindValue(':year', $year);
$statement->bindValue(':pages', $pages);
$statement->bindValue(':price', $price);
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->execute();
$statement->closeCursor();

$_SESSION["bookName"] = $title;
header("Location: confirmation.php");
die();