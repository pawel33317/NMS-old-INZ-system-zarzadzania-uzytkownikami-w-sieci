<?php 
	include "../config.php";
	$panel_logowania=false;
	$panel_rejestracji=false;
	$signed_correctly=false;
	$show_login_panel=false;
	$show_register_panel=false;
	$user_banned=false;
	$bad_browser_data=false;
	$comp_unregistered=false;
	$new_account=false;
	$old_account=false;
	$bad_login_data=false;
	
	if(@$_GET['sign'] == "true"){
		$panel_logowania=true;
		$show_register_panel=true;
	}
	if(@$_GET['register'] == "true"){
		$panel_rejestracji=true;
		$show_login_panel=true;
	}
	$MY_MAC=getmac();
    $sql = $con->query("SELECT stan FROM devices WHERE mac='".$MY_MAC."'");
	$sql3 = $sql->fetch_row();
	$sql3 = $sql3[0];	
	if($sql3 == 2){
		$user_banned=true;
	}

	if(isset($_COOKIE['user_id'])){
		$result = $con->query("SELECT haslo FROM users WHERE id='".$_COOKIE['user_id']."'");
		if ($result->num_rows == 0)
			$bad_browser_data=true;
		$row = $result->fetch_assoc();
		if ($row['haslo'] == $_COOKIE['user_pass']){
			$signed_correctly=true;
			setcookie("user_id", $_COOKIE['user_id'], time()+360000);
			setcookie("user_pass", $_COOKIE['user_pass'], time()+360000);
		}
	}
	elseif(isset($_POST['user_name'])){
		$result = $con->query("SELECT haslo FROM users WHERE login='".$_POST['user_name']."'");
		if ($result->num_rows == 0){
			$bad_login_data=true;
			$new_account=true;
			$panel_logowania=true;
		}
		$row = $result->fetch_assoc();
		if ($row['haslo'] == md5($_POST['user_pass'])){
			$signed_correctly=true;
			$panel_logowania=false;
			$panel_rejestracji=false;
			$result = $con->query('select u.id as user_id, u.haslo as user_pass from users as u where login = "'.$_POST['user_name'].'"');
			if ($result->num_rows == 0){
				echo "error";die();
			}else{
				$row = $result->fetch_assoc();
				setcookie("user_id", $row['user_id'], time()+360000);
				setcookie("user_pass", $row['user_pass'], time()+360000);
				Header ("Location: index.php");
			}
		}
	}
	else{
		$result = $con->query("SELECT id FROM devices WHERE mac='".$MY_MAC."'");
		if ($result->num_rows == 0){
			$comp_unregistered=true;
			if(!isset($_GET['sign']))
				$new_account=true;
			if(!isset($_GET['register']))
				$old_account=true;
		}else{
			//$comp_registered=true;//wypisz("",1,0,"Zaloguj na konto właściciela laptopa. <a href=\"index.php?owner=true\">LINK</a>");//wypisz("",1,0,"Mam już konto zaloguj. <a href=\"index.php?sign=true\">LINK</a>");//wypisz("",2,0,"Nie mam konta utwórz nowe. <a href=\"index.php?register=true\">LINK</a>");
			$result = $con->query('select u.id as user_id, u.haslo as user_pass from users as u join devices as d on u.id = d.user_id where d.mac = "'.$MY_MAC.'"');
			if ($result->num_rows == 0){
				echo "error";die();
			}else{
				$row = $result->fetch_assoc();
				setcookie("user_id", $row['user_id'], time()+360000);
				setcookie("user_pass", $row['user_pass'], time()+360000);
				Header ("Location: index.php");
			}
		}
	} 


	include 'header.php';
	wypisz("Rejestracja użytkownika w sieci",2);

	if(isset($_POST['lengthen'])){
		$tmp = strtotime("now") + 60*60*24*91;	
		$sql = 'update users set datawaznoscikonta='.$tmp.' where id = "'.$_COOKIE['user_id'].'"';
			if ($con->query($sql) === TRUE) {
				wypisz("Przedłużono do maksymalnego możliwego czasu",1);} else {
				wypisz("Niestety się nie udało przedłużyć",2);
			}
	}

	
	if($user_banned==true)
		wypisz("ZOSTAŁEŚ ZABLOKOWANY PRZEZ ADMINISTRATORA",4,1);
	if($bad_browser_data==true)
		wypisz("Złe dane przeglądarki. Wyczyść dane przeglądarki bądź skontaktuj się z administratorem",3,1);
	if ($signed_correctly==true){
		$result = $con->query("SELECT imie, nazwisko, login FROM users WHERE id='".$_COOKIE['user_id']."'");
		$row = $result->fetch_assoc();
		wypisz("Zalogowany poprawnie jako: ".$row['imie']." ".$row['nazwisko']." - ".$row['login']."",1,0);
		
		$result = $con->query("SELECT id FROM devices WHERE mac='".$MY_MAC."'");
		if ($result->num_rows == 0)
			wypisz("Przejdź do panelu dodania urządzenia. <a href=\"register.php\">LINK</a>",1,0);
		
		echo '
			<div class="panel panel-primary">
				<div class="panel-heading">Lista moich urządzeń</div>
				<div class="panel-body">	
					<table class="table table-hover"><thead>
					 <tr>
					  <th>Nazwa urządzenia</th>
					  <th>Typ urządzenia</th>
					  <th>Adres IP</th>
					  <th>Operacje</th>
					 </tr>
					 </thead><tbody>';
			$result = $con->query("SELECT id, ip, devtype, devname FROM devices WHERE user_id='".$_COOKIE['user_id']."'");
			if ($result->num_rows == 0){
				echo '<tr><td>Brak</td><td></td><td></td><td></td></tr>';
			}else{
				while ($row = $result->fetch_assoc()) {
					echo '<tr><td>'.$row["devname"].'</td><td>'.$row["devtype"].'</td><td>'.$row["ip"].'</td><td><a href="index.php?delete='.$row["id"].'">Usuń</a></td></tr>';
				}
			}
		echo '	     </tbody>
					</table></div></div>';
		
		$result = $con->query("SELECT datawaznoscikonta FROM users WHERE id='".$_COOKIE['user_id']."'");
		$row = $result->fetch_assoc();
		wypisz("Twoje konto jest ważne do: ".@date("h:i, d-m-Y", $row['datawaznoscikonta'])."",2);
		echo '<form role="form" action="index.php" method="POST"><button type="submit" name="lengthen" class="btn btn-success">Przedłóż</button> ';
		echo '<button type="button" class="btn btn-warning">Zmień ustawienia konta</button> ';
		echo '<button type="button" class="btn btn-danger">Usuń konto</button></form> <br>';

	}
	if($comp_unregistered==true)
		wypisz("",2,0,"Komputer niezarejestrowany.");
	if($bad_login_data==true)
			wypisz("",4,0,"Złe dane. Zaloguj się ponownie.");


	if (isset($_POST['login'])){
		$rimie = htmlspecialchars($_POST['imie']);
		$rpokoj = htmlspecialchars($_POST['pokoj']);
		$rwydzial = htmlspecialchars($_POST['wydzial']);
		$rkierunek = htmlspecialchars($_POST['kierunek']);
		$rnazwisko = htmlspecialchars($_POST['nazwisko']);
		$rlogin = htmlspecialchars($_POST['login']);
		$rhaslo = htmlspecialchars($_POST['haslo']);	
		$rhaslo2 = htmlspecialchars($_POST['haslo2']);	

		$rdatarejestracji = @strtotime("now");
		$rdatawaznoscikonta = $rdatarejestracji + 60*60*24*90;
		$rstan = 0; //0 dozwolony po rejestracji//1 dozwolony po akceptacji//2 zabroniony dostep
		$roplata = 0;
		$rportyonof = 0;
		$rporty = 0;
		$rdownloadhttp = 0;
		$rdownloadall = 0;
		$rupload = 0;

		$poprawnosc = true;
		if($_POST['haslo']!=$_POST['haslo2'])
		{
			wypisz("Niezgodne hasła",4);
			$poprawnosc = false;
		}
		$result = $con->query('select id from users where login = "'.$rlogin.'"');
		if ($result->num_rows > 0){
			wypisz("Podany login już istnieje wybierz inny",4);
			$poprawnosc = false;
		}
		if(strlen($rlogin) > 60 || strlen($rlogin)<4)
		{
			wypisz("Zła długość loginu",4);
			$poprawnosc = false;
		}
		if(strlen($rhaslo) > 60 || strlen($rhaslo)<4)
		{
			wypisz("Zła długość hasła",4);
			$poprawnosc = false;
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
		if(strlen($rimie) < 3){
			wypisz("Zbyt krótkie imię lub nie podano go w ogóle",4);
			$poprawnosc = false;
		}
		if(strlen($rnazwisko) < 3){
			wypisz("Zbyt którkie nazwisko lub nie podano go w ogóle",4);
			$poprawnosc = false;
		}
		if($poprawnosc == true){
			$panel_rejestracji=false;
			$new_account=false;
			wypisz("Dane pobrane poprawnie.",1);

			$sql = 'INSERT INTO `siec2`.`users` (`datarejestracji`, `imie`, `nazwisko`, `pokoj`, `wydzial`, `kierunek`, `stan`, `login`, 
				`haslo`, `oplata`, `datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`) VALUES 
				("'.$rdatarejestracji.'", "'.$rimie.'", "'.$rnazwisko.'", "'.$rpokoj.'", "'.$rwydzial.'", "'.$rkierunek.'", "'.$rstan.'", "'.$rlogin.'", 
				"'.md5($rhaslo).'", "'.$roplata.'", "'.$rdatawaznoscikonta.'", "'.$rportyonof.'", "'.$rporty.'", "'.$rdownloadhttp.'", "'.$rdownloadall.'", "'.$rupload.'")';

			if ($con->query($sql) === TRUE) {
					wypisz("Rejestracja ukończona pomyślnine. W razie problemów nie rejestruj się ponownie tylko zgłoś do administratora",1);
					} else {
					wypisz("Nastąpił problem podczas rejestracji prosimy o kontakt z administratorem",4,1);
			}
			
			$result = $con->query('select id from users where login = "'.$rlogin.'"');
			$row = $result->fetch_assoc();
			setcookie("user_id", $row['id'], time()+360000);
			setcookie("user_pass", md5($rhaslo), time()+360000);
//wysyła ciastka i zostawia info 
			wypisz("Konto zostało utworzone możesz teraz dodać to urządzenie. <a href=\"index.php\">Przejdź do panelu</a>",1);	
		}	
	}
	
	if($new_account==true)
		wypisz("Mam już konto zaloguj",1,0," (rejestrowałem się na innym urządzeniu). <a href=\"index.php?sign=true\">LINK</a>");
	if($old_account==true)
			wypisz("",2,0,"Nie mam konta utwórz nowe. <a href=\"index.php?register=true\">LINK</a>");	
	//panel logowania 
	if($panel_logowania)
		echo '
			<div class="panel panel-primary">
				<div class="panel-heading">Logowanie: wprowadź dane</div>
				<div class="panel-body">
					<form role="form" action="index.php" method="POST">
					<div style="width:49%; float:left;">
					  <div class="form-group">
						<label>Login</label>
						<input type="text" name="user_name" class="form-control" placeholder="nick">
					  </div>  
					  
					 </div>
					 <div style="width:49%; float:right;"> 
					  <div class="form-group">
						<label>Hasło</label>
						<input type="password" name="user_pass" class="form-control" placeholder="*****">
					  </div>
					 </div>
					 <div style="clear:both;"></div>
					 <button type="submit" class="btn btn-default">Zaloguj</button>
					</form>
				</div>
			</div>
		';
	
	if($panel_rejestracji)
		echo '
			<div class="panel panel-primary">
				<div class="panel-heading">Rejestracja: wprowadź dane</div>
				<div class="panel-body">
					<form role="form" action="index.php?register=true" method="POST">
					<div style="width:49%; float:left;">
					  <div class="form-group">
						<label>Login</label>
						<input type="text" name="login" class="form-control" placeholder="nick"  value="'.@$_POST["login"].'">
					  </div>
					  <div class="form-group">
						<label>Imię</label>
						<input type="text" name="imie" class="form-control" placeholder="Jan"  value="'.@$_POST["imie"].'">
					  </div>
					  <div class="form-group">
						<label>Pokój</label>
						<input type="text" name="pokoj" class="form-control" placeholder="123" value="'.@$_POST["pokoj"].'">
					  </div>
					  <div class="form-group">
						<label>Wydzial</label>
						<input type="text" name="wydzial" class="form-control" placeholder="weeia"  value="'.@$_POST["wydzial"].'">
					  </div>
 				  
					  
					 </div>
					 <div style="width:49%; float:right;"> 
					  <div class="form-group">
						<label>Hasło</label>
						<input type="password" name="haslo" class="form-control" placeholder="*****">
					  </div>
					  <div class="form-group">
						<label>Powtórz hasło</label>
						<input type="password" name="haslo2" class="form-control" placeholder="*****">
					  </div>
					  <div class="form-group">
						<label>Nazwisko</label>
						<input type="text" name="nazwisko" class="form-control" placeholder="Kowalski" value="'.@$_POST["nazwisko"].'">
					  </div>
					  <div class="form-group">
						<label>Kierunek</label>
						<input type="text" name="kierunek" class="form-control" placeholder="Ekonomia" value="'.@$_POST["kierunek"].'">
					  </div>
					 </div>
					 <div style="clear:both;"></div>
					 <button type="submit" class="btn btn-default">Zarejestruj</button>
					</form>
				</div>
			</div>
		';
	//INSERT INTO `siec2`.`users` (`id`, `datarejestracji`, `imie`, `nazwisko`, `pokoj`, `wydzial`, `kierunek`, `stan`, `login`, `haslo`, `oplata`, 
	//`datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`)
	
	//INSERT INTO `siec2`.`devices` (`id`, `user_id`, `dateadd`, `mac`, `ip`, `devtype`, `devname`, `opis`, `stan`) 
	//VALUES ('22', '2', '2324', '11:22:33:44:55:66', '192.16.6.6.', 'laptop', 'laptopunio', 'opis', '1');
	
	wypisz("",4,0,"Każde urządzenie musi być zarejestrowane osobno, nie wolno rejestrować 2 razy tego samego urządzenia, w razie problemów zgłosić się do administratora pokój 401.");
	include 'footer.php';
?>