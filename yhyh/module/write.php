<?php
if($_REQUEST["_no"]) {
	$s_field = "
	yhb_title
	, yhb_name
	, yhb_homepage
	, yhb_email
	, yhb_content
	, yhb_secret
	, yhb_secret_pass
	, yhb_check
	, yhb_notice
	, yhb_reply_mail
	, yhb_file
	, yhb_sizes
	, yhb_download
	";
	$query = "select ".$s_field." from yh_board where yhb_number = '".$_REQUEST["_no"]."'";
	$_bb = $_LhDb->Fetch_Object_Query($query);
	$_result->title = $_bb->yhb_title;
	$_result->name = $_bb->yhb_name;
	$_result->homepage = $_bb->yhb_homepage;
	$_result->email = $_bb->yhb_email;
	$_result->content = $_bb->yhb_html != 2 ? nl2br($_bb->yhb_content) : $_bb->yhb_content;
	
	$_result->secret = $_bb->yhb_secret;
	$_result->secret_pass = $_bb->yhb_secret_pass;
	$_result->check = $_bb->yhb_check;
	$_result->notice = $_bb->yhb_notice;
	$_result->reply_mail = $_bb->yhb_reply_mail;
	
	$_result->secret_checked = $_bb->yhb_secret ? " checked" : "";
	$_result->check_checked = $_bb->yhb_check ? " checked" : "";
	$_result->notice_checked = $_bb->yhb_notice ? " checked" : "";
	$_result->reply_mail_checked = $_bb->yhb_reply_mail ? " checked" : "";
	
	/* 파일 정리 */
	$file_names = split("\*", $_bb->yhb_file);
	$count = sizeof($file_names);
	
	if($count > 0 && trim($_bb->yhb_file)) {
		
		$file_sizes = split("\*", $_bb->yhb_sizes);
		$file_downs = split("\*", $_bb->yhb_download);
		$field = "seq
			, group_no
			, board_no
			, board_name
			, f_name
			, s_name
			, f_size
			, f_ext
			, f_download
			, f_order
		";
		for($i = 0; $i < $count; $i++) {
			$f_name = $file_names[$i];
			$f_size = $file_sizes[$i];
			
			$f_save = "files".$_bb->yhb_reg_date.($i).".".File_ext($f_name);
			$url = _lh_yhyh_web."/upload/".$f_save;
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$url)) {
				$_idx = TableMax("yh_file", "seq", "1", "");
				
				$_files .= $_files ? "_***_" : "";
				$_files .= $url."_**_".$f_save."_**_".$f_name."_**_".$f_size."_**_".File_ext($f_name)."_**_".$_idx."_**_no";
				
				$values = "'".$_idx."'
					, '".$_config->yhb_group_no."'
					, '".$_REQUEST["_no"]."'
					, '".$_REQUEST["_id"]."'
					, '".$f_name."'
					, '".$f_save."'
					, '".$f_size."'
					, '".File_ext($f_name)."'
					, '".$file_downs[$i]."'
					, '".($i + 1)."'
				";
				
				$query = "insert into yh_file(".$field.") values(".$values.")";
				$_LhDb->Query($query);
				//echo $query."<br>";
			}
		}
		$query = "update yh_board set yhb_file = '', yhb_sizes = '', yhb_download = '' where yhb_number = '".$_REQUEST["_no"]."'";
		$_LhDb->Query($query);
		
	} else {
		$s_field = "
		f_name
		, s_name
		, f_size
		, f_ext
		, seq
		";
		$query = "select ".$s_field." from yh_file where board_no = '".$_REQUEST["_no"]."' order by f_order asc";
		
		$result = $_LhDb->Query($query);
		while($ff = $_LhDb->Fetch_Object($result)) {
			$url = _lh_yhyh_web."/upload/".$ff->s_name;
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$url)) {
				$_files .= $_files ? "_***_" : "";
				$_files .= $url."_**_".$ff->s_name."_**_".$ff->f_name."_**_".$ff->f_size."_**_".$ff->f_ext."_**_".$ff->seq."_**_no";
			}
		}
	}
}

if(strtolower($_REQUEST["_writeMode"]) != "modify") {
	
	switch(strtolower($_REQUEST["_writeMode"])) {
		case "reply":
			$_result->content = "<br/><br/><hr/><p style=\"padding:3px 10px 0px;\"><b>".$_result->name." 님의 원문입니다.</b></p><hr/><br/><br/>".$_result->content;
		break;
		default:
	}
	
	$_files = "";
	//if($HTTP_COOKIE_VARS[yh_board_title]) $_result->title = htmlspecialchars(stripslashes($HTTP_COOKIE_VARS[yh_board_title]));
	//if($HTTP_COOKIE_VARS[yh_board_name]) $_result->name = htmlspecialchars(stripslashes($HTTP_COOKIE_VARS[yh_board_name]));
	//if($HTTP_COOKIE_VARS[yh_board_home]) $_result->homepage = $HTTP_COOKIE_VARS[yh_board_home];
	//if($HTTP_COOKIE_VARS[yh_board_email]) $_result->email = $HTTP_COOKIE_VARS[yh_board_email];
	
	if($_member->yhb_name) $_result->name = $_member->yhb_name;
	if($_member->yhb_homepage) $_result->homepage = $_member->yhb_homepage;
	if($_member->yhb_email) $_result->email = $_member->yhb_email;
}

if(strtolower($_REQUEST["_writeMode"]) == "reply") {
	$_grant = $_LhDb->Grant_Reply();
} else if(!strtolower($_REQUEST["_writeMode"])) {
	$_grant = $_LhDb->Grant_Write();
} else {
}

$referer_url = eregi_replace("^([a-z0-9A-Z])*://", "", $_SERVER['HTTP_REFERER']);
$protocol = str_replace($referer_url, "", $_SERVER['HTTP_REFERER']);

//echo("1:".$_SERVER['HTTP_REFERER']."<br>2:".$_LhDb->Return_Url_Get("write_login"));
if($_SERVER['HTTP_REFERER'] != $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) {
	if($_SERVER['HTTP_REFERER'] != $_LhDb->Return_Url_Get("write_login")) {
		$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "write_back");
		//echo "<br>save";
	}
}

//echo("<br>3:".$_LhDb->Return_Url_Get("write_back"));

if($_grant) {
	
	$_login_grant_link .= "&_returnType=write";
	$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "back");
	
	$_list_link = $_LhDb->Return_Url_Get("back");
	
	$_LhDb->Return_Url_Set($protocol.$_SERVER['HTTP_HOST'].$_login_grant_link, "write_login");
	
	switch($_grant->type) {
		case "message":
			include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
		break;
		case "message_view":
			include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
		break;
		case "message_login":
			include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
		break;
	}
} else {
	
	$_back_link = $_LhDb->Return_Url_Get("write_back") ? $_LhDb->Return_Url_Get("write_back") : $_SERVER['HTTP_REFERER'];
	
	include_once($_skin_root."write.php");
}
?>