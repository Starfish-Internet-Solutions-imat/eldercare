<?php 

class hcpModule
{
	private static $hcp_registry = array();
	
	public static function hcp_registry_get($id)
	{
		if (isset(Self::$hcp_registry[$id]))
			return Self::$hcp_registry[$id]; 
	}
	
	public static function hcp_registry_set($id, $name)
	{
		Self::$hcp_registry[$id] = $name;
	}
}
?>