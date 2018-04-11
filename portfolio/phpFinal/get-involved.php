<?php 
    $title = "Get Involved - Ankeny SummerFest";
    $header_title = "Get Involved";
    $header_paragraph = "Want to get involved with SummerFest but not sure where to start? You’ve come to the right place. A great Summerfest event is only possible due to the countless number of volunteers and their commitment to an outstanding event. If’ you’d like to be a part of the excitement, we welcome your involvement. Below are numerous volunteer opportunities that may be a good fit for you. Look them over and email us with the areas of interest and we’ll try to match you up with an opportunity that fits your preferences.";
    include "php/include/Email.php";
     error_reporting(E_ERROR | E_PARSE); // Removes Notices and Warnings that would be visible to user. 
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
    //$inVolResumeError = "";
    $inVolMessageError = "";

    $sponBusinessName = "";
    $sponContactFirstName = "";
    $sponContactLastName = "";
    $sponEmail = "";
    $sponPhoneNumber = "";
    $sponWebsite = "";
    $sponContactPreference = "";
    $sponMessage = "";
    $honeyPot = "";

    $sponBusinessNameError = "";
    $sponContactFirstNameError = "";
    $sponContactLastNameError = "";
    $sponEmailError = "";
    $sponPhoneNumberError = "";
    $sponWebsiteError = "";
    $sponContactPreferenceError = "";
    $sponMessageError = "";

    $validForm = false;
    
    require 'php/databaseConnect.php';


 
if (isset($_POST["volSubmit"])) {  // if the volunteer form has been submitted

    $inVolFirstName = $_POST["volFirstName"];
    $inVolLastName = $_POST["volLastName"];
    $inVolEmail = $_POST["volEmailAddress"];
    $inVolPhoneNumber = $_POST["volPhoneNumber"];
    $inVolBirthDate = $_POST["volBirthDate"];
    $inVolAvailablity= $_POST["volAvailability"];
    $inVolAvailablityVariables= implode(" , ",$inVolAvailablity);
    //$inVolResume = $_POST["volResume"];
    $inVolMessage = $_POST["volMessage"];
    $volHoneyPot = $_POST["volHoneyPot"];

    $inVolFirstNameError =$_POST["inVolFirstNameError"];
    $inVolLastNameError =$_POST["inVolLastNameError"];
    $inVolEmailError =$_POST["inVolEmailError"];
    $inVolPhoneNumberError =$_POST["inVolPhoneNumberError"];
    $inVolBirthDateError =$_POST["inVolBirthDateError"];
    $inVolAvailablityError=$_POST["inVolAvailablityError"];
    $inVolResumeError =$_POST["inVolResumeError"];
    $inVolMessageError =$_POST["inVolMessageError"];
    $volunteerButton = $_POST["volunteerButton"];
    $sponsorButton = $_POST["sponsorButton"];

    $validForm = true;

    require "php/include/volunteerValidation.php";


       validateFirstName($inVolFirstName);
        validateLastName($inVolLastName);
        validateEmail($inVolEmail);
        validatePhoneNumber($inVolPhoneNumber );
        validateBirthDate($inVolBirthDate);
        validateAvailablity($inVolAvailablity );
        validateMsg($inVolMessage);
        validateHoneypot($volHoneyPot);

    if ($validForm) {

        try {

                //mySQL dates stores data in a YYYY-MM-DD Format
                $todaysDate = date("Y-m-d");
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

                $title = "Success Form Submitted! - Ankeny SummerFest";
                $header_title = "Form Submitted Successfully";
                $header_paragraph = "Thank you for your interest for the Ankeny SummerFest. <br> We will get back to you in a matter of days.";
                $stmt = null;
                $conn = null;
            } catch (PDOException $e) {

                error_log($e->getMessage());
            }

    } else {
        $title = "Form Failed - Ankeny SummerFest";
        $header_title = "Whoops, looks like you got some errors. ";
        $header_paragraph = "We apologize for any inconvenience. Press the button to review your errors. ";    
    }
}


