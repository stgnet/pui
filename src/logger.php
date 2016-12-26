<?php

class logger
{
	public function __construct($maildest, $maxage)
	{
		$this->maildest = $maildest;
		$this->maxage = $maxage;

		$this->filename = '/tmp/php-stgnet-logger-'.md5($maildest . (string)getmyuid()).'.txt';

		if (!file_exists($this->filename)) {
			return;
		}

		$stats = stat($this->filename);
		if (time() - $stats['atime'] > $maxage || $stats['size'] > 1024 * 1024) {
			$file = fopen($this->filename, 'r+');
			if (flock($file, LOCK_EX)) {
				mail($this->maildest, gethostname(), file_get_contents($this->filename));
				ftruncate($file, 0);
			}
			fclose($file);
		}
	}

	public function log($message)
	{
		$file = fopen($this->filename, 'a');
		fwrite($file, date('Y/m/d H:i:s').' '.$_SERVER['SCRIPT_NAME'].' '.$message."\n");
		fclose($file);
	}
}
