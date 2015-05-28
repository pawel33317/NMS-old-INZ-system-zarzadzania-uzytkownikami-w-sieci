<?php 
	/////////////////////////////////
	/////CREATED BY PAWEL CZUBAK/////
	/////////////HAKS.PL/////////////
	//////PAWEL33317@GMAIL.COM///////
	/////////////////////////////////
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	
	include '../config.php';	
	
	//$resetserwera = `echo 1 > $fileToReload'newusr.s'`;
	$stan = `echo "1" > $fileToReload'stany.s'`;
	$stan = `echo "1" > $fileToReload'logi.s'`;
	if(@$_GET['change'] == "firewall")
		$stan = `echo 1 > $fileToReload'firewall.s'`;	
	if(@$_GET['change'] == "tc")
		$stan = `echo 1 > $fileToReload'tc.s'`;
	if(@$_GET['change'] == "dhcpreload")
		$stan = `echo 1 > $fileToReload'dhcpreload.s'`;
	if(@$_GET['change'] == "dhcp1")
		$stan = `echo 1 > $fileToReload'dhcp.s'`;
	if(@$_GET['change'] == "dhcp2")
		$stan = `echo 2 > $fileToReload'dhcp.s'`;
	if(@$_GET['change'] == "dhcp3")
		$stan = `echo 3 > $fileToReload'dhcp.s'`;
	if(@$_GET['change'] == "apache1")
		$stan = `echo 1 > $fileToReload'apache.s'`;
	if(@$_GET['change'] == "apache2")
		$stan = `echo 2 > $fileToReload'apache.s'`;
	if(@$_GET['change'] == "apache3")
		$stan = `echo 3 > $fileToReload'apache.s'`;
	if(@$_GET['change'] == "mysql1")
		$stan = `echo 1 > $fileToReload'mysql.s'`;
	if(@$_GET['change'] == "mysql2")
		$stan = `echo 2 > $fileToReload'mysql.s'`;
	if(@$_GET['change'] == "mysql3")
		$stan = `echo 3 > $fileToReload'mysql.s'`;
	if(@$_GET['change'] == "cron1")
		$stan = `echo 1 > $fileToReload'cron.s'`;
	if(@$_GET['change'] == "cron2")
		$stan = `echo 2 > $fileToReload'cron.s'`;
	if(@$_GET['change'] == "cron3")
		$stan = `echo 3 > $fileToReload'cron.s'`;
		//sleep(1);
		
	if(isset($_GET['change'])){
		header('Location: index.php');
	}	

	if( md5(@$_POST['password']) == $config['adminpass'] || @$_COOKIE['password'] == $config['adminpass']){
		if(isset($_POST['password']))
			setcookie('password',md5($_POST['password']),time()+3600);
	}else{
		include "login.php";
		die();
	}
	if(@$_GET['operation'] == 'lout' ){
		setcookie('password',md5($_POST['password']),time()-3600);
		header('Location: index.php');
	}
	if(@$_GET['useroperation'] == "yes"){
		include 'user_options_change.php';
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<script src="../jquery.js"></script>
    <title>Panel zarządzania użytkownikami</title>
    <link href="../strona/css/bootstrap.min.css" rel="stylesheet">
	<link href="../strona/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="dashboard.css" rel="stylesheet">
	<script>
    function submitForm(action, op){
        document.getElementById(op).action = action;
        document.getElementById(op).submit();
    }
	</script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Panel zarządzania urządzeniami i użytkownikami</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php">Ustawienia</a></li>
            <li><a href="?operation=lout">Wyloguj</a></li>
          </ul>
          <form class="navbar-form navbar-right" action="index.php" method="GET">
            <input type="text" class="form-control" name="search" placeholder="Szukaj...">
          </form>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li<?php if(count($_GET) < 1)echo ' class="active"';?>><a href="index.php">Statystyki i ustawienia</a></li>
          </ul>
          <ul class="nav nav-sidebar">
			<li<?php if(@$_GET['where'] == "users")echo ' class="active"';?>><a href="?where=users">Użytkownicy</a></li>
			<li<?php if(@$_GET['where'] == "devices")echo ' class="active"';?>><a href="?where=devices">Urządzenia</a></li>
			<li<?php if(@$_GET['where'] == "paid")echo ' class="active"';?>><a href="?where=paid">Opłaceni</a></li>
			<li<?php if(@$_GET['where'] == "nopaid")echo ' class="active"';?>><a href="?where=nopaid">Nieopłaceni</a></li>
		    <li<?php if(@$_GET['where'] == "blocked")echo ' class="active"';?>><a href="?where=blocked">Zablokowani</a></li>
			<li<?php if(@$_GET['where'] == "noaccepted")echo ' class="active"';?>><a href="?where=noaccepted">Niezaakceptowani</a></li>
            <li<?php if(@$_GET['user'] == "adduser")echo ' class="active"';?>><a href="index.php?user=adduser">Dodaj nowego użytkownika</a></li>
            <li<?php if(@$_GET['device'] == "adddevice")echo ' class="active"';?>><a href="index.php?device=adddevice">Dodaj nowe urządzenie</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li<?php if(@$_GET['where'] == "p1")echo ' class="active"';?>><a href="?where=p1">Piętro - 1</a></li>
            <li<?php if(@$_GET['where'] == "p2")echo ' class="active"';?>><a href="?where=p2">Piętro - 2</a></li>
            <li<?php if(@$_GET['where'] == "p3")echo ' class="active"';?>><a href="?where=p3">Piętro - 3</a></li>
            <li<?php if(@$_GET['where'] == "p4")echo ' class="active"';?>><a href="?where=p4">Piętro - 4</a></li>
            <li<?php if(@$_GET['where'] == "p5")echo ' class="active"';?>><a href="?where=p5">Piętro - 5</a></li>
            <li<?php if(@$_GET['where'] == "p6")echo ' class="active"';?>><a href="?where=p6">Piętro - 6</a></li>
            <li<?php if(@$_GET['where'] == "p7")echo ' class="active"';?>><a href="?where=p7">Piętro - 7</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<?php
			if(@$_GET['where'] == "devices"){
				include 'show_devices.php';
			}
			elseif(isset($_GET['where'])){
				include 'show_users.php';
			}
			elseif(count($_GET) < 1)
			{
				include 'stats.php';
			}
			if(isset($_GET['user']))
			{	
				include 'user_edit_add.php';
			}
			if(isset($_GET['device']))
			{	
				include 'device_edit_add.php';
			}
			elseif(isset($_GET['search']))
			{
				include 'search.php';
			}
		?>

      </div>
    </div>
  </body>
</html>


