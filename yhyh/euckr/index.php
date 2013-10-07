<?php
$_incoding_mode = eregi("euckr".$_p_pattern, $_SERVER['PHP_SELF']) ? "euckr/" : "";

if(eregi($_yhyh_web."/index.php", $PHP_SELF)) {
	$_arr = split("yhyh", dirname($_SERVER['PATH_TRANSLATED']));
	if(!$_yhyh_root) $_yhyh_root = $_arr[0];
	if(!$_yhyh_web) $_yhyh_web = "/yhyh";
	
	define(_lh_document_root, $_yhyh_root ? $_yhyh_root : $_SERVER['DOCUMENT_ROOT']);
	define(_lh_yhyh_web, $_yhyh_web ? $_yhyh_web : "/yhyh");

	if(!$_yhyh_common_load) include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."common.php");
	//echo $_yhyh_web;
	$_solo_mode = true;
}
$_board_title = $_config->yhb_title_name ? $_config->yhb_title_name : "게시판";

$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern."|(&)*_writeMode=".$_p_pattern;
$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($query_string) $query_string = "&".$query_string;

$_write_link = ($_rewrite_mod) ? "/yh/write/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string."&_module=write";
$_list_link = ($_rewrite_mod) ? "/yh/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string;

$_login_link = ($_rewrite_mod) ? "/yh/login/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string."&_module=login";
$_login_grant_link = ($_rewrite_mod) ? "/yh/login/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string."&_module=login";

$_admin_link = ($_rewrite_mod) ? "/yh/login/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string."&_module=login&_returnType=admin";
$_logout_link = ($_rewrite_mod) ? "/yh/logout/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string."&_module=logout";
$_register_link = ($_rewrite_mod) ? "/yh/register/".$_REQUEST["_id"] : $PHP_SELF."?".$query_string."&_module=register";

function Yh_Header() {
	global $_REQUEST, $_solo_mode, $_lh_main_Root, $_board_title, $_skin_group_web;
	// 스크립트 혹은 스타일이 추가될 경우 아래 경로에 있는 파일도 같이 수정해줘야한다... 혹은 별도로 IF 문 밖에 작성을 할것
	
	if($_REQUEST["_module"] == "write") {
		if($_REQUEST["_no"]) {
			$_board_title .= $_REQUEST["_writeMode"] == "modify" ? " 글 수정" : " 답글 작성";
		}
		$_board_title .= " 글 쓰기";
	}
	
	if($_solo_mode) {
?><!doctype html><html><head>
	<meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
	<meta charset="euc-kr"><title><?=$_board_title?></title><?
		include_once(_lh_document_root._lh_yhyh_web."/module/inc/css.php");
		include_once(_lh_document_root._lh_yhyh_web."/module/inc/script.php");
?></head><body><?
	} else {
		include_once(_lh_document_root._lh_yhyh_web."/module/inc/css.php");
		include_once(_lh_document_root._lh_yhyh_web."/module/inc/script.php");
	}
?><div id="yhyh_board_main_body"><h1 class="hide"><?=$_board_title?></h1><?
}

if($_REQUEST["_skin"]) {
	header("Content-Type: text/html; charset=EUC-KR");
	if(file_exists($_skin_root.$_REQUEST["_skin"].".php")) {
		include_once($_skin_root.$_REQUEST["_skin"].".php");
	}
	exit();
}

if(!$_REQUEST["_module"]) $_REQUEST["_module"] = "list";

switch($_REQUEST["_returnType"]) {
	case "list":
	case "write":
	break;
	default:
	$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "back");
}

