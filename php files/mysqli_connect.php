<?php

/*  
    Description: This page handles database connection. WILL BE USED EXTENSIVELY. DO NOT EDIT UNLESS YOU KNOW WHAT YOU'RE DOING.

    CHANGELOG:
    1. Initial version created (04/06/2024)

    TO DO:
    1. Include database name once determined
    
    Created on 04/06/2024 by Sean
*/

// Set the database access information as constants:
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
// ONCE DATABASE IS DONE, REPLACE DATABASE NAME
DEFINE ('DB_NAME', 'DATABASE NAME');

// Make the connection:
$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

// Set the encoding...
mysqli_set_charset($dbc, 'utf8');

?>