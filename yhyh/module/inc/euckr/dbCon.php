<?php
$_LhDb = new Lh_Default_Class("mysql", $_host, $_db_user_name, $_db_user_pass, $_db_name);
switch($_LhDb->status) {
	case "db_no_connect":
	break;
	case "db_no_select";
	break;
}
?>