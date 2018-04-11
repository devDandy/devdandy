<?php
    function validateFirstName($inName) {
        global $validForm, $inVolFirstNameError;
        $inVolFirstNameError = "";
        if (empty($inName)) {
            $validForm = false; 
            $inVolFirstNameError = "This field is required. ";
        }
    }

    function validateLastName($inName) {
        global $validForm, $inVolLastNameError;
        $inVolLastNameError = "";
        if (empty($inName)) {
            $validForm = false; 
            $inVolLastNameError = "This field is required. ";
        }
    }

    function validateEmail($inEmail) {
        global $validForm, $inVolEmailError;
        $inVolEmailError = "";
        if (empty($inEmail)) {
            $validForm = false; 
            $inVolEmailError = "Email Address is required.";
        } else {
            if (!filter_var($inEmail, FILTER_VALIDATE_EMAIL)) {
                $validForm = false;
                $inVolEmailError = "Invalid email format.";
            }
        }
    }

    function validatePhoneNumber($inPhoneNumber ) {
        global $validForm, $inVolPhoneNumberError;

        $inVolPhoneNumberError = "";
        $cleanPhoneNumber = preg_replace("/[^0-9]/", '', $inPhoneNumber); // Eliminates everything except 0-9

        if (empty($inPhoneNumber)) {
            $validForm = false; 
            $inVolPhoneNumberError = "Phone number is required.";
        } 
        if (strlen($cleanPhoneNumber) > 10 ) {
            $validForm = false;
            $inVolPhoneNumberError = "A phone number can only have 10 numbers.";
            }  
        if(!is_numeric($cleanPhoneNumber)) {
            $validForm = false;
            $inVolPhoneNumberError = "Phone number has to be numeric.";
        }
    }
    function validateAvailablity($inAvailablity) {
        global $validForm, $inVolAvailablityError;
        $inVolAvailablityError = "";
        if (empty($inAvailablity)) {
            $validForm = false;
            $inVolAvailablityError = "Please select an checkbox.";
        }
    }
    
    function validateMsg($inMsg) {
        global $validForm, $inVolMessageError;
        $inVolMessageError = "";
        if (empty($inMsg)) {
            $validForm = false;
            $inVolMessageError = "Please provide a comment on why you want to volunteer for the Ankeny SummerFest.";
        }
    }

    function validateBirthDate($inBirthDate) {
        global $validForm, $inVolBirthDateError; 

        $inVolBirthDateError = "";

        list($m, $d, $y) = explode('-', $inBirthDate);

        if (empty($inBirthDate)) {
            $validForm = false;
            $inVolBirthDateError = "Birth date required.";
        } elseif(!checkdate($m, $d, $y)) {
            $validForm = false;
            $inVolBirthDateError = "Birth date is invalid";

        }
    }



        function reCAPTCHA() {
               global $validForm, $recaptchaError;
                $recaptchaError = "";
                function post_captcha($user_response) {
                    $fields_string = '';
                    $fields = array(
                        'secret' => 'XXX',
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

            function validateHoneypot($honeypot) {
                global $validForm;
                if (!empty($honeypot)) {
                    $validForm = false;
                }
            }
    // function validateEmail($inEmail, $inPhoneNumber, $inError) {
    //     global $validForm;
    //     $inError = "";
    //     if (empty($inPhoneNumber)) {
    //         $validForm = false; 
    //         $inError = "You must either have a phone number or a email address.";
    //     } else {
    //         if (!filter_var($inEmail, FILTER_VALIDATE_EMAIL)) {
    //             $validForm = false;
    //             $inError = "Invalid email format.";
    //         }
    //     }
    // }

    // function validatePhoneNumber($inPhoneNumber, $inEmail, $inError) {
    //     global $validForm;

    //     $inError = "";
    //     $cleanPhoneNumber = preg_replace("/[^0-9]/", '', $inPhoneNumber); // Eliminates everything except 0-9

    //     if (empty($inEmail)) {
    //         $validForm = false; 
    //         $inError = "You must either input a phone number or an email address.";
    //     } else {
    //         if (!strlen($cleanPhoneNumber) == 10) {
    //            $validForm = false;
    //            $inError = "A phone number can only have 10 numbers.";
    //         }
    //     }
    // }
    // function validateAvailablity($inAvailablity, $inError) {
    //     global $validForm;
    //     $inError = "";
    //     if (!isset($inAvailablity)) {
    //         $validForm = false;
    //         $inError = "Please select an checkbox.";
    //     }
    // }
?>
