<?
$dir = $_SERVER['DOCUMENT_ROOT'];

switch($_REQUEST["_delete_type"]) {
	case "write_out":
		$arr = array();
		$arr = split(",", $_REQUEST["_seqs"]);
		$count = sizeof($arr);
		for($i = 0; $i < $count; $i++) {
			$_seq = trim($arr[$i]);
			if($_seq) {
				$query = "select s_name from yh_file where seq = '".$_seq."'";
				$ff = $_LhDb->Fetch_Object_Query($query);
				$url = _lh_document_root._lh_yhyh_web."/upload/".$ff->s_name;
				if(file_exists($url)) {
					@unlink($url);
				}
				$query = "delete from yh_file where seq = '".$_seq."'";
				$_LhDb->Query($query);
			}
		}
		echo($_REQUEST["_seqs"]);
	break;
	default:
		if(file_exists($dir.$_POST["_file"])) {
			@unlink($dir.$_POST["_file"]);
			$query = "delete from  yh_file where seq = '".$_POST["_seq"]."'";
			$_LhDb->Query($query);
			echo("complete");
		} else {
			echo("no-file");
		}
}
?>