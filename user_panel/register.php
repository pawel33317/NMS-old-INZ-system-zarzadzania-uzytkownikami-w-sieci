<?php 
	include "../config.php";
	
	$signed_correctly=false;
	if(isset($_COOKIE['user_id'])){
		$result = $con->query("SELECT haslo FROM users WHERE id='".$_COOKIE['user_id']."'");
		if ($result->num_rows == 0)
			$signed_correctly=false;
		$row = $result->fetch_assoc();
		if ($row['haslo'] == $_COOKIE['user_pass']){
			$signed_correctly=true;
		}
	}	

	$comp_unregistered=false;
	$MY_MAC = getmac();
	$result = $con->query("SELECT id FROM devices WHERE mac='".$MY_MAC."'");
	if ($result->num_rows == 0){
		$comp_unregistered=true;
	}else{
			Header ("Location: index.php");
	}
	

	
	
	include 'header.php';
	wypisz("Rejestracja urządzenia w sieci",2);
	
	if($signed_correctly==false){
		wypisz("Nie jesteś zalogowany. Możesz to zrobić lub utworzyć nowe konto tutaj: <a href=\"index.php\">LINK</a>",3,1);
	}else{
		$result = $con->query("SELECT imie, nazwisko, login FROM users WHERE id='".$_COOKIE['user_id']."'");
		$row = $result->fetch_assoc();
		wypisz("Zalogowany poprawnie jako: ".$row['imie']." ".$row['nazwisko']." - ".$row['login']."",1,0);
		wypisz("Przejdź do panelu zarządzania Twoimi urządzeniami. <a href=\"index.php\">LINK</a>",1,0);
	}
	
	
	if (isset($_POST['devname'])){
		$rdevtype = htmlspecialchars($_POST['devtype']);
		$rdevname = htmlspecialchars($_POST['devname']);
		$rstan = 0; //0 dozwolony po rejestracji//1 dozwolony po akceptacji//2 zabroniony dostep
		$rdatarejestracji = @strtotime("now");
		$ruser_id = $_COOKIE['user_id'];
		$rmac = $MY_MAC;
		$rip = getNewIp($con);

		$poprawnosc = true;
		if(strlen($rdevtype) > 60 || strlen($rdevtype)<2){
			wypisz("Zła długość nazwy urządzenia.",4);
			$poprawnosc = false;
		}
		if(strlen($rdevname) > 60 || strlen($rdevname)<2){
			wypisz("Zła długość typu urządzenia.",4);
			$poprawnosc = false;
		}
		
		if($poprawnosc == true){
			wypisz("Dane pobrane poprawnie.",1);
			$sql = 'INSERT INTO `siec2`.`devices` (`user_id`, `dateadd`, `mac`, `ip`, `devtype`, `devname`, `stan`) 
					VALUES ("'.$ruser_id.'", "'.$rdatarejestracji.'", "'.$rmac.'", "'.$rip.'", "'.$rdevtype.'", "'.$rdevname.'",  "'.$rstan.'");';
			if ($con->query($sql) === TRUE) {
					wypisz("Rejestracja ukończona pomyślnine Urządzenie dodane do systemu.",1);
					} else {
					wypisz("Nastąpił problem podczas rejestracji prosimy o kontakt z administratorem",4,1);
			}
			$comp_unregistered=false;
		
			$resetserwera = `echo 1 > $fileToReload'newusr.s'`;
			wypisz("Serwer będzie zresetowany w ciągu 1 sec",1);

			wypisz("W ciągu kilku sekund internet powinien zacząć działać. Należy wyłączyć i włączyć kartę sieciową lub wyjąć kabel na 10 sekund i włożyć go ponownie, 
					w przypadku połączenia wifi należy rozłączyć się z siecią i połączyć ponownie po upływie kilku sekund.",1);
		}	
	}
	
	
	if($comp_unregistered==true){
		echo '
			<div class="panel panel-primary">
				<div class="panel-heading">Rejestracja tego urządzenia: wprowadź dane</div>
				<div class="panel-body">
					<form role="form" action="register.php" method="POST">
					<div style="width:49%; float:left;">
					  <div class="form-group">
						<label>Nazwa urządzenia</label>
						<input type="text" name="devname" class="form-control" placeholder="Samsung Galaxy"  value="'.@$_POST["devname"].'">
					  </div>  
					 </div>
					 <div style="width:49%; float:right;"> 
					  <div class="form-group">
						<label>Typ urządzenia</label>
						<input type="text" name="devtype" class="form-control" placeholder="Telefon" value="'.@$_POST["devtype"].'">
					  </div>
					 </div>
					 <div style="clear:both;"></div>
					 <button type="submit" class="btn btn-default">Zarejestruj</button>
					</form>
				</div>
			</div>
		';
	}
	
	wypisz("",4,0,"Każde urządzenie musi być zarejestrowane osobno, nie wolno rejestrować 2 razy tego samego urządzenia, w razie problemów zgłosić się do administratora pokój 401.");
	include 'footer.php';
?>