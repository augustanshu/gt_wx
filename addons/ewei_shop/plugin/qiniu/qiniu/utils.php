<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
function Qiniu_Encode($str)
{
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($str));
}
function Qiniu_Decode($str)
{
	$find = array('-', '_');
	$replace = array('+', '/');
	return base64_decode(str_replace($find, $replace, $str));
}