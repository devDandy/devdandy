<?php 

    //error_reporting(E_ERROR | E_PARSE); //Removes Errors from the client
    include "php/include/Email.php";

    $inName = "";
    $inEmail = "";
    $inSubject = "";
    $inMessage = "";

    $inNameError = "";
    $inEmailError = "";
    $inSubjectError = "";
    $inMessageError = "";
    $recaptchaError = "";
    $message = "";

    $validForm = false;


    if (isset($_POST['submit'])) {

        $inName = cleanData($_POST['inName']);
        $inEmail = cleanData($_POST['inEmail']);
        $inSubject = cleanData($_POST['inSubject']);
        $inMessage = cleanData($_POST['inMessage']);

        $inNameError = $_POST['inNameError'];
        $inEmailError = $_POST['inEmailError'];
        $inSubjectError = $_POST['inSubjectError'];
        $inMessageError = $_POST['inMessageError'];
        $recaptchaError = $_POST['recaptchaError'];

        function validateName($inName) {
            global $validForm, $inNameError;
            $inNameError = "";

            if (empty($inName)) {
                $validForm = false;
                $inNameError = "Name is required.";
            }
        } // end validateName()

        function validateEmail($inEmail) {
            global $validForm, $inEmailError;
            $inEmailError = "";

            if (empty($inEmail)) {
                $validForm = false;
                $inEmailError = "Email is required.";
            } else {
                if (!filter_var($inEmail, FILTER_VALIDATE_EMAIL)) {
                    $validForm = false;
                    $inEmailError = "Invalid email format.";
                }
            }
        }

        function validateSubject($inSubject) {
            global $validForm, $inSubjectError;

            $inSubjectError = "";

            if ($inSubject == "default") {
                $validForm = false;
                $inSubjectError = "Please select a subject reason.";
            }
        }

        function validateMessage($inMessage) {
            global $validForm, $inMessageError;
            $inMessageError = "";
            if (empty($inMessage)) {
                $validForm = false;
                $inMessageError = "Message is required.";
            }

        }

        function reCAPTCHA() {
               global $validForm, $recaptchaError;
                $recaptchaError = "";
                function post_captcha($user_response) {
                    $fields_string = '';
                    $fields = array(
                        'secret' => '6Lef7jYUAAAAAIgszZsKt8ViGACk1YsC65road0Y',
                        'response' => $user_response
                    );
                    foreach($fields as $key=>$value)
                    $fields_string .= $key . '=' . $value . '&';
                    $fields_string = rtrim($fields_string, '&');
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                    curl_setopt($ch, CURLOPT_POST, count($fields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    return json_decode($result, true);
                }
                $res = post_captcha($_POST['g-recaptcha-response']);
                if (!$res['success']) {
                    $recaptchaError = "Please make sure you click the CAPTCHA box.";
                    $validForm = false;
                } else {
                    $validForm = true;
                }
            }


        $validForm = true;

        validateName($inName);
        validateEmail($inEmail);
        validateSubject($inSubject);
        validateMessage($inMessage);
         reCAPTCHA();

        if ($validForm) {
            try { 
                include "php/databaseConnect.php";

                $todaysDate = date("Y-m-d");

                // Inserting information into this table using these table columns 
                $sql = "INSERT INTO ankeny_summerfest_contact_form (";
                $sql .= "contact_name, ";
                $sql .= "contact_email, ";
                $sql .= "contact_reason, ";
                $sql .= "contact_message ";
                $sql .= ") VALUES (:contact_name, :contact_email, :contact_reason, :contact_message)";
                // prepare the SQL

                $stmt = $conn->prepare($sql);

              //BIND the values to the input parameters of the prepared statement (Sync them up to variables)
              $stmt->bindParam(':contact_name', $inName); // Assign :contact_name to $inName
              $stmt->bindParam(':contact_email', $inEmail);        
              $stmt->bindParam(':contact_reason', $inSubject);     
              $stmt->bindParam(':contact_message', $inMessage);  

              //Execute the prepared statement

              $stmt->execute();
            } catch(PDOException $e) {
                $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
                    error_log($e->getMessage());            //Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                    
                    error_log(var_dump(debug_backtrace()));
        }

        // EMAIL TO SITEOWNER

        $timestamp = date('F j, Y, g:i a');
        
        $contactEmail = new Email("");  //instantiate
        
        $contactEmail->setRecipient("djschneider1@dmacc.edu");
        $contactEmail->setSender($inEmail);
        $contactEmail->setSubject("Ankeny SummerFest - Contact Form");
        $contactEmail->setMessage("From: $inName  Email: $inEmail  Contact Reason: $inSubject Comment: $inMessage ");
        $emailStatus = $contactEmail->sendMail(); //create and send email

        //CONFIRMATION EMAIL
        $contactEmail->setRecipient($inEmail);
        $contactEmail->setSender("djschneider1@dmacc.edu");
        $contactEmail->setSubject("Ankeny SummerFest Confirmation");
        $contactEmail->setMessage("Thank you for contacting us! We just got your message and we'll reply as fast as possible! Date: $timestamp ");
        $emailStatus = $contactEmail->sendMail(); //create and send email

        $message = "Success!";


        } else {
            echo "<script>alert('not good');</script>";
        } // else

        }



    if (isset($_POST['resetContact'])) 
    {
        header("location: contact.php");
        exit;
    }


     function cleanData($data) { // sanitizes special charcters  
        $data = trim($data); // Removes extra spaces, tab, and new line 
        $data = stripslashes($data); //Removes backslashes 
        $data = htmlspecialchars($data); //Security 
        return $data;
     }




?>


<!doctype html>
<html lang="en">

<head>
    <title>Contact Us - Ankeny Summerfest</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
        <div class="header-bg">
        <nav>
                <div class="navMenu">
                    <ul class="navList">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#">Theme</a></li>
                        <li><a href="#">Sponsors</a></li>
                        <li><a href="get-involved.php">Get Involved</a></li>
                        <li class="active"><a href="contact.php">Contact Us</a></li>
                        <li><a href="admin/admin.php">Admin</a></li>
                    </ul>
                    <div class="logo"><a href="../index.php"><span class="ankeny">Ankeny</span><br> <span class="summerfest">Summerfest</span></a></div>
                </div>

        </nav>
        </div>

        
    <main id="main" >
    <h1 class="page-title">Contact Us</h1>

        <div class="container">
            <div class="contactFormContainer">
            <h1 style="color:red;"><?php echo $message?></h1>
            <h2>Questions? Comments? Concerns? </h2>
            <p class="lead">We'll answer them all!</p>
            <form method="POST" class="contactForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="Name">Name:</label><span class="error"><?php echo $inNameError; ?></span>
                    <input type="text" name="inName">
                <br>
                <label for="Email">Email Address:</label><span class="error"><?php echo $inEmailError; ?></span>
                    <input type="email" name="inEmail">
                <br>

                <label for="Subject">Subject:</label><span class="error"><?php echo $inSubjectError; ?></span>
                 <select name="inSubject">
                      <option value="default">Select Subject</option>
                      <option value="General">General</option>
                      <option value="Activities">Activities</option>
                      <option value="Promotions">Promotions</option>
                      <option value="Other">Other</option>
                </select> 
                <br>
                <label for="Message">Message:<br></label><span class="error"><?php echo $inMessageError; ?></span>
                    <textarea rows="4" cols="50" name="inMessage"></textarea> 
                <br>
                <label for="Google reCAPTCHA" name="contactFormReCAPTCHA"></label><br>
                        <span class="error recaptcha"><?php echo $recaptchaError; ?></span>
                        <div class="g-recaptcha" data-sitekey="6Lef7jYUAAAAAPTFGJ0z3REnq6B3FynQiFiqn9RK"></div>
                        <br>
                
                 <br>
                <input type="submit" name="submit" value="Submit">
                <input type="reset" name="resetContact" value="Reset">
            </form>
            </div>

            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11913.489015975509!2d-93.6182434!3d41.7124855!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xbde2ca6e3850b536!2sAnkeny+Area+Chamber+of+Commerce!5e0!3m2!1sen!2sus!4v1512762920392" width="auto" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        <p>Ankeny Area Chamber of Commerce  |  1631 SW Main Street, Ste. 204/205, Ankeny, IA 50023 | (515) 964-0685</p>
        </div>
        <!-- container -->

    </main>
    <footer id="footer" >

    </footer>
</body>

</html>