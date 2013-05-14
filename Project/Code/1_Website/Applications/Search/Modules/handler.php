<?php

class handler
{
	public static $id;
	public static $zipcode;
	public static $zipcode_id;
	public static $housing_type;
	public static $pricing_range;
	public static $state_city;
	
	public static $raw_uri;
	
	public static function link_builder($name, $id)
	{
		$name = preg_replace('/[^ \w]+/', '', $name);
		return strtolower(str_replace(' ', '-', $name) . "-" . $id);
	}
	
	public static function uriCoverter($uri)
	{
		return str_replace('@', '/', $uri);
	
	}
	
	public static function permalink($uri = "")
	{
		if($uri == "")
			$uri = $_SERVER['REQUEST_URI'];
		
		$uri = ltrim($uri, '/');
	
		$uri = explode('/', $uri);
	
		$uri = $uri[1];
		
		self::$id = "";
		$uri = preg_replace('/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $uri);
		
		return ltrim(substr($uri, strrpos($uri, '-')), '-');
	}
	
	public static function getId()
	{
		
	}
	
	public static function encodePrettyUrl($url="")
	{
		//var_dump($url);die;
		$decoded_array = self::urldecode_to_array($url);
		//var_dump($decoded_array); die;
		include_once 'Project/Code/System/ZipCodes/zipcode.php';
		$zipcodeModel = new zipcode();
		
		if (empty($decoded_array['zipcode_id']))
			$decoded_array['zipcode_id'] = 1;
		
		if (isset($decoded_array['price_range']) && !empty($decoded_array['price_range']))
		{
			//self::$zipcode = rtrim(ltrim(substr($decoded_array['query'], 0, strpos($decoded_array['query'], '-')))," ");
			self::$zipcode_id = $decoded_array['zipcode_id'];
			$zipcodeModel->__set('zipcode_id', self::$zipcode_id);
			$zipcode = $zipcodeModel->selectById();
			self::$zipcode = $zipcode['zipcode'];
			
			self::$state_city = rtrim(ltrim(substr($decoded_array['query'], strpos($decoded_array['query'], '-')+2))," ");
			self::$housing_type = strtolower(str_replace(" ", "-", $decoded_array['housing_type']));
			self::$pricing_range = strtolower($decoded_array['price_range']);
		//	die(trim("/".self::$housing_type."/".self::$zipcode."/".self::$pricing_range, " "));
			return trim("/".self::$housing_type."/".self::$zipcode."/".self::$pricing_range, " ");
		}
		else
		{
			self::$zipcode_id = $decoded_array['zipcode_id'];
			$zipcodeModel->__set('zipcode_id', self::$zipcode_id);
			$zipcode = $zipcodeModel->selectById();
			self::$zipcode = $zipcode['zipcode'];
				
			self::$state_city = rtrim(ltrim(substr($decoded_array['query'], strpos($decoded_array['query'], '-')+2))," ");
			self::$housing_type = strtolower(str_replace(" ", "-", $decoded_array['housing_type']));
			//var_dump( strtolower(str_replace(" ", "-", $decoded_array['housing_type'])));
			//echo self::$housing_type; die;
			//echo trim("/".self::$housing_type."/".self::$zipcode, " "); die;
			return trim("/".self::$housing_type."/".self::$zipcode, " ");
		}
	}
	
	public static function decodeUrl($pretty_url = "")
	{
		if ($pretty_url == "")
			$pretty_url = ltrim($_SERVER['REQUEST_URI']."/",'/');
		
		$array_of_values = explode('/', $pretty_url);
		
		return $array_of_values;
	}
	
	public static function urldecode_to_array($url="") 
	{
		if(count($_GET) != 0)
		{
			if ($url == "")
				$url = $_SERVER['REQUEST_URI'];
			//echo $url; die;
			self::$raw_uri = $url;
			$return_array = array();
			if (($pos = strpos($url, '?')) !== false)
			$url = substr($url, $pos + 1);
			if (substr($url, 0, 1) == '&')
			$url = substr($url, 1);
			$elems_ar = explode('&', $url);
			for ($i = 0; $i < count($elems_ar); $i++)
			{
				list($key, $val) = explode('=', $elems_ar[$i]);
				$return_array[urldecode($key)] = urldecode($val);
			}
			return $return_array;
		}
		
	}
	
}