<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/ResultCleaner/resultCleaner.php';

class settings
{
	private $username;
	private $password;
	private $smtp_host;
	private $smtp_username;
	private $smtp_password;
	private $smtp_port;
	private $use_smtp;
	private $smtp_auth;
	private $to_email;
	private $to_name;
	private $from_email;
	private $from_name;
	private $google_analytics;
	
	private $user_role_id;
	
//========================================================================================

	public function getUsername() { return $this->username; }
	
	public function getPassword() { return $this->password; }
	
	public function getHost() { return $this->smtp_host; }
	
	public function getSmtpUsername() { return $this->smtp_username; }
	
	public function getSmtpPassword() {	return $this->smtp_password; }
	
	public function getPort() {	return $this->smtp_port; }
	
	public function getUseSmtp() { return $this->use_smtp; }
	
	public function getSmtpAuth() { return $this->smtp_auth; }
	
	public function getToEmail() { return $this->to_email; }
	
	public function getToName() { return $this->to_name; }
	
	public function getFromEmail() { return $this->from_email; }
	
	public function getFromName() { return $this->from_name; }
	
	public function getGoogleAnalytics() { return $this->google_analytics; }
	
	
//========================================================================================
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function setHost($smtp_host) {
		$this->smtp_host = $smtp_host;
	}
	
	public function setSmtpUsername($smtp_username) {
		$this->smtp_username = $smtp_username;
	}
	
	public function setSmtpPassword($smtp_password) {
		$this->smtp_password = $smtp_password;
	}
	
	public function setPort($smtp_port) {
		$this->smtp_port = $smtp_port;
	}
	
	public function setToEmail($to_email) {
		$this->to_email = $to_email;
	}
	
	public function setToName($to_name) {
		$this->to_name = $to_name;
	}
	
	public function setFromEmail($from_email) {
		$this->from_email = $from_email;
	}
	
	public function setFromName($from_name) {
		$this->from_name = $from_name;
	}
	
	public function setUseSmtp($use_smtp) {
		$this->use_smtp = $use_smtp;
	}
	
	public function setSmtpAuth($smtp_auth) {
		$this->smtp_auth = $smtp_auth;
	}
	
	public function setGoogleAnalytics($google_analytics) {
		$this->google_analytics = stripslashes($google_analytics);
	}
	
//========================================================================================
	
	public function update()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
				
			$sql = "UPDATE
						enterprise_settings
					SET
						password			= :password,
						username			= :username,
						smtp_host			= :smtp_host,
						smtp_username		= :smtp_username,
						smtp_pass			= :smtp_pass,
						smtp_port			= :smtp_port,
						smtp_auth			= :smtp_auth,
						use_smtp			= :use_smtp,
						to_email			= :to_email,
						to_name				= :to_name,
						from_email			= :from_email,
						from_name			= :from_name,
						google_analytics	= :google_analytics
					WHERE
						settings_id = 1
					";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':password', $this->password, PDO::PARAM_STR);
			$pdo_statement->bindParam(':username', $this->username, PDO::PARAM_STR);
			$pdo_statement->bindParam(':smtp_host', $this->smtp_host, PDO::PARAM_STR);
			$pdo_statement->bindParam(':smtp_username', $this->smtp_username, PDO::PARAM_STR);
			$pdo_statement->bindParam(':smtp_pass', $this->smtp_password, PDO::PARAM_STR);
			$pdo_statement->bindParam(':smtp_auth', $this->smtp_auth, PDO::PARAM_INT);
			$pdo_statement->bindParam(':use_smtp', $this->use_smtp, PDO::PARAM_INT);
			$pdo_statement->bindParam(':smtp_port', $this->smtp_port, PDO::PARAM_STR);
			$pdo_statement->bindParam(':to_email', $this->to_email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':to_name', $this->to_name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':from_email', $this->from_email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':from_name', $this->from_name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':google_analytics', $this->google_analytics, PDO::PARAM_STR);
			$pdo_statement->execute();
				
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//========================================================================================
	
	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "
				SELECT
					*
				FROM
					enterprise_settings
				WHERE
					settings_id = 1
						";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
	
			$result = resultCleaner::cleanSingleResult($pdo_statement->fetch(PDO::FETCH_ASSOC));

			$this->username				= $result['username'];
			$this->password				= $result['password'];
			$this->smtp_host			= $result['smtp_host'];
			$this->smtp_username		= $result['smtp_username'];
			$this->smtp_password		= $result['smtp_pass'];
			$this->smtp_port			= $result['smtp_port'];
			$this->to_email				= $result['to_email'];
			$this->to_name				= $result['to_name'];
// 			$this->from_email			= $result['from_email'];
// 			$this->from_name			= $result['from_name'];
			$this->google_analytics		= $result['google_analytics'];
			$this->use_smtp				= $result['use_smtp'];
			$this->smtp_auth			= $result['smtp_auth'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}

	}
	
//========================================================================================
	
	public static function selectLogin($username, $password)
	{
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            
            $sql = "SELECT
                    	COUNT(settings_id) as counter
                    FROM
                        enterprise_settings
                    WHERE
                        username = :username
                    AND
                        password = :password
                    ";
            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(':username', $username, PDO::PARAM_STR);
            $pdo_statement->bindParam(':password', $password, PDO::PARAM_STR);
            $pdo_statement->execute();
            
            $result = resultCleaner::cleanSingleResult($pdo_statement->fetch());
            
           	return $result['counter'];
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
        
        return FALSE;
	}
	
//========================================================================================
	public function selectGoogleAnalytics()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "
					SELECT
						google_analytics
					FROM
						enterprise_settings
					WHERE
						settings_id = 1
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
			
			$result = $pdo_statement->fetch();
			
			$this->google_analytics		= $result['google_analytics'];
		}
		catch(PDOException $pdoe)
		{
		throw new Exception($pdoe);
		}
	} 

}
?>