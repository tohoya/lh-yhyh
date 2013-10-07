<?

$fileName = md5(time());

$fileDirData = opendir(_lh_document_root._lh_yhyh_web."/upload/");

$_upload_field = $_REQUEST["_upload_type"];
echo $_upload_field;
if($_FILES[$_upload_field]["name"] && $_FILES[$_upload_field]["tmp_name"]) {
	if($_POST["_no"] && strtolower($_POST["_writeMode"]) == "modify") {
		$where = "board_no = '".$_POST["_no"]."'";
		$_board_no = $_POST["_no"];
	}
	$_idx = TableMax("yh_file", "seq", "1", "");
	$_order = TableMax("yh_file", "f_order", 1, $where);

	$save_name = "file_".$_POST["_id"]."_".$fileName.".".File_ext($_FILES[$_upload_field]["name"]);
	$save_file = _lh_yhyh_web."/upload/".$save_name;
	
	$field = "seq
		, group_no
		, board_no
		, board_name
		, f_name
		, s_name
		, f_size
		, f_ext
		, f_order
	";
	
	$values = "'".$_idx."'
		, '".$_config->yhb_group_no."'
		, '".$_board_no."'
		, '".$_POST["_id"]."'
		, '".$_FILES[$_upload_field]["name"]."'
		, '".$save_name."'
		, '".$_FILES[$_upload_field]["size"]."'
		, '".File_ext($_FILES[$_upload_field]["name"])."'
		, '".$_order."'
	";
	
	$query = "insert into yh_file(".$field.") values(".$values.")";
	if(!$_LhDb->Query($query)) {
		?>
		<p class="error">파일 업로드에 실패하였습니다.</p>
		<?
		exit();
	}
	move_uploaded_file($_FILES[$_upload_field]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$save_file);
	?>
	<p class="complete"><span><?=$save_file?></span><span><?=$save_name?></span><span><?=$_FILES[$_upload_field]["name"]?></span><span><?=$_FILES[$_upload_field]["size"]?></span><span><?=File_ext($_FILES[$_upload_field]["name"])?></span><span><?=$_idx?></span></p>
	<?
} else {
	?>
	<p class="error">파일 업로드에 실패하였습니다.</p>
	<?
}
?>