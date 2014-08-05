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
	$data3= $_GET['action']; 
	    
	if ($data3=="verif_login"){
	
	$login = $_POST['login'];
	$pass = md5($_POST['pass']);
	$e_mail = $_POST['e_mail'];
			try {
				$req = $db->prepare('SELECT id FROM identification WHERE login = :login OR mail=:e_mail');
		   		$req->execute(array(':login'=> $login, ':e_mail' => $e_mail));
		   		$resultat=$req->fetch(); 
	            /*si il n'y a pas de resultats, on enregistre dans la base identification*/
	            if(!$resultat)
		   		 {	   	
		   		 	try {	 
			   		 $sql = "INSERT INTO `identification`( `login`, `pass`, `mail` ) VALUES ( :login, :pass, :e_mail)";
					 $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					 $sth->execute(array(':login' => $login, ':pass' => $pass, ':e_mail' => $e_mail));
					 $sth->closeCursor();
					 echo json_encode("true");
					 
		   		 	}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : verif_login requete INSERT ',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 	}
		   		 	
		   		 }
		   		 // sinon on renvoie un message erreur 
		   		 else
		  		  {
		  		  
		  		  echo json_encode("false"); 
		  		  
		  		  }
			} catch (Exception $e) {
	    			echo json_encode('Erreur reqête Mysql section : verif_login requete SELECT ',  $e->getMessage(), "\n");
			}
	
	}elseif ($data3=="supr_tour"){
	
				$login = $_POST['login'];
	
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET login=:login2, delete2=:login where login=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':login2' => ''));
				$st2->closeCursor();
				
				
				$sql = "UPDATE `aerogard2`.`identification` SET tour=:login2 where tour=:login and tour!='';";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':login2' => '0'));
				$st2->closeCursor();
				
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : supr_tour requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
	
				
	}elseif ($data3=="verif_login2"){
	
	$login = $_POST['login'];
	$pass = md5($_POST['pass']);
	$e_mail = $_POST['e_mail'];
			try {
				$req = $db->prepare('SELECT id FROM identification WHERE login = :login or mail = :e_mail');
		   		$req->execute(array(':login'=> $login, ':e_mail' => $e_mail));
		   		$resultat=$req->fetch(); 
	            /*si il n'y a pas de resultats, on enregistre dans la base identification*/
	            if(!$resultat)
		   		 {	   	
		   		 	try {	 
			   		 $sql = "INSERT INTO `identification`( `login`, `pass`, `tour`, `mail` ) VALUES ( :login, :pass, :tour, :e_mail)";
					 $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					 $sth->execute(array(':login' => $login, ':pass' => $pass, ':tour' => '0', ':e_mail' => $e_mail));
					 $sth->closeCursor();
					 echo json_encode("true");
					 
		   		 	}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : verif_login2 requete INSERT ',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 	}
		   		 	
		   		 }
		   		 // sinon on renvoie un message erreur 
		   		 else
		  		  {
		  		  
		  		  echo json_encode("false"); 
		  		  
		  		  }
			} catch (Exception $e) {
	    			echo json_encode('Erreur reqête Mysql section : verif_login2 requete SELECT ',  $e->getMessage(), "\n");
			}
	
	}elseif ($data3=="supr_membre"){
	
				$login = $_POST['login'];
	
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET delete2=:login where login=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login));
				$st2->closeCursor();
				
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : supr_membre requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
	
				
	}elseif ($data3=="activ_membre"){
	
				$login = $_POST['login'];
	
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET delete2=:login2 where delete2=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':login2' => ''));
				$st2->closeCursor();			
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : activ_membre requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
				
	}elseif ($data3=="modif_tour"){
	
				$login = $_POST['login'];
				$tour = $_POST['tour'];
				if($tour=='Aucun'){			
				$tour = 0;
				}
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET tour=:tour where login=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':tour' => $tour));
				$st2->closeCursor();			
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : modif_tour requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
				
	}elseif ($data3=="modif_groupe"){
	
				$login = $_POST['login'];
				$groupe = $_POST['groupe'];
				if($groupe=='Aucun'){			
				$groupe = '';
				}
	
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET groupe=:groupe where login=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':groupe' => $groupe));
				$st2->closeCursor();			
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : modif_groupe requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
				
	}elseif ($data3=="modif_mail"){
	
				$login = $_POST['login'];
				$mail = $_POST['mail'];				
	
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET mail=:mail where login=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':mail' => $mail));
				$st2->closeCursor();			
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : modif_mail requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
				
	}elseif ($data3=="modif_pass"){
	
				$login = $_POST['login'];
				$mon_pass = md5($_POST['pass']);				
	
				try {
		   		$sql = "UPDATE `aerogard2`.`identification` SET pass=:mon_pass where login=:login;";
				$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$st2->execute(array(':login' => $login, ':mon_pass' => $mon_pass));
				$st2->closeCursor();			
				echo json_encode("true");
		   		}catch (Exception $e) {
	
		   		 	echo json_encode('Erreur reqête Mysql section : modif_mail requete UPDATE',  $e->getMessage(), "\n");  		 	
		   		 	
		   		 }
				
	}
}
?>