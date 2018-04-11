<?php 
    session_cache_limiter('none');          //This prevents a Chrome error when using the back button to return to this page.
    session_start();
    error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 

    if ($_SESSION['validUser'] == "yes") { //is there already a valid user?
        
        $title = "Add Volunteers - Ankeny SummerFest";
        $submitButton = "Add";
        $pageTitle = "Add Volunteers";

        $volunteerId = "";
        $inVolFirstName = "";
        $inVolLastName = "";
        $inVolEmail = "";
        $inVolPhoneNumber = "";
        $inVolBirthDate = "";
        $inVolAvailablity= "";
       // $inVolResume = "";
        $inVolMessage = "";

        $inVolFirstNameError = "";
        $inVolLastNameError = "";
        $inVolEmailError = "";
        $inVolPhoneNumberError = "";
        $inVolBirthDateError = "";
        $inVolAvailablityError= "";
       // $inVolResumeError = "";
        $inVolMessageError = "";

    require '../php/databaseConnect.php';


    if (isset($_POST["volSubmit"])) {
        $volunteerId= $_POST["volunteerId"];
        $inVolFirstName = $_POST["volFirstName"];
        $inVolLastName = $_POST["volLastName"];
        $inVolEmail = $_POST["volEmailAddress"];
        $inVolPhoneNumber = $_POST["volPhoneNumber"];
        $inVolBirthDate = $_POST["volBirthDate"];
        $inVolAvailablity= $_POST["volAvailability"];
        $inVolAvailablityVariables= implode(" , ",$inVolAvailablity);
       // $inVolResume = $_POST["volResume"];
        $inVolMessage = $_POST["volMessage"];



        include "../php/include/volunteerValidation.php";


        $validForm = true;

        validateFirstName($inVolFirstName);
        validateLastName($inVolLastName);
        validateEmail($inVolEmail);
        validatePhoneNumber($inVolPhoneNumber );
        validateBirthDate($inVolBirthDate);
        validateAvailablity($inVolAvailablity );
        validateMsg($inVolMessage);

        if ($validForm) {
        
            if ($volunteerId != "") {
            //if volunteerId is empty then its updating
                try {
                        //SQL Command
                        $updateSQL = "UPDATE ank_summfest_volunteers_2018 SET ";
                        $updateSQL .= "volunteer_first_name = :volunteerFirstName, ";
                        $updateSQL .= "volunteer_last_name = :volunteerLastName, ";
                        $updateSQL .= "volunteer_email = :volunteerEmail, ";
                        $updateSQL .= "volunteer_phone_number = :volunteerPhoneNumber, ";
                        $updateSQL .= "volunteer_birth_date = :volunteerBirthDate, ";
                        $updateSQL .= "volunteer_availablity = :volunteerAvailablity, ";
                        $updateSQL .= "volunteer_message = :volunteerMessage ";
                        $updateSQL .= "WHERE volunteer_id = :volunteerId";



                        $updateStatment = $conn->prepare($updateSQL);
                            //bind parameters to the prepared statement
                        $updateStatment->bindParam(":volunteerFirstName", $inVolFirstName);
                        $updateStatment->bindParam(":volunteerLastName", $inVolLastName);
                        $updateStatment->bindParam(":volunteerEmail", $inVolEmail);
                        $updateStatment->bindParam(":volunteerPhoneNumber", $inVolPhoneNumber);
                        $updateStatment->bindParam(":volunteerBirthDate", $inVolBirthDate);
                        $updateStatment->bindParam(":volunteerAvailablity", $inVolAvailablity);
                        $updateStatment->bindParam(":volunteerMessage", $inVolMessage);
                        $updateStatment->bindParam(":volunteerId", $volunteerId);

                        $updateStatment->execute();

                        $message = "Success! Entry has been updated successfully.";
                        $message .= "<a href='listOfVolunteers.php'>View Volunteer List.</a>";
                        $updateStatment = null;
                        $conn = null;


                } catch (PDOException $e) {
                    $message .= "Error has occured... Please  try again later.";
                    error_log($e->getMessage());
                }
        } else {
                //Volunteer hasn't passed through URL so this'll insert it
            try {
                        $sql = "INSERT INTO ank_summfest_volunteers_2018(";
                        $sql .= "volunteer_first_name, ";
                        $sql .= "volunteer_last_name, ";
                        $sql .= "volunteer_email, ";
                        $sql .= "volunteer_phone_number, ";
                        $sql .= "volunteer_birth_date, ";
                        $sql .= "volunteer_availablity, ";
                        //$sql .= "volunteer_resume , ";
                        $sql .= "volunteer_message ";
                        $sql .= ") VALUES (:volunteer_first_name, :volunteer_last_name, :volunteer_email, :volunteer_phone_number, :volunteer_birth_date,:volunteer_availablity, :volunteer_message)";
                        //Preparing the statement
                        $stmt = $conn->prepare($sql);


                        //Bind the values to the input parameters of the prepared statement 
                        $stmt->bindParam(':volunteer_first_name', $inVolFirstName); // Assign :volunteer_first_name to $inVolFirstName
                        $stmt->bindParam(':volunteer_last_name', $inVolLastName);        
                        $stmt->bindParam(':volunteer_email', $inVolEmail);     
                        $stmt->bindParam(':volunteer_phone_number', $inVolPhoneNumber);     
                        $stmt->bindParam(':volunteer_birth_date', $inVolBirthDate);
                        $stmt->bindParam(':volunteer_availablity', $inVolAvailablityVariables);
                        //$stmt->bindParam(':volunteer_resume', $inVolResume);
                        $stmt->bindParam(':volunteer_message', $inVolMessage);

                        $stmt->execute();

                        $message = "Volunteer has been updated.";
                        $message .= "<a href='listOfVolunteers.php'>View Volunteer List.</a>";
                        $stmt = null;
                        $conn = null;
                } catch(PDOException $e) {
                        $message = "There has been a issue with your request. We apologize for the inconvenience.";
                        error_log($e->getMessage());            //Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                        error_log(var_dump(debug_backtrace()));
                        //Clean up any variables or connections that have been left hanging by this error.
            }
        }
    } else {
        $message .= "Form is invalid. Please try again.";
    }
} else {
    //Form has yet to be submitted
    $volunteerId= $_GET["volId"];


    if (isset($volunteerId)) {
        $pageTitle = "Update Volunteer";
        $submitButton = "Update";


        //select this
        $selectSQLPreparedStmt = $conn->prepare("SELECT
                volunteer_id,
                volunteer_first_name,
                volunteer_last_name,
                volunteer_email,
                volunteer_phone_number,
                volunteer_birth_date,
                volunteer_availablity,
                volunteer_message
                FROM ank_summfest_volunteers_2018
                WHERE volunteer_id = :volunteerId");
        //bind 
        $selectSQLPreparedStmt->bindParam(":volunteerId", $volunteerId);

        //execute 
        if ($selectSQLPreparedStmt->execute() && $selectSQLPreparedStmt->rowCount() > 0 ) {
            while ($row = $selectSQLPreparedStmt->fetch(PDO::FETCH_ASSOC)) {
                $inVolFirstName = $row['volunteer_first_name'] ;
                $inVolLastName = $row['volunteer_last_name'];
                $inVolEmail = $row['volunteer_email'];
                $inVolPhoneNumber = $row['volunteer_phone_number'];
                $inVolBirthDate = $row['volunteer_birth_date'];
                $inVolAvailablity = $row['volunteer_availablity'];
                $inVolMessage = $row['volunteer_message'];
            }
        }

        $selectSQLPreparedStmt = null;
        $conn = null; 
        } else {

        }
    }
} else {
    header('Location: admin.php');
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
                        <p class="volunteerAddedMsg">
                            <?php echo $message; ?>
                        </p>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" class="SignupForm" id="volunteerSignUpForm" name="volunteerSignupForm">

                            <div class="volFormSelection">
                                <input type="text" name="volunteerId" value="<?php echo $volunteerId ?>" style="display: none;">
                                <label for="firstName">First Name:</label> <span class="error"><?php echo $inVolFirstNameError ?></span><br>
                                    <input type="text" name="volFirstName" placeholder="John" value="<?php echo $inVolFirstName ?>">
                                
                                <br>
                                <label for="lastName">Last Name:</label><span class="error"><?php echo $inVolLastNameError ?></span><br>
                                    <input type="text" name="volLastName" placeholder="Doe" value="<?php echo $inVolLastName ?>">
                                
                                <br>
                                <label for="Email Address ">Email Address:</label><span class="error"><?php echo $inVolEmailError ?></span><br>
                                    <input type="text" name="volEmailAddress" placeholder="example@host.com" value="<?php echo $inVolEmail ?>">
                                
                                <br>
                                <label for="Phone Number ">Phone Number:</label> <span class="error"><?php echo $inVolPhoneNumberError ?></span><br>
                                    <input type="text" name="volPhoneNumber" placeholder="(XXX)-XXX-XXXX" value="<?php echo $inVolPhoneNumber ?>">
                                
                                <br>
                            <label for="Birth Date">Birth Date:</label> <span class="error"><?php echo $inVolBirthDateError ?></span><br>
                                <input type="text" name="volBirthDate" placeholder="MM-DD-YYYY" value="<?php echo $inVolBirthDate ?>">
                            <br> 
                            </div>
                            <div class="volFormSelection">

                            <label for="availableDays">I am available on these days:</label> <span class="error"><?php echo $inVolAvailablityError ?></span><br>
                            <div class="checkboxList"><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 12" value="<?php echo $inVolAvailablityVariables ?>"><span class="checkboxDescription">July 12, Thursday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 13" value="<?php echo $inVolAvailablityVariables ?>"><span class="checkboxDescription">July 13, Friday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 14" value="<?php echo $inVolAvailablityVariables ?>"><span class="checkboxDescription">July 14, Saturday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 15" value="<?php echo $inVolAvailablityVariables ?>"><span class="checkboxDescription">July 15, Sunday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="TBD" value="<?php echo $inVolAvailablityVariables ?>"><span class="checkboxDescription">Don't know yet.</span></div><br>
                            </div>
                            <br><br>
                            <label for="Volunteer Message">Questions, Comments, Concerns:</label> <span class="error"><?php echo $inVolMessageError ?></span> <br>
                                <textarea rows="4" cols="50" name="volMessage"><?php echo $inVolMessage ?></textarea><br>  
                            </div>


                            <div class="btnContainer">
                                <input type="submit" name="volSubmit" value="<?php echo $submitButton ?>" class="btn" >
                                <input type="reset" name="volReset" value="Clear" class="btn">
                            </div>
                        </form>

                   </div>
            </div>


</body>

</html>