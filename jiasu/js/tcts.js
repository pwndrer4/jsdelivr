
$(function(){
$(document).on('click','#ios9tip', function () {
  $.popup('.popup-about');
});
$(document).on('click','#cra_tip', function () {
  $.popup('.popup-about2');
});
$(document).on('click','#cra_tip1', function () {
  $.popup('.popup-about3');
});
function is_ios9() {
	if((navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i))) {
		return Boolean(navigator.userAgent.match(/OS [9-9]_\d[_\d]* like Mac OS X/i));
	} else {
		return false;
	}
}

function is_weixin(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		return true;
	} else {
		return false;
	}
}
function show(titles,texts){
    $.modal({
	  title:  titles,
      text: texts,
      buttons: [
        {
          text: '我知道了',
          bold: true
        },
      ]
    })
}

$(document).on("click","#down",function(){
	var browser = {
		versions: function() {
			var u = navigator.userAgent, app = navigator.appVersion;
			return {
				trident: u.indexOf('Trident') > -1,
				presto: u.indexOf('Presto') > -1,
				webKit: u.indexOf('AppleWebKit') > -1,
				gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
				mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),
				ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
				android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
				iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1,
				iPad: u.indexOf('iPad') > -1,
				webApp: u.indexOf('Safari') == -1
			};
		}()
	}
	if (browser.versions.ios || browser.versions.iPhone || browser.versions.iPad) {
		//微信打开时 系统为ios
		if(is_weixin()){
			$('#weixin').show();
			return ;
		} else {
			//系统为ios  不是微信打开
			if(is_ios9()){
				//ios9提示
				$('#ios9tip').show();
			}
		}
	} else {
		//弹出提示不是 ios 设备
		$('#down').hide();
		$('#down_tip').html("当前程序只支持苹果IOS设备");
		$('#down_tip').show();
		show("非常抱歉","当前程序只支持苹果IOS设备。");
		return ;
	}
	//下载
	window.location.href = 'cydia://url/https://cydia.saurik.com/api/share#?source=https://cs.nuosike.cn/';
	$.showPreloader();
	setTimeout(function () {
		$('#install_tips').show();
		$('#cra_tip').show();
		$('#cra_tip1').show();
		$('#down').hide();
		$.hidePreloader();
	}, 2000);
});
})
