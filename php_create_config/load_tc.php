<?php 
error_reporting(E_ALL);
	include '../config.php';
	$t = "\n#poprzednie iptables /var/www/shscripts/firewall";
	$t .="\n#poprzednie iptables /var/www/shscripts/loadiptables.txt";
	$t .= "\n/var/www/shscripts/firewall
	\n#USTAWIENIA KART SIECIOWYCH
tc qdisc del dev eth1 root
tc -s qdisc ls dev eth1
tc qdisc del dev eth0 root
tc -s qdisc ls dev eth0
/sbin/tc qdisc add dev eth1 root handle 1: htb
/sbin/tc class add dev eth1 parent 1: classid 1:1 htb rate 1000mbit
/sbin/tc qdisc add dev eth0 root handle 1: htb
/sbin/tc class add dev eth0 parent 1: classid 1:1 htb rate 1000mbit


#DOWNLOAD Domyslne
/sbin/tc class add dev eth1 parent 1:1 classid 1:0x".dechex(11)." htb rate 10mbit prio 2
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle 0x".dechex(11)." fw flowid 1:0x".dechex(11)."
#WWW Domyslne
/sbin/tc class add dev eth1 parent 1:1 classid 1:0x".dechex(10)." htb rate 15mbit prio 1
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle 0x".dechex(10)." fw flowid 1:0x".dechex(10)."
#UPLOAD Domyslne
/sbin/tc class add dev eth0 parent 1:1 classid 1:0x".dechex(12)." htb rate 3mbit prio 1
/sbin/tc filter add dev eth0 parent 1:0 prio 1 protocol ip handle 0x".dechex(12)." fw flowid 1:0x".dechex(12)."
";

	$sql = $con->query('SELECT u.id as id , login, ip, login, downloadhttp, downloadall, upload 
	FROM devices AS d LEFT JOIN users as u on u.id = d.user_id order by u.id asc');
	$markNR=100;
	$lastUserID=0;
	while($sqlx=$sql->fetch_assoc())
	{
		if($lastUserID != $sqlx['id']){
			$t .= "\n			#!!!!!!!!NOWY USER - ".$sqlx['login']." - ".$sqlx['ip']."";
			$markNR++;
			$lastUserID=$sqlx['id'];
			if ($sqlx['downloadall'] != "0" && $sqlx['downloadall'] != "" && $sqlx['downloadall'] != " " ){
				$markid = $markNR + 20000; //download
				$markid = dechex($markid);$markid = '0x'.$markid;
				$t .="\n#DOWNLOAD
/sbin/tc class add dev eth1 parent 1:1 classid 1:".$markid." htb rate ".$sqlx['downloadall']."mbit prio 2
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle ".$markid." fw flowid 1:".$markid."
iptables -t mangle -I POSTROUTING -p all -d ".$sqlx['ip']." -j MARK --set-mark ".$markid."";
			}else{
				$t .="\n#DOWNLOAD
iptables -t mangle -I POSTROUTING -p all -d ".$sqlx['ip']." -j MARK --set-mark 0x".dechex(11)."";
			}
			
			if ($sqlx['downloadhttp'] != "0" && $sqlx['downloadhttp'] != "" && $sqlx['downloadhttp'] != " " ){
				$markid = $markNR + 10000;$markid = dechex($markid);$markid = '0x'.$markid; //www
				$t .="\n#WWW
/sbin/tc class add dev eth1 parent 1:1 classid 1:".$markid." htb rate ".$sqlx['downloadhttp']."mbit prio 1
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle ".$markid." fw flowid 1:".$markid."
iptables -t mangle -A POSTROUTING -p tcp -d ".$sqlx['ip']." --sport 80 -j MARK --set-mark ".$markid."";
			}else{
				$t .="\n#WWW
iptables -t mangle -A POSTROUTING -p tcp -d ".$sqlx['ip']." --sport 80 -j MARK --set-mark 0x".dechex(10)."";
			}
			
			if ($sqlx['upload'] != "0" && $sqlx['upload'] != "" && $sqlx['upload']!= " " ){
				$markid = $markNR + 30000; $markid = dechex($markid);$markid = '0x'.$markid;//upload
				$t .="\n#UPLOAD
/sbin/tc class add dev eth0 parent 1:1 classid 1:".$markid." htb rate ".$sqlx['upload']."mbit prio 1
/sbin/tc filter add dev eth0 parent 1:0 prio 1 protocol ip handle ".$markid." fw flowid 1:".$markid."
iptables -i eth1 -t mangle -I PREROUTING -p all -s ".$sqlx['ip']." -j MARK --set-mark ".$markid."";
			}else{
				$t .="\n#WWW
iptables -i eth1 -t mangle -I PREROUTING -p all -s ".$sqlx['ip']." -j MARK --set-mark 0x".dechex(12)."";
			}

		}
		else{
			$t .= "\n			#NOWY KOMP ".$sqlx['ip']."";
			if ($sqlx['downloadall'] != "0" && $sqlx['downloadall'] != "" && $sqlx['downloadall'] != " " ){
				$markid = $markNR + 20000;$markid = dechex($markid); $markid = '0x'.$markid;//download
				$t .="\n#DOWNLOAD
iptables -t mangle -I POSTROUTING -p all -d ".$sqlx['ip']." -j MARK --set-mark ".$markid."";
			}else{
				$t .="\n#DOWNLOAD
iptables -t mangle -I POSTROUTING -p all -d ".$sqlx['ip']." -j MARK --set-mark 0x".dechex(11)."";
			}
			if ($sqlx['downloadhttp'] != "0" && $sqlx['downloadhttp'] != "" && $sqlx['downloadhttp'] != " " ){
				$markid = $markNR + 10000;$markid = dechex($markid); $markid = '0x'.$markid;//www
				$t .="\n#WWW
iptables -t mangle -A POSTROUTING -p tcp -d ".$sqlx['ip']." --sport 80 -j MARK --set-mark ".$markid."";
			}else{
				$t .="\n#WWW
iptables -t mangle -A POSTROUTING -p tcp -d ".$sqlx['ip']." --sport 80 -j MARK --set-mark 0x".dechex(10)."";
			}
			if ($sqlx['upload'] != "0" && $sqlx['upload'] != "" && $sqlx['upload']!= " " ){
				$markid = $markNR + 30000;$markid = dechex($markid); $markid = '0x'.$markid;//upload
				$t .="\n#UPLOAD
iptables -i eth1 -t mangle -I PREROUTING -p all -s ".$sqlx['ip']." -j MARK --set-mark ".$markid."";
			}else{
				$t .="\n#WWW
iptables -i eth1 -t mangle -I PREROUTING -p all -s ".$sqlx['ip']." -j MARK --set-mark 0x".dechex(12)."";
			}
		}
	}
		$t .=  "\niptables -t mangle -I POSTROUTING -p all -d 10.0.0.0/22 -j MARK --set-mark 0x".dechex(11)."";
		$t .=  "\niptables -t mangle -I POSTROUTING -p tcp -d 10.0.0.0/22 --dport 80 -j MARK --set-mark 0x".dechex(10)."";
		$t .=  "\niptables -i eth1 -t mangle -I PREROUTING -p all -s 10.0.0.0/22 -j MARK --set-mark 0x".dechex(12)."";
		
	
	echo $t;
$file = 'load_tc.txt';
$current = $t;
file_put_contents($file, $current);
?>

