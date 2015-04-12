<?php
// generates doctype, html, head, title, meta, body tags

class Page extends Element
{
	public function __construct($title='Web Page', $kwargs=array())
	{
		//pui.element.__init__(self, 'page', **kwargs)
		$this->title = $title;
		$this->head[] = new Element('meta', array('charset' => 'utf-8'));
	}

	public function asHtml()
	{
		/*
			redefine html output for this page
			special case grabs head and tail lists
			and constructs entire page with doctype
		*/
		// get the head/tail before contents duplicated
		$page_head = $this->_get_head();
		$page_tail = $this->_get_tail();
		$page_ready = $this->_get_ready();

		// construct ready script section
		$ready_script = NULL;
		if ($page_ready)
		{
			$scripts = implode("\n", $page_ready);
			$ready_script_func = implode("\n", array(
				'$(document).ready(function(){',
				$scripts,
				'});'
			));
			$ready_script = new Element('script', array(
				'type' => 'text/javascript',
				'html' => $ready_script_func
			));
		}

		// create fake body element
		$body = new Element('body');
		$body->contents = $this->contents;
		$body->attributes = $this->attributes;

		# construct page with head and body
		$html = new Element('html');
		$head = new Element('head');
		$title = new Element('title', array('text'=>$this->title));
		$html->Add($head);
		$head->Add($title);
		$head->Add($page_head);
		$html->Add($body);
		$body->Add($page_tail);
		$body->Add($ready_script);

		return '<!DOCTYPE html>' . $html->asHtml() . "\n";
	}
}
