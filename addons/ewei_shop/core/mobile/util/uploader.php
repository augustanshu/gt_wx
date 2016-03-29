<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
load()->func('file');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'upload';
if ($operation == 'upload') {
	$field = $_GPC['file'];
	if (!empty($_FILES[$field]['name'])) {
		if ($_FILES[$field]['error'] != 0) {
			$result['message'] = '上传失败，请重试！';
			die(json_encode($result));
		}
		$path = '/images/ewei_shop/' . $_W['uniacid'];
		if (!is_dir(ATTACHMENT_ROOT . $path)) {
			mkdirs(ATTACHMENT_ROOT . $path);
		}
		$_W['uploadsetting'] = array();
		$_W['uploadsetting']['image']['folder'] = $path;
		$_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
		$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
		$file = file_upload($_FILES[$field], 'image');
		if (is_error($file)) {
			$result['message'] = $file['message'];
			die(json_encode($result));
		}
		$result['status'] = "success";
		$result['url'] = $file['url'];
		$result['error'] = 0;
		$result['filename'] = $file['path'];
		$result['url'] = save_media($_W['attachurl'] . $result['filename']);
		pdo_insert('core_attachment', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'filename' => $_FILES[$field]['name'], 'attachment' => $result['filename'], 'type' => 1, 'createtime' => TIMESTAMP));
		die(json_encode($result));
	} else {
		$result['message'] = '请选择要上传的图片！';
		die(json_encode($result));
	}
} elseif ($operation == 'remove') {
	$file = $_GPC['file'];
	file_delete($file);
	show_json(1);
}