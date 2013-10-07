<?
$f_url = _lh_document_root._lh_yhyh_web."/upload/".$_REQUEST["_file_name"];

$query = "select f_name from yh_file where s_name = '".$_REQUEST["_file_name"]."'";
$f_result = $_LhDb->Fetch_Object_Query($query);

//if(!$_REQUEST[f_orginal]) $_REQUEST[f_orginal] = iconv("utf-8", "euc-kr", $_jbook->cs_name)."(".$_jj->cs_year.") page ".$_jj->cs_page_start."_".$_jj->cs_page_end.".".$_LhDb->File_Ext($_REQUEST[f_name]);

$f_size = filesize($f_url);

// 선택된 파일 열기
$f = @fopen($f_url, "r");

// 사이즈 별로 읽어오기
$f_data = fread($f, $f_size > 0 ? $f_size : 1); 

// 열었던 파일 닫기
fclose($f); 

// 다운로드에 대한 해더 설정
if(strstr($HTTP_USER_AGENT, "MSIE 5.5")) {
	header("Content-Type: doesn/matter");
	header("Content-Disposition: filename=".iconv("utf-8", "euc-kr", $f_result->f_name));
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$f_size);
	header("Pragma: no-cache");
	header("Expires: 0");
} else {
	header("Content-type: file/unknown");
	header("Content-Disposition: attachment; filename=".iconv("utf-8", "euc-kr", $f_result->f_name));
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$f_size);
	header("Content-Description: PHP3 Generated Data");
	header("Pragma: no-cache");
	header("Expires: 0");
}
// 파일 다운로드 출력
print $f_data;
?>