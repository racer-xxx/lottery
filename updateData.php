<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SSQIU数据生成</title>
</head>
<body>
<?php

require 'lotConf.php';

/**
 * @desc 每期开奖后执行，读取网站开奖数据，生成必须数据文件
 * @version 2012.05.12
 * @author wans88
 */

class dataUpdateClass{
	
	private $_ssqDataDir;
	private $_saeMysql;
	private $_rawData;
	
	/**
	 * @desc 构造方法
	 * @version 2012.11.08
	 */
	function construct(){
		$this->_ssqDataDir = 'data/';
		$this->_saeMysql = new SaeMysql();
		$this->_rawData = file(ssqFile);
		if (!is_array($this->_rawData))
			exit('获取数据失败！');
	}
	
	
	/**
	 * @desc 根据原始数据，执行存储过程保存原始数据到数据库
	 * @version 2012.11.08
	 */
	function genRawDataByProc(){
		$msg = '';
		if ($this->_rawData){
			$flag = true;
			$k = 0;			
			$insertAffectedRows = array();
			$errMsgs = array();
			$sql = "DELETE FROM ".mysql_table_data_ssq;
			$this->_saeMysql->runSql($sql);
// 			file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			foreach ($this->_rawData as $dataValue) {
				$sql = "INSERT INTO `".mysql_table_data_ssq."` VALUES (\"".str_replace(' ', '","', $dataValue)."\")";
				$this->_saeMysql->runSql($sql);
// 				file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
				$insertAffectedRows[] = $this->_saeMysql->affectedRows();
				$errMsgs[] = $this->_saeMysql->errmsg();
			}
			foreach ($insertAffectedRows as $retKey => $retValue) {
				if ($retValue == '-1'){
					$msg .= "第".$retKey."行插入失败;";
					echo $errMsgs[$retKey];
					$flag = false;
				}
			}
			if ($flag)
				$msg = '获取原始数据并保存到数据库成功!';
		}		
		else 
			$msg = '获取原始数据失败，请检查网络连接！';
		return $msg;
	}	
	
	
	/**
	 * @desc 根据已有的数据插入数据到数据库
	 * @version 2012.11.08
	 */
	function redBlueTimesByProc(){
		$msg = '';
		$sql = "DELETE FROM ".mysql_table_data_ssq_nums_red;
		$this->_saeMysql->runSql($sql);
// 		file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		$insertAffectedRows = array();
		$errMsgs = array();
		foreach ($this->countRed() as $redK => $redV) {
			$sql3 = "INSERT INTO `".mysql_table_data_ssq_nums_red."` (`red_num_id`,`nums`) VALUES(".$redK.",".$redV.")";	
			$this->_saeMysql->runSql($sql3);
// 			file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			$insertAffectedRows['r'][] = $this->_saeMysql->affectedRows();
			$errMsgs['r'][] = $this->_saeMysql->errmsg();
		}
		foreach ($this->countBlue() as $blueK => $blueV) {
			$sql4 = "INSERT INTO `".mysql_table_data_ssq_nums_blue."` (`blue_num_id`,`nums`) VALUES(".$blueK.",".$blueV.")";
			$this->_saeMysql->runSql($sql4);
// 			file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			$insertAffectedRows['b'][] = $this->_saeMysql->affectedRows();
			$errMsgs['b'][] = $this->_saeMysql->errmsg();
		}
		$flag = true;
		foreach ($insertAffectedRows['r'] as $redK1 => $redV1) {
			if ($redV1 == -1){
				$flag = false;
				$msg .= $redK1.'号红球插入红球频数表失败！errMsg:'.$errMsgs['r'][$redK1];
			}
		}
		foreach ($insertAffectedRows['b'] as $blueK1 => $blueV2) {
			if ($blueV2 == -1){
				$flag = false;
				$msg .= $blueK1.'号蓝球插入红球频数表失败！errMsg:'.$errMsgs['b'][$redK1];
			}
		}
		if ($flag)
			$msg = '红蓝球频数表插入数据成功！';
		return $msg;
	}
	
	/**
	 * @desc 根据已有的数据插入数据到数据库
	 * @version 2012.11.08
	 */
	function redBlueTimesYearlyByProc(){
		$msg = '';
		$sql = array();
		$redCount = array();
		$blueCount = array();
		$sql = "DELETE FROM ".mysql_table_data_ssq_nums_yearly;
		$this->_saeMysql->runSql($sql);
// 		file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		$insertAffectedRows = array();
		$errMsgs = array();
		for ($i = 2003;$i <= intval(date('Y'));$i++){
			$redCount[$i] = $this->countRed($i);
			$blueCount[$i] = $this->countBlue($i);
			$sql = "INSERT INTO tbl_ssq_nums_yearly VALUES (".$i.",".implode(',', $redCount[$i]).",".implode(',', $blueCount[$i]).")";
			$this->_saeMysql->runSql($sql);
// 			file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			$insertAffectedRows[$i] = $this->_saeMysql->affectedRows();
			$errMsgs[$i] = $this->_saeMysql->errmsg().'相关sql语句:'.$sql;
		}
		$flag = true;
		foreach ($insertAffectedRows as $kk => $vv) {
			if ($vv == -1){
				$flag = false;
				$msg .= $kk.'年度频数表更新失败！';
				echo $errMsgs[$kk].'<br />';
			}
		}
		if ($flag)
			$msg = '红蓝球年度频数表更新成功！';
		return $msg;
	}
		
	
	/**
	 * @desc 获取红球频数
	 * @version 2012.11.08
	 */
	function countRed($year=''){		
		$sql = "SELECT red_1,red_2,red_3,red_4,red_5,red_6 FROM ".mysql_table_data_ssq." WHERE qihao LIKE '".$year."%'";	
		for ($i = 1;$i < 34; $i++)
			$redCount[$i] = 0;
		foreach ($this->_saeMysql->getData($sql) as $value) {
			$red1 = intval($value['red_1']);
			$red2 = intval($value['red_2']);
			$red3 = intval($value['red_3']);
			$red4 = intval($value['red_4']);
			$red5 = intval($value['red_5']);
			$red6 = intval($value['red_6']);
			$redCount[$red1]++;
			$redCount[$red2]++;
			$redCount[$red3]++;
			$redCount[$red4]++;
			$redCount[$red5]++;
			$redCount[$red6]++;
		}
// 		file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		return $redCount;
	}
	
	/**
	 * @desc 获取蓝球频数
	 * @version 2012.11.08
	 */
	function countBlue($year=''){
		$sql = "SELECT blue FROM ".mysql_table_data_ssq." WHERE qihao LIKE '".$year."%'";
		for ($i = 1;$i < 17; $i++)
			$blueCount[$i] = 0;
		foreach ($this->_saeMysql->getData($sql) as $value) {
			$blue = intval($value['blue']);
			$blueCount[$blue]++;
		}
// 		file_put_contents('updateDataSQLLog.txt', 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		return $blueCount;
	}
}

/**
 * 进入页面选择操作
 */
if (isset($_GET['p']) && $_GET['p'] == 'db'){
	$duc = new dataUpdateClass();
	$duc->construct();
	$msg1 = $duc->genRawDataByProc();
	$msg2 = $duc->redBlueTimesByProc();
	$msg3 = $duc->redBlueTimesYearlyByProc();
	echo 'OK!';
}
else{
?>
<a name="generateData" href="?p=db"/>直接写入数据库<br/>
<?php
}
?>
</body>
</html>