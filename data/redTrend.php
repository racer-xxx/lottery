<?php 
require '../lotConf.php';
$saeMysql = new SaeMysql();
$sql = "SELECT qihao as q,red_1 as r1,red_2 as r2,red_3 as r3,red_4 as r4,red_5 as r5,red_6 as r6 FROM ".
		mysql_table_data_ssq.' WHERE qihao LIKE "'.date('Y').'%"';
$thisYearTrend = $saeMysql->getData($sql);
// file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
//只取今年数据
//ORDER BY date DESC？
echo json_encode($thisYearTrend);
?>
