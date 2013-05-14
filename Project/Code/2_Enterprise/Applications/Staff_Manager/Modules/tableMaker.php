<?php

class tableMaker
{
	public static function generateTable($rows, $columns)
	{
		$content = '<table>';
		
		for($i = 0; $i <= $rows; $i++)
		{
			$content .= '<tr>';
			
			for($j = 0; $j <= $columns; $j++)
				if($j == 0)
					$content .= '<th></th>';
				else
					$content .= '<td></td>';
			
			$content .= '</tr>';
		}
		
		$content .= '</table>';
		
		return $content;
	}
}