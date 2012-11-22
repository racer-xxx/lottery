<?php
	require '../lotConf.php';
	$saeMysql = new SaeMysql();
	$blues = array();
	for($i = 1;$i < 17;$i++)
		$blueYearSum[$i] = 0;
	$sql = "SELECT * FROM ".mysql_table_data_ssq_nums_yearly;
	foreach ($saeMysql->getData($sql) as $value){
		for ($i = 1;$i < 17;$i++){
			$blues[$value['year']][$i] = intval($value['b'.$i]);
			$blueYearSum[$i] += intval($value['b'.$i]);
		}
	}
// 	file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
?>
<div id="freB"></div>
<script type="text/javascript">
    var chart,colors = Highcharts.getOptions().colors,name = '01-16号蓝球2003-2012出现频数柱状图',redsData = new Array(),bluesData = new Array(),
    	blueNum = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16'],
    	catYear = ['2003','2004','2005','2006','2007','2008','2009','2010','2011','2012'],    	
    	blueData = eval(<?php echo json_encode($blues);?>),
    	blueSum = eval(<?php echo json_encode($blueYearSum);?>);
    for(var i=1;i<17;i++){
    	bluesData[i-1] = {y: blueSum[i],
  	      	                   	color: colors[i%10],
  	      	                   	drilldown: {
  	          	                   	name: blueNum[i-1]+'号蓝球2003-2012出现频数柱状图',
  	          	                   	categories: catYear,
  	          	                   	data: [blueData[2003][i],blueData[2004][i],blueData[2005][i],blueData[2006][i],blueData[2007][i],blueData[2008][i],blueData[2009][i],blueData[2010][i],blueData[2011][i],blueData[2012][i]],
  	          	                   	color: colors[i%10]}
      	};
    }
    chart = new Highcharts.Chart({
        chart: {renderTo: 'freB',type: 'column',backgroundColor: '#f4f0f1'},        
        title: {text: '2003-2012蓝球频数柱状图'},
        subtitle: {text: '点击每栏查看详细,再次点击返回总频数表'},
        xAxis: {categories: blueNum},
        yAxis: {title: null},
        plotOptions: {
            column: {
               cursor: 'pointer',
               point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                drilldown ? setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color) : setChart(name, blueNum, bluesData);
                            }
                        }
                    },
                    dataLabels: {enabled: true,color: colors[0],style: {fontWeight: 'bold'},formatter: function() {return this.y;}}
                }
        },
        tooltip: {
            formatter: function() {
                var point = this.point,s = this.x;
                s += '<font color="blue">'+point.drilldown ? '</font>号蓝球:<b>'+ this.y +'次</b><br/>点击查看2003-2012年详细' : '年:<b>'+ this.y +'次</b><br/>点击返回蓝球频数总表';
                return s;
            }
        },
        series: [{name: name,data: bluesData,color: 'white'}],
        exporting: {enabled: false}        
    });
</script>