<?php

/**
 * @desc 重新整理，适合本站
 * @version 2012.11.22
 */

require 'lotConf.php';

class statisticsClass {		
	
	private $_saeMysql;
	
	//实例化
	function __construct(){
		$this->_saeMysql = new SaeMysql();
	}
	
	/**
	 * @desc 微博API调用统计方法
	 * @param string $api
	 * @param string $caller 微博用户id
	 * @param string $ip
	 * @param int 	 $error_code
	 * @param string $note
	 * @param string $leftCounts
	 * @param string $src
	 */		
	public function countAPI($apiArrayJson){
		$apiArray = json_decode($apiArrayJson,true);
		$sql = "INSERT INTO api_count (`api`,`caller`,`call_time`,`call_ip`,`error_code`,`note`,`left_count`,`source`)".
				" VALUES('".$apiArray["api"]."','".$apiArray["caller"]."',NOW(),'".$apiArray["ip"]."','".
				$apiArray["error_code"]."','".$apiArray["note"]."','".$apiArray["leftCounts"]."','".$apiArray["src"]."')";
		$this->_saeMysql->runSql($sql);
// 		file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		return $this->_saeMysql->affectedRows() != -1 ? 1 : 0;
	}	
	
	/**
	 * @desc 插入授权用户信息或者更新其状态
	 * @param string $userInfoArrayJson
	 */
	public function insertUserUpdateStatus($userInfoArrayJson){
		$uiArr = json_decode($userInfoArrayJson,true);
		$last_access_time = '';
		$last_access_ip = '';
		$loginInfo = '';
		$mode = 'UPDATE';
		
		$sql = "SELECT last_access_time,last_access_ip FROM weibo_users WHERE weibo_id = '".$uiArr['weibo_id']."'";
		$exists = $this->_saeMysql->getLine($sql);		
// 		file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		if ($exists){			
			$loginInfo = $exists['last_access_time'].'@'.($this->ip2add($exists['last_access_ip']));
			$sql = "UPDATE weibo_users SET last_access_time = NOW(),last_access_ip = '".$uiArr["last_access_ip"]."',access_times = access_times + 1,screen_name = '".
					$uiArr["screen_name"]."',name = '".$uiArr["name"]."',province = '".$uiArr["province"]."',city = '".$uiArr["city"]."',location = '".
					$uiArr["location"]."',description = '".$uiArr["description"]."',url = '".$uiArr["url"]."',profile_image_url = '".$uiArr["profile_image_url"].
					"',cover_image = '".$uiArr["cover_image"]."',profile_url = '".$uiArr["profile_url"]."',domain = '".$uiArr["domain"]."',weihao = '".
					$uiArr["weihao"]."',gender = '".$uiArr["gender"]."',followers_count = '".$uiArr["followers_count"]."',friends_count = '".
					$uiArr["friends_count"]."',statuses_count = '".$uiArr["statuses_count"]."',favourites_count = '".$uiArr["favourites_count"].
					"',created_at = '".$uiArr["created_at"]."',following = '".$uiArr["following"]."',allow_all_act_msg = '".$uiArr["allow_all_act_msg"].
					"',geo_enabled = '".$uiArr["geo_enabled"]."',verified = '".$uiArr["verified"]."',verified_type = '".$uiArr["verified_type"]."',remark = '".
					$uiArr["remark"]."',status = '".$uiArr["status"]."',allow_all_comment = '".$uiArr["allow_all_comment"]."',avatar_large = '".
					$uiArr["avatar_large"]."',verified_reason = '".$uiArr["verified_reason"]."',follow_me = '".$uiArr["follow_me"]."',online_status = '".
					$uiArr["online_status"]."',bi_followers_count = '".$uiArr["bi_followers_count"]."',lang = '".$uiArr["lang"]."',source = '".$uiArr["source"].
					"',star = '".$uiArr["star"]."' WHERE weibo_id = '".$uiArr['weibo_id']."'";
			$this->_saeMysql->runSql($sql);
// 			file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
		}
		else{
			$sql = "INSERT INTO weibo_users VALUES ('".$uiArr['weibo_id']."',NOW(),NOW(),'".$uiArr['last_access_ip']."',1,'".$uiArr['screen_name']."','".
					$uiArr['name']."','".$uiArr['province']."','".$uiArr['city']."','".$uiArr['location']."','".$uiArr['description']."','".$uiArr['url'].
					"','".$uiArr['profile_image_url']."','".$uiArr['cover_image']."','".$uiArr['profile_url']."','".$uiArr['domain']."','".$uiArr['weihao'].
					"','".$uiArr['gender']."','".$uiArr['followers_count']."','".$uiArr['friends_count']."','".$uiArr['statuses_count']."','".
					$uiArr['favourites_count']."','".$uiArr['created_at']."','".$uiArr['following']."','".$uiArr['allow_all_act_msg']."','".
					$uiArr['geo_enabled']."','".$uiArr['verified']."','".$uiArr['verified_type']."','".$uiArr['remark']."','".$uiArr['status']."','".
					$uiArr['allow_all_comment']."','".$uiArr['avatar_large']."','".$uiArr['verified_reason']."','".$uiArr['follow_me']."','".
					$uiArr['online_status']."','".$uiArr['bi_followers_count']."','".$uiArr['lang']."','".$uiArr['source']."','".$uiArr['star']."')";
			$this->_saeMysql->runSql($sql);
// 			file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			$mode = $this->_saeMysql->affectedRows() != -1 ? 'INSERT' : 'ERROR';
		}		
		//插入登录积分信息
		$this->insertIntegral($uiArr['weibo_id'],$uiArr['last_access_ip'],$uiArr['source'],1/*登录*/);
		return ($loginInfo != '') ? $loginInfo : $mode;
	}
	
	/**
	 * @desc ip转为地址信息
	 * @param string $ip
	 * @return string $add
	 */
	public function ip2add($ip){
		$add = '';
		if ($ip != ''){
			$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip;
			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//可以将返回结果赋值给变量
			$rr = curl_exec($ch);
			$rr = substr($rr, stripos($rr, '{'));
			$rr = substr($rr, 0, strlen($rr)-1);
			$rr = json_decode($rr,true);
			if (isset($rr['ret']) && $rr['ret'] == 1)
				$add = $rr['city'];
			curl_close($ch);
		}
		return $add;
	}
	
	/**
	 * @desc 插入更新积分信息
	 * @param string $uid
	 * @param string $ip
	 * @param string $src
	 * @param string $type
	 * @param string $integ 可选，当类型为0（扣积分）时避开类型判断直接用积分
	 * @return 1成功0失败-1积分不变
	 */
	public function insertIntegral($uid,$ip,$src,$type,$integ = 0){
		if ($integ == 0){
			$integral = 0;
			switch ($type){
				case 1:$integral = 10;break;//登录
				case 2:$integral = 5;break;//签到
				case 4:$integral = 50;break;//反馈
				case 7:$integral = 100;break;//关注
				case 8:$integral = 50;break;//分享到微博
				case 9:$integral = 200;break;//邀请好友
			}
		}
		else
			$integral = $integ;//0投注扣分3答题得分5抽奖6其他 
		if ($integral != 0){
			$sql = "INSERT INTO integral_detail (`user_id`,`created_time`,`ip`,`integral`,`source`,`type`) VALUES('".$uid."',NOW(),'".$ip."','".$integral.
					"','".$src."','".$type."')";
			$this->_saeMysql->runSql($sql);
// 			file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			//更新用户总积分表
			$sql = "SELECT * FROM integral WHERE user_id = '".$uid."' AND source = '".$src."'";
			$advs = $this->_saeMysql->getLine($sql);
// 			file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			if ($advs)
				$sql = "UPDATE integral SET integral_sum = integral_sum + '".$integral."' ,updated_time = NOW() WHERE user_id = '".$uid."' AND source = '".$src."'";
			else
				$sql = "INSERT INTO integral (`user_id`,`created_time`,`updated_time`,`integral_sum`,`source`) VALUES ('".$uid."',NOW(),NOW(),'".
						$integral."','".$src."')";
			$this->_saeMysql->runSql($sql);
// 			file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$this->_saeMysql->errmsg()."\r\n",FILE_APPEND);
			return $this->_saeMysql->affectedRows() != -1 ? 1 : 0;
		}
		else
			return -1;
	}
	
	/**
	 * 过滤
	 * @param string $input
	 * @return string
	 */
	public function filterSQLChars($input){
		$input = strip_tags($input);
		$input = addslashes($input);//phpinfo里面magic_quotes_gpc 为 off，需要手动转义
// 		$input = htmlspecialchars($input);
// // 		$input = str_replace("&", "-and-",$input);
// 		$input = str_replace("<", "-小于号-",$input);
// 		$input = str_replace(">", "-大于号-",$input);
// 		$input = str_replace("\\\"", "-三个左斜杠-",$input);
// 		$input = str_replace("\\''", "-两个左斜杠-",$input);
// // 		$input = str_replace('/', "-右斜杠-",$input);
// // 		$input = str_replace('"', "-双引号-",$input);
// 		$input = str_replace("\"", "-左斜杠-",$input);
// 		$input = str_replace("'", "-单引号-",$input);
// // 		$input = str_replace("#", "-井号-",$input);
// 		$input = str_replace("~", "-浪号-",$input);
// 		$input = str_replace("`", "-反引号-",$input);
// 		$input = str_replace("@", "-艾特-",$input);
// // 		$input = str_replace("$", "-美刀-",$input);
// 		$input = str_replace("%", "-百分号-",$input);
// // 		$input = str_replace("^", "-上破折号-",$input);
// // 		$input = str_replace("*", "-星号-",$input);
		return $input;
	}
}
?>