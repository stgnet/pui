<?php

class tdspan extends element
{
	public function __construct($span, $text=None)
	{
		parent::__construct('td', array('colspan' => $span, 'text' => $text));
	}
}
