<nav class="navbar navbar-fixed main">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
            </button>
            <a class="navbar-brand" href="index.php">MyTwitter</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="rules.php"><span class="glyphicon glyphicon-education"></span></a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="editUserData.php">
                        <span class="glyphicon glyphicon-user"> 
                            <?php
                            if (!isset($_SESSION['loggedUserId'])) {

                                header('Location: loginRegister.php');
                            } else {

                                $loggedUserId = $_SESSION['loggedUserId'];
                                $loggedUser = Users::loadUserById($conn, $loggedUserId);

                                echo " " . $loggedUser->getUsername() . "</strong><br>";
                            }
                            ?>
                        </span></a></li>
                <li class="active"><a href="messages.php"><span class="glyphicon glyphicon-inbox"><span class="badge">4</span></span></a></li>
                <li class="active"><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>
