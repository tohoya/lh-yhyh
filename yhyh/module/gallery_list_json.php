<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_REQUEST["_id"]) {
	if(!$_REQUEST["_width"]) $_REQUEST["_width"] = 110;
	if(!$_REQUEST["_height"]) $_REQUEST["_height"] = 120;
	
	if(!$_REQUEST["_start_idx"]) $_REQUEST["_start_idx"] = 0;
	if(!$_REQUEST["_limit"]) $_REQUEST["_limit"] = 6;
	// list 게시물 가져오기
	$where = " where yhb_board_name = '".$_REQUEST["_id"]."'";
	
	// 관리자 1:1 게시판에 대한 조건
	if($_group->yhb_use_agreement == 1 || $_config->yhb_use_agreement == 1) {
		$where .= " AND (yhb_check = 1 OR ";
		$where .= ($_member->yhb_number) ? "yhb_id = '".$_member->yhb_id."' AND yhb_pass='".$_member->yhb_pass."'))" : "yhb_ip = '".$_SERVER['REMOTE_ADDR']."')";
	}
	
	// 카테고리값이 있을때
	if($_REQUEST["_ct"]) $where .= " AND (yhb_category = '".$ct."') OR ($id_list AND yhb_notice='1')";

	// 관리자 글 표시여부
	if($_config->yhb_use_notice_admin == 1) $where .= " OR yhb_board_name = 'admin'";

	// 1:1게시판일경우
	if($_config->yhb_use_member == 1) $where .= " AND (yhb_view_member = '".$_member->yhb_mysign."' OR yhb_mysign = '".$_member->yhb_mysign."' OR yhb_view_member = '')";
	
	// 답글들만 정렬할때
	if($_REQUEST["_no"] && $_config->yhb_use_reply_list == 1 && $_config->yhb_use_list != 1) $where .= " AND yhb_number_up = '".$_result->yhb_number_up."'";

	// 검색어로 검색하기
	if($_REQUEST["_search"]) {
		$where .= "AND (";
		
		$sArr = split("/", $_REQUEST["_search_t"]);
		$sCount = sizeof($sArr);
		for($si = 0; $si < $sCount; $si++) {
			$where .= $si > 0 ? " OR" : "";
			$where .= $sArr[$si]." like '%".$_REQUEST["search"]."%'";
		}
		$where .= ")";
	}
	
	$where .= " AND yhb_number = board_no AND s_name != '' AND (yf.f_ext = 'png' OR f_ext = 'jpg' OR f_ext = 'bmp' OR f_ext = 'jpeg' OR f_ext = 'gif' OR f_ext = 'pic') GROUP BY board_no ORDER BY yhb_notice DESC, yhb_number_up DESC, yhb_number_rows ASC, s_name DESC, f_order ASC";
	
	$s_field = "
	yhb_number
	, yhb_number_rows
	, yhb_title
	, yhb_name
	, yhb_html
	, yhb_content
	, yhb_reg_date
	, yhb_hit
	, yhb_ip
	, yhb_vote
	, f_name
	, s_name
	, f_size
	, f_ext
	, f_download
	, f_order
	";
	
	$query_gallery = "select ".$s_field." from yh_board as yb, yh_file as yf".$where;
	//echo $query_gallery;
	$_total_rows = $_LhDb->Query_Row_Num($query_gallery);
	$result_gallery = $_LhDb->Query($query_gallery." limit ".$_REQUEST["_start_idx"].", ".$_REQUEST["_limit"]);
	
	$i = 0;
	
	$_result = array();
	
	$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern;
	$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
	if($query_string) $query_string = "&".$query_string;
	
	while($bb = $_LhDb->Fetch_Object($result_gallery)) {
		//"/yh/".$_REQUEST["_id"]."/".$bb->yhb_number;
		$_grant_view = $_LhDb->Grant_View($bb);
		$result = "";
		$result->no = $_total_rows - $_REQUEST["_start_idx"] - $i;
		$result->number = $bb->yhb_number;
		$result->title = ($bb->yhb_notice) ? "[공지] " : "";
		$result->title .= ($bb->yhb_number_rows > 0) ? "[답글] " : "";
		$result->title .= trim($bb->yhb_title) ? htmlspecialchars($bb->yhb_title) : "제목이 없는 게시물입니다.";
		$result->title_link = ($_rewrite_mod) ? "/yh/".$_REQUEST["_id"]."/".$bb->yhb_number : $PHP_SELF."?".$query_string."&_no=".$bb->yhb_number;
		$result->name = $bb->yhb_name;
		$result->date = $bb->yhb_reg_date;
		$result->hit = $bb->yhb_hit;
		
		$_LhDb->Old_File_Converter($bb);
		
		$s_field = "
		f_name
		, s_name
		, f_size
		, f_ext
		, f_download
		, f_order
		";
		
		$f_query = "select ".$s_field." from yh_file where board_no = '".$bb->yhb_number."' AND s_name != '' order by f_order";
		$result->file = "";
		if($_LhDb->Query_Row_Num($f_query) > 0) {
			$f_result = $_LhDb->Query($f_query);
			$f_i = 0;
			while($f_data = $_LhDb->Fetch_Object($f_result)) {
				switch(strtolower($f_data->f_ext)) {
					case "png":
					case "jpg":
					case "bmp":
					case "jpeg":
					case "gif":
					case "pic":
						if(trim($f_data->s_name) && file_exists(_lh_document_root._lh_yhyh_web."/upload/".trim($f_data->s_name))) {
							$result->file[$f_i]->f_name = trim($f_data->f_name);
							$result->file[$f_i]->s_name = trim($f_data->s_name);
							$result->file[$f_i]->url = _lh_yhyh_web."/upload/".trim($f_data->s_name);
							$result->file[$f_i]->f_size = trim($f_data->f_size);
							$result->file[$f_i]->f_ext = trim($f_data->f_ext);
							$result->file[$f_i]->f_download = trim($f_data->f_download);
							$result->file[$f_i]->f_order = trim($f_data->f_order);
							$f_i++;
						}
					break;
				}
			}
		}
		if(sizeof($result->file) > 0 && $result->file) {
			//echo "<br>".$i;
			$_result[$i] = $result;
			$i++;
		}
	}
	$count = sizeof($_result);
?>{ "total_count" : "<?=$_total_rows?>", "gallery" : [<?
	for($i = 0; $i < $count; $i++) {
		$f_count = sizeof($_result[$i]->file);
		if($f_count > 0 && $_result[$i]->file) {
			if($i > 0) {
?>,<?
			}
?>
{"yhb_no":"<?=$_result[$i]->no?>"
, "yhb_number":"<?=$_result[$i]->number?>"
, "yhb_title":"<?=$_result[$i]->title?>"
, "yhb_title_link":"<?=$_result[$i]->title_link?>"
, "yhb_name":"<?=$_result[$i]->name?>"
, "yhb_date":"<?=$_result[$i]->date?>"
, "yhb_hit":"<?=$_result[$i]->hit?>"
<?
			//echo "////".$_result[$i]->file."/////";
			if($f_count > 0 && $_result[$i]->file) {
?>, "yhb_file":[<?
				for($f_i = 0; $f_i < $f_count; $f_i++) {
					$f_data = $_result[$i]->file[$f_i];
					$img_info = $_LhDb->Image_Resize(_lh_document_root._lh_yhyh_web."/upload/".$f_data->s_name, $_REQUEST["_width"], $_REQUEST["_height"]);
					if($f_i > 0) {
?>,<?
					}
?>
	{"f_name":"<?=$f_data->f_name?>"
	, "s_name":"<?=$f_data->s_name?>"
	, "url":"<?=_lh_yhyh_web?>/upload/<?=$f_data->s_name?>"
	, "f_size":"<?=$f_data->f_size?>"
	, "f_ext":"<?=$f_data->f_ext?>"
	, "f_download":"<?=$f_data->f_download?>"
	, "f_order":"<?=$f_data->f_order?>"
	, "f_width":"<?=$img_info->width?>"
	, "f_height":"<?=$img_info->height?>"
	}
<?
				}
?>	]<?
			}
?>

}<?
		}
	}
?>]}<?
	exit();
}
echo("");
?>