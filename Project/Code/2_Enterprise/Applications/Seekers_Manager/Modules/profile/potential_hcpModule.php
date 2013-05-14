<?php 




class potential_hcpModule {
	
	
	public static function statusAnalyzer($status_array = array())
	{ 
		if($status_array['0'] != '')
		{
			$status_array = array_filter($status_array);
			
			$status_value_array = array(	'contacted' 		=> 1,
											'info_sent' 		=> 2,
											'placed' 			=> 3,
											'pending_placed' 	=> 4,
											'not_placed' 		=> 5
										);
			
			$value_equivalent_array = array(
													1 	=> 'contact_made',
													2 	=> 'info_sent',
													3 	=> 'placed',
													4	=> 'pending_placed',
													5	=> 'not_placed'
											);
			
			$result_status = 0;
			
			foreach($status_array as $status)
			{
				if ($status_value_array[$status] > $result_status)
				{
					$result_status = $status_value_array[$status];
				}
			}
			return $value_equivalent_array[$result_status];
		}
		else
			return 'cold_new';
	}
}
?>