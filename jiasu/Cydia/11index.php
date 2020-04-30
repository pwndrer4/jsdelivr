<?php
/**
 * DCRM Mobile Page
 *
 * This file is part of WEIPDCRM.
 * 
 * WEIPDCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * WEIPDCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with WEIPDCRM.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('../system/common.inc.php');
base_url();

// URL For Rewrite 
$rewrite_mod = get_option('rewrite_mod');
switch($rewrite_mod){
	case 3:
		$rewrite_url = array('view' => 'view/%d', 'view_nohistory' => 'view/%d/nohistory', 'screenshot' => 'screenshot/%d', 'history' => 'history/%d', 'contact' => 'contact/%d', 'section' => 'section/%d', 'report' => 'report/%d', 'report_support' => 'report/%1$d/%2$d', 'more' => 'more/%d', 'more_offset' => 'more/%1$d/%2$d', 'misc' => 'misc');
		break;
	case 1:
	case 2:
	default:
		$rewrite_url = array('view' => 'index.php?method=view&amp;pid=%d', 'view_nohistory' => 'index.php?pid=%d&amp;addr=nohistory', 'screenshot' => 'index.php?method=screenshot&amp;pid=%d', 'history' => 'index.php?method=history&amp;pid=%d', 'contact' => 'index.php?method=contact&amp;pid=%d', 'section' => 'index.php?method=section&amp;pid=%d', 'report' => 'index.php?method=report&amp;pid=%d', 'report_support' => 'index.php?method=report&amp;pid=%1$d&amp;support=%2$d', 'more' => 'index.php?method=more&amp;pid=%d', 'more_offset' => 'index.php?method=more&amp;pid=%1$d&amp;offset=%2$d', 'misc' => 'misc.php');
		break;
}
function echo_rewrite_url($type, $variable1, $variable2=''){
	global $rewrite_url;
	echo SITE_URL;
	printf($rewrite_url[$type], $variable1, $variable2);
}

class_loader('Mobile_Detect');
$detect = new Mobile_Detect;
if(!$detect->isiOS()){
	if (DCRM_PCINDEX == 1) {
		header("Location: ".SITE_URL.$rewrite_url['misc']);
		exit();
	} else {
		$isCydia = false;
	}
} else {
	if (DCRM_MOBILE == 2) {
		if (!strpos($detect->getUserAgent(), 'Cydia')) {
			$isCydia = false;
		} else {
			$isCydia = true;
		}
	} else {
		exit('Access Denied');
	}
}
if (file_exists('Release')) {
	$release = file('Release');
	$release_origin = __('No Name');
	$release_mtime = filemtime('Release');
	$release_time = date('Y-m-d H:i:s',$release_mtime);
	foreach ($release as $line) {
		if(preg_match('#^Origin#', $line)) {
			$release_origin = trim(preg_replace("#^(.+):\\s*(.+)#","$2", $line));
		}
		if(preg_match("#^Description#", $line)) {
			$release_description = trim(preg_replace("#^(.+):\\s*(.+)#","$2", $line));
		}
	}
} else {
	$release_origin = __('Empty Page');
}
if (isset($_GET['pid'])) {
	if (ctype_digit($_GET['pid']) && intval($_GET['pid']) <= 10000) {
		function device_check(){
			global $detect;
			$device_type = array('iPhone', 'iPod', 'iPad');
			for ($i = 0; $i < count($device_type); $i++) {
				$check = $detect->version($device_type[$i]);
				if ($check !== false) {
					if (isset($_SERVER['HTTP_X_MACHINE'])) {
						$DEVICE = $_SERVER['HTTP_X_MACHINE'];
					} else {
						$DEVICE = 'Unknown';
					}
					$OS = str_replace('_', '.', $check);
					break;
				}
			}
			return array('DEVICE' => $DEVICE, 'OS' => $OS);
		}

		if (isset($_GET['method']) && $_GET['method'] == 'screenshot') {
			$index = 2;
			$title = __('View Screenshots');
		} elseif (isset($_GET['method']) && $_GET['method'] == 'report') {
			$device_info = device_check();
			if (!isset($_GET['support'])) {
				$index = 3;
			} else {
				if ($_GET['support'] == '1') {
					$support = 1;
				} elseif ($_GET['support'] == '2') {
					$support = 2;
				} elseif ($_GET['support'] == '3') {
					$support = 3;
				} else {
					$support = 0;
				}
				$index = 4;
			}
			$title = __('Report Problems');
		} elseif (isset($_GET['method']) && $_GET['method'] == 'history') {
			$index = 5;
			$title = __('Version History');
		} elseif (isset($_GET['method']) && $_GET['method'] == 'contact') {
			$index = 6;
			$title = __('Contact us');
		} elseif (isset($_GET['method']) && $_GET['method'] == 'section') {
			$index = 7;
			$title = __('Package Category');
		} elseif (isset($_GET['method']) && $_GET['method'] == 'more') {
			$index = 8;
			$section = DB::fetch_first("SELECT `Name`, `Icon` FROM `".DCRM_CON_PREFIX."Sections` WHERE `ID` = '".(int)$_GET['pid']."'");
			$q_name = DB::real_escape_string($section['Name']);
			if (isset($_GET['offset']) && !empty($_GET['offset']) && ctype_digit($_GET['offset'])) {
				$offset = intval($_GET['offset']);
			} else {
				$offset = 0;
			}
			$packages = DB::fetch_all("SELECT `ID`, `Name`, `Package` FROM `".DCRM_CON_PREFIX."Packages` WHERE (`Stat` = '1' AND `Section` = '".$q_name."') ORDER BY `ID` DESC LIMIT 10 OFFSET ".$offset);
			foreach($packages as $package){
				if(!empty($package)){
					if ($isCydia) { ?>
              <a href="cydia://package/<?php echo($package['Package']); ?>" target="_blank">
<?php	} else { ?>
              <a href="<?php echo_rewrite_url('view', $package['ID']);?>">    
<?php				} ?>
                 <table cols="2" border="0" class=" information" >
                  <tbody class="">
                    <tr class="">
                      <td style="margin-top:6px;font-size:18px;"> 
                          <img class="icon" src="<?php echo(SITE_URL); ?>icon/<?php echo(empty($section['Icon']) ? 'default/unknown.png' : $section['Icon']); ?>" style="border-radius: 50%; width: 25px; height: 25px; position:relative;left:-11px;top:6px;"><?php echo($package['Name']); ?>
                      </td>
                      <i class="ui-btn3" style="color: #7A67EE; float:right;margin-right:18px;margin-top:18px;"><strong>查看</strong></i>
                    </tr>
                  </tbody>
                 </table> 
               </a> 
              <div class=" fading-sep"></div>
<?php
				}
			}
			exit();
		} elseif (!isset($_GET['method']) || (isset($_GET['method']) && $_GET['method'] == 'view')) {
			$index = 1;
			$title = __('View Package');
			$package_id = (int)DB::real_escape_string($_GET['pid']);
			$package_info = DB::fetch_first("SELECT `Name`, `Version`, `Author`, `Package`, `Description`, `DownloadTimes`, `Multi`, `CreateStamp`, `Size`, `Installed-Size`, `Section`, `Homepage`, `Tag`, `Level`, `Price`, `Purchase_Link`, `Changelog`, `Changelog_Older_Shows`, `Video_Preview`, `System_Support`, `ScreenShots` FROM `".DCRM_CON_PREFIX."Packages` WHERE `ID` = '".$package_id."' LIMIT 1");
			if ($package_info) $title = $title.' - '.$package_info['Name'];
		} else {
			httpinfo(405);
			exit();
		}
	} else {
		httpinfo(405);
		exit();
	}
} elseif (!isset($_GET['method'])) {
	$index = 0;
	$title = $release_origin;
} else {
	httpinfo(405);
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>AlanSileo中文源</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="apple-mobile-web-app-title" content="AlanSileo中文源" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta name="HandheldFriendly" content="true" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="robots" content="index, follow" />
		<meta name="title" content="AlanSileo中文源" />
		<meta name="description" content="AlanSileo中文源最简洁的中文源简单整洁的Cydia源™，纯净无广告插件源™！" />
		<base target="_top">
		<link rel="apple-touch-icon" href="//apt.sileo.ga/CydiaIcon.png">
		<link rel="shortcut icon" href="//apt.sileo.ga/favicon.ico">
		<link rel="stylesheet" href="//apt.sileo.ga/Cydia/sd/css/new.css">
		<script src="//apt.sileo.ga/Cydia/sd/js/fastclick.js" type="text/javascript"></script>
		<script src="//apt.sileo.ga/Cydia/sd/js/jquery-2.1.3.js" type="text/javascript"></script>
		<style>.hlong *{display:inline-block;vertical-align:middle}</style>
<style>
.xiangmu>ul>li
{
float:left;
}
</style>
	</head>
	<body class="pinstripe">   
		<panel>

    <panel class="left">
        <div style="margin:9px;padding:0 16px">


            <style type="text/css">.cytyle-faux #logo {
                    background-image: url("./CydiaIcon.png");
                    background-size: 59px 60px;
                    width: 59px
                }
                
                .cytyle-flat #logo {
                    background-image: url("./CydiaIcon.png");
                    background-size: 64px 60px;
                    width: 64px
                }
            </style>
              <script type="text/javascript">
        if (navigator.userAgent.search(/Cydia/) != -1) {
            var title = document.title;
            var dot = title.indexOf('·');
            if (dot != -1)
                document.title = title.substring(0, dot);
        }
    </script>
     <panel style="margin-top:0px;" >
           <panel-body style="background-image: linear-gradient( 135deg, #69FF97 10%, #00E4FF 100%);width:100%;margin-left:-20px;" >
             <div>
               <div style="margin-top:10px;float: right; vertical-align: middle; text-align: center; width: 200px">
                <span style="font-size: 18px;color:#080808;">欢迎使用 Cydia<sup>™</sup></span>
                 <br><div style="margin-top:10px;"></div> 
                 <span style="font-size: 14px;color:#080808;margin-top:10px;">by <?php echo(AUTOFILL_SEO); ?><sup>™</sup></span>
               </div>
               <center><img class="icon" src="CydiaIcon.png" style="width: 59px; height: 59px; vertical-align: top;"></center>
        </div>
     </panel-body>
   </panel>  
      <panel style="margin-top:12px;" class="modern">
              <ul class="doubleCol" style="padding-top:0px;margin-top:-10px;">
                 <panel-body style="background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-38px;width:97%;height: 15px;" classname="green-gradient">
                <a style="padding-top: 0px" href="//apt.sileo.ga/Cydia/gzh.html" class="" target="_blank">
					<div style="padding-top: 0px;" class="reviews-more">
                     <div style="color: white !important" class="">
                       <img class="icon" src="wechat.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
						公众微信
                         </div>
                       </div>
                  </a> 
                  </panel-body>
                
  			   <panel-body style="background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:3px;width:97%;height: 15px;" classname="green-gradient">
                <a style="padding-top: 0px;" href="//jq.qq.com/?_wv=1027&k=5seVbRh" class="" target="_open">
                  
							  <div style="padding-top: 0px;" class="reviews-more">
                                    <div style="color: white !important" class="">
                                    <img class="icon" src="../icon/default/qq.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
									官方Q群
                                    </div>
                                </div>
                   
                    </a> 
                   </panel-body>
             </ul>  
 


              <ul class="doubleCol" style="padding-top:0px;margin-top:0px;">
                 <panel-body style="background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-38px;width:97%;height: 15px;" classname="green-gradient">
                <a style="padding-top: 0px" href="//weibo.com/u/6536322065" class="" target="_open">
					<div style="padding-top: 0px;" class="reviews-more">
                     <div style="color: white !important" class="">
                       <img class="icon" src="../icon/default/weibo.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
						关注微博
                         </div>
                       </div>
                  </a> 
                  </panel-body>
                
  			   <panel-body style="background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:3px;width:97%;height: 15px;" classname="green-gradient">
                <a style="padding-top: 0px;" href="//zfb.cydia.love" class="" target="_open">
							  <div style="padding-top: 0px;" class="reviews-more">
                                    <div style="color: white !important" class="">
                                    <img class="icon" src="./alipay.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
									捐助我们
                                    </div>
                                </div>
                   
                    </a> 
                   </panel-body>
             </ul>  
  </panel>   

      <panel style="margin-top:-10px;" class="modern">
  			   <a style="padding-top: 0px" href="../ios9.php" class="">
  			   <panel-body style="margin-top:-25px;width:100%;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="../manage/Speed/icon/dh9.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">Sileo中文V计划</div>
                    </div>
                      </panel-body>
                 </a> 
       </panel>
          
          
           <panel style="margin-top:-15px;" class="modern">       
             <ul class="doubleCol" style="padding-top:0px;margin-top:0px;">
               <panel-body style="background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-38px;width:97%;height: 15px;" classname="green-gradient">
                 <a style="padding-top: 0px" href="//apt.sileo.ga/ios9.php" class="" target="_blank">
					<div style="padding-top: 0px;" class="reviews-more">
                     <div style="color: white !important" class="">
                       <img class="icon" src="//apt.sileo.ga/Cydia/ios9.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
						IOS9分类
                         </div>
                       </div>
                  </a> 
                  </panel-body>
                
  			   <panel-body style="background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:3px;width:97%;height: 15px;" classname="green-gradient">
                <a style="padding-top: 0px;" href="//apt.sileo.ga/ios10.php" class="" target="_open">
							  <div style="padding-top: 0px;" class="reviews-more">
                                    <div style="color: white !important" class="">
                                    <img class="icon" src="//apt.sileo.ga/Cydia/ios10.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
									IOS10分类
                                    </div>
                                </div>
                   
                    </a> 
                   </panel-body>
             </ul>     
            <a style="padding-top: 0px" href="//apt.sileo.ga/ios11.php" class="">
  			   <panel-body style="margin-top:-9px;width:100%;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="//apt.sileo.ga/Cydia/ios11.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">IOS11分类🚧<span style="color: #6e6d6d; float:right;">
                   <l style="font-size: 8px;">随时回来看看👻<br/>资源不断添加中...</l>
                   </span>
                  </div>
                    </div>
                      </panel-body>
                 </a> 
            <a style="padding-top: 0px" href="//apt.sileo.ga/Sections1.php" class="">
  			   <panel-body style="margin-top:0px;width:100%;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="//apt.sileo.ga/Cydia/ios.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">IOS应用插件归类🚧
                  <span style="color: #6e6d6d; float:right;"><l style="font-size: 8px;">随时回来看看👻<br/>资源不断添加中...</l>
                   </span>
                    </div>
                 </div>
                      </panel-body>
                 </a>             
           </panel>
<?php
require_once ('../qqlogin/function.php');
require_once ('../qqlogin/Connect2.1/qqConnectAPI.php');
?>                  
<?php if(!isset($_SESSION["accesstoken"]) || !isset($_SESSION["openid"])) { ?>

         <panel style="margin-top:-10px;" class="modern">
  			   <a style="padding-top: 0px" onclick="cookipost2()" class="">
  			   <panel-body style="margin-top:-25px;width:100%;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="../icon/sc.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">登录会员中心
                  <span  id="isbookmark" style="float:right;"><span onclick="cookipost2()" style="color: #808080;">登录</span></span>
                    </div></div>
                      </panel-body>
                 </a> 
       </panel>       
 <script language="javascript">
    function cookipost2(){
      parent.location.href='../qqlogin/qqlogin.php';
 
	}
</script>
<?php } else { 
    $qc = new QC($_SESSION["accesstoken"],$_SESSION["openid"]);
    $arr = $qc->get_user_info();           
?>  
         <panel style="margin-top:-10px;" class="modern">
  			   <a style="padding-top: 0px" onclick="cookipost()" class="">
  			   <panel-body style="width:100%;margin-top:-25px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="<?php echo $arr['figureurl_qq_2'];?>" style="border-radius: 50%;width: 25px; height: 25px;float:left;margin-top:-5px;">
                   用户名：<?php echo $arr["nickname"];?>
                   <span  style="float:right;"><span onclick="cookipost()" style="color: #808080;">退出</span></span>
                    </div></div>
                      </panel-body>
                 </a> 
      
            <a style="padding-top: 0px;" href="../favourite.php" class="">
  			   <panel-body style="width:100%;margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="../icon/user.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">
                 会员个人中心
                    </div></div>
                      </panel-body>
                 </a> 
           </panel>
 <script language="javascript">
    function cookipost(){
      $.get("../qqlogin/qqlogout.php", function(result){
    	location.reload();
        alert('退出账户');
  	  });  
	}
</script>
<?php
 }           
?>                   
      
<?php 
if(isset($_SESSION['accesstoken']) || isset($_SESSION['openid'])) {      
$User=$arr["nickname"];
$Logo=$arr['figureurl_2'];
$Openid=$_SESSION['openid'];
$date=date("Y-m-d h:i:s");     
$servername = "localhost";
$username = "lovecydia";
$password = "lovecydia";
$dbname = "lovecydia";   
$conn2 = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
 $ip = ($ip) ? $ip : $_SERVER["REMOTE_ADDR"];  
$date=date("Y-m-d h:i:s");   
if($row=DB::result_first("SELECT `Openid` FROM `apt_User` WHERE `Openid`='".$_SESSION['openid']."'" )){  

}else{  
$sql2 = "INSERT INTO apt_User (ID,User,Logo,Openid,Packages,TimeStamp,IP) VALUES ('','$User','$Logo','$Openid','','$date','$ip')";  
if ($conn2->query($sql2) === TRUE) {
} 
} 
   }
?>     
   

<script src="../icon/gd/js/msc.js"></script>
<link rel="stylesheet" href="../icon/gd/css/gd.css">
     <panel style="margin-top:0px;" >
           <panel-body style="background-image: linear-gradient( 135deg, #FFE985 10%, #FA742B 100%);width:100%;margin-left:-20px;" >
             <div>
		<a href="//apt.sileo.ga" target="_open"><p id="name"><img src="../icon/gd/jblogo.png" width="100%"></p>
 	<div class="one">
       <ul style="left: -254.4px;">
       <li><img src="../icon/gd/a1.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a2.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a3.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a4.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a5.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a6.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a7.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a8.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a9.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a10.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a11.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a12.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
       <li><img src="../icon/gd/a1.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a2.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a3.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a4.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a5.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a6.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a7.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a8.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a9.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a10.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a11.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   <li><img src="../icon/gd/a12.png" style="border-radius: 12%;box-shadow:0 0 1px #AAA;padding:1px;margin-right: 1px;"></li>
	   </ul>
	</div></a>
        </div>
     </panel-body>   
         <panel class=" modern">
           <panel-header classname="">
             <label class=""style="margin-left:-40px;">
                 <?php _e('最近更新的插件'); ?>
             </label>
           </panel-header>
  <panel-body style="padding-top:5px;padding-bottom:10px;margin-left:-20px;width:100%;" classname="">                  
<?php 
				$packages = DB::fetch_all("SELECT `ID`, `DownloadTimes`, `Description`, `Name`, `Package` FROM `".DCRM_CON_PREFIX."Packages` WHERE (`Stat` = '1') ORDER BY `CreateStamp` DESC LIMIT " . DCRM_SHOW_NUM);
				foreach($packages as $package) {
                if ($isCydia) { 
?>
             <a href="cydia://package/<?php echo($package['Package']); ?>" target="_blank">
               <table cols="2" border="0" class=" information" >
                  <tbody class="">
                    <tr class="">
                      <td style="margin-top:6px;font-size:16px;"> 
                          <img class="icon" src="./recent.png" style="width: 25px; height: 25px; position:relative;left:-11px;top:6px;"><?php echo($package['Name']); ?>
                      </td>
                    </tr>
                  </tbody>
                 </table> 
               </a> 
              <div class=" fading-sep"></div>
<?php	} else { ?>
              <a href="<?php echo_rewrite_url('view', $package['ID']);?>">  
                <table cols="2" border="0" class=" information" >
                  <tbody class="">
                    <tr class="">
                      <td style="margin-top:6px;font-size:16px;"> 
                          <img class="icon" src="./recent.png" style="width: 25px; height: 25px; position:relative;left:-11px;top:6px;"><?php echo($package['Name']); ?>
                      </td>
                    </tr>
                  </tbody>
                 </table> 
               </a> 
              <div class=" fading-sep"></div>
<?php				
       }  
     }        
?>	
             <a href="../qb.php" target="_blank">     
             <table cols="2" border="0" class=" information" >
               <tbody class="">
                 <tr class="">
                   <td style="margin-top:6px;font-size:18px;">
                       <img class="icon" src="./recent.png" style="width: 25px; height: 25px; position:relative;left:-11px;top:6px;"><?php _e('查看全部软件包'); ?>
                   </td>
                 </tr>
               </tbody>
             </table> 
             </a>  
       </panel-body>
		</panel>
              
          <panel style="margin-top:50px;" class="modern">
  			   <a style="padding-top: 0px" href="//cydia.saurik.com/account" class="">
  			   <panel-body style="margin-top:-25px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="account.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">管理账户
                    </div></div>
                      </panel-body>
                 </a> 
       </panel>  
           
       <panel style="margin-top:20px;width:100%;" class="modern">
  			   <a style="padding-top: 0px" href="../help" class="">
  			   <panel-body style="margin-top:-25px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="../manage/Speed/icon/dh.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">升级和越狱帮助
                    </div></div>
                      </panel-body>
                 </a> 
           	<a style="padding-top: 0px;" href="../Sections1.php" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="ios.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">为应用程序查找扩展
                    </div></div>
                      </panel-body>
                 </a> 
               <a style="padding-top: 0px;" href="//cydia.saurik.com/sources.html" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">更多软件源
                    </div></div>
                      </panel-body>
                 </a>
       </panel>  
           
         <panel class=" modern">
           <panel-header classname="">
             <label class=""style="margin-left:-40px;">
                 <?php _e('用户指南'); ?>
             </label>
           </panel-header>
           
       <panel style="margin-top:30px;width:100%;" class="modern">
  			   <a style="padding-top: 0px" href="../help/3.php" class="">
  			   <panel-body style="margin-top:-25px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">常见问题
                    </div></div>
                      </panel-body>
                 </a> 
           	<a style="padding-top: 40px;" href="//cydia.saurik.com/copying.html" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">设备文件拷贝
                    </div></div>
                      </panel-body>
                 </a> 
               <a style="padding-top: 0px;" href="../help/4.php" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">OpenSSH 访问教程
                    </div></div>
                      </panel-body>
                 </a>
               <a style="padding-top: 0px;" href="cydia://package/com.cydia.love.sshpasswd" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">Root 密码修改
                    </div></div>
                      </panel-body>
                 </a>   
              <a style="padding-top: 0px;" href="//cydia.saurik.com/storage.html" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">存储详情
                    </div></div>
                      </panel-body>
                 </a>  
       </panel>  
           
         <panel class=" modern">
           <panel-header classname="">
             <label class=""style="margin-left:-40px;">
                 <?php _e('社区'); ?>
             </label>
           </panel-header>
           
          <panel style="margin-top:50px;width:100%;" class="modern">
  			   <a style="padding-top: 0px" href="//blog.cydia.love" class="">
  			   <panel-body style="margin-top:-25px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">
                  <img class="icon" src="reddit.png" style="width: 25px; height: 25px;float:left;margin-top:-5px;">越狱博客
                    </div></div>
                      </panel-body>
                 </a> 
       </panel>  

          <panel class=" modern">
           <panel-header classname="">
             <label class=""style="margin-left:-40px;">
                 <?php _e('开发者专区'); ?>
             </label>
           </panel-header>
            
       <panel style="margin-top:50px;" class="modern">
  			   <a style="padding-top: 0px" href="//cydia.saurik.com/faq/developing.html" class="">
  			   <panel-body style="margin-top:-25px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">开发者常用资源
                    </div></div>
                      </panel-body>
                 </a> 
       </panel> 
            
        <panel style="margin-top:10px;" >
           	<a style="padding-top: 0px;width:100%;" href="//cydia.saurik.com/credits.html" class="">
  			   <panel-body style="margin-top:0px;width:100%;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">致谢 / 感谢
                    </div></div>
                      </panel-body>
                 </a> 
               <a style="padding-top: 0px;" href="//cydia.saurik.com/sources.html" class="">
  			   <panel-body style="margin-top:0px;background: linear-gradient(45deg,rgba(254,172,94,0.5),rgba(199,121,208,0.5),rgba(75,192,200,0.5)); !important;font-size:16px;margin-left:-20px;width:100%;font-weight:700;letter-spacing:0.5px" classname="green-gradient">
                <div style="padding-top: 0px" class="reviews-more">
                 <div style="color: white !important" class="">免责声明
                    </div></div>
                      </panel-body>
                 </a>
       </panel>             

<footer>	
<?php  
require_once('iphone.php');  
?>  
         <panel class=" modern">
           <panel-header classname="">
             <label class=""style="margin-left:-40px;">
                 <?php _e('设备信息'); ?>
             </label>
           </panel-header>  
  <panel-body style="font-size:14px;width:100%;margin-left:-20px;" classname="">
            <div class="package-description"></div>
            <div style="text-align: left; width: 100%;">
              <p style='margin-top:14px;font-size:14px;'>当前Cydia版本:<?php echo determinebrowser ();?></p> 
              <div class='fading-sep'></div>
              <p style='margin-top:14px;font-size:14px;'>当前设备型号:<?php echo getPhone();?></p>    
              <div class='fading-sep'></div>
               <p style='margin-top:14px;font-size:14px;'>当前设备版本:<?php echo getOS();?></p>    
              <div class='fading-sep'></div>
              <p style='margin-top:14px;font-size:14px;'>当前设备IP地址:<?php echo($_SERVER['REMOTE_ADDR']); ?></p>
<?php  
  if($_SESSION["UDID"]=="") {
?>
  <div class='fading-sep'></div>
    <p style='margin-top:14px;font-size:14px;'>设备UDID:未检测到设备UDID</p>
<?php }else{ ?> 
  <div class='fading-sep'></div>
    <p style='margin-top:14px;font-size:14px;'>设备UDID:<?php echo $_SESSION["UDID"];?></p>
<?php         
   }                
?>
            </div>
   </panel-body> 

  
<?php  

function getOS()  
{  
    $ua = $_SERVER['HTTP_USER_AGENT'];//这里只进行IOS和Android两个操作系统的判断，其他操作系统原理一样  
    if (strpos($ua, 'Android') !== false) {//strpos()定位出第一次出现字符串的位置，这里定位为0  
        preg_match("/(?<=Android )[\d\.]{1,}/", $ua, $version);  
        echo 'Platform:Android OS_Version:'.$version[0];  
    } elseif (strpos($ua, 'iPhone') !== false) {  
        preg_match("/(?<=CPU iPhone OS )[\d\_]{1,}/", $ua, $version);  
        echo 'IOS'.str_replace('_', '.', $version[0]);  
    } elseif (strpos($ua, 'iPad') !== false) {  
        preg_match("/(?<=CPU OS )[\d\_]{1,}/", $ua, $version);  
        echo 'IOS'.str_replace('_', '.', $version[0]);  
    }   
}  
     
?>   
<?php
function determinebrowser () {

    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '' ;
    $browseragent = "";   //浏览器
    $browserversion = ""; //浏览器的版本
    if (preg_match('/360SE/i',$agent)) {
        $browserversion = '';
        $browseragent = "360浏览器";
    } else if(preg_match('/Maxthon (([0-9_.]{1,2}+))/i',$agent,$version)){
        $browserversion = $version[1];
        $browseragent = "Maxthon";
    }else if (preg_match('/MSIE (([0-9_.]{1,2})+)/i',$agent,$version)) {
        $browserversion = $version[1];
        $browseragent = "Internet Explorer";
    } else if (preg_match( '/iOpera\/(([0-9_.]{1,2})+)/i',$agent,$version)) {
        $browserversion = $version[1];
        $browseragent = "Opera";
    } else if (preg_match( '/Firefox\/(([0-9_.]{1,3})+)/i',$agent,$version)) {
        $browserversion=$version[1];
        $browseragent = "Firefox";
    } else if (preg_match( '/QQ\/(([0-9_.]{1,5})+)/i',$agent,$version)) {
        $browserversion = $version[1];
        $browseragent = "QQ";
    } else if (preg_match( '/Cydia\/(([0-9_.]{1,5})+)/',$agent,$version)) {
        $browserversion = $version[1];
        $browseragent = "Cydia";  
    } else if (preg_match( '/Safari\/(([0-9_.]{1,5})+)/',$agent,$version)) {
        $browserversion = $version[1];
        $browseragent = "Safari";
    } else {
        $browserversion = "";
        $browseragent = "Unknown";
    }
    return $browseragent." ".$browserversion;
}

 ?> 
  
</footer>          
          

    </body>       
   </html>    
     
     
     
     
     