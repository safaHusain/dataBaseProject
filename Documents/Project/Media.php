<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Media
 *
 * @author safa
 */
class Media {

    private $articleId;
    private $name;
    private $type;
    private $size;
    private $data;

//    public $dbc = null;
//
//    public function __construct() {
//        $this->getDBConnection();
//    }
//
//    private function getDBConnection() {
//        include_once "Connection.php";
//
//        try {
//            if ($this->dbc == null) {
//                $db = new Connection();
//                $this->dbc = $db->getConnection();
//            }
//            return $this->dbc;
//        } catch (Exception $e) {
//            echo 'Caught exception: ' . $e->getMessage();
//            return null;
//        }
//    }

    public function initWith($articleId, $name, $type, $size, $data) {
        $this->articleId = $articleId;
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->data = $data;
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('select * from projectMedia where article_id = ' . $id);
        $this->initWith($data->article_id, $data->name, $data->type, $data->size, $data->data);
    }

    public function getArticleId() {
        return $this->articleId;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getSize() {
        return $this->size;
    }

    public function getData() {
        return $this->data;
    }

    public function setArticleId($articleId) {
        $this->articleId = $articleId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function saveMedia() {
        $db = Database::getInstance();
        $query = "INSERT INTO projectMedia (article_id, name, type, size) "
                . "VALUES ('$this->articleId', '$this->name', '$this->type', '$this->size')";

        if ($db->querySQL($query)) {
            return true;
        } else {
            echo "Error: " . $query . "<br>" . $this->dbc->error;
            return false;
        }
    }

    public function updateMedia() {
        $db = Database::getInstance();
        $query = "update projectMedia set name = '$this->name' , type = '$this->type' , size = '$this->size'"
                . " where article_id = '$this->articleId'";
        //echo $query;

        if ($db->querySQL($query)) {
            return true;
        } else {
            echo "Error: " . $query . "<br>" . $this->dbc->error;
            return false;
        }
    }

    public function deleteMedia() {
        try {
            $db = Database::getInstance();
            $data = "delete from projectMedia where article_id = '$this->articleId'";
            $result = $db->querySQL($data);
            return $result;
        } catch (Exception $exc) {
            echo 'error: ' . $exc;
            return false;
        }
    }

}
