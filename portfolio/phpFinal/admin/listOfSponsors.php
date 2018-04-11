<?php 
    $title = "Update / List Of Sponsors - Ankeny SummerFest";
    session_cache_limiter('none');          //This prevents a Chrome error when using the back button to return to this page.
    session_start();
    //error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 

    $message = "";

    if ($_SESSION['validUser'] == "yes") { //is there already a valid user?
        try {
            include '../php/databaseConnect.php';

            $stmt = $conn->prepare("SELECT
                sponsor_id,
                sponsor_business_name ,
                sponsor_first_name ,
                sponsor_last_name ,
                sponsor_email,
                sponsor_phone_number,
                sponsor_website ,
                sponsor_contact_preference,
                sponsor_message
                FROM ank_summfest_sponsors_2018
                ORDER BY sponsor_id");

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
                    </ul>
                        <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                </div>
        </nav>
        <div class="container">
            <div class="listContainer">

                <h1>Ankeny SummerFest's<br> Sponsor List</h1>
                <h5>Sorted by business name alphabetically.</h5>
                <?php
                    //Display each row as formatted output
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                    //Turn each row of the result into an associative array 
                    {
                    //For each row you have in the array create a block of formatted text
                     ?>
                <div class="listBlock">
                    <div class="listTitle">
                    <div class="editIconContainer">
                        <a href="addSponsors.php?SponId=<?php echo $row['sponsor_id']  ?>">
                            <img src="../assets/img/icons/edit.png" alt="Edit This Volunteer" title="Edit">
                            <span class="listIconDescription">Edit <br> Info</span>
                        </a>
                    </div>
                    <div class="deleteIconContainer">
                        <a href="deleteSponsor.php?SponId=<?php echo $row['sponsor_id']  ?>" >
                            <img src="../assets/img/icons/delete.png" alt="Delete This Volunteer" title="Delete">
                            <span class="listIconDescription">Delete <br> Info</span>   
                        </a>
                    </div>
                    <h2>Sponsor #<?php echo $row["sponsor_id"]; ?></h2>
                    <h4><?php echo ucwords($row["sponsor_business_name"]); ?></h4>
                    </div>
                    <div class="listBody">
                        <h4>Contact Information:</h4>
                        <p><strong>Contact Name -</strong> <span><?php echo $row["sponsor_first_name"]; ?> <?php echo $row["sponsor_last_name"]; ?></span> </p>
                        <p><strong>Contact Email - </strong> - <span><?php echo $row["sponsor_email"]; ?></span> </p>
                        <p><strong>Business Phone Number - </strong>- <span><?php echo $row["sponsor_phone_number"]; ?></span> </p>
                        <p><strong>Website</strong>- <span><?php echo $row["sponsor_website"]; ?></span></p>
                        <p><strong>Contact Preference</strong>- <span><?php echo $row["sponsor_contact_preference"]; ?></span></p>
                        <h4>Message:</h4> <p><?php echo $row["sponsor_message"]; ?></p> 
                    </div>

            </div>
            <?php 
            }
            $conn = null; //Close database connection
             ?>
        </div>
</body>

</html>