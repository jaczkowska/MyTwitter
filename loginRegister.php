<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';


//LOGIN//
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['mail']) &&
        isset($_POST['password'])) {

    $email = $_POST['mail'];
    $password = $_POST['password'];

    $loggedUser = Users::loadUserByEmail($conn, $email);

    if ($loggedUser != null) {

        $hash = $loggedUser->getHashedPassword();

        if (password_verify($password, $hash)) {

            $loggedUserId = $loggedUser->getId();

            $_SESSION['loggedUserId'] = $loggedUserId;

            header('Location: index.php');
        } else {

            echo "Incorrect e-mail or password. Try again!";
        }
    } else {

        echo "Incorrect e-mail or password. Try again!";
    }
}

//SIGN UP//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) &&
        isset($_POST['newEmail']) &&
        isset($_POST['newPassword1']) &&
        isset($_POST['newPassword2'])) {
        $username = $_POST['username'];
        $email = $_POST['newEmail'];
        $password1 = $_POST['newPassword1'];
        $password2 = $_POST['newPassword2'];
        if (Users::loadUserById($conn, $email) != null) {
            echo "This e-mail address is already taken. Try another.";
            
        } elseif ($_POST['newPassword1'] != $_POST['newPassword2']) {
            echo "Different password.";
            
        } else {
            $newUser = new Users();
            $newUser->setEmail($email);
            $newUser->setUsername($username);
            $newUser->setHashedPassword($password1);
            
            $result = $newUser->saveToDB($conn);
            
            if($result) {
                echo "User <strong>" . $newUser->getUsername() . "</strong> added to database";
            }
        }
    } else {
        echo "<br>";
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <title>TweetTweet</title>
    </head>

    <body class="bgLogin">

        <!-- Login / Sing up-->

        <div class="container">
            <div class="row">
                <div class="col-sm-offset-8 col-sm-4 paddingLogin main">

                    <!-- Login -->
                    <h4>Log in!</h4>
                    <form class="logForm" action="#" method="POST">
                        <div class="form-group">
                            <label for="mail">Login:</label>
                            <input type="text" class="form-control input-sm" name="mail" placeholder="place your e-mail address">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control input-sm" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-send"> Log in!</span></button>
                    </form>    
                    <!-- End: Login -->

                    <!-- Sign up -->
                    <br>
                    <h4>Are You new? Sign up!</h4>
                    <form class="regForm" action="#" method="POST">
                        <div class="form-group">
                            <label for="username">Login:</label>
                            <input type="text" class="form-control input-sm" name="username" placeholder="Login">
                        </div>
                        <div class="form-group">
                            <label for="newEmail1">Email address</label>
                            <input type="email" class="form-control input-sm" name="newEmail" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label for="newPassword1">Password</label>
                            <input type="password" class="form-control input-sm" name="newPassword1" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="newPassword2">Confirm password</label>
                            <input type="password" class="form-control input-sm" name="newPassword2" placeholder="Repeat password">
                        </div>
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"> Sign up!</span></button>
                    </form>
                    <!-- End: Sign up -->

                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>