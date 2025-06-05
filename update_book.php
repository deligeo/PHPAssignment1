<?php
    session_start();

    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    // get data from the form
    $title = filter_input(INPUT_POST, 'title');
    // alternative
    // $title = $_POST['title'];
    $author = filter_input(INPUT_POST, 'author');
    $isbn = filter_input(INPUT_POST, 'isbn');
    $year = filter_input(INPUT_POST, 'year');
    $pages = filter_input(INPUT_POST, 'pages'); // assigns the value of the selected radio button
    $price = filter_input(INPUT_POST, 'price');

    $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
    $image = $_FILES['image'];
    
    require_once('database.php');
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();
    $statement1->closeCursor();

    foreach ($books as $book)
    {
        if ($title == $book["title"] && ($book_id) != ($book["bookID"]))
        {
            $_SESSION["add_error"] = "Invalid data, Duplicate Book. Try again.";

            $url = "error.php";
            header("Location: " . $url);
            die();
        }
    }

    if ($title == null || $author == null ||
        $isbn == null || $year == null ||
        $price == null || $type_id === null) 

    {
        $_SESSION["add_error"] = "Invalid book data, Check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    require_once('image_util.php');

    // Get current image name from database
    $query = 'SELECT imageName FROM books WHERE bookID = :bookID';
    $statement = $db->prepare($query);
    $statement->bindValue(':bookID', $book_id);
    $statement->execute();
    $current = $statement->fetch();
    $current_image_name = $current['imageName'];
    $statement->closeCursor();

    $image_name = $current_image_name;

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        // Delete old image files if they exist
        $base_dir = 'images/';
        if ($current_image_name) {
            $dot = strrpos($current_image_name, '_100.');
            if ($dot !== false) {
                $original_name = substr($current_image_name, 0, $dot) . substr($current_image_name, $dot + 4);
                $original = $base_dir . $original_name;
                $img_100 = $base_dir . $current_image_name;
                $img_400 = $base_dir . substr($current_image_name, 0, $dot) . '_400' . substr($current_image_name, $dot + 4);

                if (file_exists($original)) unlink($original);
                if (file_exists($img_100)) unlink($img_100);
                if (file_exists($img_400)) unlink($img_400);
            }
        }

        // Upload and process new image
        $original_filename = basename($image['name']);
        $upload_path = $base_dir . $original_filename;
        move_uploaded_file($image['tmp_name'], $upload_path);
        process_image($base_dir, $original_filename);

        // Save new _100 filename for database
        $dot_position = strrpos($original_filename, '.');
        $name_without_ext = substr($original_filename, 0, $dot_position);
        $extension = substr($original_filename, $dot_position);
        $image_name = $name_without_ext . '_100' . $extension;
    }

        // Update the book in the database
        $query = 'UPDATE books
            SET title = :title,
            author = :author,
            isbn = :isbn,
            year = :year,
            pages = :pages,
            price =:price,
            typeID = :typeID,
            imageName = :imageName
            WHERE bookID = :bookID';

        $statement = $db->prepare($query);
        $statement->bindValue(':bookID', $book_id);
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

    // redirect to confirmation page
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die(); // releases add_book.php from memory

?>