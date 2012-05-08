<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));
	session_start();

	$applicationPath = dirname(__FILE__);
	$controllersPath = $applicationPath.'/controllers/';
	$useHeaderFooter = true;

	require_once('definitions.php');
	require_once('common.php');

	$job = (!empty($_GET['job']) ? trim($_GET['job']) : 'home');

	require_once($controllersPath.'/login.php');

	switch ($job) {

		default:
		case 'home':
		case 'backups':
			//$includeFile = 'home.php';
			$includeFile = 'backups.php';
		break;
		case 'settings':
			$includeFile = 'settings.php';
		break;
		break;
		case 'download':
			$useHeaderFooter = false;
			$includeFile = 'download.php';
		break;
		case 'delete':
			$useHeaderFooter = false;
			$includeFile = 'delete.php';
		break;
		case 'restore':
			$useHeaderFooter = false;
			$includeFile = 'restore.php';
		break;
		case 'logout':
			$useHeaderFooter = false;
			$includeFile = 'logout.php';
		break;
		case 'login.form':
			$includeFile = 'login.form.php';
		break;

	}


	$includeFilePath = $controllersPath.$includeFile;
	if (empty($includeFilePath) || !is_file($includeFilePath)) {
		echo 'include-file-problem';
	} else {
		if ($useHeaderFooter) {
			include($controllersPath.'header.php');
		}
//		if (!$settingsDone) {
//				header('Location:?do=settings');
//		}

		include($controllersPath.'/'.$includeFile);

		if ($useHeaderFooter) {
			include($controllersPath.'footer.php');
		}
	}