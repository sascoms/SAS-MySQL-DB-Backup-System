<?php
	if ($requireLogin === true) {
		if ($_SESSION['dbbackup_auth'] !== true) {

			if (isset($_POST['loginSubmit'])) {
				$authUser = trim($_POST['login_user']);
				$authPass = trim($_POST['login_pass']);

				if ( ($authUser == $username) && ($authPass == $password) ) {
					$_SESSION['dbbackup_auth'] = true;
				} else {
					$job = 'login.form';
					$loginMsg = 'invalid login or password';
				}
			} else {
				if ($job != 'logout') {
					$job = 'login.form';
				}
			}
		}
	}

