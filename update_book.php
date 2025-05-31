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

        // Update the book in the database
        $query = 'UPDATE books
            SET title = :title,
            author = :author,
            isbn = :isbn,
            year = :year,
            pages = :pages,
            price =:price
            WHERE bookID = :bookID';

        $statement = $db->prepare($query);
        $statement->bindValue(':bookID', $book_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':author', $author);
        $statement->bindValue(':isbn', $isbn);
        $statement->bindValue(':year', $year);
        $statement->bindValue(':pages', $pages);
        $statement->bindValue(':price', $price);

        $statement->execute();
        $statement->closeCursor();

    }
    $_SESSION["bookName"] = $title;

    // redirect to confirmation page
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die(); // releases add_book.php from memory

?>