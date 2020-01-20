<?php

// This script holds the sanitisation function that we pass all our user data to
// This script holds the validation functions that double-check our user data is valid
// You can add new PHP functions to validate different kinds of user data (e.g., emails, dates) by following the same convention:
// If the data is valid return an empty string, if the data is invalid return a help message
// You should add all frequently used function to this script

/////////////////////////////////////////////////////////////////
///////////////////  SANITISATION FUNCTION  /////////////////////
/////////////////////////////////////////////////////////////////

// function to sanitise (clean) user data:
function sanitise($str, $connection) {
	if (get_magic_quotes_gpc()) {
		// just in case server is running an old version of PHP with "magic quotes" running:
		$str = stripslashes($str);
	}

	// escape any dangerous characters, e.g. quotes:
	$str = mysqli_real_escape_string($connection, $str);
	// ensure any html code is safe by converting reserved characters to entities:
	$str = htmlentities($str);
	// return the cleaned string:
	return $str;
}

/////////////////////////////////////////////////////////////////
//////////////////  VALIDATE STRING FUNCTION  ///////////////////
/////////////////////////////////////////////////////////////////

// if the data is valid return an empty string, if the data is invalid return a help message
function validateString($field, $minlength, $maxlength) {
    
	if (strlen($field)<$minlength) {
		
		// wasn't a valid length, return a help message:		
        return "Minimum length: " . $minlength; 
    }

	elseif (strlen($field)>$maxlength) {
		
		// wasn't a valid length, return a help message:
        return "Maximum length: " . $maxlength; 
    }

	// data was valid, return an empty string
    return ""; 
}

/////////////////////////////////////////////////////////////////
/////////////////  VALIDATE INTEGER FUNCTION  ///////////////////
/////////////////////////////////////////////////////////////////

// if the data is valid return an empty string, if the data is invalid return a help message
function validateInt($field, $min, $max) 
{ 
	// see PHP manual for more info on the options: http://php.net/manual/en/function.filter-var.php
	$options = array("options" => array("min_range"=>$min,"max_range"=>$max));
    
	if (!filter_var($field, FILTER_VALIDATE_INT, $options)) 
    { 
		// wasn't a valid integer, return a help message:
        return "Not a valid number (must be whole and in the range: " . $min . " to " . $max . ")"; 
    }
	// data was valid, return an empty string
    return ""; 
}

/////////////////////////////////////////////////////////////////
//////////////////  VALIDATE EMAIL FUNCTION  ////////////////////
/////////////////////////////////////////////////////////////////

//validate the email string pushed through
function validateEmail($field) {
	
	//check if the email is in a valid format
	if (!filter_var($field, FILTER_VALIDATE_EMAIL)){
		
		//format was invalid, return an error message
		return "Invalid email format";
	}
	
	//email was valid, return empty string
	return "";
}

/////////////////////////////////////////////////////////////////
////////////////  VALIDATE BIRTHDATE FUNCTION  //////////////////
/////////////////////////////////////////////////////////////////

//check the validity of a date and use a preset format (YEAR-MONTH-DAY)
function validateDate($field, $format = 'Y-m-d') {
	//format the field
	$d = DateTime::createFromFormat($format, $field);
	//return formatted field as a date
	return $d && $d->format($format) == $field;

}

//if the date of birth is valid return an empty string, otherwise return a message
function validateDOB($field) {
	
	//check if the date is in the right format
	if(var_dump(validateDate($field, 'Y-m-d'))) {
		
		//the date wasn't in the right format, return an error message
		return "Invalid date";
	}
	
	//date was valid, return an empty string
	return "";
}

?>