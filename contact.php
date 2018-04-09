<?php

	$isSuccess = isset($_GET['success']);

	$dataArray = array();

	if (isset($_POST['submit-btn'])) 
	{

		$dataArray = $_POST;

		$dataArray = sanitizeInput($dataArray);

		$errorsArray = validateInput($dataArray);

		if (count($errorsArray) == 0) 
		{
			if (!sendEmail($dataArray)) 
			{
            	$errorsArray['submit-btn'] = "Send Failed";
			}
			else 
			{
          		header("location: " . $_SERVER['SCRIPT_NAME'] . "?success");
          		exit;
			}
		}
	}

	function sanitizeInput($inputArray) 
	{

		$inputArray['inFirstName'] = filter_var($inputArray['inFirstName'],FILTER_SANITIZE_STRING);
		$inputArray['inLastName'] = filter_var($inputArray['inLastName'],FILTER_SANITIZE_STRING);
		$inputArray['inBusinessName'] = filter_var($inputArray['inBusinessName'],FILTER_SANITIZE_STRING);

		return $inputArray;
	}

	function validateInput($inputArray) 
	{
		$errorsArray = array();

		//First name is required
		if (empty($inputArray['inFirstName'])) 
		{
			$errorsArray['inFirstName'] = 'First Name is required.';
		}

		//Last name is required
		if (empty($inputArray['inLastName'])) 
		{
			$errorsArray['inLastName'] = 'Last  Name is required.';
		}

		//Validate email format
		if (empty($inputArray['inEmailAddress'])) {
			$errorsArray['inEmailAddress'] = "Email is required.";
		} else 
		{
	        if (!filter_var($inputArray['inEmailAddress'], FILTER_VALIDATE_EMAIL))
			{
				$errorsArray['inEmailAddress'] = "Email is not in a valid format <br>(Ex. yourName@host.com)";
			}
		}

		//Validate message input
		if (empty($inputArray['inMessage'])) {
			$errorsArray['inMessage'] = "Message is required.";
		} 

		return $errorsArray;

	}
	function sendEmail($sanitizedArray) 
	{
	    // quick message
	    $message = "Your email has been sent!" . PHP_EOL . PHP_EOL;
	    foreach ($sanitizedArray as $key => $value) 
	    {
	        $message .= $key . ": " . $value . PHP_EOL;
	    }	    
	    $sendSuccess = mail("dschnd@gmail.com" , "Thank you for contacting devDandy!", $message);
	    
	    return $sendSuccess;
	}

	function echoValue($dataArray, $key) 
	{
		return (isset($dataArray[$key]) ? $dataArray[$key] : '');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Contact Me - devDandy</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>

		<nav>
			<div class="nav-block">
					<ul class="desktop-menu">
						<li class="desktop-menu-item "><a href="index.php"><span class="section-title-blue">dev</span>Dandy</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="homework/index.html">HW</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="about.html">About</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="resume.html">Resume</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="portfolio.html">Portfolio</a></li>
						<li class="desktop-menu-item">
							<a class="desktop-menu-item-link" href="#">Contact</a>
							<img class="active" src="assets/img/png/popsicle-right.png">
						</li>
					</ul>
					<ul class="desktop-social-media">
						<li><a href="https://github.com/devdandy"><img src="assets/img/png/github.png" title="Dan Schneider's Github" alt="Dan's GitHub"></a></li>
						<li><a href="https://www.linkedin.com/in/dan-schneider1/"><img src="assets/img/png/linkedin.png" title="Dan Schneider's Linkedin" alt="Dan's Linkedin"></a></li>
					</ul>
					<!-- Copyright -->
					<div class="copyright-information">
						<p>&copy;<script>new Date().getFullYear()>document.write(new Date().getFullYear());</script>, devDandy Web Development</p> <p>All Rights Reserved.</p> 
					</div>
			</div>
						<div  class="mobile-nav">
							<a href="index.php" class="nav-header"><h1><span class="section-title-blue">dev</span>Dandy</h1></a>
							<span onclick="openNav()" class="open-hamburger-menu">
								<img src="assets/img/png/menu.png">

							</span>
						</div>
						<div class="mobile-menu" id="mobile-menu">
  							<a href="javascript:void(0)" class="close-btn" onclick="closeNav()"> &times; </a>
							<a href="index.php"><h1>devDandy</h1></a> 
							<a href="homework/index.html">Homework</a> 
							<a href="about.html">About</a>
							<a href="resume.html">Resume</a> 
							<a href="portfolio.html">Portfolio</a> 
							<a href="#">Contact</a>
						</div>
		</nav>

			<div class="container">
				<div class="page-box">

					<img class="logo" src="assets/img/png/logo.png" title="devDandy" alt="The logo of devDandy">
					<?php if ($isSuccess)
					{ ?>
						<h1 class="section-title">
							&lt;&lt; Success! &gt;&gt;
						</h1>
						<div class="colored-borders">
							<div class="success-message-container">
								<p class="success-message-text">Your message has been sent!</p>
								<button class="success-message-button">
									Return To Contact
								</button>
							</div>
						</div>
					<?php }
					else
					{ ?>
						<h1 class="section-title">
							<span class="section-title-blue">&lt;&lt;Contact</span> <span class="section-title-red">Me&gt;&gt;</span>
						</h1>
						<div class="colored-borders">
							<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" class="contact-form">
								<div class="row">
									<div class="col-6">
										<div>
											<label for="First Name">First Name</label>
											
											<?php if (isset($errorsArray))
											{ ?>
											<span class="form-error"><?php echo echoValue($errorsArray, 'inFirstName');  ?></span>
											 <?php } ?>
											
											<input type="text" name="inFirstName" class="border-red" value="<?php echo echoValue($dataArray, 'inFirstName');?>">
										</div>
									</div>
									<div class="col-6">
										<div>
											<label for="Last Name">Last Name</label>

											<?php if (isset($errorsArray)) 
											{ ?>
											<span class="form-error"><?php echo echoValue($errorsArray, 'inLastName');  ?></span>	
											<?php } ?>

											<input type="text" name="inLastName" value="<?php echo echoValue($dataArray, 'inLastName');?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div>
											<label for="Email Address">Email Address</label>

											<?php if (isset($errorsArray)) 
											{ ?>
											<span class="form-error"><?php echo echoValue($errorsArray, 'inEmailAddress');  ?></span>
											<?php } ?>

											<input type="text" name="inEmailAddress" value="<?php echo echoValue($dataArray, 'inEmailAddress');?>">
										</div>
									</div>
									<div class="col-6">
										<div>
											<label for="Business Name">Business Name</label>
											<input type="text" name="inBusinessName" class="border-red" value="<?php echo echoValue($dataArray, 'inBusinessName');?>">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-12">
										<label for="Message">Message</label>
											<?php if (isset($errorsArray)) 
											{ ?>
											<span class="form-error textarea-error"><?php echo echoValue($errorsArray, 'inMessage');  ?></span>
											<?php } ?>
										<textarea cols="40" rows="4" name="inMessage"><?php echo echoValue($dataArray, 'inMessage');?></textarea>
									</div>
								</div>
								<div class="btn-group">
									<button class="form-btn submit-btn" name="submit-btn">Submit</button>
									<button class="form-btn reset-btn">Reset</button>
								</div>
							</form>
					</div>
					<?php } ?>
				</div>




			</div>
<script src="assets/js/main.js"></script>

</body>
</html>