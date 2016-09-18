<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';
require_once 'src/Message.php';


if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginRegister.php');
} else {
    $loggedUserId = $_SESSION['loggedUserId'];
    $loggedUser = Users::loadUserById($conn, $loggedUserId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['text']) &&
        isset($_POST['senderId']) &&
        isset($_POST['receiverId'])) {

    $newMessage = new Message();
    $newMessage->setText($_POST['text']);
    $newMessage->setCreationDate(date('Y-m-d H:i:s'));
    $newMessage->setSenderId($_POST['senderId']);
    $newMessage->setReceiverId($_POST['receiverId']);
    $result = $newMessage->saveToDB($conn);
}
$sentMessages = Message::loadAllMessagesBySenderId($conn, $loggedUserId);
$receivedMessages = Message::loadAllMessagesByReceiverId($conn, $loggedUserId);
?>

<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="fonts/glyphicons-halfilings-regular.svg">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <title>MyTwitter</title>
    </head>
    <body class="bgMessage">

        <!-- HEADER NAVBAR -->
        <?php require_once 'layouts/navbar.php'; ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-5 main paddingTweetBox">
                    <h4 style="color: white;">Received Messages</h4>
                    <table class="table">

                            <?php
                            foreach ($receivedMessages as $message) {
                                $messageId = $message->getId();
                                $senderId = $message->getSenderId();
                                $sender = Users::loadUserById($conn, $senderId)->getUsername();
                                $creationDate = $message->getCreationDate();
                                $text = $message->getText();
                                
                                echo "<thead>
                                        <tr>
                                            <th style='color:white;'> From: <a href='userPage.php?id=$senderId'>$sender</a></th>
                                            <th style='color:white;'> Date: $creationDate </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td style='color:white;' colspan='2'>$text <a style='color:white;' href='messageDetails.php?id=$messageId'>Show all</a></td>
                                        </tr>";

                                     echo "<tr><td ></td></tr>
                                     </tbody>";
                            }
                            ?>
                    
                    </table>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-5 main paddingTweetBox">
                    <h4 style="color: white;">Sended Messages</h4>
                    <table class="table">
                    
                        
                            <?php
                            foreach ($sentMessages as $message) {
                                $messageId = $message->getId();
                                $receiverId = $message->getReceiverId();
                                $receiver = Users::loadUserById($conn, $receiverId)->getUsername();
                                $creationDate = $message->getCreationDate();
                                $text = $message->getText();
                                echo "<thead>
                                        <tr>
                                            <th style='color:white;'> To: <a href='userPage.php?id=$receiverId'>$receiver</a></th>
                                            <th style='color:white;'> Date: $creationDate </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td colspan='2' style='color:white;'>$text <a style='color:white;' href='messageDetails.php?id=$messageId'>Show all</a></td>
                                        </tr>";

                                echo "<tr><td></td></tr>
                                 </tbody>";
                            }
                            ?>
                    </table>
                </div>
            </div>

            <!-- FOOTER -->
            <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>