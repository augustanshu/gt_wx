<?php 
/**
 * 
 * 规则回复函数
 */
defined('IN_IA') or exit('Access Denied');

/**规则查询
*$condition	string	查询条件where后的内容
*$params	array	查询参数
*$pindex	int	当前页码
*$psize	int	分页大小
*$total	int	总记录数
*如果查询条件非空，当前页码不为0，则依照此条件进行查询。
*/
function reply_search($condition = '', $params = array(), $pindex = 0, $psize = 10, &$total = 0) {
	if (!empty($condition)) {
		$where = "WHERE {$condition}";
	}
	$sql = 'SELECT * FROM ' . tablename('rule') . $where . " ORDER BY status DESC, displayorder DESC, id ASC";
	if ($pindex > 0) {
				$start = ($pindex - 1) * $psize;
		$sql .= " LIMIT {$start},{$psize}";
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('rule') . $where, $params);
	}
	return pdo_fetchall($sql, $params);
}

/*
*查询单条规则及其下的所有关键字。
*$id	int	规则的id字段值
*如果查询出的规则为空，显示NULL。否则显示其关键字
*/
function reply_single($id) {
	$result = array();
	$id = intval($id);
	$result = pdo_fetch("SELECT * FROM " . tablename('rule') . " WHERE id = :id", array(':id' => $id));
	if (empty($result)) {
		return $result;
	}
	$result['keywords'] = pdo_fetchall("SELECT * FROM " . tablename('rule_keyword') . " WHERE rid = :rid", array(':rid' => $id));
	return $result;
}

/*
*查询满足条件的所有规则关键字
*$condition	string	查询条件where后的内容
*$params	array	查询参数
*$pindex	int	当前页码
*$psize	int	分页大小
*$total	int	总计录
*当查询条件不为空，当前页码值不为0时，返回按照相关条件查询出的结果。
*/
function reply_keywords_search($condition = '', $params = array(), $pindex = 0, $psize = 10, &$total = 0) {
	if (!empty($condition)) {
		$where = " WHERE {$condition} ";
	}
	$sql = 'SELECT * FROM ' . tablename('rule_keyword') . $where . ' ORDER BY displayorder DESC, `type` ASC, id DESC LIMIT 3';
	if ($pindex > 0) {
				$start = ($pindex - 1) * $psize;
		$sql .= " LIMIT {$start},{$psize}";
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('rule_keyword') . $where, $params);
	}
	return pdo_fetchall($sql, $params);
}

