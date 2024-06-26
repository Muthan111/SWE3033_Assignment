<?php
session_start();

if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    
    redirect_user('../LoginPage/login.php');
} else{
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}

if(!empty($_GET['id'])){
    include ('PHP/project_functions.php');
    include ('PHP/mysqli_connect.php');
    include ('PHP/tasks_functions.php');
    $project_id = $_GET['id'];
    $errors = validate_project_id($dbc, $project_id);

    if(empty($errors)){

        $q = "SELECT isadmin FROM userproject WHERE projectID = '$project_id' AND userID = '$user_id'";
        $r = @mysqli_query($dbc, $q);

        if(mysqli_num_rows($r) > 0){
            $check_admin = mysqli_fetch_array($r)['isadmin'];
        } else{
            $check_admin = -1; // USER NOT ALLOWED IN PROJECT
        }

        if($check_admin != 1){
            $errors[]="YOU DO NOT HAVE ACCESS TO THIS PAGE!";
        }

        if(!empty($errors)){
            foreach($errors as $msg){
                echo " - $msg<br />\n";
            }
        }

    } else{
        foreach($errors as $msg){
            echo " - $msg<br />\n";
        }
    }

} else{

    include('../PHP/redirect_function.php');
    redirect_user('../HomePage/Homepage.php');

}
?>