if (isset($_POST["sponSubmit"])) {

    $sponBusinessName = $_POST["sponBusinessName"];
    $sponContactFirstName = $_POST["sponFirstName"];
    $sponContactLastName = $_POST["sponLastName"];
    $sponEmail = $_POST["sponEmailAddress"];
    $sponPhoneNumber = $_POST["sponPhoneNumber"];
    $sponWebsite = $_POST["sponBusinessWeb"];
    $sponContactPreference = $_POST["sponContactPreference"] ;
    $sponMessage = $_POST["sponMessage"] ;

    $sponBusinessNameError = $_POST["sponBusinessNameError"] ;
    $sponContactFirstNameError = $_POST["sponFirstNameError"];
    $sponContactLastNameError = $_POST["sponLastNameError"];
    $sponEmailError = $_POST["sponEmailAddressError"];
    $sponPhoneNumberError = $_POST["sponPhoneNumberError"];
    $sponWebsiteError = $_POST["sponBusinessWebError"];
    $sponMessageError = $_POST["sponMessageError"];

    $validForm = true;

    require "php/include/sponsorValidation.php";

    validateBusinessName($sponBusinessName);
    validateContactFirstName($sponContactFirstName);
    validateContactLastName($sponContactLastName);
    validateSponEmail($sponEmail);
    validateSponPhoneNumber($sponPhoneNumber);
    validateWebsite($sponWebsite);
    validateSponMsg($sponMessage);
    validateHoneypot($sponHoneyPot);
    
    if ($validForm) {

        try {
            $sqlSponsor = "INSERT INTO ank_summfest_sponsors_2018(";
            $sqlSponsor .= "sponsor_business_name , ";
            $sqlSponsor .= "sponsor_first_name, ";
            $sqlSponsor .= "sponsor_last_name, ";
            $sqlSponsor .= "sponsor_email, ";
            $sqlSponsor .= "sponsor_phone_number, ";
            $sqlSponsor .= "sponsor_website, ";
            $sqlSponsor .= "sponsor_contact_preference , ";
            $sqlSponsor .= "sponsor_message  ";
            $sqlSponsor .= ") VALUES (:sponsor_business_name, :sponsor_first_name, :sponsor_last_name, :sponsor_email, :sponsor_phone_number,:sponsor_website,:sponsor_contact_preference, :sponsor_message)";
            //Preparing the statement
            $stmtSponsor = $conn->prepare($sqlSponsor);
            //Bind the values to the input parameters of the prepared statement 
            $stmtSponsor->bindParam(':sponsor_business_name', $sponBusinessName); // Assign :sponsor_business_name to $sponBusinessName
            $stmtSponsor->bindParam(':sponsor_first_name', $sponContactFirstName);        
            $stmtSponsor->bindParam(':sponsor_last_name', $sponContactLastName);     
            $stmtSponsor->bindParam(':sponsor_email', $sponEmail);     
            $stmtSponsor->bindParam(':sponsor_phone_number', $sponPhoneNumber);
            $stmtSponsor->bindParam(':sponsor_website', $sponWebsite);
            $stmtSponsor->bindParam(':sponsor_contact_preference', $sponContactPreference);
            $stmtSponsor->bindParam(':sponsor_message', $sponMessage);

            $stmtSponsor->execute();

            $title = "Success Form Submitted! - Ankeny SummerFest";
            $header_title = "Form Submitted Successfully";
            $header_paragraph = "Thank you for your interest for the Ankeny SummerFest. <br> We will get back to you ASAP!";

            $stmtSponsor = null;
            $conn = null;
        } catch (PDOException $e) {
                error_log($e->getMessage());
            }

    } else {
        $title = "Form Failed - Ankeny SummerFest";
        $header_title = "Whoops, looks like you got some errors. ";
        $header_paragraph = "We apologize for any inconvenience. Press the button to review your errors. ";    
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo $title; ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="css/main.css">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <link rel="stylesheet" href="/resources/demos/style.css">
      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <script>
      $( function() {
        $( "#datepicker" ).datepicker();
      } );
    </script>
    </head>

<body>
        <div class="header-bg">
        <nav>
                <div class="navMenu">
                    <ul class="navList">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#">Theme</a></li>
                        <li><a href="#">Sponsors</a></li>
                        <li class="active"><a href="get-involved.php">Get Involved</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="admin/admin.php">Admin</a></li>
                    </ul>
                    <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                </div>

        </nav>
        </div>
        
    <main id="main" >
        <div class="container">
            <h1 class="header-title"><?php echo $header_title;?></h1>
            <p class="lead get-involved-lead"><?php echo $header_paragraph; ?></p>
            <div>
                <button id="triggerVolunteerModal" onclick="openModal(volunteerModal)" class="big-btn btn" name="volunteerButton">Become A Volunteer</button>

                <div id="volunteerModal" class="modal">
                    <div class="modalContent" id="modalVolunteerContent">
                        <span class="closeModal" onclick="hideModal(volunteerModal)">X</span>
                        <h3>Volunteer Sign-Up Form</h3>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" class="SignupForm" id="volunteerSignUpForm" name="volunteerSignupForm">
                            <label for="firstName">First Name:</label> <span class="error"><?php echo $inVolFirstNameError ?></span><br>
                                <input type="text" name="volFirstName" placeholder="John">
                            
                            <br>
                            <label for="lastName">Last Name:</label><span class="error"><?php echo $inVolLastNameError ?></span><br>
                                <input type="text" name="volLastName" placeholder="Doe">
                            
                            <br>
                            <label for="Email Address ">Email Address:</label><span class="error"><?php echo $inVolEmailError ?></span><br>
                                <input type="text" name="volEmailAddress" placeholder="example@host.com">
                            
                            <br>
                            <label for="Phone Number ">Phone Number:</label> <span class="error"><?php echo $inVolPhoneNumberError ?></span><br>
                                <input type="text" name="volPhoneNumber" placeholder="(XXX)-XXX-XXXX">
                            
                            <br>
                            <label for="Birth Date">Birth Date:</label> <span class="error"><?php echo $inVolBirthDateError ?></span><br>
                                <input type="text" id="datepicker" name="volBirthDate" placeholder="MM/DD/YYYY">
                            <br> 
                            <label for="availableDays">I am available on these days:</label> <span class="error"><?php echo $inVolAvailablityError ?></span><br>
                            <div class="checkboxList"><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 12"><span class="checkboxDescription">July 12, Thursday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 13"><span class="checkboxDescription">July 13, Friday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 14"><span class="checkboxDescription">July 14, Saturday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="July 15"><span class="checkboxDescription">July 15, Sunday</span></div><br>
                                <div class="checkboxListItem"><input type="checkbox" name="volAvailability[]" value="TBD"><span class="checkboxDescription">Don't Know Yet</span></div>
                            </div>
                            <br><br>
<!--                             <label for="fileSelect">Resume:</label> <span class="error"><?php echo $inVolResumeError ?></span><br><br>
                               <input type="file" name="photo" id="fileSelect" name="volResume">
                                <p class="fileMsg"><strong>Note:</strong> Only .docx, and .pdf formats allowed to a max size of 5 MB.</p> -->
                            
                            <input type="hidden" name="volHoneyPot" >

                            
                            <label for="Volunteer Message">Questions, Comments, Concerns:</label> <span class="error"><?php echo $inVolMessageError ?></span> <br>
                                <textarea rows="4" cols="50" name="volMessage"></textarea><br>
                            <div class="btnContainer">
                                <input type="submit" name="volSubmit" value="Submit" class="btn" >
                                <input type="reset" name="volReset" value="Reset" class="btn">
                            </div>

                        </form>
                    </div>
                </div>

                <button id="triggerSponsorModal" onclick="openModal(sponsorModal)" class="big-btn btn" name="sponsorButton">Become A Sponsor</button>
                <div id="sponsorModal" class="modal">
                    <div class="modalContent">
                        <span class="closeModal" onclick="hideModal(sponsorModal)" ">X</span>
                        <h3>Sponsorship Form</h3>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" class="SignupForm" name="sponsorSignupForm">
                            <label for="BusinessName">Business Name:</label><span class="error"><?php echo $sponBusinessNameError ?></span><br>
                                <input type="text" name="sponBusinessName">
                            <br>
                            <label for="Contact First Name">Contact First Name:</label><span class="error"><?php echo $sponContactFirstNameError ?></span><br>
                                <input type="text" name="sponFirstName">
                            
                            <br>
                            <label for="Contact Last Name">Contact Last Name:</label><span class="error"><?php echo $sponContactLastNameError ?></span><br>
                                <input type="text" name="sponLastName">
                            
                            <br>
                            <label for="Sponsorship Email Address">Email Address:</label><span class="error"><?php echo $sponEmailError ?></span><br>
                                <input type="text" name="sponEmailAddress">
                            
                            <br>
                            <label for="Phone Number ">Business Phone Number:</label><span class="error"><?php echo $sponPhoneNumberError ?></span><br>
                                <input type="text" name="sponPhoneNumber">
                            <br>
                            <label for="Business Website">Business Website:</label><span class="error"><?php echo $sponWebsiteError ?></span><br>
                                <input type="text" name="sponBusinessWeb">
                            
                            <br>
                            <label for="Sponsorship Contact Preference">Contact Preference:</label><br><br>
                                <input type="radio" name="sponContactPreference" value="Phone Call" checked><span class="radioDescrip">Phone Call</span><br>
                                <input type="radio" name="sponContactPreference" value="Email"><span class="radioDescrip">Email</span>
                            <br>
                            <label for="Sponsorship Message">Tell Us Why:</label><span class="error"><?php echo $sponMessageError ?></span><br>
                            <textarea rows="4" cols="50" name="sponMessage"></textarea><br>
                            <input type="hidden" name="sponHoneyPot" >
                            <input type="submit" name="sponSubmit" value="Submit" class="btn">
                            <input type="reset" name="sponReset" value="Reset" class="btn">
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- container -->

    </main>
    <footer id="footer" >

    </footer>
    <script>
        var volunteerModal = document.getElementById('volunteerModal');

        var sponsorModal = document.getElementById('sponsorModal');

        var closeModal = document.getElementByClassName('closeModal')[0];

        function openModal(inModal) { // Opens modal
            inModal.style.display = "block";
        }
        function hideModal(inModal) {
            inModal.style.display = "none";
        }
        // Get the modal
        var confirmationModal = document.getElementById('confirmationModal');

        // Get the <span> element that closes the modal
        var closeConfirmationModal = document.getElementsByClassName("closeConfirmationModal")[0];
        // Modal closes when user hits "x"
        closeConfirmationModal.onclick = function() {
            modal.style.display = "none";
        }
    // Modal closes when user hits anywhere else of the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            closeConfirmationModal.style.display = "none";
        }
    }
    </script>

</body>

</html>