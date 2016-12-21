<?php
//namespace stgnet\pui;

// base element class

//#always_break_before = ['ul', 'li', 'script', 'meta'];
//$always_break_before = array();


class element
{
	protected $tag;
	protected $attributes;
	protected $styles;
	protected $classes;
	protected $head;
	protected $tail;
	protected $ready;
	protected $contents;
	protected $raw_html;
	protected $id;

	// create object with optional tag and optional parameters by array of key=>value pair
	public function __construct($tag=False, $kwargs=array())
	{
		//echo "==> TAG=$tag instance={$this->id}\n";
		//print_r($kwargs);
		/*
			create an element tag with various properties
			used to construct a nested list of elements
			to make up the entire page
		*/

		// if tag else ''  # "%s" % id($this)
/*
		if ($tag) {
			$this->tag = $tag;
		} else {
			$this->tag = "#" . $this->id;
		}
*/
		$this->tag = $tag;

		$this->attributes = array();
		$this->styles = array();
		$this->classes = array();
		$this->head = array();      // tags that go in <head>
		$this->tail = array();      // usually <script>'s at end of page inside body
		$this->ready = array();     // document ready scripts (named for collision)
		$this->contents = array();  // sub content to this element (nested)
		$this->raw_html = '';       // html inside this tag but before contents[]

		//for key in kwargs:
		foreach ($kwargs as $key => $value) {
			if (!$value) {//not kwargs[key]:
				continue;
			}
			else if ($key == 'html') {
				if (!is_string($value)) {
					print_r($value);
					throw new Exception('html value is not a string');
				}
				$this->raw_html .= $value;

//throw new Exception('have raw_html='.$this->raw_html);
			}
			else if ($key == 'text') {
				$this->raw_html .= htmlentities($value);
			}
			else if ($key == 'class') {
				$this->classes = explode(' ', $value);
			}
			else
			{
				$this->attr(array($key => $value)); //***{key: kwargs[key]}
			}
		}
	}

	// obtain an unique id to this object for reference elsewhere
	public function get_id()
	{
		if (empty($this->id)) {
			if (empty($GLOBALS['pui_element_count'])) {
				$GLOBALS['pui_element_count'] = 0;
			}
			$this->id = ++$GLOBALS['pui_element_count'];
		}
		if (empty($this->attributes['id'])) {
			$this->attributes['id'] = $this->tag.'-'.$this->id; //uniqid('id');
		}
		return $this->attributes['id'];
	}

	// obtain the tag
	public function get_tag()
	{
		return $this->tag;
	}

	// internal function for walking content tree to get all head content
	protected function _get_head()
	{
		$head = $this->head;
		foreach ($this->contents as $subelement) {
			foreach ($subelement->_get_head() as $item) {
				$head[]=$item;
			}
		}
		return $head;
	}

	// internal function for walking content tree to get all tail content
	protected function _get_tail()
	{
		//tail = $this->tail
		foreach ($this->contents as $subelement) {
			foreach ($subelement->_get_tail() as $item) {
				//$this->tail->append($item);
				$this->tail[] = $item;
			}
		}
		return $this->tail;
	}

	// internal function for walking content tree to get all ready content
	protected function _get_ready()
	{
		//ready = $this->ready
		//for subelement in $this->contents:
		foreach ($this->contents as $subelement) {
			//for name, script in subelement._get_ready().iteritems():
			foreach ($subelement->_get_ready() as $name => $script) {
				$this->ready[$name] = $script;
			}
		}
		return $this->ready;
	}

	// internal function for generating attributes for this tag to be output as html
	private function _get_attr_str()
	{
		if ($this->styles) {
			$styles = '';
			foreach ($this->styles as $key => $value) {
				$styles .= $key.': '.$value.';';
			}
			$this->attributes = array_merge(array('style' => $styles), $this->attributes);
		}
		if ($this->classes) {
			$classes = '';
			foreach ($this->classes as $value) {
				if ($classes)
					$classes.=' ';
				$classes.=$value;
			}
			$this->attributes = array_merge(array('class' => $classes), $this->attributes);
		}
		$attrib = '';
		if ($this->attributes) foreach ($this->attributes as $key => $value) {
			if ($value===False || $value===Null)
				continue;
			if ($value===True) {
				$attrib .= ' ' + $key;
				continue;
			}
			$attrib .= ' ' . $key .'="'. (string)$value.'"';
		}
		return $attrib;
	}

