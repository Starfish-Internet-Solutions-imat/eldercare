<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'healthcare_provider.php';

class healthcare_providers extends dbQuery
{
	private $array_of_results = array();

	//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	
	
	public function select()
	{
		$sql = "SELECT
					*
				FROM
					hcp_contact_persons
				";
		return $this->executeSQL('select_many', $sql);
		
	}
	
	
}


?>