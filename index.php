<?php

/*  
    Description: This script performs an INSERT query to add a record to the users table. It also verifies and ensures that the user registration actually matches requirements

    CHANGELOG:
    1. Initial version created (04/06/2024)
    2. Commented out phone number code, added verification for first name and last name, added validation for long usernames, partially updated SQL. (08/06/2024)
    3. Moved to root folder, added registration page at the end (11/06/2024)
    4. Integrated registration_script with index page, fixed SQL statements (12/06/2024)
    5. Fixed sign in link (13/06/2024)
    6. Redirect user to Homepage and properly assigned userID to session variables. Removed phone validation to reduce cluttering. (15/06/2024)

    TO DO:
    1. Update SQL statements once database is completed
    2. IF NO SYSTEM ADMIN, REMOVE SYSTEM ADMIN PARTS

    Created on 04/06/2024 by Sean
*/

require ('login_functions.inc.php');

// Check if user already logged in
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])){
	redirect_user("Homepage.HTML");
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
    $q = "SELECT userID FROM account WHERE email = '$email'";
    $r = @mysqli_query($dbc, $q);
    if (mysqli_num_rows($r) != 0){
        $errors[]= "The email address has already been registered";
    }
	}

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
      $errors[] = "Password and re-entered password do not match";
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
  // Requirement 4: At most 20 characters long
  if (strlen($password) > 20){
      $errors[] = "Password must be less than 20 characters long";
  }
		
	if (empty($errors)) {

		// Make the query:
    // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
		$q = "INSERT INTO account (username, firstName, lastName, email, password) VALUES ('$username', '$first_name', '$last_name', '$email', '$password')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
            
      // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
      $q = "SELECT userID, username FROM account WHERE email = '$email'";
      $r = @mysqli_query ($dbc, $q);

      $row = mysqli_fetch_assoc($r);
			// Set Session
      $_SESSION['user_id'] = $row['userID'];
			$_SESSION['username'] = $row['username'];
      // Redirects the user to a page, temporary placeholder for now
      redirect_user("Homepage.HTML");	
		
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

} // End of the main Submit conditional.
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./index.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    />
  </head>
  <body>
    <div class="registration">
      <main class="registration1">
        <div class="content">
          <img
            class="scorpio-task-manager-logo"
            loading="lazy"
            alt=""
            src="scorpio-icon.png"
          />
          <h2 class="scorpio-task-manager">Scorpio Task Manager</h2>
          <div class="create-an-account-group">
            <h3 class="create-an-account">Create an account</h3>
            <div class="enter-your-email">
              Enter your email to sign up for this app
            </div>
          </div>
          <form id="register_form" action="index.php" method="post">
            <div class="input-and-button">
              <div class="field">
                <input 
                  class="label"
                  name="username"
                  id="username"
                  value="<?php echo $username ?>" 
                  type="text"
                />
              </div>
              <div class="field1">
                <input
                  class="label1"
                  name="fname"
                  id="fname"
                  value="<?php echo $first_name ?>"
                  type="text"
                />
              </div>
              <div class="field2">
                <input
                  class="label2"
                  name="lname"
                  id="lname"
                  value="<?php echo $last_name ?>"
                  type="text"
                />
              </div>
              <div class="field3">
                <input 
                  id="pass1" 
                  name="pass1" 
                  class="label3" 
                  placeholder="Password" 
                  type="password" 
                />
              </div>
              <div class="field4">
                <input
                  id="pass2"
                  name="pass2"
                  class="label4"
                  placeholder="Re-enter Password"
                  type="password"
                />
              </div>
              <div class="field5">
                <input
                  class="label5"
                  name="email"
                  id="email"
                  value="<?php echo $email ?>"
                  type="email"
                />
              </div>
              <div class="errors">
                <?php
                  // PRINTS OUT THE ERRORS, NEED TO DESIGN THE OUTPUT SOON
                  if (isset($errors) && !empty($errors)) {
                    echo '<p class="errorclass">The following error(s) occurred:<br />';
                    foreach ($errors as $msg) {
                        echo " - $msg<br />\n";
                    }
                    echo '</p><p class="errorclass">Please try again.</p>';
                  }
                ?>
              </div>
              <button class="button" type = "submit" form = "register_form" value = "Submit">
                <div class="sign-up">Sign up</div>
              </button>
            </div>
          </form>
          <div class="divider"></div>
          <div class="by-clicking-continue-container">
            <span>By clicking continue, you agree to our </span>
            <span class="terms-of-service">Terms of Service</span>
            <span> and </span>
            <span class="privacy-policy">Privacy Policy</span>
          </div>
          <a class="already-have-an-container" id="alreadyHaveAn">
            <span class="already-have-an">Already have an account?</span>
            <span class="span"> </span>
            <span class="sign-in">Sign In</span>
          </a>
        </div>
      </main>
    </div>

    <script>
      var alreadyHaveAn = document.getElementById("alreadyHaveAn");
      if (alreadyHaveAn) {
        alreadyHaveAn.addEventListener("click", function (e) {
          window.location.href = "./login.php";
        });
      }
      </script>
  </body>
</html>
