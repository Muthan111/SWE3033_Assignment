<?php

/*  
    Description: This page handles database connection. WILL BE USED EXTENSIVELY. DO NOT EDIT UNLESS YOU KNOW WHAT YOU'RE DOING.

    CHANGELOG:
    1. Initial version created (04/06/2024)
    2. Updated database name (08/06/2024)
    3. Moved to root folder (11/06/2024)

    TO DO:
    
    Created on 04/06/2024 by Sean
*/

// Set the database access information as constants:
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_NAME', 'managementtooldb');

// Make the connection:
$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

// Set the encoding...
mysqli_set_charset($dbc, 'utf8');

?>