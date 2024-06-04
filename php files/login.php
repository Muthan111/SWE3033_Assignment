<?php

/*  
    Description: This page processes the login form submission. The script uses sessions.

    CHANGELOG:
    1. Initial version created (04/06/2024)
    2. Commented out line 30 to line 37, overlooked this part of the code (04/06/2024)

    TO DO:
    1. Once pages are completed, make sure redirect_user function goes to the right pages (CTRL + F 'temp')
    2. IF NO SYSTEM ADMIN, REMOVE SYSTEM ADMIN PARTS
    3. login_page.inc.php needs HTML parts done for this page to work
    
    Created on 04/06/2024 by Sean
*/

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE); 

// LOGIN AUTHENTICATION FILE
require ('login_functions.inc.php');

if(isset($_SESSION["user_id"]) && isset($_SESSION["username"])){

	// Redirects the user to a page, temporary placeholder for now
    redirect_user('temp');

}
// !!! NEEDS TO BE REMOVED IF NO SYSTEM ADMIN !!!
// elseif (isset($_SESSION["admin_id"]) && isset($_SESSION["name"])){

//     // Redirects the admin to a page, temporary placeholder for now
//     // !!! REMOVE IF THERE IS NO SYSTEM ADMIN !!!
// 	redirect_user('temp');

// }
else{

	// Check if the form has been submitted:
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		require ('mysqli_connect.php');

		// Check the login:
		list ($check, $data) = check_login($dbc, $_POST['username'], $_POST['pass']);
		
		if ($check == 1) { // User Verified!
			
			// Set the session data:
			$_SESSION['user_id'] = $data['user_id'];
			$_SESSION['username'] = $data['username'];
			
			// Redirect:
			redirect_user('temp');	// Redirects the user to a page, temporary placeholder for now
				
		}
        // !!! NEEDS TO BE REMOVED IF NO SYSTEM ADMIN !!!
		// elseif ($check == 2){ // Admin Verified!

		// 	// Set the session data:
		// 	$_SESSION['admin_id'] = $data['admin_id'];
		// 	$_SESSION['name'] = $data['name'];
			
		// 	// Redirect:
		// 	redirect_user('temp'); // Redirects the admin to a page, temporary placeholder for now
		// } 
		else { // Verification / Authentication Unsuccessful!

			// Assign $data to $errors for login_page.inc.php:
			$errors = $data;

		}
			
		mysqli_close($dbc); // Close the database connection.

	} // End of the main submit conditional.

    // NEEDS HTML CODE AND DOCUMENTS
	// Create the page:
	// include ('login_page.inc.php');

}
?>