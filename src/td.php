<?php

class td extends element
{
	public function __construct($text=None)
	{
		parent::__construct('td', array('text' => $text));
	}
}
