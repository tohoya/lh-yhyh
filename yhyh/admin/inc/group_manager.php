<?
if(!$_REQUEST["_order"]) $_REQUEST["_order"] = "yhb_name-asc";

$s_field = "
yhb_number
, yhb_name
, yhb_skin
, yhb_open_group
, yhb_language
, yhb_reg_date
";
$query = "SELECT ".$s_field." from yh_group";
$query .= " ORDER BY ".eregi_replace("-", " ", $_REQUEST["_order"]);

$result = $_LhDb->Page_Index($query, $order, $_REQUEST["_page"], 20);
$i = 0;
$_total_rows = $_LhDb->Total_Row_Count();
$start_no = $_total_rows - ($_REQUEST["_page"] - 1) * $_config->yhb_rows;

$p = "^[&]|(&)*_admin=".$_p_pattern."|(&)*_order=".$_p_pattern;
$search_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($search_string) $search_string = "&".$search_string;

$p = "^[&]|(&)*_admin=".$_p_pattern;
$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($query_string) $query_string = "&".$query_string;

while($data = $_LhDb->Fetch_Object($result)) {
	$_result[$i]->number = $data->yhb_number;
	$_result[$i]->name = $data->yhb_name;
	$_result[$i]->skin = $data->yhb_group_skin ? $data->yhb_group_skin : "default";
	
	$query = "select yhb_number from yh_member where yhb_group_no = '".$data->yhb_number."'";
	$_result[$i]->member_count = $_LhDb->Query_Row_Num($query);
	
	$query = "select yhb_number from yh_config_board where yhb_group_no = '".$data->yhb_number."'";
	$_result[$i]->board_count = $_LhDb->Query_Row_Num($query);
	
	$query = "select yhb_number from yh_board where yhb_group_no = '".$data->yhb_number."'";
	$_result[$i]->board_rows = $_LhDb->Query_Row_Num($query);
	$_result[$i]->open = $data->yhb_open_group == 1 ? "Y" : "N";
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
<link href="<?=_lh_yhyh_web?>/admin/css/group_manager.css" rel="stylesheet" type="text/css">
<script>
function List_Search_Action() {
	var url = "<?=$PHP_SELF?>?_admin=<?=$_REQUEST["_admin"]?><?=$search_string?>&_order=" + $("#_order").val();
	location.href = url;
}

function Group_Delete_Action(group, group_name) {
	if(confirm(group_name + " 그룹을 삭제하시겠습니까?\n삭제하시면 그룹내 모든 게시판 회원정보가 삭제됩니다.")) {
		$.post("<?=_lh_yhyh_web?>/admin/", { "_admin" : "group_delete_proc", "_group" : group }, function(data) {
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
			<legend>그룹 리스트</legend>
	<div class="yhyh_list_header">
		<p class="numberFont">Groups : <?=number_format($_total_rows)?></p>
		<div class="list_header_right">
			<select name="_order" id="_order" onChange="List_Search_Action();">
				<option value="yhb_name-asc"<?=$_REQUEST["_order"] == "yhb_name-asc" ? " selected" : ""?>>이름 오름차순</option>
				<option value="yhb_name-desc"<?=$_REQUEST["_order"] == "yhb_name-desc" ? " selected" : ""?>>이름 내림차순</option>
				<option value="yhb_number-asc"<?=$_REQUEST["_order"] == "yhb_number-asc" ? " selected" : ""?>>그룹번호 오름차순</option>
				<option value="yhb_number-desc"<?=$_REQUEST["_order"] == "yhb_number-desc" ? " selected" : ""?>>그룹번호 내림차순</option>
				<option value="yhb_reg_date-asc"<?=$_REQUEST["_order"] == "yhb_reg_date-asc" ? " selected" : ""?>>등록일 오름차순</option>
				<option value="yhb_reg_date-desc"<?=$_REQUEST["_order"] == "yhb_reg_date-desc" ? " selected" : ""?>>등록일 내림차순</option>
			</select>
		</div>
	</div>
	<table>
		<colgroup>
			<col width="35px"/>
			<col width="40px"/>
			<col width="auto"/>
			<col width="120px"/>
			<col width="76px"/>
			<col width="47px"/>
		</colgroup>
		<thead>
			<tr>
				<th class="numberFont">No</th>
				<th><input type="checkbox" id="List_Check_Main"></th>
				<th>코드</th>
				<th>스킨</th>
				<th>회원 수</th>
				<th>게시판 수</th>
				<th>게시물 수</th>
				<th>그룹 공개</th>
				<th>언어</th>
				<th>등록일</th>
				<th class="last">수정/삭제</th>
			</tr>
		</thead>
		<tbody>
		<?
		$count = sizeof($_result);
		for($i = 0; $i < $count; $i++) {
			$_data = $_result[$i];
		?>
			<tr>
				<td class="numberFont"><?=$_data->number?></td>
				<td><input type="checkbox" name="" value="<?=$_data->number?>"></td>
				<td class=""><a href="<?=$PHP_SELF?>?_admin=group_register<?=$query_string?>&_group=<?=$_data->number?>" title="그룹내용 수정화면으로"><?=$_data->name?></a></td>
				<td class="numberFont_wrap"><?=$_data->skin?></td>
				<td class="numberFont"><a href="<?=$PHP_SELF?>?_admin=member_manager&_group=<?=$_data->number?>" title="그룹내 회원리스트로 이동"><?=number_format($_data->member_count)?></a></td>
				<td class="numberFont"><a href="<?=$PHP_SELF?>?_admin=board_manager&_group=<?=$_data->number?>" title="그룹내 게시판리스트로 이동"><?=number_format($_data->board_count)?></a></td>
				<td class="numberFont"><?=number_format($_data->board_rows)?></td>
				<td class="numberFont_wrap"><?=$_data->open?></td>
				<td class=""><?=$_data->language?></td>
				<td class="numberFont"><?=$_data->date ? date("Y.m.d", $_data->date) : "-"?></td>
				<td class="last">
					<a class="a_button" href="<?=$PHP_SELF?>?_admin=group_register<?=$query_string?>&_group=<?=$_data->number?>" title="그룹내용 수정화면으로">수정</a>
					<a class="a_button" href="javascript:Group_Delete_Action('<?=$_data->number?>', '<?=$_data->name?>');" title="그룹 삭제진행">삭제</a>
				</td>
			</tr>
		<? }
		if($count == 0) { ?>
			<tr>
				<td colspan="15">
					<p class="no_list_rows">현재 등록된 그룹이 없습니다.<br>
					신규그룹등록 버튼을 클릭 후 그룹을 생성하세요.</p>
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
					<span class="yhyh_button_footer_right"><a href="<?=$PHP_SELF?>?_admin=group_register<?=$query_string?>" class="a_button">신규그룹등록</a></span>
				</td>
			</tr>
		</tfoot>
	</table>
		</fieldset>
	</form>
</div>