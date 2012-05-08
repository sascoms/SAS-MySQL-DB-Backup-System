<?php


    function rowColor($i, $classOne='odd', $classTwo='even') {
		//$bgcolor1 = "#f0f0f0";
		//$bgcolor2 = "#fdfded";
        return ( ($i % 2) == 0 ) ? $classOne : $classTwo;
    }

    /*
	function authenticate($realm) {
	    header('WWW-Authenticate: Basic realm="'.$realm.'"');
	    header('HTTP/1.0 401 Unauthorized');
	    die('You must enter a valid username and password to access this application');
	}
	*/