// 모듈 코드 정리
if($_REQUEST["_module"] != "list") {
	switch($_REQUEST["_module"]) {
		case "view":
		case "delete_proc":
		case "file_delete_proc":
		case "file_upload_proc":
		case "login_proc":
		case "id_check_proc":
		case "pass_check_proc":
		case "register_proc":
		case "write_proc":
		case "gallery_list_json":
		case "gallery_list_proc":
		case "gallery_list_xml":
		case "ajax_free_loading_list":
		case "ajax_free_qna_list":
			$_proc_mode = true;
			header("Content-Type: text/html; charset=EUC-KR");
			include_once(_lh_document_root._lh_yhyh_web."/module/".$_incoding_mode.$_REQUEST["_module"].".php");
			exit();
		break;
		case "write":
			$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "list");
		break;
	}
	Yh_Header();
	include_once(_lh_document_root._lh_yhyh_web."/module/".$_REQUEST["_module"].".php");
} else {
	Yh_Header();
	$_grant = $_LhDb->Grant_List();
	
	if($_REQUEST["_speed"]) echo("\n<!-- timmer 1 : ".(time()-$_start_time)."sec -->");
	if($_grant) {
		
		$_login_grant_link .= "&_returnType=list";
		$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "back");
		
		switch($_grant->type) {
			case "message":
				include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
			break;
			case "message_login":
				include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
			break;
		}
	} else {
		if($_REQUEST["_no"]) {
			if($_REQUEST["_speed"]) echo("\n<!-- timmer view start : ".(time()-$_start_time)."sec -->");
			
			$_login_grant_link .= "&_returnType=view";
			$_grant = $_LhDb->Grant_View();
			if($_grant) {
				
				$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "back");
				
				switch($_grant->type) {
					case "message":
						include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
					break;
					case "message_list":
						include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
					break;
					case "message_login":
						include_once(_lh_document_root._lh_yhyh_web."/module/message.php");
					break;
				}
			} else {
				$_modify_link = $_modify_link_original = ($_rewrite_mod) ? "/yh/write/modify/".$_REQUEST["_id"]."/".$_REQUEST["_no"] : $PHP_SELF."?".$query_string."&_module=write&_writeMode=modify&_no=".$_REQUEST["_no"];
				$_delete_link = "javascript:Rows_Delete_Check('".$_REQUEST["_no"]."');";
				$_delete_link_original = ($_rewrite_mod) ? "/yh/delete/".$_REQUEST["_id"]."/".$_REQUEST["_no"] : $PHP_SELF."?".$query_string."&_module=delete_proc&_no=".$_REQUEST["_no"];
				
				$_reply_link = ($_rewrite_mod) ? "/yh/write/reply/".$_REQUEST["_id"]."/".$_REQUEST["_no"] : $PHP_SELF."?".$query_string."&_module=write&_writeMode=reply&_no=".$_REQUEST["_no"];
				$s_field = "
				yhb_number
				, yhb_title
				, yhb_name
				, yhb_html
				, yhb_secret
				, yhb_board_pass
				, yhb_content
				, yhb_reg_date
				, yhb_hit
				, yhb_ip
				, yhb_vote
				";
				$query = "select ".$s_field." from yh_board where yhb_number = '".$_REQUEST["_no"]."'";
				$bb = $_LhDb->Fetch_Object_Query($query);
				
				// 비밀글에 대한 권한 확인
				$s_grant = $_LhDb->Grant_Secret($bb);
				if($s_grant) {
					include_once(_lh_document_root._lh_yhyh_web."/module/password.php");
				} else {
					
					// 글 수정에 대한 권한 확인
					$_grant = $_LhDb->Grant_Modify($bb);
					
					if($_grant) {
						switch($_grant->type) {
							case "alert":
								$_modify_link = "javascript:Alert_Message('".$_grant->message."');";
							break;
							case "pass_check":
								$_modify_link = "javascript:Pass_Check_Form_Show('modify');";
							break;
						}
					}
					
					// 글 삭제에 대한 권한 확인
					$_grant = $_LhDb->Grant_Delete($bb);
					
					if($_grant) {
						switch($_grant->type) {
							case "alert":
								$_delete_link = "javascript:Alert_Message('".$_grant->message."');";
							break;
							case "pass_check":
								$_delete_link = "javascript:Pass_Check_Form_Show('delete');";
							break;
						}
					}
					
					$i = 0;
					$_result->number	= $bb->yhb_number;
					$_result->title		= $bb->yhb_title;
					$_result->name		= $bb->yhb_name;
					$_result->content	= ($bb->yhb_html == 0) ? nl2br($bb->yhb_content) : $bb->yhb_content;
					$_result->date		= $bb->yhb_reg_date;
					$_result->hit		= $bb->yhb_hit + 1;
					$_result->ip		= $bb->yhb_ip;
					$_result->vote		= $bb->yhb_vote;
		
				
					$_LhDb->Old_File_Converter($bb);
					
					/**
					 * 내용 : 파일 데이터 정리
					 * 작성자 : 진영호(reghoya@gmail.com)
					 * 작성일 : 2013. 01. 16
					 */
					$s_field = "
					f_name
					, s_name
					, f_size
					, f_ext
					, f_download
					, f_order
					";
					$f_query = "select ".$s_field." from yh_file where board_no = '".$bb->yhb_number."' order by f_order";
					if($_LhDb->Query_Row_Num($f_query) > 0) {
						$f_result = $_LhDb->Query($f_query);
						$f_i = 0;
						while($f_data = $_LhDb->Fetch_Object($f_result)) {
							$_result->file[$f_i]->f_name = $f_data->f_name;
							$_result->file[$f_i]->s_name = $f_data->s_name;
							$_result->file[$f_i]->url = _lh_yhyh_web."/upload/".$f_data->s_name;
							$_result->file[$f_i]->f_size = $f_data->f_size;
							$_result->file[$f_i]->f_ext = $f_data->f_ext;
							$_result->file[$f_i]->f_download = $f_data->f_download;
							$_result->file[$f_i]->f_order = $f_data->f_order;
							$f_i++;
						}
					}
						
					include_once($_skin_root."view.php");
					
					$query = "update yh_board set yhb_hit = yhb_hit + 1 where yhb_number = '".$_REQUEST["_no"]."'";
					$_LhDb->Query($query);
				}
				
			}
		}
		if(!$s_grant && (($_config->yhb_use_list == 1 && $_REQUEST["_no"]) || !$_REQUEST["_no"])) {
			
			if($_REQUEST["_speed"]) echo("\n<!-- timmer list start : ".(time()-$_start_time)."sec -->");
			$_LhDb->Return_Url_Set("", "back");
			
			$query_set = $_LhDb->Get_List_Query($_config->yhb_name);

			$query = $query_set->query;
			$order = $query_set->order;
			//echo $query." ".$order;
			//$link = "&_id=".$_REQUEST["_id"]."";
			
			if(!$_config->yhb_rows) $_config->yhb_rows = 20;
			if(!$_REQUEST["_page"]) $_REQUEST["_page"] = 1;
			
			$result = $_LhDb->Page_Index($query, $order, $_REQUEST["_page"], $_config->yhb_rows);
			if($_REQUEST["_speed"]) echo("\n<!-- timmer list query(".$query." ".$order.") : ".(time()-$_start_time)."sec -->");
			$i = 0;
			$_total_rows = $_LhDb->Total_Row_Count();
			$start_no = $_total_rows - ($_REQUEST["_page"] - 1) * $_config->yhb_rows;
			$_result = array();
			
			$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern;
			$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
			if($query_string) $query_string = "&".$query_string;
			
			if($_REQUEST["_speed"]) echo("\n<!-- timmer list query : ".(time()-$_start_time)."sec -->");
			
			while($bb = $_LhDb->Fetch_Object($result)) {
				//"/yh/".$_REQUEST["_id"]."/".$bb->yhb_number;
				$_grant_view = $_LhDb->Grant_View($bb);
				$_result[$i]->no = $start_no - $i;
				$_result[$i]->number = $bb->yhb_number;
				$_result[$i]->icon_viewer = ($bb->yhb_number == $_REQUEST[_no]) ? "<img src=\""._lh_skin_web."icon_viewer.gif\" alt=\"현재글\" class=\"list_viewer_img\">" : "";
				$_result[$i]->icon_notice = ($bb->yhb_notice) ? "<img src=\""._lh_skin_web."icon_notice.gif\" alt=\"공지글\" class=\"list_notice_img\">" : "";
				//'$_result[$i]->title = ($bb->yhb_notice) ? "[공지] " : "";
				$_result[$i]->icon_reply = ($bb->yhb_number_rows) ? "<img src=\""._lh_skin_web."icon_reply.gif\" alt=\"답변글\" class=\"list_reply_img\">" : "";
				$_result[$i]->icon_secret = ($bb->yhb_secret) ? "<img src=\""._lh_skin_web."icon_secret.gif\" alt=\"비밀글\" class=\"list_secret_img\">" : "";
				$_result[$i]->icon_new = ($bb->yhb_reg_date + ($_config->yhb_time * 3600) > time() && $_config->yhb_time != 0) ? "<img src=\""._lh_skin_web."icon_new.gif\" alt=\"신규글\" class=\"list_new_img\">" : "";
				//$_result[$i]->title .= ($bb->yhb_number_rows > 0) ? "[답글] " : "";
				$_result[$i]->title .= trim($bb->yhb_title) ? $bb->yhb_title : "제목이 없는 게시물입니다.";
				$_result[$i]->title_link = ($_rewrite_mod) ? "/yh/".$_REQUEST["_id"]."/".$bb->yhb_number : $PHP_SELF."?".$query_string."&_no=".$bb->yhb_number;
				//if($_grant_view) $_result[$i]->title_link = "javascript:Grant_No_View('".$_grant_view->message."');";
				$_result[$i]->name = $bb->yhb_name;
				$_result[$i]->date = $bb->yhb_reg_date;
				$_result[$i]->hit = $bb->yhb_hit;
				
				$_LhDb->Old_File_Converter($bb);
				
				/**
				 * 내용 : 파일 데이터 정리
				 * 작성자 : 진영호(reghoya@gmail.com)
				 * 작성일 : 2013. 01. 16
				 */
				$s_field = "
				f_name
				, s_name
				, f_size
				, f_ext
				, f_download
				, f_order
				";
				$f_query = "select ".$s_field." from yh_file where board_no = '".$bb->yhb_number."' order by f_order";
				if($_LhDb->Query_Row_Num($f_query) > 0) {
					$f_result = $_LhDb->Query($f_query);
					$f_i = 0;
					while($f_data = $_LhDb->Fetch_Object($f_result)) {
						$_result[$i]->file[$f_i]->f_name = $f_data->f_name;
						$_result[$i]->file[$f_i]->s_name = $f_data->s_name;
						$_result[$i]->file[$f_i]->url = _lh_yhyh_web."/upload/".$f_data->s_name;
						$_result[$i]->file[$f_i]->f_size = $f_data->f_size;
						$_result[$i]->file[$f_i]->f_ext = $f_data->f_ext;
						$_result[$i]->file[$f_i]->f_download = $f_data->f_download;
						$_result[$i]->file[$f_i]->f_order = $f_data->f_order;
						$f_i++;
					}
				}
				
				$i++;
			}
			if($_REQUEST["_speed"]) echo("\n<!-- timmer list : ".(time()-$_start_time)."sec -->");
			include_once($_skin_root."list.php");
			if($_REQUEST["_speed"]) echo("\n<!-- timmer list Out : ".(time()-$_start_time)."sec -->");
		}
	}
}
	
echo("\n<!-- timmer end : ".(time()-$_start_time)."sec -->");
if(!$_proc_mode) {
?></div><? if($_solo_mode) { ?>
</body>
</html>
<? } } ?>