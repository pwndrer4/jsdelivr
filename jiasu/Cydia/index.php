<?php
require_once('../system/common.inc.php');
base_url();

// URL For Rewrite 
$rewrite_mod = get_option('rewrite_mod');
switch($rewrite_mod){
	case 3:
          $rewrite_url = array( 'misc2' => '../Cydia.php');
		break;
}


class_loader('Mobile_Detect');
$detect = new Mobile_Detect;
if(!$detect->isiOS()){

} else {
	if (DCRM_MOBILE == 2) {
		header("Location: ".SITE_URL.$rewrite_url['misc2']);
		exit();
	} else {
		$isCydia = false;
	}
}
?>