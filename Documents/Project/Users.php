<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Users
 *
 * @author safa
 */
class Users {

    private $uid;
    private $username;
    private $email;
    private $regDate;
    private $password;
    private $role;

    function __construct() {
        $this->uid = null;
        $this->username = null;
        $this->email = null;
        $this->regDate = null;
        $this->password = null;
        $this->role = null;
    }

    public function setUid($uid) {
        $this->uid = $uid;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRegDate($regDate): void {
        $this->regDate = $regDate;
    }

    public function setRole($role): void {
        $this->role = $role;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getUid() {
        return $this->uid;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRegDate() {
        return $this->regDate;
    }

    public function getRole() {
        return $this->role;
    }

    public function getPassword() {
        return $this->password;
    }

    function initWithUid($uid) {
        $db = Database::getInstance();
        $data = $db->singleFetch('select * from projectUsers where uid = ' . $uid);
        $this->initWith($data->uid, $data->username, $data->email, $data->password, $data->regDate, $data->role);
    }

    function initWithUsername() {
        $db = Database::getInstance();
        $data = $db->singleFetch('select * from projectUsers where username = \'' . $this->username . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function initWith($uid, $username, $email, $password, $regDate, $role) {
        $this->uid = $uid;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->regDate = $regDate;
        $this->role = $role;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->username)) {
            $errors = false;
        } else {
            if (!$this->initWithUsername()) {
                $errors = false;
            }
        }

        if (empty($this->email)) {
            $errors = false;
        }

        if (empty($this->password)) {
            $errors = false;
        }

        return $errors;
    }

    function registerUser() {
        if ($this->isValid()) {
            try {
                $hashed_pwd = password_hash($this->password, PASSWORD_DEFAULT);
                $db = Database::getInstance();
                $data = "insert into projectUsers (uid, username, email, RegDate, password, role) values (null, '$this->username', '$this->email', NOW(), '$hashed_pwd', 'user')";
                $db->querySQL($data);
                //echo $data;
                return true;
            } catch (Exception $ex) {
                echo 'exception: ' . $ex;
                return false;
            }
        } else {
            return false;
        }
    }

    function updateDB() {
        if ($this->isValid()) {

            $db = Database::getInstance();
            $data = 'update projectUsers set email = \'' . $this->email . '\', username = \'' . $this->username . '\', password = \'' . $this->password . '\' where uid = ' . $this->uid;
            $db->querySQL($data);
            return true;
        }
        return false;
    }

    function deleteUser() {
        try {
            $db = Database::getInstance();
            $data = $db->querySQL('delete from projectUsers where uid = ' . $this->uid);
            return true;
        } catch (Exception $ex) {
            echo 'exception: ' . $ex;
            return false;
        }
    }

    function checkUser($username, $password) {
        $db = Database::getInstance();

        $query = $db->singleFetch("select * from projectUsers where username = '$username'");
        //$query = mysqli_query($db, "select password from projectUsers where username = '$username'");
        $retrieved_pwd = $query->password;
        echo $retrieved_pwd;
        if (!empty($retrieved_pwd)) {
            if (password_verify($password, $retrieved_pwd)) {
                $data = $db->singleFetch('select * from projectUsers where username = \'' . $username . '\' and password = \'' . $retrieved_pwd . '\'');
                $this->initWith($data->uid, $data->username, $data->email, $data->password, $data->regDate, $data->role);
            }
        }
    }

    function login($username, $password) {
        try {
            $this->checkUser($username, $password);
            if ($this->getUid() != null) {
                $_SESSION['uid'] = $this->getUid();
                $_SESSION['username'] = $this->getUsername();
                $_SESSION['role'] = $this->getRole();
                return true;
            } else {
                $error[] = 'wrong username or password';
            }
            return false;
        } catch (Exception $ex) {
            $error = $ex->getMessage();
        }
        return false;
    }

    function logout() {
        $_SESSION['uid'] = '';
        $_SESSION['username'] = '';
        $_SESSION['role'] = '';
        session_destroy();
    }

}

?>