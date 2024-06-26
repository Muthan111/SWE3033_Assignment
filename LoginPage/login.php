<?php

/*  
    Description: This page processes the login form submission. The script uses sessions.

    CHANGELOG:
    1. 	Initial version created (04/06/2024)
    2. 	Commented out line 30 to line 37, overlooked this part of the code (04/06/2024)
	3.	Change username input to email input, added html file at the end, moved login.php to root folder, tested login system, works as intended, user is redirected to homepage
		once logged in. Added the email variable so that users don't have to re-enter their email everytime they fail the login.(11/06/2024)
	4.	Fixed user ID not being set in the session variables and redirect users to hompage once they are logged in(15/06/2024)
	5.	Removed system admin and udpated redirect_user to go to the correct page (21/06/2024)
	6.	Fixed the redirect function (it was .html instead of .php) (24/06/2024)

    TO DO:
    
    Created on 04/06/2024 by Sean
*/

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE); 

// LOGIN AUTHENTICATION FILE
require ('../PHP/login_functions.inc.php');

// Initialising variables
$email = "Registered email";

if(isset($_SESSION["user_id"]) && isset($_SESSION["username"])){

	// Redirects the user to a page, temporary placeholder for now
    redirect_user('../HomePage/Homepage.php');

}
else{

	// Check if the form has been submitted:
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		require ('../PHP/mysqli_connect.php');

		// Makes sure that the user does not have to input the email for each failed login (the email they entered stays in the text field)
		$email = $_POST['email'];

		// Check the login:
		list ($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);
		
		if ($check == 1) { // User Verified!
			
			// Set the session data:
			$_SESSION['user_id'] = $data['userID'];
			$_SESSION['username'] = $data['username'];
			
			// Redirect:
			redirect_user('../HomePage/Homepage.php');	// Redirects the user to a page, temporary placeholder for now
				
		}

		else { // Verification / Authentication Unsuccessful!

			// Assign $data to $errors for login_page.inc.php:
			$errors = $data;

		}
			
		mysqli_close($dbc); // Close the database connection.

	} // End of the main submit conditional.

	// Create the page:
	include ('login_html.php');

}
?>