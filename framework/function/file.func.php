<?php
/**
 *  文件操作函数
 *  该文件内所有函数使用前必须加载文件: load()→func('file');
 */
defined('IN_IA') or exit('Access Denied');

/*
* 将数据写入文件 
* $filename	string	文件名称 
* $data	string	写入数据
* 将数据 $data 写入到文件 $filename 中，如果文件 $filename 不存在，则创建，返回一个是否写入成功的布尔值。
* load()->func('file'); file_write(IA_ROOT . '/test.log', 'hello-world');
*/
function file_write($filename, $data) {
	global $_W;
	$filename = ATTACHMENT_ROOT . '/' . $filename;
	mkdirs(dirname($filename));
	file_put_contents($filename, $data);
	@chmod($filename, $_W['config']['setting']['filemode']);
	return is_file($filename);
}

/*
* 将文件移动至目标位置
* $filename	string	移动的文件 $dest	string	移动的目标位置
* 将指定文件移动到指定的目录，如果目录不存在，则创建，返回一个是否移动成功的布尔值。
* load()->func('file'); file_move(IA_ROOT . '/test.log', IA_ROOT . '/web/test.log');
*/
function file_move($filename, $dest) {
	global $_W;
	mkdirs(dirname($dest));
	if (is_uploaded_file($filename)) {
		move_uploaded_file($filename, $dest);
	} else {
		rename($filename, $dest);
	}
	@chmod($filename, $_W['config']['setting']['filemode']);
	return is_file($dest);
}

/*
* 获取指定目录下所有文件路径
* $path	string	文件夹目录
* 获取并返回 $path 目录下所有文件数组。
*/
function file_tree($path) {
	$files = array();
	$ds = glob($path . '/*');
	if (is_array($ds)) {
		foreach ($ds as $entry) {
			if (is_file($entry)) {
				$files[] = $entry;
			}
			if (is_dir($entry)) {
				$rs = file_tree($entry);
				foreach ($rs as $f) {
					$files[] = $f;
				}
			}
		}
	}
	return $files;
}

/*
* 递归创建目录
* $path	string	需要创建的目录名称
* load()->func('file'); mkdirs(IA_ROOT . '/web/hello/world/example');
*/
function mkdirs($path) {
	if (!is_dir($path)) {
		mkdirs(dirname($path));
		mkdir($path);
	}
	return is_dir($path);
}

/*
* 复制指定目录下所有文件到新目录
* $src	string	原始文件夹
* $des	string	目标文件夹
* $filter	array	需要过滤的文件类型
* file_copy(IA_ROOT . '/web', IA_ROOT . '/data', array('php'))
*/
function file_copy($src, $des, $filter) {
	$dir = opendir($src);
	@mkdir($des);
	while (false !== ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($src . '/' . $file)) {
				file_copy($src . '/' . $file, $des . '/' . $file, $filter);
			} elseif (!in_array(substr($file, strrpos($file, '.') + 1), $filter)) {
				copy($src . '/' . $file, $des . '/' . $file);
			}
		}
	}
	closedir($dir);
}

/*
* 删除目录
* $path	string	目录位置 $clean	boolean	是否删除整个目录
* 如果参数 $clean 为 true，则不删除目录，仅删除目录内文件; 否则删除整个目录。
* load()->func('file'); rmdirs(IA_ROOT . '/test');
*/
function rmdirs($path, $clean = false) {
	if (!is_dir($path)) {
		return false;
	}
	$files = glob($path . '/*');
	if ($files) {
		foreach ($files as $file) {
			is_dir($file) ? rmdirs($file) : @unlink($file);
		}
	}
	return $clean ? true : @rmdir($path);
}

/*
* 上传文件到附件目录
* $file	array	上传的文件信息
* $type	string	文件保存类型
* $name	string	保存的文件名，如果为空则自动生成
* $is_wechat	boolean	是否上传到微信服务器
* 返回上传的结果，如果失败，返回错误数组，否则返回上传成功信息及保存的文件路径。
8 load()->func('file'); file_upload($_FILE['test'], 'image', 'test.png');
*/
function file_upload($file, $type = 'image', $name = '', $is_wechat = false) {
	$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
	if (empty($file)) {
		return error(-1, '没有上传内容');
	}
	if (!in_array($type, array('image', 'thumb', 'voice', 'video', 'audio'))) {
		return error(-2, '未知的上传类型');
	}

	global $_W;
	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	$ext = strtolower($ext);
		if (!$is_wechat) {
		$setting = $_W['setting']['upload'][$type];
		if (!in_array(strtolower($ext), $setting['extentions']) || in_array(strtolower($ext), $harmtype)) {
			return error(-3, '不允许上传此类文件');
		}
		if (!empty($setting['limit']) && $setting['limit'] * 1024 < filesize($file['tmp_name'])) {
			return error(-4, "上传的文件超过大小限制，请上传小于 {$setting['limit']}k 的文件");
		}
	}
	$result = array();
	if (empty($name) || $name == 'auto') {
		$uniacid = intval($_W['uniacid']);
		$path = "{$type}s/{$uniacid}/" . date('Y/m/');
		mkdirs(ATTACHMENT_ROOT . '/' . $path);
		$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $ext);

		$result['path'] = $path . $filename;
	} else {
		mkdirs(dirname(ATTACHMENT_ROOT . '/' . $name));
		if (!strexists($name, $ext)) {
			$name .= '.' . $ext;
		}
		$result['path'] = $name;
	}

	if (!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
		return error(-1, '保存上传文件失败');
	}

	$result['success'] = true;
	
	return $result; 
}

