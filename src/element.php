<?php
// base element class

$dont_self_close = array('script', 'i', 'iframe', 'div', 'title');
$indention = '  ';
//#always_break_before = ['ul', 'li', 'script', 'meta'];
//$always_break_before = array();


class element
{
	private $tag;
	private $attributes;
	private $styles;
	private $classes;
	private $head;
	private $tail;
	private $ready;
	private $contents;
	private $navmenu;
	private $raw_html;

	public function __construct($tag=None, $kwargs=array())
	{
		/*
			create an element tag with various properties
			used to construct a nested list of elements
			to make up the entire page
		*/

		// if tag else ''  # "%s" % id($this)
		if ($tag)
			$this->tag = $tag;
		else
			throw new \Exception("tag not defined");

		$this->attributes = array();
		$this->styles = array();
		$this->classes = array();
		$this->head = array();  // tags that go in <head>
		$this->tail = array();  // usually <script>'s at end of page inside body
		$this->ready = array();  // document ready scripts (named for collision)
		$this->contents = array();  // sub content to this element (nested)
		$this->navmenu = array();   // nested navigation menus
		$this->raw_html = '';	  // html inside this tag

		//for key in kwargs:
		foreach ($kwargs as $key => $value) {
			if (!$value) {//not kwargs[key]:
				continue;
			}
			else if ($key == 'html') {
				$this->raw_html .= $value;
			}
			else if ($key == 'text') {
				$this->raw_html .= htmlentities($value);
			}
			else if (key == 'class') {
				$this->classes = explode(' ', $value);
			}
			else
			{
				$this->attr(array($key => $value)); //***{key: kwargs[key]}
			}
		}
	}

