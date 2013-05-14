<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class searchModel
{
	private $name;
	private $join_date;
	private $status;
	private $zipcode;
	private $enterprise_user_account_id;
	
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
					seekers
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		foreach($results as $result)
		{
			$seeker = new seeker();
			
			$seeker->__set('seeker_id', $result['seeker_id']);
			$seeker->__set('name', $result['name']);
			$seeker->__set('email', $result['email']);
			$seeker->__set('telephone', $result['telephone']);
			$seeker->__set('relationship_to_patient', $result['relationship_to_patient']);
			$seeker->__set('house_type', $result['house_type']);
			$seeker->__set('zipcode', $result['zipcode']);
			
			$this->array_of_seekers[] = $seeker;
		}
		
		return $this->$results;
	}
	
}

?>