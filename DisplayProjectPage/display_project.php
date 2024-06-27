<?php

/*  
    Description: This page processes the display project requests and will include either the member or admin page depending on the status of the user in the project.
    If the user is not part of the project, the user will be redirected to the homepage

    CHANGELOG:
    1. 	Initial version created (25/06/2024)
    2.  Changed how the PHP processes both GET and POST requests to allow for the form submission through POST while still maintaining the GET submission of the project ID. (26/06/2024)

    TO DO:
    
    Created on 04/06/2024 by Sean
*/

session_start();

// Checks if user is logged in
if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    include('../PHP/redirect_function.php');
    redirect_user('../LoginPage/login.php');
} else{
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}

// Check if user did this through a get method
if(!empty($_GET['id'])){
    $project_id = $_GET['id'];
    include ('../PHP/project_functions.php');
    include ('../PHP/mysqli_connect.php');
    include ('../PHP/tasks_functions.php');

    list($check, $data) = return_project_details($dbc, $user_id, $project_id);

    if ($check > -1){

        $project_name = $data['projectName'];
        $project_desc = $data['projectDescription'];
        $start = $data['startDate'];
        $due = $data['dueDate'];

        if ($check == 1){
            include('admin_display_project.php');
        } else{
            include('member_display.php');
        }

    } else{

        foreach($data as $msg){

            echo " - $msg<br />\n"; // Prints out errors

        }

    }
} else{

    include('../PHP/redirect_function.php');
    redirect_user('../HomePage/Homepage.php');

}

