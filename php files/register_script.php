<?php

/*  
    Description: This script performs an INSERT query to add a record to the users table. It also verifies and ensures that the user registration actually matches requirements

    CHANGELOG:
    1. Initial version created (04/06/2024)
    2. Commented out phone number code, added verification for first name and last name, added validation for long usernames, partially updated SQL. (08/06/2024)

    TO DO:
    1. Update SQL statements once database is completed
    2. IF NO SYSTEM ADMIN, REMOVE SYSTEM ADMIN PARTS
    3. Include HTML page once done
    4. What are the password requirements? How is user id generated?
    
    Created on 04/06/2024 by Sean
*/

require ('login_functions.inc.php');

// Check if user already logged in
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])){
	redirect_user("user_page.php");
}
// !!! NEEDS TO BE REMOVED IF NO SYSTEM ADMIN !!!
// elseif (isset($_SESSION["admin_id"]) && isset($_SESSION["name"])){
// 	redirect_user("admin_register.php");
// }

// Initialising variables
$username = "username";
$email = "email";
$first_name = "First Name";
$last_name = "Last Name";
// $phone = "+60XXXXXXXXX";

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('mysqli_connect.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

	// Validate username
    if (empty($_POST['username'])) {
        $errors[] = "Username is required";
    } elseif (strlen($_POST['username']) > 25){
        $errors[] = "Username is too long";
    }
	else{
		$username = mysqli_real_escape_string($dbc, $_POST['username']);
	}

    // Validate first name
    if (empty($_POST['fname'])) {
        $errors[] = "First name is required";
    } elseif (strlen($_POST['username']) > 25){
        $errors[] = "First name is too long";
    }
	else{
		$first_name = mysqli_real_escape_string($dbc, $_POST['fname']);
	}

    // Validate last name
    if (empty($_POST['lname'])) {
        $errors[] = "Last name is required";
    } elseif (strlen($_POST['username']) > 25){
        $errors[] = "Last name is too long";
    }
	else{
		$last_name = mysqli_real_escape_string($dbc, $_POST['lname']);
	}

    // Validate email
    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
	else{
		$email = mysqli_real_escape_string($dbc, $_POST['email']);
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        //  Test for unique email address
        $q = "SELECT user_id FROM user WHERE email = '$email'";
        $r = @mysqli_query($dbc, $q);
        if (mysqli_num_rows($r) != 0){
            $errors[]= "The email address has already been registered";
        }
	}


    // // Validate phone number
	// $phone_regex = "/^(\+?6?01)[02-46-9]-*[0-9]{7}$|^(\+?6?01)[1]-*[0-9]{8}$/"; //Includes optional +60, only accepts Malaysian phone numbers
    // if (empty($_POST['phone'])) {
    //     $errors[] = "Phone number is required";
    // }
	// elseif (!preg_match($phone_regex, $_POST['phone'])) {
	// 	$errors[] = "Phone number is invalid";
	// }
	// else{
	// 	$phone = mysqli_real_escape_string($dbc, trim($_POST['phone']));
	// }
	
    // //  Test for unique phone number
    // // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
	// $q = "SELECT user_id FROM user WHERE phone_no = '$phone'";
	// $r = @mysqli_query($dbc, $q);
    // if (mysqli_num_rows($r) != 0){
    //     $errors[]= "The phone number has already been registered";
    // }


    // Validate password
    if (empty($_POST['pass1'])) {
        $errors[] = "Password is required";
    }
	else{
		$password = mysqli_real_escape_string($dbc, $_POST['pass1']);
	}

    // Validate confirm password
    if (empty($_POST['pass2'])) {
        $errors[] = "Confirm password is required";
    } 
    elseif ($password !== $_POST['pass2']) {
        $errors[] = "Password and confirm password do not match";
    }

	// Validate Password Requirements
	// Requirement 1: At least 8 characters long
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    // Requirement 2: A combination of uppercase and lowercase letters
    if (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain a combination of uppercase and lowercase letters";
    }
    // Requirement 3: At least 1 number
    if (!preg_match('/\d/', $password)) {
        $errors[] = "Password must contain at least 1 number";
    }
    // Requirement 4: At least 1 special character
    if (!preg_match('/.*[!@#$%^&*()_+{}|:"<>?`~\-=[\];\',.\/\\\\].*/', $password)) {
        $errors[] = "Password must contain at least 1 special character";
    }
		
	if (empty($errors)) {

		// Make the query:
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
		$q = "INSERT INTO account (username, firstName, lastName, email, password) VALUES ('$username', '$first_name', '$last_name', '$email', SHA1('$password'))";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
            
            // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
            $q = "SELECT userID, username FROM user WHERE email = '$email'";
            $r = @mysqli_query ($dbc, $q);

            $row = mysqli_fetch_assoc($r);
			// Set Session
            $_SESSION['user_id'] = $row['user_id'];
			$_SESSION['username'] = $row['username'];
            // Redirects the user to a page, temporary placeholder for now
            redirect_user("temp");	
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // End of if ($r) IF.

		mysqli_close($dbc); // Close the database connection.

		exit();

	}
	
	mysqli_close($dbc); // Close the database connection.

    // Registration page here
    include('temp');

} // End of the main Submit conditional.
?>