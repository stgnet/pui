<?php

class col extends Element
{
	public function __construct($twelph)
	{
		parent::__construct('div', array('class' => 'col-md-'.$twelph));
	}
}
