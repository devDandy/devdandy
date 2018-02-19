<?php

	$dataArray = array();

	if (isset($_POST['submit-btn'])) 
	{

		$dataArray = $_POST;

		$dataArray = sanitizeInput($dataArray);

		$errorsArray = validateInput($dataArray);

		if (count($errorsArray) == 0) 
		{
			echo "yay";
		}
	}

	function sanitizeInput($inputArray) 
	{

		$inputArray['inFirstName'] = filter_var($inputArray['inFirstName'],FILTER_SANITIZE_STRING);
		$inputArray['inFirstName'] = filter_var($inputArray['inFirstName'],FILTER_SANITIZE_STRING);
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


		return $errorsArray;

	}
	function sendEmail($sanitizedArray) 
	{

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
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>


			<div class="nav-block">
				<nav>
					<ul class="desktop-menu">
						<li class="desktop-menu-item "><a href="index.php"><span class="section-title-blue">dev</span>Dandy</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="homework/homework.html">HW</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="about.html">About</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="resume.html">Resume</a></li>
						<li class="desktop-menu-item"><a class="desktop-menu-item-link" href="portfolio.html">Portfolio</a></li>
						<li class="desktop-menu-item">
							<a class="desktop-menu-item-link" href="contact.html">Contact</a>
							<img class="active" src="assets/img/png/popsicle-right.png">
						</li>
					</ul>
					<ul class="desktop-social-media">
						<li>Github</li>
						<li>Twitter</li>
						<li>Linkedin</li>
					</ul>
				</nav>
			</div>

			<div class="container">
				<div class="page-box">

					<img class="logo" src="assets/img/png/logo.png" title="devDandy" alt="The logo of devDandy">
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
									<textarea cols="40" rows="4"><?php echo echoValue($dataArray, 'inBusinessName');?></textarea>
								</div>
							</div>
							<div class="btn-group">
								<button class="form-btn submit-btn" name="submit-btn">Submit</button>
								<button class="form-btn reset-btn">Reset</button>
							</div>



						</form>
				</div>
				</div>




			</div>

</body>
</html>