<?
if(!$_REQUEST["_order"]) $_REQUEST["_order"] = "yhb_name-asc";

$s_field = "
yg.yhb_name AS yhb_group_name
, ym.yhb_number
, yhb_id
, ym.yhb_name
, yhb_admin
, yhb_group_no
, yhb_joindate
, ym.yhb_level
";
$query = "SELECT ".$s_field." FROM yh_member AS ym, yh_group AS yg WHERE (yhb_group_no = yg.yhb_number OR yhb_group_no = '')";
$query .= " AND ym.yhb_number != '".$_member->yhb_number."'";
if($_REQUEST["_group"]) {
	$query .= " AND ym.yhb_group_no = '".$_REQUEST["_group"]."'";
}
if($_REQUEST["_grant"]) {
	switch($_REQUEST["_grant"]) {
		case "s":
			$query .= " AND ym.yhb_admin = '2'";
		break;
		case "g":
			$query .= " AND ym.yhb_admin = '2'";
		break;
		case "b":
			$query .= " AND ym.yhb_admin = '' AND ym.yhb_board_name != ''";
		break;
		case "n":
			$query .= " AND ym.yhb_admin = '' AND ym.yhb_board_name = ''";
		break;
	}
}
if($_REQUEST["_level"]) {
	$query .= " AND ym.yhb_level = '".$_REQUEST["_level"]."'";
}
if($_REQUEST["_keyword"]) {
	$query .= " AND (ym.yhb_name like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_name like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_id like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_nickname like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_kook_no like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_homepage like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_email like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_board_name like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_home_post like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_home_address like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_home_address_etc like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_school_post like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_school_address like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_school_address_etc like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_post like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_address like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_address_etc like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_home_tel like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_handphone like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_fax like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_school_name like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_school_tel like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_name like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_level like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_position like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_tel like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_no like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_owner like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_charge like '%".$_REQUEST["_keyword"]."%'
	 OR ym.yhb_office_charge_tel like '%".$_REQUEST["_keyword"]."%'
	 )";
}
$query .= " GROUP BY ym.yhb_number ORDER BY ym.".eregi_replace("-", " ", $_REQUEST["_order"])."";

//echo $query;
$result = $_LhDb->Page_Index($query, $order, $_REQUEST["_page"], 14);
$i = 0;
$_total_rows = $_LhDb->Total_Row_Count();
$start_no = $_total_rows - ($_REQUEST["_page"] - 1) * $_config->yhb_rows;

$p = "^[&]|(&)*_admin=".$_p_pattern."|(&)*_order=".$_p_pattern."|(&)*_grant=".$_p_pattern."|(&)*_level=".$_p_pattern."|(&)*_group=".$_p_pattern."|(&)*_keyword=".$_p_pattern;
$search_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($search_string) $search_string = "&".$search_string;

$p = "^[&]|(&)*_admin=".$_p_pattern;
$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($query_string) $query_string = "&".$query_string;

while($data = $_LhDb->Fetch_Object($result)) {
	$_result[$i]->number = $data->yhb_number;
	$_result[$i]->name = $data->yhb_name;
	$_result[$i]->group_name = $data->yhb_group_name;
	switch($_LhDb->Get_Admin($data)) {
		case "s":
			$_result[$i]->grant = "최고관리자";
			$_result[$i]->grant_class = "grant_s";
		break;
		case "g":
			$_result[$i]->grant = "그룹관리자";
			$_result[$i]->grant_class = "grant_g";
		break;
		case "b":
			$_result[$i]->grant = "보드관리자";
			$_result[$i]->grant_class = "grant_b";
		break;
		default:
			$_result[$i]->grant = "일반회원";
	}
	$_result[$i]->level = $data->yhb_level;
	$_result[$i]->open = $data->yhb_open_group == 1 ? "Y" : "N";
	$_result[$i]->id = $data->yhb_id;
	$_result[$i]->date = $data->yhb_joindate;
	
	$query = "select yhb_number from yh_board where yhb_id = '".$data->yhb_id."' AND yhb_pass = '".$data->yhb_pass."'";
	$_result[$i]->board_rows = $_LhDb->Query_Row_Num($query);
	$i++;
}
?>
<link href="<?=_lh_yhyh_web?>/admin/css/member_manager.css" rel="stylesheet" type="text/css">
<script>
function List_Search_Action() {
	var url = "<?=$PHP_SELF?>?_admin=<?=$_REQUEST["_admin"]?><?=$search_string?>&_order=" + $("#_order").val() + "&_group=" + $("#_group").val() + "&_grant=" + $("#_grant").val() + "&_level=" + $("#_level").val() + "&_keyword=" + encodeURIComponent($("#_keyword").val().toString());
	location.href = url;
}

