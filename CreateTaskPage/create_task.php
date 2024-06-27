<?php

/*  
    Description: This page processes the create task requests and will include the create task page. If the user is not an admin of the project, an error will appear.

    CHANGELOG:
    1. 	Initial version created (26/06/2024)

    TO DO:
    
    Created on 04/06/2024 by Sean
*/

session_start();

if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    
    redirect_user('../LoginPage/login.php');
} else{
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}

if(!empty($_GET['id'])){
    include ('../PHP/project_functions.php');
    include ('../PHP/mysqli_connect.php');
    include ('../PHP/tasks_functions.php');
    $current_project_id = $_GET['id'];
    $errors = validate_project_id($dbc, $current_project_id);


    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['projectCode'])) {
        $projectCode = $_POST['projectCode'];
        $already_joined = join_project($dbc, $projectCode, $user_id);

        if (!$already_joined) {
            $redirect_url = '../DisplayProjectPage/display_project.php?id='. $projectCode;
            echo "<script>window.open('$redirect_url', '_blank');</script>";
            exit();
        } else {
            $error_message = "You have already joined this project"; 
        }
    }


    if(empty($errors)){

        $q = "SELECT isadmin FROM userproject WHERE projectID = '$current_project_id' AND userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if(mysqli_num_rows($r) > 0){
            $check_admin = mysqli_fetch_array($r)['isadmin'];
        } else{
            $check_admin = -1; // USER NOT ALLOWED IN PROJECT
        }

        if($check_admin != 1){
            $errors[]="YOU DO NOT HAVE ACCESS TO THIS PAGE!";
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task-submit'])){

            $errors = create_task($dbc, $current_project_id);

        }

    }

    if(!empty($errors)){
        foreach($errors as $msg){
            echo " - $msg<br />\n";
        }
    } else{

        include('create_task_html.php');
    }

} else{

    include('../PHP/redirect_function.php');
    redirect_user('../HomePage/Homepage.php');

}

?>