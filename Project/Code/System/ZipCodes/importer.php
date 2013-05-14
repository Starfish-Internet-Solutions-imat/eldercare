<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/dbQuery/dbQuery.php';

class importer
{

//====================================================================================== //

	public static function import()
	{
		$handle = fopen('C:\Users\Oct 14-2011\Desktop\HEART\PROJECT RESOURCES\Eldercare Zip codes\zipcodes.csv', 'r');
		$state = '';
		
		$i = 0;
			
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
		if($handle !== FALSE)
		{
			while(!feof($handle))
			{
				$fetched_line = fgets($handle);
				$columns = str_getcsv($fetched_line);
				
				if($columns[0] != '' && $state != $columns[0])
					$state = $columns[0];
				
				$dbQuery = new dbQuery();
				$dbQuery->__set('table_name', 'zipcodes');
				
				if($columns[3] != '')
				{
					$county = explode(', ', $columns[7]);
					
					$insert_array = array(
						'zipcode'	=> $columns[3],
						'state'		=> $state,
						'city'		=> $columns[5],
						'county'	=> $county[0]
					);
					
					$dbQuery->__set('insert_array', $insert_array);
					$dbQuery->insert();
				}
				
				$i++;
			}
		}
		
		$pdo_connection->commit();
		fclose($handle);
	}
	
}

?>