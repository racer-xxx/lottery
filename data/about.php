<?php 
if(!isset($_SESSION))
	session_start();
	//公共部分	
	require '../statistics.php';
	$client = new statisticsClass();
	//关注
	if (isset($_POST['fl']) && trim($_POST['fl'])==1){
// 		$followRet = $c->follow_by_id('2289658027');	
// 		$client->countAPI(json_encode(array('api'=>'follow_by_id', 'caller'=>$_SESSION['sina_oauth2']["user_id"], 'ip'=>$_SERVER['REMOTE_ADDR'],
// 				'error_code'=>isset($followRet['error_code']) ? $followRet['error_code'] : 0,'note'=>$_SERVER['REQUEST_URI'],
// 				'leftCounts'=>json_encode($followRet), 'src'=>source)));
// 		if ($followRet['error_code'] == 0){
// 			$msg = array('state'=>1,'msg'=>'感谢你的关注!同时恭喜您获得100积分!再次感谢您的鼓励!');
// 			$client->insertIntegral($_SESSION['sina_oauth2']["user_id"], $_SERVER['REMOTE_ADDR'], source, 7);
// 		}
// 		else
// 			$msg = array('state'=>0,'msg'=>$followRet['error']);
	}
	else{
		$msg['state'] = -1;
// 		$isFollowedMe = $c->is_followed_by_id($_SESSION['sina_oauth2']["user_id"],'2289658027');//关注wans88了？
// 		$client->countAPI(json_encode(array('api'=>'is_followed_by_id', 'caller'=>$_SESSION['sina_oauth2']["user_id"], 'ip'=>$_SERVER['REMOTE_ADDR'],
// 				'error_code'=>isset($isFollowedMe['error_code']) ? $isFollowedMe['error_code'] : 0,'note'=>$_SERVER['REQUEST_URI'],
// 				'leftCounts'=>json_encode($isFollowedMe), 'src'=>source)));		
		?>		
		<div id="aboutMyIntst">
			<dl class="dl-horizontal">
				<dt>我行我素</dt>
					<dd>淡泊名利，置身世外，不求闻达于诸侯，但求极尽吾所致。好编程，爱算法，看赛车，玩游戏，逛论坛，喜彩票，偶旅游，陪女友~_~</dd><br />
				<dd>不愤世嫉俗亦非阿谀谄媚，我行我素而相处甚欢。得些许基友，美女一枚，上班各自发挥，闲时小聚浅酌，人生似如此，岂不快哉！</dd><br />
				<dd>诚恳接受建议和意见，毕竟涉世未深，所见甚浅，还望海涵。</dd><br /><br />
				<dt>一言概之</dt><dd>现致力于敲代码。07届合工大校友，拿过一等奖学金，全国英语竞赛三等奖，混过班干、学生会部长，没太多才可也不算白板。
						总之，拿奖不少，当官不小，没学多少，一棵小草。欢迎拔苗浇花勿除“草”!</dd>
			</dl>
		</div>
		<a class="btn btn-primary btn-middle" id="mailtome" href="mailto:me@wanzk.com">给我发邮件</a>
		<?php 
// 			if (!isset($isFollowedMe['target']['following']) || $isFollowedMe['target']['following'] == false)//未关注
// 				echo '<a class="btn btn-primary btn-middle" id="followme">关注wans88</a>';
		?>		
	<?php
	}
if ($msg['state'] != -1)
	echo json_encode($msg);
?>