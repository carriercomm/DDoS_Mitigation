<?php
function doBlock($IP) {
  global $DOCUMENT_ROOT,$db,$HOST;
  $IP=ltrim(rtrim($IP));
  $rsw=$db->Execute("Select * from white_list where IP=".$db->qstr($IP));
  $rsb=$db->Execute("Select * from block_list where IP=".$db->qstr($IP)." and host=".$db->qstr($HOST));
  if ($rsw->EOF && $rsb->EOF && strpos("_".$IP,"192.168.")!=1 && preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $IP)) {
    $db->Execute("Lock tables block_list,block_log Write");
    $db->Execute("Insert into block_list (IP,host) values (".$db->qstr($IP).",".$db->qstr($HOST).")");
    //exec("/sbin/iptables -A RH-Firewall-1-INPUT -m state --state NEW -m tcp -p tcp -s ".$IP." --dport 80 -j DROP");
    exec("/sbin/iptables -I INPUT 1 -s ".$IP." -j DROP");
    $db->Execute("Insert into block_log (IP,Action,host) values (".$db->qstr($IP).",".$db->qstr("Block").",".$db->qstr($HOST).")");
    $db->Execute("Unlock tables");
  } 
}
function doRelease($IP) {
  global $DOCUMENT_ROOT,$db,$HOST;
  $IP=ltrim(rtrim($IP));
  $rs=$db->Execute("Select * from block_list where IP=".$db->qstr($IP)." and host=".$db->qstr($HOST));
  if (!$rs->EOF) {
    $db->Execute("Lock tables block_list,block_log Write");
    $db->Execute("Delete from block_list where IP=".$db->qstr($IP));
    //exec("/sbin/iptables -D RH-Firewall-1-INPUT -m state --state NEW -m tcp -p tcp -s ".$IP." --dport 80 -j DROP");
    exec("/sbin/iptables -D INPUT -s ".$IP." -j DROP");
    $db->Execute("Insert into block_log (IP,Action,host) values ("
			.$db->qstr($IP).","
			.$db->qstr("Release").","
			.$db->qstr($HOST).")");
    $db->Execute("Unlock tables");
  }
}
function doExpire() {
  global $DOCUMENT_ROOT,$db,$HOST,$AUTO_RELEASE_TIME;
  $rs=$db->Execute("Select * from block_list where host=".$db->qstr($HOST)." and UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(block_ts)>".$AUTO_RELEASE_TIME);
  while (!$rs->EOF) {
    doRelease($rs->fields[0]);
    $rs->MoveNext();
  }
}
?>
