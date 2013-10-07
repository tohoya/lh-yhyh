<?php
// 반드시 설정되어저야 한다.
include_once("../../../module/inc/root_info.php");

function File_Ext($files) {
	return substr(strrchr($files,"."),1);
}

function File_Name($files) {
	return substr($files, 0, (strripos($files,".")));
}

 	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$file = new stdClass;
	$file->name = rawurldecode($headers['file_name']);
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");

	$uploadDir = '../../upload/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}
	
	$newPath = $uploadDir.iconv("utf-8", "cp949", $file->name);
	
	$i = 0;
	while(file_exists($newPath)) {
		$i++;
		$newPath = $uploadDir.iconv("utf-8", "cp949", File_Name($file->name)."_".$i.".".File_Ext($file->name));
	}
	
	if($i > 0) {
		$file->name = File_Name($file->name)."_".$i.".".File_Ext($file->name);
	}
	
	$_web_root = _lh_yhyh_web."/se/upload/";
	
	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$file->name;
		$sFileInfo .= "&sFileURL=".$_web_root.$file->name;
	}
	
	echo $sFileInfo;
 ?>