<?php
//namespace stgnet\pui;

require_once 'element.php';

class Bootstrap extends element
{
	private $theme;

	public function __construct($theme = False)
	{
		parent::__construct();  /* placeholder object, don't output an actual tag */

		if ($theme) {
			$this->theme = $theme;
		} else {
			$this->theme='default';
		}

		$this->head[]=new Element('meta', array(
			'http-equiv' => 'X-UA-Compatible',
			'content' => 'IE=edge'
		));

		$this->head[]=new Element('meta', array(
			'name' => 'viewport',
			'content' => 'width=device-width, initial-scale=1'
		));

		$this->head[]=new Element('link', array(
			'rel' => 'stylesheet',
			'href' => $this->url('css'),
			'type' => 'text/css'
		));

		$this->tail[]=new Element('script', array(
			'src' => $this->url('jquery')
		));

		$this->tail[]=new Element('script', array(
			'src' => $this->url('js')
		));
	}

	// utility function for generating bootstrap urls
	private function url($type)
	{
		if ($type == 'jquery')
		{
			return '//code.jquery.com/jquery-1.10.1.min.js';
		}

		$cdn = '//maxcdn.bootstrapcdn.com';
		$which = 'bootstrap';
		$version = '3.3.6';
		$theme = $type;
		if ($type == 'css')
		{
			$which = 'bootswatch';
			$theme = $this->theme;
		}

		return implode('/', array(
			$cdn,
			$which,
			$version,
			$theme,
			'bootstrap.min.'.$type
		));
	}
}
