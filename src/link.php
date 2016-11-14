<?php

class link extends Element
{
	public function __construct($url, $text="")
	{
		parent::__construct('a', array('href' => $url, 'text' => $text));
	}
}
