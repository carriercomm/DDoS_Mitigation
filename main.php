<?php
include("/opt/admin/scripts/DOS_Mitigation/config.php");
include($DOCUMENT_ROOT."common.inc");

if ( $argc<3 || !is_numeric($argv[1]) || !is_file($argv[2]) ) {
  exit;
}

$Threshold = $argv[1];
$StatFile = $argv[2];

$fp=fopen($StatFile,"r");
while (!feof($fp)) {
  $ln = ltrim(chop(fgets($fp,4096)));
  if (strlen($ln)==0) continue;
  list($a,$b) = explode(" ",$ln);
  if ($a>$Threshold) {
    doBlock($b);
    echo $b."\n";
  }
  unset($a,$b);
}
fclose($fp);
@unlink($argv[1]);
doExpire();
?>
