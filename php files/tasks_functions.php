<?php

/*  
    Description: This page includes all functions related to tasks.
    create_task() function creates a task.
    update_task_status() function updates the status of a task.
    get_task_list() function returns an associated array which contains a list of tasks that matches the project_id
    
    CHANGELOG:
    1. Initial version created (05/06/2024)
    2. Renamed $project_id to $user_project_id. Updated SQL statements to reflect database design. validate_project_id () function was copied to project_functions.php.
    Fixed a bug for $task_title in create_task(), it was calling a $_POST variable instead of the parameter. Added a return error statement for update_task_status().
    Include a dependency to redirect_function.php. (06/06/2024)
    3. Set max length for title, added task_description input to create_task(), change status verification code, partially updated SQL statements. (08/06/2024)
    4. Added validate_task_id and return_task_status functions, added validation process for task due dates and task id (13/06/2024)
    5. Updated SQL statements to reflect new database structure, renamed variables to their respective POST submission. Renamed $user_project_id to $project_id.(14/06/2024)
    6. Added a section to the task id generator to make sure that the generated id is not the same as the ids found in the task table. (14/06/2024)

    TO DO:
    1. Update SQL statements once database is completed
    2. Update HTML code once web pages are completed
    
    Created on 05/06/2024 by Sean
*/

// Dependencies
include ('redirect_function.php');
include ('project_functions.php');

function create_task($dbc, $project_id) {

    $errors = validate_project_id($dbc, $project_id); // Initialize error array and check if project ID is valid

    // Validate the task title
	if (empty($_POST['task-title'])){

		$errors[] = 'You forgot to enter a title for your task';

	} elseif(strlen($_POST['task-title']) > 30){

        $errors[] = 'The title entered is too long';

    } else{

		$task_title = mysqli_real_escape_string($dbc, trim($_POST['task-title']));

	}

    // Validate the task description
	if (empty($_POST['task-description'])){

		$errors[] = 'You forgot to enter a title for your task';

	} elseif(strlen($_POST['task-description']) > 50){

        $errors[] = 'The title entered is too long';

    } else{

		$task_description = mysqli_real_escape_string($dbc, trim($_POST['task-description']));

	}

    // Validate or generate task id
    if  (empty($_POST['task-id'])){

        $id_used = true;

        while($id_used){

            $task_id = substr(sha1(time()), 0, 4);
            $task_id = mysqli_real_escape_string($dbc, $task_id);

            $q = "SELECT projectID FROM project WHERE projectID = '$project_id'";
            $r = @mysqli_query($dbc, $q);

            if (mysqli_num_rows($r) == 0){

                $id_used = false;

            } else{

                sleep(1);

            }
        }

    } elseif (strlen($_POST['task-id']) > 4){

        $errors[] = 'Task ID is too long';

    } elseif (strlen($_POST['task-id']) < 2){

        $errors[] = 'Task ID is too short';
        
    } else{

        $task_id = mysqli_real_escape_string($dbc, $_POST['task-id']);

    }

    // Validate due date
    if (empty($_POST['task-due-date'])){

        $errors[] = "No specified due date!";

    } elseif($_POST['task-due-date'] < date("Y-m-d")){

        $errors[] = "Selected due date has already elapsed! Please select another date";
        
    } else{

        $due_date = mysqli_real_escape_string($dbc, trim($_POST['task-due-date']));

    }

    if(empty($errors)){

        $project_id = mysqli_real_escape_string($dbc, $project_id);

        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "INSERT INTO task (taskID, projectID, taskName, description, dueDate) VALUES ('$task_id', '$project_id', '$task_title', '$task_description', '$due_date')";		
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

    $errors = validate_project_id($dbc, $project_id); // Initialize error array and check if user project ID is valid

    $errors += validate_task_id ($dbc, $task_id); // WILL THIS WORK???

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

        $project_id = mysqli_real_escape_string($dbc, $project_id);

        // Make the query
        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        $q = "UPDATE task SET status = '$status' WHERE projectID = '$project_id' AND taskID = '$task_id'";		
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

        $project_id = mysqli_real_escape_string($dbc, $project_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Construct the SQL query
        $q = "SELECT * FROM task where projectID = '$project_id'";
        $r = @mysqli_query($dbc, $q);

        // Returns an associated array of all matching tasks
        return mysqli_fetch_assoc($r);

    } else{
        
        return $errors;

    }

}

function return_task_status($dbc, $task_id){

    $errors = validate_task_id($dbc, $task_id);

    if (empty($errors)){

        $q = "SELECT status from task WHERE taskID = '$task_id'";
        $r = @mysqli_query($dbc, $q);

        /*
        status code 1 = "Ongoing"
        status code 2 = "Completed"
        status code 3 = "Unassigned"
        */

        switch($r){
            case 1:
                return "Ongoing";
            case 2:
                return "Completed";
            case 3:
                return "Unassigned";
            default:
                $errors[] = "Invalid status code in the database! Please alert admin!";
                return $errors;
        }
    } else{

        return $errors;

    }

}

function validate_task_id ($dbc, $task_id){

    $errors = array();

     // Validate task ID
     if (empty($task_id)) {

        $errors[] = "Task ID has not been properly initialised";

    } else{

		$task_id = mysqli_real_escape_string($dbc, $task_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if task_id is found
        $q = "SELECT taskID FROM task WHERE taskID = '$task_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[]= "Task not found!";

        }
	}

    if (!empty($errors)){

        return $errors;

    }

}


?>