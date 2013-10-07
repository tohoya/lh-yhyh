<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>새로운 일정 작성 및 수정</title>
<link href="../../common/css/default.css" rel="stylesheet" type="text/css">
<link href="../../common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="css/default.css" rel="stylesheet" type="text/css">
<script>

function FormSubmitStart(f) {
	
	if($("[name=yhb_title]", f).length > 0 && !f.yhb_title.value) {
		alert("일정의 간단한 설명을 적어주세요.");
		f.yhb_title.focus();
		return false;
	}
	var start_str = $("#start_time_date").val().toString();
	var start_time = new Date(start_str.Get_Split(0, "."), Number(start_str.Get_Split(1, ".")) - 1, start_str.Get_Split(2, "."), $("#start_time_hour").val(), $("#start_time_minute").val());
	$("#yhb_text1").val(Math.ceil(start_time.getTime()/1000));
	
	var end_str = $("#end_time_date").val().toString();
	var end_time = new Date(end_str.Get_Split(0, "."), Number(end_str.Get_Split(1, ".")) - 1, end_str.Get_Split(2, "."), $("#end_time_hour").val(), $("#end_time_minute").val());
	$("#yhb_text2").val(Math.ceil(end_time.getTime()/1000));
	//alert($("#yhb_text1").val() + " : " + $("#yhb_text2").val());
	/*return false;*/
	f.write_complete_LHtmlEditers.value = 1;
	f._module.value = "write_proc";
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		//alert(data);
		//return;
		var p = $("<p/>").html(data);
		var type = $("> p", p).attr("title");

		if($("> p", p).attr("title") == "error") {
			//alert($("> p", p).attr("title"));
			$(".yhyh_textarea").css("display", "block").find("> textarea").val(data);
		} else {
			switch(type) {
				case "write":
					alert("일정 등록이 완료되었습니다.");
				break;
				case "modify":
					alert("일정 수정이 완료 되었습니다.");
				break;
				case "reply":
					alert("일정 작성이 완료 되었습니다.");
				break;
			}
		}
		Schedule_Load_Get_List(); //start_time.getFullYear(), start_time.getMonth() + 1, start_time.getDate(), end_time.getFullYear(), end_time.getMonth() + 1, end_time.getDate());
		Schedule_Get_List("<?=$_REQUEST["_year"]?>", "<?=$_REQUEST["_month"]?>", "<?=$_REQUEST["_day"]?>");
		Schedule_Pop_Show('prev');
	});
	
}

