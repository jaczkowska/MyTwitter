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
if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
        isset($_GET['id'])) {

    $userId = $_GET['id'];
    $user = Users::loadUserById($conn, $userId);
    $username = $user->getUsername();

    $allUserTweets = Tweet::loadAllTweetsByUserId($conn, $userId);
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

    <body class="bgUser">

        <!-- HEADER NAVBAR -->
        <?php require_once 'layouts/navbar.php'; ?>

        <!-- FORM 
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingTweetBox">
                    <div class="row" id="tweetbox">
                        <form action="#" method="POST">
                            <div class="form-group" >
                                <h4>Insert Your Tweet...</h4>
                                <input class="form-control input" type="textarea" placeholder="Insert Your tweet..." maxlength="140" name="text">
                                <button type="submit" class="btn btn-success col-sm-2"><span class="glyphicon glyphicon-send"> Tweet!</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        -->

        <!--SEND MESSAGE TO USER-->
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingTweetBox">
                    <div class="row" id="tweetbox">
                        <?php
                        
                        
                        if ($userId != $loggedUserId) {
                            echo '<h5><span class="glyphicon glyphicon-user"><a href="userPage.php?id=$userId">'. $username . '</a></span></h5>';
                            echo "<button type='submit' class='btn btn-success col-sm-3 inline'><span class='glyphicon glyphicon-pencil'><a href='sendMessage.php?id=$userId''>" . "Send message" . "</a></span></button>";
                        } else {
                            
                            echo "<div style='color: white; text-align:center;'>It's good to see You again  <span class='glyphicon glyphicon-user'><strong>$username!</strong></span></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!-- TWEETS -->
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingMainBG">
                    <?php
                    foreach ($allUserTweets as $tweet) {
                        $tweetId = $tweet->getId();
                        $creationDate = $tweet->getCreationDate();
                        $text = $tweet->getText();
                        $commentsNo = count(Comment::loadAllCommentsByTweetId($conn, $tweetId));

                        echo "<div class='form-group' >
                                <h5><span class='glyphicon glyphicon-user'><a href='userInfo.php?id=$userId'> $username</a></span></h5>
                                <span class='glyphicon glyphicon-globe'><a href='tweetInfo.php?id=$tweetId'> $creationDate</a></span>
                                <input class='form-control input' type='textarea' placeholder='$text' disabled>
                                <button type='submit' class='btn btn-primary col-sm-3 inline'><span class='glyphicon glyphicon-comment'><a href='tweetInfo.php?id=$tweetId#comments>". 'Comment'. "<span class='badge'>$commentsNo</span></a></span></button>
                                <br>
                                <hr>
                            </div>";
                        //<button type='submit' class='btn btn-success col-sm-3 inline'><span class='glyphicon glyphicon-pencil'><a href=''> Edit tweet</a></span></button>
                        //<button type='submit' class='btn btn-danger col-sm-3 inline'><span class='glyphicon glyphicon-trash'><a href=''> Delete tweet</a></span></button>
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>

    </body>
</html>