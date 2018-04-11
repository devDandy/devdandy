<?php 
    $title = "Admin Login - Ankeny SummerFest";
    session_cache_limiter('none');          //This prevents a Chrome error when using the back button to return to this page.
    session_start();
    error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 

    $message = "";

    if ($_SESSION['validUser'] == "yes") { //is there already a valid user?
        //User is already signed on, skip the rest.

        $message = "Welcome back! $username"; //Greeting for user

    }
    else { // if not valid user
        if (isset($_POST["submitLogin"])) { //Called from a submitted form
            // Grabs the username and password 
            $inUsername = $_POST["adminLoginUsername"];
            $inPassword = $_POST["adminLoginPassword"];

            // connect to DB
            include "../php/databaseConnect.php";


            $query = "SELECT admin_username, admin_password FROM admin_user WHERE admin_username = :admin_username AND admin_password = :admin_password";

            // prepare query and then bind parameters
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':admin_username', $inUsername, PDO::PARAM_STR);
            $stmt->bindValue(':admin_password', $inPassword, PDO::PARAM_STR);
            $stmt->execute();

            //If valid user then there should only be one row
            if ($stmt->rowCount() == 1) {
                global $adminName;
                //This is a valid user, set your SESSION variable
                $adminName = $inUsername; 
                $_SESSION['validUser'] = "yes";
                $message = "Welcome back! $username";
                //Valid user can do these things:
                $title = "Admin Hub - Ankeny SummerFest";
            } else {
                //Unable to process login / user or password wasnt found 

                $_SESSION['validUser'] = "no";
                $message = "Sorry, there was a problem with your username or password. Please try again.";
            }
            $conn = null;
        } // form submitted
    } //else valid user

    if (isset($_POST["adminLogout"])) {
        $_SESSION['validUser'] ='no';
        session_unset();    //remove all session variables related to current session
        session_destroy();  //remove current session
        header('Location: admin.php');
    }

?>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../css/main.css">

</head>

<body>




        <?php 
        if ($_SESSION['validUser'] == "yes") {
        ?>
            <div class="home-header-bg">
                <nav>
                        <div class="navMenu">
                            <ul class="navList">
                                <li><a href="../index.php">Home</a></li>
                                <li><a href="#">Theme</a></li>
                                <li><a href="#">Sponsors</a></li>
                                <li><a href="../get-involved.php">Get Involved</a></li>
                                <li><a href="../contact.php">Contact Us</a></li>
                                <li class="active"><a href="admin.php">Admin</a></li>
                            </ul>
                            <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                        </div>
                </nav>
        <div class="adminControlPanel">
            <h1><?php echo $message; ?></h1>
            <div class="card-container">
                <div class="card controlPanelOption">
                    <div class="card-title">
                        <h1>Add <br>Volunteers/Sponsors</h1>

                    </div>
                    <div class="card-image">
                        <img src="../assets/img/icons/update.png">
                    </div>
                    <div class="card-description">
                        <a href="addVolunteers.php"><button class="btn">Add Volunteers</button></a>
                        <a href="addSponsors.php"><button class="btn">Add Sponsors</button></a>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <div class="card controlPanelOption">
                    <div class="card-title">
                        <h1>List Of <br> Volunteers/Sponsors</h1>
                    </div>
                    <div class="card-image">
                        <img src="../assets/img/icons/update.png">
                    </div>
                    <div class="card-description">
                        <a href="listOfVolunteers.php"><button class="btn">Volunteer List</button></a>
                        <a href="listOfSponsors.php"><button class="btn">Sponsor List</button></a>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <div class="card controlPanelOption">
                    <div class="card-title">
                        <h1>Admin <br> Logout</h1>
                    </div>
                    <div class="card-image reset">
                        <img src="../assets/img/icons/exit.png">
                    </div>
                    <div class="card-description">
                        <form action='<?=$_SERVER['PHP_SELF'] ?>' method='POST'>
                        <button class="btn" name="adminLogout" type="submit">LOGOUT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php 
        } else {
            ?>
            <div class="index-header-bg">
                <nav>
                        <div class="navMenu">
                            <ul class="navList">
                                <li><a href="../index.php">Home</a></li>
                                <li><a href="#">Theme</a></li>
                                <li><a href="#">Sponsors</a></li>
                                <li><a href="../get-involved.php">Get Involved</a></li>
                                <li><a href="../contact.php">Contact Us</a></li>
                                <li class="active"><a href="admin.php">Admin</a></li>
                            </ul>
                            <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                        </div>
                </nav>
            <div class="adminLoginContainer">
                <h2>Admin Login</h2>
                <form method="POST" name="loginForm" action="<?php echo $_SERVER["PHP_SELF"];?>">
                    <p>Username: <input name="adminLoginUsername" type="text"  class="adminLoginFields" /> </p>
                    <p>Password: <input name="adminLoginPassword" type="password"  class="adminLoginFields" /></p>
                    <p><input name="submitLogin" value="Login" type="submit" class="adminLoginActionFields" /> <input name="" type="reset" class="adminLoginActionFields" /></p>
                </form>
                <p><?php echo $message; ?></p>
            </div>
            </div>
            <?php 
            }
            ?>


</body>

</html>