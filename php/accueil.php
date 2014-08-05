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
	if ($_GET['action']=='recherche'){
	$data2= $_GET['recherche'];
	$data3= $_GET['choix'];
	$data4= $_GET['choix2'];
	$st = $db->prepare("select animal.id_p, client.nom, client.prenom, client.id2, client.adresse, client.code, client.ville, client.variable, animal.id, animal.nom_a, animal.espece, animal.sexe, animal.race, animal.variable2, animal.mort, animal.datenais FROM animal inner JOIN client ON client.id2=animal.id_p where (client.permission2='".$_SESSION['login']."' and animal.permission='".$_SESSION['login']."' and $data3 LIKE '$data4$data2%') order by client.nom asc");
			
			$st->execute($arr);
			
			// Returns an array of Category objects:
			 
			if($data3=="client.nom"){
			echo json_encode($st->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP));
			}elseif($data3=="animal.nom_a"){
			echo json_encode($st->fetchAll());		
			}
	}elseif($_GET['action']=='suppression'){
	requetemysql::supr_salle_attente($_POST['id'],$_POST['nom_a'],$_POST['nom_p']);
	$salle_attente = requetemysql::salle_attente("general");
					if(empty($salle_attente)){
					throw new Exception("Aucun animal dans la salle d'attente !");
					}
	echo $salle_attente;	
	}elseif($_GET['action']=='suppression2'){
	requetemysql::supr_rapport_refere($_POST['id']);
	$rapport_refere = requetemysql::rapport_refere("general");
					if(empty($rapport_refere)){
					throw new Exception("Vous n'avez pas recu de cas référé !");
					}
	echo $rapport_refere;	
	}elseif($_GET['action']=='suppression3'){
	requetemysql::supr_mur($_POST['id_mur']);
	$liste_mur = requetemysql::liste_mur();
					if(empty($liste_mur)){
					throw new Exception("Vous n'avez pas recu de cas référé !");
					}
	echo $liste_mur;	
	}elseif($_GET['action']=='changement'){
	
	$st = $db->prepare("DELETE FROM `aerogard2`.`tourdegarde` where date=FROM_UNIXTIME('".$_POST['ma_date']."'/1000,'%Y/%m/%d') and tour='".$_SESSION['tour']."' and nature='".$_POST['valeur']."'");
	$st->execute();	
	$st->closeCursor();
	$sql = "INSERT INTO `aerogard2`.`tourdegarde` (`id`, `login`, `from`, `date`, `nature`, `tour`) VALUES ('', :login, :from, FROM_UNIXTIME(:date/1000,'%Y/%m/%d'), :nature, :tour )";
	$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':login' => $_POST['veto'], ':from' => $_SESSION['login2'], ':date' => $_POST['ma_date'], ':nature' => $_POST['valeur'], ':tour' => $_SESSION['tour']));
	$st->closeCursor();
	echo json_encode("ok");
	
	}elseif($_GET['action']=='deplacement'){
		$date_debut =  mktime(0, 0, 0, date("m",$_POST['ma_date']/1000)  , 1, date("Y",$_POST['ma_date']/1000));
		$date_fin =  mktime(0, 0, 0, date("m",$_POST['ma_date']/1000)+1  , 1, date("Y",$_POST['ma_date']/1000));
		$planning = requetemysql::planning(array('tour'=>$_SESSION['tour'], 'date_debut'=>$date_debut, 'date_fin'=>$date_fin, 'nature'=>1));		
		$planning2 = requetemysql::planning(array('tour'=>$_SESSION['tour'], 'date_debut'=>$date_debut, 'date_fin'=>$date_fin, 'nature'=>2));
		$super_planning = array ($planning,$planning2);
		echo json_encode($super_planning);
		
	}elseif($_GET['action']=='recherche_consult'){
	$recherche_consult = requetemysql::recherche_consult($_POST['consult']);
	
	echo $recherche_consult;	
	}elseif ($_GET['action']=='recup_historique'){
	
	$liste_garde = requetemysql::liste_garde(array('debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin']));
	
	echo json_encode($liste_garde);
	
}
}
?>