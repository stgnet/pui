<?php

class Paragraph extends Element
{
	public function __construct($text="")
	{
		parent::__construct('p', array('text'=>$text));
	}
}
