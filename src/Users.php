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
            $db = "INSERT INTO users(username, email, hashed_password)
                VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connection->query($db);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
            // UPDATE User in database
        } else {
            $db = "UPDATE users SET username='$this->username',
                email='$this->email',
                hashed_password='$this->hashedPassword'
                WHERE id=$this->id";
            $result = $connection->query($db);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }

    //DELETE User from database
    public function delete(mysqli $connection) {
        if ($this->id != -1) {
            $db = "DELETE FROM users WHERE id=$this->id";
            $result = $connection->query($db);
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
        $db = "SELECT * FROM users WHERE id=$id";
        $result = $connection->query($db);

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
        $db = "SELECT * FROM users";
        $allUsers = [];
        $result = $connection->query($db);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new Users();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                $allUsers[] = $loadedUser;
            }
        }
        return $allUsers;
    }
    
    static public function loadUserByEmail(mysqli $connection, $email) {
        
        $db = "SELECT * FROM users WHERE email='$email'";
        
        $result = $connection->query($db);
        
        if($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedUser= new Users();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            
            return $loadedUser;
        }
        
        return null;
        
    }

    static public function loginUser(mysqli $connection, $email, $password) {
        $db = "SELECT * FROM users WHERE email='$email'";
        $result = $connection->query($db);
        $row = $result->fetch_assoc();

        if ($result == true && $result->num_rows == 1) {
            if (password_verify($password, $row['hashed_password'])) {

                $loadedUser = new Users();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->email = $row['email'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                return $loadedUser;
            }
        }

        return false;
    }

}
