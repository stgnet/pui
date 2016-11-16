<?php

require_once 'element.php';

class favicon extends element
{
	public function __construct($url)
	{
		parent::__construct();

		$this->head[] = new element('link', array(
			'rel' => 'icon',
			'href' => $url));
	}
}
