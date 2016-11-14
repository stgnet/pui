<?php


/* example from bootstrap docs:

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

*/

class Navbar extends Element
{
	private $inner;

	public function __construct()
	{
		parent::__construct('nav', array('class' => 'navbar navbar-default'));

		$nav_list = pui::ulist('nav navbar-nav');
		$nav_menu = pui::element('div', array('class' => 'collapse navbar-collapse'))->add($nav_list);

		$this->add(
			pui::element('div', array('class' => 'container-fluid'))->add(
				pui::element('div', array('class' => 'navbar-header'))->add(
					pui::element('button', array(
						'type' => 'button',
						'class' => 'navbar-toggle collapsed',
						'data-toggle' => 'collapse',
						'data-target' => '#' . $nav_menu->get_id(),
						'aria-expanded' => 'false'
					))->add(
						pui::element('span', array('class' => 'sr-only', 'text' => 'Toggle navigation')),
						pui::element('span', array('class' => 'icon-bar')),
						pui::element('span', array('class' => 'icon-bar')),
						pui::element('span', array('class' => 'icon-bar'))
					),
					pui::element()->addContent(func_get_args())
				),
				$nav_menu
			)
		);
		$this->inner = $nav_list;
	}
	public function innerObject()
	{
		if ($this->inner) {
			return $this->inner;
		}
		return $this;
	}
}

