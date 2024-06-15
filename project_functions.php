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

    TO DO:
    1. Update SQL statements once database is completed
    2. Turn user validation into a function? maybe place it in the user file
    
    Created on 06/06/2024 by Sean
*/

// Dependencies
include ('redirect_function.php');

function create_project($dbc, $creator_id){

    $errors = array();

    // Validate user (probably should turn this into a function)
	if (empty($creator_id)){

		$errors[] = "User ID is missing!";

	} else{

		$user_id = mysqli_real_escape_string($dbc, $creator_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if user_id is found
        $q = "SELECT userID from account where userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[] = "User not found!";

        }

	}

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

    } elseif(strlen($_POST['project-description']) > 25){

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

        $project_id = mysqli_real_escape_string($dbc, trim(generate_id($dbc, 4)));

    } else{

        $project_id = mysqli_real_escape_string($dbc, trim($_POST['project-id']));

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
                redirect_user("Homepage.HTML");	
            } else{
                // Public message:
                // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
                echo '<h1>System Error</h1>
                <p class="error">The task encountered an error on our server, your task was not created. We apologised for any incovenience.</p>'; 
                
                // Debugging message:
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                }
		
		} else { // If it did not run OK.
			
			// Public message:
            // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
            echo '<h1>System Error</h1>
			<p class="error">The task encountered an error on our server, your task was not created. We apologised for any incovenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // End of if ($r) IF.

    } else{

        // Returns error messages
        return $errors;

    }

}

function join_project($dbc, $project_id, $user_id){

    $errors = validate_project_id($dbc, $project_id);

    // Validate user (probably should turn this into a function)
	if (empty($user_id)){

		$errors[] = "You forgot to enter a title for your task";

	} else{

		$user_id = mysqli_real_escape_string($dbc, $user_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if user_id is found
        $q = "SELECT userID from User where userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[] = "User not found!";

        }

	}

    if (empty($errors)){

        $project_id = mysqli_real_escape_string($dbc, $project_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED (need to find out how admin is determined)
        // Lets user join a project
        $q = "INSERT INTO User_Project (userID, projectID, admin) VALUES ('$user_id', '$project_id', 0";
        $r = @mysqli_query($dbc, $q);

        if($r){

            // Redirects the user to a page, temporary placeholder for now
            redirect_user("temp");

        } else{ // If it did not run OK.

            // Public message:
            // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
            echo '<h1>System Error</h1>
			<p class="error">Your request encountered an error on our server, your request to join this project was not made. We apologised for any incovenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // End of if ($r) IF.

		mysqli_close($dbc); // Close the database connection.

		exit();

    } else{

        // Returns error messages
        return $errors;

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

    if (!empty($errors)){

        return $errors;

    }

}

function generate_id($dbc, $length_of_string) {

    $id_used = true;

        while($id_used){

            // GENERATES ID BASED ON TIMESTAMP
            // sha1 the timestamps and returns substring of specified length
            $project_id = substr(sha1(time()), 0, $length_of_string);

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
