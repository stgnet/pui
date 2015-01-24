<?php
	// prove that travis-ci implementation is working

	class NonTest extends PHPUnit_Framework_TestCase
	{
		public function testTheTest()
		{
			$this->assertEquals(1, 1);
		}
	}
