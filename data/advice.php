<?php
if(!isset($_SESSION))
	session_start();
	//公共部分
	require '../statistics.php';
	$client = new statisticsClass();
	$saeMysql = new SaeMysql();	
	$msg = array('state'=>0,'msg'=>'咦，提交出错了，检查输入后再试试吧！');
	//用户发送建议和意见
	$advice = $client->filterSQLChars(trim($_POST['adv']));
	if (mb_strlen($advice) > 2000)
		$msg = array('state'=>0,'msg'=>'哇,建言蛮多,多谢指正!不过一次最多提交2000字,分多次提交吧(*^__^*)');
	else{
		$sql = "INSERT INTO advices (`user`,`ip`,`level`,`advices`,`created_time`,`source`,`site`) VALUES ('".user.
				"','".$_SERVER["REMOTE_ADDR"]."','".intval($_POST["level"])."','".$advice."',NOW(),'".source."','".site."')";
		$saeMysql->runSql($sql);	
// 		file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
		$client->insertIntegral(user, $_SERVER["REMOTE_ADDR"], source, 4);
		$msg = array('state'=>1,'msg'=>'恭喜您获得50积分!再次感谢您的鼓励!');
	}
echo json_encode($msg);
?>