	public function get_id()
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['id'] = uniqid('id');
		}
		return $this->attributes['id'];
	}

	private function _get_head()
	{
		//foreach ($subelement in $this->contents) {
		foreach ($this->contents as $subelement) {
			//for ($item in $subelement->_get_head()) {
			foreach ($subelement->_get_head() as $item) {
				$this->head->append($item);
			}
		}
		return $this->head;
	}

	private function _get_tail()
	{
		//tail = $this->tail
		foreach ($this->contents as $subelement) {
			foreach ($subelement->_get_tail() as $item) {
				$this->tail->append($item);
			}
		}
		return $this->tail;
	}

	private function _get_ready()
	{
		//ready = $this->ready
		//for subelement in $this->contents:
		foreach ($this->contents as $subelement) {
			//for name, script in subelement._get_ready().iteritems():
			foreach ($subelement->_get_ready() as $name => $script) {
				$this->ready[$name] = $script;
			}
		}
		// also walk the navmenu
		//for subelement in $this->navmenu:
		foreach ($this->navmenu as $subelement) {
			//for name, script in subelement._get_ready().iteritems():
			foreach ($subelement->_get_ready() as $name => $script) {
				$this->ready[$name] = $script;
			}
		}
		return $this->ready;
	}

	private function _get_attr_str()
	{
		if ($this->styles) {
			//$this->attributes['style'] = ';'.join(
			//	key + ': '+$this->styles[key] for key in $this->styles)
			$this->attributes['style'] = '';
			foreach ($this->styles as $key => $value) {
				$this->attributes['style'] .= $key.': '.$value.';';
			}
		}
		if ($this->classes) {
			//$this->attributes['class'] = ' '.join(
			//	key for key in $this->classes)
			$this->attributes['class'] = '';
			foreach ($this->classes as $value) {
				if ($this->attributes['class'])
					$this->attributes['class'].=' ';
				$this->attributes['class'].=$value;
			}
		}
		$attrib = '';
		//for key in $this->attributes:
		foreach ($this->attributes as $key => $value) {
			//if $this->attributes[key] in [False, None]:
			if ($value===False || $value===None)
				continue;
			//if $this->attributes[key] is True:
			if ($value===True) {
				$attrib .= ' ' + $key;
				continue;
			}
			$attrib .= ' ' + $key .'="'. (string)$value.'"'; //'="%s"' % $this->attributes[key]
		}
		return $attrib;
	}

	public function asHtml($level=0)
	{
		/*
			recursively walk element tree and generate
			html document, creating tags along the way
		*/
		global $indention;
		$indent = $indention * $level;
		$content = $this->raw_html; // if $this->raw_html else '';
		$length = strlen($content);
		//content += ''.join(item.asHtml(level + 1) for item in $this->contents)
		//for item in $this->contents:
		foreach ($this->contents as $item) {
			$html = $item->asHtml($level + 1);
			//if not html.startswith('\n') and html.startswith('<'):
			if ($html[0]!="\n" && $html[0]!='<') {
				if ($length + strlen($html) > 70) {
					$html = '\n' + $indent + $indention + $html;
					$length = 0;
				}
			}
			$content += html;
			$length += strlen(html);
		}

		//if not $this->tag:
		if (!$this->tag) {
			return $content;
		}
		$tag_attr = $this->tag . $this->_get_attr_str();

		//if not content and $this->tag not in dont_$this_close:
		if (!$content && !in_array($this->tag, $dont_self_close)) {
			//return ''.join(['<', tag_attr, ' />'])
			return '<'.$tag_attr.' />';
		}

		//if content.startswith('\n'):
		if ($content[0]=="\n") {
			//return ''.join(['\n', indent, '<', tag_attr, '>',
			//				content,
			//				'\n', indent, '</', $this->tag, '>'])
			return	"\n".$indent.'<'.$tag_attr.'>'.
				$content.
				"\n".$indent.'</'.$this->tag.'>';
		}

		//if strlen(content) + strlen(indent) > 70:
		if (strlen($content) + strlen($indent) > 70) {
			//return ''.join(['\n', indent, '<', tag_attr, '>',
			//				'\n', indent, indention, content,
			//				'\n', indent, '</', $this->tag, '>'])
			return	"\n".$indent.'<'.$tag_attr.'>'.
				"\n".$indent.$indention.$content.
				"\n".$indent.'</'.$this->tag.'>';
		}

		//if $this->tag in always_break_before:
		if (in_array($this->tag, array('ul', 'li', 'script', 'meta'))) {
			//return ''.join(['\n', indent, '<', tag_attr, '>',
			//				content,
			//				'</', $this->tag, '>'])
			return	"\n".$indent.'<'.$tag_attr.'>'.
				$content.
				'</'.$this->tag.'>';
		}
		//return ''.join(['<', tag_attr, '>',
		//				content,
		//				'</', $this->tag, '>'])
		return	'<'.$tag_attr.'>'.
			$content.
			'</'.$this->tag.'>';
	}

	public function addList($things)
	{
		//for thing in things:
		foreach ($things as $thing) {
			if ($thing) {
				if (!($thing instanceof element)) {
					// ad as separate object to retain in supplied order
					$thing = element(None, array('html' => $thing));
				}
				$this->contents.append($thing);
			}
		}
		return $this;
	}

	public function add()
	{
		return $this->addList(func_get_args());
	}

	public function menu()
	{
		//for thing in things:
		foreach (func_get_args() as $thing) {
			$this->navmenu[] = $thing;
		}
		return $this;
	}

	public function attr($kwargs)
	{
		//for key in $kwargs
		foreach ($kwargs as $key => $value) {
			//if key is '_class':
			//	raise 'not handled'
			//name = key.replace('_', '-')
			$this->attributes[$key] = $value;
		}
		return $this;
	}

	public function style($kwargs)
	{
		foreach ($kwargs as $key => $value) {
			//name = key.replace('_', '-')
			$this->styles[$key] = $value;
		}
		return $this;
	}

	public function addClass()
	{
		//for name in args:
		foreach (func-get_args() as $class)
		{
			if (!in_array($class, $this->classes)) {
				$this->classes[]=$class;
			}
		}
		return $this;
	}

	public function addReadyScript($kwargs)
	{
		foreach ($kwargs as $key => $value) {
			$this->ready[$key] = $value;
		}
		return $this;
	}

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
