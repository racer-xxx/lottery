<?php
	require '../lotConf.php';
	$saeMysql = new SaeMysql();
	$reds = array();
	for($i = 1;$i < 34;$i++)
		$redYearSum[$i] = 0;
	$sql = "SELECT * FROM ".mysql_table_data_ssq_nums_yearly;
	foreach ($saeMysql->getData($sql) as $value){
		for ($i = 1;$i < 34;$i++){
			$reds[$value['year']][$i] = intval($value['r'.$i]);		
			$redYearSum[$i] += intval($value['r'.$i]);
		}
	}
// 	file_put_contents(SQLLog, 'time:'.date('H:i:s').';SQL:'.$sql.';errmsg:'.$saeMysql->errmsg()."\r\n",FILE_APPEND);
?>
<div id="freR"></div>
<script type="text/javascript">
    var chart,colors = Highcharts.getOptions().colors,name = '01-33号红球2003-2012出现频数柱状图',redsData = new Array()
    	redNum = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33'],
    	catYear = ['2003','2004','2005','2006','2007','2008','2009','2010','2011','2012'],    	
    	redData = eval(<?php echo json_encode($reds);?>),redSum = eval(<?php echo json_encode($redYearSum);?>);
    for(var i=1;i<34;i++){
    	redsData[i-1] = {y: redSum[i],
    	    			color: colors[i%10],
    	    			drilldown: {name: redNum[i-1]+'号红球2003-2012出现频数柱状图',
        	    					categories: catYear,
        	    					data: [redData[2003][i],redData[2004][i],redData[2005][i],redData[2006][i],redData[2007][i],redData[2008][i],redData[2009][i],redData[2010][i],redData[2011][i],redData[2012][i]],
      	                			color: colors[i%10]}
		};
    }       
    chart = new Highcharts.Chart({
        chart: {renderTo: 'freR',type: 'column',backgroundColor: '#f4f0f1'},
        title: {text: '2003-2012红球频数柱状图'},
        subtitle: {text: '点击每栏查看详细,再次点击返回总频数表'},
        xAxis: {categories: redNum},
        yAxis: {title: null},
        plotOptions: {
            column: {
               cursor: 'pointer',
               point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                drilldown ? setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color) : setChart(name, redNum, redsData);
                            }
                        }
                    },
                    dataLabels: {enabled: true,color: colors[0],style: {fontWeight: 'bold'},formatter: function() {return this.y;}}
                }
        },
        tooltip: {
            formatter: function() {
                var point = this.point,s = this.x;
                s += '<font color="red">'+point.drilldown ? '</font>号红球:<b>'+ this.y +'次</b><br/>点击查看2003-2012年详细' : '年:<b>'+ this.y +'次</b><br/>点击返回红球频数总表';
                return s;
            }
        },
        series: [{name: name,data: redsData,color: 'white'}],
        exporting: {enabled: false}        
    });
</script>