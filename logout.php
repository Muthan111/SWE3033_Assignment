<?php
include('PHP/redirect_function.php');

session_start();
session_destroy();
redirect_user('/LoginPage/login.php');
?>