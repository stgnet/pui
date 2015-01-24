<?php
	require 'src/element.php';

	class ElementTest extends PHPUnit_Framework_TestCase
	{
		public function testptext()
		{
			$p = new element('p', array('text' => 'should be <p> tag'));
			$this->assertEquals($p->asHtml(), '<p>should be &lt;p&gt; tag</p>');
		}
	}
