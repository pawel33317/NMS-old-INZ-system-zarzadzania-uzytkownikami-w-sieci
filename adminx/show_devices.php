<?php
				if(isset($_GET['where'])){	
					$lnwhere = '&where='.$_GET['where'];
				}else{
					$lnwhere = "";
				}
				
				if(isset($_GET['userid'])){	
					$lnuserid = '&userid='.$_GET['userid'];
					$sklWhere = ' where u.id='.$_GET['userid'];	
					$sql = $con->query('SELECT login, imie, nazwisko FROM users where id='.$_GET['userid']);//.$sqlOrder
					$sqll=$sql->fetch_assoc();
					$label = 'Urządzenia użytkownika: '.$sqll["login"].' ('.$sqll["imie"].' '.$sqll["nazwisko"].')';	
				}else{
					$lnuserid = "";
					$sklWhere = "";
					$label = "Urządzenia";
				}
				
echo '<h2 class="sub-header">'.$label.'</h2>
			  <div class="table-responsive">
				<table class="table table-striped">
				  <thead>
					<tr>';
				
				if(isset($_GET['order'])){
					if($_GET['otype'] == "ASC"){
						$otype = "DESC";
					}else{
						$otype = "ASC";
					}
					$sqlOrder = " ORDER BY ".$_GET['order']." ".$otype."";
				}else{
					$sqlOrder = " ORDER BY d.id DESC";
				}
				echo'
					  <th><a href="?order=d.id&otype='.@$otype.$lnwhere.$lnuserid.'">Id</a></th>
					  <th><a href="?order=u.id&otype='.@$otype.$lnwhere.$lnuserid.'">Właściciel</a></th>
					  <th><a href="?order=mac&otype='.@$otype.$lnwhere.$lnuserid.'">MAC</a></th>
					  <th><a href="?order=ip&otype='.@$otype.$lnwhere.$lnuserid.'">IP</a></th>
					  <th><a href="?order=devtype&otype='.@$otype.$lnwhere.$lnuserid.'">Typ</a></th>
					  <th><a href="?order=devname&otype='.@$otype.$lnwhere.$lnuserid.'">Nazwa</a></th>
					  <th><a href="?order=dateadd&otype='.@$otype.$lnwhere.$lnuserid.'">Data dodania</a></th>
					  <th>Operacje</th>
					</tr>
				  </thead>
				  <tbody>';
			    $sql = $con->query('SELECT d.id as did, u.id as uid, login, imie, nazwisko, mac, ip, devtype, devname, datarejestracji, d.stan as stann FROM devices AS d
					LEFT JOIN users as u on u.id = d.user_id'.$sklWhere.$sqlOrder);
			    while($sqlx=$sql->fetch_assoc()){
					echo '
						<tr>
						  <td><a href="?device=edit&id='.$sqlx['did'].'">'.$sqlx['did'].'</a></td>
						  <td><a href="?user=edit&id='.$sqlx['uid'].'"> '.$sqlx['uid'].'</a>: '.$sqlx['login'].' ('.$sqlx['imie'].' '.$sqlx['nazwisko'].')</td>
						  <td>'.$sqlx['mac'].'</td>
						  <td>'.$sqlx['ip'].'</td>
						  <td>'.$sqlx['devtype'].'</td>
						  <td>'.$sqlx['devname'].'</td>
						  <td>'.@date("d-m-Y", $sqlx['datarejestracji']).'</td>
						  <td>
							  <div class="btn-group btn-group-xs">';
					if($sqlx['stann'] != 1)
						echo '<button type="button" class="btn btn-success" onclick="window.location.href = \'index.php?where='.$_GET['where'].$lnuserid.'&useroperation=yes&option=aktywujdev&id='.$sqlx['did'].'\'">Aktywuj</button>';
					echo '<button type="submit" class="btn btn-primary" onclick="window.location.href = \'index.php?device=edit&id='.$sqlx['did'].'\'">Edytuj</button>';
					if($sqlx['stann'] != 2)
						echo '<button type="submit" class="btn btn-warning" onclick="window.location.href = \'index.php?where='.$_GET['where'].$lnuserid.'&useroperation=yes&option=blokujdev&id='.$sqlx['did'].'\'">Blokuj</button>';
					echo '<button type="submit" class="btn btn-danger" onclick="window.location.href = \'index.php?where='.$_GET['where'].$lnuserid.'&useroperation=yes&option=usundev&id='.$sqlx['did'].'\'">Usuń</button>
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