<?php
class Book {
    private $title;
    private $author;
    private $year;

    public function __construct($title, $author, $year) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
    }
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setAuthor($author) {
        $this->author =  $author;

    }

    public function setYear($year) {
        $this->year  = $year;

    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getYear() {
        return $this->year;
    }
}
?>
