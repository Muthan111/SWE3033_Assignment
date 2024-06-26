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
    7. Created join_task function and generate_id function, fixed a bug where a user could have entered a Task ID that was already in used which could have caused an SQL error.
    Updated get_task_list function to account for admin and normal member's task differences. (19/06/2024)
    8. Fixed bugs regarding input validation and how the errors are processed as arrays. (24/06/2024)
    9. Removed dependencies as they are causing redeclared functions errors. Fixed SQL where it was searching for a User table instead of the account table. Fixed bugs. (25/06/2024)
    10. Fixed some error messages and some SQL functions. (26/06/2024)

    TO DO:
    1. Update SQL statements once database is completed
    2. Update HTML code once web pages are completed
    3. Join task function
    
    Created on 05/06/2024 by Sean
*/

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

		$errors[] = 'You forgot to enter a description for your task';

	} elseif(strlen($_POST['task-description']) > 50){

        $errors[] = 'The title entered is too long';

    } else{

		$task_description = mysqli_real_escape_string($dbc, trim($_POST['task-description']));

	}

    // Validate or generate task id
    if  (empty($_POST['task-id'])){

        $task_id = generate_task_id($dbc, 4);

    } elseif (strlen($_POST['task-id']) > 4){

        $errors[] = 'Task ID is too long';

    } elseif (strlen($_POST['task-id']) < 2){

        $errors[] = 'Task ID is too short';
        
    } else{

        $task_id = mysqli_real_escape_string($dbc, $_POST['task-id']);

        $q = "SELECT taskID FROM task WHERE taskID = '$task_id'";
        $r = @mysqli_query($dbc, $q);

        if(mysqli_num_rows($r) > 0){

            $errors[] = "Task ID is in used";

        }

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
        //Project ID needs to be created first
        $q = "INSERT INTO task (taskID, projectID, taskName, description, dueDate) VALUES ('$task_id', '$project_id', '$task_title', '$task_description', '$due_date')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.

		if ($r) { // If it ran OK.
            
            // Redirects the user to a page, temporary placeholder for now
            redirect_user("../CreateTaskPage/create_task.php?id=$project_id");	
		
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

function join_task($dbc, $project_id, $task_id, $user_id){

    $errors = array_merge(validate_project_id($dbc, $project_id), validate_task_id($dbc, $task_id)); // Initialize error array and check if task ID and project ID is valid // Will this work?

    // Validate user ID
    if(empty($user_id)){

        $errors[] = "User ID is empty";
        
    } else{

        $user_id = mysqli_real_escape_string($dbc, $user_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if user_id is found
        $q = "SELECT userID from account where userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[] = "User not found!";

        } else{

            // Checks if the user is actually part of the project
            $q = "SELECT projectID WHERE projectID = '$project_id' AND userID = '$user_id";
            $r = @mysqli_query($dbc, $q);

            if (mysqli_num_rows($r) == 0){

                $errors[] = "User not part of this project!";
    
            } else{

                // Checks if the user is already part of the task
                $q = "SELECT userProjectTaskID FROM userprojecttask WHERE projectID = '$project_id' AND userID = '$user_id' AND taskID = '$task_id'";
                $r = @mysqli_query($dbc, $q);

                if(mysqli_num_rows($r) > 0 ){

                    $errors[] = "User has already been assigned to this task!";

                }

            }

        }

    }

    if(empty($errors)){

        $q = "INSERT INTO userprojecttask (userID, projectID, taskID) VALUES ('$user_id', '$project_id', '$task_id')";
        $r = @mysqli_query($dbc, $q);

        if($r){
            redirect_user("Temp"); // Redirect user back to the project page
        } else{
            // Public message:
            // NEED TO UPDATE HTML CODE ONCE WEB PAGES ARE COMPLETED
            echo '<h1>System Error</h1>
            <p class="error">The task encountered an error on our server, you were not added to this task. We apologised for any incovenience.</p>'; 
            
            // Debugging message:
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
        }

    } else{

        return $errors;

    }

}

function update_task_status($dbc, $project_id, $task_id, $status) {

    $errors = array_merge(validate_project_id($dbc, $project_id), validate_task_id ($dbc, $task_id)); // Initialize error array and check if task ID and project ID is valid // WILL THIS WORK???

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

function return_task_list($dbc, $project_id, $user_id){
    
    $errors = validate_project_id($dbc, $project_id);

    // Validate user ID
    if(empty($user_id)){

        $errors[] = "User ID is empty";
        
    } else{

        $user_id = mysqli_real_escape_string($dbc, $user_id);

        // NEED TO UPDATE SQL ONCE DATABASE IS COMPLETED
        // Find out if user_id is found
        $q = "SELECT userID FROM account WHERE userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 0){

            $errors[] = "User not found!";

        } else{

            // Check whether the user is part of a project and if they are an admin
            $q = "SELECT isadmin FROM userproject WHERE projectID = '$project_id' AND userID = '$user_id'";
            $r = @mysqli_query($dbc, $q);

            if(mysqli_num_rows($r) < 0){

                $errors[] = "User is not part of this project";

            }

        }
    }

    if(empty($errors)){

        if(mysqli_fetch_array($r)['isadmin'] == 1){
            $q = "SELECT * FROM task WHERE projectID = '$project_id'"; // IF the user is an admin
        } else{
            $q = "SELECT * FROM task WHERE taskID IN (SELECT taskID FROM userprojecttask WHERE projectID = '$project_id' AND userID = '$user_id')"; // IF the user is assigned
        }

        $r = @mysqli_query($dbc, $q);

        return $r;

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

function generate_task_id($dbc, $length_of_string) {

    $id_used = true;

        while($id_used){

            // GENERATES ID BASED ON TIMESTAMP
            // sha1 the timestamps and returns substring of specified length
            $task_id = substr(sha1(time()), 0, $length_of_string);
            $task_id = mysqli_real_escape_string($dbc, $task_id);

            $q = "SELECT taskID FROM task WHERE taskID = '$task_id'";
            $r = @mysqli_query($dbc, $q);

            if (mysqli_num_rows($r) == 0){

                $id_used = false;

            } else{

                sleep(1);

            }
        }

    return $task_id;
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