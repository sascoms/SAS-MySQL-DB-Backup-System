<?php
	try {
		$db = @mysql_connect($dbhost, $dbuser, $dbpass);
		if (!$db) {
			throw new Exception('could-not-connect-to-database');
		}
		$sql = "show databases";
		$res = mysql_query($sql);

		$availableDBs = array();
		while($row = mysql_fetch_array($res)) {
			//print_r($row);
			$availableDBs[] = $row['Database'];

		}

		if (!empty($_POST['db'])) {
			$backupDbName = urldecode($_POST['db']);
			if ( ($backupDbName != 'all') && (!in_array($backupDbName, $availableDBs)) ) {
				throw new Exception('selected-db-is-not-in-available-database-names');
			}
			if ($backupDbName == 'all') {
				$backupDbName = 'all_databases';
			}
			$dbBackupFilename = $dbBackupFolder.'/'.$backupDbName.'.'.date('Y_m_d_H_i_s') . '.sql';
			if ($backupDbName == 'all_databases') {
				$backupDbName = '--all-databases';
			}

			$dumpWithData = trim($_POST['dump-type']);
        	$dumpDropTables = trim($_POST['drop-table-command']);
        	$dumpCompress = trim($_POST['compress']);

        	$addDumpCommand = array();
        	//$addDumpCommand[] = '--opt';
        	switch ($dumpWithData) {
        		case 1: //all data and structure
			        $addDumpCommand[] = '--opt';
        			break;
        		case 2:// -d, --no-data       No row information.
        			$addDumpCommand[] = '--no-data';
        			break;
        		case 3://no structure but only data
        			$addDumpCommand[] = '--no-create-info';
        			break;
        	}
        	if ($dumpDropTables == '1') {
				$addDumpCommand[] = '--add-drop-table=true';
        	} else {
        		$addDumpCommand[] = '--add-drop-table=false';
        	}


	        //echo $dbBackupCommand;
	        if ($dumpCompress == 'gzip') {

				$dbBackupCommand = sprintf("
		            %s %s --user %s --password=%s %s | %s -c > %s.gz",
		            escapeshellcmd($mysqldumpPath),
		            escapeshellcmd(implode(' ', $addDumpCommand)),
		            escapeshellcmd($dbuser),
		            escapeshellcmd($dbpass),
		            escapeshellcmd($backupDbName),
		            escapeshellcmd($gzipPath),
		            escapeshellcmd($dbBackupFilename)
		        );

		        //echo $dbBackupCommand;
		        exec($dbBackupCommand);
	        	//exec('gzip '.$dbBackupFilename);
	        } else if ($dumpCompress == 'zip') {

				$dbBackupCommand = sprintf("
		            %s %s --user %s --password=%s %s >> %s",
		            escapeshellcmd($mysqldumpPath),
		            escapeshellcmd(implode(' ', $addDumpCommand)),
		            escapeshellcmd($dbuser),
		            escapeshellcmd($dbpass),
		            escapeshellcmd($backupDbName),
		            escapeshellcmd($dbBackupFilename)
		        );

		        exec($dbBackupCommand);
		        exec($zipPath.' -m '.$dbBackupFilename .'.zip '.$dbBackupFilename);
		        //unlink($dbBackupFilename);//zip -m ile zip lenen dosyayi siliyoruz. zip in icine tasiyor..
	        } else {

				$dbBackupCommand = sprintf("
		            %s %s --user %s --password=%s %s >> %s",
		            escapeshellcmd($mysqldumpPath),
		            escapeshellcmd(implode(' ', $addDumpCommand)),
		            escapeshellcmd($dbuser),
		            escapeshellcmd($dbpass),
		            escapeshellcmd($backupDbName),
		            escapeshellcmd($dbBackupFilename)
		        );

		        //echo $dbBackupCommand;
		        exec($dbBackupCommand);
	        }

		}


		//get backup files
    	$iterator = new DirectoryIterator($dbBackupFolder);
    	$i = 1;
	    foreach ($iterator as $file) {
            if (true === $file->isFile()) {
            	if (!$file->isDot() && !$file->isDir() && ($file->getFilename() != 'index.htm')) {
            		$backupFiles[$i]['name'] = $file->getFilename();
            		$backupFiles[$i]['size'] = $file->getsize();
            		$backupFiles[$i]['modified'] = $file->getMTime();
        			$i++;
            	}
            }
	    }
	    if (!empty($backupFiles)) {
	    	sort($backupFiles);
	    }




	} catch (Exception $e) {
		$msg = $e->getMessage();
		$msgClass = 'error';
	}
	//print_r($_SESSION);
?>

<h2>Backup Databases</h2>


<?php if (!empty($msg)) { ?>
	<div class="<?php echo $msgClass;?>"><?php echo $msg;?></div>
<?php } ?>

<?php if (!empty($_SESSION['backups']['msg'])) { ?>
	<div class="<?php echo $_SESSION['backups']['msgType'];?>"><?php echo $_SESSION['backups']['msg'];?></div>
<?php
		$_SESSION['backups']['msg'] = '';
		$_SESSION['backups']['msgType'] = '';
	}
?>

