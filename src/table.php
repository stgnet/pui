<?php

require_once 'thead.php';
require_once 'tbody.php';
require_once 'tr.php';
require_once 'th.php';
require_once 'td.php';

class Table extends Element
{
	public function __construct($content, $options=array())
	{
		$this->table_content = $content;
		parent::__construct('table');
	}
	private function columns_from_table($table)
	{
		$columns = array();
		foreach ($table as $index => $row)
		{
			foreach ($row as $column => $data)
			{
				if (!in_array($column, $columns))
				{
					$columns[] = $column;
				}
			}
		}
		return $columns;
	}
	private function thead_from_columns($columns)
	{
		$thead = new Thead();
		$tr = new Tr();
		foreach ($columns as $column)
		{
			$th = new Th($column);
			$tr->Add($th);
		}
		$thead->Add($tr);
		return $thead;
	}
	public function asHtml($level)
	{
		/* add tags JIT */
		$columns = $this->columns_from_table($this->table_content);
		$this->add($this->thead_from_columns($columns));
		$tbody = new Tbody();
		foreach ($this->table_content as $index => $row)
		{
			$tr = new Tr();
			foreach ($row as $column => $data)
			{
				$td = new Td($data);
				$tr->Add($td);
			}
			$tbody->Add($tr);
		}
		$this->add($tbody);
		return parent::asHtml($level);
	}
}
