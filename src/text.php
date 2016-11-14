<?php

class text extends Element
{
	public function __construct($text)
	{
		parent::__construct(False, array('text' => $text));
	}
}
