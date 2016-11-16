<?php

require_once 'element.php';

class meta extends element
{
	public function __construct($kwargs)
	{
		parent::__construct();

		$this->head[] = new element('meta', $kwargs);
	}
}
