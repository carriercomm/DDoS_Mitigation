<?
$DOCUMENT_ROOT="/opt/admin/scripts/DOS_Mitigation/";

$HOST="c1";
$AUTO_RELEASE_TIME=60 * 10;
$Apache_Process_Safe_Count = 1500;

include($DOCUMENT_ROOT."adodb5/adodb.inc.php");
$db = NewADOConnection('mysql');
$db->Connect("db2", "admin", "jochi777day888", "DOS_MITIGATION");


?>
