<?php
	require_once 'pui.php';

	class LinkTest extends PHPUnit_Framework_TestCase
	{
		public function testBasic()
		{
			$p = new pui();
			$test = $p->ulist('none');
			$test->addText('plain <text>');
			$test->add($p->link("/target.url")->add('target <b>html</b>'));
			$test->add('one', 'two', 'three');
			$this->assertEquals($test->asHtml(), '
<ul class="none">
  plain &lt;text&gt;
  <li><a href="/target.url">target <b>html</b></a></li>
  <li>one</li>
  <li>two</li>
  <li>three</li>
</ul>');

		}

		public function testArray()
		{
			$p = new pui();
			$menu=array('one', 'two', 'three');
			$this->assertEquals($p->ulist()->addArray($menu)->asHtml(), '
<ul>
  <li>one</li>
  <li>two</li>
  <li>three</li>
</ul>');
		}

		public function testObjectArray()
		{
			$p = new pui();
			$menu=array(
				$p->link('1.html', 'one'),
				$p->link('2.html')->add('two')
			);
			$this->assertEquals($p->ulist()->addArray($menu)->asHtml(), '
<ul>
  <li><a href="1.html">one</a></li>
  <li><a href="2.html">two</a></li>
</ul>');

		}

		public function testNestedArray()
		{
			$p = new pui();
			$submenu=array('a', 'b');
			$menu=array(
				$p->link('1.html', 'one'),
				$p->ulist()->addArray($submenu)
			);
			$this->assertEquals($p->ulist()->addArray($menu)->asHtml(), '
<ul>
  <li><a href="1.html">one</a></li>
  <li>
    <ul>
      <li>a</li>
      <li>b</li>
    </ul>
  </li>
</ul>');
		}

	}