function Member_Delete_Action(no, name) {
	if(confirm(name + " 회원을 삭제하시겠습니까?\n삭제하시면 회원님의 정보가 삭제됩니다.")) {
		$.post("<?=_lh_yhyh_web?>/admin/", { "_admin" : "member_delete_proc", "_m_no" : no }, function(data) {
			//alert(data);
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
			<legend>회원 리스트</legend>
	<div class="yhyh_list_header">
		<p class="numberFont">Members : <?=number_format($_total_rows)?></p>
		<div class="list_header_right">
			<select name="_order" id="_order" onChange="List_Search_Action();">
				<option value="yhb_name-asc"<?=$_REQUEST["_order"] == "yhb_name-asc" ? " selected" : ""?>>이름 오름차순</option>
				<option value="yhb_name-desc"<?=$_REQUEST["_order"] == "yhb_name-desc" ? " selected" : ""?>>이름 내림차순</option>
				<option value="yhb_id-asc"<?=$_REQUEST["_order"] == "yhb_id-asc" ? " selected" : ""?>>아이디 오름차순</option>
				<option value="yhb_id-desc"<?=$_REQUEST["_order"] == "yhb_id-desc" ? " selected" : ""?>>아이디 내림차순</option>
				<option value="yhb_joindate-asc"<?=$_REQUEST["_order"] == "yhb_joindate-asc" ? " selected" : ""?>>등록일 오름차순</option>
				<option value="yhb_joindate-desc"<?=$_REQUEST["_order"] == "yhb_joindate-desc" ? " selected" : ""?>>등록일 내림차순</option>
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
			<select name="_grant" id="_grant" onChange="List_Search_Action();">
				<option value="">전체권한</option>
				<option value="n"<?=$_REQUEST["_grant"] == "n" ? " selected" : ""?>>일반회원</option>
				<option value="b"<?=$_REQUEST["_grant"] == "b" ? " selected" : ""?>>보드관리자</option>
				<option value="g"<?=$_REQUEST["_grant"] == "g" ? " selected" : ""?>>그룹관리자</option>
				<option value="s"<?=$_REQUEST["_grant"] == "s" ? " selected" : ""?>>최고관리자</option>
			</select>
			<select name="_level" id="_level" onChange="List_Search_Action();">
				<option value="">전체레벨</option>
				<?
				for($i = 1; $i <= 10; $i++) {
				?>
				<option value="<?=$i?>"<?=$_REQUEST["_level"] == $i ? " selected" : ""?>>Lv.<?=$i?></option>
				<?
				}
				?>
			</select>
			<input type="text" name="_keyword" id="_keyword" value="<?=$_REQUEST["_keyword"]?>" title="검색어 입력창">
			<label for="_keyword" class="placeHolder">검색어 입력</label>
			<input type="button" onClick="List_Search_Action();" value="검색">
			<? if($_REQUEST["_group"] || $_REQUEST["_grant"] || $_REQUEST["_level"] || $_REQUEST["_keyword"]) { ?>
			<a href="<?=$PHP_SELF?>?_admin=<?=$_REQUEST["_admin"]?><?=$search_string?>" class="a_button">초기화</a>
			<? } ?>
		</div>
	</div>
	<table>
		<colgroup>
			<col width="35px"/>
			<col width="40px"/>
			<col width="80px"/>
			<col width="80px"/>
			<col width="80px"/>
			<col width="47px"/>
			<col width="80px"/>
			<col width="47px"/>
			<col width="47px"/>
			<col width="100px"/>
		</colgroup>
		<thead>
			<tr>
				<th class="numberFont">No</th>
				<th><input type="checkbox" id="List_Check_Main"></th>
				<th>아이디</th>
				<th>이름</th>
				<th>권한</th>
				<th>등급</th>
				<th>그룹명</th>
				<th>게시물</th>
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
				<td class="numberFont"><a href="<?=$PHP_SELF?>?_admin=member_register<?=$query_string?>&_m_no=<?=$_data->number?>"><?=$_data->id?></a></td>
				<td><?=$_data->name?></td>
				<td class="<?=$_result[$i]->grant_class?>"><?=$_data->grant?></td>
				<td class="numberFont">Lv.<?=$_data->level?></td>
				<td class=""><?=$_data->group_name?></td>
				<td class="numberFont"><?=number_format($_data->board_rows)?></td>
				<td class="numberFont"><?=$_data->date ? date("Y.m.d", $_data->date) : "-"?></td>
				<td class="last">
					<a class="a_button" href="<?=$PHP_SELF?>?_admin=member_register<?=$query_string?>&_m_no=<?=$_data->number?>" title="회원정보 수정화면으로">수정</a>
					<a class="a_button" href="javascript:Member_Delete_Action('<?=$_data->number?>', '<?=$_data->name?>');" title="회원정보 삭제진행">삭제</a>
				</td>
			</tr>
		<? }
		if($count == 0) { ?>
			<tr>
				<td colspan="15">
					<p class="no_list_rows">현재 등록된 회원이 없습니다.<br>
					신규회원등록 버튼을 클릭 후 회원을 생성하세요.</p>
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
					<span class="yhyh_button_footer_right"><a href="<?=$PHP_SELF?>?_admin=member_register<?=$query_string?>" class="a_button">신규회원등록</a></span>
				</td>
			</tr>
		</tfoot>
	</table>
		</fieldset>
	</form>
</div>