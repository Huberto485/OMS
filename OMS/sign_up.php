<?php

// This script collects the user's data into a form and then uses an INSERT statement to add the data into the database

// execute the header script
require_once "header.php";

// default values we show in the form
$username = "";
$password = "";
$email = "";

// strings to hold any validation error messages
$username_val = "";
$password_val = "";
$email_val = "";

// do not display the sign up form
$show_signup_form = false;

// message to output to user
$message = "";

// checks the session variable named 'loggedIn'
if (isset($_SESSION['loggedIn'])) {
	
	// user is already logged in, just display a message
	echo "You are already logged in, please log out if you wish to create a new account<br>";
	
}

elseif (isset($_POST['username'])) {
	
	// user just tried to sign up
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit
	if (!$connection) {
		
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	// SANITISATION (see helper.php for the function definition)
	
	// take copies of the credentials the user submitted, and sanitise (clean) them:
	$username = sanitise($_POST['username'], $connection);
	$password = sanitise($_POST['password'], $connection);
    $email = sanitise($_POST['email'], $connection);


	// VALIDATION (see helper.php for the function definitions)
	// make sure the validation is exactly as it is in the database
	
	$username_val = validateString($username, 1, 16);
	$password_val = validateString($password, 1, 16);
    $email_val = validateString($email, 1, 64);
	
	// concatenate all the validation results together 
	$errors = $username_val . $password_val . $email_val;
	
	// check that all the validation tests passed before going to the database
	if ($errors == "") {
		
		// try to insert the new details
		$query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email');";
		$result = mysqli_query($connection, $query);
		
		// no data returned, we just test for true(success)/false(failure)
		if ($result) {
			// show a successful signup message
			$message = "Signup was successful, please sign in<br>";
		} 
		
		else {
			
			// show the form
			$show_signup_form = true;
			
			// show an unsuccessful signup message
			$message = "Sign up failed, please try again<br>";
		}
			
	}

	else {
		
		// validation failed, show the form again with guidance
		$show_signup_form = true;
		
		// show an unsuccessful signin message
		$message = "Sign up failed, please check the errors shown above and try again<br>";
	}
	
	// close the connection
	mysqli_close($connection);

}

else {
	
	// just a normal visit to the page, show the signup form
	$show_signup_form = true;
}

if ($show_signup_form) {
	
echo <<<_END
<form action="sign_up.php" method="post">
  Please choose a username and password:<br>
  Username: <input type="text" name="username" maxlength="16" value="$username" required> $username_val
  <br>
  Password: <input type="password" name="password" maxlength="16" value="$password" required> $password_val
  <br>
  Email: <input type="email" name="email" maxlength="64" value="$email" required> $email_val
  <br>
  <input type="submit" value="Submit">
</form>	
_END;
}

// display our message to the user
echo $message;

// finish off the HTML for this page
require_once "footer.php";

?>