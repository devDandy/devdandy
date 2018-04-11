<?php 
    $title = "Update / List Of Volunteers - Ankeny SummerFest";
    session_cache_limiter('none');          //This prevents a Chrome error when using the back button to return to this page.
    session_start();
    error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 

    $message = "";

    if ($_SESSION['validUser'] == "yes") { //is there already a valid user?
        try {
            include '../php/databaseConnect.php';

            $stmt = $conn->prepare("SELECT
                volunteer_id,
                volunteer_first_name,
                volunteer_last_name,
                volunteer_email,
                volunteer_phone_number,
                volunteer_birth_date,
                volunteer_availablity,
                volunteer_message
                FROM ank_summfest_volunteers_2018
                ORDER BY volunteer_last_name");

            $stmt->execute()
            ;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    } else {
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
    <style>
        html {
            height: 100%;
        }
    </style>
</head>

<body class="listView">
        <nav>
                <div class="navMenuNew">
                    <ul class="navList">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="#">Theme</a></li>
                        <li><a href="#">Sponsors</a></li>
                        <li><a href="../get-involved.php">Get Involved</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>
                        <li class="active"><a href="admin.php">Admin</a></li>
                        <li class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></li>
                    </ul>
                </div>
        </nav>
        <div class="container">
            <div class="listContainer">
                <div class="sendEmailContainer">
                <h2>Email<br> Volunteer List</h2>
                <form method="POST">
                    <label for="Subject">Subject</label><br>
                    <input type="text" name="emailVolunteersSubject"><br>
                    <label>Message:</label><br>
                    <textarea class="emailVolunteersForm"></textarea>
                            <div class="btnContainer">
                                <input type="submit" name="volSubmit" value="Send To Email" class="btn" >
                                <input type="reset" name="volReset" value="Clear" class="btn">
                            </div>
                </form>
            </div>
        </div>
</body>

</html>