/*
* 获取指定某目录下指定后缀的随机文件名
* $dir	string	目录的绝对路径 $ext	string	文件后缀名
*
*/
function file_random_name($dir, $ext){
	do {
		$filename = random(30) . '.' . $ext;
	} while (file_exists($dir . $filename));

	return $filename;
}

/*
* 删除文件
* file_delete('test.png')
*/
function file_delete($file) {
	global $_W;
	if (empty($file)) {
		return FALSE;
	}
	if (file_exists(ATTACHMENT_ROOT . '/' . $file)) {
		@unlink(ATTACHMENT_ROOT . '/' . $file);
	}
	return TRUE;
}

/*
* 图像缩略处理
* $srcfile	string	需要缩略的图像 $desfile	string	缩略完成后的图像 $width	int	缩放宽度
* 如果图像缩略成功，则返回缩略图的地址，否则返回错误信息数组。
* file_image_thumb(IA_ROOT . '/test.png', IA_ROOT . '/test2.png', 500);
*/
function file_image_thumb($srcfile, $desfile = '', $width = 0) {
	global $_W;

	if (!file_exists($srcfile)) {
		return error('-1', '原图像不存在');
	}
	if (intval($width) == 0) {
		load()->model('setting');
		$width = intval($_W['setting']['upload']['image']['width']);
	}
	if (intval($width) < 0) {
		return error('-1', '缩放宽度无效');
	}

	if (empty($desfile)) {
		$ext = pathinfo($srcfile, PATHINFO_EXTENSION);
		$srcdir = dirname($srcfile);
		do {
			$desfile = $srcdir . '/' . random(30) . ".{$ext}";
		} while (file_exists($desfile));
	}

	$des = dirname($desfile);
		if (!file_exists($des)) {
		if (!mkdirs($des)) {
			return error('-1', '创建目录失败');
		}
	} elseif (!is_writable($des)) {
		return error('-1', '目录无法写入');
	}

		$org_info = @getimagesize($srcfile);
	if ($org_info) {
		if ($width == 0 || $width > $org_info[0]) {
			copy($srcfile, $desfile);
			return str_replace(ATTACHMENT_ROOT . '/', '', $desfile);
		}
		if ($org_info[2] == 1) { 			if (function_exists("imagecreatefromgif")) {
				$img_org = imagecreatefromgif($srcfile);
			}
		} elseif ($org_info[2] == 2) {
			if (function_exists("imagecreatefromjpeg")) {
				$img_org = imagecreatefromjpeg($srcfile);
			}
		} elseif ($org_info[2] == 3) {
			if (function_exists("imagecreatefrompng")) {
				$img_org = imagecreatefrompng($srcfile);
				imagesavealpha($img_org, true);
			}
		}
	} else {
		return error('-1', '获取原始图像信息失败');
	}
		$scale_org = $org_info[0] / $org_info[1];
		$height = $width / $scale_org;
	if (function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$img_dst = imagecreatetruecolor($width, $height)) {
		imagealphablending($img_dst, false);
		imagesavealpha($img_dst, true);
		imagecopyresampled($img_dst, $img_org, 0, 0, 0, 0, $width, $height, $org_info[0], $org_info[1]);
	} else {
		return error('-1', 'PHP环境不支持图片处理');
	}
	if ($org_info[2] == 2) {
		if (function_exists('imagejpeg')) {
			imagejpeg($img_dst, $desfile);
		}
	} else {
		if (function_exists('imagepng')) {
			imagepng($img_dst, $desfile);
		}
	}

	imagedestroy($img_dst);
	imagedestroy($img_org);

	return str_replace(ATTACHMENT_ROOT . '/', '', $desfile);
}

