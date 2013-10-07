<?

if(!$_REQUEST["_order"]) $_REQUEST["_order"] = "yhb_name-asc";

$query = "SELECT yg.yhb_name AS yhb_group_name, yb.* FROM yh_config_board AS yb, yh_group AS yg WHERE yhb_group_no = yg.yhb_number";
if($_REQUEST["_group"]) {
	$query .= " AND yb.yhb_group_no = '".$_REQUEST["_group"]."'";
}
if($_REQUEST["_skin"]) {
	$query .= ($_REQUEST["_skin"] == "no_skin") ? " AND yb.yhb_skin = ''" : " AND yb.yhb_skin = '".$_REQUEST["_skin"]."'";
}
if($_REQUEST["_keyword"]) {
	$query .= " AND yb.yhb_name like '%".$_REQUEST["_keyword"]."%'";
}
$query .= " ORDER BY yb.".eregi_replace("-", " ", $_REQUEST["_order"]);

$result = $_LhDb->Page_Index($query, $order, $_REQUEST["_page"], 14);
$i = 0;
$_total_rows = $_LhDb->Total_Row_Count();
$start_no = $_total_rows - ($_REQUEST["_page"] - 1) * $_config->yhb_rows;

$p = "^[&]|(&)*_admin=".$_p_pattern."|(&)*_order=".$_p_pattern."|(&)*_skin=".$_p_pattern."|(&)*_group=".$_p_pattern."|(&)*_keyword=".$_p_pattern;
$search_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($search_string) $search_string = "&".$search_string;

$p = "^[&]|(&)*_admin=".$_p_pattern;
$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($query_string) $query_string = "&".$query_string;
//echo "<br>".eregi_replace("(&)*tttt=(([A-Za-z0-9_가-힣\x20\/\.!,])*(\-)*)*", "A", "&tttt=asddfdsa-fdsa")."<br>";
//echo "<br>".eregi_replace("^&|tttt=", "", "&tttt=fds1231&&&&tttt=fds1231")."<br>";

while($data = $_LhDb->Fetch_Object($result)) {
	$_result[$i]->number = $data->yhb_number;
	$_result[$i]->group_name = $data->yhb_group_name;
	$_result[$i]->name = $data->yhb_name;
	$_result[$i]->skin = $data->yhb_skin;
	
	$query = "select yhb_number from yh_member where yhb_group_no = '".$data->yhb_gropu_no."'";
	$_result[$i]->member_count = $_LhDb->Query_Row_Num($query);
	
	$query = "select yhb_number from yh_board where yhb_board_name = '".$data->yhb_name."'";
	$_result[$i]->board_rows = $_LhDb->Query_Row_Num($query);
	
	$_result[$i]->grant = $data->yhb_grant_list < 10 ? "L/".$data->yhb_grant_list."" : "";
	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	$_result[$i]->grant .= $data->yhb_grant_view < 10 ? $_grant_guide."V/".$data->yhb_grant_view."" : "";

	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	if($data->yhb_admin_write == 1) $_result[$i]->grant .= $_grant_guide."W/A";
	else $_result[$i]->grant .= $data->yhb_grant_write < 10 ? $_grant_guide."W/".$data->yhb_grant_write."" : "";
	
	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	if($data->yhb_admin_reply == 1) $_result[$i]->grant .= $_grant_guide."R/A";
	else $_result[$i]->grant .= $data->yhb_grant_reply < 10 ? $_grant_guide."R/".$data->yhb_grant_reply."" : "";

	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	if($data->yhb_admin_memo == 1) $_result[$i]->grant .= $_grant_guide."M/A";
	else $_result[$i]->grant .= $data->yhb_grant_memo < 10 ? $_grant_guide."M/".$data->yhb_grant_memo."" : "";

	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	if($data->yhb_admin_notice == 1) $_result[$i]->grant .= $_grant_guide."N/A";
	else $_result[$i]->grant .= $data->yhb_grant_notice < 10 ? $_grant_guide."N/".$data->yhb_grant_notice."" : "";

	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	if($data->yhb_admin_secret == 1) $_result[$i]->grant .= $_grant_guide."S/A";
	else $_result[$i]->grant .= $data->yhb_grant_secret < 10 ? $_grant_guide."S/".$data->yhb_grant_secret."" : "";

	$_grant_guide = ($_result[$i]->grant) ? ", " : "";
	if($data->yhb_admin_delete == 1) $_result[$i]->grant .= $_grant_guide."D/A";
	else $_result[$i]->grant .= $data->yhb_grant_delete < 10 ? $_grant_guide."D/".$data->yhb_grant_delete."" : "";
	
	switch($data->yhb_language) {
		case "eng":
			$_result[$i]->language = "영어";
		break;
		case "jp":
			$_result[$i]->language = "일본어";
		break;
		default:
			$_result[$i]->language = "한국어";
	}
	$_result[$i]->date = $data->yhb_reg_date;
	$i++;
}

