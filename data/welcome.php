<?php 
if(!isset($_SESSION))
	session_start();
	$info = array('wel'=>'','wds'=>'获取信息失败','res'=>'01&nbsp;02&nbsp;03&nbsp;04&nbsp;05&nbsp;06&nbsp;07');
	require_once '../lotConf.php';
	$saeMysql = new SaeMysql();
// 	$welconme_info = isset($_SESSION['welcome_info']) ? trim($_SESSION['welcome_info']) : '';
	$sql = "SELECT qihao,date,red_1,red_2,red_3,red_4,red_5,red_6,blue FROM ".mysql_table_data_ssq." ORDER BY qihao DESC LIMIT 1";
	$r = $saeMysql->getLine($sql);
	$md = str_replace('-', '月', substr($r['date'], 5));
	$words = '<b>'.$r['qihao'].'期('.$md.'日)</b>开奖结果';
	$newestResult = '';
	for ($i = 2;$i < 8; $i++)
		$newestResult .= (($r['red_'.($i-1)] < 10)?'0'.$r['red_'.($i-1)]:$r['red_'.($i-1)])."\r";
	$newestResult .= ($r['blue'] < 10)?'0'.$r['blue']:$r['blue'];
	$info = array('wel'=>'','wds'=>$words,'res'=>$newestResult);
echo json_encode($info);
?>