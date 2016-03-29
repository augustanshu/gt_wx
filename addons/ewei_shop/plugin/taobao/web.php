<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
require_once 'model.php';
class TaobaoWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('taobao');
	}
	public function index()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function fetch()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}