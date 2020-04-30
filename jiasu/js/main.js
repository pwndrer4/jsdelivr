var isCydia = navigator.userAgent.search(/Cydia/);
var isHistory = window.location.href.search(/nohistory/);
var isAdv = window.location.href.search(/advertisement/);
function loadPackages() {
	var offset = 0;
	offset = document.getElementById("section").children.length;
	$.ajax({
		type: 'GET',
		url: siteurl + 'index.php?pid=' + $('#loadmore').attr("name") + '&method=more' + '&offset=' + offset,
		dataType: 'html',
		cache: true,
		success: function (data) {
			if ($(data).length < 10) {
				$('#loadmore').fadeOut();
			}
			if (data.length != 0) {
				$('#section').append(data);
			}
		}
	});
}
function setCookie(c_name, value, expiredays) {
	var exdate = new Date()
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}
function getCookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1) { 
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if (c_end == -1) {
				c_end = document.cookie.length;
			}
    	return unescape(document.cookie.substring(c_start, c_end));
		} 
	}
	return "";
}
function hide() {
	if ($("#advertisement")[0]) {
		$("#advertisement").fadeOut();
		setCookie("hideadv", "yes", 1);
	}
}
function show() {
	if (getCookie("hideadv") == "yes" && $("#advertisement")[0]) {
		document.getElementById("advertisement").style.display = "none";
	}
}
if (isCydia != -1) {
	document.body.classList.add("cydia");
} else {
	if ($("#cydialink")[0]) {
		document.getElementById("cydialink").style.display = "";
	}
	if ($("#downloadlink")[0]) {
		document.getElementById("downloadlink").style.display = "";
	}
}
if (isHistory != -1 || isCydia == -1) {
	if ($("#header")[0]) {
		document.getElementById("header").style.display = "";
	}
	if ($("#contact")[0]) {
		document.getElementById("contact").style.display = "";
	}
	if ($("#reportlink")[0]) {
		document.getElementById("reportlink").style.display = "none";
	}
	if ($("#advertisement")[0]) {
		document.getElementById("advertisement").style.display = "none";
	}
	if ($("#footer")[0]) {
		document.getElementById("footer").style.display = "";
	}
}
if (isHistory != -1) {
	if ($("#reportlink")[0]) {
		document.getElementById("reportlink").style.display = "none";
	}
	if ($("#historylink")[0]) {
		document.getElementById("historylink").style.display = "none";
	}
}
if (isAdv != -1) {
	if ($("#advertisement")[0]) {
		document.getElementById("advertisement").style.display = "none";
	}
}
if ($("#scroller")[0]) {
	new iScroll(document.getElementById("scroller"));
}
if ($("#loadmore")[0]) {
	loadPackages();
}
show();
//pic
$("div.box").height($("li#fuck5h").height())
        fuck5hous.onload = function() {
        	$("div.box").height($("li#fuck5h0").height())
            $("div.box").height($("li#fuck5h").height())
        }
		window.onresize =function(){
			$("div.box").height($("li#fuck5h").height())
		}
$("div.box > ul > li").eq(0).show().siblings("li").hide();
$("div.triggers > a").mouseover(function(){
            clearInterval(timer);
            var _index = $(this).index();
            $(this).addClass("current").siblings().removeClass("current");
            $("div.box > ul > li").eq(_index).fadeIn(1000).siblings("li").fadeOut(1000);
    });
$("div.triggers > a").mouseout(function(){
         autoplay();
        });
var _index = 0;
var timer = null;
function autoplay(){
    timer=setInterval(function(){
    _index++;
    if(_index<3){
      $("div.triggers > a").eq(_index).addClass("current").siblings().removeClass("current");
      $("div.box > ul > li").eq(_index).fadeIn(1000).siblings("li").fadeOut(1000);
      }else{_index=0;
	  $("div.triggers > a").eq(_index).addClass("current").siblings().removeClass("current");
	  $("div.box > ul > li").eq(_index).fadeIn(1000).siblings("li").fadeOut(1000);
	  }                 
    },5000); 
     };
autoplay();
//sina
var web = 'http://m.weibo.cn/u/6536322065';
var app = 'sinaweibo://userinfo?uid=6536322065';
	function adClick(web,app) {
		window.open(web);
		window.location = app;
};
 $(function(){
    	$(".pay_item").click(function(){
    		$(this).addClass('checked').siblings('.pay_item').removeClass('checked');
    		var dataid=$(this).attr('data-id');
    		$(".shang_payimg img").attr("src","/img/"+dataid+"img.jpg");
    		$("#shang_pay_txt").text(dataid=="alipay"?"支付宝":"微信");
    	});
    });
    function dashangToggle(){
    	$(".hide_box").fadeToggle();
    	$(".shang_box").fadeToggle();
    }

function siteTime(){
window.setTimeout("siteTime()", 1000);
var seconds = 1000
var minutes = seconds * 60
var hours = minutes * 60
var days = hours * 24
var years = days * 365
var today = new Date()
var todayYear = today.getFullYear()
var todayMonth = today.getMonth()
var todayDate = today.getDate()
var todayHour = today.getHours()
var todayMinute = today.getMinutes()
var todaySecond = today.getSeconds()
var t1 = Date.UTC(2014,9,28,11,19,00)
var t2 = Date.UTC(todayYear,todayMonth,todayDate,todayHour,todayMinute,todaySecond)
var diff = t2-t1
var diffYears = Math.floor(diff/years)
var diffDays = Math.floor((diff/days)-diffYears*365)
var diffHours = Math.floor((diff-(diffYears*365+diffDays)*days)/hours)
var diffMinutes = Math.floor((diff-(diffYears*365+diffDays)*days-diffHours*hours)/minutes)
var diffSeconds = Math.floor((diff-(diffYears*365+diffDays)*days-diffHours*hours-diffMinutes*minutes)/seconds)
document.getElementById("sitetime").innerHTML="本源已运行 "+diffYears+" 年 "+diffDays+" 天 "+diffHours+" 时 "+diffMinutes+" 分 "+diffSeconds+" 秒"
}
siteTime()


