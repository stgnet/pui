<?php
//use stgnet\pui;

require "pui.php";

$pui = new pui();

$lock='<span class="glyphicon glyphicon-lock"></span>';
$menu=array(
	$pui->link('/about.php')->addText('About'),
	$pui->link('/contact.php', 'Contact Us'),
	$pui->link('/faq.php')->add($pui->Text('FAQ')),
/*
	'About' => '/index.php',
	'Contact Us' => '/contact.php',
	'Guitars' => '/guitars/',
	'Parts' => '/inventory/',
	'Lessons' => '/lessons.php',
	'Kits & Classes' => '/kits.php',
	'Help' => array(
		'About' => 'about.php',
		'FAQ' => 'faq.php',
		'Suggest' => 'suggest.php',
		'Changes' => 'changes.php'
	)
*/
);

	$menu_right=array(
		'Admin' => array(
//			'Payment '.$lock => 'payment.php',
//			'Balances '.$lock => 'balances.php',
			'Report '.$lock => '/report.php',
//			'Items '.$lock => 'items.php',
//			'Change Email '.$lock => 'change_email.php'
		)
	);


echo $pui->page('Very Much Longer Page Name <test>', array('lang' => 'en'))->background('blue')->add(
    $pui->bootstrap('flatly'),
	$pui->navbar($pui->link('#','BRAND!'))->addContent($menu),
    $pui->heading(1, "this & that and several other things too"))->asHtml(0, True);

/* <div class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><span>
			  <img src="/mmm.png" style="height:auto; width:auto; max-height:26px;">
			  Magic Music Mill
		  </span></a>
        </div>
        <div class="navbar-collapse collapse">
			<?php
				navbar($menu); 
				navbar($menu_right,'navbar-right');
			?>
        </div><!--/.nav-collapse -->
      </div>
    </div>
*/
