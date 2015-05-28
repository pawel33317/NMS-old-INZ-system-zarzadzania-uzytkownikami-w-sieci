<?php 
	include 'header.php';

	function getNewIp($con){
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
			wypisz("Przydzielone IP: ".$oktet1.'.'.$oktet2.'.'.$oktet3.'.'.$oktet4,1);
			return $oktet1.'.'.$oktet2.'.'.$oktet3.'.'.$oktet4;//return '10.0.0.111';
		}
		else{
			wypisz("Brak adresów IP zgłoś to do admina",4,1);
		}
	}
	
	include "../config.php";

    $sql = $con->query("SELECT id FROM devices WHERE mac='".getmac()."'");
    $sql2 = $sql->fetch_row();
    $sql2 = $sql2[0];	
//$sql2 = $db->sl('id', 'users', 'mac', getmac());

    $sql = $con->query("SELECT stan FROM devices WHERE mac='".getmac()."'");
    
//	$sql3 = $db->sl('stan', 'users', 'mac', getmac());

	if(!$sql) {
		echo $con->error;
		
	} else {
		$sql3 = $sql->fetch_row();
		$sql3 = $sql3[0];	
	}

	if(isset($_POST['przed']))
	{
		$tmp = strtotime("now") + 60*60*24*91;
		//zmiana warunku
/*		$zapytanie = $db->query('update users set datawaznoscikonta='.$tmp.' where id = "'.getmac().'"');
		if($zapytanie){
			wypisz("Przedłużono do maksymalnego możliwego czasu",1);
		}
		else{
			wypisz("Niestety się nie udało przedłużyć",2);
		}*/
		
		
//do zmiany		
		$sql = 'update users set datawaznoscikonta='.$tmp.' where id = "'.getmac().'"';
			if ($con->query($sql) === TRUE) {
					wypisz("Przedłużono do maksymalnego możliwego czasu",1);} else {
					wypisz("Niestety się nie udało przedłużyć",2);
			}
		
		
		
	}
	if($sql3 == 2)
	{
		wypisz("ZOSTAŁEŚ ZABLOKOWANY PRZEZ ADMINISTRATORA",4,1);
	}
	if($sql2)
	{
//do zmiany
		$sql2 = $con->sl('imie,nazwisko,oplata,datawaznoscikonta,ip', 'users', 'mac', getmac());
		wypisz("Witaj ".$sql2['imie']." ".$sql2['nazwisko']."",2);
		wypisz("Twoje konto jest ważne do: ".@date("h:i, d-m-Y", $sql2['datawaznoscikonta'])."",2);
		wypisz("IP przypisane do tego urządzenia: ".$sql2['ip']."",2);
		if($sql2['oplata'] == 1)wypisz("Internet został opłacony",1);
		if($sql2['oplata'] == 0)wypisz("Brak opłaty za internet",4);
		
		//wypisz('',2,1);
		echo '<form role="form" action="register.php" method="POST"><button type="submit" name="przed" class="btn btn-success">Przedłóż</button> ';
		echo '<button type="button" class="btn btn-warning">Zmień ustawienia konta</button> ';
		echo '<button type="button" class="btn btn-primary">Załóż darmowe konto internetowe</button></form> ';

		die();
	}
	
	wypisz("Rejestracja użytkownika w sieci",2);
	if (!isset($_POST['pokoj'])){
		echo '
			<div class="panel panel-primary">
				<div class="panel-heading">Wprowadź dane</div>
				<div class="panel-body">
					<form role="form" action="register.php" method="POST">
					
					
					<div style="width:49%; float:left;">
					  <div class="form-group">
						<label>Imię</label>
						<input type="text" name="imie" class="form-control" placeholder="Jan">
					  </div>
					  <div class="form-group">
						<label>Nazwisko</label>
						<input type="text" name="nazwisko" class="form-control" placeholder="Kowalski">
					  </div>
					  <div class="form-group">
						<label>Pokój</label>
						<input type="text" name="pokoj" class="form-control" placeholder="112">
					  </div>
					  <div class="form-group">
						<label>Wydział</label>
						<input type="text" name="wydzial" class="form-control" placeholder="weeia">
					  </div>
					  </div>
					  
					 <div style="width:49%; float:right;"> 
					  <div class="form-group">
						<label>Kierunek</label>
						<input type="text" name="kierunek" class="form-control" placeholder="telekomunikacja">
					  </div>
					  <div class="form-group">
						<label>Typ urządzenia</label>
						<input type="text" name="typurzadzenia" class="form-control" placeholder="laptop, tablet, telefon ...">
					  </div>
					  <div class="form-group">
						<label>Nazwa urządzenia (jeśli nie znamy to firma i model)</label>
						<input type="text" name="nazwaurzadzenia" class="form-control" placeholder="admin-komputer">
					  </div>
					  </div>
					  <div style="clear:both;"></div>';
					  
					$x='  <hr><hr><hr>
					  <div style="width:50%; float:center;">
					   <label>
							<input id="acc" type="checkbox" name="ac" value="ac" onclick="shhd();">
							Czy chcesz dodatkowo darmowe konto internetowe <br><small>(dostaniesz dostęp do dysku serwera gdzie będziesz mógł umieszczać materiały i publikować je w sieci)</small>
						</label><br>
						
						<div id="addaccount" style="display:none;">
							  <div class="form-group">
								<label>Login</label>
								<input type="text" name="aclogin" class="form-control" placeholder="nick">
							  </div>
							  <div class="form-group">
								<label>Hasło</label>
								<input type="password" name="acpass" class="form-control" placeholder="******">
							  </div>							  
							  <div class="form-group">
								<label>Powtórz hasło</label>
								<input type="password" name="acpass2" class="form-control" placeholder="******">
							  </div>
							  </div>
					  </div>';
					  
					  echo '
					  <button type="submit" class="btn btn-default">Potwierdź</button>
					</form>
				</div>
			</div>
		';
		wypisz("",4,0,"Każde urządzenie musi być zarejestrowane osobno, nie wolno rejestrować 2 razy tego samego urządzenia, w razie problemów zgłosić się do administratora pokój 401.");
	}
	if (isset($_POST['pokoj'])){
		$rimie = htmlspecialchars($_POST['imie']);
		$rdatarejestracji = @strtotime("now");
		$rdatawaznoscikonta = $rdatarejestracji + 60*60*24*90;
		$rnazwisko = htmlspecialchars($_POST['nazwisko']);
		$rpokoj = htmlspecialchars($_POST['pokoj']);
		$rwydzial = htmlspecialchars($_POST['wydzial']);
		$rkierunek = htmlspecialchars($_POST['kierunek']);
		$rmac = getmac();
		$rtypurzadzenia = htmlspecialchars($_POST['typurzadzenia']);
		$rnazwaurzadzenia = htmlspecialchars($_POST['nazwaurzadzenia']);
		$rkonto = isset($_POST['ac'])?1:0;
		$rkontologin = isset($_POST['ac'])?htmlspecialchars($_POST['aclogin']):"";
		$rkontohaslo = isset($_POST['ac'])?htmlspecialchars($_POST['acpass']):"";
		$ropis = "";
		$rstan = 1; 
		//0 dozwolony po rejestracji
		//1 dozwolony po akceptacji
		//2 zabroniony dostep
		$poprawnosc = true;
		if($rkonto == 1) 
		{
			if($_POST['acpass']!=$_POST['acpass2'])
			{
				wypisz("Niezgodne hasła",4);
				$poprawnosc = false;
			}
			if(strlen($rkontologin) > 60 || strlen($rkontologin)<4)
			{
				wypisz("Zła długość loginu",4);
				$poprawnosc = false;
			}
			if(strlen($rkontohaslo) > 60 || strlen($rkontohaslo)<4)
			{
				wypisz("Zła długość hasła",4);
				$poprawnosc = false;
			}
		}
		if(strlen($rimie) > 60){
			wypisz("Zbyt długie imie",4);
			$poprawnosc = false;
		}
		if(strlen($rnazwisko) > 60){
			wypisz("Zbyt długie nazwisko",4);
			$poprawnosc = false;
		}
		if(!is_numeric($rpokoj)){
			if($rpokoj >721){
				wypisz("Nie ma takiego pokoju",4);
				$poprawnosc = false;
			}
			wypisz("Zły nr pokoju tylko cyfry",4);
			$poprawnosc = false;
		}
		if(strlen($rwydzial) > 330){
			wypisz("Zbyt długa nazwa wydziału",4);
			$poprawnosc = false;
		}
		if(strlen($rkierunek) > 330){
			wypisz("Zbyt długa nazwa kierunku",4);
			$poprawnosc = false;
		}
		if(strlen($rnazwaurzadzenia) > 330){
			wypisz("Zbyt długa nazwa urządzenia",4);
			$poprawnosc = false;
		}
		if(strlen($rtypurzadzenia) > 330){
			wypisz("Zbyt długa nazwa typu urządzenia",4);
			$poprawnosc = false;
		}
		if(strlen($rimie) < 3){
			wypisz("Zbyt krótkie imię lub nie podano go w ogóle",4);
			$poprawnosc = false;
		}
		if(strlen($rnazwisko) < 3){
			wypisz("Zbyt którkie nazwisko lub nie podano go w ogóle",4);
			$poprawnosc = false;
		}
		
		if($poprawnosc == true){
			if(getmac() != false){
				wypisz("Dane pobrane poprawnie (MAC)",1);
			}

			/*$zapytanie = $db->query('insert into users (datarejestracji, imie, nazwisko, pokoj, wydzial, kierunek, mac, typurzadzenia, nazwaurzadzenia, stan, konto, login, haslo, oplata, datawaznoscikonta,ip ) 
							values('.$rdatarejestracji.',"'.$rimie.'","'.$rnazwisko.'",'.$rpokoj.',"'.$rwydzial.'","'.$rkierunek.'","'.$rmac.'","'.$rtypurzadzenia.'",
							"'.$rnazwaurzadzenia.'", "0","'.$rkonto.'","'.$rkontologin.'","'.$rkontohaslo.'",0,'.$rdatawaznoscikonta.',"'.getNewIp($con).'")');
			if($zapytanie){
				wypisz("Rejestracja ukończona pomyślnine. W razie problemów nie rejestruj się ponownie tylko zgłoś do administratora",1);
			}
			else{
				wypisz("Nastąpił problem podczas rejestracji prosimy o kontakt z administratorem",4,1);
			}
			*/
			
			$sql = 'insert into users (datarejestracji, imie, nazwisko, pokoj, wydzial, kierunek, mac, typurzadzenia, nazwaurzadzenia, stan, konto, login, haslo, oplata, datawaznoscikonta,ip ) 
							values('.$rdatarejestracji.',"'.$rimie.'","'.$rnazwisko.'",'.$rpokoj.',"'.$rwydzial.'","'.$rkierunek.'","'.$rmac.'","'.$rtypurzadzenia.'",
							"'.$rnazwaurzadzenia.'", "0","'.$rkonto.'","'.$rkontologin.'","'.$rkontohaslo.'",0,'.$rdatawaznoscikonta.',"'.getNewIp($con).'")';
			if ($con->query($sql) === TRUE) {
					wypisz("Rejestracja ukończona pomyślnine. W razie problemów nie rejestruj się ponownie tylko zgłoś do administratora",1);
					} else {
					wypisz("Nastąpił problem podczas rejestracji prosimy o kontakt z administratorem",4,1);
			}
			
			
			$resetserwera = `echo 1 > $fileToReload'newusr.s'`;
				wypisz("Serwer będzie zresetowany w ciągu 1 sec",1);

			
			wypisz("W ciągu kilku sekund internet powinien zacząć działać. Należy wyłączyć i włączyć kartę sieciową lub wyjąć kabel na 10 sekund i włożyć go ponownie, w przypadku połączenia wifi należy rozłączyć się z siecią i połączyć ponownie po upływie kilku sekund.",1);
			wypisz("Po wykonaniu powyższej czynności internet powinien działać, jeżeli tak nie jest zgłoś się do administratora p.501",1);
			wypisz("Ważne co jakiś czas neleży wchodzić na stronę http://szostka.internet bez .pl i przedłużać tam ważność swojego konta, można tam też zmienić dotychczasowe ustawienia",1);
			
			
		}
		else{
			echo '
				<div class="panel panel-primary">
					<div class="panel-heading">Wprowadź dane</div>
					<div class="panel-body">
						<form role="form" action="register.php" method="POST">
<div style="width:49%; float:left;">
						  <div class="form-group">
							<label>Imię</label>
							<input type="text" name="imie" class="form-control" placeholder="Jan" value="'.$rimie.'">
						  </div>
						  <div class="form-group">
							<label>Nazwisko</label>
							<input type="text" name="nazwisko" class="form-control" placeholder="Kowalski" value="'.$rnazwisko.'">
						  </div>
						  <div class="form-group">
							<label>Pokój</label>
							<input type="text" name="pokoj" class="form-control" placeholder="112" value="'.$rpokoj.'">
						  </div>
						  <div class="form-group">
							<label>Wydział</label>
							<input type="text" name="wydzial" class="form-control" placeholder="weeia" value="'.$rwydzial.'">
						  </div>
						   </div>
					  
					 <div style="width:49%; float:right;">
						  <div class="form-group">
							<label>Kierunek</label>
							<input type="text" name="kierunek" class="form-control" placeholder="telekomunikacja" value="'.$rkierunek.'">
						  </div>
						  <div class="form-group">
							<label>Typ urządzenia</label>
							<input type="text" name="typurzadzenia" class="form-control" placeholder="laptop, tablet, telefon ..." value="'.$rtypurzadzenia.'">
						  </div>
						  <div class="form-group">
							<label>Nazwa urządzenia (jeśli nie znamy to firma i model)</label>
							<input type="text" name="nazwaurzadzenia" class="form-control" placeholder="admin-komputer" value="'.$rtypurzadzenia.'">
						  </div>
						  </div>
					  <div style="clear:both;"></div>
					  <hr>
					  <div style="width:50%; float:center;">
					   <label>
							<input id="acc" type="checkbox" name="ac" value="ac" onclick="shhd();">
							Czy chcesz dodatkowo darmowe konto internetowe <br><small>(dostaniesz dostęp do dysku serwera gdzie będziesz mógł umieszczać materiały i publikować je w sieci)</small>
						</label><br>
						
						<div id="addaccount" style="display:none;">
							  <div class="form-group">
								<label>Login</label>
								<input type="text" name="aclogin" class="form-control" placeholder="nick">
							  </div>
							  <div class="form-group">
								<label>Hasło</label>
								<input type="password" name="acpass" class="form-control" placeholder="******">
							  </div>							  
							  <div class="form-group">
								<label>Powtórz hasło</label>
								<input type="password" name="acpass2" class="form-control" placeholder="******">
							  </div>
							  </div>
					  </div>
						  
						  
						  <button type="submit" class="btn btn-default">Potwierdź</button>
						</form>
					</div>
				</div>
			';
			wypisz("",4,0,"Każde urządzenie musi być zarejestrowane osobno, nie wolno rejestrować 2 razy tego samego urządzenia, w razie problemów zgłosić się do administratora pokój 401.");
		}
		mysql_close();
	}
	include 'footer.php';
?>
