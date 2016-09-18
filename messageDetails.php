<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';
require_once 'src/Message.php';


if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginRegister.php');
} else {
    $loggedUserId = $_SESSION['loggedUserId'];
    $loggedUser = Users::loadUserById($conn, $loggedUserId);
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $message = Message::loadMessageById($conn, $id);
} else {
    header('Location: index.php');
}
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
                <div class="col-sm-offset-2 col-sm-8 main paddingTweetBox">
                    <h4 style="color: white;">Message details</h4>
                    <table class="table">

                        <?php
                        $senderId = $message->getSenderId();
                        $receiverId = $message->getReceiverId();
                        $sender = Users::loadUserById($conn, $senderId)->getUsername();
                        $receiver = Users::loadUserById($conn, $receiverId)->getUsername();
                        $creationDate = $message->getCreationDate();
                        $text = $message->getText();
                        echo "<thead>
                                <tr>
                                    <th style='color:white;'> From: <a href='userPage.php?id=$senderId'>$sender</a></th>
                                    <th style='color:white;'> To: <a href='userPage.php?id=$receiverId'>$receiver</a></th>
                                    <th style='color:white;'> Date: $creationDate </th>
                                </tr>
                            </thead>
                            <tbody>
                                        <tr>
                                        <td style='color:white;' colspan='3'>$text</td>
                                        </tr>
                        </tbody>";

                        echo "<tr><td></td></tr>";
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>
