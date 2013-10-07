<?
$_tmp_number = session_id();
$_c_result = $_LhDb->Get_Board($_REQUEST["_id"]);
if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_c_result->yhb_group_no;

if(!$_c_result->yhb_number) {
	$c_query = "delete from yh_category where yhb_board_name = '".$_tmp_number."'";
	$_LhDb->Query($c_query);
}

// 그룹 정보
$_g_result = $_LhDb->Get_Group($_REQUEST["_group"]);

$p = "^[&]|(&)*_admin=".$_p_pattern."|(&)*_id=".$_p_pattern;
$query_string = eregi_replace("^&|&$", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($query_string) $query_string = "&".$query_string;

?>
<link href="<?=_lh_yhyh_web?>/admin/css/board_register.css" rel="stylesheet" type="text/css">
<script>
var colorPanel = ["FF0000", "ff5e00", "ffbb00", "FFE400", "abf200", "1fda11", "00d8ff", "0055ff", "0900ff", "6600ff", "ff00dd", "ff007f", "000000", "FFFFFF"
, "ffd8d8", "fae0d4", "faecc5", "faf4c0", "e4f7ba", "cefbc9", "d4f4fa", "d9e5ff", "dad9ff", "e8d9ff", "ffd9fa", "ffd9ec", "f6f6f6", "eaeaea"
, "ffa7a7", "ffc19e", "ffe08c", "faed7d", "cef279", "b7f0b1", "b2ebf4", "b2ccff", "b5b2ff", "d1b2ff", "ffb2f5", "ffb2d9", "d5d5d5", "bdbdbd"
, "f15f5f", "f29661", "f2cb61", "e5d85c", "bce55c", "86e57f", "5cd1e5", "6699ff", "6b66ff", "a366ff", "f261df", "f261aa", "a6a6a6", "8c8c8c"
, "cc3d3d", "cc723d", "cca63d", "c4b73b", "9fc93c", "47c83e", "3db7cc", "4174d9", "4641d9", "7e41d9", "d941c5", "d9418d", "747474", "5d5d5d"
, "980000", "993800", "997000", "998a00", "6b9900", "2f9d27", "008299", "003399", "050099", "3d0099", "990085", "99004c", "4c4c4c", "353535"
, "670000", "662500", "664b00", "665c00", "476600", "22741c", "005766", "002266", "030066", "290066", "660058", "660033", "212121", "000000"];

$(window).load(function() {
	var _category_color_sel = $("#yhb_category_color");
	_category_color_sel.empty().change(function() {
		$(this).css("background", $(this).val());
	});
	
	for(var i = 0; i < colorPanel.length; i++) {
		var color = "#" + colorPanel[i];
		$("<option/>").css("background", color).html("&nbsp;&nbsp;").val(color).appendTo(_category_color_sel);
	}
	
	$(".category_color").each(function(i) { 
		var color = $(this).attr("data-color");
		$(this).css("color", color);
	});
	
	DesignFormInit(".FormDesignNormal");
	$(".yhyh_category_link_view").data("dw", $(".yhyh_category_link_view").width()).fadeTo(0, 0).css({
		"width" : "0px"
	});
	$("#yhb_category_link_use").change(function() {
		Category_Link_Change(this);
	});
	$("#yhb_name").change(function() {
		$("#_id_check").val("");
	});
});

function Board_Id_Check() {
	if($("#yhb_name").FormInputCheck({ msg : "게시판 코드는 2자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 2, max_msg : "영문/숫자 주합으로 2자 이상 적어주시기 바랍니다." }})) return;
	$.post("<?=_lh_yhyh_web?>/admin/", { "_admin" : "board_id_check_proc", "yhb_name" : $("#yhb_name").val() }, function(data) {
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
				$("#_id_check").val("");
			break;
			case "complete":
				switch (p.attr("title")) {
					case "use_name":
						alert(p.html());
						$("#_id_check").val(1);
					break;
					case "no_use_name":
						alert(p.html());
						$("#_id_check").val("");
					break;
				}
			break;
		}
	});
}

function FormSubmitStart(f) {
	if($("#yhb_name").FormInputCheck({ msg : "게시판 코드는 2자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 2, max_msg : "영문/숫자 주합으로 2자 이상 적어주시기 바랍니다." }, no_eng_num : "게시판 코드는 영문 숫자 조합만 가능합니다." })) return false;
	if($("#_id_check").FormInputCheck({ msg : "게시판 코드 중복체크 확인을 해주세요."})) return false;
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/admin/", function(data) {
		//alert(data);
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
			break;
			case "complete":
				alert(p.html());
				switch(p.attr("title")) {
					case "modify":
						location.reload();
					break;
					case "register":
						location.href = "<?=$PHP_SELF?>?_admin=board_manager<?=$query_string?>";
					break;
				}
			break;
		}
		//$("#testView").css("display", "block").val(data);
	});
	
}

function Category_Link_Change(checkbox) {
	var _div = $(".yhyh_category_link_view");
	if(checkbox.checked) {
		_div.stop().animate({
			opacity : 1
			, width : Number(_div.data("dw"))
		}, 500, "easeOutCubic");
	} else {
		_div.stop().animate({
			opacity : 0
			, width : 0
		}, 500, "easeOutCubic");
	}
}

function Category_Modify_Action(no, name) {
	var _li = $("#category_" + no);
	if(no) {
		$("#yhb_category_link_use").get(0).checked = $("> .category_url", _li).html() ? true : false;
		$("#yhb_category_link").val($("> .category_url", _li).html()).change();
		$("#yhb_category_color").val($("> .category_color", _li).attr("data-color")).change();
	} else {
		$("#yhb_category_link_use").get(0).checked = false;
		$("#yhb_category_link").val("").change();
	}
	$("#yhb_category_link_use").change();
	$("#category_save_button").val(no ? "수정" : "신규추가");
	$("#yhb_category_name").val(name).change();
	$("#yhb_category_number").val(no);
	$("#category_modify_cancel").stop().fadeTo(300, no ? 1 : 0, function() { if(no == "") { $(this).css("display", "none"); } });
}

function Category_Save_Action(no) {
	if(!no) {
		if($("#yhb_category_name").FormInputCheck({ msg : "카테고리명을 입력해주세요."})) return;
	} else {
		if(!confirm("선택하신 카테고리를 삭제하시겠습니까?")) return;
	}
	
	var url = "<?=_lh_yhyh_web?>/admin/";
	var params = {
		"_admin" : "category_proc"
		, "yhb_number" : no ? no : $("#yhb_category_number").val()
		, "_category_save_type" : no ? "delete" : ""
		, "yhb_group_no" : "<?=$_c_result->yhb_group_no?>"
		, "yhb_board_no" : "<?=$_c_result->yhb_number ? $_c_result->yhb_number : "0"?>"
		, "yhb_board_name" : "<?=$_c_result->yhb_name ? $_c_result->yhb_name : $_tmp_number?>"
		, "yhb_name" : $("#yhb_category_name").val()
		, "yhb_color" : $("#yhb_category_color").val()
		, "yhb_url" : $("#yhb_category_link_use").val() == 1 ? $("#yhb_category_link").val() : ""
	};
	$.post(url, params, function(data) {
		//alert(data);
		var _ul = $(".yhyh_category_list"), _li;
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
			break;
			case "complete":
				if(no) {
					$("#category_" + no).remove();
				} else {
					var category_name = $("#yhb_category_name").val();
					var category_link = $("#yhb_category_link").val();
					var category_color = $("#yhb_category_color").val();
					var category_link_checked = $("#yhb_category_link_use").get(0).checked;
					if($("#yhb_category_number").val()) {
						_li = $("#category_" + $("#yhb_category_number").val());
						_li.empty();
					} else {
						$("#yhb_category_name").val("");
						$("#yhb_category_link").val("")
						$("#yhb_category_link_use").get(0).checked = false;
						$("#yhb_category_link_use").change();
						_li = $("<li/>").attr("id", "category_" + p.attr("title")).prependTo(_ul);
					}
					$("<a/>")
					<? if($_c_result->yhb_name) { ?>
					.attr({
						"href" : "<?=_lh_yhyh_web?>/?_id=<?=$_c_result->yhb_name?>&_ct=" + p.attr("id")
						, "target" : "_blank"
					})
					<? } ?>
					.addClass("category_number").html(p.attr("id")).appendTo(_li);
					$("<span/>").addClass("category_color").html("■").attr("data-color", category_color).css("color", category_color).appendTo(_li);
					$("<span/>").addClass("category_name").html(category_name).appendTo(_li);
					if(category_link_checked && category_link) {
						$("<span/>").addClass("category_url").html(category_link).appendTo(_li);
					}
					$("<small/>").addClass("category_rows").html("(0)").appendTo(_li);
					$("<a/>").attr("href", "").addClass("a_button").click(function() { Category_Save_Action(p.attr("title")); return false; }).html("삭제").appendTo(_li);
					$("<a/>").attr("href", "").addClass("a_button").click(function() { Category_Modify_Action(p.attr("title"), category_name); return false; }).html("수정").appendTo(_li);
				}
				alert(p.html());
			break;
		}
	});
}

function Board_Admin_Grant(This, target_name) {
	if($("select[name=" + target_name + "]").length > 0) {
		$("select[name=" + target_name + "]").get(0).disabled = $(This).get(0).checked;
	}
}

function Board_Change_Regist(This) {
	location.href = "<?=$PHP_SELF?>?_admin=board_register<?=$query_string?>&_id=" + $(This).val()
}

</script>
<div class="yhyh_board_register">
	<form class="FormDesignNormal" name="yhyh_board_regist" id="yhyh_board_regist" method="POST" onSubmit="return FormSubmitStart(this);">
		<fieldset>
			<legend>게시판 등록/수정</legend>
			<input type="hidden" name="_admin" value="board_register_proc">
			<input type="hidden" name="_id" value="<?=$_c_result->yhb_name?>">
			<input type="hidden" name="_board_no" value="<?=$_c_result->yhb_number?>">
			<input type="hidden" name="_tmp_number" value="<?=$_c_result->yhb_number == "" ? $_tmp_number : ""?>">
			<? if(!$_c_result->yhb_name) { ?>
			<input type="hidden" name="_id_check" id="_id_check" value="" alt="필수">
			<? } ?>
			<table class="yhyh_table_member">
				<tbody>
					<tr><th colspan="3" class="yh_regist_title_top"><h2>게시판 기본 정보</h2></th><td class="yh_middle_submit"><input type="submit" value="게시판정보저장"><a href="<?=$PHP_SELF?>?_admin=board_manager<?=$query_string?>" class="a_button">뒤로가기</a></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">코드(아이디)</span></th>
						<td colspan="3">
							<? if($_c_result->yhb_name) { ?>
							<select name="yhb_name_change" onChange="Board_Change_Regist(this);">
							<?
							$query_board = "select yhb_name from yh_config_board order by yhb_name asc";
							$result_board = $_LhDb->Query($query_board);
							while($bc = $_LhDb->Fetch_Object($result_board)) {
							?>
							<option value="<?=$bc->yhb_name?>"<?=$bc->yhb_name == $_c_result->yhb_name ? " selected" : ""?>><?=$bc->yhb_name?></option>
							<? } ?>
							</select>
							<? } else { ?>
							<input type="text" name="yhb_name" id="yhb_name" value="<?=$_c_result->yhb_name?>" size="20" title="게시판 코드" alt="필수">
							<label for="yhb_name" class="placeHolder">영문숫자 입력</label>
							<input type="button" value="게시판중복체크" onClick="Board_Id_Check();">
							<? } ?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">타이들(head)</span></th>
						<td colspan="3">
							<input type="text" name="yhb_title_name" id="yhb_title_name" value="<?=$_c_result->yhb_title_name?>" size="80" title="게시판의 고유 타이틀을 설정합니다.">
							<label for="yhb_title_name" class="placeHolder">ex) 상담 게시판</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">그룹/스킨/언어 설정</span></th>
						<td colspan="3">
							<span class="field_title">그룹</span>
							<select name="yhb_group_no" id="yhb_group_no">
							<?
							if(!$_c_result->yhb_group_no) $_c_result->yhb_group_no = $_REQUEST["_group"];
							$query_group = "select yhb_number, yhb_name from yh_group order by yhb_number asc";
							$result_group = $_LhDb->Query($query_group);
							while($gc = $_LhDb->Fetch_Object($result_group)) {
							?>
							<option value="<?=$gc->yhb_number?>"<?=$gc->yhb_number == $_c_result->yhb_group_no ? " selected" : ""?>><?=$gc->yhb_name?></option>
							<? } ?>
							</select>
							<span class="field_title">스킨</span>
							<select name="yhb_skin">
<?
if(trim(!$_c_result->yhb_skin)) $_c_result->yhb_skin = $_REQUEST["_skin"] ? $_REQUEST["_skin"] : "defaultBoard";
$skin_dir = opendir(_lh_document_root._lh_yhyh_web."/skin");
while($dir = readdir($skin_dir)) {
	if(!eregi("\.",$dir)) {
?>
								<option value="<?=$dir?>"<?=$dir == $_c_result->yhb_skin ? " selected" : ""?>><?=$dir?></option>
<? } } ?>
							</select>
							<span class="field_title">언어</span>
							<select name="yhb_language">
								<option value="">한국어(Korea)</option>
								<option value="eng"<?=$_c_result->yhb_language == "eng" ? " selected" : ""?>>영어(English)</option>
								<option value="jp"<?=$_c_result->yhb_language == "jp" ? " selected" : ""?>>일본어(Japan)</option>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">글출력 관련설정</span></th>
						<td colspan="3">
							<span class="field_title">게시물 수</span>
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_rows" id="yhb_rows" value="<?=($_c_result->yhb_rows) ? $_c_result->yhb_rows : 16?>" size="6" title="게시물 줄수">
							<label for="yhb_rows" class="placeHolder">숫자입력</label>
							<span class="field_title">페이지 출력 수</span>
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_page" id="yhb_page" value="<?=($_c_result->yhb_page) ? $_c_result->yhb_page : 7?>" size="6" title="페이지 출력 수">
							<label for="yhb_page" class="placeHolder">숫자입력</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">신규글 표시시간</span></th>
						<td colspan="3">
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_time" id="yhb_time" value="<?=($_c_result->yhb_time) ? $_c_result->yhb_time : 24?>" size="6" title="신규 표시 제한 시간">
							<label for="yhb_time" class="placeHolder">숫자입력</label><span class="field_title">시간 동안 신규 글로 표시</span>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">리스트 출력</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_list" id="yhb_use_list" value="1"<?=$_c_result->yhb_name ? $_c_result->yhb_use_list == 1 ? " checked" : "" : " checked"?>>
							<label for="yhb_use_list">글 상세보기 하단에 리스트를 출력합니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">첨부파일 확장자</span></th>
						<td colspan="3">
							<input type="text" name="yhb_ext" id="yhb_ext" value="<?=$_c_result->yhb_ext ? $_c_result->yhb_ext : "jpeg, jpg, png, bmp, gif, pic"?>" size="80" title="첨부파일 확장자 제한">
							<label for="yhb_ext" class="placeHolder">ex) jpg, png, bmp</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">업로드 가능 용량</span></th>
						<td colspan="3">
							<input type="text" name="yhb_upload_size" id="yhb_upload_size" value="<?=$_c_result->yhb_upload_size ? $_c_result->yhb_upload_size : 100000000?>" size="10" title="첨부파일 업로드 용량 제한">
							<label for="yhb_upload_size" class="placeHolder">ex) 1024000</label><span>byte</span>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">시작파일명</span></th>
						<td colspan="3">
							<input type="text" name="yhb_start_url" id="yhb_start_url" value="<?=$_c_result->yhb_start_url?>" size="60" title="파일형태로 게시판 로딩시 파일명">
							<label for="yhb_start_url" class="placeHolder">ex) /board/news.php</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">체크박스 사용</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_checkbox" id="yhb_use_checkbox" value="1"<?=$_c_result->yhb_name ? $_c_result->yhb_use_checkbox == 1 ? " checked" : "" : " checked"?>>
							<label for="yhb_use_checkbox">리스트 제목앞에 체크 박스를 달아 사용하는 기능입니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">검색기능 사용</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_search" id="yhb_use_search" value="1"<?=$_c_result->yhb_name ? $_c_result->yhb_use_search == 1 ? " checked" : "" : " checked"?>>
							<label for="yhb_use_search">검색기능을 활성화 합니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
				<tbody>
					<tr><th colspan="3" class="yh_regist_title_top"><h2>권한정보</h2></th><td class="yh_middle_submit"><input type="submit" value="게시판정보저장"><a href="<?=$PHP_SELF?>?_admin=board_manager<?=$query_string?>" class="a_button">뒤로가기</a></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">게시판 접근 권한</span></th>
						<td colspan="3">
							<table class="yh_address_form">
								<tbody>
									<tr>
										<td>
											<span class="field_title">리스트 접근 :</span>
										</td>
										<td>
											<select name="yhb_grant_list">
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_list == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest접근가능)"?></option>
												<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">상세페이지 접근 :</span>
										</td>
										<td>
											<select name="yhb_grant_view">
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_view == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest접근가능)"?></option>
												<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">글작성 권한 :</span>
										</td>
										<td>
											<select name="yhb_grant_write"<?=$_c_result->yhb_admin_write == 1 ? " disabled" : ""?>>
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_write == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest작성가능)"?></option>
												<? } ?>
											</select>
											<input onChange="Board_Admin_Grant(this, 'yhb_grant_write');" type="checkbox" name="yhb_admin_write" id="yhb_admin_write" value="1"<?=$_c_result->yhb_admin_write == 1 ? " checked" : ""?>>
											<label for="yhb_admin_write">관리자 권한설정</label>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">답글 작성 권한 :</span>
										</td>
										<td>
											<select name="yhb_grant_reply"<?=$_c_result->yhb_admin_reply == 1 ? " disabled" : ""?>>
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_reply == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest작성가능)"?></option>
												<? } ?>
											</select>
											<input onChange="Board_Admin_Grant(this, 'yhb_grant_reply');" type="checkbox" name="yhb_admin_reply" id="yhb_admin_reply" value="1"<?=$_c_result->yhb_admin_reply == 1 ? " checked" : ""?>>
											<label for="yhb_admin_reply">관리자 권한설정</label>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">뎃글 작성 권한 :</span>
										</td>
										<td>
											<select name="yhb_grant_memo"<?=$_c_result->yhb_admin_memo == 1 ? " disabled" : ""?>>
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_memo == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest작성가능)"?></option>
												<? } ?>
											</select>
											<input onChange="Board_Admin_Grant(this, 'yhb_grant_memo');" type="checkbox" name="yhb_admin_memo" id="yhb_admin_memo" value="1"<?=$_c_result->yhb_admin_memo == 1 ? " checked" : ""?>>
											<label for="yhb_admin_memo">관리자 권한설정</label>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">삭제 권한 :</span>
										</td>
										<td>
											<select name="yhb_grant_delete"<?=$_c_result->yhb_admin_delete == 1 ? " disabled" : ""?>>
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_delete == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest삭제가능)"?></option>
												<? } ?>
											</select>
											<input onChange="Board_Admin_Grant(this, 'yhb_grant_delete');" type="checkbox" name="yhb_admin_delete" id="yhb_admin_delete" value="1"<?=$_c_result->yhb_admin_delete == 1 ? " checked" : ""?>>
											<label for="yhb_admin_delete">관리자 권한설정</label>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">공지사항 작성 권한 :</span>
										</td>
										<td>
											<select name="yhb_grant_notice"<?=$_c_result->yhb_admin_notice == 1 ? " disabled" : ""?>>
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_notice == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest작성가능)"?></option>
												<? } ?>
											</select>
											<input onChange="Board_Admin_Grant(this, 'yhb_grant_notice');" type="checkbox" name="yhb_admin_notice" id="yhb_admin_notice" value="1"<?=$_c_result->yhb_admin_notice == 1 ? " checked" : ""?>>
											<label for="yhb_admin_notice">관리자 권한설정</label>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">비밀글 작성 권한 :</span>
										</td>
										<td>
											<select name="yhb_grant_secret"<?=$_c_result->yhb_admin_secret == 1 ? " disabled" : ""?>>
												<? for($i = 10; $i > 0; $i--) { ?>
												<option value="<?=$i?>"<?=$_c_result->yhb_grant_secret == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest작성가능)"?></option>
												<? } ?>
											</select>
											<input onChange="Board_Admin_Grant(this, 'yhb_grant_secret');" type="checkbox" name="yhb_admin_secret" id="yhb_admin_secret" value="1"<?=$_c_result->yhb_admin_secret == 1 ? " checked" : ""?>>
											<label for="yhb_admin_secret">관리자 권한설정</label>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
				<tbody>
					<tr><th colspan="3" class="yh_regist_title_top"><h2>게시판연동 및 카테고리 설정</h2></th><td class="yh_middle_submit"><input type="submit" value="게시판정보저장"><a href="<?=$PHP_SELF?>?_admin=board_manager<?=$query_string?>" class="a_button">뒤로가기</a></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">게시판 연동</span></th>
						<td colspan="3">준비중....</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">카테고리설정</span></th>
						<td colspan="3" class="yhyh_category_form">
							<input type="hidden" name="yhb_category_number" id="yhb_category_number" value="">
							<select name="yhb_category_color" id="yhb_category_color">
								<option>#DFDFDF</option>
								<option>#FF0000</option>
							</select>
							<input name="yhb_category_name" type="text" id="yhb_category_name" title="카테고리 이름" value="" size="15" alt="필수">
							<label for="yhb_category_name" class="placeHolder">카테고리명</label>
							<input type="checkbox" name="yhb_category_link_use" id="yhb_category_link_use" value="1">
							<label for="yhb_category_link_use">링크</label>
							<span class="yhyh_category_link_view">
								<span class="field_title">주소</span>
								<input name="yhb_category_link" type="text" id="yhb_category_link" title="카테고리 링크주소" value="" size="24">
								<label for="yhb_category_link" class="placeHolder">ex) http://linkdomain.com</label>
							</span>
							<input id="category_save_button" onClick="Category_Save_Action('');" type="button" value="신규추가">
							<input id="category_modify_cancel" type="button" onClick="Category_Modify_Action('', '');" value="수정취소">
							<ul class="yhyh_category_list">
								<?
								$c_query = "select yhb_name, yhb_number, yhb_no, yhb_color, yhb_url from yh_category where yhb_board_no = '".$_c_result->yhb_number."' order by yhb_number desc";
								$ct_result = $_LhDb->Query($c_query);
								while ($ct = $_LhDb->Fetch_Object($ct_result)) {
										$c_count = $_LhDb->Query_Row_Num("select yhb_number from yh_board where yhb_board_name = '".$_c_result->yhb_name."' AND yhb_category = '".$ct->yhb_no."'");
								?>
								<li id="category_<?=$ct->yhb_number?>">
									<a<?=$_c_result->yhb_name ? " href=\""._lh_yhyh_web."/?_id=".$_c_result->yhb_name."&_ct=".$ct->yhb_no."\" target=\"_blank\"" : ""?> class="category_number"><?=$ct->yhb_no?></a><span class="category_color" data-color="<?=$ct->yhb_color?>">■</span><span class="category_name"><?=$ct->yhb_name?></span>
<? if($ct->yhb_url) { ?><span class="category_url"><?=$ct->yhb_url?></span><? } ?><small class="category_rows">(<?=number_format($c_count)?>)</small>
									<a href="" onClick="Category_Save_Action('<?=$ct->yhb_number?>'); return false;" class="a_button">삭제</a><a href="" onClick="Category_Modify_Action('<?=$ct->yhb_number?>', '<?=$ct->yhb_name?>'); return false;" class="a_button">수정</a>
								</li>
								<? } ?>
							</ul>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
				<tbody>
					<tr><th colspan="3" class="yh_regist_title_top"><h2>추가 기능</h2></th><td class="yh_middle_submit"><input type="submit" value="게시판정보저장"><a href="<?=$PHP_SELF?>?_admin=board_manager<?=$query_string?>" class="a_button">뒤로가기</a></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">1:1 게시판</span></th>
						<td colspan="3">
							<select name="yhb_mtm_grade">
								<option value="">전체회원</option>
								<? for($i = 10; $i > 0; $i--) { ?>
								<option value="<?=$i?>"<?=$_c_result->yhb_mtm_grade == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10"?></option>
								<? } ?>
							</select>
							<input type="checkbox" name="yhb_use_member" id="yhb_use_member" value="1"<?=$_c_result->yhb_use_member == 1 ? " checked" : ""?>>
							<label for="yhb_use_member">관리자와 회원간에 1:1로만 글을 볼 수 있습니다.</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">관리자 승인 게시판</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_agreement" id="yhb_use_agreement" value="1"<?=$_c_result->yhb_use_agreement == 1 ? " checked" : ""?>>
							<label for="yhb_use_agreement">체크 할 경우 관리자가 출력 승인을 해야만 출력이 됩니다.</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">주문형 게시판</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_joo" id="yhb_use_joo" value="1"<?=$_c_result->yhb_use_joo == 1 ? " checked" : ""?>>
							<label for="yhb_use_joo">신규 글쓰기만 가능하게 하는 기능</label>
							입니다.</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">카테고리 기능</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_category" id="yhb_use_category" value="1"<?=$_c_result->yhb_use_category == 1 ? " checked" : ""?>>
							<label for="yhb_use_category">카테고리 기능을 활성화 합니다.</label>
							입니다.</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">작성 후 상세보기</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_view" id="yhb_use_view" value="1"<?=$_c_result->yhb_use_view == 1 ? " checked" : ""?>>
							<label for="yhb_use_view">글 작성이 완료되면 상세보기 화면으로 들어갑니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">뎃글 기능(코멘트)</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_memo" id="yhb_use_memo" value="1"<?=$_c_result->yhb_name ? $_c_result->yhb_use_memo == 1 ? " checked" : "" : " checked"?>>
							<label for="yhb_use_memo">뎃글 기능을 활성화 합니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">뎃글 상단 텍스트</span></th>
						<td colspan="3">
							<textarea name="yhb_memo_title" cols="80" rows="2" id="yhb_memo_title" title="뎃글 상단에 뎃글에 대한 주의 사항등을 작성"><?=$_c_result->yhb_memo_title ? $_c_result->yhb_memo_title : ""?></textarea>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">비밀 뎃글</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_memosecret" id="yhb_use_memosecret" value="1"<?=$_c_result->yhb_use_memosecret == 1 ? " checked" : ""?>>
							<label for="yhb_use_memosecret">비밀 형태의 뎃글 작성이 가능하게 됩니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">뎃글에 파일첨부</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_memoimg" id="yhb_use_memoimg" value="1"<?=$_c_result->yhb_use_memoimg == 1 ? " checked" : ""?>>
							<label for="yhb_use_memoimg">뎃글 작성시 파일 첨부가 가능하게 됩니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">비밀글 사용</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_secret" id="yhb_use_secret" value="1"<?=$_c_result->yhb_use_secret == 1 ? " checked" : ""?>>
							<label for="yhb_use_secret">글 작성시 비밀 형태의 글을 올릴 수 있습니다. 회원의 경우는 관리자만 확인이 가능합니다.</label></td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">비밀글 기본 사용</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_secret_write" id="yhb_use_secret_write" value="1"<?=$_c_result->yhb_use_secret_write == 1 ? " checked" : ""?>>
							<label for="yhb_use_secret_write">글 작성시 비밀글 체크가 기본이 되게 합니다.</label></td>
					</tr>
				</tbody>
			</table>
			<div class="yhyh_write_button">
				<input type="submit" value="게시판정보저장">
				<a href="<?=$PHP_SELF?>?_admin=board_manager<?=$query_string?>" class="a_button">뒤로가기</a>
			</div>
			<textarea cols="100" rows="20" id="testView" style="display:none;"></textarea>
		</fieldset>
	</form>
</div>
<? if($_solo_mode) { ?>
</body>
</html>
<? } ?>