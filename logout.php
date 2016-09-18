<?php

require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/Users.php';
require_once 'src/Tweet.php';

//logout
unset($_SESSION['loggedUserId']);

header('Location: loginRegister.php');