?>
<link href="<?=_lh_yhyh_web?>/admin/css/board_manager.css" rel="stylesheet" type="text/css">
<script>
function List_Search_Action() {
	var url = "<?=$PHP_SELF?>?_admin=<?=$_REQUEST["_admin"]?><?=$search_string?>&_order=" + $("#_order").val() + "&_group=" + $("#_group").val() + "&_skin=" + $("#_skin").val() + "&_keyword=" + encodeURIComponent($("#_keyword").val().toString());
	location.href = url;
}

function Board_Delete_Action(id) {
	if(confirm(id + " 게시판을 삭제하시겠습니까?\n삭제하시면 게시판에 담겨져 있던 글과 파일들이 삭제됩니다.")) {
		$.post("<?=_lh_yhyh_web?>/admin/", { "_admin" : "board_delete_proc", "_id" : id }, function(data) {
			var p = $("> p", $("<p/>").html(data));
			switch(p.attr("class")) {
				case "error":
					alert(p.html());
				break;
				case "complete":
					alert(p.html());
					location.reload();
				break;
			}
		});
	}
}
</script>
<div class="yhyh_list">
	<form class="FormDesignNormal" onSubmit="List_Search_Action(); return false;">
		<fieldset>
			<legend>게시판 리스트</legend>
	<div class="yhyh_list_header">
		<p class="numberFont_wrap">Groups : <?=number_format($_total_rows)?></p>
		<div class="list_header_right">
			<select name="_order" id="_order" onChange="List_Search_Action();">
				<option value="yhb_name-asc"<?=$_REQUEST["_order"] == "yhb_name-asc" ? " selected" : ""?>>코드 오름차순</option>
				<option value="yhb_name-desc"<?=$_REQUEST["_order"] == "yhb_name-desc" ? " selected" : ""?>>코드 내림차순</option>
				<option value="yhb_reg_date-asc"<?=$_REQUEST["_order"] == "yhb_reg_date-asc" ? " selected" : ""?>>등록일 오름차순</option>
				<option value="yhb_reg_date-desc"<?=$_REQUEST["_order"] == "yhb_reg_date-desc" ? " selected" : ""?>>등록일 내림차순</option>
			</select>
			<select name="_group" id="_group" onChange="List_Search_Action();">
				<option value="">전체그룹</option>
				<?
				$group_query = "SELECT yhb_number, yhb_name FROM yh_group ORDER BY yhb_name asc";
				$group_result = $_LhDb->Query($group_query);
				while($group = $_LhDb->Fetch_Object($group_result)) {
				?>
				<option value="<?=$group->yhb_number?>"<?=$_REQUEST["_group"] == $group->yhb_number ? " selected" : ""?>><?=$group->yhb_name?></option>
				<?
				}
				?>
			</select>
			<select name="_skin" id="_skin" onChange="List_Search_Action();">
				<option value="">전체스킨</option>
				<?
				$skin_query = "select yhb_skin from yh_config_board group by yhb_skin";
				$skin_result = $_LhDb->Query($skin_query);
				while($skin = $_LhDb->Fetch_Object($skin_result)) {
					if(!$skin->yhb_skin) $skin->yhb_skin = "no_skin";
				?>
				<option value="<?=$skin->yhb_skin?>"<?=$_REQUEST["_skin"] == $skin->yhb_skin ? " selected" : ""?>><?=$skin->yhb_skin == "no_skin" ? "스킨없음" : $skin->yhb_skin?></option>
				<?
				}
				?>
			</select>
			<input type="text" name="_keyword" id="_keyword" value="<?=$_REQUEST["_keyword"]?>" title="검색어 입력창">
			<label for="_keyword" class="placeHolder">검색어 입력</label>
			<input type="button" onClick="List_Search_Action();" value="검색">
			<? if($_REQUEST["_group"] || $_REQUEST["_skin"] || $_REQUEST["_keyword"]) { ?>
			<a href="<?=$PHP_SELF?>?_admin=<?=$_REQUEST["_admin"]?><?=$search_string?>" class="a_button">초기화</a>
			<? } ?>
		</div>
	</div>
	<table>
		<colgroup>
			<col width="40px"/>
			<col width="120px"/>
			<col width="100px"/>
			<col width="120px"/>
			<col width="40px"/>
			<col width="auto"/>
			<col width="60px"/>
			<col width="70px"/>
			<col width="40px"/>
		</colgroup>
		<thead>
			<tr>
				<!--th class="numberFont_wrap">Board No</th-->
				<th><input type="checkbox" id="List_Check_Main"></th>
				<!--th>그룹코드</th-->
				<th>코드</th>
				<th>보기/수정</th>
				<th>스킨</th>
				<th>게시물</th>
				<th>권한</th>
				<th>언어</th>
				<th>등록일</th>
				<th class="last">삭제</th>
			</tr>
		</thead>
		<tbody>
		<?
		$count = sizeof($_result);
		for($i = 0; $i < $count; $i++) {
			$_data = $_result[$i];
		?>
			<tr>
				<!--td class="numberFont_wrap"><?=$_data->number?></td-->
				<td><input type="checkbox" name="" value="<?=$_data->number?>"></td>
				<!--td class="numberFont_wrap"><?=$_data->group_name?></td-->
				<td class="numberFont_wrap"><a href="<?=$PHP_SELF?>?_admin=board_register<?=$query_string?>&_id=<?=$_data->name?>" title="게시판 수정화면으로"><?=$_data->name?></a></td>
				<td class=""><a class="a_button" href="<?=_lh_yhyh_web?>/?_id=<?=$_data->name?>" target="_blank" title="게시판으로 이동">보기</a><a class="a_button" href="<?=$PHP_SELF?>?_admin=board_register<?=$query_string?>&_id=<?=$_data->name?>" title="게시판 수정화면으로">수정</a></td>
				<td class="numberFont_wrap"><?=$_data->skin?></td>
				<td class="numberFont_wrap"><?=number_format($_data->board_rows)?></td>
				<td class="numberFont_wrap"><?=$_data->grant?></td>
				<td class=""><?=$_data->language ? $_data->language : "한국어"?></td>
				<td class="numberFont_wrap"><?=$_data->date ? date("Y.m.d", $_data->date) : "-"?></td>
				<td class="last"><a class="a_button" href="javascript:Board_Delete_Action('<?=$_data->name?>');" title="게시판 삭제진행">삭제</a></td>
			</tr>
		<? }
		if($count == 0) { ?>
			<tr>
				<td colspan="15">
					<p class="no_list_rows">현재 등록된 게시판이 없습니다.<br>
					신규게시판등록 버튼을 클릭 후 게시판을 생성하세요.</p>
				</td>
			</tr>
		<? } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="15" class="footer">
					<div class="yhyh_list_paging">
						<?=$_LhDb->Paging($link, 5, "_self");?>
					</div>
					<span class="yhyh_button_footer_left"><a href="#" class="a_button">선택항목삭제</a></span>
					<span class="yhyh_button_footer_right"><a href="<?=$PHP_SELF?>?_admin=board_register<?=$query_string?>" class="a_button">신규게시판등록</a></span>
				</td>
			</tr>
		</tfoot>
	</table>
		</fieldset>
	</form>
</div>