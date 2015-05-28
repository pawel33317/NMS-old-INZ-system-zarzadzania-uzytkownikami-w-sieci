<?php 
error_reporting(E_ALL);
	include '../config.php';
	$t = 
	"#poprzednie iptables /var/www/shscripts/firewall \n";
	//$t .="#<br>";
	
	$sql = $con->query('select id, imie, nazwisko, ip, mac, pokoj from users');
	$sql = $con->query('SELECT login, devname, ip, portyonof, porty, u.stan as stann FROM devices AS d LEFT JOIN users as u on u.id = d.user_id');
	while($sqlx=$sql->fetch_assoc())
	{
		//$t .= "\niptables -t mangle -A PREROUTING -s ".$sqlx['ip']." -j MARK --set-mark ".$sqlx['id'];
		//$t .= "\niptables -t mangle -A PREROUTING -d ".$sqlx['ip']." -j MARK --set-mark ".$sqlx['id']."\n";
		if ($sqlx['portyonof'] == "0"){
			//$ponoff="Porty otwarte poza: ";
			//$t .="\niptables -I INPUT -p all -s ".$sqlx['ip']." -j ACCEPT";
			//$t .="\niptables -A FORWARD -p all -s ".$sqlx['ip']." -j ACCEPT";
			//$t .="#<br>";
		}else{
			//$ponoff="Porty zamkniete poza: ";
			//$t .="\niptables -I INPUT -p all -s ".$sqlx['ip']." -j DROP";
			$t .="\niptables -A FORWARD -p all -s ".$sqlx['ip']." -j DROP";
			//$t .="#<br>";
		}
		//$t .= "<br>".$sqlx['login'].": ".$ponoff." ";
		
		
		$ports = explode(';', $sqlx['porty']);
		foreach ($ports as $pp) {
			if ($pp) {
				if ($sqlx['portyonof'] == "0"){
					$t .="\niptables -I FORWARD -p tcp -s ".$sqlx['ip']." --dport ".$pp." -j DROP";
					$t .="\niptables -I FORWARD -p udp -s ".$sqlx['ip']." --dport ".$pp." -j DROP";
					//$t .="#<br>";
				}else{
					$t .="\niptables -I FORWARD -p udp -s ".$sqlx['ip']." --dport ".$pp." -j ACCEPT";
					$t .="\niptables -I FORWARD -p tcp -s ".$sqlx['ip']." --dport ".$pp." -j ACCEPT";
					//$t .="#<br>\n";
				}
				//$t .= "  ---".$pp;
			}
		}
   
		
		//$t .="\niptables -I INPUT -p all -s ".$sqlx['ip']." --dport 80 -j DROP";
	}
	
	echo $t;
$file = 'loadiptables.txt';
$current = $t;
file_put_contents($file, $current);
?>

