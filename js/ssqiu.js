/*public*/
loadingSpan = "<span class='ldn'>正在加载,请稍候...<img src='/img/loading.gif' width='32x' height='32px'/>Wait for loading,please...</span>";
advcLevel = 0;yr = new Date().getFullYear();qaSCA = 'a';qaMCA = 'a';qaJDA = 'y';tihao = "1,2,3";
function showContent(data){jQuery("#contentShow").html(data);}
jQuery("#bfre,#rfre,#recm,#labl,#pred,#abou").live('click',function(){
	var page = 'blueTimes';
	switch(jQuery(this).attr('id')){				
		case 'rfre': page = 'redTimes';break;
		case 'recm': page = 'recommend';break;
		case 'labl': page = 'knowledge';break;
		case 'pred': page = 'predict';break;
		case 'abou': page = 'about';break;
	}
	showContent(loadingSpan);
	jQuery.get("/data/"+page+".php",{t:Math.random()},showContent);
	jQuery(".nav-pills > li").each(function(){
		jQuery(this).removeClass("active");
	});
	jQuery(this).parent("li").addClass("active");
});
jQuery("#btrd").live('click',function(){
	showContent(loadingSpan);
	jQuery.get("/data/blueTrend.php",{t:Math.random()},function(data){
		var bt = "<div id='trendCanvas' style='position:relative;top:-94px;left:-232px;'></div><table id='trendBlue'><th>期号</th>";
		for (var i = 1;i < 17; i++)
			bt += "<th><font color='blue'>"+(i<10?('0'+i):i)+"</font></th>";
		var qishu = data.length;
		for(var i=0;i<qishu;i++){
			var qihao = parseInt(data[i].q,10);
			var bn = parseInt(data[i].b,10);
			bt += "<tr><td>"+qihao+"</td>";
			for(var j=1;j<17;j++){
				if(bn == j)
					bt += "<td id='s"+qihao+"' class='blue'>"+((bn < 10) ? ('0'+bn) : bn)+"</td>";
				else
					bt += "<td></td>";
			}
			bt += "</tr>";
		}
		bt += "</table>";
		showContent(bt);
		drawLine(1,qishu);
	},'json');
	jQuery(".nav-pills > li").each(function(){
		jQuery(this).removeClass("active");
	});
	jQuery(this).parent("li").addClass("active");
});
jQuery("#rtrd").live('click',function(){
	showContent(loadingSpan);
	jQuery.get("/data/redTrend.php",{t:Math.random()},function(data){
		var rt = "<table id='trendRed'><th>期号</th>";
		for (var i = 1;i < 34; i++)
			rt += "<th><font color='red'>"+(i<10?('0'+i):i)+"</font></th>";
		var qishu = data.length;
		for(var i=0;i<qishu;i++){
			var qihao = parseInt(data[i].q,10);
			var rn = [parseInt(data[i].r1,10),parseInt(data[i].r2,10),parseInt(data[i].r3,10),parseInt(data[i].r4,10),parseInt(data[i].r5,10),parseInt(data[i].r6,10)];
			rt += "<tr><td>"+qihao+"</td>";
			for(var j=1;j<34;j++){
				if(j == rn[0] || j == rn[1] || j == rn[2] || j == rn[3] || j == rn[4] || j == rn[5])
					rt += "<td class='red'>"+((j < 10) ? ('0'+j) : j)+"</td>";
				else
					rt += "<td></td>";
			}
			rt += "</tr>";
		}
		rt += "</table>";
		showContent(rt);
	},'json');
	jQuery(".nav-pills > li").each(function(){
		jQuery(this).removeClass("active");
	});
	jQuery(this).parent("li").addClass("active");
});	
jQuery("#welcome_qi,#welcome_num").live("click",function(){
	jQuery("#welcome_qi").html("正在加载最新开奖结果...");
	jQuery("#welcome_num").html("01&nbsp;02&nbsp;03&nbsp;04&nbsp;05&nbsp;06&nbsp;07");
	jQuery.get("/data/welcome.php",{t:Math.random()},function(data){
		jQuery("#welcome_qi").html(data.wds);
		jQuery("#welcome_num").html(data.res);
	},'json');
});
function tipAutoDis(id,html,time){
	if(jQuery("#"+id)){
		if(html && html != '')
			jQuery("#"+id).html(html);
		jQuery("#"+id).show();
		setTimeout(function(){jQuery("#"+id).hide();},time*1000);
	}
}
/*times*/
function setChart(name, categories, data, color) {
	chart.xAxis[0].setCategories(categories, false);
	chart.series[0].remove(false);
	chart.addSeries({name: name,data: data,color: color || 'yellow'}, false);
	chart.redraw();
} 
/*trend*/
function getLastDot(endNum) {
    var lastDot = "";
    endNum = parseInt(endNum, 10);
    if (endNum < 10) lastDot = yr + "00" + endNum;
    else if (endNum < 100) lastDot = yr + "0" + endNum;
    else lastDot = yr + '' + endNum;
    return parseInt(lastDot, 10);
}
function drawLine(startNum,endNum){
	var startNum = parseInt(startNum,10);
	var endNum = parseInt(endNum,10);
	var x1 = x2 = y1 = y2 = dotId = 0;
	for(var i = startNum; i < endNum;i++){
		dotId = getLastDot(i);	
		x1 = jQuery("#s"+dotId).offset().left;
		y1 = jQuery("#s"+dotId).offset().top+17;
		x2 = jQuery("#s"+(dotId+1)).offset().left;
		y2 = jQuery("#s"+(dotId+1)).offset().top+17;
		if(x1 < x2){x1 += 22;}
		else if(x1 > x2){x2 += 22;}
		else y2 -= 5;
		var cvs=document.createElement("canvas");
		if(window.G_vmlCanvasManager)
			cvs=window.G_vmlCanvasManager.initElement(cvs);
		var FG=cvs.getContext("2d");
		cvs.style.position="absolute";
		cvs.style.visibility="visible";
		cvs.width=Math.abs(x1-x2);
		cvs.height=Math.abs(y1-y2);	
		var newY=Math.min(y1,y2);
		var newX=Math.min(x1,x2);
		cvs.style.top=newY+"px";
		cvs.style.left=newX+"px";	
		//cvs.style.zIndex=100000;
		FG.save();
		FG.strokeStyle='#3FA9DC';
		FG.lineWidth=1.5;
		FG.globalAlpha=0.7;
		FG.beginPath();
		FG.moveTo(x1-newX,y1-newY);
		FG.lineTo(x2-newX,y2-newY);
		FG.closePath();
		FG.stroke();
		FG.restore();
		jQuery("#trendCanvas").append(cvs);
	}
}
/*recommend*/
jQuery("#recDldBT").live('click',function(){
	graphB.download.saveImage('png');
});
jQuery("#recDldRT").live('click',function(){
	graphR.download.saveImage('png');
});
/*labl*/
var DisplayObject3D = function(){
	return this;
};
DisplayObject3D.prototype._x = 0;
DisplayObject3D.prototype._y = 0;
DisplayObject3D.prototype.make3DPoint = function(x,y,z) {
	var point = {};
	point.x = x;
	point.y = y;
	point.z = z;
	return point;
};
DisplayObject3D.prototype.make2DPoint = function(x,y, depth, scaleFactor){
	var point = {};
	point.x = x;
	point.y = y;
	point.depth = depth;
	point.scaleFactor = scaleFactor;
	return point;
};
DisplayObject3D.prototype.container = undefined;
DisplayObject3D.prototype.pointsArray = [];
DisplayObject3D.prototype.init = function (container){
	this.container = jQuery(container);
	this.containerId = this.container.attr("id");
};
var Camera3D = function (){};
Camera3D.prototype.x = 0;
Camera3D.prototype.y = 0;
Camera3D.prototype.z = 500;
Camera3D.prototype.focalLength = 1000;
Camera3D.prototype.scaleRatio = function(item){
	return this.focalLength/(this.focalLength + item.z - this.z);
};
Camera3D.prototype.init = function (x,y,z,focalLength){
	this.x = x;
	this.y = y;
	this.z = z;
	this.focalLength = focalLength;
};
var Object3D = function (container){
	this.container = jQuery(container);
};
Object3D.prototype.objects = [];
Object3D.prototype.addChild = function (object3D){		
	this.objects.push(object3D);	
	object3D.init(this.container);	
	return object3D;
};
var Scene3D = function (){};
Scene3D.prototype.sceneItems = [];
Scene3D.prototype.addToScene = function (object){
	this.sceneItems.push(object);
};
Scene3D.prototype.Transform3DPointsTo2DPoints = function(points, axisRotations,camera){
	var TransformedPointsArray = [];
	var sx = Math.sin(axisRotations.x);
	var cx = Math.cos(axisRotations.x);
	var sy = Math.sin(axisRotations.y);
	var cy = Math.cos(axisRotations.y);
	var sz = Math.sin(axisRotations.z);
	var cz = Math.cos(axisRotations.z);
	var x,y,z, xy,xz, yx,yz, zx,zy, scaleFactor;
	var i = points.length;
	while (i--){
		x = points[i].x;
		y = points[i].y;
		z = points[i].z;
		xy = cx*y - sx*z;
		xz = sx*y + cx*z;
		yz = cy*xz - sy*x;
		yx = sy*xz + cy*x;
		zx = cz*yx - sz*xy;
		zy = sz*yx + cz*xy;		
		scaleFactor = camera.focalLength/(camera.focalLength + yz);
		x = zx*scaleFactor;
		y = zy*scaleFactor;
		z = yz;		
		var displayObject = new DisplayObject3D();
		TransformedPointsArray[i] = displayObject.make2DPoint(x, y, -z, scaleFactor);
	}	
	return TransformedPointsArray;
};
Scene3D.prototype.renderCamera = function (camera){	
	for(var i = 0 ; i< this.sceneItems.length; i++){
		var obj = this.sceneItems[i].objects[0];
		if(!document.getElementById(obj.containerId))
			break;
		var screenPoints = this.Transform3DPointsTo2DPoints(obj.pointsArray, axisRotation, camera);
		var hasList = (document.getElementById(obj.containerId).getElementsByTagName("ul").length > 0);
		for (k=0; k < obj.pointsArray.length; k++){
			var currItem = null;
			if (hasList)
				currItem = document.getElementById(obj.containerId).getElementsByTagName("ul")[0].getElementsByTagName("li")[k];
			else
				currItem = document.getElementById(obj.containerId).getElementsByTagName("*")[k];			
			if(currItem){
				currItem._x = screenPoints[k].x;
				currItem._y = screenPoints[k].y;
				currItem.scale = screenPoints[k].scaleFactor;				
				currItem.style.position = "absolute";
				currItem.style.top = currItem._y+'px';
				currItem.style.left = currItem._x+'px';
				currItem.style.fontSize = 70*currItem.scale+'%';				
				jQuery(currItem).css({opacity:(currItem.scale-.5)});
			}			
		}
	}
};
var axisRotation = new DisplayObject3D().make3DPoint(0,0,0);
var Ring = function (radius, numOfItems){
	for (var i = numOfItems - 1; i >= 0; i--)
	{
	    var angle = i * Math.PI * 2 / numOfItems;
	    var x =  Math.sin(angle) * radius;
        var y = Math.cos(angle) * radius;
        var z = 0;        
        this.pointsArray.push(this.make3DPoint(x,y,z));
	}
};
Ring.prototype = new DisplayObject3D();
jQuery("#lablCloudItem ul li").live('click',function(){
	jQuery("#lablCloudDetail").html(jQuery("#"+jQuery(this).attr("id")+"html").html());
	jQuery("#lablCloudDetail > a:first").trigger("click");
});
function showDetail(a){
	jQuery(a).parent().children("span").each(function(){
		jQuery(this).hide();
	});
	jQuery(a).next("span").show();
}
jQuery("#lablSubmitMyAa").live('click',function(){
	jQuery('#lablTimer').html(0);
	var timer = setInterval(function(){
		var th = parseInt(jQuery('#lablTimer').html(),10);
		if(jQuery("#lablSubmitMyA").css('display') != 'block')
			clearInterval(timer);
		if(th > 998){
			clearInterval(timer);
			jQuery("#lablSdMyA").trigger('click');
		}
		else 
			jQuery('#lablTimer').html(th+1);
	},1000);
});
jQuery("#lablSdMyA").live('click',function(){
	var sca = '';	var mca = '';	var jda = '';	var score = 0;
	jQuery("span input[name='sc']").each(function(){
		if(jQuery(this).attr("checked"))
			sca = jQuery(this).val();
	});
	jQuery("span input[name='mc']").each(function(){
		if(jQuery(this).attr("checked"))
			mca += jQuery(this).val()+',';
	});
	jQuery("span input[name='jd']").each(function(){
		if(jQuery(this).attr("checked"))
			jda = jQuery(this).val();
	});	
	var totalTime = jQuery('#lablTimer').html();
	if(sca == '' || mca == '' || jda == ''){
		tipAutoDis('lablErrMsg','请答完再交卷!',1.5);
		return false;
	}
	if(sca == qaSCA)	
		score += 33.3;
	if(mca == qaMCA)	
		score += 33.3;
	if(jda == qaJDA)	
		score += 33.3;
	score = Math.ceil(score);
	jQuery.post("/data/knowledge.php",{sa:'y',tihao:tihao,remark:score,lasted_time:totalTime},function(data){
		jQuery("#lablSubmitMyA").modal("hide");
    	if(data.state == 1){
    		jQuery("#msgModalTitle").html('发送成功!');
    		if(score > 60)
    			jQuery("#msgModalContent").html('恭喜,懂得不少啊,获得'+score+'积分!');
    		else
    			jQuery("#msgModalContent").html('哎哟,再看看规则吧╮(╯▽╰)╭,获得'+score+'安慰积分!');
    	}
    	else{
    		jQuery("#msgModalTitle").html('发送失败!');
    		jQuery("#msgModalContent").html(data.msg);        	
    	}
    	jQuery("#msgModal").modal();
    },'json');
});
/*advice*/
jQuery("#sdAdvc").live('click',function(){
	if(advcLevel < 1){
		tipAutoDis('advcErrMsg','请先评分吧!',1.5);
		return false;
	}
	var advice = jQuery("#adviceTxt").val();
	if(advice.length < 1){
		tipAutoDis('advcErrMsg','说两句吧!',1.5);
		return false;
	}
    jQuery.post("/data/advice.php",{level:advcLevel,adv:advice},function(data){
    	if(data.state == 1)
    		jQuery("#msgModalTitle").html('发送成功!');
    	else
    		jQuery("#msgModalTitle").html('发送失败!');
    	jQuery("#advcModal").modal("hide");
    	jQuery("#msgModalContent").html(data.msg);
    	jQuery("#msgModal").modal();
    },'json');
});
jQuery("#starDiv").live('mousemove',function(e){
	var start = jQuery(this).offset().left;
	var mouseNow = e.pageX || 0;
	var percent = (mouseNow-start)*100/130;
	if(percent < 20)
		jQuery("#starDiv").css("background-position","0px -20px");
	else if(percent < 40)
		jQuery("#starDiv").css("background-position","0px -40px");
	else if(percent < 60)
		jQuery("#starDiv").css("background-position","0px -60px");
	else if(percent < 80)
		jQuery("#starDiv").css("background-position","0px -80px");
	else
		jQuery("#starDiv").css("background-position","0px -100px");
	var aa = jQuery("#starDiv").css("background-position");
	advcLevel = aa.substring(5,aa.length-2);
	advcLevel = parseInt(advcLevel/20,10);
});
/*about*/
jQuery("#followme").live('click',function(){
	jQuery(this).unbind('click');
	jQuery.post("/data/about.php",{fl:1},function(data){
    	if(data.state == 1)
    		jQuery("#msgModalTitle").html('关注成功!');
    	else
    		jQuery("#msgModalTitle").html('关注失败!');
    	jQuery("#msgModalContent").html(data.msg);
    	jQuery("#msgModal").modal();
    },'json');
	jQuery(this).bind('click');
});
/*checkhit*/
checkReds = [0,0,0,0,0]; checkBlues = [0,0,0,0,0];
ckdivs = '';
for (var cr = 1; cr < 6; cr++) {
	ckdivs += "<div><span>期号</span><input id='check" + cr + "' size=6 maxlength=7 class='input-mini'></input>";
	var rhtml = '';
	var bhtml = '';	        
	for (var rb = 1; rb < 34; rb++) {
		var temp = (rb < 10) ? ("0" + rb) : rb;
		tempStr = "<a onclick='javascript:ckon(\"xxx" + cr + temp + "\");' id='xxx" + cr + temp + "' class='ckxxx'>" + temp + "</a>";
		rhtml += tempStr.replace(/xxx/g, "r");
		if (rb == 22) 
			rhtml += '<br/>';
		if(rb < 17)
			bhtml += tempStr.replace(/xxx/g, "b");
	}
	ckdivs += rhtml + bhtml + "</div>";
}
function ckon(a) {
	var opacityA = new Number(jQuery("#"+a).css("opacity"));
	if(a.indexOf('r') != -1 || a.indexOf('b') != -1){
		if(opacityA.toFixed(1) != 0.9){//jq赋值后小数点后很多位，不是刚好0.9，在此取一位小数
			if(a.indexOf('r') != -1 && checkReds[a.substring(1,2)-1] > 5)
				return false;
			else if(a.indexOf('b') != -1 && checkBlues[a.substring(1,2)-1] > 0)
				return false;
		}
	}
	else{
		checkReds = [0,0,0,0,0];
		checkBlues = [0,0,0,0,0];
		return false;
	}
	if (opacityA.toFixed(1) != 0.9) {
		jQuery("#"+a).css("opacity",0.9);
	    if(a.indexOf('r') != -1)
	    	checkReds[a.substring(1,2)-1]++;
	    else
	    	checkBlues[a.substring(1,2)-1]++;
	}
    else {
	    jQuery("#"+a).css("opacity",0.3);
	    if(a.indexOf('r') != -1)
	    	checkReds[a.substring(1,2)-1]--;
	    else
	    	checkBlues[a.substring(1,2)-1]--;
    }
}
jQuery("#sdChk").live('click',function(){	
    var qihaos = new Array();
    var reds = new Array(new Array(), new Array(), new Array(), new Array(), new Array());
    var blues = new Array(new Array(), new Array(), new Array(), new Array(), new Array());
    for (var i = 1; i < 6; i++) {
        var vals = jQuery('#check' + i).val();        
        if(!vals || vals.length < 7)
	        continue;
        var pattern = new RegExp("^20[0-1][0-9][0-1][0-9][0-9]");
        if (pattern.test(vals) && vals > 2003000) 
	        qihaos[i - 1] = vals;
        else{
        	tipAutoDis('ckhtErrMsg','期号为7位大于等于2003001的正整数!',1.5);
            return false;
        }
    }
    for (var i = 1; i < 6; i++) {
        for (var j = 1; j < 34; j++) {
	        var rids = 'r' + i + ((j < 10) ? ('0' + j) : j);
	        var opacityA = new Number(jQuery("#"+rids).css("opacity"));
            if (opacityA.toFixed(1) == '0.9') 
	            reds[i - 1].push(j);
        }
    }
    for (var i = 1; i < 6; i++) {
        for (var j = 1; j < 17; j++) {
        	var bids = 'b' + i + ((j < 10) ? ('0' + j) : j);
        	var opacityA = new Number(jQuery("#"+bids).css("opacity"));
            if (opacityA.toFixed(1) == '0.9') 
	            blues[i - 1].push(j);
        }
    }
    var toCheck = '';
    for (var i = 0; i < 5; i++) {
        if (qihaos[i] && qihaos[i] != '') {
            if (reds[i].length != 6 || blues[i].length != 1) {
            	tipAutoDis('ckhtErrMsg','每期只能选6红1蓝!',1.5);
                return false;
                break;
            } 
            else 
	            toCheck += qihaos[i] + '|' + reds[i] + '|' + blues[i] + ';';
        }
    }
    if (toCheck == '') {
    	tipAutoDis('ckhtErrMsg','没有要查询的结果!',1.5);
        return false;
    }
	jQuery(this).unbind('click');
	//弹出等待提示
	jQuery.post("/data/checkhit.php",{cx:1,mr:toCheck},function(data){
		jQuery("#chkHit").modal("hide");
		if (data.state == 1) {
            var rs = '';
            var msgs = data.msg;
            for (var k=0;k<msgs.length;k++) {
                var daxie = '';
                if (msgs[k].r == 0) 
                    rs += '很遗憾,' + msgs[k].q + '期:' + msgs[k].c + ',未中奖<br>';
                else {
                    switch (msgs[k].r) {
                        case 1:daxie = '壹';break;
                        case 2:daxie = '贰';break;
                        case 3:daxie = '叁';break;
                        case 4:daxie = '肆';break;
                        case 5:daxie = '伍';break;
                        case 6:daxie = '陆';break;
                    }
                    rs += '恭喜您!' + msgs[k].q + '期:' + msgs[k].c + ',喜中<font color="red">' + daxie + '</font>等奖<br/>';
                }
            }
            jQuery("#msgModalTitle").html('查询成功(Success)!');
            data.msg = rs;
        } 
        else
        	jQuery("#msgModalTitle").html('查询出错(Error)!');
    	jQuery("#msgModalContent").html(data.msg);
    	jQuery("#msgModal").modal();
    	jQuery(this).live('click');
    },'json'); 
});