	// generate this object and all content as html
	public function asHtml($level=0)
	{
		/*
			recursively walk element tree and generate
			html document, creating tags along the way
		*/
		$dont_close=array('meta');
		$dont_self_close = array('script', 'i', 'iframe', 'div', 'title', 'span');
		$always_break_before = array('ul', 'li', 'tr', 'td', 'script', 'meta', 'link');
		$indention = '  ';
		$indent = str_repeat($indention, $level);
		$html = $this->raw_html;
		$length = strlen($html);
		if ($this->tag) {
			$increment = 1;
		} else {
			$increment = 0;
		}

		if ($this->contents) foreach ($this->contents as $item) {
			$sub_html = $item->asHtml($level + $increment);
			if ($sub_html && $sub_html[0]!="\n" && $sub_html[0]!='<') {
				if ($length + strlen($sub_html) > 70) {
					$sub_html = "\n" . $indent . $indention . $sub_html;
					$length = 0;
				}
			}
			$html .= $sub_html;
			$length = strlen($html);
		}

		if (!$this->tag) {
			return $html;
		}
		$tag_attr = $this->tag . $this->_get_attr_str();

		if (!$html && in_array($this->tag, $dont_close)) {
			if (in_array($this->tag, $always_break_before)) {
				return "\n".$indent.'<'.$tag_attr.'>';
			}
			return '<'.$tag_attr.'>';
		}

		if (!$html && !in_array($this->tag, $dont_self_close)) {
			if (in_array($this->tag, $always_break_before)) {
				return "\n".$indent.'<'.$tag_attr.' />';
			}
			return '<'.$tag_attr.' />';
		}

		if ($html && $html[0]=="\n") {
			return	"\n".$indent.'<'.$tag_attr.'>'.
				$html.
				"\n".$indent.'</'.$this->tag.'>';
		}

		if (strlen($html) + strlen($indent) > 70) {
			return	"\n".$indent.'<'.$tag_attr.'>'.
				"\n".$indent.$indention.$html.
				"\n".$indent.'</'.$this->tag.'>';
		}

		if (in_array($this->tag, $always_break_before)) {
			return	"\n".$indent.'<'.$tag_attr.'>'.
				$html.
				'</'.$this->tag.'>';
		}

		return	'<'.$tag_attr.'>'.
			$html.
			'</'.$this->tag.'>';
	}

	// override to add an outer object around this object when being added
	public function outerObject()
	{
		return $this;
	}

	// override to point to an inner object for adding content to
	public function innerObject()
	{
		/* if ($this->content) return $this->contents[0]; */
		return $this;
	}

	// override to manipulate content objects added to this object
	public function addObject($thing)
	{
		return $thing;
	}

	// override to manipulate handling of arrays
	public function addArray($things)
	{
		// by default, flatten array invisibly
		return $this->addContent($things);
	}

	// semi-internal method to add array of things to content
	public function addContent($things)
	{
		$inner = $this->innerObject();

		//for thing in things:
		foreach ($things as $thing) {
			if (!$thing) {
				continue;
			}
			if (is_array($thing)) {
				$inner->addArray($thing);
				continue;
			}

			if ($thing instanceof element) {
				$inner->contents[] = $inner->addObject($thing->outerObject());
				continue;
			}

			// presume $thing is html string or can be converted to string of html
			$newthing = new Element(Null, array('html' => (string)$thing));
			$inner->contents[] = $inner->addObject($newthing);
		}
		return $this;
	}

	// convenience function for functionally adding much content
	public function add()
	{
		return $this->addContent(func_get_args());
	}

	// by default strings are added as html, this adds as text
	public function addText($text)
	{
		// add text as separate content element rather than append to raw_html so as to keep add() order
		$this->innerObject()->contents[] = new Element(False, array('text' => $text));
	}

	// set multiple attributes by key=>value pair
	public function attr($kwargs)
	{
		foreach ($kwargs as $key => $value) {
			//if key is '_class':
			//	raise 'not handled'
			//name = key.replace('_', '-')
			$this->attributes[$key] = $value;
		}
		return $this;
	}

	// set multiple styles by key=>value pair
	public function style($kwargs)
	{
		foreach ($kwargs as $key => $value) {
			//name = key.replace('_', '-')
			$this->styles[$key] = $value;
		}
		return $this;
	}

	// add a class name to this object
	public function addClass()
	{
		//for name in args:
		foreach (func_get_args() as $class)
		{
			if (!in_array($class, $this->classes)) {
				$this->classes[]=$class;
			}
		}
		return $this;
	}

	// add a ready script to page
	public function addReadyScript($kwargs)
	{
		foreach ($kwargs as $key => $value) {
			$this->ready[$key] = $value;
		}
		return $this;
	}

	/*
	 * the remainder of this class is public methods
	 * used to modify objects using html5 techniques
	 * or in bootstrap specific ways
	*/

	public function center()
	{
		$this->addClass('pagination-centered');
		return $this;
	}

	public function right()
	{
		$this->addClass('pull-right');
		return $this;
	}

	public function left()
	{
		$this->addClass('pull-left');
		return $this;
	}

	public function border($width=1, $color='#888', $style='solid')
	{
		$this->style(array('border' => $width . 'px ' . $style .' '. $color));
		return $this;
	}

	public function margin($value)
	{
		$this->style(array('margin' => $value));
		return $this;
	}

	public function marginBottom($value)
	{
		$this->style(array('margin_bottom' => $value));
		return $this;
	}

	public function marginLeft($value)
	{
		$this->style(array('margin_left' => $value));
		return $this;
	}

	public function marginRight($value)
	{
		$this->style(array('margin_right' => $value));
		return $this;
	}

	public function marginTop($value)
	{
		$this->style(array('margin_top' => $value));
		return $this;
	}

	public function background($color)
	{
		$this->style(array('background' => $color));
		return $this;
	}
}
