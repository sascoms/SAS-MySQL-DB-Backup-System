<?php
	try {

		if (!empty($_GET['db'])) {

			//get backup files
	    	$iterator = new DirectoryIterator($dbBackupFolder);
		    foreach ($iterator as $file) {
	            if (true === $file->isFile()) {
	            	if (!$file->isDot() && !$file->isDir()) {
	            		$backupFiles[] = $file->getFilename();
	            	}
	            }
		    }

			$backupFile = urldecode($_GET['db']);
			if (!in_array($backupFile, $backupFiles)) {
				throw new Exception('invalid-backup-file-selected');
			}
			$dbBackupFilename = $dbBackupFolder.'/'.$backupFile;
			if (!is_file($dbBackupFilename)) {
				throw new Exception('selected-backup-file-does-not-exist');
			}
			$backupFileParts = explode('.', $backupFile);
			$backupDbName = $backupFileParts[0];

			$fileParts = pathinfo($backupFile);
			//print_r($fileParts);


			if ($fileParts['extension'] == 'gz') {
				//gunzip -c ../_backups/rasmusdb_demo.2010_11_20_16_31_05.sql.gz |  mysql --user rasmus --password=rasmus rasmusdb_demo
				$dbRestoreCommand = sprintf("
		            gunzip -c %s | mysql --user %s --password=%s %s",
		            escapeshellcmd($dbBackupFilename),
		            escapeshellcmd($dbuser),
		            escapeshellcmd($dbpass),
		            escapeshellcmd($backupDbName)
		        );
			} else if ($fileParts['extension'] == 'zip') {
				$dbRestoreCommand = sprintf("
		            unzip -p %s | mysql --user %s --password=%s %s",
		            escapeshellcmd($dbBackupFilename),
		            escapeshellcmd($dbuser),
		            escapeshellcmd($dbpass),
		            escapeshellcmd($backupDbName)
		        );

			} else {
				$dbRestoreCommand = sprintf("
		            mysql --user %s --password=%s %s < %s",
		            escapeshellcmd($dbuser),
		            escapeshellcmd($dbpass),
		            escapeshellcmd($backupDbName),
		            escapeshellcmd($dbBackupFilename)
		        );
			}


	        //echo $dbRestoreCommand;
	        exec($dbRestoreCommand);

	        $_SESSION['backups']['msg'] = 'Backup file restored!';
	        $_SESSION['backups']['msgType'] = 'success';
	        header('Location:?job=backups');


		}


	} catch (Exception $e) {
		echo $msg = $e->getMessage();
		$msgClass = 'error';
	}
