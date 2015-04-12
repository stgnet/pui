<?php

class Th extends Element
{
	public function __construct($text)
	{
		parent::__construct('th', array('text'=>$text));
	}
}
