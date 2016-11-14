<?php

class ulist extends Element
{
	public function __construct($class=False)
	{
		parent::__construct('ul', array('class' => $class));
	}

	public function addObject($thing)
	{
		$item = new Element('li');
		$item->add($thing);
		return $item;
	}
}
