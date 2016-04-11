<?php
/**
 * 缓存操作函数
 * 
 */
defined('IN_IA') or exit('Access Denied');

load()->func('cache.' . $_W['config']['setting']['cache']);

/*
* 读取缓存，并将缓存加载至 $_W 全局变量中。
* $key	string	缓存键名
* $unserialize	boolean	是否将缓存数据反序列化
*/
function cache_load($key, $unserialize = false) {
	global $_W;
	$data = $_W['cache'][$key] = cache_read($key);
	if ($key == 'setting') {
		$_W['setting'] = $data;
		return $_W['setting'];
	} elseif ($key == 'modules') {
		$_W['modules'] = $data;
		return $_W['modules'];
	} else {
		return $unserialize ? iunserializer($data) : $data;
	}
}

function &cache_global($key) {
	
}
