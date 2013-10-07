<?
if(!$_REQUEST["_year"]) $_REQUEST["_year"] = date("Y");
if(!$_REQUEST["_month"]) $_REQUEST["_month"] = date("n");
if(!$_REQUEST["_day"]) $_REQUEST["_day"] = date("d");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>일정보기</title>
<link href="../../common/css/default.css" rel="stylesheet" type="text/css">
<link href="../../common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="css/default.css" rel="stylesheet" type="text/css">
<script>

Delete_Complete = function($_option) {
	Schedule_Get_List("<?=$_REQUEST["_year"]?>", "<?=$_REQUEST["_month"]?>", "<?=$_REQUEST["_day"]?>");
	Schedule_Load_Get_List(); //start_time.getFullYear(), (start_time.getMonth() + 1), start_time.getDate(), end_time.getFullYear(), (end_time.getMonth() + 1), end_time.getDate());
	if($_option && $_option.message) {
		alert($_option.message);
	}
};

</script>
</head>
<body>
<div class="yhyh_pop_body" style="width:550px; height:500px;">
	<div class="yhyh_pop_header">
		<h3><p>Detail Schedule <?=$_REQUEST["_year"]?>.<?=$_REQUEST["_month"]?>.<?=$_REQUEST["_day"]?></p></h3>
		<a href="javascript:;" onClick="Schedule_Pop_Show();" class="a_button numberFont">Close</a>
	</div>
	<div class="yhyh_pop_content">
		<ul id="schedule_view_list">
		</ul>
	</div>
	<div class="yhyh_pop_footer">
	<? if($_LhDb->Get_Admin()) { ?>
		<a href="javascript:;" onClick="Write_Pop_Action('<?=_lh_yhyh_web?>/index.php?_skin=write_pop&_id=<?=$_REQUEST["_id"]?>&_year=<?=$_REQUEST["_year"]?>&_month=<?=$_REQUEST["_month"]?>&_day=<?=$_REQUEST["_day"]?>');" class="a_button view_new_schedule">New Schedule</a>
	<? } ?>
	</div>
</div>
</body>
</html>