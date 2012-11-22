<?php
if(!isset($_SESSION))
	session_start();
	//公共部分
	require '../statistics.php';
	$client = new statisticsClass();
	$saeMysql = new SaeMysql();	
	if (isset($_POST['cx']) && isset($_POST['mr'])){//查询某期中奖情况
		$msg = array('state'=>0,'msg'=>'查询数据出错了!');
		$m = $client->filterSQLChars(trim($_POST['mr']));
		$mrs = explode(';', $m);
		$hits = 0;
		if (is_array($mrs) && count($mrs) > 0){
			$tocheckArr = array();
			foreach ($mrs as $mrv) {
				$mr = explode('|', $mrv);
				$flag = false;
				if (preg_match('/^20[0-1][0-9][0-1][0-9][0-9]/', $mr[0]) == 1){
					if (intval($mr[2]) > 0 && intval($mr[2]) < 17){
						$arrs = explode(',', $mr[1]);
						if (count($arrs) == 6){
							$tempFlag = true;
							foreach ($arrs as $value)
								if (intval($value) < 1 || intval($value) > 33)
									$tempFlag = false;
							if ($tempFlag)
								$flag = true;
						}
					}
				}
				if ($flag)
					$tocheckArr[$hits++] = $mr[0].','.$mr[1].','.$mr[2];
			}
			if (count($tocheckArr) > 0){
				$hitResult = array();
				foreach ($tocheckArr as $key => $value)
					$qihaos .= substr($value, 0, 8);
				$qihaos = substr($qihaos, 0, strlen($qihaos)-1);
				$sql = "SELECT qihao,red_1,red_2,red_3,red_4,red_5,red_6,blue FROM ".mysql_table_data_ssq." WHERE qihao IN ($qihaos)";
				$rs = array();
				$aaa = $saeMysql->getData($sql);
// 				file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
				if(is_array($aaa) && count($aaa) > 0){
					foreach ($aaa as $chfv) {
						$rs[$chfv['qihao']]['r'] = array(intval($chfv['red_1']),intval($chfv['red_2']),intval($chfv['red_3']),intval($chfv['red_4']),intval($chfv['red_5']),intval($chfv['red_6']));
						$rs[$chfv['qihao']]['b'] = $chfv['blue'];
					}
				}				
				foreach ($tocheckArr as $nokey=>$rnb) {
					$temp = explode(',', $rnb);
					$tempRedRst = 0;
					$coloredNum = '';
					for ($i = 1;$i < 7;$i++){
						if (in_array(intval($temp[$i]), isset($rs[$temp[0]]['r']) ? $rs[$temp[0]]['r'] : array())){
							$tempRedRst++;
							$coloredNum .= '<font color="red">'.(($temp[$i] < 10) ? '0'.$temp[$i] : $temp[$i]).'</font>,';
						}
						else
							$coloredNum .= (($temp[$i] < 10) ? '0'.$temp[$i] : $temp[$i]).',';
					}
					$r = (intval($temp[7])==intval($rs[$temp[0]]['b']));
					$c = $r ? $coloredNum.'<font color="blue">'.(($temp[7] < 10) ? '0'.$temp[7] : $temp[7]).'</font>' : $coloredNum.(($temp[7] < 10) ? '0'.$temp[7] : $temp[7]);
					switch ($tempRedRst){
						case 6:$hitResult[$nokey] = array('q'=>$temp[0],'r'=> $r ? 1 : 2,'c'=>$c);break;
						case 5:$hitResult[$nokey] = array('q'=>$temp[0],'r'=> $r ? 3 : 4,'c'=>$c);break;
						case 4:$hitResult[$nokey] = array('q'=>$temp[0],'r'=> $r ? 4 : 5,'c'=>$c);break;
						case 3:$hitResult[$nokey] = array('q'=>$temp[0],'r'=> $r ? 5 : 0,'c'=>$c);break;
						default:$hitResult[$nokey] = array('q'=>$temp[0],'r'=> $r ? 6 : 0,'c'=>$c);break;
					}
				}
				$msg = array('state'=>1,'msg'=>$hitResult);
			}
		}
	}
echo json_encode($msg);
?>