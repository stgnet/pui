<?php
//use stgnet\pui;

require "pui.php";

$pui = new pui();

echo $pui->page('Very Much Longer Page Name <test>')->background('blue')->add(
    $pui->bootstrap(),
    $pui->heading(1, "this & that and several other things too")
)->asHtml(0, True);
