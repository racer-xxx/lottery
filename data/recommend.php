<?php 
require '../lotConf.php';
$saeMysql = new SaeMysql();
$sql = "SELECT * FROM ".mysql_table_data_ssq_nums_yearly;
$yearlyData = $saeMysql->getData($sql);
// file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
if (count($yearlyData) < 0)
	exit('获取数据失败！');
	foreach ($yearlyData as $yv) {
		for ($i = 1;$i < 34; $i++){
			$reds[$yv['year']][$i] = $yv['r'.$i];
			if($i < 17)
				$blues[$yv['year']][$i] = $yv['b'.$i];
		}
	}
	$redThisYear = $reds[date('Y')];
	$blueThisYear = $blues[date('Y')];
	asort($redThisYear);
	asort($blueThisYear);
	/**
	 * 注：此处可以把“专家分析”、个人信息、质合，奇偶等算法层面的加上，在此先取历来和今年综合出现最少的6个/1个
	*/
	$commonR = array();
	$commonB = 0;
	for ($year = 2003;$year <= date('Y') && isset($reds[$year]) && isset($blues[$year]); $year++){
		$tempRedArr = $reds[$year];
		asort($tempRedArr);
		$k = 0;
		foreach ($redThisYear as $thisRK => $thisRV) {
			if (count($commonR) > 5 || $k++ > 8)
				break;
			foreach ($tempRedArr as $rk => $rv)
				if(!in_array($rk, $commonR) && $rk == $thisRK)
					array_push($commonR, $rk);
		}
		$tempBlueArr = $blues[$year];
		asort($tempBlueArr);
		$k = 0;
		foreach ($blueThisYear as $thisBK => $thisBV) {
			if ($commonB != 0 || $k++ > 6)
				break;
			foreach ($tempBlueArr as $bk => $bv)
				if($commonB == 0 && $bk == $thisBK)
					$commonB = $bk;
		}
	}
	asort($commonR);
	$rec = "";
	for ($i = 2;$i < 8; $i++){
		$rec .= ((current($commonR)<10)?'0'.current($commonR):current($commonR))."&nbsp;";
		next($commonR);
	}
	$rec .= $commonB<10?'0'.$commonB:$commonB;
?>
	<span id="recommend_word">单注推荐:</span><span id="recommend_num"><?php echo $rec;?></span>
	<a class="btn btn-success btn-medium" id="recDldBT" style="margin-left: 20px;">下载蓝球频数表</a>
	<a class="btn btn-success btn-medium" id="recDldRT" style="margin-left: 20px;">下载红球频数表</a>
	<div id="recommendB" style="width:870px;height:190px;top:10px;"></div>
	<div id="recommendR" style="width:870px;height:190px;top:10px;"></div>	
