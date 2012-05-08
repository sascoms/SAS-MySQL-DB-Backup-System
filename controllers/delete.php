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

			unlink($dbBackupFilename);

	        $_SESSION['backups']['msg'] = 'Backup file deleted!';
	        $_SESSION['backups']['msgType'] = 'success';


		}


	} catch (Exception $e) {
        $_SESSION['backups']['msg'] = $e->getMessage();
        $_SESSION['backups']['msgType'] = 'error';
	}

	header('Location:?job=backups');
