<?php

class Td extends Element
{
	public function __construct($text)
	{
		parent::__construct('td', array('text'=>$text));
	}
}
