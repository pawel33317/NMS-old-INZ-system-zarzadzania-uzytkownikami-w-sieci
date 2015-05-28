<?php 
    $config['host'] = "127.0.0.1";
    $config['user'] = "root";
    $config['pass'] = "haslo01k";
    $config['dbname'] = "siec2";
    $config['adminpass'] = md5("haslo01k");

	
	$log_DIR='/var/www/log_to_php_panel/';
	$service_state_DIR='/var/www/service_state/';
	$shScripts='/var/www/shscripts/';
	$fileToReload='/var/www/file_to_check_to_reload/';
	/////////////////////////////////
	/////CREATED BY PAWEL CZUBAK/////
	/////////////HAKS.PL/////////////
	//////PAWEL33317@GMAIL.COM///////
	/////////////////////////////////
	
	function wypisz($tekst, $typ, $zabij=0, $tekstinfo=""){
		//1 = success
		//2 = info
		//3 = warning
		//4 = danger
		if ($typ == 1)
			$typ = "alert-success";
		if ($typ == 2)
			$typ = "alert-info";
		if ($typ == 3)
			$typ = "alert-warning";
		if ($typ == 4)
			$typ = "alert-danger";
			
		if($tekstinfo != ""){
			echo '<div class="alert '.$typ.'"><strong>'.$tekst.'</strong>'.$tekstinfo.'</div>';
		}
		else{
			echo '<div class="alert '.$typ.'"><strong>'.$tekst.'</strong></div>';
		}
		
		if ($zabij == 1)
			die();
	}
	
	function getmac(){
		$ip=$_SERVER['REMOTE_ADDR'];
		$mac=false;
		$arp=`sudo arp -a -n $ip`;
		$lines=explode("\n", $arp);
		$tmp=explode('at ',$lines[0]);
		$tmp=explode(' [',$tmp[1]);
		if(!$tmp){
			wypisz("Nie można załadować wymaganych danych, zgłoś się do administratora: brak MAC",4,1);
		}
		return $tmp[0];//return 'aa:aa:aa:aa:aa:ab';
	}
	
	function getNewIp($con,$echoo=false){
		$oktet1 = 10;
		$oktet2 = 0;
		$oktet3 = 0;
		$oktet4 = 1;
		$i = 1; $j=false; $k=0;
		while($k<4 && $j==false){
			$i=2;
			$oktet3 = $k;
			while($i < 255 && $j==false){
				$oktet4 = $i;
				$result = $con->query('select id from devices where ip = "'.$oktet1.'.'.$oktet2.'.'.$oktet3.'.'.$oktet4.'"');
				if ($result->num_rows == 0) {
					$j = true;
				}
				$i++;
			}
			$k++;
		}
		if($j == true){
			if (!$echoo)
				wypisz("Przydzielone IP: ".$oktet1.'.'.$oktet2.'.'.$oktet3.'.'.$oktet4,1);
			return $oktet1.'.'.$oktet2.'.'.$oktet3.'.'.$oktet4;//return '10.0.0.111';
		}
		else{
			if (!$echoo)
				wypisz("Brak adresów IP zgłoś to do admina",4,1);
		}
	}
	
	
	$con=mysqli_connect($config['host'], $config['user'], $config['pass'], $config['dbname']);
	if (mysqli_connect_errno()) {echo "Failed to connect to MySQL: " . mysqli_connect_error();}

?>
