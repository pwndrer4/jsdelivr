<?php  
function getPhone()  
{  
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone1,1'){ echo 'iPhone 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone1,2'){ echo 'iPhone 3G 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone2,1'){ echo 'iPhone 3GS 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone3,1'){ echo 'iPhone 4 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone3,3'){ echo 'iPhone 4 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone4,1'){ echo 'iPhone 4S 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone5,1'){ echo 'iPhone 5 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone5,2'){ echo 'iPhone 5 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone5,3'){ echo 'iPhone 5c 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone5,4'){ echo 'iPhone 5c 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone6,1'){ echo 'iPhone 5s(A1433,A1533)';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone6,2'){ echo 'iPhone 5s 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone7,1'){ echo 'iPhone 6Plus 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone7,2'){ echo 'iPhone 6 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone8,1'){ echo 'iPhone 6S 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone8,2'){ echo 'iPhone 6SPlus 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone8,4'){ echo 'iPhone SE 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone9,1'){ echo 'iPhone 7 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone9,3'){ echo 'iPhone 7 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone9,2'){ echo 'iPhone 7Plus 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone9,4'){ echo 'iPhone 7Plus 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone10,1'){ echo 'iPhone 8 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone10,4'){ echo 'iPhone 8 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone10,2'){ echo 'iPhone 8Plus 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone10,5'){ echo 'iPhone 8Plus 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone10,3'){ echo 'iPhone X 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone10,6'){ echo 'iPhone X 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone11,4'){ echo 'iPhone XS Max 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone11,6'){ echo 'iPhone XS Max 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone11,2'){ echo 'iPhone XS 64位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPhone11,3'){ echo 'iPhone XR 64位设备';}      

//iPad1
if($_SERVER['HTTP_X_MACHINE'] == 'iPad1,1'){ echo 'iPad1 32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad1,1'){ echo 'iPad1 32位设备';}

//iPad2
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,1'){ echo 'iPad-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,2'){ echo 'iPad-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,3'){ echo 'iPad-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,4'){ echo 'iPad-32位设备';}

//iPadMini
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,5'){ echo 'iPad-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,6'){ echo 'iPad-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad2,7'){ echo 'iPad-32位设备';}

//iPad3
if($_SERVER['HTTP_X_MACHINE'] == 'iPad3,1'){ echo 'iPad3-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad3,2'){ echo 'iPad3-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad3,3'){ echo 'iPad3-32位设备';}

//iPad4
if($_SERVER['HTTP_X_MACHINE'] == 'iPad3,4'){ echo 'iPad4-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad3,5'){ echo 'iPad4-32位设备';}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad3,6'){ echo 'iPad4-32位设备';}

//iPadAIR
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,1'){ echo 'iPadAIR-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,2'){ echo 'iPadAIR-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,3'){ echo 'iPadAIR-64位设备' ;}

//iPadMini2
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,4'){ echo 'iPadMini2-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,5'){ echo 'iPadMini2-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,6'){ echo 'iPadMini2-64位设备' ;}

//iPadMini3
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,7'){ echo 'iPadMini3-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,8'){ echo 'iPadMini3-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad4,9'){ echo 'iPadMini3-64位设备' ;}

//iPadMini4
if($_SERVER['HTTP_X_MACHINE'] == 'iPad5,1'){ echo 'iPadMini4-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad5,2'){ echo 'iPadMini4-64位设备' ;}

//iPadAIR2
if($_SERVER['HTTP_X_MACHINE'] == 'iPad5,3'){ echo 'iPadAIR2-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad5,4'){ echo 'iPadAIR2-64位设备' ;}

//iPadPRO12.9"
if($_SERVER['HTTP_X_MACHINE'] == 'iPad6,3'){ echo 'iPadPRO12.9-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad6,4'){ echo 'iPadPRO12.9-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad6,4'){ echo 'iPadPRO12.9-64位设备' ;}

//iPadPRO9.7"
if($_SERVER['HTTP_X_MACHINE'] == 'iPad6,7'){ echo 'iPadPRO9.7-64位设备' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPad6,8'){ echo 'iPadPRO9.7-64位设备' ;}

//iPodTouch
if($_SERVER['HTTP_X_MACHINE'] == 'iPod1,1'){ echo 'iPodTouch2' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPod2,1'){ echo 'iPodTouch3' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPod3,1'){ echo 'iPodTouch4' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPod4,1'){ echo 'iPodTouch5' ;}
if($_SERVER['HTTP_X_MACHINE'] == 'iPod7,1'){ echo 'iPodTouch6' ;}
  
}      
?>       