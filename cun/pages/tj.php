<template>
  <div class="page">
        <div class="navbar navbar-large-transparent">
          <div class="navbar-bg"></div>
          <div class="navbar-inner">
            <div class="left">
          <a href="#" class="link back">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
            <div class="title sliding">Appstore</div>
            <div class="title-large">
              <div class="title-large-text">Appstore</div>
            </div>
          </div>
        </div>
      
    <div class="page-content">
      
      <center>

<div class="list media-list no-hairlines-between">
  <ul>
    <br>
  <?php

$string = file_get_contents("../json/appstore.json");

$json = json_decode($string, true);

foreach ($json as $app => $appkey) {
  ?>
  <li style="width:90%;border-radius:10px;margin:10px;background-color:#f2f2f2;">
      <a onclick="location='itms-services://?action=download-manifest&url=https://eonhubapp.com/plists/<?php echo $appkey['plist']; ?>.plist'" class="item-link item-content">
        <div class="item-media"><img style="border-radius:50%;height:40px;box-shadow:1px 2px 20px #fff;" src="<?php echo $appkey['icon']; ?>" width="40"/></div>
        <div class="item-inner">
          <div class="item-title-row">
            <div style="text-align:justify;" class="item-title"><?php echo $appkey['name']; ?></div>
          </div>
          <div style="text-align:justify;" class="item-subtitle"><span style="margin-right:5px;" class="badge color-red">Size: <?php echo $appkey['size']; ?> MB</span><span class="badge color-purple">Section: Appstore</span></div>
        </div>
      </a>
    </li>
<?php
}
?>
<br>
  </ul>
</div>

</center>
    </div>
  </div>
</template>