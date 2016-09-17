<?php

class Tweet {

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->text = 0;
        $this->creationDate = 0;
    }

    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getText() {
        return $this->text;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setCreationDate($newDate) {
        $this->creationDate = $newDate;
    }

    //LOAD Tweet from db
    static public function loadTweetById(mysqli $connection, $id) {
        $db = "SELECT * FROM Tweet WHERE id=$id";
        $result = $connection->query($db);
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadTweet = new Tweet();
            $loadTweet->id = $row['id'];
            $loadTweet->userId = $row['user_id'];
            $loadTweet->text = $row['text'];
            $loadTweet->creationDate = $row['creation_date'];
            return $loadTweet;
        }
    }

    //LOAD ALL Tweets from db by User Id
    static public function loadAllTweetsByUserId(mysqli $connection, $userId) {

        $db = "SELECT * FROM Tweet WHERE user_id = $userId ORDER BY creation_date DESC";
        $result = $connection->query($db);

        $allTweets = [];
        if ($result && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadTweets = new Tweet();
                $loadTweets->id = $row['id'];
                $loadTweets->userId = $row['user_id'];
                $loadTweets->text = $row['text'];
                $loadTweets->creationDate = $row['creation_date'];
                $allTweets[] = $loadTweets;
            }
        }

        return $allTweets;
    }

    //LOAD ALL Tweets from db
    static public function loadAllTweets(mysqli $connection) {
        $db = "SELECT * FROM Tweet ORDER BY creation_date DESC";
        $result = $connection->query($bd);

        $allTweets = [];
        if ($result && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadTweets = new Tweet();
                $loadTweets->id = $row['id'];
                $loadTweets->userId = $row['user_id'];
                $loadTweets->text = $row['text'];
                $loadTweets->creationDate = $row['creation_date'];
                $allTweets[] = $loadTweets;
            }
        }

        return $allTweets;
    }

    //SAVE Tweet to db
    public function saveToDB(mysqli $connection) {

        if ($this->id == -1) {
            $db = "INSERT INTO Tweet(user_id, text, creation_date) VALUES ($this->userId, '$this->text', '$this->creationDate')";
            $result = $connection->query($db);
            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
        }
        return false;
    }

    //UPDATE Tweet in db
    public function updateTweet(mysqli $connection) {

        if ($this->id == -1) {
            $db = "UPDATE Tweet SET user_id=$this->username, text=$this->text, creation_date=$this->creationDate WHERE id=$this->id";
            $result = $connection->query($db);
            
            if ($result == true) {
                return true;
            }
        return false;
        }
    }

}
