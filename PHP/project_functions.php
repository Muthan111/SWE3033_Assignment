<?php

/*  
    Description: This page includes all functions related to project.
    create_project() function creates a project.
    join_project() function allows a user to join a project by inserting into the User_Project table.
    validate_project_id() function validates the project_id.

    CHANGELOG:
    1. Initial version created (06/06/2024)
    2. Partially updated SQL statements, updated input length based on database constraints (08/06/2024)
    3. Completed create_project function, added a function to create an id of variable length (12/06/2024)
    4. Added validation for dates (13/06/2024)
    5. Updated generate ID function to account for possible repeated ID and moved project_functions.php to root folder. Fixed some SQL statement that could have
       assigned a user to a non-existing project ID. RENAMED VARIABLES AS I REALISED THAT THE POST DATA IS A GLOBAL VARIABLE THUS NO NEED TO PASS IT AS A PARAMETER.(14/06/2024)
    6. Fixed some minor SQL errors, create_project function works now (15/06/2024)
    7. Created validate_user_id function and return_project_list, fixed a bug where a user could have entered a Project ID that is already in used which could have
    caused an SQL error. (19/06/2024)
    8. Updated return_project_list so that the homepage can return all of the projects details regardless of the user's status (as long as they are part of the project). (21/06/2024)
    9. Fixed a bug where the return_project_list did not account for a situation where there was no project. (21/06/2024)
    10. Fixed bugs regarding input validation and how the errors are processed as arrays. Fixed the join_project function and the SQL. Also fixed another bug in the return_project_list
    function, where 0 is considered as empty... (24/06/2024)
    11. Added return_project_details function to return all of the details of a specific project for the display project page. (25/06/2024)

    TO DO:
    1. Testing
    
    Created on 06/06/2024 by Sean
*/

// Dependencies
include ('redirect_function.php');

