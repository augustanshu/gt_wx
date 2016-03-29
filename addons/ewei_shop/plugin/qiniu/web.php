<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class QiniuWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('qiniu');
	}
	public function check($config)
	{
		return p('qiniu')->save('http://www.baidu.com/img/bdlogo.png', $config);
	}
	public function index()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}