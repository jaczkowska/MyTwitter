<?php
require_once 'src/Users.php';
require_once 'src/Message.php';
require_once 'src/Tweet.php';
require_once 'src/connection.php';
require_once 'src/init.php';


if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginRegister.php');
} else {
    $loggedUserId = $_SESSION['loggedUserId'];
    $loggedUser = Users::loadUserById($conn, $loggedUserId);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
        isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = Users::loadUserById($conn, $id);
    $username = $user->getUsername();

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
                    <div class="row" id="tweetbox">
                        <form action="messages.php" method="POST">
                            <div class="form-group" >
                                <h4 style="color: white;">Send message to: <?php echo $username; ?></h4>
                                <input class="form-control input" class="text" type="textarea" name="text">
                                <input class="btn btn-success" type="submit" value="Send message">
                                <input type="hidden" name="senderId" value="<?php echo $loggedUserId; ?>">
                                <input type="hidden" name="receiverId" value="<?php echo $id; ?>">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>