function create_project($dbc, $creator_id){

    $errors = validate_user_id($dbc, $creator_id);

    // Validate the project name
	if (empty($_POST['project-title'])){

		$errors[] = 'You forgot to enter a name for your project';

	} elseif(strlen($_POST['project-title']) > 20){ // Arbitarily set as 100 for now

        $errors[] = "Your project name is too long";

    } else{

		$project_name = mysqli_real_escape_string($dbc, trim($_POST['project-title']));

	}

    // Validate project description
    if (empty($_POST['project-description'])){

        $errors[] = "You forgot to enter a description for your project";

    } elseif(strlen($_POST['project-description']) > 50){

        $errors[] = "Your project description is too long";

    }
    else{

        $project_description = mysqli_real_escape_string($dbc, trim($_POST['project-description']));

    }

    // Validate dates? If dates are suppose to be included here
    if (empty($_POST['project-start-date']) || empty($_POST['project-due-date'])){

        $errors[] = "One or both dates are empty!";

    } elseif ($_POST['project-due-date'] < date("Y-m-d")){

        $errors[] = "The due date has already elapsed! Please select another date!";

    } elseif ($_POST['project-start-date'] > $_POST['project-due-date']){

        $errors[] = "The due date is before the start date! Please reselect your dates!";
        
    } else{

        $start_date = mysqli_real_escape_string($dbc, trim($_POST['project-start-date']));
        $due_date = mysqli_real_escape_string($dbc, trim($_POST['project-due-date']));

    }

    // Generate a project id
    if (empty($_POST['project-id'])){

        $project_id = generate_id($dbc, 4);

    } elseif (strlen($_POST['project-id']) > 4){

        $errors[] = 'Task ID is too long';

    } elseif (strlen($_POST['project-id']) < 2){

        $errors[] = 'Task ID is too short';
    
    } else{

        $project_id = mysqli_real_escape_string($dbc, trim($_POST['project-id']));

        $q = "SELECT projectID FROM project WHERE projectID = '$project_id'";
        $r = @mysqli_query($dbc, $q);

        if(mysqli_num_rows($r) > 0){

            $errors[] = "Projoect ID is in used";

        }
    }

    if(empty($errors)){

        // Insert new project into database
        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "INSERT INTO project (projectID, projectName, startDate, dueDate, projectDescription) 
        VALUES ('$project_id', '$project_name', '$start_date', '$due_date', '$project_description')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.

		if ($r) { // If it ran OK.
            
            // Relate creator to new project
            $q = "INSERT INTO userproject (userID, projectID, isadmin) VALUES ('$creator_id', '$project_id', 1)";
            $r = @mysqli_query($dbc, $q); // Run the query

            if ($r){
                // Redirects the user to a page, temporary placeholder for now
                redirect_user("../HomePage/Homepage.php");	
            } else{
                // Public message:
                // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
                echo '<h1>System Error</h1>
                <p class="error">The project encountered an error on our server, your project was not created. We apologised for any incovenience.</p>'; 
                
                // Debugging message:
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                }
		
		} else { // If it did not run OK.
			
			// Public message:
            // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
            echo '<h1>System Error</h1>
			<p class="error">The project encountered an error on our server, your project was not created. We apologised for any incovenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // End of if ($r) IF.

    } else{

        // Returns error messages
        return $errors;

    }

}

function join_project($dbc, $project_id, $user_id){
    $q = "SELECT projectID FROM userproject WHERE projectID = '$project_id' AND userID = '$user_id'";
    $r = @mysqli_query($dbc, $q);

    if(mysqli_num_rows($r) > 0){
        return true; // User has already joined the project
    } else {
        // Insert user into userproject table
        $q = "INSERT INTO userproject (userID, projectID, isadmin) VALUES ('$user_id', '$project_id', 0)";
        $r = @mysqli_query($dbc, $q);
        return false; // User has not joined the project
    }
}

function validate_project_id ($dbc, $project_id){

    $errors = array(); // Initialize error array.

    // Validate project ID
    if (empty($project_id)) {

        $errors[] = "Project ID has not been properly initialised";

    } else{

		$project_id = mysqli_real_escape_string($dbc, $project_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if project_id is found
        $q = "SELECT projectID FROM project WHERE projectID = '$project_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[]= "Project not found!";

        }
	}

    return $errors;

}

function generate_id($dbc, $length_of_string) {

    $id_used = true;

        while($id_used){

            // GENERATES ID BASED ON TIMESTAMP
            // sha1 the timestamps and returns substring of specified length
            $project_id = substr(sha1(time()), 0, $length_of_string);
            $project_id = mysqli_real_escape_string($dbc, $project_id);

            $q = "SELECT projectID FROM project WHERE projectID = '$project_id'";
            $r = @mysqli_query($dbc, $q);

            if (mysqli_num_rows($r) == 0){

                $id_used = false;

            } else{

                sleep(1);

            }
        }

    return $project_id;
}

function return_project_list($dbc, $user_id, $check_admin){

    $errors = validate_user_id($dbc, $user_id); // Initialise error array and check if user id is valid

    if(empty($check_admin) && $check_admin != 0){

        $errors[] = "Function was not called with a check admin boolean!";

    } elseif($check_admin < 0 || $check_admin > 2){

        $errors[] = "Check admin boolean possess an invalid value!";

    }

    if(empty($errors)){

        if($check_admin < 2){
            // Sub-query to select the project name where the project is associated with the user id and whether that user is an admin
            $q = "SELECT projectName, projectID FROM project WHERE projectID IN (SELECT projectID FROM userproject WHERE userID = '$user_id' AND isadmin = '$check_admin')";
        } else{
            // Sub-query to select all of the projects and its details that a user is part of regardless whether the user is an admin or member
            $q = "SELECT * FROM project WHERE projectID IN (SELECT projectID FROM userproject WHERE userID = '$user_id')";
        }

        $r = @mysqli_query($dbc, $q);

        if(mysqli_num_rows($r) > 0){

            return array(1, $r); // Returns a 1 if successful and the SQL results

        } else{

            return array(2, "placholder"); // Returns a 2 if there are no results

        }

    } else{

        return array(0, $errors); // Returns a 0 if failure and the errors involved

    }

}

function return_project_details($dbc, $user_id, $project_id){

    $errors = array_merge(validate_user_id($dbc, $user_id), validate_project_id($dbc, $project_id));

    if(empty($errors)){

        $q = "SELECT isadmin FROM userproject WHERE projectID = '$project_id' AND userID = '$user_id'"; // Checks whether the user is an admin
        $r = @mysqli_query($dbc, $q);

        // Might be able to improve this to like two lines instead, need to check later
        if(mysqli_num_rows($r) > 0){
            $check_admin = mysqli_fetch_array($r)['isadmin'];
        } else{
            $check_admin = -1; // USER NOT ALLOWED IN PROJECT
            $errors[] = "YOU DO NOT HAVE ACCESS TO THIS PROJECT!";
        }

        if($check_admin < 0){
            return array($check_admin, $errors);
        } else{
            $q = "SELECT * FROM project WHERE projectID = '$project_id'"; // Returns project details in its entirety
            $r = @mysqli_query($dbc, $q);

            if($r){
                return array($check_admin, mysqli_fetch_assoc($r));
            } else{
                $errors[] = "Project not found!"; // For debugging purposes, there's already a validate function above
                return array(-1, $errors);
            }
        }
    } else{
        return array(-1, $errors);
    }

}

function validate_user_id($dbc, $user_id){

    $errors = array();

    if (empty($user_id)){

		$errors[] = "No User ID deteceted!";

	} else{

		$user_id = mysqli_real_escape_string($dbc, $user_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if user_id is found
        $q = "SELECT userID from account where userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[] = "User not found!";

        }

	}

    return $errors;

}
