<?php
	require_once 'pui.php';

	class PuiTest extends PHPUnit_Framework_TestCase
	{
		public function testStatic()
		{
			$p = pui::Element('p', array('text' => 'should be <p> tag'));
			$this->assertEquals($p->asHtml(), '<p>should be &lt;p&gt; tag</p>');
		}
		public function testNonStatic()
		{
			$pui = new pui();
			$p = $pui->Element('p', array('text' => 'should be <p> tag'));
			$this->assertEquals($p->asHtml(), '<p>should be &lt;p&gt; tag</p>');
		}
		public function testHtml()
		{
			$p = pui::Element('p');
			$p->add('Arbitrary <b>HTML</b>');
			$this->assertEquals($p->asHtml(), '<p>Arbitrary <b>HTML</b></p>');
		}
	}
