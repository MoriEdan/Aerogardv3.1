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
if ($_GET['action']=='envoi_message'){
			if($_POST['destinataire']=='0'){
				$sql = "INSERT INTO `message`(`from`, `destinataire`, `titre`, `message`, `lu`, `date`) VALUES ";
				$qPart = array_fill(0, count($_POST['liste_vetos']), "(?, ?, ?, ?, 1, STR_TO_DATE(?,'%d/%m/%Y'))");
				$sql .=  implode(",",$qPart);
				$stmt = $db -> prepare($sql);
				$i = 1;
				foreach($_POST['liste_vetos'] as $item) {
					$stmt -> bindParam($i++, $_SESSION['login2']);
					$stmt -> bindParam($i++, $item['login']);
					$stmt -> bindParam($i++, $_POST['titre']);
					$stmt -> bindParam($i++, $_POST['message']);
					$stmt -> bindParam($i++, date("d/m/y"));					
				}
				$stmt -> execute();
				$message_id = $db->lastInsertId();
				$stmt->closeCursor();				
			}else{
			$sql = "INSERT INTO `message`(`id`, `from`, `destinataire`, `titre`, `message`, `lu`, `date`) VALUES ('' , :permission, :destinataire, :titre, :message, '1', STR_TO_DATE(:madate,'%d/%m/%Y'))";
			$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':permission' => $_SESSION['login2'], ':destinataire' => $_POST['destinataire'], ':titre' => $_POST['titre'],
								':message' => $_POST['message'], ':madate' => date("d/m/y")));
			$message_id = $db->lastInsertId();
			$sth->closeCursor();
			}
			echo json_encode($message_id);
			
			
}elseif($_GET['action']=='recupmessage'){
			if($_POST['choix']=='perso'){
			$liste_message_recu_perso = requetemysql::liste_message(array('login'=>$_SESSION['login2'], 'choix'=>'recu'));
			}elseif($_POST['choix']=='garde'){
			$liste_message_recu_perso = requetemysql::liste_message(array('login'=>$_SESSION['login'], 'choix'=>'recu'));
			}elseif($_POST['choix']=='emis'){
			$liste_message_recu_perso = requetemysql::liste_message(array('login'=>$_SESSION['login2'], 'choix'=>'emis'));
			}	
				if(empty($liste_message_recu_perso)){
					throw new Exception("Pas de message dans la base");
				}				
			echo $liste_message_recu_perso;	
}elseif($_GET['action']=='marquelu'){
	if($_POST['choix']=='perso'){
		$message_marque_lu = requetemysql::marquelu(array('login'=>$_SESSION['login2'], 'message'=>$_POST['message']));
	}elseif($_POST['choix']=='garde'){
		$message_marque_lu = requetemysql::marquelu(array('login'=>$_SESSION['login'], 'message'=>$_POST['message']));
	}
	echo $message_marque_lu;	
}
}
