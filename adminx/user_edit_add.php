<?php
//edycja usera
if($_GET['user'] == 'edit' && isset($_POST['imie'])){
		$imie = htmlspecialchars($_POST['imie']);
		$pokoj = htmlspecialchars($_POST['pokoj']);
		$wydzial = htmlspecialchars($_POST['wydzial']);
		$kierunek = htmlspecialchars($_POST['kierunek']);
		$nazwisko = htmlspecialchars($_POST['nazwisko']);
		$login = htmlspecialchars($_POST['login']);
		$haslo = htmlspecialchars($_POST['haslo']);	
		$haslo2 = htmlspecialchars($_POST['haslo2']);	
		$datarejestracji = strtotime("now");
		$datawaznoscikonta = $datarejestracji + 60*60*24*90;
		$nazwisko = htmlspecialchars($_POST['nazwisko']);
		$stan = htmlspecialchars($_POST['stan']);	
		$oplata = htmlspecialchars($_POST['oplata']);	
		$portyonof = htmlspecialchars($_POST['portyonof']);	
		$porty = htmlspecialchars($_POST['porty']);	
		$downloadhttp = htmlspecialchars($_POST['downloadhttp']);	
		$downloadall = htmlspecialchars($_POST['downloadall']);	
		$upload = htmlspecialchars($_POST['upload']);	

	$zapytanie = $con->query("UPDATE `users` SET  `datarejestracji` =  '441',
		`imie` =  '".$imie."',
		`nazwisko` =  '".$nazwisko."',
		`pokoj` =  '".$pokoj."',
		`wydzial` =  '".$wydzial."',
		`kierunek` =  '".$kierunek."',
		`stan` =  '".$stan."',
		`login` =  '".$login."',
		`oplata` =  '".$oplata."',
		`datawaznoscikonta` =  '".$datawaznoscikonta."',
		`portyonof` =  '".$portyonof."',
		`porty` =  '".$porty."',
		`downloadhttp` =  '".$downloadhttp."',
		`downloadall` =  '".$downloadall."',
		`upload` =  '".$upload."' WHERE  `users`.`id` =".$_GET['id']);
	if($zapytanie){
		wypisz("Użytkownik został edytowany",1);
	}else{
		wypisz("Nie można edytować użytkownika",4);
	}
	if (empty($haslo) || strlen($haslo) < 3 || $haslo != $haslo2){
		wypisz("Hasło nie zostało zmienione",2);
	}else{
		$zapytanie = $con->query("UPDATE  `siec2`.`users` SET `haslo` =  '42' WHERE  `users`.`id` =".$_GET['id']);
		wypisz("Hasło zostało zmienione",2);
	}
}

//dodanie nowego usera
if($_GET['user'] == 'adduser' && isset($_POST['imie'])){
		$imie = htmlspecialchars($_POST['imie']);
		$pokoj = htmlspecialchars($_POST['pokoj']);
		$wydzial = htmlspecialchars($_POST['wydzial']);
		$kierunek = htmlspecialchars($_POST['kierunek']);
		$nazwisko = htmlspecialchars($_POST['nazwisko']);
		$login = htmlspecialchars($_POST['login']);
		$haslo = htmlspecialchars($_POST['haslo']);	
		$haslo2 = htmlspecialchars($_POST['haslo2']);	
		$datarejestracji = strtotime("now");
		$datawaznoscikonta = $datarejestracji + 60*60*24*90;
		$nazwisko = htmlspecialchars($_POST['nazwisko']);
		$stan = htmlspecialchars($_POST['stan']);	
		$oplata = htmlspecialchars($_POST['oplata']);	
		$portyonof = htmlspecialchars($_POST['portyonof']);	
		$porty = htmlspecialchars($_POST['porty']);	
		$downloadhttp = htmlspecialchars($_POST['downloadhttp']);	
		$downloadall = htmlspecialchars($_POST['downloadall']);	
		$upload = htmlspecialchars($_POST['upload']);	
		
		$zapytanie = $con->query("INSERT INTO users (	`datarejestracji`, `imie`, `nazwisko`, `pokoj`, `wydzial`, `kierunek`, `stan`, `login`, `haslo`, 
														`oplata`, `datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`) 
											VALUES ('".$datarejestracji."', '".$imie."', '".$nazwisko."', '".$pokoj."', '".$wydzial."', '".$kierunek."', '".$stan."', '".$login."', '".$haslo."', 
														'".$oplata."', '".$datawaznoscikonta."', '".$portyonof."', '".$porty."', '".$downloadhttp."', '".$downloadall."', '".$upload."')");		
		if ($haslo != $haslo2){
			wypisz("Hasła niezgodne - dodano pierwsze. Zmień to.",2);
		}
		if($zapytanie){
			wypisz("Użytkownik został dodany",1);
			$sql = $con->query('select id from users order by id desc limit 1');
			$sqlx=$sql->fetch_assoc();
			Header ("Location: index.php?user=edit&id=".$sqlx['id']);
		}else{
			wypisz("Nie można dodać użytkownika: ".$con->error,4);
		}		
}

if($_GET['user'] == 'edit'){
	$sql = $con->query('select * from users where id = '.$_GET['id']);
	$sqlx=$sql->fetch_assoc();
	$label = 'Edycja użytkownika';
}else{
	$sqlx=NULL;
	$label = 'Dodanie nowego użytkownika';
}
if(isset($_GET['user']))
echo '
	<div class="panel panel-primary">
				<div class="panel-heading">'.$label.'</div>
				<div class="panel-body">
					<form role="form" action="index.php?user='.$_GET['user'].'&id='.@$_GET['id'].'" method="POST">
					<div style="width:49%; float:left;">
					  <div class="form-group"><label>Login</label><input type="text" name="login" class="form-control" placeholder="nick"  value="'.$sqlx["login"].'"></div>
					  <div class="form-group"><label>Imię</label><input type="text" name="imie" class="form-control" placeholder="Jan"  value="'.$sqlx["imie"].'"></div>
					  <div class="form-group"><label>Pokój</label><input type="text" name="pokoj" class="form-control" placeholder="123" value="'.$sqlx["pokoj"].'"></div>
					  <div class="form-group"><label>Wydzial</label><input type="text" name="wydzial" class="form-control" placeholder="weeia"  value="'.$sqlx["wydzial"].'"></div>
					  <div class="form-group"><label>Stan</label><input type="text" name="stan" class="form-control" placeholder="0 nowy ,1 zaakceptowany, 2 blokowany"  value="'.$sqlx["stan"].'"></div>
					  <div class="form-group"><label>Opłata</label><input type="text" name="oplata" class="form-control" placeholder="0 brak, 1 jest"  value="'.$sqlx["oplata"].'"></div>
					  <div class="form-group"><label>Data ważności konta</label><input type="text" name="datawaznoscikonta" class="form-control" placeholder="UNIX time"  value="'.$sqlx["datawaznoscikonta"].'"></div>
					  <div class="form-group"><label>Porty otwarte / zamknięte</label><input type="text" name="portyonof" class="form-control" placeholder="0 zezwalaj tylko na wymienione ,1 blokuj wszystkie poza wymienionymi"  value="'.$sqlx["portyonof"].'"></div>
					 </div>
					 <div style="width:49%; float:right;"> 
					  <div class="form-group"><label>Hasło</label><input type="password" name="haslo" class="form-control" placeholder="*****"></div>
					  <div class="form-group"><label>Powtórz hasło</label><input type="password" name="haslo2" class="form-control" placeholder="*****"></div>
					  <div class="form-group"><label>Nazwisko</label><input type="text" name="nazwisko" class="form-control" placeholder="Kowalski" value="'.$sqlx["nazwisko"].'"></div>
					  <div class="form-group"><label>Kierunek</label><input type="text" name="kierunek" class="form-control" placeholder="Ekonomia" value="'.$sqlx["kierunek"].'"></div>
					  <div class="form-group"><label>Download http</label><input type="text" name="downloadhttp" class="form-control" placeholder="20480" value="'.$sqlx["downloadhttp"].'"></div>
					  <div class="form-group"><label>Download All</label><input type="text" name="downloadall" class="form-control" placeholder="10240" value="'.$sqlx["downloadall"].'"></div>
					  <div class="form-group"><label>Upload</label><input type="text" name="upload" class="form-control" placeholder="4096" value="'.$sqlx["upload"].'"></div>
					  <div class="form-group"><label>Porty blokowane / otwarte</label><input type="text" name="porty" class="form-control" placeholder="88;53;443;21;22;23" value="'.$sqlx["porty"].'"></div>
					 </div>
					 <div style="clear:both;"></div>
					 <button type="submit" class="btn btn-default">Zapisz</button>
					</form>
				</div>
			</div>
	';
	
	//  `stan`, `oplata`, 
	//`datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`)
	
?>