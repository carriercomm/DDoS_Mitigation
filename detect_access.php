<?php
//統計每分鐘存取 php file 次數
include("/opt/admin/scripts/DOS_Mitigation/config.php");
include($DOCUMENT_ROOT."common.inc");
date_default_timezone_set('Asia/Taipei');
$ErrLog[] = "/home/httpd/myday.cn/var/log/access_log";

//檢查 Apache process 數
exec("/bin/ps auwx|/bin/grep httpd|/usr/bin/wc -l",$ret);
$Httpd_Num = chop($ret[0]);
if ( !is_numeric($Httpd_Num) || $Httpd_Num < $Apache_Process_Safe_Count ) {
  doExpire();
  exit;
}

$GrepStr = date("Y:H:i", mktime()-60);
for ($i=0;$i<sizeof($ErrLog);$i++) {
  $StatLog=uniqid("/var/tmp/stat.");
  exec("/bin/grep '".$GrepStr."' $ErrLog[$i] | /bin/awk '{print $1\" \"$7}' | /bin/grep php | /bin/awk '{print $1}' | /bin/sort | /usr/bin/uniq -c | /bin/sort -n > ".$StatLog);
  //超過 9 次就封鎖
  exec("/usr/bin/php -q /opt/admin/scripts/DOS_Mitigation/main.php 9 ".$StatLog);
  exec("/sbin/service httpd restart");
}
?>
