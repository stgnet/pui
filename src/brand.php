<?php

class brand extends Element
{
	public function __construct($url, $text="")
	{
		parent::__construct('a', array('href' => $url, 'text' => $text));
		$this->addClass('navbar-brand');
		$this->add(pui::element('span'));
	}

	public function innerObject()
	{
		if ($this->contents) {
			return $this->contents[0];
		}
		return $this;
	}
}
