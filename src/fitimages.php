<?php

require_once 'element.php';

class FitImages extends element
{
	public function __construct()
	{
		parent::__construct();

		$this->head[] = new Element('style', array('html' => 'img { height: 100%; width: 100%; object-fit: contain; }'));
	}
}
