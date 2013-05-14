<?php
require_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php';
require_once 'Project/Code/2_Enterprise/Applications/Staff_Manager/Modules/tableMaker.php';

class applicationsSuperView  extends viewSuperClass_Core
{
	
//-------------------------------------------------------------------------------------------------	

	public function displayCommonCSSAndJS()
	{	
		$common_location = 'Project/Design/2_Enterprise/Applications/_Common/templates/';
		
		$content = $this->renderTemplate($common_location.'js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_used_on_every_page',array('COMMON_CSS'=>$content));
		
		$content = $this->renderTemplate($common_location.'crm_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CRM_CSS'=>$content));
		
		$content = $this->renderTemplate($common_location.'v_scrollbar.phtml');
		response::getInstance()->addContentToTree(array('V_SCROLLBAR'=>$content));
		
		$content = $this->renderTemplate($common_location.'crm_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_bottom',array('CRM_COMMON_JS'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	protected function generateTable($rows, $columns)
	{
		$content = '';
		$th = '';
		$td = '';
		$tr = '';
			
		for($j = 1; $j <= $columns; $j++)
				$th .= 
		"
		<th>Heading</th>";
			
		$tr .= 
	'
	<tr>'.$th.'
	</tr>';
			
		for($j = 1; $j <= $columns; $j++)
				$td .= 
		"
		<td>Data</td>";
		
		for($i = 1; $i <= $rows; $i++)
		{
			$tr .= 
	'
	<tr>'.$td.'
	</tr>';
		}
		
		$content = 
'<table>'.$tr.'
</table>';
		
		return $content;
	}
	
//-------------------------------------------------------------------------------------------------	

	protected function displayNumberSelectInput($name, $max, $selected = '', $min = 1)
	{	
		$options = range($min, $max);
		
		return $this->displaySelectInput($options, $name, $selected);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displaySelectInput($options, $name, $selected = '')
	{	
		$content = '<select name="'.$name.'"><option value=""></option>';
		
		foreach($options as $key=>$value)
		{
			$select = '';
			
			if($selected === $key)
				$select = ' selected="selected"';
			
			$content .= '<option value="'.$key.'"'.$select.'>'.$value.'</option>';
		}
		
		$content .= '</select>';
		
		return $content;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayRadioOrCheckInput($options, $name, $selected = '', $type = 'radio', $sep = '')
	{	
		$content = array();
		
		foreach($options as $key=>$value)
		{
			$select = ($selected == $value) ? ' checked="checked"' : '';
			$content[] = '<input type="'.$type.'" name="'.$name.'" value="'.$key.'"'.$select.'>'.'<span>'.$value.'</span>';
		}
		
		return implode($sep, $content);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayOptionChoice($options, $selected)
	{
		foreach($options as $key=>$value)
			if($selected == $key)
				return $value;
	}
}