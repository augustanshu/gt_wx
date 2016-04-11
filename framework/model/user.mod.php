<?php
/**
 * Copyright © 2016 Port-of-World.
 * 用户操作相关函数
 */
defined('IN_IA') or exit('Access Denied');

/*
* 用户注册
*$user	array	用户注册信息 如果注册成功，返回新增的用户ID，否则返回0
*/
function user_register($user) {
	if (empty($user) || !is_array($user)) {
		return 0;
	}
	if (isset($user['uid'])) {
		unset($user['uid']);
	}
	$user['salt'] = random(8);
	$user['password'] = user_hash($user['password'], $user['salt']);
	$user['joinip'] = CLIENT_IP;
	$user['joindate'] = TIMESTAMP;
	$user['lastip'] = CLIENT_IP;
	$user['lastvisit'] = TIMESTAMP;
	if(empty($user['status'])){
		$user['status'] = 2;
	}
	$result = pdo_insert('users', $user);
	if(!empty($result)) {
		$user['uid'] = pdo_insertid();
	}
	return intval($user['uid']);
}

/*
* 检测用户是否存在
* $user	array	用户信息 如果用户不存在，返回FALSE，如果用户存在，则返回TRUE
*/
function user_check($user) {
	if (empty($user) || !is_array($user)) {
		return false;
	}
	$where = ' WHERE 1 ';
	$params = array();
	if(!empty($user['uid'])) {
		$where .= ' AND `uid`=:uid';
		$params[':uid'] = intval($user['uid']);
	}
	if(!empty($user['username'])) {
		$where .= ' AND `username`=:username';
		$params[':username'] = $user['username'];
	}
	if(!empty($user['status'])) {
		$where .= " AND `status`=:status";
		$params[':status'] = intval($user['status']);
	}
	if (empty($params)) {
		return false;
	}
	$sql = 'SELECT `password`,`salt` FROM ' . tablename('users') . "$where LIMIT 1";
	$record = pdo_fetch($sql, $params);
	if(empty($record) || empty($record['password']) || empty($record['salt'])) {
		return false;
	}
	if(!empty($user['password'])) {
		$password = user_hash($user['password'], $record['salt']);
		return $password == $record['password'];
	}
	return true;
}

/*
*查询用户信息
*$user_or_id	int	要查询的用户ID
*如果要查询的用户ID为空，返回FALSE，否则返回用户的详细信息。
*/
function user_single($user_or_uid) {
	$user = $user_or_uid;
	if (empty($user)) {
		return false;
	}
	if (is_numeric($user)) {
		$user = array('uid' => $user);
	}
	if (!is_array($user)) {
		return false;
	}
	$where = ' WHERE 1 ';
	$params = array();
	if(!empty($user['uid'])) {
		$where .= ' AND `uid`=:uid';
		$params[':uid'] = intval($user['uid']);
	}
	if(!empty($user['username'])) {
		$where .= ' AND `username`=:username';
		$params[':username'] = $user['username'];
	}
	if(!empty($user['email'])) {
		$where .= ' AND `email`=:email';
		$params[':email'] = $user['email'];
	}
	if(!empty($user['status'])) {
		$where .= " AND `status`=:status";
		$params[':status'] = intval($user['status']);
	}
	if (empty($params)) {
		return false;
	}
	$sql = 'SELECT * FROM ' . tablename('users') . " $where LIMIT 1";
	$record = pdo_fetch($sql, $params);
	if(empty($record)) {
		return false;
	}
	if(!empty($user['password'])) {
		$password = user_hash($user['password'], $record['salt']);
		if($password != $record['password']) {
			return false;
		}
	}
	return $record;
}

/*
*更新用户资料
*$uesr	array	用户的资料数据 如果更新数据成功返回TRUE否则返回FALSE。
*/
function user_update($user) {
	if(empty($user['uid']) || !is_array($user)) {
		return false;
	}
	$record = array();
	if(!empty($user['password'])) {
		$record['password'] = user_hash($user['password'], $user['salt']);
	}
	if(!empty($user['lastvisit'])) {
		$record['lastvisit'] = (strlen($user['lastvisit']) == 10) ? $user['lastvisit'] : strtotime($user['lastvisit']);
	}
	if(!empty($user['lastip'])) {
		$record['lastip'] = $user['lastip'];
	}
	if(isset($user['joinip'])) {
		$record['joinip'] = $user['joinip'];
	}
	if(isset($user['remark'])) {
		$record['remark'] = $user['remark'];
	}
	if(isset($user['status'])) {
		$status = intval($user['status']);
		if (!in_array($status, array(1,2))) {
			$status = 2;
		}
		$record['status'] = $status;
	}
	if (isset($user['groupid'])) {
		$record['groupid'] = $user['groupid'];
	}
	if(empty($record)) {
		return false;
	}

	return pdo_update('users', $record, array('uid' => intval($user['uid'])));
}

/*
*计算用户密码
*$passwordinput	string	用户表单提交的密码
*$salt	string	附加的字符串
*返回的是一个字符串。
*/
function user_hash($passwordinput, $salt) {
	global $_W;
	$passwordinput = "{$passwordinput}-{$salt}-{$_W['config']['setting']['authkey']}";
	return sha1($passwordinput);
}

/*
*给用户设置权限
*返回值 数组
*/
function user_level() {
	static $level = array(
		'-3' => '锁定用户',
		'-2' => '禁止访问',
		'-1' => '禁止发言',
		'0' => '普通会员',
		'1' => '管理员',
	);
	return $level;
}
