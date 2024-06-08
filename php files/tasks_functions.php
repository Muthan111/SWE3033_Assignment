<?php

/*  
    Description: This page includes all functions related to tasks.
    create_task() function creates a task.
    update_task_status() function updates the status of a task.
    get_task_list() function returns an associated array which contains a list of tasks that matches the project_id
    validate_user_project_id() function validates the project_id, this is a function cause it's repeatedly used throughout this file.

    CHANGELOG:
    1. Initial version created (05/06/2024)
    2. Renamed $project_id to $user_project_id. Updated SQL statements to reflect database design. validate_project_id () function was copied to project_functions.php.
    Fixed a bug for $task_title in create_task(), it was calling a $_POST variable instead of the parameter. Added a return error statement for update_task_status().
    Include a dependency to redirect_function.php. (06/06/2024)
    3. Set max length for title, added task_description input to create_task(), change status verification code, partially updated SQL statements. (08/06/2024)

    TO DO:
    1. Update SQL statements once database is completed
    2. Update HTML code once web pages are completed
    3. Decide what status these tasks have
    4. DETERMINE HOW THE TASK_ID IS GENERATED
    5. Add a check_status function that will return the status of a task in string rather than int
    
    Created on 05/06/2024 by Sean
*/

// Dependencies
include ('redirect_function.php');

function create_task($dbc, $user_project_id, $task_title, $task_description) {

    $errors = validate_user_project_id($dbc, $user_project_id); // Initialize error array and check if project ID is valid

    // Validate the task title
	if (empty($task_title)){

		$errors[] = 'You forgot to enter a title for your task';

	} elseif(strlen($task_title) > 30){

        $errors[] = 'The title entered is too long';

    } else{

		$task_title = mysqli_real_escape_string($dbc, trim($task_title));

	}

    // Validate the task description
	if (empty($task_description)){

		$errors[] = 'You forgot to enter a title for your task';

	} elseif(strlen($task_title) > 50){

        $errors[] = 'The title entered is too long';

    } else{

		$task_description = mysqli_real_escape_string($dbc, trim($task_title));

	}

    if(empty($errors)){

        $user_project_id = mysqli_real_escape_string($dbc, $user_project_id);

        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "INSERT INTO task (userprojectID, taskName, description) VALUES ('$user_project_id', '$task_title', '$task_description')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.

		if ($r) { // If it ran OK.
            
            // Redirects the user to a page, temporary placeholder for now
            redirect_user("temp");	
		
		} else { // If it did not run OK.
			
			// Public message:
            // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
            echo '<h1>System Error</h1>
			<p class="error">The task encountered an error on our server, your task was not created. We apologised for any incovenience.</p>'; 
			
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

function update_task_status($dbc, $user_project_id, $task_id, $status) {

    $errors = validate_user_project_id($dbc, $user_project_id); // Initialize error array and check if user project ID is valid

    // Validate task ID
    if (empty($task_id)) {

        $errors[] = "Task ID has not been properly initialised";

    } else{

		$task_id = mysqli_real_escape_string($dbc, $task_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if task_id is found
        $q = "SELECT taskID FROM task WHERE taskID = '$task_id' AND userprojectID = '$user_project_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[]= "Task not found!";

        }
	}

    // Validate status
    if (empty($status)) {

        $errors[] = "Status has not been properly initialised";

    } else{

        // Depending on the status code, the task will be assigned one of the following statuses
        /*
        status code 1 = "Ongoing"
        status code 2 = "Completed"
        status code 3 = "Unassigned"
        */
        if ($status > 3 && $status < 1){

            $errors[] = "Incorrect status code";

        }

    }

    if (empty($errors)){

        $user_project_id = mysqli_real_escape_string($dbc, $user_project_id);

        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "UPDATE task SET status = '$status' WHERE userprojectID = '$user_project_id' AND taskID = '$task_id'";		
		$r = @mysqli_query ($dbc, $q); // Run the query.

		if ($r) { // If it ran OK.
            
            // Redirects the user to a page, temporary placeholder for now
            redirect_user("temp");	
		
		} else { // If it did not run OK.
			
			// Public message:
            // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
            echo '<h1>System Error</h1>
			<p class="error">The task encountered an error on our server, your task was not updated. We apologised for any incovenience.</p>'; 
			
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

function get_task_list($dbc, $user_project_id){
    
    $errors = validate_user_project_id($dbc, $user_project_id);

    if(empty($errors)){

        $user_project_id = mysqli_real_escape_string($dbc, $user_project_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Construct the SQL query
        $q = "SELECT * FROM task where userprojectID = '$user_project_id'";
        $r = @mysqli_query($dbc, $q);

        // Returns an associated array of all matching tasks
        return mysqli_fetch_assoc($r);

    } else{
        
        return $errors;

    }

}

function validate_user_project_id ($dbc, $user_project_id){

    $errors = array(); // Initialize error array.

    // Validate project ID
    if (empty($user_project_id)) {

        $errors[] = "userprojectID has not been properly initialised";

    } else{

		$user_project_id = mysqli_real_escape_string($dbc, $user_project_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if project_id is found
        $q = "SELECT userprojectID FROM User_Project WHERE userprojectID = '$user_project_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[]= "userprojectID not found!";

        }
	}

    if (!empty($errors)){

        return $errors;

    }

}


?>