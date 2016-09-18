<?php

/*
  CREATE TABLE `message` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `reciver_id` INT NOT NULL,
  `read` INT NOT NULL,
  `creation_date` DATE,
  `text` text(255) NOT NULL,
  PRIMARY KEY(`id`),
  FOREIGN KEY(`sender_id`) REFERENCES `users`(`id`),
  FOREIGN KEY(`reciver_id`) REFERENCES `users`(`id`)
  );
 */

class Message {

    private $id;
    private $senderId;
    private $receiverId;
    private $creationDate;
    private $text;
    private $read;

    public function __construct() {
        $this->id = -1;
        $this->senderId = 0;
        $this->receiverId = 0;
        $this->creationDate = 0;
        $this->text = 0;
        $this->read = 0;
    }

    public function setSenderId($newSenderId) {
        $this->senderId = $newSenderId;
    }

    public function setReceiverId($newReceiverId) {
        $this->receiverId = $newReceiverId;
    }

    public function setCreationDate($newCreationDate) {
        $this->creationDate = $newCreationDate;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function readMessage() {
        $this->read = 1;
    }

    public function getId() {
        return $this->id;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    public function isRead() {
        return $this->read;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $db = "INSERT INTO message(sender_id, receiver_id, creation_date, text, `read`) VALUES ($this->senderId, $this->receiverId, '$this->creationDate', '$this->text', $this->read)";
            $result = $connection->query($db);
            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
        }
        return false;
    }

    static public function loadMessageById(mysqli $connection, $id) {
        $db = "SELECT * FROM message WHERE id=$id";
        $result = $connection->query($db);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadMessage = new Message();
            $loadMessage->id = $row['id'];
            $loadMessage->senderId = $row['sender_id'];
            $loadMessage->receiverId = $row['receiver_id'];
            $loadMessage->creationDate = $row['creation_date'];
            $loadMessage->text = $row['text'];
            $loadMessage->read = $row['read'];

            return $loadMessage;
        }
    }

    static public function loadAllMessagesBySenderId(mysqli $connection, $senderId) {
        $db = "SELECT * FROM message WHERE sender_id = $senderId ORDER BY creation_date DESC";
        $result = $connection->query($db);
        $allSenderMess = [];
        if ($result && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadMessage = new Message();
                $loadMessage->id = $row['id'];
                $loadMessage->senderId = $row['sender_id'];
                $loadMessage->receiverId = $row['receiver_id'];
                $loadMessage->creationDate = $row['creation_date'];
                $loadMessage->text = substr($row['text'], 0, 30) . '...';
                $loadMessage->read = $row['read'];
                
                $allSenderMess[] = $loadMessage;
            }
        }
        return $allSenderMess;
    }

    static public function loadAllMessagesByReceiverId(mysqli $connection, $receiverId) {
        $db = "SELECT * FROM message WHERE receiver_id = $receiverId ORDER BY creation_date DESC";
        $result = $connection->query($db);
        $allReceiverMess = [];
        if ($result && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadMessage = new Message();
                $loadMessage->id = $row['id'];
                $loadMessage->senderId = $row['sender_id'];
                $loadMessage->receiverId = $row['receiver_id'];
                $loadMessage->creationDate = $row['creation_date'];
                $loadMessage->text = substr($row['text'], 0, 30) . '...';
                $loadMessage->read = $row['read'];
                
                $allReceiverMess[] = $loadMessage;
            }
        }
        return $allReceiverMess;
    }

}