<script type="text/javascript">	
	graphR = '';graphB = '';i=0;
	(function barsb_stacked (containerb, horizontalb) {
		var d1 = [],d2 = [],d3 = [],d4 = [],d5 = [],d6 = [],d7 = [],d8 = [],d9 = [],d10 = [],d1db = [<?php echo implode(',', $blues[2003])?>],
			d2db = [<?php echo implode(',', $blues[2004])?>],d3db = [<?php echo implode(',', $blues[2005])?>],d4db = [<?php echo implode(',', $blues[2006])?>],
			d5db = [<?php echo implode(',', $blues[2007])?>],d6db = [<?php echo implode(',', $blues[2008])?>],d7db = [<?php echo implode(',', $blues[2009])?>],
			d8db = [<?php echo implode(',', $blues[2010])?>],d9db = [<?php echo implode(',', $blues[2011])?>],d10db = [<?php echo implode(',', $blues[2012])?>];
		for (i = 1; i < 19; i++) {
			d1.push([i, parseInt(d1db[i-1],10)]);d2.push([i, parseInt(d2db[i-1],10)]);d3.push([i, parseInt(d3db[i-1],10)]);d4.push([i, parseInt(d4db[i-1],10)]);
			d5.push([i, parseInt(d5db[i-1],10)]);d6.push([i, parseInt(d6db[i-1],10)]);d7.push([i, parseInt(d7db[i-1],10)]);d8.push([i, parseInt(d8db[i-1],10)]);
			d9.push([i, parseInt(d9db[i-1],10)]);d10.push([i, parseInt(d10db[i-1],10)]);
		}
	  	graphB = Flotr.draw(containerb,[{ data : d1, label : '2003' },{ data : d2, label : '2004' },{ data : d3, label : '2005' },{ data : d4, label : '2006' },
                { data : d5, label : '2007' },{ data : d6, label : '2008' },{ data : d7, label : '2009' },{ data : d8, label : '2010' },
                { data : d9, label : '2011' },{ data : d10, label : '2012' }],{/*详细参数参见不是min的js源文件*/title : '2003-'+yr+'蓝球频数表',HtmlText: false,
           		shadowSize: 0,fontSize: 9,legend : {position: 'ne',backgroundOpacity : 0},bars : {show : true,stacked : true, barWidth : 0.7,lineWidth : 0,
               	fillOpacity: 1,centered: true},grid:{verticalLines:false},yaxis:{noTicks: 5,min: 0,max: 120,color: 'blue'},xaxis: {ticks: ['01','02','03','04',
                '05','06','07','08','09',10,11,12,13,14,15,16,'',''],noTicks: 16,color: 'blue'}});
	})(document.getElementById("recommendB"));
	(function bars_stacked (container, horizontal) {
		var d1 = [],d2 = [],d3 = [],d4 = [],d5 = [],d6 = [],d7 = [],d8 = [],d9 = [],d10 = [],d1d = [<?php echo implode(',', $reds[2003])?>],
			d2d=[<?php echo implode(',', $reds[2004])?>],d3d=[<?php echo implode(',', $reds[2005])?>],d4d=[<?php echo implode(',', $reds[2006])?>],
			d5d=[<?php echo implode(',', $reds[2007])?>],d6d=[<?php echo implode(',', $reds[2008])?>],d7d=[<?php echo implode(',', $reds[2009])?>],
			d8d=[<?php echo implode(',', $reds[2010])?>],d9d=[<?php echo implode(',', $reds[2011])?>],d10d=[<?php echo implode(',', $reds[2012])?>];
		for (i = 1; i < 38; i++) {
			d1.push([i, parseInt(d1d[i-1],10)]);d2.push([i, parseInt(d2d[i-1],10)]);d3.push([i, parseInt(d3d[i-1],10)]);d4.push([i, parseInt(d4d[i-1],10)]);
			d5.push([i, parseInt(d5d[i-1],10)]);d6.push([i, parseInt(d6d[i-1],10)]);d7.push([i, parseInt(d7d[i-1],10)]);d8.push([i, parseInt(d8d[i-1],10)]);
			d9.push([i, parseInt(d9d[i-1],10)]);d10.push([i, parseInt(d10d[i-1],10)]);
		}
	  	graphR = Flotr.draw(container,[{ data : d1, label : '2003' },{ data : d2, label : '2004' },{ data : d3, label : '2005' },{ data : d4, label : '2006' },
  	  	        { data : d5, label : '2007' },{ data : d6, label : '2008' },{ data : d7, label : '2009' },{ data : d8, label : '2010' },
  	  	        { data : d9, label : '2011' },{ data : d10, label : '2012' }],{/*详细参数参见不是min的js源文件*/title : '2003-'+yr+'红球频数表',HtmlText: false,
  	        	shadowSize: 0,fontSize: 9,legend : {position: 'ne',backgroundOpacity : 0},bars : {show : true,stacked : true,barWidth:0.7,lineWidth:0,
  	  	        fillOpacity: 1,centered: true},grid:{verticalLines : false},yaxis:{noTicks: 8,min: 0,max: 330,color: 'red'},xaxis: {ticks: ['01','02','03','04',
  	  	  	    '05','06','07','08','09',10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,'','','',''],noTicks:33,color:'red'}});
	})(document.getElementById("recommendR"));
</script>