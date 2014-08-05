<?php
/* il faut demarrer la session*/
session_start();
if (empty($_SESSION['id'])){

	header('HTTP/1.1 400 Bad Request');
	exit("votre session a expiré. Reconnectez-vous !!");

}elseif(!empty($_SESSION['id'])){
	require_once "config.php";
	require_once "connexionmysql.php";
	$data = "labadie";
	$data3= $_GET['choix'];
	
	if($data3=="code"){
	$data2= $_GET['recherche'];
	$st = $db->prepare("select CODEPAYS, VILLE, CP FROM cp_autocomplete where CP = '$data2' LIMIT 5");
			
			$st->execute($arr);
			
			
			echo json_encode($st->fetchAll());		
			
	}elseif ($data3=="nouveauclient"){
	$mes_donnee = Array();
	foreach ( $_POST as $key => $value )
	{
	 array_push($mes_donnee,$value);
	 if ( preg_match('/relancemail/', $key) )
	    {
	 array_push($mes_donnee,$_SESSION['login']);
	    }
	}
	try {
	$st = $db->prepare("INSERT INTO `client` ( ref, nom, prenom, adresse, code, ville, tel1, tel2, mail, envoimail, permission2, variable, variable3, variable4 ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?);");
	$st->execute($mes_donnee);
	$client_id = $db->lastInsertId();
	echo json_encode(array("statut" => "nouveauclient", "id_pro" => $client_id));
	//echo $client_id;		
	} catch (PDOException $e) {
	        die($e->getMessage());
	 }
			
	}elseif ($data3=="modifclient"){
	
	$mes_donnee = Array();
	foreach ( $_POST as $key => $value )
	{
	 array_push($mes_donnee,$value);
	
	}
	try {
	$st = $db->prepare("UPDATE client SET ref=?, nom=?, prenom=?, adresse=?, code=?, ville=?, tel1=?, tel2=?, mail=?, envoimail=?, variable=?, variable3=?, variable4=?  WHERE id2='".$_GET['client']."' and permission2='".$_SESSION['login']."'");
	$st->execute($mes_donnee);
	//echo $_GET['client'];
	echo json_encode(array("statut" => "modifclient", "id_pro" => $_GET['client']));
	} catch (PDOException $e) {
	        die($e->getMessage());
	 }
	
	}
}