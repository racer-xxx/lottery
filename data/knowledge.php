<?php 
if(!isset($_SESSION))
	session_start();
require '../statistics.php';
	$saeMysql = new SaeMysql();
	$stsc = new statisticsClass();	
	if (isset($_POST['sa']) && trim($_POST['sa']) == 'y'){//sendAnswer
		$msg = array('state'=>0,'msg'=>'参数错误，刷新页面重试吧~_~');
		if (is_array($_POST) && count($_POST) == 4){
			if (isset($_POST['tihao']) && trim($_POST['tihao']) != '' && isset($_POST['remark']) && trim($_POST['remark']) != ''
					&& isset($_POST['lasted_time']) && trim($_POST['lasted_time']) != ''){
				$ip = $_SERVER['REMOTE_ADDR'];
				$tihao = $stsc->filterSQLChars(trim($_POST['tihao']));
				$remark = intval(trim($_POST['remark']));
				$lasted_time = intval(trim($_POST['lasted_time']));
				$sql = "INSERT INTO kaokaoni (`weibo_id`,`ip`,`tihao`,`remark`,`lasted_time`,`submit_time`,`source`,`site`) VALUES('".
						user."','".$ip."','".$tihao."','".$remark."','".$lasted_time."',NOW(),'".source."','".site."')";
				$res = $saeMysql->runSql($sql);
// 				file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
				if ($saeMysql->affectedRows() != -1)
					$msg = array('state'=>1,'msg'=>'Yeah!成功了!');
				else
					$msg['msg'] = '提交出错，刷新页面重试吧~_~';					
				$stsc->insertIntegral(user, $ip, source, 3, $remark);
			}
		}
	}
	else{
		$msg['state'] = -1;
		//根据来源选择考题1单选2多选4判断  先是这三种类型，各类难易各两题.Q&A 非盈利等，无需服务器验证，简单于客户端验证答案即可
		$qas = array();
		$sql = "SELECT tihao,q,a,b,c,d,e,`answer`,`level` FROM kaokaoni_shiti WHERE `source` = '".source."' AND type = 1 ORDER BY RAND() LIMIT 1";
		$qas[] = $saeMysql->getLine($sql);
// 		file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
		$sql = "SELECT tihao,q,a,b,c,d,e,`answer`,`level` FROM kaokaoni_shiti WHERE `source` = '".source."' AND type = 2 ORDER BY RAND() LIMIT 1";
		$qas[] = $saeMysql->getLine($sql);
// 		file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
		$sql = "SELECT tihao,q,`answer`,`level` FROM kaokaoni_shiti WHERE `source` = '".source."' AND type = 4 ORDER BY RAND() LIMIT 1";
		$qas[] = $saeMysql->getLine($sql);
// 		file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
		for ($i = count($qas);$i < 3;$i++){
			if ($i == 2)
				array_push($qas, array('tihao'=>0,'q'=>'站长很懒,还没添加试题,所以你是满分O(∩_∩)O~发邮件告诉他吧:me@wanzk.com','answer'=>'','level'=>1));
			else
				array_push($qas, array('tihao'=>0,'q'=>'站长很懒,还没添加试题,所以你是满分O(∩_∩)O~发邮件告诉他吧:me@wanzk.com','a'=>'','b'=>'','c'=>'','d'=>'','e'=>'','answer'=>'','level'=>1));
		}
		foreach ($qas as &$qasv) {
			switch ($qasv['level']){
				case 2:$qasv['level']='有点难';break;
				case 3:$qasv['level']='挺难的';break;
				default:$qasv['level']='很简单';
			}
		}
		$c = array();
		$c[0] = $c[1] = '';
		for($i = 0;$i < 2;$i++){
			foreach ($qas[$i] as $k => $v) {
				if (in_array($k, array('a','b','c','d','e')) && trim($v) != '')
					if ($i == 0)//单选
						$c[$i] .= '<input type="radio" name="sc" value='.$k.'>'.$v.'</input><br>';
				elseif ($i == 1)
					$c[$i] .= '<input type="checkbox" name="mc" value='.$k.'>'.$v.'</input><br>';
			}
		}
		$simpleQA = array('sc'=>array('t'=>$qas[0]['tihao'],'q'=>$qas[0]['q'],'c'=>$c[0],'a'=>$qas[0]['answer'],'l'=>$qas[0]['level']),
				'mc'=>array('t'=>$qas[1]['tihao'],'q'=>$qas[1]['q'],'c'=>$c[1],'a'=>$qas[1]['answer'],'l'=>$qas[1]['level']),
				'jd'=>array('t'=>$qas[2]['tihao'],'q'=>$qas[2]['q'],
						'c'=>'<input type="radio" name="jd" value="y">对的</input><input type="radio" name="jd" value="n">不对</input>',
						'a'=>$qas[2]['answer'],'l'=>$qas[2]['level']));
		?>
		<div id="lablCloudItem">
			<ul>
				<li id="lablLi1">估测和值</li>
				<li id="lablLi2">区间分布</li>
				<li id="lablLi3">空区选号</li>
				<li id="lablLi4">和值锁定</li>
				<li id="lablLi5">中心和值</li>
				<li id="lablLi6">号码缩水</li>
				<li id="lablLi7">胆拖玩法</li>
				<li id="lablLi8">蓝码效应</li>
				<li id="lablLi9">选号方式</li>
				<li id="lablLi10">逆向选号</li>
				<li id="lablLi11">数态检验</li>
				<li id="lablLi12">蓝球选号</li>
			</ul>
		</div>		
		<div id="lablCloudDetail">
		</div>
		<br /><br />
		<a href="#lablSubmitMyA" data-toggle="modal" class="btn btn-primary btn-small" id="lablSubmitMyAa">答题</a>
		<!-- labl开始 -->
		    <div class="modal hide fade" id="lablSubmitMyA">
			    <div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">×</button>	
			    	<h4 style="float: left;">Wanna try?来试试吧!</h4>				    
			    </div>
			    <div class="modal-body" style="clear: both;text-align:left;">
			    	<div>
			    		<span>★(单选,<?php echo $simpleQA['sc']['l'];?>)<b><?php echo $simpleQA['sc']['q'];?></b></span><br>
			    		<span style='color:#444;'><?php echo $simpleQA['sc']['c'];?></span>
			    	</div>
			    	<div>
			    		<span>★(多选,<?php echo $simpleQA['mc']['l'];?>)<b><?php echo $simpleQA['mc']['q'];?></b></span><br>
			    		<span style='color:#444;'><?php echo $simpleQA['mc']['c'];?></span>
			    	</div>
			    	<div>
			    		<span>★(判断,<?php echo $simpleQA['jd']['l'];?>)<b><?php echo $simpleQA['jd']['q'];?></b></span><br>
			    		<span style='color:#444;'><?php echo $simpleQA['jd']['c'];?></span>
			    	</div>
			    	<div>已过去<span id='lablTimer' style='color:#FF00CB;'>0</span>秒</div>
		    		<div class="alert alert-error" id="lablErrMsg" style="display: none;">!</div>		    		
		   		</div>
			    <div class="modal-footer">
				 	<a href="#" class="btn btn-primary" id="lablSdMyA">交卷</a>
				    <a href="#" class="btn" data-dismiss="modal">算了</a>					    
				</div>
		   </div>
		<!-- labl结束 -->
		<div style="display: none;" id="lablHiddenDiv">
			<div id="lablLi1html">
				<a onclick='javascript:showDetail(this);'>综合分析</a>
				<span style='display:none;'>综合分析包括几个指标：孤码数、重码、左斜码、右斜码的个数、和值振幅。当孤码数较少时，和值与前期相差不大。当孤码数
					较多时，和值跳动较大。另外重码、左斜码、右斜码重码出现较多时，和值变化也不会很大。相反，如果较少，可能和值偏离较大。我们可以通过统计上期开出的斜码和重码在
					本期的开奖个数来分析其中的规律并运用到实战。</span>
				<a onclick='javascript:showDetail(this);'>间距变化</a>
				<span style='display:none;'>分析间距，选定分区比例。号码都有分久必合、合久必分的规律，结合这个规律再利用间距分析，可以把握号码游动大方向。如
					分析间距时，经常可看到连续几期号码间距较近，那么下期就有分开的可能，或某两个号码间的间距连续几期都很大，那么下期有收拢的可能。通过分析间距，凭第一感号码走
					向和号码组分区出号的比例统计，就可以大致测出三个区间的出号比，再写一注预测号码，求出和值。当然这个第一次的估计值不会准，但误差可以修正。</span>
				<a onclick='javascript:showDetail(this);'>和值的五期平均值、单期振幅值、振幅平均值、位置和值中的首尾定位和值等</a>
				<span style='display:none;'>个人认为五期移动平均值最有实用价值，当然通过观察和值的振幅也有 一定的帮助，毕竟它不可能老是无规律的上窜下跳，有时
					候甚至可以观察出阶段性的规律。指标分析多数时候是很准的，但偶尔还会有些小意外。</span>
				<a onclick='javascript:showDetail(this);'>同位比较、前期相关号码的个数以及尾数和遗漏值等</a>
				<span style='display:none;'>老彩民都知道5个号码同时变大变小一般不可能持续超过3期，多数时候连续上扬2期下期就会突然转折下行，反之亦然。这样就
					把握了和值 变化，但升降的多少要自己斟酌。当我们通过以上手段分析了和值的增加和减小的趋势，初定了分区比例进行同位比较之后，估计和值落点就有了一定的把握了，
					在大概定出一个范围后，最后一 步是精确定位，这需要斟酌号码的出号规律和尾数出号规律，定向排除部分和值，主要方法就是通过出号尾数的分析来估算和值的尾数大小，
					然后排除部分不可能开出的和值尾数，简单点的话排除一些近期开出或自己认为不会开出的和值。</span>
			</div>
			<div id="lablLi2html">
				<a onclick='javascript:showDetail(this);'>区间分布的代码</a>
				<span style='display:none;'>①6枚中奖号码在三分区的组选代码有600、510、420、411、330、321、222共七种，其中的600表示，当期中奖号码在某一分区
					出现了6枚，而在另外的两个分区分别出现了0枚；<br/>②6枚中奖号码在三分区的单选代码有600、060、006、510、501、150、105、051、015、420、402、240、204、042、
					024、411、141、114、330、303、033、321、312、231、213、132、123、222共二十八种，其中的222表示，当期中奖号码在某一分区出现了2枚，而在另外的两个分区分别
					也出现了2枚；<br/>③区间分布每一代码的出现频率，跟其瓜分总注数的具体份额成正比。</span>
				<a onclick='javascript:showDetail(this);'>区间分布代码之比较</a>
				<span style='display:none;'>图一、标准三分区：<br/>
					    01 02 03 04 05 06 07 08 09 10 11----12 13 14 15 16 17 18 19 20 21 22----23 24 25 26 27 28 29 30 31 32 33<br/>
					    图二、除3余数分区：<br/>
					    01 04 07 10 13 16 19 22 25 28 31----02 05 08 11 14 17 20 23 26 29 32----03 06 09 12 15 18 21 24 27 30 33<br/>
					    图三、开奖公告出球顺序三分区：<br/>
					    10 11 12 13 26 28 04 09 19 20 21----01 07 23 32 06 25 15 17 30 31 03----27 05 08 14 18 22 02 24 16 33 29<br/>
					    标准三分区区间分布预测还不够，要通过其他三分区区间分布代码来预测下期中奖号码。</span>
				<a onclick='javascript:showDetail(this);'>2005082期投注案例</a>
				<span style='display:none;'>当上述三种不同的三分区代码被分别预测为330、330、402时，本期投注不但可以排除各图里空区的有关12号码，还可以利用各剩
					余的号码，继续在图一、图二和图三里缩水组号。而区间分布的选号价值也在以上投注案例中得到了体现。</span>
			</div>
			<div id="lablLi3html">
				<a onclick='javascript:showDetail(this);'>空白区简介</a>
				<span style='display:none;'>从中奖号码分布图观察，最常见的偏态即是奖号的“扎堆”效应。在某些期段，某些区间，奖号往往 很容易往同一区域聚集，从而在
					该区域附近形成或大或小的空白区，这些空白区为我们在此区域选号制造了障碍。一方面从概率上说，空白区出现奖号的机会较大，不得不关注；二是空白区中各号码相互关联性
					较差，难以找到有利的条件支持。</span>
				<a onclick='javascript:showDetail(this);'>要点一：寻找较多条件支持的号码</a>
				<span style='display:none;'>例如06104期的07 。05、06、07、08自06099-06103期连续5期轮空， 应预防有奖号产生。06098期06、07以连号开出，根据相同
					遗漏值的号码中容易产生奖号的原理分析，06 、07是当期最值得关注的号码，而07是隔期01、04、07与隔2期11、09、07两条延伸线的交点，这样，目标就集中到了07上。07当
					期遗漏5期，往上分别遗漏7期、6期，呈递减趋势，至此，07基本敲定。</span>
				<a onclick='javascript:showDetail(this);'>要点二：最冷的号码要关注</a>
				<span style='display:none;'>如06104期的22。21、22、23同样多期轮空，而22最冷，三者中若出号，22应做首选，恰好22又为18、20、22与26、24、22两条
					隔期斜三连线的交点，至此，22基本敲定。</span>
				<a onclick='javascript:showDetail(this);'>要点三：关注“孤岛”</a>
				<span style='display:none;'>空白区不可能永远不出号，而当某号码开出后，远看就像茫茫大海中的一座孤岛 ，按通常规律，不远处还会有岛屿出现。按照上述
					规律分析，当空白区出号后，该号附近(下期或下下期 )仍会有号码出现。</span>
			</div>
			<div id="lablLi4html">
				<a onclick='javascript:showDetail(this);'>和值锁定红球码</a>
				<span style='display:none;'>一般来讲，每次投注红球时，所选6个心水号码中包含3个开奖号码的时候是很多的，想中大奖必须找到中奖号码中另一半。通过
					好彩3中心和值的选择，可以让彩民朋友心想事成。<br/>我们不妨设置以下两种可能。A：你选择的6个心水号码中包含3个中奖号码。B：你选择的中心和值也被你选中。在上述基
					础上，只要6个心水号码与好彩3单注组合中的号码不发生重叠，只需128×20=2560注，5120元就能击中全部6个红球。注：上式中128指好彩3中心和值的最高注数。20指6个心水号
					码化解成的20注好彩3组合。用好彩3中心和值投注红球就是将上述两种好彩3进行全方位的强强联合，有效地对红球进行全面围剿。</span>
			</div>
			<div id="lablLi5html">
				<a onclick='javascript:showDetail(this);'>选择心水号码和好彩3中心和值的准确性与中奖的关系</a>
				<span style='display:none;'>1.你选择的心水号中，不多不少包含有3个中奖号，你选择的中心和值也安全正确，在交叉组合过程中，如果组合号不发生重
					叠，意味着中心和值中有1注好彩3的全部3个号是开奖号。恭喜你，你的投注中将有1注包含了全部6个红球开奖号，至少可获二等奖。幸运之神眷顾你的话，可包揽至少500
					万。<br/>2.选中5个红球的两种可能：a心水号选中3个，但好彩3中心和值选错。一般而言，选错的中心和值与它左右相邻的中心和值只有一个号不同。只要开奖号中实
					际中奖和值，与选错的中心和值区别只为±1。b.中心和值选中了，中心和值中有1注好彩3包含有3个开奖号，但心水号只中2个，以上两种情况最多只中三等奖。<br/>3.有三种
					可能导致你只选中4个红球：a.心水号选中3个但中心和值偏差为±2或以上。b.中心和值选择准确，而心水号只选中1个。c.心水号与好彩3中心和值前后区域各只选中2个中奖。</span>
			</div>
			<div id="lablLi6html">
				<a onclick='javascript:showDetail(this);'>好彩3中心和值和心水号进行强强联合的特点</a>
				<span style='display:none;'>一是选中6个红球的概率大大缩小。<br/>二是通过分析推断，可对好彩3中心和值所包含的注数进行取舍（缩水）。</span>
				<a onclick='javascript:showDetail(this);'>缩水过程</a>
				<span style='display:none;'>缩水过程是对好彩3中心和值注数中第1个号进行定位。也称之为“0字头”选择或基础号定位。通过筛选“0字头”，可减少投注数，
					节约投资成本。一般确定1-3个“0字头”。在好彩3中心和值中，每一个“0字头”包含的注数不一样，如和值51中，“01”包含的好彩3为9注。 “08”包含的好彩3为12注。还有少数是
					“1字头”为起点的好彩3。为了方便起见，被统称为“0字头”。“1字头”包含的好彩3注数较少，实际开奖中也处于劣势。但在研究和投注过程中，同样应该引起重视。</span>
			</div>
			<div id="lablLi7html">
				<a onclick='javascript:showDetail(this);'>总则</a>
				<span style='display:none;'>选胆要求稳，不能求险</span>
				<a onclick='javascript:showDetail(this);'>选胆时宜选热码区间的号</a>
				<span style='display:none;'>热码区间，就是近期号最易开出的区间。由于惯性，热码区间总比冷码区间开出的号多，选择其中的号成功率当然就大</span>
				<a onclick='javascript:showDetail(this);'>选胆时可多考虑码重叠码</a>
				<span style='display:none;'>由于重叠码出现较多，备选范围相对较小(每期只有6个)，选中的可能性也比盲目选择的要大</span>
				<a onclick='javascript:showDetail(this);'>选多胆时要注意单双码比例、大小码比例的适中搭配</a>
				<span style='display:none;'>如果选择多个胆码，一定要注意单双码、大小码的基本平衡，不能偏向太多</span>
				<a onclick='javascript:showDetail(this);'>选胆码时还要注意选择旺数尾码</a>
				<span style='display:none;'>如果只考虑号码的个位上的差别，那么33个号可分为0-9共10组，一般情况总会有个别尾数号近期表现特别突出，如果我们选定了
					一个尾码，对应的号就只有3个，再从中细选出1-2个。相对于精心选择的胆码来说，拖码的选择则可以“粗燥”一些，技术加运气，也许就幸运地获中大奖。</span>
			</div>
			<div id="lablLi8html">
				<a onclick='javascript:showDetail(this);'>蓝码三效应之一：聚集效应</a>
				<span style='display:none;'>聚集效应就是数期蓝码数值相差不超过2。如果对蓝码实在没把握，不妨采用加1、加2或减1、减2的方法确定。 </span>
				<a onclick='javascript:showDetail(this);'>蓝码三效应之二：发散效应</a>
				<span style='display:none;'>发散效应就是蓝码大范围移动，一般本期蓝码值要比上期差7以上。同时，这个效应与聚集效应明显相关联，经常在最近3期蓝码出
					现聚集效应的情况下出现。这也提示彩民，发现蓝码“扎堆”出现在某个数字周围两三期后，那么你在选择蓝码的时候，要大胆“跨蓝”，跨越区域选择蓝码。 </span>
				<a onclick='javascript:showDetail(this);'>蓝码三效应之三：惯性效应</a>
				<span style='display:none;'>惯性效应就是指蓝码出现了重复码。值得注意的是，一连几期蓝码重复码的产生，可能都是在离它们最近的两期出现聚集效应(而且
					是差1的聚集效应)的情况下发生的。 </span>
			</div>
			<div id="lablLi9html">
				<a onclick='javascript:showDetail(this);'>单式投注</a>
				<span style='display:none;'>从33个红球中选择6个，从16个蓝球中选择1个，组合为一注投注号码，投注金额为2元。</span>
				<a onclick='javascript:showDetail(this);'>复式投注</a>
				<span style='display:none;'>红球号码复式：从33个红球中选择7—20个，从16个蓝球中选择1个，组合成多注投注号码投注。<br/>蓝球号码复式：从33个红球
					中选择6个，从16个蓝球中选择2—16个，组合成多注投注号码投注。<br/>全复式：从33个红球中选择7—20个，从16个蓝球中选择2—16个，组合成多注投注号码投注。</span>
				<a onclick='javascript:showDetail(this);'>多倍投注</a>
				<span style='display:none;'>同样的号码购买多倍的投注，总投注倍数不限。</span>
				<a onclick='javascript:showDetail(this);'>“性价比”高的选号方式</a>
				<span style='display:none;'>这三种投注方式中，复式投注结合多倍投注中奖几率比较高。它拓宽了中奖面，增加了中奖机会，大小奖一个不漏。业内人士将“9+16”
					以下的复式投注称为小复式投注。而“6+N”的蓝球复式中，如果击中头奖，可获得1个头奖和N-1个二等奖。“N+1”的红球复式中得头奖，可获得一个一等奖和固定奖。实战中，“7+2”
					与“8+2”这两种小复式包号的投入不多，但中奖效益最高。以“7+2”为例，只要中出一个蓝球，那么奖金就有35元，净赚7元。而“8+2”中一个蓝球为140元，比投入还多28元。虽然
					“9+2”中蓝球也有赚，但“9+2”的投入高达336元，单期投入比较贵。也就是说，“7+2”与“8+2”这两种小复式包号的投入不多，但中奖效益最高。</span>
			</div>
			<div id="lablLi10html">
				<a onclick='javascript:showDetail(this);'>在分析号码资料时，由近到远、由新到旧</a>
				<span style='display:none;'>以七期号码为准，将距离当前期数最远的前面第七期作为上一期号码看待，从中挑选号码，再将其与上一期真正的开奖号进行联系，凡
					能与上一期奖号发生重、夹、斜三种关系的坚决抛弃，使自己的思路离开上一期开奖。</span>
				<a onclick='javascript:showDetail(this);'>开奖号在具体到某期时却一定是不平衡的</a>
				<span style='display:none;'>如36选7，奇偶数的个数不可能等同。这种不平衡有强弱之分，如号码的冷热偏态、奇强偶弱、大强小弱、连号强散号弱、重号强新号弱
					等。当某种现象出现强势特征时可大胆逆势而为，见强跟弱，见弱跟强，甚至可以全选强数或弱数。</span>
				<a onclick='javascript:showDetail(this);'>偶尔走一次极端却可能有意外的收获</a>
				<span style='display:none;'>例如：所选号的尾数不超过3个；所选号的最大差值不超 过12(即最大号与最小号相差在10以内)；所选号全部是此前十期的特别号等等，
					这其实是从中选择一个或数个模式选号进行坚守。</span>
			</div>
			<div id="lablLi11html">
				<a onclick='javascript:showDetail(this);'>小区分布数态</a>
				<span style='display:none;'>主要看走势图，一般分析10期中奖号的4个部首共出码多少个，看其优势在何处；再看6+1个小区划分，各小区出码情况。从4个部首号码
					布局和6+1个小区号码分布的特征来分析，号码分布的数态呈现出是大或小情况，通过总值、和数值、极差等数据进而确定选码重点和方向。</span>
				<a onclick='javascript:showDetail(this);'>单双数和大小码数态</a>
				<span style='display:none;'>这主要看10期中奖号里的单数和双数出现情况，分析10期数态演变特征。其数态特征是：当一种大数码占有优势时，即比例为 5：1、
					4：2时，最多维持3期，常规维持两期便发生转换；同样小数码也是如此，最多维持3期，所以我们一般重点按中奖号大小码常规比例1：5、 4：2、5：1、2：4以及3：3而分别确定下期
					大小码比例，具体比例依上期情况而论。</span>
				<a onclick='javascript:showDetail(this);'>中奖号码模式</a>
				<span style='display:none;'>这主要看10期里中奖号模式出现的次数，有小大、中大、小、中、大等等模式，常见的小中大数码模式出现次数最高，把各种模式分别按
					1、2、3、4、5进行编号比较，看它们之间的转换是不是十分明显，进而判断下期中奖号的模式。</span>
			</div>
			<div id="lablLi12html">
				<a onclick='javascript:showDetail(this);'>蓝球选号四原则之一：进行“保本”分析</a>
				<span style='display:none;'>由于六等奖奖金为5元，因此打中蓝球单注就有5元奖金。从保本的角度看，符合保本要求的蓝色球号码可以有2.5个。因此，必须单独建立
					长期的“蓝球走势图”，跟踪统计每个蓝球出现概率，然后根据均值或偏态修正值确定2-3个的蓝球号码投注。</span>
				<a onclick='javascript:showDetail(this);'>蓝球选号四原则之二：区段连号</a>
				<span style='display:none;'>因为选择2-3个甚至5-6个蓝球连号，符合彩民视线习惯，较由可能把握蓝球号码最佳位置形态，实现捕获蓝球开奖号。</span>
				<a onclick='javascript:showDetail(this);'>蓝球选号四原则之三：选择同音球</a>
				<span style='display:none;'>经过一段时间开奖，蓝球的出号形态趋于规则化。特别是同音球的下移和转换会留下痕迹，较易看出蓝球号码“走势”关系。</span>
				<a onclick='javascript:showDetail(this);'>蓝球选号四原则之四：集中选择奇数或者偶数球</a>
				<span style='display:none;'>蓝球的开奖球只有1个，不是奇数就是偶数。可猜测蓝球的奇偶性，再选择相关的蓝球组合投注。</span>
			</div>
		</div>
		<script type="text/javascript">
			qaSCA = '<?php echo $simpleQA['sc']['a'];?>';qaMCA = '<?php echo $simpleQA['mc']['a'];?>';qaJDA = '<?php echo $simpleQA['jd']['a'];?>';
			tihao = "<?php echo $simpleQA['sc']['t'].','.$simpleQA['mc']['t'].','.$simpleQA['jd']['t'];?>";
			jQuery(document).ready(function() {
				var camera = new Camera3D();
				camera.init(0,0,0,300);				
				var container = jQuery("#lablCloudItem");				
				var item = new Object3D(container);
				item.addChild(new Ring(200, jQuery("#lablCloudItem ul li").length));				
				var scene = new Scene3D();
				scene.addToScene(item);
				var mouseX,mouseY = 0;
				var offsetX = jQuery("#lablCloudItem").offset().left;
				var offsetY = jQuery("#lablCloudItem").offset().top;
				var speed = 6000;				
				jQuery(document).mousemove(function(e){
					mouseX = e.clientX - offsetX - (container.width() / 2);
					mouseY = e.clientY - offsetY - (container.height() / 2);
				});				
				axisRotation.x = 1.5;				
				var animateIt = function(){
					if(!jQuery("#lablCloudItem"))
						clearInterval(intv);
					if (mouseX != undefined)
						axisRotation.y += (mouseX) / speed;
					scene.renderCamera(camera);					
				};
				var intv = setInterval(animateIt, 50);
			});
		</script>		
<?php 
	}
if ($msg['state'] != -1)
	echo json_encode($msg);
?>