<?php
echo '<h2 class="sub-header">Użytkownicy</h2>
			  <div class="table-responsive">
				<table class="table table-striped">
				  <thead>
					<tr>';
				if(isset($_GET['where'])){	
					if($_GET['where'] == "devices" OR $_GET['where'] == "users")
						$sklWhere = "";
					elseif($_GET['where'] == "paid")
						$sklWhere = " where oplata = 1";
					elseif($_GET['where'] == "nopaid")
						$sklWhere = " where oplata = 0";
					elseif($_GET['where'] == "blocked")
						$sklWhere = " where stan = 2";
					elseif($_GET['where'] == "noaccepted")
						$sklWhere = " where stan = 0";
					elseif($_GET['where'] == "p1")
						$sklWhere = " where pokoj > 99 and pokoj < 200";
					elseif($_GET['where'] == "p2")
						$sklWhere = " where pokoj > 199 and pokoj < 300";
					elseif($_GET['where'] == "p3")
						$sklWhere = " where pokoj > 299 and pokoj < 400";
					elseif($_GET['where'] == "p4")
						$sklWhere = " where pokoj > 399 and pokoj < 500";
					elseif($_GET['where'] == "p5")
						$sklWhere = " where pokoj > 499 and pokoj < 600";
					elseif($_GET['where'] == "p6")
						$sklWhere = " where pokoj > 599 and pokoj < 700";
					elseif($_GET['where'] == "p7")
						$sklWhere = " where pokoj > 699 and pokoj < 800";
					$lnwhere = '&where='.$_GET['where'];
				}else{
					$sklWhere = "";
					$lnwhere = '';
				}
				if(isset($_GET['order'])){
					if($_GET['otype'] == "ASC"){
						$otype = "DESC";
					}else{
						$otype = "ASC";
					}
					$sqlOrder = " ORDER BY ".$_GET['order']." ".$otype."";
				}else{
					$sqlOrder = " ORDER BY id DESC";
				}
				echo'
					  <th><a href="?order=id&otype='.@$otype.$lnwhere.'">Id</a></th>
					  <th><a href="?order=login&otype='.@$otype.$lnwhere.'">Login</a></th>
					  <th><a href="?order=imie&otype='.@$otype.$lnwhere.'">Imię</a></th>
					  <th><a href="?order=nazwisko&otype='.@$otype.$lnwhere.'">Nazwisko</a></th>
					  <th><a href="?order=pokoj&otype='.@$otype.$lnwhere.'">Pokój</a></th>
					  <th><a href="?order=ilosc&otype='.@$otype.$lnwhere.'">Ilość urządzeń</a></th>
					  <th><a href="?order=datarejestracji&otype='.@$otype.$lnwhere.'">Data</a></th>
					  <th><a href="?order=stan&otype='.@$otype.$lnwhere.'">Stan</a></th>
					  <th><a href="?order=oplata&otype='.@$otype.$lnwhere.'">Opłata</a></th>
					  <th>Operacje</th>
					</tr>
				  </thead>
				  <tbody>';
				//id login 	imie	nazwisko	pokoj	Ilość urządzeń	datarejestracji 	stan	oplata	Operacje
	//INSERT INTO `siec2`.`users` (`id`, `datarejestracji`, `imie`, `nazwisko`, `pokoj`, `wydzial`, `kierunek`, `stan`, `login`, `haslo`, `oplata`, 
	//`datawaznoscikonta`, `portyonof`, `porty`, `downloadhttp`, `downloadall`, `upload`)
			
			    $sql = $con->query('SELECT * FROM users AS u
					LEFT JOIN (
						SELECT COUNT( user_id ) AS ilosc, user_id FROM devices
						GROUP BY user_id
					) AS tmp ON tmp.user_id = u.id'.$sklWhere.$sqlOrder);
			    while($sqlx=$sql->fetch_assoc()){
					$stan = "";
					if($sqlx['stan'] == 2)
						$stan = "zablokowany";
					if($sqlx['stan'] == 0)
						$stan = "nieaktywowany";
					if($sqlx['stan'] == 1)
						$stan = "aktywowany";	
					$oplata = $sqlx['oplata']==1?"Tak":"Nie";
					
					echo '
						<tr>
						  <td><a href="?user=edit&id='.$sqlx['id'].'">'.$sqlx['id'].'</a></td>
						  <td>'.$sqlx['login'].'</td>
						  <td>'.$sqlx['imie'].'</td>
						  <td>'.$sqlx['nazwisko'].'</td>
						  <td>'.$sqlx['pokoj'].'</td>
						  <td><a href="?where=devices&userid='.$sqlx['id'].'">'.$sqlx['ilosc'].'</a></td>
						  <td>'.@date("d-m-Y", $sqlx['datarejestracji']).'</td>
						  <td>'.$stan.'</td>
						  <td>'.$oplata.'</td>
						  <td>
							  <div class="btn-group btn-group-xs">';
					if($sqlx['stan'] != 1)
						echo '<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?where='.$_GET['where'].'&useroperation=yes&option=aktywuj&id='.$sqlx['id'].'\'">Aktywuj</button>';
					echo '<button type="submit" class="btn btn-primary" onclick="window.location.href = \'index.php?user=edit&id='.$sqlx['id'].'\'">Edytuj</button>';
					if($sqlx['oplata'] != 1)
						echo '<button type="submit" class="btn btn-success" onclick="window.location.href = \'index.php?where='.$_GET['where'].'&useroperation=yes&option=oplacil&id='.$sqlx['id'].'\'">Opłacił</button>';
					if($sqlx['oplata'] == 1)
						echo '<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?where='.$_GET['where'].'&useroperation=yes&option=nieoplacil&id='.$sqlx['id'].'\'">Nie opłacił</button>';
					if($sqlx['stan'] != 2)
						echo '<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?where='.$_GET['where'].'&useroperation=yes&option=blokuj&id='.$sqlx['id'].'\'">Blokuj</button>';
					echo '<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?where='.$_GET['where'].'&useroperation=yes&option=usun&id='.$sqlx['id'].'\'">Usuń</button>
							  </div>
						  </td>
						</tr>
					';
				}
				echo '</tbody>
					</table>
				  </div>
				</div>';
?>