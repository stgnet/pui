<?php
class Navbar extends Element
{
	/*
		generates doctype, html, head, title, meta, body tags
		also loads bootstrap
	*/
	public function __construct($navigation)
	{
		parent::__construct('x-navbar');
		$this->navmenu = $navigation;
	}

	private function nav_list($elements, $dropdown=False)
	{
		$ul = new Element('ul');

		if ($dropdown)
		{
			$ul->addClass('dropdown-menu');
			$dropdown_id = $dropdown->get_id();
			$ul->attr(array(
				'role'=>'menu',
				'aria_labelledby'=>$dropdown_id
			));
		}
		else
		{
			$ul->addClass('nav', 'navbar-nav');
		}

		//for item in elements:
		foreach ($elements as $item)
		{
			$li = new Element('li');
			$li->add($item);
			if (!empty($item->navmenu))
			{
				//if 'dropdown' in str(type(item)):
				//	li.add(self.nav_list(item.navmenu, item))
				//	li.addClass('dropdown')
				//else:
					$li->add($this->nav_list($item->navmenu));
			}

			$ul->add($li);
		}
		return $ul;
	}

	public function asHtml($level)
	{
		/*
			redefine html output for this object
			contents are put in header (usually brand)
			navigation table used to generate navlist
		*/
		$div_navbar_main = new Element(array('class'=>'navbar-collapse collapse'));
		$div_navbar_main->add($this->nav_list($this->navmenu));
		$div_navbar_header = new Element('div');
		$div_navbar_hamburger = new Element('button', array(
			'class'=>'navbar-toggle', 'type'=>'button',
			'data_toggle'=>'collapse', 'data_target'=>'.navbar-main'));

		$div_navbar = new Element('div', array('style'=>'navbar navbar-default'));
		$div_navbar->add(
			$div_navbar_header->addList($this->contents)->add(
				$div_navbar_hamburger->add(
					new Element('span', array('class'=>'icon-bar')),
					new Element('span', array('class'=>'icon-bar')),
					new Element('span', array('class'=>'icon-bar'))
				)
			),
			$div_navbar_main
		);

		return $div_navbar->asHtml(level);
	}
}

