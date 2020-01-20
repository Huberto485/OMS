<?php

//this script can be called via require_once command - use 'require_once('header.php')'
//this is the header of every web page of this system

// database connection details:
require_once "credentials.php";

// our helper functions:
require_once "helper.php";

// start/restart the session:
// this allows use to make use of session variables
session_start();

// checks the session variable named 'loggedInSkeleton'
if (isset($_SESSION['loggedIn']))
{
	// THIS PERSON IS LOGGED IN
	// show the logged in menu options:

echo <<<_END
<!DOCTYPE html>
<html>
<head><title>Order Management System</title></head>
<body>
<a href='account.php'>My Account</a> ||
<a href='surveys_manage.php'>Transactions</a> ||
<a href='competitors.php'>Design and Analysis</a> ||
<a href='sign_out.php'>Sign Out ({$_SESSION['username']})</a>
_END;

    // add an extra menu option if this was the admin:
    // this allows us to display the admin tools to them only
	if ($_SESSION['username'] == "admin")
	{
		echo " |||| <a href='admin.php'>Admin Tools</a>";
	}
}

else
{
	// THIS PERSON IS NOT LOGGED IN
	// show the logged out menu options:
echo <<<_END
<!DOCTYPE html>
<html>
<body>
<a href='about.php'>About</a> ||
<a href='sign_up.php'>Sign Up</a> ||
<a href='sign_in.php'>Sign In</a>
_END;
}

echo <<<_END
<br>
<h1>ORDER MANAGEMENT SYSTEM</h1>
_END;
?>