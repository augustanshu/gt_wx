<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class VerifyMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('verify');
	}
	public function check()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
	public function complete()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
	public function qrcode()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
	public function detail()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
}