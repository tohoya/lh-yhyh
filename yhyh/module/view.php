<?
/**
 * 내용 : HTML 편집기에서 작성한 내용 글 보기로 보이는 부분
 * 작성자 : 진영호(reghoya@gmail.com)
 * 작성일 : 2013. 01. 11
 * 수정일 : 2013. 09. 23
 */
 
if($_REQUEST["_no"]) {
	$query = "select yhb_title, yhb_content, yhb_html from yh_board where yhb_number = '".$_REQUEST["_no"]."'";
	$_bb = $_LhDb->Fetch_Object_Query($query);
	$_result->title = $_bb->yhb_title;
	$_result->content	= ($_bb->yhb_html == 0) ? nl2br($_bb->yhb_content) : $_bb->yhb_content;
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$_result->title?></title>
<link href="<?=_lh_yhyh_web?>/common/css/cssReset.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/htmlView.css" rel="stylesheet" type="text/css">
<script src="<?=_lh_yhyh_web?>/common/js/jquery.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.min.js"></script>
<script language="javascript">
<!--
var _frame_height = "<?=$_REQUEST["_frame_height"] || $_REQUEST["_frame_height"] == "0" ? $_REQUEST["_frame_height"] : 30?>";
$(window).load(function() {
	parent.Content_frame_resize($("body").height() + Number(_frame_height), "#<?=$_REQUEST["_content_frame"]?>", "<?=$_REQUEST["_img_width"]?>");
});
//-->
</script>
</head>

<body>
<?=$_result->content?>
</body>
</html>