<?php 
/*  
    Description: This page includes the check_login function that verifies the input values, and then authenticate the login details once those values are verified. 
    If authentication passes, will return either 1 or 2 (if system admin still exists). Otherwise, returns a 0 (Failed to authenticate).
    Includes a redirect_user function to redirect users to another page. (To redirect once logged in, or if already logged in)

    CHANGELOG:
    1. Initial version created (04/06/2024)
	2. Removed redirect_user function and placed it into its own separate file since it's being referenced by multiple files. Included a dependency to redirect_function.php. (06/06/2024)
	3. Partially updated the SQL (08/06/2024)

    TO DO:
    1. Update SQL statements once database is completed
    2. IF NO SYSTEM ADMIN, REMOVE SYSTEM ADMIN PARTS
    
    Created on 04/06/2024 by Sean
*/

// Dependencies
include ('redirect_function.php');

function check_login($dbc, $email = '', $pass = '') {

	$errors = array(); // Initialize error array.

	// Validate the username
	if (empty($_POST['username'])){
		$errors[] = 'You forgot to enter your username';
	} else{
		$n = mysqli_real_escape_string($dbc, trim($_POST['username']));
	}
	
	// Validate the password
	if (empty($_POST['pass'])){
		$errors[] = 'You forgot to enter your password';
	} else{
		$p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
	}

	if (empty($errors)) { // If everything's OK.

		// Retrieve the user_id and first_name for that email/password combination:
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED, HOW IS THE PASSWORD BEING SAVED? NEED TO ENCRYPT?
		$q = "SELECT userID, username FROM account WHERE username='$n' AND password=SHA1('$p')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// Check the result:
		if (mysqli_num_rows($r) == 1) {

			// Fetch the record:
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
	
			// Return true and the record:
			return array(1, $row);
			
		} 

        // !!! NEEDS TO BE REMOVED IF NO SYSTEM ADMIN !!!
        // else { // Not a match in user table!
			
        //     // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        //     $q = "SELECT admin_id, name FROM admin WHERE name='$n' AND password=SHA1('$p')";
        //     $r = @mysqli_query ($dbc, $q); // Run the query.
            
        //     if (mysqli_num_rows($r) == 1) {

        //         // Fetch the record:
        //         $row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
        
        //         // Return true and the record:
        //         return array(2, $row);
                
        //     } else { // Not a match in admin table!
        //         $errors[] = 'The username and password entered do not match those on file.';
        //     }
		// }

        else{ // Not a match in user table!
            $errors[] = 'The username and password entered do not match those on file.';
        }
		
	} // End of empty($errors) IF.
	
	// Return false and the errors:
	return array(0, $errors);

} // End of check_login() function.


?>