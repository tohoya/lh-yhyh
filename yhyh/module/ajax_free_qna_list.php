<?php
Header_Init($_incoding_mode);
if($_REQUEST["_id"]) {
	if(!$_REQUEST["_width"]) $_REQUEST["_width"] = 110;
	if(!$_REQUEST["_height"]) $_REQUEST["_height"] = 120;
	
	if(!$_REQUEST["_start_idx"]) $_REQUEST["_start_idx"] = 0;
	if(!$_REQUEST["_limit"]) $_REQUEST["_limit"] = 6;
	
	$s_field = "
	yhb_number
	, yhb_number_rows
	, yhb_board_name
	, yhb_group_no
	, yhb_title
	, yhb_name
	, yhb_html
	, yhb_notice
	, yhb_content
	, yhb_file
	, yhb_reg_date
	, yhb_hit
	, yhb_ip
	, yhb_vote
	";
	$where = " AND yhb_number_rows = '0'";
	$query_set = $_LhDb->Get_List_Query($_REQUEST["_id"], $_REQUEST["_ct"], $s_field, "yh_board", "", $where);
	$query = $query_set->query;
	$query .= $query_set->order;
	//echo $query;
	//mysql_query($query);
	//exit();
	
	$_total_rows = $_LhDb->Query_Row_Num($query);
	$_LhDb->Query_Row_Num;
	$result = $_LhDb->Query($query." limit ".$_REQUEST["_start_idx"].", ".$_REQUEST["_limit"]);
	
	$i = 0;
	
	$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern;
	$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
	if($query_string) $query_string = "&".$query_string;
	
?>{ "total_count" : "<?=$_total_rows?>", "list" : [<?
	while($bb = $_LhDb->Fetch_Object($result)) {
		$query = "select yhb_number from yh_board where yhb_number_up = '".$bb->yhb_number."' AND yhb_number != '".$bb->yhb_number."' order by yhb_number desc";
		$re_bb = $_LhDb->Fetch_Object_Query($query);
		
		$_grant_view = $_LhDb->Grant_View($bb);
		$_result = "";
		$_result->no = $_total_rows - $_REQUEST["_start_idx"] - $i;
		$_result->notice = $bb->yhb_notice;
		$_result->number = $bb->yhb_number;
		$_result->title = ($bb->yhb_notice) ? "[공지] " : "";
		$_result->reply_no = $re_bb->yhb_number ? $re_bb->yhb_number : "";
		$_result->title .= trim($bb->yhb_title) ? htmlspecialchars($bb->yhb_title) : "제목이 없는 게시물입니다.";
		$_result->title_link = ($_rewrite_mod) ? "/yh/".$_REQUEST["_id"]."/".$bb->yhb_number : $PHP_SELF."?".$query_string."&_no=".$bb->yhb_number;
		
		$files = split("\*", $bb->yhb_file);
		$count = sizeof($files);
		$f = 0;
		//echo $_SERVER['DOCUMENT_ROOT'];
		while(!$_result->file && $f < $count) {
			$ext = $_LhDb->File_Ext($files[$f]);
			switch(strtolower($ext)) {
				case "png":
				case "jpeg":
				case "jpg":
				case "bmp":
				case "gif":
				case "pic":
					$file_url = "/yhboard/files/files".$bb->yhb_reg_date.$f.".".$ext;
					if(file_exists("/home/hosting_users/ntid/www".$file_url)) {
						$_result->file = "http://slasik.com".$file_url;
					}
				break;
			}
			$f++;
		}
		
		$_result->name = $bb->yhb_name;
		$_result->date = $bb->yhb_reg_date;
		$_result->hit = $bb->yhb_hit;
		if($i > 0) {
?>,<?
		}
?>
{"yhb_no":"<?=$_result->no?>"
, "yhb_number":"<?=$_result->number?>"
, "yhb_title":"<?=$_result->title?>"
, "yhb_title_link":"<?=$_result->title_link?>"
, "yhb_name":"<?=$_result->name?>"
, "yhb_notice":"<?=$_result->notice?>"
, "yhb_photo":"<?=$_result->file?>"
, "yhb_date":"<?=$_result->date?>"
, "yhb_reply":"<?=$_result->reply_no?>"
, "yhb_hit":"<?=$_result->hit?>"}
<?
		$i++;
	}
?>]}<?
	exit();		
}
echo("");
?>