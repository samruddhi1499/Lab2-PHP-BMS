<?php
class Book {
    public $title;
    public $author;
    public $year;

    public function __construct($title, $author, $year) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
    }
}

class BookManager {
    private static $books = array();

    public static function addBook($title, $author, $year) {
        $book = new Book($title, $author, $year);
        self::$books[] = $book;
    }

    public static function getBooks() {
        return self::$books;
    }
}

// Process form submission
if (isset($_POST['title']) && isset($_POST['author']) && isset($_POST['year'])) {
    BookManager::addBook($_POST['title'], $_POST['author'], $_POST['year']);
}

// Return the list of books as a JSON response
$books = BookManager::getBooks();
echo json_encode($books);
?>

<!-- index.html -->
<html>
  <head>
    <title>Book Management System</title>
  </head>
  <body>
    <h1>Book Management System</h1>
    <form id="book-form">
      <label for="title">Title:</label>
      <input type="text" id="title" name="title"><br><br>
      <label for="author">Author:</label>
      <input type="text" id="author" name="author"><br><br>
      <label for="year">Year:</label>
      <input type="number" id="year" name="year"><br><br>
      <input type="submit" value="Add Book">
    </form>
    <div id="book-list"></div>

    <script>
      const form = document.getElementById('book-form');
      const bookList = document.getElementById('book-list');

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const title = document.getElementById('title').value;
        const author = document.getElementById('author').value;
        const year = document.getElementById('year').value;

        fetch('index.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ title, author, year })
        })
        .then(response => response.json())
        .then((books) => {
          bookList.innerHTML = '';
          books.forEach((book) => {
            const bookHTML = `
              <h2>${book.title}</h2>
              <p>Author: ${book.author}</p>
              <p>Year: ${book.year}</p>
            `;
            bookList.innerHTML += bookHTML;
          });
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      });
    </script>
  </body>
</html>