<?php
$ipAddress=$_SERVER['REMOTE_ADDR'];
$macAddr=false;

#run the external command, break output into lines
$arp=`sudo arp -a $ipAddress`;

echo $ipAddress;
echo $arp;

?>
