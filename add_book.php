<?php
    session_start();

    require_once 'image_util.php'; // the process_image function

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    if (isset($_FILES['file1']))
    {
        $filename = $_FILES['file1']['name'];

        if (!empty($filename))
        {
            $source = $_FILES['file1']['tmp_name'];

            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

            move_uploaded_file($source, $target);

            // create the '400' and '100' versions of the image
            process_image($image_dir_path, $filename);
        }
    }

    // get data from the form
    $title = filter_input(INPUT_POST, 'title');
    // alternative
    // $author = $_POST['author'];
    $author = filter_input(INPUT_POST, 'author');
    $isbn = filter_input(INPUT_POST, 'isbn');
    $year = filter_input(INPUT_POST, 'year');
    $pages = filter_input(INPUT_POST, 'pages'); // assigns the value of the selected radio button
    $price = filter_input(INPUT_POST, 'price');
    $image_name = $_FILES['file1']['name'];

    require_once('database.php');
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();

    $statement1->closeCursor();

    foreach ($books as $book)
    {
        if ($title == $book["title"])
        {
            $_SESSION["add_error"] = "Invalid data, Duplicate Book. Try again.";

            $url = "error.php";
            header("Location: " . $url);
            die();
        }
    }

    if ($title == null || $author == null ||
        $isbn == null || $year == null ||
        $price == null)
    {
        $_SESSION["add_error"] = "Invalid book data, Check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die();
    }
    else
    {        

        require_once('database.php');

        // Add the book to the database
        $query = 'INSERT INTO books
            (title, author, isbn, year, pages, price, imageName)
            VALUES
            (:title, :author, :isbn, :year, :pages, :price, :imageName)';

        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':author', $author);
        $statement->bindValue(':isbn', $isbn);
        $statement->bindValue(':year', $year);
        $statement->bindValue(':pages', $pages);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':imageName', $image_name);

        $statement->execute();
        $statement->closeCursor();

    }
    $_SESSION["bookName"] = $title;

    // redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); // releases add_book.php from memory

?>