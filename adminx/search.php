<?php

echo '<h2 class="sub-header">Wyszukiwane:  <i>"'.$_GET['search'].'"</i></h2>
			  <div class="table-responsive">
				<table class="table table-striped">
				  <thead>
					<tr>';

				$lnwhere = "&search=".$_GET['search'];
				
				if(isset($_GET['order'])){
					if($_GET['otype'] == "ASC"){
						$otype = "DESC";
					}else{
						$otype = "ASC";
					}
					$sqlOrder = " ORDER BY ".$_GET['order']." ".$otype."";
				}else{
					$sqlOrder = " ORDER BY id DESC";
					$otype = "ASC";
				}

				echo'
					<th><a href="?order=id&otype='.@$otype.$lnwhere.'">Id</a></th>
					  <th><a href="?order=login&otype='.@$otype.$lnwhere.'">Login</a></th>
					  <th><a href="?order=imie&otype='.@$otype.$lnwhere.'">Imię</a></th>
					  <th><a href="?order=nazwisko&otype='.@$otype.$lnwhere.'">Nazwisko</a></th>
					  <th><a href="?order=pokoj&otype='.@$otype.$lnwhere.'">Pokój</a></th>
					  <th><a href="#">Ilość urządzeń</a></th>
					  <th><a href="?order=datarejestracji&otype='.@$otype.$lnwhere.'">Data</a></th>
					  <th><a href="?order=stan&otype='.@$otype.$lnwhere.'">Stan</a></th>
					  <th><a href="?order=oplata&otype='.@$otype.$lnwhere.'">Opłata</a></th>
					  <th>Operacje</th>
					</tr>
				  </thead>
				  <tbody>';
				
				$tresc = "";
				
			$arr = array("u.id", "d.id", "imie", "nazwisko", "wydzial", "pokoj", "kierunek",  "login", "mac", "ip", "devtype", "devname", "opis");
			foreach ($arr as &$value) {
			    $sql = $con->query('SELECT u.id as id, d.id as did, login, imie, nazwisko, pokoj, datarejestracji, u.stan as stan, oplata   FROM users AS u LEFT JOIN devices as d ON d.user_id = u.id WHERE '.$value.' LIKE "%'.$_GET['search'].'%"'.$sqlOrder);
			    while($sqlx=$sql->fetch_assoc())
				{
				$czyjuzjest = strpos($tresc, "id=".$sqlx['id']);
				if($czyjuzjest == false){
					$stan = "";
					if($sqlx['stan'] == 2)
						$stan = "zablokowany";
					if($sqlx['stan'] == 0)
						$stan = "nieaktywowany";
					if($sqlx['stan'] == 1)
						$stan = "aktywowany";
						
					$oplata = $sqlx['oplata']==1?"Tak":"Nie";
					
					$sql = $con->query('SELECT count(user_id) as ilosc FROM devices WHERE user_id = '.$sqlx['id'] );
					$sqlc=$sql->fetch_assoc();
					
					$tresc .= '
						<tr>
						  <td><a href="?user=edit&id='.$sqlx['id'].'">'.$sqlx['id'].'</a></td>
						  <td>'.$sqlx['login'].'</td>
						  <td>'.$sqlx['imie'].'</td>
						  <td>'.$sqlx['nazwisko'].'</td>
						  <td>'.$sqlx['pokoj'].'</td>
						  <td><a href="?where=devices&userid='.$sqlx['id'].'">'.$sqlc['ilosc'].'</a></td>
						  <td>'.@date("d-m-Y", $sqlx['datarejestracji']).'</td>
						  <td>'.$stan.'</td>
						  <td>'.$oplata.'</td>
						  <td>
							  <div class="btn-group btn-group-xs">';
					if($sqlx['stan'] != 1)
						$tresc .= '<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?where='.@$_GET['where'].'&useroperation=yes&option=aktywuj&id='.$sqlx['id'].'\'">Aktywuj</button>';
					$tresc .= '<button type="submit" class="btn btn-primary" onclick="window.location.href = \'index.php?user=edit&id='.$sqlx['id'].'\'">Edytuj</button>';
					if($sqlx['oplata'] != 1)
						$tresc .= '<button type="submit" class="btn btn-success" onclick="window.location.href = \'index.php?where='.@$_GET['where'].'&useroperation=yes&option=oplacil&id='.$sqlx['id'].'\'">Opłacił</button>';
					if($sqlx['oplata'] == 1)
						$tresc .= '<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?where='.@$_GET['where'].'&useroperation=yes&option=nieoplacil&id='.$sqlx['id'].'\'">Nie opłacił</button>';
					if($sqlx['stan'] != 2)
						$tresc .= '<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?where='.@$_GET['where'].'&useroperation=yes&option=blokuj&id='.$sqlx['id'].'\'">Blokuj</button>';
					$tresc .= '<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?where='.@$_GET['where'].'&useroperation=yes&option=usun&id='.$sqlx['id'].'\'">Usuń</button>
							  </div>
						  </td>
						</tr>
					';
				}
				}
			}
				unset($value);
				echo $tresc;
				echo '</tbody>
					</table>
				  </div>
				</div>';
?>