</script>
</head>
<body>
<div class="yhyh_pop_body" style="width:550px; height:500px;">
	<form class="FormDesignNormal" name="yhyh_write" id="yhyh_write" method="POST" onSubmit="return FormSubmitStart(this);" enctype="multipart/form-data">
		<fieldset>
			<legend>신규일정 작성 및 수정</legend>
			<input type="hidden" name="_module" value="write_proc">
			<input type="hidden" name="_writeMode" value="<?=$_REQUEST["_writeMode"]?>">
			<input type="hidden" name="_id" value="<?=$_REQUEST["_id"]?>">
			<input type="hidden" name="_no" value="<?=$_REQUEST["_no"]?>">
			<input type="hidden" name="yhb_html" value="2">
			<input type="hidden" name="yhb_name" value="<?=$_result->name ? $_result->name : "기본일정"?>">
			<input type="hidden" name="yhb_board_pass" value="">
			<input type="hidden" name="yhb_text1" id="yhb_text1" value="<?=$_result->start_time?>">
			<input type="hidden" name="yhb_text2" id="yhb_text2" value="<?=$_result->end_time?>">
			<input type="hidden" name="yhb_board_pass" value="">
			<input type="hidden" id="yhb_file" name="yhb_file" value="<?=$_files?>">
			<input type="hidden" id="write_complete_LHtmlEditers" name="write_complete_LHtmlEditers" value="">
			<input type="hidden" id="file_add_LHtmlEditers" name="file_add_LHtmlEditers" value="">
			<input type="hidden" id="file_delete_LHtmlEditers" name="file_delete_LHtmlEditers" value="">
			<div class="yhyh_pop_header">
				<h3><p>Detail Schedule <?=$_REQUEST["_year"]?>.<?=$_REQUEST["_month"]?>.<?=$_REQUEST["_day"]?></p></h3>
				<a href="javascript:;" onClick="Schedule_Pop_Show();" class="a_button numberFont">Close</a>
			</div>
			<div class="yhyh_pop_content">
				<div class="yhyh_input_text">
					<input type="text" name="yhb_title" id="yhb_title" value="<?=$_result->title?>" size="70" title="일정 간단한 설명">
					<label for="yhb_title" class="placeHolder">일정 간단한 설명</label>
				</div>
				<div class="yhyh_input_text">
					<input readonly onFocus="FormMiniCalender(this, '.');" name="start_time_date" class="text_align_center" type="text" id="start_time_date" title="시작되는 일시" value="<?=$_result->start_time_date?>" size="13" maxlength="10">
					<label for="start_time_date" class="placeHolder">시작되는 일시</label>
					<select name="start_time_hour" id="start_time_hour">
						<?
						for($i = 0; $i < 24; $i++) {
							$h = $i > 9 ? $i : "0".$i;
						?>
						<option value="<?=$i?>"<?=$_result->start_time_hour == $i ? " selected" : ""?>><?=$h?>시</option>
						<? } ?>
					</select>
					<select name="start_time_minute" id="start_time_minute">
						<?
						for($i = 0; $i < 60; $i+=10) {
							$m = $i > 9 ? $i : "0".$i;
						?>
						<option value="<?=$i?>"<?=$_result->start_time_minute == $i ? " selected" : ""?>><?=$m?>분</option>
						<? } ?>
					</select>
					<span>~</span>
					<input readonly onFocus="FormMiniCalender(this, '.');" name="end_time_date" class="text_align_center" type="text" id="end_time_date" title="시작되는 일시" value="<?=$_result->end_time_date?>" size="13" maxlength="10">
					<label for="end_time_date" class="placeHolder">시작되는 일시</label>
					<select name="end_time_hour" id="end_time_hour">
						<?
						for($i = 0; $i < 24; $i++) {
							$h = $i > 9 ? $i : "0".$i;
						?>
						<option value="<?=$i?>"<?=$_result->end_time_hour == $i ? " selected" : ""?>><?=$h?>시</option>
						<? } ?>
					</select>
					<select name="end_time_minute" id="end_time_minute">
						<?
						for($i = 0; $i < 60; $i+=10) {
							$m = $i > 9 ? $i : "0".$i;
						?>
						<option value="<?=$i?>"<?=$_result->end_time_minute == $i ? " selected" : ""?>><?=$m?>분</option>
						<? } ?>
					</select>
				</div>
				<div class="yhyh_textarea">
					<textarea name="yhb_content" id="yhb_content" cols="70" rows="20"><?=$_result->content?></textarea>
				</div>
				<?
				$c_query = "select yhb_name, yhb_no from yh_category where yhb_board_name = '".$_REQUEST["_id"]."' order by yhb_name asc";
				if($_LhDb->Query_Row_Num($c_query) > 0) {
				?>
				<div class="yhyh_input_text">
					<select name="yhb_category" id="yhb_category">
						<option value="">없음</option>
						<?
						$c_query = "select yhb_name, yhb_no from yh_category where yhb_board_name = '".$_REQUEST["_id"]."' order by yhb_name asc";
						$ct_result = $_LhDb->Query($c_query);
						while ($ct = $_LhDb->Fetch_Object($ct_result)) {
						?>
						<option value="<?=$ct->yhb_no?>"<?=$_result->category == $ct->yhb_no ? " selected" : ""?>><?=$ct->yhb_name?></option>
						<? } ?>
					</select>
					<span class="field_title">* 일정에 대한 종류를 선택하시면 됩니다.(없으면 없음으로 선택)</span>
				</div>
				<? } ?>
			</div>
			<div class="yhyh_pop_footer">
				<input type="submit" value="Save">
			<? if($_REQUEST["_direct"] != "true") { ?>
				<a href="javascript:;" onClick="Schedule_Pop_Show('prev');" class="a_button">Cancel</a>
			<? } ?>
			</div>
		</fieldset>
	</form>
</div>
</body>
</html>