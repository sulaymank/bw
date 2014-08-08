<?php

include("config.php");
include('functions.php');

$errors = array();
$formsubmit = htmlspecialchars(filter_input(INPUT_POST, 'formsubmit', FILTER_SANITIZE_STRING));

if (isset($formsubmit) && $formsubmit == "5106c9b86fd53299437a15bd8923866d") {	//Form was submitted

	$id = (filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
	$firstname = (filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
	$lastname = (filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
	$province = (filter_input(INPUT_POST, 'province', FILTER_SANITIZE_STRING));
	$email = (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
	$emailconfirm = (filter_input(INPUT_POST, 'emailconfirm', FILTER_VALIDATE_EMAIL));
	$age = (filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING));
	$gender = (filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING));
	$consent = (filter_input(INPUT_POST, 'consent', FILTER_SANITIZE_STRING));
	$language ="EN";
	$now = date("Y-m-d H:i:s");

	
	//Server-side form validation
	
	if ($firstname == "" || !$firstname){
		$errors[] = "First Name missing or invalid";
	}
	if ($lastname == "" || !$lastname){
		$errors[] = "Last Name missing or invalid";
	}
	if ($province == "" || !$province){
		$errors[] = "Province missing or invalid";
	}
	if (!$email || !$emailconfirm) {
		$errors[] = "Invalid Email address";
	}
	if ($email <> $emailconfirm) {
		$errors[] = "Confirm email must be the same as email address";
	}
	if ($age == "" || !$age){
		$errors[] = "Age group missing or invalid";
	}
	if ($gender == "" || !$gender){
		$errors[] = "Gender missing or invalid";
	}
	if ($consent == "" || !$consent){
		$errors[] = "Required consent field missing or invalid";
	}


	if (!count($errors)) {

		if ($consent == "fullconsent") {
			/* Create a prepared statement */
			if($stmt = $mysqli -> prepare("UPDATE " . $maintblname . " SET
					firstname = ?,
					lastname = ?,
					province = ?,
					email = ?,
					age = ?,
					gender = ?,
					language = ?,
					registration_date = ?,
					aspirin81 = '1',
					aspirinforpain = '1',
					bayercoupon = '1',
					canesoral = '1',
					canesten = '1',
					maxidol = '1',
					midol = '1',
					startingwithme = '1',
					myoflex = '1',
					has_confirmed = '1'
				WHERE
					secret_id = ?
				")) {

				/* Bind parameters
					 Types: s = string, i = integer, d = double,  b = blob */
				$stmt -> bind_param("sssssssss", $firstname, $lastname, $province, $email, $age, $gender, $language, $now, $id);

				/* Execute it */
				$stmt -> execute();

				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error;
				} else {
					updateActivityLog($email, "antispam", "Consented");
					header ("Location: thanks.php?id=".$id);
				}
				  
				/* Close statement */
				$stmt -> close();

				/* Close connection */
				$mysqli -> close();

			} else {
				die( "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error );
			}


		} else if ($consent == "partialconsent") {

			/* Create a prepared statement */
			if($stmt = $mysqli -> prepare("UPDATE " . $maintblname . " SET
					firstname = ?,
					lastname = ?,
					province = ?,
					email = ?,
					age = ?,
					gender = ?,
					language = ?,
					registration_date = ?,
					has_confirmed = '1'
				WHERE
					secret_id = ?
				")) {

				/* Bind parameters
					 Types: s = string, i = integer, d = double,  b = blob */
				$stmt -> bind_param("sssssssss", $firstname, $lastname, $province, $email, $age, $gender, $language, $now, $id);

				/* Execute it */
				$stmt -> execute();

				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error;
				} else {
					
					updateActivityLog($email, "antispam", "Consented");
					header ("Location: thanks.php?id=".$id);
				}
				  
				/* Close statement */
				$stmt -> close();

				/* Close connection */
				$mysqli -> close();

			} else {
				die( "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error );
			}

			

		}

	}

} else {
	$errors[] = "You did not submit the form";
}


?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title; ?></title>
        <meta name="description" content="<?php echo $description; ?>">
		<meta name="keywords" content="<?php echo $keywords; ?>">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <meta name="viewport" content="width=1200">

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="css/ie.css" />
	<![endif]-->
		
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>

    </head>
    <body>
		<?php include ('GTMA.php'); ?>
		<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		<div class="wrapper">

			<header id="homeheader">
				<div id="topsection" class="container">
					<h1 class="blue" style="color:red;">Submission Errors</h1>
					<img src="img/bayer-logo.png" alt="Bayer" id="logo" />
				</div>
			</header>
			

			<section class="container">
				
				<article>
					<?php
						if(count($errors)) {
							foreach ($errors as $error) {
								echo "<h2 style='color:red;'> - ".$error."</h2>";
							}
						}
					?>
				</article>

				<div class="clear"></div>

			</section>

        
		</div>
    </body>
</html>
