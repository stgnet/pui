<?php

class Heading extends Element
{
	public function __construct($number, $text="")
	{
		if (!is_numeric($number) && !$text) {
			// presume single parameter and h1
			$text=$number;
			$number=1;
		}
		parent::__construct('h'.$number, array('text'=>$text));
	}
}
