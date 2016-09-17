<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['text'])) {
    $newTweet = new Tweet();
    $newTweet->setText($_POST['text']);
    $newTweet->setCreationDate(date('Y-m-d H:i:s'));
    $newTweet->setUserId($loggedUserId);
    $newTweet->saveToDB($conn);
}
$allTweets = Tweet::loadAllTweets($conn);
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

    <body class="bgMain">

        <!-- HEADER NAVBAR -->
        <?php require_once 'layouts/navbar.php'; ?>

        <!-- TWEETS -->

        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingTweetBox">
                    <!-- textarea to send tweet to db and show at main page -->
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
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingMainBG">
                        <?php
                        foreach ($allTweets as $tweet) {
                            $tweetId = $tweet->getId();
                            $userId = $tweet->getUserId();
                            $username = User::loadUserById($conn, $userId)->getUsername();
                            $creationDate = $tweet->getCreationDate();
                            $text = $tweet->getText();
                            
                            echo "<div class='form-group' >
                                <h5><a href='userInfo.php?id=$userId'>$username>/a></h5>
                                <span><a href='tweetInfo.php?id=$tweetId'>$creationDate</a></span>
                                <input class='form-control input' type='textarea' placeholder='$text'>
                                <button type='submit' class'btn btn-success col-sm-2'><span class='glyphicon glyphicon-send'> Tweet!</span></button>
                            </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>

        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>

    </body>
</html>