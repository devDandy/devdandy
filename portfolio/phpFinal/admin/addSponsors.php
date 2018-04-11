<?php 
    session_cache_limiter('none');          //This prevents a Chrome error when using the back button to return to this page.
    session_start();
    // error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 

    if ($_SESSION['validUser'] == "yes") { //is there already a valid user?

        $title = "Add Sponsors - Ankeny SummerFest";
        $submitButton = "Add";
        $pageTitle = "Add Sponsors ";

        $sponId = "";
        $SponId = "";
        $sponBusinessName = "" ;
        $sponContactFirstName = "";
        $sponContactLastName = "" ;
        $sponEmail = "" ;
        $sponPhoneNumber = "" ;
        $sponWebsite = "" ;
        $sponContactPreference = "" ;
        $sponMessage =  "" ;

        $sponBusinessNameError =  "";
        $sponContactFirstNameError = "";
        $sponContactLastNameError = "";
        $sponEmailError = "";
        $sponPhoneNumberError = "";
        $sponWebsiteError = "";
        $sponMessageError = "";
        $inSponLogoError = "";

        $message = "";
        $validForm = false;



        require '../php/databaseConnect.php';


        if (isset($_POST["sponSubmit"])) {

            $sponId= $_POST["sponId"];

            $sponBusinessName = $_POST["sponBusinessName"];
            $sponContactFirstName = $_POST["sponFirstName"];
            $sponContactLastName = $_POST["sponLastName"];
            $sponEmail = $_POST["sponEmailAddress"];
            $sponPhoneNumber = $_POST["sponPhoneNumber"];
            $sponWebsite = $_POST["sponBusinessWeb"];
            $sponContactPreference = $_POST["sponContactPreference"] ;
            $sponMessage = $_POST["sponMessage"] ;

            include "../php/include/sponsorValidation.php";

            $validForm = true;

            validateBusinessName($sponBusinessName);
            validateContactFirstName($sponContactFirstName);
            validateContactLastName($sponContactLastName);
            validateSponEmail($sponEmail);
            validateSponPhoneNumber($sponPhoneNumber);
            validateWebsite($sponWebsite);
            validateSponMsg($sponMessage);

            if ($validForm) {

                if ($sponId != "") {
                    //If the $sponId is empty then its an UPDATE 
                    try {
                        //SQL Command
                        $updateSQL = "UPDATE ank_summfest_sponsors_2018 SET ";
                        $updateSQL .= "sponsor_business_name = :sponsorBusinessName, ";
                        $updateSQL .= "sponsor_first_name = :sponsorFirstName, ";
                        $updateSQL .= "sponsor_last_name = :sponsorLastName, ";
                        $updateSQL .= "sponsor_email = :sponsorEmail, ";
                        $updateSQL .= "sponsor_phone_number = :sponsorPhoneNumber, ";
                        $updateSQL .= "sponsor_website = :sponsorWebsite, ";
                        $updateSQL .= "sponsor_contact_preference = :sponsorContactPref, ";
                        $updateSQL .= "sponsor_message = :sponsorMessage ";
                        $updateSQL .= "WHERE sponsor_id = :sponId";



                        $updateStatment = $conn->prepare($updateSQL);
                            //bind parameters to the prepared statement
                        $updateStatment->bindParam(":sponsorBusinessName", $sponBusinessName);
                        $updateStatment->bindParam(":sponsorFirstName", $sponContactFirstName);
                        $updateStatment->bindParam(":sponsorLastName", $sponContactLastName);
                        $updateStatment->bindParam(":sponsorEmail", $sponEmail);
                        $updateStatment->bindParam(":sponsorPhoneNumber", $sponPhoneNumber);
                        $updateStatment->bindParam(":sponsorWebsite", $sponWebsite);
                        $updateStatment->bindParam(":sponsorContactPref", $sponContactPreference);
                        $updateStatment->bindParam(":sponsorMessage", $sponMessage);
                        $updateStatment->bindParam(":sponId", $sponId);
       
                        //execute prepared statement 
                        $updateStatment->execute();
                        $message .= "Success! Entry has been updated successfully.";
                        $message .= "<a href='listOfSponsors.php'>View Sponsor List.</a>";

                        $updateStatment = null;
                        $conn = null; 


                    } catch (PDOException $e) {
                        $message .= "Error occured when updating. Please try again later.";
                        error_log($e->getMessage());
                    }
                } else {
                    //Sponsor hasn't passed through URL so this'll insert it
                    try {
                        $sql = "INSERT INTO ank_summfest_sponsors_2018(";
                        $sql .= "sponsor_business_name, ";
                        $sql .= "sponsor_first_name, ";
                        $sql .= "sponsor_last_name, ";
                        $sql .= "sponsor_email, ";
                        $sql .= "sponsor_phone_number, ";
                        $sql .= "sponsor_website, ";
                        $sql .= "sponsor_contact_preference,";
                        $sql .= "sponsor_message";
                        $sql .= ") VALUES (:sponsor_business_name, :sponsor_first_name, :sponsor_last_name, :sponsor_email, :sponsor_phone_number,:sponsor_website, :sponsor_contact_preference,:sponsor_message )";
                        //Preparing the statement
                        $stmt = $conn->prepare($sql);

                        //Bind the values to the input parameters of the prepared statement 
                        $stmt->bindParam(':sponsor_business_name', $sponBusinessName); // Assign :sponsor_business_name to $sponBusinessName
                        $stmt->bindParam(':sponsor_first_name', $sponContactFirstName);        
                        $stmt->bindParam(':sponsor_last_name', $sponContactLastName);     
                        $stmt->bindParam(':sponsor_email', $sponEmail);     
                        $stmt->bindParam(':sponsor_phone_number', $sponPhoneNumber);
                        $stmt->bindParam(':sponsor_website', $sponWebsite);
                        $stmt->bindParam(':sponsor_contact_preference', $sponContactPreference);
                        $stmt->bindParam(':sponsor_message', $sponMessage);

                        $stmt->execute();

                        $message = "Sponsor has been updated.";
                        $message .= "<a href='listOfSponsors.php'>View Sponsor List.</a>";

                        $stmt = null;
                        $conn = null;
                    } catch(PDOException $e) {
                        $message = "There has been a issue with your request. We apologize for the inconvenience.";
                        error_log($e->getMessage());            //Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                        error_log(var_dump(debug_backtrace()));
                        //Clean up any variables or connections that have been left hanging by this error.
                    } //catch
                }
            } else {
                $message .= "Form is invalid. Please try again.";
            }
        } else {
            //Form has yet to be submitted  
            $sponId = $_GET["SponId"];
            if (isset($sponId)) {
                $pageTitle = "Update Sponsor $sponBusinessName";
                $submitButton = "Update";

                $selectSQL = ("SELECT sponsor_business_name, sponsor_first_name, sponsor_last_name, sponsor_email, sponsor_phone_number, sponsor_website, sponsor_contact_preference, sponsor_message FROM ank_summfest_sponsors_2018 WHERE sponsor_id = :sponId");

                $selectSQLPreparedStmt = $conn->prepare($selectSQL);

                $selectSQLPreparedStmt->bindParam(":sponId", $sponId);    

                //execute prep statment

                if ($selectSQLPreparedStmt->execute() && $selectSQLPreparedStmt->rowCount() > 0 ) {
                    while ($row = $selectSQLPreparedStmt->fetch(PDO::FETCH_ASSOC)) {
                        $sponBusinessName = $row['sponsor_business_name'];
                        $sponContactFirstName = $row['sponsor_first_name'];
                        $sponContactLastName = $row['sponsor_last_name'];
                        $sponEmail = $row['sponsor_email'];
                        $sponPhoneNumber = $row['sponsor_phone_number'];
                        $sponWebsite = $row['sponsor_website'];
                        $sponContactPreference = $row['sponsor_contact_preference'];
                        $sponMessage =  $row['sponsor_message'];
                }
            }
                $selectSQLPreparedStmt = null; 
                $conn = null;
            } else {

            }
        } // this one

} else { // if not valid user
    header('Location: admin.php');
} //else valid user



    if (isset($_POST['sponReset'])) 
    {
        header("location: addSponsors.php");
        exit;
    }



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


            <div class="adminAddVolunteers">
                <div class="title">
                    <h2><?php echo $pageTitle; ?></h2>
                </div>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" class="SignupForm" id="volunteerSignUpForm" name="volunteerSignupForm">
                        <p class="volunteerAddedMsg">
                            <?php echo $message; ?>
                        </p>
                            <div class="volFormSelection">
                                <input type="text" name="sponId" value="<?php echo $sponId ?>" style="display: none;">
                            <label for="BusinessName">Business Name:</label><span class="error"><?php echo $sponBusinessNameError ?></span><br>
                                <input type="text" name="sponBusinessName" value="<?php echo $sponBusinessName ?>">
                                
                                <br>
                                <label for="Contact First Name">Contact First Name:</label><span class="error"><?php echo $sponContactFirstNameError ?></span><br>
                                <input type="text" name="sponFirstName" value="<?php echo $sponContactFirstName ?>">
                            
                                <br>
                                <label for="Contact Last Name">Contact Last Name:</label><span class="error"><?php echo $sponContactLastNameError ?></span><br>
                                    <input type="text" name="sponLastName" value="<?php echo $sponContactLastName ?>">
                                
                                <br>
                                    <label for="Sponsorship Email Address">Email Address:</label><span class="error"><?php echo $sponEmailError ?></span><br>
                                        <input type="text" name="sponEmailAddress" value="<?php echo $sponEmail ?>">
                                <br>
                            <label for="Phone Number ">Business Phone Number:</label><span class="error"><?php echo $sponPhoneNumberError ?></span><br>
                                <input type="text" name="sponPhoneNumber" placeholder="(XXX)-XXX-XXXX" value="<?php echo $sponPhoneNumber ?>">
                            </div>
                            <div class="volFormSelection">
                                <label for="Business Website">Business Website:</label><span class="error"><?php echo $sponWebsiteError ?></span><br>
                                    <input type="text" name="sponBusinessWeb" value="<?php echo $sponWebsite ?>">
                            <br> 
                            <label for="Sponsorship Contact Preference">Contact Preference:</label><br><br>
                                <input type="radio" name="sponContactPreference" value="Phone Call" checked><span class="radioDescrip">Phone Call</span><br>
                                <input type="radio" name="sponContactPreference" value="Email"><span class="radioDescrip">Email</span>
                            <br><br>

                            <label for="Sponsorship Message">Tell Us Why:</label><span class="error"><?php echo $sponMessageError ?></span><br>
                            <textarea rows="4" cols="50" name="sponMessage"><?php echo $sponMessage ?></textarea><br>  
                            </div>
                            <div class="btnContainer">
                                <input type="submit" name="sponSubmit" value="<?php echo $submitButton ?>" class="btn">
                                <input type="reset" name="sponReset" value="Clear" class="btn">
                            </div>
                        </form>
                   </div>
            </div>


</body>

</html>