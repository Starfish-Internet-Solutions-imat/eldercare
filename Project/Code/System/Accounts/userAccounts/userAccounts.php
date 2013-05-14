<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'userAccount.php';

class userAccounts
{
	private $accounts_array;
	
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
		$pdo_connection = starfishDatabase::getConnection();
	
		$sql = "SELECT
					*
				FROM
					user_accounts
				";
		
		$pdo_statement = $pdo_connection->query($sql);
	
		$results = $pdo_statement->fetchAll();
	
		foreach($results as $result)
		{
			$user_account = new userAccount();
				
			$user_account->__set('user_account_id', $result['user_account_id']);
			$user_account->__set('email', $result['email']);
			$user_account->__set('first_name', $result['first_name']);
			$user_account->__set('last_name', $result['last_name']);
	
			$this->accounts_array[] = $user_account;
		}
	}
	
// ====================================================================================== //

	public static function isEmailUnique($email)
	{
		$email = trim($email);
		
		$pdo_connection = starfishDatabase::getConnection();
		
		$sql = "SELECT
							email
						FROM
							user_accounts
						WHERE
							email = :email
						";
		
		$pdo_statement = $pdo_connection->prepare($sql);
		$pdo_statement->bindParam(':email', $email, PDO::PARAM_STR);
		$pdo_statement->execute();
		
		$results = $pdo_statement->fetch();
		var_dump($results);die;
	}
	
}

?>