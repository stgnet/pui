<?php

// <span class="glyphicon glyphicon-lock"></span>
class glyphicon extends Element
{
	public function __construct($icon)
	{
		parent::__construct('span', array('class' => 'glyphicon glyphicon-'.$icon));
	}
}
