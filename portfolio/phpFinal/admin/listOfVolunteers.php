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
                    </ul>
                    <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                </div>
        </nav>
        <div class="container">
            <div class="listContainer">

                <h1> Volunteer List</h1>
                <h5>Sorted by last name alphabetically.</h5>
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
                        <a href="addVolunteers.php?volId=<?php echo $row['volunteer_id']  ?>">
                            <img src="../assets/img/icons/edit.png" alt="Edit This Volunteer" title="Edit">
                            <span class="listIconDescription">Edit <br> Info</span>
                        </a>
                    </div>
                    <div class="deleteIconContainer">
                        <a href="deleteVolunteer.php?VolId=<?php echo $row['volunteer_id']  ?>" >
                            <img src="../assets/img/icons/delete.png" alt="Delete This Volunteer" title="Delete">
                            <span class="listIconDescription">Delete <br> Info</span>   
                        </a>
                    </div>
                    <h2>Volunteer #<?php echo $row["volunteer_id"]; ?></h2>
                    <h4><?php echo $row["volunteer_last_name"]; ?> <?php echo $row["volunteer_first_name"]; ?></h4>
                    </div>
                    <div class="listBody">
                        <h4>Contact Information:</h4>
                        <p><strong>Email</strong> - <span><?php echo $row["volunteer_email"]; ?></span> </p>
                        <p><strong>Phone Number</strong> - <span><?php echo $row["volunteer_phone_number"]; ?></span> </p>
                        <h4>Personal Information:</h4>
                        <p><strong>Birth date</strong>- <span><?php echo $row["volunteer_birth_date"]; ?></span> </p>
                        <p><strong>Availability</strong>- <span><?php echo $row["volunteer_availablity"]; ?></span></p>
                        <h4>Message:</h4> <p><?php echo $row["volunteer_message"]; ?></p> 
                    </div>

            </div>
            <?php 
            }
            $conn = null; //Close database connection
             ?>
        </div>
</body>

</html>