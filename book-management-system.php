<?php
// using requre to make use of Book.php
require 'Book.php';

// using session _start to start the session
// using session to persist data in books  array.
// observed without  session, the data is lost after php script is executed again

session_start();
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}


$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if action is reset the data is deleted from array by ressting the session
    if (isset($_POST['reset'])) {
        session_unset();
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else { // else checking the form input
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $year = $_POST['year'] ?? '';

        try {
            // using regex to validate the entered input
            if (empty($title) || empty($author) || empty($year)) {
                $errors[] = "All fields are required.";
            }
            if (!preg_match("/^[a-zA-Z0-9\s\.\:\"\?\'\-]+$/", $title)) {
                $errors[] = "Invalid book title.";
            }
            if (!preg_match("/^[a-zA-Z\s\.]+$/", $author)) {
                $errors[] = "Invalid author name.";
            }
            if (!preg_match("/^[0-9]{4}$/", $year)) {
                $errors[] = "Invalid year.";
            }

            if (empty($errors)) {
                $book = new Book($title, $author, $year);
                $_SESSION['books'][] = $book;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

// displaying the books detail stored in array using for each loop in html table
function displayBooks($books) {
    if (count($books) > 0) {
        echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
        echo "<tr><th style='background-color: #b3ebf2;'>Title</th><th style='background-color: #b3ebf2;'>Author</th><th style='background-color: #b3ebf2;'>Year</th></tr>";
        foreach ($books as $book) {
            echo "<tr><td style='padding: 10px;'>{$book->getTitle()}</td><td style='padding: 10px;'>{$book->getAuthor()}</td><td style='padding: 10px;'>{$book->getYear()}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: gray;'>No books have been added yet.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
        }
        header {
            margin-bottom: 20px;
            padding-left: 20px;
            padding-right: 20px;
        }
        header h1 {
            font-size: 2.5em;
        }
        .image-container {
            position: relative;
            margin-bottom: 20px;
        }
        img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .form-container {
            width: 40%;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-right: 30px;
        }
        .table-container {
            width: 55%;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h2 {
            color: #333;
        }
        input[type="text"], input[type="number"], input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4a9dae;
            color: white;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #77cbda;
        }
        .error {
            color: red;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Book Management System</h1>
    </header>
    <div class="image-container">
        <img src="./images/books.jpg" alt="Books" />
        <div class="overlay"></div>
    </div>
    <div class="container">
        <div class="form-container">
            <h2>Add a New Book</h2>
            <form method="post">
                <label>Book Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
                <?php if (in_array("Invalid book title.", $errors)) echo "<div class='error'>Invalid book title.</div>"; ?>
                <?php if (in_array("All fields are required.", $errors)) echo "<div class='error'>All fields are required.</div>"; ?>

                <label>Author:</label>
                <input type="text" name="author" value="<?php echo htmlspecialchars($author ?? ''); ?>" required>
                <?php if (in_array("Invalid author name.", $errors)) echo "<div class='error'>Invalid author name.</div>"; ?>

                <label>Year:</label>
                <input type="number" name="year" value="<?php echo htmlspecialchars($year ?? ''); ?>" required>
                <?php if (in_array("Invalid year.", $errors)) echo "<div class='error'>Invalid year.</div>"; ?>

                <input type="submit" value="Add Book">
            </form>

            <h2>Reset Books</h2>
            <form method="post">
                <input type="submit" name="reset" value="Reset All Books">
            </form>
        </div>

        <div class="table-container">
            <h2>List of Books</h2>
            <?php 
            // calling displayBooks function
            displayBooks($_SESSION['books']); 
            ?>
        </div>
    </div>
</body>
</html>
