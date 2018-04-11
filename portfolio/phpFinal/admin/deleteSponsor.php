<?php 
    session_cache_limiter('none');          //This prevents a Chrome error when using the back button to return to this page.
    session_start();
    error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 

    $message = "";
    $sponsorId = $_GET['SponId'];
    $deleteSponsor = "";

    $title = "Delete Sponsor $sponsorID - Ankeny SummerFest";

    if ($_SESSION['validUser'] == "yes") { //is there already a valid user?
        try {
            include '../php/databaseConnect.php';

            $unpreparedSQL = "SELECT sponsor_business_name FROM ank_summfest_sponsors_2018 WHERE sponsor_id=:sponsorId ";
            //prepare
            $stmt = $conn->prepare($unpreparedSQL);

            //bind
            $stmt->bindParam(":sponsorId", $sponsorId);

            //execute
            if ($stmt->execute()) {
                //Find which sponsor id to delete...

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                       $deleteSponsor = $row['sponsor_business_name'];
                    }

                //SQL DELETE STATEMENT
                $SQL = "DELETE FROM ank_summfest_sponsors_2018 WHERE sponsor_id=:sponsorId";

                $delStmt = $conn->prepare($SQL);
            
                $delStmt->bindParam(":sponsorId", $sponsorId);

                if ($delStmt->execute()) {
                    $message = "<h1>Success!</h1>";
                    $message .= "<p>You have successfully deleted $deleteSponsor </p>";
                }
            } 


            }

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
                    </ul>
                        <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                </div>
        </nav>
        <div class="container">
            <div class="deleteContainer">
                <div class="listContainer">
                    <?php echo $message; ?>
                    <a href="admin.php"><button class="btn">Return to Admin Hub</button></a>
                    <a href="listOfSponsors.php"><button class="btn">Return to List</button></a>
                </div>
            </div>
            
        </div>
</body>

</html>