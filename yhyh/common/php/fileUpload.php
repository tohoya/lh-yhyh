<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LHtmlEditers</title>
</head>
<body>
<?
function file_style($files) {
	return substr(strrchr($files,"."),1);
}

$count = 0;
$fileDirData = opendir($_SERVER['DOCUMENT_ROOT']."/LHtmlEditers/upload");
while($dirList = readdir($fileDirData)) {
	if(!is_dir($dirList)) {
		if(eregi($_POST["form_id"], $dirList)) {
			$count++;
		}
	}
}

if($_FILES["upload"]["name"] && $_FILES["upload"]["tmp_name"]) {
	$save_file = "/LHtmlEditers/upload/lh".$_POST["form_id"]."_".$count.".".file_style($_FILES["upload"]["name"]);
	move_uploaded_file($_FILES["upload"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$save_file);
	?>
	<p class="complete"><?=$save_file?></p>
	<p class="complete"><?=$_FILES["upload"]["name"]?></p>
	<?
} else {
	?>
	<p class="error">파일 업로드에 실패하였습니다.</p>
	<?
}
?>
</body>
</html>