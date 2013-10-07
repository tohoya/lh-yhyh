<?php

$referer_url = eregi_replace("^([a-z0-9A-Z])*://", "", $_SERVER['HTTP_REFERER']);
$protocol = str_replace($referer_url, "", $_SERVER['HTTP_REFERER']);

if($_SERVER['HTTP_REFERER'] != $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) {
	if($_SERVER['HTTP_REFERER'] != $_LhDb->Return_Url_Get("login_register")) {
		$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "login_back");
		//echo("<br>save:".$_SERVER['HTTP_REFERER']."<br>".$protocol.$_SERVER['HTTP_HOST'].$_LhDb->Return_Url_Get("login_register"));
	}
}

$query_string = eregi_replace("(&)*_returnType=".$_p_pattern, "", $query_string);

$_back_link = $_login_return_link = $_LhDb->Return_Url_Get("login_back") ? $_LhDb->Return_Url_Get("login_back") : $_SERVER['HTTP_REFERER'];
$_back_link = eregi_replace("(&)*_module=write|(&)*_writeMode=".$_p_pattern, "", $_back_link);
$_register_link = $PHP_SELF."?_module=register&_group=".$_REQUEST["_group"].$query_string;
$_LhDb->Return_Url_Set($protocol.$_SERVER['HTTP_HOST'].$_register_link, "login_register");

@include_once($_skin_group_root."login.php");
?>