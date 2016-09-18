<?php

class Comment {

    private $id;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;

    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->tweetId = 0;
        $this->creationDate = 0;
        $this->text = 0;
    }

    public function setUserId($newUserId) {
        $this->userId = $newUserId;
    }

    public function setTweetId($newTweetId) {
        $this->tweetId = $newTweetId;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function setCreationDate($newDate) {
        $this->creationDate = $newDate;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    //SAVING COMMENTS INTO DB//
    public function saveToDB(mysqli $connection) {
        
        if ($this->id == -1) {
            $db = "INSERT INTO comment(user_id, tweet_id, creation_date, text)
                    VALUES ($this->userId, $this->tweetId, '$this->creationDate', '$this->text')";
            $result = $connection->query($db);
            
            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
        }
        return false;
    }

    //LOAD COMMENTS by user id FROM DB//
    static public function loadCommentById(mysqli $connection, $id) {
        $db = "SELECT * FROM comment WHERE id=$id";
        $result = $connection->query($db);
        
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadComment = new Comment();
            $loadComment->id = $row['id'];
            $loadComment->userId = $row['user_id'];
            $loadComment->tweetId = $row['tweet_id'];
            $loadComment->creationDate = $row['creation_date'];
            $loadComment->text = $row['text'];
            
            return $loadComment;
        }
    }

    // LOAD all COMMENTS by tweet id FROM DB
    static public function loadAllCommentsByTweetId(mysqli $connection, $tweetId) {
        $db = "SELECT * FROM comment WHERE tweet_id = $tweetId ORDER BY creation_date DESC";
        $result = $connection->query($db);
        $allComments = [];
        if ($result && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->tweetId = $row['tweet_id'];
                $loadedComment->creationDate = $row['creation_date'];
                $loadedComment->text = $row['text'];
                $allComments[] = $loadedComment;
            }
        }
        return $allComments;
    }

}
