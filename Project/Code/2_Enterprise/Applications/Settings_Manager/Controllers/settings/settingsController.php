<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/System/Settings/settings.php';
require_once 'settingsView.php';

class settingsController extends applicationsSuperController
{
	public function indexAction()
	{	
		$model = new settings();
		$model->select();
		
		if(isset($_POST['saveBtn']) == 'Save')
		{
			if($_POST['password'] == '' || $_POST['password'] == NULL){
				$model->setPassword($model->getPassword());
			}
			else
				$model->setPassword(sha1(md5($_POST['password'])));
			
			$model->setUsername($_POST['username']);
			$model->setHost($_POST['smtp_host']);
			$model->setSmtpUsername($_POST['smtp_user']);
			$model->setSmtpPassword($_POST['smtp_pass']);
			$model->setPort($_POST['smtp_port']);
			$model->setToEmail($_POST['to_email']);
			$model->setToName($_POST['to_name']);
			$model->setSmtpAuth($_POST['smtp_auth']);
			$model->setUseSmtp($_POST['use_smtp']);
			$model->setGoogleAnalytics($_POST['g_analytics']);
			$model->update();
		}
		
		$view = new settingsView();
		$view->setArrayOfResults($model);
		$view->displaySettingsContentColumn();
	}
}


