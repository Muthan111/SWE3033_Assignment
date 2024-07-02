<?php 
/*  
    Description: This page includes the check_login function that verifies the input values, and then authenticate the login details once those values are verified. 
    If authentication passes, will return either 1 or 2 (if system admin still exists). Otherwise, returns a 0 (Failed to authenticate).
    Includes a redirect_user function to redirect users to another page. (To redirect once logged in, or if already logged in)

    CHANGELOG:
    1. 	Initial version created (04/06/2024)
	2.	Removed redirect_user function and placed it into its own separate file since it's being referenced by multiple files. Included a dependency to redirect_function.php. (06/06/2024)
	3. 	Partially updated the SQL (08/06/2024)
	4. 	Moved to root folder, updated POST submission to email rather than username, temporarily removed password encryption and changed to email for SQL, functions work as intended
		(11/06/2024)
	5.	Removed system admin parts (21/06/2024)

    TO DO:
    
    Created on 04/06/2024 by Sean
*/

// Dependencies
include ('redirect_function.php');

function check_login($dbc, $email = '', $pass = '') {

	$errors = array(); // Initialize error array.

	// Validate the email
	if (empty($_POST['email'])){
		$errors[] = 'You forgot to enter your email';
	} else{
		$n = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	// Validate the password
	if (empty($_POST['pass'])){
		$errors[] = 'You forgot to enter your password';
	} else{
		$p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
	}

	if (empty($errors)) { // If everything's OK.

		// Retrieve the user_id and first_name for that email/password combination:
		$q = "SELECT userID, username FROM account WHERE email='$n' AND password='$p'";	
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// Check the result:
		if (mysqli_num_rows($r) == 1) {

			// Fetch the record:
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
	
			// Return true and the record:
			return array(1, $row);
			
		} 

        else{ // Not a match in user table!
            $errors[] = 'The email and password entered do not match those on file.';
        }
		
	} // End of empty($errors) IF.
	
	// Return false and the errors:
	return array(0, $errors);

} // End of check_login() function.


?>