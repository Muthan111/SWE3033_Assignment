<?php

/*  
    Description: This page includes the redirect_user function which is used in mutlipe files.

    CHANGELOG:
    1. Initial version created (06/06/2024)
	2. Moved to root folder (11/06/2024)

    TO DO:
    
    Created on 06/06/2024 by Sean
*/

// Redirects users to the intended page name based on the input String
function redirect_user ($page) {

	// Start defining the URL...
	// URL is http:// plus the host name plus the current directory:
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	// Remove any trailing slashes:
	$url = rtrim($url, '/\\');
	
	// Add the page:
	$url .= '/' . $page;
	
	// Redirect the user:
	header("Location: $url");
	exit(); // Quit the script.

} // End of redirect_user() function.

?>