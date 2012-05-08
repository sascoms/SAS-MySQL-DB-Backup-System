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

			header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($backupFile));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($dbBackupFilename));
		    ob_clean();
		    flush();
		    readfile($dbBackupFilename);
		    exit;

		}


	} catch (Exception $e) {
		$msg = $e->getMessage();
		$msgClass = 'error';
	}