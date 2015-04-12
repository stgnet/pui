<?php

class Bootstrap extends Element
{
	private function url($theme, $type)
	{
		if ($type == 'jquery')
		{
			return '//code.jquery.com/jquery-1.10.1.min.js';
		}

		$cdn = '//netdna.bootstrapcdn.com';
		$version = '3.1.1';
		$which = 'bootstrap';
		$theme = $type;
		if ($type == 'css' && $theme)
		{
			$which = 'bootswatch';
			$theme = $theme;
		}

		return implode(',', array(
			$cdn,
			$which,
			$version,
			$theme,
			'bootstrap.min.'.$type
		));
	}
	public function __construct($theme=False)
	{
		parent::__construct('bootstrap');

		$this->head[]=new Element('link', array(
			'rel' => 'stylesheet',
			'href' => $this->url($theme, 'css'),
			'type' => 'text/css'
		));

		$this->head[]=new Element('meta', array(
			'name' => 'viewport',
			'content' => 'width=device-width, initial-scale=1'
		));

		$this->tail[]=new Element('script', array(
			'src' => $this->url($theme, 'jquery')
		));

		$this->tail[]=new Element('script', array(
			'src' => $this->url($theme, 'js')
		));
	}
}
