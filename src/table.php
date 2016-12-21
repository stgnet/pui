<?php

require_once 'element.php';

class Table extends Element
{
	public $columns;

	public function __construct($columns=0)
	{
		parent::__construct('table');
		$this->columns = $columns;
	}
	// override the array handler to add td's around each element
	public function addArray($things)
	{
		$tr = new element('tr');
		foreach ($things as $item) {
			$td = new element('td');
			$td->add($item);
			$tr->add($td);
		}
		$this->add($tr);
	}
	// override the object handler to add 
	public function addObject($thing)
	{
		// is this already a tr object?
		if (is_object($thing) && $thing instanceof element && $thing->get_tag() == 'tr') {
			return $thing;
		}
		// somebody might have given us a modified td object (add tr around it)
		if (is_object($thing) && $thing instanceof element && $thing->get_tag() == 'td') {
			return pui::element('tr')->add($thing);
		}
		return pui::element('tr')->add(pui::element('td')->add($thing));
	}
	public function addData($data, $header=False)
	{
		/* count the columns */
		foreach ($data as $row) {
			if (!is_array($row)) {
				if ($this->columns < 1) {
					$this->columns = 1;
				}
				continue;
			}
			$count = 0;
			foreach ($row as $col) {
				$count++;
			}
			if ($this->columns < $count) {
				$this->columns = $count;
			}
		}

		/* now add the data */
		foreach ($data as $row) {
			$this->add($row);
		}
		return $this;
	}
}
