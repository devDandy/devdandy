<?php 

	function validateBusinessName($inBusinessName) {
		global $validForm, $sponBusinessNameError;
		$sponBusinessNameError = "";

		if (empty($inBusinessName)) {
			$validForm = false;
			$sponBusinessNameError = "This field is required.";
		}
	}

	function validateContactFirstName($inName) {
		global $validForm, $sponContactFirstNameError;
		$sponContactFirstNameError = "";
		if (empty($inName)) {
			$validForm = false;
			$sponContactFirstNameError = "This field is required.";
		}
	}

	function validateContactLastName($inName) {
		global $validForm, $sponContactLastNameError;
		$sponContactLastNameError = "";
		if (empty($inName)) {
			$validForm = false;
			$sponContactLastNameError = "This field is required.";
		}
	}

	function validateSponEmail($inEmail) {
        global $validForm, $sponEmailError;
        $sponEmailError = "";
        if (empty($inEmail)) {
            $validForm = false; 
            $sponEmailError = "Email Address is required.";
        } else {
            if (!filter_var($inEmail, FILTER_VALIDATE_EMAIL)) {
                $validForm = false;
                $sponEmailError = "Invalid email format.";
            }
        }
	}


    function validateSponPhoneNumber($inPhoneNumber ) {
        global $validForm, $sponPhoneNumberError;

        $sponPhoneNumberError = "";
        $cleanPhoneNumber = preg_replace("/[^0-9]/", '', $inPhoneNumber); // Eliminates everything except 0-9

        if (empty($inPhoneNumber)) {
            $validForm = false; 
            $sponPhoneNumberError = "Phone number is required.";
        } 
        if (strlen($cleanPhoneNumber) > 10 ) {
            $validForm = false;
            $sponPhoneNumberError = "A phone number can only have 10 numbers.";
            }  
        if(!is_numeric($cleanPhoneNumber)) {
            $validForm = false;
            $sponPhoneNumberError = "Phone number has to be numeric.";
        }
    }

    function validateWebsite($inWebsite) {
        global $validForm, $sponWebsiteError;

        $sponWebsiteError = "";

        if (empty($inWebsite)) {
        	$validForm = false;
        	$sponWebsiteError = "A website is required.";
        } else {
        	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $inWebsite)) {
        		$validForm = false;
        		$sponWebsiteError = "Invalid URL";
        	}
        }
    }

    function validateSponMsg($inMsg) {
        global $validForm, $sponMessageError;
        $sponMessageError = "";
        if (empty($inMsg)) {
            $validForm = false;
            $sponMessageError = "Please tell us why you would like to sponsor Ankeny SummerFest.";
        }
    }
            function validateHoneypot($honeypot) {
                global $validForm;
                if (!empty($honeypot)) {
                    $validForm = false;
                }
            }
?>

