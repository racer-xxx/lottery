<?php
if(!isset($_SESSION))
	session_start();
header('P3P: CP="CAO PSA OUR"');
require 'statistics.php';
$saeMysql = new SaeMysql();
//根据来源查询评分信息（有多少人评分，平均评分）
$sql = "SELECT COUNT(`user`) AS n,SUM(`level`) AS s FROM advices WHERE source LIKE '%".source."%' AND site LIKE '%".site."%'";
$advs = $saeMysql->getLine($sql);
// file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
$ren = isset($advs['n']) ? ($advs['n'] == 0 ? 1 : intval($advs['n'])) : 1;
$avg = number_format((isset($advs['s']) ? ($advs['s'] == 0 ? 5 : $advs['s']) : 5)/$ren,1);
?>
<!DOCTYPE html>
<html>
<head>
<title>乐多彩|信息分享,你我共赢</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=6;IE=7" /><!-- 资源分开读取，突破浏览器单域名限制，更快加载 -->
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://lib.sinaapp.com/js/prototype/1.7.0.0/prototype.min.js"></script>
<script type="text/javascript" src="http://lib.sinaapp.com/js/bootstrap/latest/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/highcharts.js"></script><!-- sina的有兼容性问题 -->
<!--[if IE]>
	<script type="text/javascript" src="/js/excanvas.compiled.js"></script>
	<script type="text/javascript" src="/js/flotr2.ie.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/flotr2.min.js"></script>
<link href="http://lib.sinaapp.com/js/bootstrap/latest/css/bootstrap.min.css" rel="stylesheet">
<link href="/style/ssqiu.css" rel="stylesheet">
<script type="text/javascript" src="/js/ssqiu.js"></script>
</head>
	<body>		
	<div class="row" style='margin: 0 auto;width:940px;'>
		<!-- modal部分开始 -->
			<!-- 公共消息开始 -->
				<div class="modal hide fade" id="msgModal">
				    <div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal">×</button>	
				    	<h4 style="float: left;" id="msgModalTitle">消息标题</h4>				    
				    </div>
				    <div class="modal-body" style="clear: both;">			    		
			    		<div><h5 id='msgModalContent'>消息内容</h5></div>				    
			   		</div>
				    <div class="modal-footer"><a href="#" class="btn btn-primary" data-dismiss="modal">好的</a></div>
			   </div>
			<!-- 公共消息结束 -->
			<!-- advc开始 -->
			    <div class="modal hide fade" id="advcModal">
				    <div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal">×</button>	
				    	<h4 style="float: left;">Thank you!感谢评价和建议</h4>				    
				    </div>
				    <div class="modal-body" style="clear: both;">			    		
			    		<div id='advcRmk'>
			    			<h5>请评分(<?php echo $ren;?>人评,得分<?php echo $avg;?>/5)</h5><div id="starDiv"></div>
			    			<h5>意见和建议(Suggestions):</h5><h5><textarea rows=10 cols=40 id='adviceTxt'></textarea></h5>
			    			<div class="alert alert-error" id="advcErrMsg" style="display: none;">请先评分吧!</div>
				    	</div>				    
			   		</div>
				    <div class="modal-footer">
					 	<a href="#" class="btn btn-primary" id="sdAdvc">发送</a>
					    <a href="#" class="btn" data-dismiss="modal">算了</a>					    
					</div>
			   </div>
			<!-- advc结束 -->
			<!-- ckht开始 -->
			    <div class="modal hide fade" id="chkHit" style="width:610px;">
				    <div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal">×</button>	
				    	<h4 style="float: left;">中奖自助查询.每次最多查询5注,祝您高中!</h4>				    
				    </div>
				    <div class="modal-body" style="clear: both;" id="toChkDiv">			    		
			    		<h5>注:期号为大于等于2003001的7位半角数字,每期只能选6红1蓝.</h5>	
			    		<div class="alert alert-error" id="ckhtErrMsg" style="display: none;">!</div>		    		
			   		</div>
				    <div class="modal-footer">
					 	<a href="#" class="btn btn-primary" id="sdChk">查询</a>
					    <a href="#" class="btn" data-dismiss="modal">算了</a>					    
					</div>
			   </div>
			<!-- ckht结束 -->
		<!-- modal部分结束 -->
        <div class="span12 well pricehover" style='border: 0px;margin:0 auto;'>
			<div id="welcome_res">
				<span id="welcome_qi" title="点击更新最新开奖结果">正在加载最新开奖结果...</span>
				<span id="welcome_num" title="点击更新最新开奖结果">01&nbsp;02&nbsp;03&nbsp;04&nbsp;05&nbsp;06&nbsp;07</span>
				<a class="btn btn-primary btn-medium" href="#chkHit" data-toggle="modal">中奖查询</a>
				<!-- a class="btn btn-primary btn-medium" href="#simBet" data-toggle="modal">模拟投注</a-->
				<a class="btn btn-primary btn-medium" href="#advcModal" data-toggle="modal"><i class="icon-comment"></i>提意见</a>
			</div>
		    <ul class="nav nav-pills" style="margin: 0 auto;text-align:center;">
			    <li><a href="#" id="bfre"><i class="icon-signal"></i>蓝球频率</a></li>
			    <li><a href="#" id="rfre"><i class="icon-signal"></i>红球频率</a></li>
			    <li><a href="#" id="btrd"><i class="icon-resize-full"></i>蓝球走势</a></li>
				<li><a href="#" id="rtrd"><i class="icon-resize-full"></i>红球走势</a></li>			    
			    <li><a href="#" id="recm"><i class="icon-pencil"></i>智选号</a></li>
			    <li><a href="#" id="labl"><i class="icon-book"></i>术语标签云</a></li>
			    <li><a href="#" id="pred"><i class="icon-screenshot"></i>砖家说</a></li>
			    <li><a href="#" id="abou"><i class="icon-user"></i>关于我</a></li>
		    </ul>	
		    <div id="contentShow">
			</div>
			<div id="copyInfo">
				<p class="help-block">Copyright © 2012 lot-tery.com All Rights Reserved. Proudly Powered by <a href="http://www.wanzk.cn" target="_blank">Jetson Wans</a></p>
			</div>			
        </div>        	
	</div>
	<script type="text/javascript">
			showContent(loadingSpan);
			jQuery("#bfre").trigger("click");
			jQuery("#toChkDiv > h5").after(ckdivs);
			jQuery.get("/data/welcome.php","",function(data){
				jQuery("#welcome_qi").html(data.wds);
				jQuery("#welcome_num").html(data.res);
			},'json');
	</script>
<?php 
	require 'adnstat.php';
?>	
</body>
</html>