/*
* 图像裁切处理
* $src	string	原图像地址 $desfile	string	新图像地址 $width	int	要裁切的宽度 $height	int	要裁切的高度
* $position	int	开始裁切的位置， 按照九宫格1-9指定位置
* file_image_crop(IA_ROOT . '/test.png', IA_ROOT . '/test2.png', 50, 50);
*/
function file_image_crop($src, $desfile, $width = 400, $height = 300, $position = 1) {
	if (!file_exists($src)) {
		return error('-1', '原图像不存在');
	}
	if (intval($width) <= 0 || intval($height) <= 0) {
		return error('-1', '裁剪尺寸无效');
	}
	if (intval($position) > 9 || intval($position) < 1) {
		return error('-1', '裁剪位置无效');
	}

	$des = dirname($desfile);
		if (!file_exists($des)) {
		if (!mkdirs($des)) {
			return error('-1', '创建目录失败');
		}
	} elseif (!is_writable($des)) {
		return error('-1', '目录无法写入');
	}
		$org_info = @getimagesize($src);
	if ($org_info) {
		if ($org_info[2] == 1) { 			if (function_exists("imagecreatefromgif")) {
				$img_org = imagecreatefromgif($src);
			}
		} elseif ($org_info[2] == 2) {
			if (function_exists("imagecreatefromjpeg")) {
				$img_org = imagecreatefromjpeg($src);
			}
		} elseif ($org_info[2] == 3) {
			if (function_exists("imagecreatefrompng")) {
				$img_org = imagecreatefrompng($src);
			}
		}
	} else {
		return error('-1', '获取原始图像信息失败');
	}

		if ($width == '0' || $width > $org_info[0]) {
		$width = $org_info[0];
	}
	if ($height == '0' || $height > $org_info[1]) {
		$height = $org_info[1];
	}
		switch ($position) {
		case 0 :
		case 1 :
			$dst_x = 0;
			$dst_y = 0;
			break;
		case 2 :
			$dst_x = ($org_info[0] - $width) / 2;
			$dst_y = 0;
			break;
		case 3 :
			$dst_x = $org_info[0] - $width;
			$dst_y = 0;
			break;
		case 4 :
			$dst_x = 0;
			$dst_y = ($org_info[1] - $height) / 2;
			break;
		case 5 :
			$dst_x = ($org_info[0] - $width) / 2;
			$dst_y = ($org_info[1] - $height) / 2;
			break;
		case 6 :
			$dst_x = $org_info[0] - $width;
			$dst_y = ($org_info[1] - $height) / 2;
			break;
		case 7 :
			$dst_x = 0;
			$dst_y = $org_info[1] - $height;
			break;
		case 8 :
			$dst_x = ($org_info[0] - $width) / 2;
			$dst_y = $org_info[1] - $height;
			break;
		case 9 :
			$dst_x = $org_info[0] - $width;
			$dst_y = $org_info[1] - $height;
			break;
		default:
			$dst_x = 0;
			$dst_y = 0;
	}
	if ($width == $org_info[0]) {
		$dst_x = 0;
	}
	if ($height == $org_info[1]) {
		$dst_y = 0;
	}

	if (function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$img_dst = imagecreatetruecolor($width, $height)) {
		imagecopyresampled($img_dst, $img_org, 0, 0, $dst_x, $dst_y, $width, $height, $width, $height);
	} else {
		return error('-1', 'PHP环境不支持图片处理');
	}
	if (function_exists('imagejpeg')) {
		imagejpeg($img_dst, $desfile);
	} elseif (function_exists('imagepng')) {
		imagepng($img_dst, $desfile);
	}
	imagedestroy($img_dst);
	imagedestroy($img_org);
	return true;
}

/*
* 扫描指定目录中的文件，如果参数 $ext 不为空，则返回对应的文件类型，最后返回由文件名组成的数组。
* ^$filepath | string | 目录名称 | $subdir	int	是否搜索子目录 $ext	string	搜索扩展名称 $isdir	int	是否只搜索目录
* $md5	int	是否生成MD5验证码 $enforcement	int	是否开启强制模式
*load()->func('file'); $files = file_lists(IA_ROOT . '/web/common', 1, '.php', 0, 1); print_r($files);
*/
function file_lists($filepath, $subdir = 1, $ex = '', $isdir = 0, $md5 = 0, $enforcement = 0) {
	static $file_list = array();
	if ($enforcement) $file_list = array();
	$flags = $isdir ? GLOB_ONLYDIR : 0;
	$list = glob($filepath . '*' . (!empty($ex) && empty($subdir) ? '.' . $ex : ''), $flags);
	if (!empty($ex)) $ex_num = strlen($ex);
	foreach ($list as $k => $v) {
		$v = str_replace('\\', '/', $v);
		$v1 = str_replace(IA_ROOT . '/', '', $v);
		if ($subdir && is_dir($v)) {
			file_lists($v . '/', $subdir, $ex, $isdir, $md5);
			continue;
		}
		if (!empty($ex) && strtolower(substr($v, -$ex_num, $ex_num)) == $ex) {

			if ($md5) {
				$file_list[$v1] = md5_file($v);
			} else {
				$file_list[] = $v1;
			}
			continue;
		} elseif (!empty($ex) && strtolower(substr($v, -$ex_num, $ex_num)) != $ex) {
			unset($list[$k]);
			continue;
		}
	}
	return $file_list;
}
