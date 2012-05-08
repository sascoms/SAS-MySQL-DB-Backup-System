<?php

	unset($_SESSION['dbbackup_auth']);
	unset($_SESSION);

	unset($_SERVER['PHP_AUTH_USER']);
	unset($_SERVER['PHP_AUTH_PW']);

	$_SERVER['PHP_AUTH_PW'] = '';
	$_SERVER['PHP_AUTH_USER'] = '';
	$_SERVER['PHP_AUTH_USER'];

	header('Location: ?job=home');