<?php 
	error_reporting(E_ALL);
	include '../config.php';	
	
$t = 
	'#authoritative;
	#ddns-update-style none;

	subnet 10.0.0.0 netmask 255.255.248.0 {
		#option broadcast-address 10.0.7.255;
		range 10.0.4.2 10.0.7.254;
		option domain-name-servers 8.8.8.8, 212.51.207.67;
		#option domain-name "bambo";
		option routers 10.0.0.1;
			default-lease-time 300;
			max-lease-time 1200;
			#lease-file-name "/var/db/dhcpd.leases" ;
			#option subnet-mask 255.255.252.0;';
			
	$i=1;
	//$sql = $con->query('select imie,nazwisko,ip,mac,pokoj from users');
	$sql = $con->query('SELECT login, imie, nazwisko, mac, ip, pokoj, devname, u.stan as stann FROM devices AS d LEFT JOIN users as u on u.id = d.user_id');
	while($sqlx=$sql->fetch_assoc()){
		if($sqlx['stann'] != 2){
			$t .= "\n\thost a".$i." \t{\t#".$sqlx['imie']." ".$sqlx['nazwisko']." (".$sqlx['login'].") - ".$sqlx['pokoj']."\n\t\t";
			$t .= "hardware ethernet ".$sqlx['mac'].";\n\t\t";
			$t .= "fixed-address ".$sqlx['ip'].";\n\t";
			$t .= "}";
			$i++;
		}
	}
	$t.="\n#range dynamic-bootp 10.0.4.1 10.0.7.255;\n}";
	echo $t;

$file = 'load_dhcp_conf.txt';
$current = $t;
file_put_contents($file, $current);
?>

