<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Articles
 *
 * @author safa
 */
class Articles {

    private $articleID;
    private $title;
    private $category;
    private $text;
    private $status;
    private $publishedBy;
    private $publishDate;
    public $dbc = null;

    public function __construct() {
        $this->getDBConnection();
    }

    private function getDBConnection() {
        include_once "Connection.php";

        try {
            if ($this->dbc == null) {
                $db = new Connection();
                $this->dbc = $db->getConnection();
            }
            return $this->dbc;
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage();
            return null;
        }
    }

    public function getArticleID() {
        return $this->articleID;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getText() {
        return $this->text;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getPublishedBy() {
        return $this->publishedBy;
    }

    public function getPublishDate() {
        return $this->publishDate;
    }

    public function setArticleID($articleID): void {
        $this->articleID = $articleID;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    public function setCategory($category): void {
        $this->category = $category;
    }

    public function setText($text): void {
        $this->text = $text;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

    public function setPublishedBy($publishedBy): void {
        $this->publishedBy = $publishedBy;
    }

    public function setPublishDate($publishDate): void {
        $this->publishDate = $publishDate;
    }

    public function initWith($articleID, $title, $category, $text, $status, $publishedBy, $publishDate) {
        $this->articleID = $articleID;
        $this->title = $title;
        $this->category = $category;
        $this->text = $text;
        $this->status = $status;
        $this->publishedBy = $publishedBy;
        $this->publishDate = $publishDate;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->title)) {
            $errors = false;
        }

        if (empty($this->text)) {
            $errors = false;
        }


        if (empty($this->category)) {
            $errors = false;
        }

        return $errors;
    }

    public function getLastID() {
        $db = Database::getInstance();
        $article_id = mysqli_insert_id($db);
        return $article_id;
    }

    public function addArticle() {
        $db = Database::getInstance();
        $author = $_SESSION['username'];

        $query = "INSERT INTO projectArticles (title, category, text, status, publishedBy, publishDate) "
                . "VALUES ('$this->title', '$this->category', '$this->text', 0, '$author', null)";

        $db->querySQL($query);

        $data = $db->singleFetch("select articleID from projectArticles where text = '$this->text'");
        $article_id = $data->articleID;
        echo 'article id ' . $article_id;
        return $article_id;
    }

    public function updateArticle() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = "update projectArticles set title = '$this->title', category = '$this->category', text = '$this->text'"
                        . " where articleID = '$this->articleID'";
                $db->querySQL($data);
                return true;
            } catch (Exception $exc) {
                echo 'error: ' . $exc;
            }
        } else {
            return false;
        }
    }

    public function deleteArticle() {
        
            $db = Database::getInstance();
            $data = "delete from projectArticles where articleID = '$this->articleID'";
            //echo $data;
            $db->querySQL($data);
            return true;

    }

    public function publishSavedArticle() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = "update projectArticles set title = '$this->title', category = '$this->category', text = '$this->text',status = 1, publishDate = NOW()"
                        . " where articleID = '$this->articleID'";
                $db->querySQL($data);
                return true;
            } catch (Exception $exc) {
                echo 'error: ' . $exc;
            }
        } else {
            return false;
        }
    }

    public function publishArticle() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $author = $_SESSION['username'];
                $data = "insert into projectArticles (articleID, title, category, text, status, publishedBy, publishDate)"
                        . " values (null, '$this->title', '$this->category', '$this->text', 1, '$author', NOW())";
                $db->querySQL($data);

                $query = $db->singleFetch("select articleID from projectArticles where text = '$this->text'");
                $article_id = $query->articleID;
                echo 'article id ' . $article_id;
                return $article_id;
            } catch (Exception $exc) {
                echo 'error: ' . $exc;
            }
        } else {
            return false;
        }
    }

    function getAllArticles() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from projectArticles');
        return $data;
    }

    function getAllPublishedArticles() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from projectArticles where status = 1');
        return $data;
    }

    function getAllUnpublishedArticlesForAuthor() {
        $author = $_SESSION['username'];
        $db = Database::getInstance();
        $data = $db->multiFetch("Select * from projectArticles where status = 0 and publishedBy = '$author'");
        return $data;
    }
    
    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('select * from projectArticles where articleID = ' . $id);
        $this->initWith($data->articleID, $data->title, $data->category, $data->text, $data->status, $data->publishedBy, $data->publishDate);
    }

}
