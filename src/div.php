<?php

class Div extends Element
{
	public function __construct($class=None)
	{
		parent::__construct('div', array('class' => $class));
	}
}
