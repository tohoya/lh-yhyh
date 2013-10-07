<?php
// 반드시 설정되어저야 한다.
include_once("../../../module/inc/root_info.php");

echo $_REQUEST["htImageInfo"];
function File_Ext($files) {
	return substr(strrchr($files,"."),1);
}

function File_Name($files) {
	return substr($files, 0, (strripos($files,".")));
}


// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$uploadDir = '../../upload/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}
	
	$new_path = $uploadDir.urlencode($name);
	
	$i = 0;
	while(file_exists($new_path)) {
		$i++;
		$new_path = $uploadDir.urlencode(File_Name($name))."_".$i.".".urlencode(File_Ext($name));
	}
	
	if($i > 0) {
		$name = File_Name($name)."_".$i.".".File_Ext($name);
	}
	
	@move_uploaded_file($tmp_name, $new_path);
	
	$_web_root = _lh_yhyh_web."/se/upload/";
	
	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($name));
	$url .= "&sFileURL=".$_web_root.urlencode(urlencode($name));
}
// FAILED
else {
	$url .= '&errstr=error';
}

header('Location: '. $url);
?>