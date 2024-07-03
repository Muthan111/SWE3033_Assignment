<?php
/*  
    Description: Handles task deletion.

    CHANGELOG:
    1. 	Initial version created (03/07/2024)

    TO DO:
    1. Testing with admin page
    
    Created on 04/06/2024 by Sean
*/

session_start();

echo "<script> windows.onload = function () { alert('Page loaded');} </script>";

// Checks if user is logged in
if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    include('../PHP/redirect_function.php');
    redirect_user('../LoginPage/login.php');
} else{
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}

// Check if user did this through a get method
if(!empty($_GET['id']) && !empty($_GET['task'])){
    echo "<script> windows.onload = function () { alert('Getters received');} </script>";
    $project_id = $_GET['id'];
    $task_id = $_GET['task'];
    include ('../PHP/project_functions.php');
    include ('../PHP/mysqli_connect.php');
    include ('../PHP/tasks_functions.php');

    list($check, $data) = return_project_details($dbc, $user_id, $project_id);

    if ($check > -1){

        if ($check == 1){
            $error = delete_task($dbc, $task_id);
            if(!empty($error)){
                echo '<p class="errorclass">The following error(s) occurred:<br />';
                foreach($error as $msg){
                    echo " - $msg<br />\n";
                }
                echo '</p><p class="errorclass">Please try again.</p>';
            } else{
                echo "<script>
                    window.onload = function () { 
                        alert('Task deleted succesfully'); 
                        window.location.href = 'display_project.php?id=$project_id';
                    };
                </script>";
            }
        } else{
            echo "<p>You do not have access to this page</p>";
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

