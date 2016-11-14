<?php
	require_once 'pui.php';

	class NavbarTest extends PHPUnit_Framework_TestCase
	{
		public function testBasic()
		{
			$p = new pui();

			$test = $p->navbar(pui::link('#')->add('BRAND!'))->add(
				$p->link('/target.url')->add('target'),
				'non-link'
			);
			$this->assertEquals($test->asHtml(), '
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#div-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span><span class="icon-bar" /><span class="icon-bar" /><span class="icon-bar" />
      </button><a href="#">BRAND!</a>
    </div>
    <div class="collapse navbar-collapse" id="div-1">
      <ul class="nav navbar-nav">
        <li><a href="/target.url">target</a></li>
        <li>non-link</li>
      </ul>
    </div>
  </div>
</nav>');

		}

	}
