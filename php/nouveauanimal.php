<?php
/* il faut demarrer la session*/
session_start();
if (empty($_SESSION['id'])){

	header('HTTP/1.1 400 Bad Request');
	exit("votre session a expiré. Reconnectez-vous !!");

}elseif(!empty($_SESSION['id'])){
	require_once "config.php";
	require_once "connexionmysql.php";
	$data3= $_GET['choix'];
	
	  if($_POST['datenais']!=""){
	    $date = DateTime::createFromFormat('d/m/Y', $_POST['datenais']);
	    $datenais = ($date->getTimestamp())*1000;
	    }else{
	    $date = new DateTime();
	    $datenais = ($date->getTimestamp())*1000;
	    }
		if($_POST['datemort']!=""){
		$mort = 2;
	    $date = DateTime::createFromFormat('d/m/Y', $_POST['datemort']);
	    $datemort = ($date->getTimestamp())*1000;
	    }else{
	    $mort = 1;
	    $datemort = "";
	    }  
	    
	    
	if ($data3=="nouveauanimal"){
	$client_id = $_GET['client'];
	$espece = is_null($_POST['species']) ? '' : $_POST['species'];
	$sexe = is_null($_POST['sexe']) ? '' : $_POST['sexe'];
	
	try {
	$st = $db->prepare("INSERT INTO `animal` ( nom_a, espece, race, sexe, datenais, num_p, num_t, num_pa, mort, datemort, variable2, repro, permission, id_p ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?);");
	$st->bindParam(1, $_POST['aniname']);
	$st->bindParam(2, $espece);
	$st->bindParam(3, $_POST['race']);
	$st->bindParam(4, $sexe);
	$st->bindParam(5, $datenais);
	$st->bindParam(6, $_POST['puce']);
	$st->bindParam(7, $_POST['tatou']);
	$st->bindParam(8, $_POST['passeport']);
	$st->bindParam(9, $mort);
	$st->bindParam(10, $datemort);
	$st->bindParam(11, $_POST['variable2']);
	$st->bindParam(12, $_POST['repro']);
	$st->bindParam(13, $_SESSION['login']);
	$st->bindParam(14, $client_id);
	$st->execute();
	$client_id = $db->lastInsertId();
	$filename = '../sauvegarde/animaux/'.$client_id;
	
	if (file_exists($filename)) {
	    
	} else {
		if (!mkdir($filename, 0755, true)) {
	  	  die('Echec lors de la création des répertoires...');
		}
	}
	echo $client_id;		
	} catch (PDOException $e) {
	        die($e->getMessage());
	 }
			
	}elseif($data3=="modifanimal"){
	$client_id = $_GET['client'];
	$ani_id = $_GET['ani']; 
	$espece = is_null($_POST['species']) ? '' : $_POST['species'];
	$sexe = is_null($_POST['sexe']) ? '' : $_POST['sexe'];
	$filename = '../sauvegarde/animaux/'.$_GET['ani'].'/';
	
	if (file_exists($filename)) {
	    
	} else {
		if (!mkdir($filename, 0755, true)) {
	  	  die('Echec lors de la création des répertoires...');
		}
	}
	
	try {
	$st = $db->prepare("UPDATE animal SET nom_a=?, espece=?, race=?, sexe=?, datenais=?, num_p=?, num_t=?, num_pa=?, mort=?, datemort=?, variable2=?, repro=?  WHERE id='".$_GET['ani']."' and permission='".$_SESSION['login']."'");
	$st->bindParam(1, $_POST['aniname']);
	$st->bindParam(2, $espece);
	$st->bindParam(3, $_POST['race']);
	$st->bindParam(4, $sexe);
	$st->bindParam(5, $datenais);
	$st->bindParam(6, $_POST['puce']);
	$st->bindParam(7, $_POST['tatou']);
	$st->bindParam(8, $_POST['passeport']);
	$st->bindParam(9, $mort);
	$st->bindParam(10, $datemort);
	$st->bindParam(11, $_POST['variable2']);
	$st->bindParam(12, $_POST['repro']);
	$st->execute();
	echo $_GET['ani'];
	} catch (PDOException $e) {
	        die($e->getMessage());
	 }
	
	}
}
?>