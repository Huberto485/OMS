<?php

// This script will let a logged-in user VIEW their account details and allow them to UPDATE those details
// The main job of this script is to execute an INSERT or UPDATE statement to create or update a user's account information
// but only once the data the user supplied has been validated on the client-side, and then sanitised ("cleaned") and validated again on the server-side

// execute the header script
require_once "header.php";

// default values we show in the form
$email = "";
    
// strings to hold any validation error messages
$email_val = "";
 
// disable the update form so that its not there if user isn't logged in
$show_account_form = false;

// message to output to user
$message = "";

// checks the session variable named 'loggedIn'
if (!isset($_SESSION['loggedIn'])) {
	
	// user isn't logged in, display a message saying they must be
	echo "You must be logged in to view this page.<br>";
}

//user just tried to update their profile information
elseif (isset($_POST['email'])) {
	
	// connect directly into our database
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection) {
		
		// display an error message if there is no connection
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// Add validation code here

	$email = $_POST['email'];

    // Add your sanitisation code around here
	
	// errors collected by validation
	$errors = $email_val;
	
	// check that all the validation tests passed before going to the database
	if ($errors == "") {
		
		// read their username from the session
		$username = $_SESSION["username"];
		
		// write user's info into the database
		$query = "SELECT * FROM users WHERE username='$username'";
		
		// this query can return data
		$result = mysqli_query($connection, $query);
		
		// get number of rows
		$n = mysqli_num_rows($result);
			
		// if there was a match then UPDATE their profile data, otherwise INSERT it
		if ($n > 0) {
			
			// create an UPDATE query
			$query = "UPDATE users SET email='$email' WHERE username='$username'";
			
			// run the query to update the users
			$result = mysqli_query($connection, $query);		
		}
		
		// after update is ran, display a message to the user
		if ($result) {
			
			// show a successful update message
			$message = "Profile successfully updated<br>";
		} 
		
		else {
			
			// show the set profile form
			$show_account_form = true;
			
			// show an unsuccessful update message
			$message = "Update failed<br>";
		}
	}
	
	else {
		
		// validation failed, show the form again with guidance
		$show_account_form = true;
		
		// show an unsuccessful update message
		$message = "Update failed, please check the errors above and try again<br>";
	}
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}

// user has arrived at the page for the first time, show any data already in the table
else {
	
	// read the username from the session
	$username = $_SESSION["username"];
	
	// read their profile info onto the page
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit
	if (!$connection) {
		
		// display no connection error
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// check for a row in our profiles table with a matching username
	$query = "SELECT * FROM users WHERE username='$username'";
	
	// this query can return data
	$result = mysqli_query($connection, $query);
	
	// get the total number of rows
	$n = mysqli_num_rows($result);
		
	// if there was a match then extract their profile data
	if ($n > 0){
		
		// use the identifier to fetch one row as an associative array
		$row = mysqli_fetch_assoc($result);
		// extract their profile data for use in the HTML
		$email = $row['email'];

	}
	
	// show the set profile form
	$show_account_form = true;
	
	// we're finished with the database, close the connection
	mysqli_close($connection);
	
}

if ($show_account_form) {
	
echo <<<_END

    <!-- CLIENT-SIDE VALIDATION MISSING -->
    
    <form action="account_update.php" method="post">
      Update your profile info:<br>
      Username: {$_SESSION['username']}
      <br>
      Email address: <input type="text" name="email" value="$email">
      <br>
      <input type="submit" value="Submit">
    </form>	
_END;
}

// display our message to the user
echo $message;

// finish of the HTML for this page
require_once "footer.php";
?>