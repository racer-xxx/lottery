<?php 
require '../lotConf.php';
$saeMysql = new SaeMysql();
$sql = "SELECT qihao as q,blue as b FROM ".mysql_table_data_ssq.' WHERE qihao LIKE "'.date('Y').'%"';
$thisYearTrend = $saeMysql->getData($sql);
// file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
//只取今年数据
//ORDER BY date DESC？
echo json_encode($thisYearTrend);
?>
