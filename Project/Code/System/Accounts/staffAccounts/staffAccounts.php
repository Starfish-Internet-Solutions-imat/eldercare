<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'staffAccount.php';

class staffAccounts extends dbQuery
{
	private $accounts_array;
	private $staff_id;
	
//-------------------------------------------------------------------------------------------------	
//get value of property in this class. happens after select function is executed	
//$field = name of variable
	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	
//set value of property in the class. executed before update or insert
//$field = name of variable; $value = value of variable
	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }

// ====================================================================================== //
	// select all
	public function select()
	{
		$sql = "SELECT
					*
				FROM
					staffs
				";
		
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$user_account = new staffAccount();

			$this->staff_id = $result['staff_id'];
			$user_account->__set('staff_id', $result['staff_id']);
			$user_account->__set('email', $result['email']);
			$user_account->__set('name', $result['name']);
			$user_account->__set('role', $result['role']);
			$user_account->__set('last_login', $result['last_login']);
			$user_account->__set('cold_leads_count', $this->selectCount('cold'));
			$user_account->__set('immediate_leads_count', $this->countUrgent($this->staff_id));
			$user_account->__set('placed_leads_count', $this->selectCount('placed'));
			$user_account->__set('closed_leads_count', $this->selectCount('closed'));
			
			$total_count = $this->selectCountAll();
						
			$user_account->__set('total', $total_count);
	
			$this->accounts_array[] = $user_account;
		}
		return $this->accounts_array;
	}
	
	public function selectOrderbyUserID($staff_id)
	{
		$sql = "SELECT
					*
				FROM
					staffs
				ORDER BY 
					staff_id = $staff_id DESC
				";
	
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$user_account = new staffAccount();
	
			$this->staff_id = $result['staff_id'];
			$user_account->__set('staff_id', $result['staff_id']);
			$user_account->__set('email', $result['email']);
			$user_account->__set('name', $result['name']);
			$user_account->__set('role', $result['role']);
			$user_account->__set('last_login', $result['last_login']);
			$user_account->__set('cold_leads_count', $this->selectCount('cold'));
			$user_account->__set('immediate_leads_count', $this->countUrgent($this->staff_id));
			$user_account->__set('placed_leads_count', $this->selectCount('placed'));
			$user_account->__set('closed_leads_count', $this->selectCount('closed'));
				
			$total_count = $this->selectCountAll();
	
			$user_account->__set('total', $total_count);
	
			$this->accounts_array[] = $user_account;
		}
		return $this->accounts_array;
	}
	
	
	public function selectWithLeadsOverview()
	{
		$sql = "
				SELECT DISTINCT
						s.* 
					FROM
						staffs s
					INNER JOIN
						leads l
					ON
						l.staff_id = s.staff_id
						
					";
		
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$user_account = new staffAccount();
			$this->staff_id = $result['staff_id'];
			$user_account->__set('staff_id', $result['staff_id']);
			$user_account->__set('email', $result['email']);
			$user_account->__set('name', $result['name']);
			$user_account->__set('role', $result['role']);
			$user_account->__set('last_login', $result['last_login']);
			$user_account->__set('cold_leads_count', $this->selectCount('cold'));
			$user_account->__set('immediate_leads_count', $this->countUrgent($this->staff_id));
			$user_account->__set('contacted_leads_count', $this->selectCount('contact'));
			$user_account->__set('info_sent_leads_count', $this->selectCount('info_sent'));
	
			$this->accounts_array[] = $user_account;
		}
		return $this->accounts_array;
	}
	// ====================================================================================== //	
	public function selectCount($status = "")
	{
		$sql = "
				SELECT 
					DISTINCT COUNT(l.status) 
				AS $status
					FROM leads l 
				WHERE l.status LIKE '%".$status."%' AND l.staff_id = $this->staff_id
								
							";
		
		$results	= $this->executeSQL('select_one', $sql);
		return $results;
		
	}
	
	public function countUrgent($staff_id)
	{
		$sql = "
			SELECT
				SUM(urgent) as immediate
			FROM
				leads
			WHERE
				staff_id = $staff_id
		";
		
		$results	= $this->executeSQL('select_one', $sql);
		return $results;
	}
	
	
// ====================================================================================== //
	public function selectCountAll()
	{
		$sql = "
					SELECT 
						COUNT(l.lead_id)
					AS	total						
						FROM leads l 
					WHERE l.staff_id = $this->staff_id
									
								";
		
		$results	= $this->executeSQL('select_one', $sql);
	
		return $results;
	
	}
}

?>