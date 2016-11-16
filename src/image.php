<?php

class image extends Element
{
	public function __construct($url)
	{
		parent::__construct('img', array('src' => $url));
	}
}
