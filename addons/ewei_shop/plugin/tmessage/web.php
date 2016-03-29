<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class TmessageWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('tmessage');
	}
	public function index()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}