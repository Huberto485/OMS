<?php

// This webpage will only be displayed if the admin is is logged into the system

// execute the header script
require_once "header.php";

// checks the session variable named 'loggedIn'
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be
	echo "You must be logged in to view this page.<br>";
}

// the admin must be signed-in, show them suitable page content
else
{
	// only display the page content if this is the admin account
	if ($_SESSION['username'] == "admin")
	{
		echo "Implement the admin tools here... See the assignment specification for more details.<br>";
	}

	else
	{
		echo "You don't have permission to view this page...<br>";
	}
}

// finish off the HTML for this page
require_once "footer.php";
?>