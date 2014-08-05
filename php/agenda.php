<?php
/* il faut demarrer la session*/
session_start();
if (empty($_SESSION['id'])){

	header('HTTP/1.1 400 Bad Request');
	exit("votre session a expiré. Reconnectez-vous !!");

}elseif(!empty($_SESSION['id'])){
	require_once "config.php";
	require_once "connexionmysql.php";
	require_once "requetemysql.php";
	if ($_GET['action']=='save_rdv'){
			if($_POST['id_rdv']==0){
	
				$sql = "INSERT INTO `rendezvous`(`id`, `date_debut`, `date_fin`, `titre`, `permission`) VALUES ('' , FROM_UNIXTIME(:date_debut/1000  ), FROM_UNIXTIME(:date_fin/1000), :titre, :permission)";
				$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$sth->execute(array(':permission' => $_SESSION['login'], ':date_debut' => $_POST['debut'], ':date_fin' => $_POST['fin'],
									':titre' => $_POST['rdv']));
				$agenda_id = $db->lastInsertId();
				$sth->closeCursor();
				echo json_encode($agenda_id);
			}else{		
			$st = $db->prepare("UPDATE `rendezvous` SET `titre`=:titre WHERE `id`=:id_rdv AND `permission`=:permission");
			$st->execute(array(':permission' => $_SESSION['login'], ':id_rdv' => $_POST['id_rdv'], ':titre' => $_POST['rdv']));	
			echo json_encode($_POST['id_rdv']);		
			}
				
				
	}elseif($_GET['action']=='recuprdv'){
	
	
				$liste_rdv = array ();
				$liste_rdv_perso = requetemysql::liste_rdv(array('permission' => $_SESSION['login'], 'debut' => $_POST['debut'], 'fin' => $_POST['fin']));
				$liste_rdv[$_SESSION['login']] = $liste_rdv_perso;
				if($_SESSION['login']==$_SESSION['login2']){
						$liste_rdv_garde = requetemysql::liste_rdv(array('permission' => $_SESSION['tour'], 'debut' => $_POST['debut'], 'fin' => $_POST['fin']));
						$liste_rdv[$_SESSION['tour']] = $liste_rdv_garde;			
				}else{			
						$liste_rdv_garde = requetemysql::liste_rdv(array('permission' => $_SESSION['login2'], 'debut' => $_POST['debut'], 'fin' => $_POST['fin']));
						$liste_rdv[$_SESSION['login2']] = $liste_rdv_garde;
				}
				$liste_assos = requetemysql::recup_groupe(array('groupe' => $_SESSION['groupe']));
				 foreach($liste_assos as $key => $row)
				  			  {
				  			$liste_rdv_groupe = requetemysql::liste_rdv(array('permission' => $row['login'], 'debut' => $_POST['debut'], 'fin' => $_POST['fin']));  
				  			$liste_rdv[$row['login']] = $liste_rdv_groupe;
				  			  
				  			  }
				
								
				echo json_encode($liste_rdv);	
	}elseif($_GET['action']=='supr_rdv'){
				
			$st = $db->prepare("DELETE FROM `aerogard2`.`rendezvous` where id=:mon_id and permission=:permission LIMIT 1");
			$st->execute(array(':permission' => $_SESSION['login'], ':mon_id' => $_POST['mon_id']));	
			echo json_encode("ok");
	}elseif($_GET['action']=='miseajour_rdv'){
				
			$st = $db->prepare("UPDATE `rendezvous` SET `date_debut`=FROM_UNIXTIME(:date_debut/1000  ),`date_fin`=FROM_UNIXTIME(:date_fin/1000  ) WHERE `id`=:id_rdv AND `permission`=:permission");
			$st->execute(array(':permission' => $_SESSION['login'], ':id_rdv' => $_POST['id_rdv'], ':date_debut' => $_POST['debut'], ':date_fin' => $_POST['fin']));	
			echo json_encode("ok");	
	}
}
