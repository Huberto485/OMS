<?php

// The main job of this script is to execute a SELECT statement to look for the submitted username and password in the appropriate database table
// The script stores a number of variables that help with identifying the errors and storing data that is fetched from the table

// execute the header script
require_once "header.php";

// default values we show in the form
$username = "";
$password = "";

// strings to hold any validation error messages
$username_val = "";
$password_val = "";

// should we show the signin form
$show_signin_form = false;

// message to output to user
$message = "";

// checks the session variable named 'loggedIn'
if (isset($_SESSION['loggedIn'])) {
	
	// user is already logged in, just display a message
	echo "You are already logged in, please log out first.<br>";
}

elseif (isset($_POST['username'])) {
	
	// user has just tried to log in...
	
	// connect directly to our database
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	// SANITISATION (see helper.php for the function definition)
	
	// take copies of the credentials the user submitted and sanitise them
	$username = sanitise($_POST['username'], $connection);
	$password = sanitise($_POST['password'], $connection);
	
	// VALIDATION (see helper.php for the function definitions)
	
	// the validation should be exactly what the constrictions are in the create_data.php file
	$username_val = validateString($username, 1, 16);
	$password_val = validateString($password, 1, 16);
	
	// concatenate all the validation results together
	$errors = $username_val . $password_val;
	
	// check that all the validation tests passed before going to the database
	if ($errors == "") {
		
		// fetch the user's details and see if its a match here...
			
		// if there was a match then set the session variables and display a success message
		if ($n > 0)
		{
			// set a session variable to record that this user has successfully logged in
			$_SESSION['loggedIn'] = true;
			
			// and copy their username into the session data for use by our other scripts
			$_SESSION['username'] = $username;
			
			// show a successful signin message:
			$message = "Hi, $username, you have successfully logged in, please <a href='account.php'>click here</a><br>";
		}

		else {
			
			// no matching credentials found so redisplay the signin form with a failure message
			$show_signin_form = true;
			
			// show an unsuccessful signin message
			$message = "Sign in failed, please try again<br>";
		}
		
	}
	else 
	{
		// validation failed, show the form again with guidance
		$show_signin_form = true;
		
		// show an unsuccessful signin message
		$message = "Sign in failed, please check the errors shown above and try again<br>";
	}
	
	// we're finished with the database, close the connection
	mysqli_close($connection);

}
else {
	// user has arrived at the page for the first time, just show them the form
	$show_signin_form = true;
}

// this form allows the user to sign into the system
if ($show_signin_form) {
	
echo <<<_END
<form action="sign_in.php" method="post">
  Please enter your username and password:<br>
  Username: <input type="text" name="username" maxlength="16" value="$username" required> $username_val
  <br>
  Password: <input type="password" name="password" maxlength="16" value="$password" required> $password_val
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