<?php
date_default_timezone_set('Asia/Taipei');
$ErrLog[] = "/home/httpd/myday.cn/var/log/error_log";

/*
$StartTS=mktime(4,3,0,8,6,2014);
for ($i=0;$i<=1430;$i++) {
  $ts=$StartTS + $i*60;
  $str=date(" M d H:i:",$ts);
  system("echo -n $str;grep \"$str\" /home/httpd/myday.cn/var/log/error_log.1 | wc -l");
}
*/

$GrepStr = date(" M d H:i:", mktime()-60);
//echo $GrepStr."\n";
for ($i=0;$i<sizeof($ErrLog);$i++) {
  //@unlink("/var/tmp/stat.log");
  $StatLog=uniqid("/var/tmp/stat.");
  exec("/bin/grep '".$GrepStr."' $ErrLog[$i] | /bin/awk '{print $8}' | /bin/sed -e 's/]//' | /bin/sort | /usr/bin/uniq -c | /bin/sort -n > ".$StatLog);
}
?>
