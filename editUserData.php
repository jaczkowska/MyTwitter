<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';

//Check if user is logged in
if (!isset($_SESSION['loggedUserId'])) {

    header('Location: loginRegister.php');
} else {

    $loggedUserId = $_SESSION['loggedUserId'];

    $loggedUser = Users::loadUserById($conn, $loggedUserId);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) &&
            isset($_POST['password1']) &&
            isset($_POST['password2'])) {
        $username = $_POST['username'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        if ($password1 != $password2) {
            echo "Different password!";
        } else {
            $loggedUser->setUsername($username);
            $loggedUser->setHashedPassword($password1);

            $result = $loggedUser->saveToDB($conn);

            if ($result) {
                echo "You've changed your data correctly <strong>" . $loggedUser->getUsername() . "</strong>";
            }
        }
    } else {
        echo "Something's wrong...";
    }
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

    <body class="bgEdit">

        <!-- HEADER NAVBAR -->
        <?php require_once 'layouts/navbar.php'; ?>

        <!-- EDIT USER DATA -->

        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8 main paddingTweetBox">
                    <!-- textarea to send tweet to db and show at main page -->
                    <div class="row" id="tweetbox">
                        <h4>Change Your data here:</h4>
                        <form class="regForm" action="#" method="POST">
                            <div class="form-group">
                                <label for="username">Login:</label>
                                <input type="text" class="form-control input-sm" name="username" placeholder="Login">
                            </div>
                            <div class="form-group">
                                <label for="newPassword1">Password</label>
                                <input type="password" class="form-control input-sm" name="password1" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="newPassword2">Confirm password</label>
                                <input type="password" class="form-control input-sm" name="password2" placeholder="Repeat password">
                            </div>
                            <button type="submit" class="btn btn-success"><a href="index.php"><span class="glyphicon glyphicon-send"> Save changes and return to home page</span></a></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>

    </body>
</html>