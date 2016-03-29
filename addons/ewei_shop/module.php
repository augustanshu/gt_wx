<?php

//www.weixin023.com 
if (!defined('IN_IA')) {
	die('Access Denied');
}
class Ewei_shopModule extends WeModule
{
	public function fieldsFormDisplay($rid = 0)
	{
	}
	public function fieldsFormSubmit($rid = 0)
	{
		return true;
	}
	public function settingsDisplay($settings)
	{
	}
}