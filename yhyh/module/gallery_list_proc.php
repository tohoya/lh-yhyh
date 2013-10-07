<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_REQUEST["_id"]) {
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
	";
	$query_gallery = "select ".$s_field." from yh_board".$where;
	$query .= " order by yhb_notice desc, yhb_number_up desc, yhb_number_rows asc limit ".$_REQUEST["_start_idx"].", ".$_REQUEST["_limit"];
	
	echo $query;
	$result = $_LhDb->Query($query);
	
	$i = 0;
	$_total_rows = $_LhDb->Query_Row_Num($query);
	$_result = array();
	
	$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern;
	$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
	if($query_string) $query_string = "&".$query_string;
	
	?>
		<div class="complete">
	<?
	while($bb = $_LhDb->Fetch_Object($result)) {
		//"/yh/".$_REQUEST["_id"]."/".$bb->yhb_number;
		$_grant_view = $_LhDb->Grant_View($bb);
		$_result[$i]->no = $_REQUEST["_start_idx"] - $i;
		$_result[$i]->number = $bb->yhb_number;
		$_result[$i]->title = ($bb->yhb_notice) ? "[공지] " : "";
		$_result[$i]->title .= ($bb->yhb_number_rows > 0) ? "[답글] " : "";
		$_result[$i]->title .= trim($bb->yhb_title) ? $bb->yhb_title : "제목이 없는 게시물입니다.";
		$_result[$i]->title_link = ($_rewrite_mod) ? "/yh/".$_REQUEST["_id"]."/".$bb->yhb_number : $PHP_SELF."?".$query_string."&_no=".$bb->yhb_number;
		//if($_grant_view) $_result[$i]->title_link = "javascript:Grant_No_View('".$_grant_view->message."');";
		$_result[$i]->name = $bb->yhb_name;
		$_result[$i]->date = $bb->yhb_reg_date;
		$_result[$i]->hit = $bb->yhb_hit;
		
		$_LhDb->Old_File_Converter($bb);
		
		$s_field = "
		f_name
		, s_name
		, f_size
		, f_ext
		, f_download
		, f_order
		";
		
		$f_query = "select ".$s_field." from yh_file where board_no = '".$bb->yhb_number."' order by f_order";
		?>
			<ul>
				<li class="_yhb_no"><?=$_result[$i]->no?></li>
				<li class="_yhb_number"><?=$_result[$i]->number?></li>
				<li class="_yhb_title"><?=$_result[$i]->title?></li>
				<li class="_yhb_title_link"><?=$_result[$i]->title_link?></li>
				<li class="_yhb_name"><?=$_result[$i]->name?></li>
				<li class="_yhb_date"><?=$_result[$i]->date?></li>
				<li class="_yhb_hit"><?=$_result[$i]->hit?></li>
				<?
				if($_LhDb->Query_Row_Num($f_query) > 0) {
				?>
				<li class="_yhb_file">
				<?
					$f_result = $_LhDb->Query($f_query);
					$f_i = 0;
					while($f_data = $_LhDb->Fetch_Object($f_result)) {
						$img_info = $_LhDb->Image_Resize(_lh_document_root._lh_yhyh_web."/upload/".$f_data->s_name, $_REQUEST["_width"], $_REQUEST["_height"]);
				?>
					<ul>
						<li class="_f_name"><?=$f_data->f_name?></li>
						<li class="_s_name"><?=$f_data->s_name?></li>
						<li class="_url"><?=_lh_yhyh_web?>/upload/<?=$f_data->s_name?></li>
						<li class="_f_size"><?=$f_data->f_size?></li>
						<li class="_f_ext"><?=$f_data->f_ext?></li>
						<li class="_f_download"><?=$f_data->f_download?></li>
						<li class="_f_order"><?=$f_data->f_order?></li>
						<li class="_f_width"><?=$img_info->width?></li>
						<li class="_f_height"><?=$img_info->height?></li>
					</ul>
				<?
					$f_i++;
				}
				?>
				</li>
				<? } ?>
			</ul>
		<?
		$i++;
	}
	?>
		</div>
	<?
	exit();
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>