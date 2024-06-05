<?php

/*  
    Description: This page includes all functions related to tasks.
    create_task() function creates a task.
    update_task_status() function updates the status of a task.
    get_task_list() function returns an associated array which contains a list of tasks that matches the project_id
    validate_project_id() function validates the project_id, this is a function cause it's repeatedly used throughout this file.

    CHANGELOG:
    1. Initial version created (05/06/2024)

    TO DO:
    1. Determine max length of title
    2. Update SQL statements once database is completed
    3. Update HTML code once web pages are completed
    4. Decide what status these tasks have
    
    Created on 05/06/2024 by Sean
*/

function create_task($dbc, $project_id, $task_title) {

    $errors = validate_project_id($dbc, $project_id); // Initialize error array and check if project ID is valid

    // Validate the task title
	if (empty($_POST['task_title'])){

		$errors[] = 'You forgot to enter a title for your task';

	} elseif(strlen($_POST['task_title']) > 100){ // Arbitarily set as 100 for now

        $errors[] = 'The title entered is too long';

    } else{

		$task_title = mysqli_real_escape_string($dbc, trim($_POST['task_title']));

	}

    if(empty($errors)){

        $project_id = mysqli_real_escape_string($dbc, $project_id);

        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "INSERT INTO task (project_id, task_title, date_created) VALUES ('$project_id', '$task_title', NOW() )";		
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

function update_task_status($dbc, $project_id, $task_id, $status) {

    $errors = validate_project_id($dbc, $project_id); // Initialize error array and check if project ID is valid

    // Validate task ID
    if (empty($task_id)) {

        $errors[] = "Task ID has not been properly initialised";

    } else{

		$task_id = mysqli_real_escape_string($dbc, $task_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if task_id is found
        $q = "SELECT task_id FROM task WHERE task_id = '$task_id' AND project_id = '$project_id'";
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
        // NEED TO DECIDE WHAT STATUS THESE TASKS HAS
        switch($status){

            case 1:
                $status = "Incomplete";
                break;
            case 2:
                $status = "Not Assigned";
                break;
            case 3:
                $status = "Completed";
                break;
            case 4:
                $status = "Suspended";
                break;
            case 5:
                $status = "Paused";
                break;
            case 6:
                $status = "Waiting for other tasks to complete";
                break;
            case 7:
                $status = "Waiting for review";
                break;
            default:
                $errors = "Invalid status";

        }

    }

    if (empty($errors)){

        $project_id = mysqli_real_escape_string($dbc, $project_id);

        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "UPDATE task SET task_status = '$status' WHERE project_id = '$project_id' AND task_id = '$task_id'";		
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

function get_task_list($dbc, $project_id){
    
    $errors = validate_project_id($dbc, $project_id);

    if(empty($errors)){

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Construct the SQL query
        $q = "SELECT * FROM task where project_id = '$project_id'";
        $r = @mysqli_query($dbc, $q);

        // Returns an associated array of all matching tasks
        return mysqli_fetch_assoc($r);

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
        $q = "SELECT project_id FROM project WHERE project_id = '$project_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[]= "Project not found!";

        }
	}

    if (!empty($errors)){

        return $errors;

    }

}


?>