<?php

// The main job of this script is to execute a SELECT statement to find the user's profile information (then display it)
// The script also allows the user to go to the 'account_update.php' page in order to update their details

// load the header
require_once "header.php";

// checks the session variable named 'loggedIn'
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be
	echo "You must be logged in to view this page.<br>";
}

// the user is signed-in, show them suitable page content
else
{    
    // user is already logged in, read their username from the session:
	$username = $_SESSION["username"];
	
	// Use credentials to connect to the database
	// $dbhost - name of the host, in this case 'localhost'
	// $dbuser - name of the user, in this case 'root'
	// $dbpass - password for access to the database, in this case '' (empty)
	// $dbname - name of the databse, in this case 'oms'
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit
	if (!$connection) {
		
		//display error message on failure
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// check for a row in our profiles table with a matching username
	$query = "SELECT * FROM users WHERE username='$username'";
	
	// this query can return data ($result is an identifier)
	$result = mysqli_query($connection, $query);
	
	// check the total number of rows fetched
	$n = mysqli_num_rows($result);
		
	// if there was a match then extract their profile data
	if ($n > 0)
	{
		// use the identifier to fetch one row as an associative array
		$row = mysqli_fetch_assoc($result);
		
		// display their profile data
		echo "Username: {$row['username']}<br>";
		echo "Password: {$row['password']}<br>";
		echo "Email: {$row['email']}<br>";
        echo "<br>You can <a href='account_set.php'>update</a> your account details here.<br>";
	}

	else
	{
		// no match found, prompt user to set up their profile
		echo "You need to set up a profile!<br>";
	}
	
	// we're finished with the database, close the connection
	mysqli_close($connection);
		
}

// load the footer of the web page
require_once "footer.php";
?>