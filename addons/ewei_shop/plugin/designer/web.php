<?php

//decode by 012wz.com QQ:800083075
if (!defined('IN_IA')) {
	die('Access Denied');
}
class DesignerWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('designer');
	}
	public function index()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function api()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}