<div align="right" class="backupOptions-container">
	<form method="post" action="" id="backupFormAll">
		<div id="customize_All" style="display:none;" class="backupOptions-all">

			<label for="drop-table-commandAll">
				<input type="checkbox" name="drop-table-command" id="drop-table-commandAll" value="1" checked="true" />include drop-table commands
			</label>
			<br />
			<label for="compress_noneAll"><input type="radio" name="compress" id="compress_noneAll" value="" checked="checked" />do not compress</label>
			<label for="compress_gzipAll"><input type="radio" name="compress" id="compress_gzipAll" value="gzip" />compress with gz</label>
			<label for="compress_zipAll"><input type="radio" name="compress" id="compress_zipAll" value="zip" />compress with zip</label>
			<br />


			<label for="dump-type-1All"><input type="radio" name="dump-type" id="dump-type-1All" value="1" checked="checked" />both structure+data</label>&nbsp;&nbsp;&nbsp;
			<label for="dump-type-2All"><input type="radio" name="dump-type" id="dump-type-2All" value="2" />only structure</label>&nbsp;&nbsp;&nbsp;
			<label for="dump-type-3All"><input type="radio" name="dump-type" id="dump-type-3All" value="3" />only data</label>&nbsp;&nbsp;&nbsp;


		</div>

		<a href="javascript:void(0);" onclick="$('#customize_All').toggle()" class="small">customize backup</a>
		<input type="hidden" name="db" id="db" value="all" />
		<a href="javascript:void(0);" id="createBackup" class="small button blue" onclick="$('#backupFormAll').submit();" >back up all databases</a>

	</form>
</div>
<table width="100%" class="lists">
<tr>
	<th>Database name</th>
	<th>Options</th>
	<th align="center">Operations</th>
</tr>
<?php if (is_array($availableDBs)) { ?>
<?php
	$i=0;
	foreach ($availableDBs as $dbname) {
?>
<tr class="<?php echo rowColor($i);?>">
	<td width="30%" valign="top"><?php echo $dbname;?></td>
	<td>
		<form method="post" action="" id="backupForm<?php echo $i?>">
			<div id="backupOptions<?php echo $i?>">

				<label for="drop-table-command<?php echo $i?>">
					<input type="checkbox" name="drop-table-command" id="drop-table-command<?php echo $i?>" value="1" checked="true" />include drop-table commands
				</label>
				<br />

				<label for="dump-type-1<?php echo $i?>"><input type="radio" name="dump-type" id="dump-type-1<?php echo $i?>" value="1" checked="checked" />both structure+data</label>&nbsp;&nbsp;&nbsp;
				<label for="dump-type-2<?php echo $i?>"><input type="radio" name="dump-type" id="dump-type-2<?php echo $i?>" value="2" />only structure</label>&nbsp;&nbsp;&nbsp;
				<label for="dump-type-3<?php echo $i?>"><input type="radio" name="dump-type" id="dump-type-3<?php echo $i?>" value="3" />only data</label>&nbsp;&nbsp;&nbsp;
				<br />

				<label for="compress_none<?php echo $i?>"><input type="radio" name="compress" id="compress_none<?php echo $i?>" value="" checked="checked" />do not compress</label>
				<label for="compress_gzip<?php echo $i?>"><input type="radio" name="compress" id="compress_gzip<?php echo $i?>" value="gzip" />compress with gz</label>
				<label for="compress_zip<?php echo $i?>"><input type="radio" name="compress" id="compress_zip<?php echo $i?>" value="zip" />compress with zip</label>
				<br />

				<input type="hidden" name="db" id="db" value="<?php echo $dbname;?>" />

			</div>
		</form>

	</td>

	<td width="125" align="center">
		<a href="javascript:void(0);" id="createBackup" class="small button blue" onclick="$('#backupForm<?php echo $i?>').submit();" >back up db</a>
	</td>
</tr>
<?php
	$i++;
	}
?>
<?php } ?>

</table>

<br />
<br />

<h2>Backup Files</h2>
<table width="100%" class="lists">
<tr>
	<th>File name</th>
	<th>Database name</th>
	<th>backup date</th>
	<th>backup size</th>
	<th align="center">Operations</th>
</tr>
<tbody>
<?php
	if (is_array($backupFiles)) {
		$i=0;
		foreach ($backupFiles as $backupFile) {
			$backupDetails = explode('.', $backupFile['name']);
			$backupDatetime = explode('_', $backupDetails[1]);

?>
<tr class="<?php echo rowColor($i);?>">
	<td><?php echo $backupFile['name'];?></td>
	<td><?php echo $backupDetails[0];?></td>
	<td>
		<?php //echo date('Y-m-d H:i:s', $backupFile['modified']);?>
		<?php echo $backupDatetime[0].'-'.$backupDatetime[1].'-'.$backupDatetime[2].' '.$backupDatetime[3].':'.$backupDatetime[4].':'.$backupDatetime[5];?>
	</td>
	<td align="right"><?php echo number_format(($backupFile['size']/1024), 2);?> kb</td>
	<td align="center">

		<a href="?job=download&db=<?php echo $backupFile['name'];?>" class="small button green">download</a>
		<a href="?job=restore&db=<?php echo $backupFile['name'];?>" class="small button yellow restoreLinks">restore</a>
		<a href="?job=delete&db=<?php echo $backupFile['name'];?>" class="small button red deleteLinks">delete</a>
	</td>
</tr>
<?php
		$i++;
		}
	}
?>
</tbody>
</table>


<script type="text/javascript">
	$('.deleteLinks').click(function(){
		return confirm('This backup file will be deleted. Are you sure?');
	});

	$('.restoreLinks').click(function(){
		return confirm('This backup file will be restored. Are you sure?');
	});


	$(document).ready(function(){
		$(".lists tr").mouseover(function() {
			$(this).addClass("hover");
		}).mouseout(function() {
			$(this).removeClass("hover");
		});
	});

</script>
