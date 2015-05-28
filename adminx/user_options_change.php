<?php
		if($_GET['option'] == "aktywuj"){
			$zapytanie = $con->query('update users set stan = "1" where id = '.$_GET['id']);
		}
		if($_GET['option'] == "oplacil"){
			$zapytanie = $con->query('update users set oplata = "1" where id = '.$_GET['id']);
		}
		if($_GET['option'] == "nieoplacil"){
			$zapytanie = $con->query('update users set oplata = "0" where id = '.$_GET['id']);
		}
		if($_GET['option'] == "blokuj"){
			$zapytanie = $con->query('update users set stan = "2" where id = '.$_GET['id']);	
		}
		if($_GET['option'] == "usun"){
			$zapytanie = $con->query('delete from users where id = '.$_GET['id']);
			$zapytanie = $con->query('delete from devices where user_id = '.$_GET['id']);
		}
		
		if($_GET['option'] == "aktywujdev"){
			$zapytanie = $con->query('update devices set stan = "1" where id = '.$_GET['id']);
		}
		if($_GET['option'] == "blokujdev"){
			$zapytanie = $con->query('update devices set stan = "2" where id = '.$_GET['id']);	
		}
		if($_GET['option'] == "usundev"){
			$zapytanie = $con->query('delete from devices where id = '.$_GET['id']);
		}
?>