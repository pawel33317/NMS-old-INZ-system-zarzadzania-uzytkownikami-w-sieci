 <!DOCTYPE html>
<html>
  <head>
	<meta charset="utf-8">

    <title>Instalacja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../strona/css/bootstrap.min.css" rel="stylesheet">
	<link href="../strona/css/bootstrap-theme.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class="container theme-showcase site_content">
		<div style="height:20px;"></div>
 
 <?php 	
	include "../config.php";

	wypisz("Instalacja systemu rejestracji i zarządzania urzytkownikami w sieci",2);
	
	$polaczenie = mysql_connect($config['host'], $config['user'], $config['pass']);
	if (!$polaczenie) {
		wypisz('Nie można połączyć: ',4,1,mysql_error());
	}
	else {
		wypisz("Połączono z usługą poprawnie MySQL",1);
	}
	
	
	$db_selected = @mysql_select_db($config['dbname'], $polaczenie);
	if (!$db_selected) {
		wypisz("Nie ma bazy danych ".$config['dbname']."",3);
		
		$sql = mysql_query('create database '.$config['dbname'].'');
		if(!$sql){
			wypisz('Nie można ustawić ani utworzyć bazy danych siec: ',4,1,mysql_error());
		}
		else{
			wypisz("Utworzono baze danych sieć",1);
			
			$db_selected = @mysql_select_db($config['dbname']);
			if (!$db_selected) {
				wypisz('Pomimo utworzenia bazy siec nie można z nia polaczyc: ',4,1,mysql_error());
			}
			else{
				wypisz("Połączono z bazą siec",1);
			}
		}
	}
	else{
		wypisz("Połączono z bazą",1);
	}
	
	
	
	$zapytanie = mysql_query('create table users (
		id int unsigned not null auto_increment primary key,
		datarejestracji int,
		imie varchar(64),
		nazwisko varchar(64),
		pokoj int,
		wydzial varchar(333),
		kierunek varchar(333),
		stan int,
		login varchar(64) UNIQUE,
		haslo varchar(256),
		oplata int,
		datawaznoscikonta int,
		portyonof int,
		porty text,
		downloadhttp int,
		downloadall int,
		upload int
		)');	
	if($zapytanie){
		wypisz("Uwtorzono tabelę users",1);
	}else{
		wypisz("Błąd podczas tworzenia tabeli users",4);
	}
	
//INSERT INTO `siec2`.`users` (`id`, `datarejestracji`, `imie`, `nazwisko`, `pokoj`, `wydzial`, `kierunek`, `stan`, `login`, `haslo`, `oplata`, `datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`) VALUES ('1', '32456', 'pawel', 'czubak', '123', 'weeia', 'informatyka', '1', 'pawel33317', 'haslo01k', '1', '12345', '1', '80;51;77;33', '99999', '999999', '999999');
	$zapytanie = mysql_query('create table devices (
		id int unsigned not null auto_increment primary key,
		user_id int,
		dateadd int,
		mac varchar(17),
		ip varchar(20),
		devtype varchar(333),
		devname varchar(333),
		opis text,
		stan int
		)');	
	if($zapytanie){
		wypisz("Uwtorzono tabelę devices ",1);
	}else{
		wypisz("Błąd podczas tworzenia tabeli devices ". mysql_error(),4);
	}
	
	
	
	
	
	$zapytanie = mysql_query('create table settings (
		id int unsigned not null auto_increment primary key,
		name  varchar(128),
		intval int,
		descr text,
		textval text
		)');
	if($zapytanie){
		wypisz("Uwtorzono tabelę settings",1);
	}else{
		wypisz("Błąd podczas tworzenia tabeli settings",4);
	}
	
	
	$zapytanie = mysql_query('create table admin (
		id int unsigned not null auto_increment primary key,
		datarejestracji int,
		login varchar(64) UNIQUE,
		haslo varchar(128),
		ranga int
		)');
	if($zapytanie){
		wypisz("Uwtorzono tabelę admin",1);
	}else{
		wypisz("Błąd podczas tworzenia tabeli admin",4);
	}

	$zapytanie = mysql_query('insert into admin (login, haslo, ranga) values(
		"admin", 
		"'.md5('haslo01k').'",
		1
		)');	
	if($zapytanie){
		wypisz("Utworzono administratora: admin hasło: haslo01k",1);
	}else{
		wypisz("Nie można utworzyć konta administratora",4);
	}
	
	
	
	/*$zapytanie = mysql_query('insert into users (login, haslo, email, datareg, datalast, ranga) values(
		"pawel33317", 
		"'.md5('haslo01k').'",
		"pawel33317@gmail.com", 
		"'.strtotime("now").'",
		"'.strtotime("now").'",
		"2"
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';*/

	mysql_close();
?>

	<script src="https://code.jquery.com/jquery.js"></script>
    <script src="../strona/js/bootstrap.min.js"></script>
  </body>
</html>
