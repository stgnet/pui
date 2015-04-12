<?php

require_once 'src/element.php';

class pui
{
	public function __construct()
	{
	}

	public static function __callStatic($class, $args)
	{
		if (!class_exists($class, FALSE)) {
			$file='vendor/stgnet/pui/src/'.strtolower($class).'.php';
			if (!file_exists($file)) {
				throw new \Exception($file.' does not exist'); //."\n".`ls -R`);
			}
			require $file;
			if (!class_exists($class, FALSE)) {
				throw new \Exception('Class '.$class.' does not exist after require '.$file);
			}
		}
		$reflection = new ReflectionClass($class);
		return $reflection->newInstanceArgs($args);
	}
	public function __call($class, $args)
	{
		return self::__callStatic($class, $args);
	}
}
