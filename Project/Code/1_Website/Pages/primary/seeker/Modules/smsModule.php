<?php 



class smsModule {
	
	//this works with http://sendhub.com
	
	private $seeker_mobile_number;
	private $array_of_hcp_mobile_number;

	//=============================================METHODS==============================================================
	
	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
	
		else return NULL;
	}
	
	//===================================================================================================================
	
	public function _set($field, $value) {
		if(property_exists($this, $field)) $this->{$field} = $value;
	}
	
	//===================================================================================================================
	
	public function addContact()
	{
		
		
		
	}
	
	public function sendSms($contact_mobile_number = array())
	{
		
		
		
		
		
	}
	
	
	public function editContact()
	{
		
		
		
	}
	
	
	
	
}





















?>