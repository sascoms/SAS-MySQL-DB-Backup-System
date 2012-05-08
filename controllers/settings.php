<?php

	$definitionsFilename = $applicationPath.'/definitions.php';

	try {

		if (!is_writable($definitionsFilename)) {
			throw new Exception('definitions file is not writable!');
		}

		if (isset($_POST['submit'])) {


			$pApplicationLabel = trim($_POST['applicationLabel']);
			$pUseHeaderFooter = trim($_POST['useHeaderFooter']);
			$pRequireLogin = trim($_POST['requireLogin']);
			$pUsername = trim($_POST['username']);
			$pPassword = trim($_POST['password']);

			$pDbBackupFolder = trim($_POST['dbBackupFolder']);

			$pDbHost = trim($_POST['dbhost']);
			$pDbUser = trim($_POST['dbuser']);
			$pDbPass = trim($_POST['dbpass']);
			$pDbPort = trim($_POST['dbport']);

			$pMysqldumpPath = trim($_POST['mysqldumpPath']);
			$pZipPath = trim($_POST['zipPath']);
			$pGzipPath = trim($_POST['gzipPath']);

			$definitionsContent = '<?php
				$applicationLabel = \''.$pApplicationLabel.'\';


				$requireLogin = '. (($pRequireLogin) ? 'true':'false') .';
				$realm = \''.$pRealm.'\';
				$username = \''.$pUsername.'\';
				$password = \''.$pPassword.'\';


				$dbhost = \''.$pDbHost.'\';
				$dbuser = \''.$pDbUser.'\';
				$dbpass = \''.$pDbPass.'\';
				$dbport = \''. (($pDbPort) ? $pDbPort:'3306') .'\';

				$dbBackupFolder = \''.$pDbBackupFolder.'\';

				$mysqldumpPath = \''.$pMysqldumpPath.'\';
				$zipPath = \''.$pZipPath.'\';
				$gzipPath = \''.$pGzipPath.'\';

				$settingsDone = \'true\';

			';

			//$useHeaderFooter = '. (($pUseHeaderFooter) ? 'true':'false') .';



			file_put_contents($definitionsFilename, $definitionsContent);

			$msg = 'Settings saved!';
			$msgClass = 'success';

		} else {
			if (!$settingsDone) {
				$msg = 'Please first take care of settings...';
				$msgClass = 'error';

			}
		}
	} catch (Exception $e) {
		$msg = $e->getMessage();
		$msgClass = 'error';
	}

	include($definitionsFilename);

?>
<h2>Settings</h2>
<?php if (!empty($msg)) { ?>
	<div class="<?php echo $msgClass;?>"><?php echo $msg;?></div>
<?php } ?>
<form id="" method="post" action="" onsubmit="return validateForm();">
<table width="100%">
<tr>
	<td width="20%" align="right">db host</td>
	<td>
		<input type="text" name="dbhost" id="dbhost" value="<?php echo $dbhost;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">db user</td>
	<td>
		<input type="text" name="dbuser" id="dbuser" value="<?php echo $dbuser;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">db pass</td>
	<td>
		<input type="text" name="dbpass" id="dbpass" value="<?php echo $dbpass;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">db port</td>
	<td>
		<input type="text" name="dbport" id="dbport" value="<?php echo $dbport;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">db backup folder</td>
	<td>
		<input type="text" name="dbBackupFolder" id="dbBackupFolder" size="75" value="<?php echo $dbBackupFolder;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">require login for this application/system</td>
	<td>
		<input type="checkbox" name="requireLogin" id="requireLogin" value="1" <?php echo ($requireLogin) ? 'checked':'';?> />
	</td>
</tr>
<tr>
	<td width="20%" align="right">username</td>
	<td>
		<input type="text" name="username" id="username" value="<?php echo $username;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">password</td>
	<td>
		<input type="text" name="password" id="password" value="<?php echo $password;?>" />
	</td>
</tr>
<?php /*
<tr>
	<td width="20%" align="right">use header/footer templates</td>
	<td>
		<input type="checkbox" name="useHeaderFooter" id="useHeaderFooter" value="1" <?php echo ($useHeaderFooter) ? 'checked':'';?> />
	</td>
</tr>
*/?>

<tr>
	<td width="20%" align="right">application label</td>
	<td>
		<input type="text" name="applicationLabel" id="applicationLabel" size="75" value="<?php echo $applicationLabel;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">mysqldump path</td>
	<td>
		<input type="text" name="mysqldumpPath" id="mysqldumpPath" size="75" value="<?php echo $mysqldumpPath;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">zip path</td>
	<td>
		<input type="text" name="zipPath" id="zipPath" size="75" value="<?php echo $zipPath;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">gzip path</td>
	<td>
		<input type="text" name="gzipPath" id="gzipPath" size="75" value="<?php echo $gzipPath;?>" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">&nbsp;</td>
	<td>
		<input type="submit" name="submit" class="submit" id="submit" value="Save" />
	</td>
</tr>
</table>
</form>

<script type="text/javascript">

	function validateForm() {
		//if ()

	}
</script>