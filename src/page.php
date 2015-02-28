<?php
// generates doctype, html, head, title, meta, body tags

class Page extends Element
{
	public function __construct($title='Web Page', $kwargs=array())
	{
		//pui.element.__init__(self, 'page', **kwargs)
		$this->title = title;
		$this->head[] = Element('meta', array('charset' => 'utf-8'));
	}

	public function asHtml(self)
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
			ready_script_func = '\n'.join([
				"$(document).ready(function(){",
				scripts,
				"});"
			])
			ready_script = pui.element(
				'script',
				type='text/javascript',
				html=ready_script_func)

		// create fake body element
		body = pui.element('body')
		body.contents = $this->contents
		body.attributes = $this->attributes

		# construct page with head and body
		html = pui.element('html').add(
			pui.element('head').add(
				pui.element('title', text=$this->title),
			).addList(page_head)
		).add(
			body.addList(page_tail).add(ready_script)
		)

		return '<!DOCTYPE html>' + html.asHtml() + '\n'
	}
}
