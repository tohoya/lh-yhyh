<?php


$_m_result = $_LhDb->Get_Member($_REQUEST["_m_no"], ($_REQUEST["_auto"] != "false"));
if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_m_result->yhb_group_no;

// 그룹 정보
$_g_result = $_LhDb->Get_Group($_REQUEST["_group"]);


$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern."|(&)*_writeMode=".$_p_pattern;
$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['HTTP_REFERER']));
if($query_string) $query_string = "&".$query_string;

$referer_url = eregi_replace("^([a-z0-9A-Z])*://", "", $_SERVER['HTTP_REFERER']);
$protocol = str_replace($referer_url, "", $_SERVER['HTTP_REFERER']);
//echo("1:".$_SERVER['HTTP_REFERER']."<br>2:".$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
if($_SERVER['HTTP_REFERER'] != $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) {
	$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "register_back");
	//echo "<br>save";
}

//echo("<br>3:".$_LhDb->Return_Url_Get("register_back"));

$_back_link = $_LhDb->Return_Url_Get("register_back") ? $_LhDb->Return_Url_Get("register_back") : $_SERVER['HTTP_REFERER'];

@include_once($_skin_group_root."register.php");
?>