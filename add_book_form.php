
<?php
    
    require_once('database.php');
    $queryTypes = 'SELECT * FROM types';
    $statement = $db->prepare($queryTypes);
    $statement->execute();
    $types = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Library Manager - Add Book</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Add Book</h2>

            <form action="add_book.php" method="post" id="add_book_form"
                enctype="multipart/form-data">

                <div id="data">

                    <label>Title:</label>
                    <input type="text" name="title" /><br />

                    <label>Author:</label>
                    <input type="text" name="author" /><br />

                    <label>ISBN:</label>
                    <input type="text" name="isbn" /><br />

                    <label>Year:</label>
                    <input type="text" name="year" /><br />

                    <label>Pages:</label>
                    <input type="text" name="pages" /><br />

                    <label>Price:</label>
                    <input type="text" name="price" /><br />

                    <label>Book Type:</label>
                    <select name="type_id">
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['typeID']; ?>">
                                <?php echo $type['bookType']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br />

                    <label>Upload Image:</label>
                    <input type="file" name="file1" /><br />

                </div>

                <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Save Book" /><br />

                </div>

            </form>

            <p id="viewBook"><a href="index.php">View Book List</a></p>
            
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>
