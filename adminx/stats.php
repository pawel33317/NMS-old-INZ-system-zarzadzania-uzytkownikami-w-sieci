<?php


$sql = $con->query('select count(*) as ilosc from users');$row = $sql->fetch_assoc(); $wszyscy_userzy=$row['ilosc'];
$sql = $con->query('select count(*) as ilosc from devices');$row = $sql->fetch_assoc(); $wszystkie_urzadzenia=$row['ilosc'];
$srednia_luczba_urzadzeni_na_usera = round($wszystkie_urzadzenia/$wszyscy_userzy, 2);
//$sql = $con->query('select login, id as ilosc from devices');$row = $sql->fetch_assoc(); $user_z_max_urzadzeniami=$row['ilosc'];

$sql = $con->query('SELECT login, user_id, COUNT(d.user_id) as \'ilosc\'
	FROM users AS u JOIN devices AS d
		ON u.id=d.user_id
	GROUP BY user_id
	HAVING COUNT(d.user_id) =
	(SELECT MAX( ilosc )
			FROM (
			SELECT COUNT( user_id ) AS ilosc
			FROM devices
			GROUP BY user_id
			) AS tmp) ORDER BY RAND() LIMIT 1;');$row = $sql->fetch_assoc(); $user_max_urzadzen_ilosc=$row['ilosc'];$user_max_urzadzen_login=$row['login'];

$sql = $con->query('select count(*) as ilosc from users where oplata = 1');$row = $sql->fetch_assoc(); $oplaceni=$row['ilosc'];
$sql = $con->query('select count(*) as ilosc from users where stan = 2');$row = $sql->fetch_assoc(); $zablokowani=$row['ilosc'];
$sql = $con->query('select count(*) as ilosc from users where stan = 0');$row = $sql->fetch_assoc(); $nieaktywni=$row['ilosc'];
$tmp = @strtotime("now")-60*60*24*2;
$sql = $con->query('select count(*) as ilosc from users where datarejestracji > '.$tmp);$row = $sql->fetch_assoc(); $s24h=$row['ilosc'];
$sql = $con->query('select count(*) as ilosc from devices where dateadd > '.$tmp);$row = $sql->fetch_assoc(); $s24hu=$row['ilosc'];
$tmp = @strtotime("now")-60*60*24*7;
$sql = $con->query('select count(*) as ilosc from users where datarejestracji > '.$tmp);$row = $sql->fetch_assoc(); $lastweek=$row['ilosc'];
$sql = $con->query('select count(*) as ilosc from devices where dateadd > '.$tmp);$row = $sql->fetch_assoc(); $lastweeku=$row['ilosc'];

$sql = $con->query('select id from users where datarejestracji > '.$tmp);
$s48h=$sql->num_rows;
$sql = $con->query('select id from users where oplata > 0');
$soplaceni=$sql->num_rows;
$sql = $con->query('select id from users where stan = 2');
$szablokowani=$sql->num_rows;
$sql = $con->query('select id from users where stan = 0');
$snieaktywni=$sql->num_rows;

$file6 = file_get_contents($log_DIR.'panel-log.stan', true);$convert = explode("\n", $file6);$file6='';for ($i=0;$i<count($convert);$i++){$file6=$file6.$convert[$i]; if($i<count($convert)-1)$file6=$file6.'<br>';}
$file1 = file_get_contents($log_DIR.'last-5.stan', true);$convert = explode("\n", $file1);$file1='';for ($i=0;$i<count($convert);$i++){$file1=$file1.$convert[$i]; if($i<count($convert)-1)$file1=$file1.'<br>';}
$file2 = file_get_contents($log_DIR.'tail-5dmesg.stan', true);$convert = explode("\n", $file2);$file2='';for ($i=0;$i<count($convert);$i++){$file2=$file2.$convert[$i]; if($i<count($convert)-1)$file2=$file2.'<br>';}
$file3 = file_get_contents($log_DIR.'tail-5var-log-cron.stan', true);$convert = explode("\n", $file3);$file3='';for ($i=0;$i<count($convert);$i++){$file3=$file3.$convert[$i]; if($i<count($convert)-1)$file3=$file3.'<br>';}
$file4 = file_get_contents($log_DIR.'tail-5var-log-messages.stan', true);$convert = explode("\n", $file4);$file4='';for ($i=0;$i<count($convert);$i++){$file4=$file4.$convert[$i]; if($i<count($convert)-1)$file4=$file4.'<br>';}
$file5 = file_get_contents($log_DIR.'tail-10var-lib-dhcpd-dhcpd.leases.stan', true);$convert = explode("\n", $file5);$file5='';for ($i=0;$i<count($convert);$i++){$file5=$file5.$convert[$i]; if($i<count($convert)-1)$file5=$file5.'<br>';}


$u1 = file_get_contents($service_state_DIR.'dhcpd.d', true);
$u2 = file_get_contents($service_state_DIR.'httpd.d', true);
$u3 = file_get_contents($service_state_DIR.'mysqld.d', true);
$u4 = file_get_contents($service_state_DIR.'crond.d', true);
$u5 = file_get_contents($service_state_DIR.'iptables.d', true);
	//INSERT INTO `siec2`.`users` (`id`, `datarejestracji`, `imie`, `nazwisko`, `pokoj`, `wydzial`, `kierunek`, `stan`, `login`, `haslo`, `oplata`, 
	//`datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`)
	
	//INSERT INTO `siec2`.`devices` (`id`, `user_id`, `dateadd`, `mac`, `ip`, `devtype`, `devname`, `opis`, `stan`) 
	//VALUES ('22', '2', '2324', '11:22:33:44:55:66', '192.16.6.6.', 'laptop', 'laptopunio', 'opis', '1');
	

echo'
	<div class="panel panel-primary">
		<div class="panel-heading">
			Statystyki
		</div> 
		<div class="panel-footer">
			<div class="list-group" style="width:49%; float:left;">
				<a href="#" class="list-group-item"><span class="badge">'.$wszyscy_userzy.'</span>Liczba wszystkich użytkowników</a>
				<a href="#" class="list-group-item"><span class="badge">'.$wszystkie_urzadzenia.'</span>Liczba wszystkich urządzenie</a>
				<a href="#" class="list-group-item"><span class="badge">'.$user_max_urzadzen_ilosc.'</span>Użytkownik z największą liczbą urządzeń: '.$user_max_urzadzen_login.'</a>
				<a href="#" class="list-group-item"><span class="badge">'.$srednia_luczba_urzadzeni_na_usera.'</span>Średnia liczba urządzeń na użytkownika</a>
				<a href="#" class="list-group-item"><span class="badge">20480</span>Domyślna prędkość http</a>
				<a href="#" class="list-group-item"><span class="badge">10240</span>Domyślna prędkość download</a>
				<a href="#" class="list-group-item"><span class="badge">4096</span>Domyślna prędkość upload</a>
			</div>
			<div class="list-group list-group-success" style="width:49%; float:right;">
				<a href="#" class="list-group-item"><span class="badge">'.$oplaceni.'</span>Opłaconych</a>
				<a href="#" class="list-group-item"><span class="badge">'.$zablokowani.'</span>Zablokowanych</a>
				<a href="#" class="list-group-item"><span class="badge">'.$nieaktywni.'</span>Nieaktywowanych</a>
				<a href="#" class="list-group-item"><span class="badge">'.$s24hu.'</span>Zarejestrowanych urządzeń w ciągu 48h</a>
				<a href="#" class="list-group-item"><span class="badge">'.$s24h.'</span>Zarejestrowanych użytkowników w ciągu 48h</a>
				<a href="#" class="list-group-item"><span class="badge">'.$lastweeku.'</span>Zarejestrowanych urządzeń w ciągu ostatniego tygodnia</a>
				<a href="#" class="list-group-item"><span class="badge">'.$lastweek.'</span>Zarejestrowanych użytkowników ostatniego tygodnia</a>
			</div>
		<div style="clear:both;"></div>	
		</div>
	</div>
		

			<div class="alert alert-info"><strong>Monitorowane usługi (5)</strong> (zielony - działają ok)</div>
			<button type="button" class="btn ';if ($u1[0] == "0") {echo 'btn-success';}else {echo 'btn-danger';} echo '">DHCPD</button>
			<button type="button" class="btn ';if ($u2[0] == "0") {echo 'btn-success';}else {echo 'btn-danger';} echo '">HTTPD</button>
			<button type="button" class="btn ';if ($u3[0] == "0") {echo 'btn-success';}else {echo 'btn-danger';} echo '">MYSQLD</button>
			<button type="button" class="btn ';if ($u4[0] == "0") {echo 'btn-success';}else {echo 'btn-danger';} echo '">CROND</button>
			<button type="button" class="btn ';if ($u5[0] == "0") {echo 'btn-success';}else {echo 'btn-danger';} echo '">iptables -L -n</button>
';
			
			
echo '

			<h2 class="sub-header">Operacje</h2>
<table class="table table-striped">
		  <thead>
			<tr>
			  <th><a href="">Usługa</a></th>
			  <th>Operacje</th>
			</tr>
		  </thead>
		  <tbody>
		  
		  
				<tr>
				  <td><a href="">firewall</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=firewall\'">Przeładuj</button>
					  </div>
				  </td>
				</tr>

				<tr>
				  <td><a href="">TC - traffic control</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=tc\'">Przeładuj</button>
					  </div>
				  </td>
				</tr>
				
				<tr>
				  <td><a href="">dhcp - konfiguracja</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=dhcpreload\'">Przeładuj</button>
					  </div>
				  </td>
				</tr>
				
				
				<tr>
				  <td><a href="">dhcp</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=dhcp1\'">Uruchom</button>
				<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?change=dhcp2\'">Restartuj</button>
				<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?change=dhcp3\'">Wyłącz</button>
					  </div>
				  </td>
				</tr>						
				
				<tr>
				  <td><a href="">apache</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=apache1\'">Uruchom</button>
				<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?change=apache2\'">Restartuj</button>
				<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?change=apache3\'">Wyłącz</button>
					  </div>
				  </td>
				</tr>						
				
				<tr>
				  <td><a href="">mysql</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=mysql1\'">Uruchom</button>
				<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?change=mysql2\'">Restartuj</button>
				<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?change=mysql3\'">Wyłącz</button>
					  </div>
				  </td>
				</tr>						
				
				<tr>
				  <td><a href="">cron</a></td>
				  <td>
					  <div class="btn-group btn-group-xs">
				<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?change=cron1\'">Uruchom</button>
				<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?change=cron2\'">Restartuj</button>
				<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?change=cron3\'">Wyłącz</button>
					  </div>
				  </td>
				</tr>
				
			</tbody>
			</table>
			
			
			<div class="alert alert-danger"><strong>Ustawienia</strong></div>
			<button type="button" class="btn btn-danger">Resetuj opłaty internetu</button>
			<button type="button" class="btn btn-danger">Blokuj możliwość rejestracji do sieci</button>
			<button type="button" class="btn btn-danger">Blokuj możliwość zakładania konta</button>
			<button type="button" class="btn btn-danger">Usuń starych użytkowników</button>
			
<h2 class="sub-header">Ostatni logi</h2>

<div class="panel panel-default">
<div class="panel-heading">Log główny panelu</div>
<div class="panel-body">
'.$file6.'
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">last -5</div>
<div class="panel-body">
'.$file1.'
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">tail -5 dmesg</div>
<div class="panel-body">
'.$file2.'
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">tail -5 /var/log/cron</div>
<div class="panel-body">
'.$file3.'
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">tail -5 /var/log/messages</div>
<div class="panel-body">
'.$file4.@$message.'
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">tail -10 /var/lib/dhcpd/dhcpd.leases</div>
<div class="panel-body">
'.$file5.'
</div>
</div>
<h2 class="sub-header">Logi</h2>
<a href="../php_create_config/load_dhcp_conf.txt">dhcp</a> |
<a href="../shscripts/firewall">iptables</a> |
<a href="../php_create_config/loadiptables.txt">iptables port</a> |
<a href="../php_create_config/load_tc.txt">TC</a> |
<a href="../shscripts/log.log">log</a>

';


			
			
?>