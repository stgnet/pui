<?php

class Tr extends Element
{
	public function __construct($class=None)
	{
		parent::__construct('tr', array('class' => $class));
	}
}
