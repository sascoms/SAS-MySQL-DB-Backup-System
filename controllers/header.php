<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta name="keywords" content="<?php echo $applicationLabel;?> - Complete database backup solution with restore feature" />
	<meta name="description" content="database backup, restore, mysqldump, mysql, mysql import, mysql db backup" />
	<title><?php echo $applicationLabel;?> - Complete database backup solution with restore feature</title>

	<meta http-equiv="Expires" content="Tue, 01 Jan 2000 12:12:12 GMT" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta name="Copyright" content="SHATO Networks | http://www.shato.net" />
	<meta name="Author" content="shato networks | http://www.shato.net" />

	<link rel="stylesheet" href="css/style.css" type="text/css" />

	<script src="js/jquery-1.4.2.min.js" type="text/javascript" language="javascript"></script>


</head>

<body>

	<div id="container">
		<table width="100%">
		<tr>
			<td class="header">
				<div class="top-links">
					  <a href="?job=home">Home</a>
					| <a href="?job=backups">Backups</a>
					| <a href="?job=settings">Settings</a>

					<?php if ($_SESSION['dbbackup_auth'] === true) { ?>
					| <a href="?job=logout">Logout</a>
					<?php } ?>
				</div>

				<h1>
					<a href="?job=home"><?php echo $applicationLabel;?></a>
				</h1>

			</td>
		</tr>


		<tr><td>&nbsp;</td> </tr>

		<tr>
			<td class="content">
