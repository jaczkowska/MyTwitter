<?php

class Users {

    private $id;
    private $email;
    private $username;
    private $hashedPassword;

    public function __construct() {
        $this->id = -1;
        $this->email = "";
        $this->username = "";
        $this->hashedPassword = "";
    }

    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getUsername() {
        return $this->username;
    }

    function getHashedPassword() {
        return $this->hashedPassword;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setHashedPassword($newPassword) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->hashedPassword = $newHashedPassword;
    }

    //password verification
    public function verifyPassword($password) {
        return password_verify($password, $this->hashedPassword);
    }

    // SAVE new User to database
    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            //Saving new user to DB
            $sql = "INSERT INTO users(username, email, hashed_password)
                VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
    // UPDATE User in database
        } else {
            $sql = "UPDATE users SET username='$this->username',
                email='$this->email',
                hashed_password='$this->hashedPassword'
                WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }

    //DELETE User from database
    public function delete(mysqli $connection) {
        if ($this->id != -1) {
            $sql = "DELETE FROM Users WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    //LOAD 1 User from database
    static public function loadUserById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Users WHERE id=$id";
        $result = $connection->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedUser = new Users();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    //LOAD all Users from database
    static public function loadAllUsers(mysqli $connection) {
        $sql = "SELECT * FROM Users";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new Users();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    
    static public function loginUser(mysqli $connection, $email, $password) {
        $sql = "SELECT * FROM Users WHERE email='$email'";
        $result = $connection->query($sql);
        $row = $result->fetch_assoc();

        if ($result == true && $result->num_rows == 1) {
            if (password_verify($password, $row['hashed_password'])) {

                $loadedUser = new Users();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                return $loadedUser;
            }
        }

        return false;
    }

}
