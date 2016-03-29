<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
if (!class_exists('TmessageModel')) {
	class TmessageModel extends PluginModel
	{
		function perms()
		{
			return array('tmessage' => array('text' => $this->getName(), 'isplugin' => true, 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log', 'send' => '发送-log'));
		}
	}
}