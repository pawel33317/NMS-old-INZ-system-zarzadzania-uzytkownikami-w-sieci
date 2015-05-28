<?php
//edycja usera
if($_GET['device'] == 'edit' && isset($_POST['devname'])){
		$devname = htmlspecialchars($_POST['devname']);
		$user_id = htmlspecialchars($_POST['user_id']);
		$mac = htmlspecialchars($_POST['mac']);
		$opis = htmlspecialchars($_POST['opis']);
		$devtype = htmlspecialchars($_POST['devtype']);
		$stan = htmlspecialchars($_POST['stan']);
		$ip = htmlspecialchars($_POST['ip']);	

	$zapytanie = $con->query("UPDATE `devices` SET 
		`user_id` =  '".$user_id."',
		`mac` =  '".$mac."',
		`ip` =  '".$ip."',
		`devtype` =  '".$devtype."',
		`devname` =  '".$devname."',
		`opis` =  '".$opis."',
		`stan` =  '".$stan."' WHERE  `devices`.`id` =".$_GET['id']);
	if($zapytanie){
		wypisz("Urządzenie zostało edytowane",1);
	}else{
		wypisz("Nie można edytować urządzenia: ".$con->error,4);
	}
}

//dodanie nowego urządzenia
if($_GET['device'] == 'adddevice' && isset($_POST['devname'])){
		$devname = htmlspecialchars($_POST['devname']);
		$user_id = htmlspecialchars($_POST['user_id']);
		$mac = htmlspecialchars($_POST['mac']);
		$opis = htmlspecialchars($_POST['opis']);
		$devtype = htmlspecialchars($_POST['devtype']);
		$stan = htmlspecialchars($_POST['stan']);
		$ip = htmlspecialchars($_POST['ip']);	

		$zapytanie = $con->query("INSERT INTO `devices` (`user_id`, `mac`, `ip`, `devtype`, `devname`, `opis`, `stan`) 
												VALUES ('".$user_id."', '".$mac."', '".$ip."', '".$devtype."', '".$devname."', '".$opis."', '".$stan."')");		
		if($zapytanie){
			wypisz("Urządzenie zostało dodane",1);
			$sql = $con->query('select id from devices order by id desc limit 1');
			$sqlx=$sql->fetch_assoc();
			Header ("Location: index.php?device=edit&id=".$sqlx['id']);
		}else{
			wypisz("Nie można dodać urządzenia: ".$con->error,4);
		}		
}

if($_GET['device'] == 'edit'){
	$sql = $con->query('select * from devices where id = '.$_GET['id']);
	$sqlx=$sql->fetch_assoc();
	$label = 'Edycja urządzenia';
	$genip=NULL;
}else{
	$sqlx=NULL;
	$label = 'Dodanie nowego urządzenia';
	$genip='
	<script>
		function ipajax(){
		var xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function(){
		  if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("ipgen").value=xmlhttp.responseText;}}
		xmlhttp.open("GET","genip.php",true);xmlhttp.send();}
	</script>
	<div class="form-group"><label>Generuj IP</label> <button type="button" class="form-control btn-success" onclick="ipajax()">Generuj</button></div>
	';
}
if(isset($_GET['device']))
	echo '
			<div class="panel panel-primary">
				<div class="panel-heading">'.$label.'</div>
				<div class="panel-body">
					<form role="form" action="index.php?device='.$_GET['device'].'&id='.@$_GET['id'].'" method="POST">
					<div style="width:49%; float:left;">
					  <div class="form-group"><label>Nazwa urządzenia</label><input type="text" name="devname" class="form-control" placeholder="Samsung Galaxy"  value="'.@$sqlx["devname"].'"></div>  
					  <div class="form-group"><label>Id właściciela</label><input type="text" name="user_id" class="form-control" placeholder="11"  value="'.@$sqlx["user_id"].'"></div>  
					  <div class="form-group"><label>MAC</label><input type="text" name="mac" class="form-control" placeholder="11:22:33:44:55:66"  value="'.@$sqlx["mac"].'"></div>  
					  <div class="form-group"><label>Opis</label><input type="text" name="opis" class="form-control" placeholder="Opis urządzenia"  value="'.@$sqlx["opis"].'"></div>  
					 </div>
					 <div style="width:49%; float:right;"> 
					  <div class="form-group"><label>Typ urządzenia</label><input type="text" name="devtype" class="form-control" placeholder="Telefon" value="'.@$sqlx["devtype"].'"></div>
					  <div class="form-group"><label>Stan</label><input type="text" name="stan" class="form-control" placeholder="0 nowy ,1 zaakceptowany, 2 blokowany" value="'.@$sqlx["stan"].'"></div>
					  <div class="form-group"><label>IP</label><input type="text" name="ip" id="ipgen" class="form-control" placeholder="10.0.4.11" value="'.@$sqlx["ip"].'"></div>
						'.$genip.'
					 </div>
					
					 <div style="clear:both;"></div>
					 <button type="submit" class="btn btn-default">Zapisz</button>
					</form>
				</div>
			</div>
		';

	
?>