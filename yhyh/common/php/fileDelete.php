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
$dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists($dir."/".$_GET["del_file"])) {
	@unlink($dir."/".$_GET["del_file"]);
}

?>
</body>
</html>