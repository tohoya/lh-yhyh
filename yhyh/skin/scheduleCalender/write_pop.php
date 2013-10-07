<?php
if(!$_REQUEST["_year"]) $_REQUEST["_year"] = date("Y");
if(!$_REQUEST["_month"]) $_REQUEST["_month"] = date("n");
if(!$_REQUEST["_day"]) $_REQUEST["_day"] = date("d");

$_result->start_time = mktime(9, 0, 0, $_REQUEST["_month"], $_REQUEST["_day"], $_REQUEST["_year"]);
$_result->end_time = mktime(10, 0, 0, $_REQUEST["_month"], $_REQUEST["_day"], $_REQUEST["_year"]);

if($_REQUEST["_no"]) {
	$s_field = "
	yhb_title
	, yhb_name
	, yhb_homepage
	, yhb_email
	, yhb_content
	, yhb_secret
	, yhb_secret_pass
	, yhb_category
	, yhb_check
	, yhb_notice
	, yhb_reply_mail
	, yhb_file
	, yhb_sizes
	, yhb_download
	, yhb_text1
	, yhb_text2
	";
	$query = "select ".$s_field." from yh_board where yhb_number = '".$_REQUEST["_no"]."'";
	$_bb = $_LhDb->Fetch_Object_Query($query);
	$_result->title = $_bb->yhb_title;
	$_result->name = $_bb->yhb_name;
	$_result->homepage = $_bb->yhb_homepage;
	$_result->email = $_bb->yhb_email;
	$_result->content = $_bb->yhb_content;
	
	$_result->category = $_bb->yhb_category;
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
	$_result->start_time = $_bb->yhb_text1;
	$_result->end_time = $_bb->yhb_text2;
}

$_result->start_time_date = date("Y.m.d", $_result->start_time);
$_result->start_time_hour = date("H", $_result->start_time);
$_result->start_time_minute = date("i", $_result->start_time);

$_result->end_time_date = date("Y.m.d", $_result->end_time);
$_result->end_time_hour = date("H", $_result->end_time);
$_result->end_time_minute = date("i", $_result->end_time);

include_once($_skin_root."write.php");
?>