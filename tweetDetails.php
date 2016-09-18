<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';


if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginRegister.php');
} else {
    $loggedUserId = $_SESSION['loggedUserId'];
    $loggedUser = Users::loadUserById($conn, $loggedUserId);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tweet = Tweet::loadTweetById($conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['text'])) {
        $newComment = new Comment();
        $newComment->setText($_POST['text']);
        $newComment->setCreationDate(date('Y-m-d H:i:s'));
        $newComment->setUserId($loggedUserId);
        $newComment->setTweetId($id);
        $newComment->saveToDB($conn);
    }
    $comments = Comment::loadAllCommentsByTweetId($conn, $id);
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

    <body class="bgTweet">

        <!-- HEADER NAVBAR -->
        <?php require_once 'layouts/navbar.php'; ?>

        <!-- TWEETS -->
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingMainBG">

                    <?php
                    $userId = $tweet->getUserId();
                    $username = Users::loadUserById($conn, $userId)->getUsername();
                    $creationDate = $tweet->getCreationDate();
                    $text = $tweet->getText();

                    echo "<div class='form-group' >
                                <h5><span class='glyphicon glyphicon-user'><a href='userPage.php?id=$userId'> $username</a></span></h5>
                                <span class='glyphicon glyphicon-globe'> $creationDate</a></span>
                                <input class='form-control input-sm' type='textarea' placeholder='$text' disabled>
                                <hr>
                            </div>";
                    ?>
                    <form action="#" method="POST">
                        <div class="form-group" >
                            <h5 style="color:white;">Comments:</h5>
                            <input class="form-control input input-sm" type="textarea" placeholder="Your comment..." maxlength="140" name="text">
                            <button type="submit" class="btn btn-success btn-sm col-sm-2"><span class="glyphicon glyphicon-comment"> Comment!</span></button>
                        </div>
                    </form>
                    <br>
                   
                    <?php
                    foreach ($comments as $comment) {
                        $userCommId = $comment->getUserId();
                        $username = Users::loadUserById($conn, $userCommId)->getUsername();
                        $creationDate = $comment->getCreationDate();
                        $text = $comment->getText();
                        echo "<br><div class='form-group' >
                                    <h5><span class='glyphicon glyphicon-user'><a href='userInfo.php?id=$userCommId' $username</a></span></h5>
                                    <span class='glyphicon glyphicon-globe'> $creationDate</a></span>
                                    <input class='form-control input input-sm' type='textarea' placeholder='$text' disabled>";
                    }
                    ?>

                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>

    </body>
</html>