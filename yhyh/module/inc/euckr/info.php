<?php
# path
$_lh_main_Root = $_SERVER['DOCUMENT_ROOT'];

// 傅农 规侥
$_rewrite_mod = false;

// 傈拳 锅龋 惫锅 沥府
$_tel_type = array("02", "051", "053", "032", "062", "042", "052", "044", "031", "033", "043", "041", "063", "061", "054", "055", "064");
// 绒措迄 锅龋 菊磊府 沥府
$_mobile_type = array("010", "011", "016", "017", "018", "019");

// 楷遏贸 傈眉
$_tel_type_all = array_merge($_mobile_type, $_tel_type);

// 八祸昏力 菩畔
$_p_pattern = "(([A-Za-z0-9_啊-芌\x20\/\.!,%])*(\-)*)*";

// 夸老 函荐 沥府
$_week_data_array = array("老", "岿", "拳", "荐", "格", "陛", "配");
// 矫埃 拌魂侩 单捞磐
$_timeStemp = array("hour"=>3600, "day"=>86400, "year"=>31536000);

$_email_type = array("naver.com"
, "gmail.com"
, "nate.com"
, "hotmail.com"
, "daum.net"
, "hanmail.net"
, "msn.com"
);

?>