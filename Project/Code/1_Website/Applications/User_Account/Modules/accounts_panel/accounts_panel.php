<?php
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';

class accountPanel 
{
	public static function showAccountPanel()
	{
		if(userSession::areWeLoggedIn() == TRUE)
		{
		?>
			<ul class="fright posRel unstyled" id="account_panel">
				<li>Welcome, <?=userSession::getUserName()?>!</li>
				<li><a href="/account/profile/accountSettings">My Account</a></li>
				<li><a href="/account/logout">Log out</a></li>
			</ul>
		<?
		}
		
		else
		{
		?>
			<ul class="fright posRel unstyled" id="account_panel">
				<li><a href="/account/login">Sign In</a></li>
				<li><a href="/account/register">Register</a></li>
			</ul>
		<?
		}
	}
	
}