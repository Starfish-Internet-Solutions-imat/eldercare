<?php
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class contact
{
	private $contact_id;
	private $sendhub_contact_id;
	private $user_id;
	private $client_type;
	private $contact_number;
	
	//-------------------------------------------------------------------------------------------------
	
	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
	
		else return NULL;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function __set($field, $value) {
		if(property_exists($this, $field)) $this->{$field} = $value;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function insert()
	{
		$insert_array = array(
		'sendhub_contact_id'		=> $this->sendhub_contact_id,
		'user_id'					=> $this->user_id,
		'client_type'				=> $this->client_type,
		'contact_number'			=> $this->contact_number
		);
		
		$db			  = new dbQuery();
		$db->insert('contacts', $insert_array);
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function select()
	{
		$sql = "SELECT
					*
				FROM
					contacts
				WHERE
					user_id = :user_id
				AND
					client_type = :client_type
				";

		
		$db			= new dbQuery();
		$bindParams	= array('hcp_id' => $this->user_id, 'client_type' => $this->client_type);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		return $result;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function updateContactNumber()
	{
		$update_array = array(
			'contact_number' => $this->contact_number
		);
		
		$where_array	= array('user_id' => $this->user_id, 'client_type' => $this->client_type);
		$sql_where		=	" WHERE user_id = :user_id AND client_type = :client_type  ";
		$db = new dbQuery();
		$db->update('contacts', $update_array, $sql_where, $where_array);
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function selectSendHubContactID()
	{
		$sql = "SELECT
					*
				FROM
					contacts
				WHERE
					contact_number = :contact_number
				";
		$db  = new dbQuery();
		$bindParams	= array('contact_number' => $this->contact_number);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		return $result['sendhub_contact_id'];
	}
	
	//-------------------------------------------------------------------------------------------------
		
	public function selectSendHubIdByUserId($user_type, $user_id)
	{
		$sql = "SELECT
					*
				FROM
					contacts
				WHERE
					user_id = :user_id
				AND
					client_type = :client_type
					";
		$db  = new dbQuery();
		$bindParams	= array('user_id' => $user_id, 'client_type' => $user_type);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		return $result['sendhub_contact_id'];
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function updateGeneric($update_array)
	{
		$where_array	= array('user_id' => $this->user_id, 'client_type' => $this->client_type);
		$sql_where		=	" WHERE user_id = :user_id AND client_type = :client_type  ";
		$db = new dbQuery();
		$db->update('contacts', $update_array, $sql_where, $where_array);
	}
	
	
}

?>