<?php 
class sendhub
{
	public function sendSms($contactID, $message)
		{
			$httpHeader = array("Content-Type:  application/json");
			
			$mnum = trim('4803325449');
			$apk =  trim('35d33470910806d61b8bb15bf47df2813689deaf');
			$url = 'https://api.sendhub.com/v1/messages/?username='.$mnum.'&api_key='.$apk;
						
			$fields = '{"contacts": ['.$contactID.'] , "text":"'.$message.'"}';
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			
			$status = $this->responseStatusCheck($result);
			return $status;
		
	}
	
	public function addSmsContacts($name, $mobileNumber)
	{
		$mnum = trim('4803325449');
		$apk = trim('35d33470910806d61b8bb15bf47df2813689deaf');
		$url = 'https://api.sendhub.com/v1/contacts/?username='.$mnum.'&api_key='.$apk;
		$ch = curl_init();
		$number = stripslashes(rawurldecode($mobileNumber));
		$number = str_replace( '(', "", $number );
		$number = str_replace( ')', "", $number );
		//$number = str_replace( '-', "", $number );
		$number = trim($number);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json' ));
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"name":"'.strtoupper($name).'","number":"'.$number.'"}');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch); 
		
		$status = substr($result, 9, 3);
		if($status != "201")
		{ 
			return 'Invalid Number';
		}
		else
		{
			$start = strpos($result, '{');
			$json  = substr($result, $start);
			$decoded = json_decode($json);
			return $decoded->id;
		} 
	}
	
	public function editSenhubContact($sendHubContactID, $name, $number)
	{
		$httpHeader = array("Content-Type:  application/json");
		$mnum = trim('4803325449');
		$apk =  trim('35d33470910806d61b8bb15bf47df2813689deaf');
		$url = 'https://api.sendhub.com/v1/contacts/'.$sendHubContactID.'/?username='.$mnum.'&api_key='.$apk;
		
		$fields = '{"id": "'.$sendHubContactID.'" , "name":"'.$name.'", "number": "'.$number.'"}';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$status = $this->responseStatusCheck($result);
		return $status;
		
	}
	
	private function responseStatusCheck($response)
	{
		$status = substr($response, 9, 3);
		if($status == "201" ||  $status == "202" )
			return TRUE;
		else
			return FALSE;
	}

}
?>