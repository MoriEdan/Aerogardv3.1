<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";
if (empty($_SESSION['id'])) //les membres connectes ne peuvent pas changer de mot de passe
{
    /* il faut que toutes les variables du formulaires existent*/
    if(isset($_POST['email2']))
    {
        /*il faut que tous les champs soient renseignes*/
        if($_POST['email2']!="")
        {
            
           
 
            /* on verifie qu'un membre a bien ce pseudo */
            $req = $db->prepare('SELECT * FROM identification WHERE mail = :mail');
	   		$req->execute(array('mail'=> $_POST['email2']));
	   		$resultat=$req->fetch();
 
            /*si il n'y a pas de resultats, on renvoie a la page de connexion*/
            if(!$resultat)
	   		 {
	//   		 	echo "<script>alert('Adresse email invalide');</script>"; 
	   		 	
	   		 	header('Location: ../index.php');
	   		 }
	   		 else
	  		  {
                /* on demarre la session */
                session_start();
 
               /* on cree les variables de session du membre qui lui serviront pendant sa session*/
               
                	if($resultat['delete2']!=''){
            //    		echo "<script>alert('Votre compte a été désactivé. Contactez l administrateur.');</script>";
                		
                		header('Location: ../index.php');
                	                		
                	}else{
                		/*on crypte le mot de passe pour faire le test*/
                		$mon_pass = newChaine(8);
                		$passwordhache = md5($mon_pass);
                		
                		$sql="UPDATE identification set pass2=:monpass WHERE mail=:mail LIMIT 1";
                		$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                		$st2->execute(array(':monpass' => $passwordhache, ':mail' => $_POST['email2']));
                		$st2->closeCursor();
                		
                		
                		
                		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
                		{
                			$passage_ligne = "\r\n";
                		}
                		else
                		{
                			$passage_ligne = "\n";
                		}
                		//=====Déclaration des messages au format texte et au format HTML.
                		$message_txt = "Bonjour ".addslashes($resultat['login']).". <br>Nous avons reçu votre demande de changement de mot de passe.<br>
						Nouveau mot de passe : ".$mon_pass." <br>
						Pour valider ce changement de mot de passe, veuillez cliquer sur le lien : ".$url_serveur."index2.php?mdp=".$passwordhache." <br>
						Si vous avez reçu ce mail par erreur : veuillez contacter ".$mail_serveur." <br>
						Sincères salutations.<br>";
                		
                		$message_html = "<html><head></head><body><p>Bonjour ".addslashes($resultat['login']).". </p><section><aside>Nous avons reçu votre demande de changement de mot de passe.</aside><article>
						<p>Nouveau mot de passe : ".$mon_pass." </p>
						<p>Pour valider ce changement de mot de passe, veuillez cliquer sur le lien :  <a href=\"".$url_serveur."index2.php?mdp=".$passwordhache."\">cliquez sur ce lien</a></p>
						<p>Si vous avez reçu ce mail par erreur : veuillez contacter : ".$mail_serveur."</p>
						<p>Sincères salutations.</p><ul>";
                		
                		//==========
                		//=====Création de la boundary
                		$boundary = "-----=".md5(rand());
                		//==========
                		//=====Définition du sujet.
                		$sujet = "mot de passe Urgencesvet ";
                		//=========
                		//=====Création du header de l'e-mail
                		$header = "From: \"".$nom_serveur_mail."\"<".$mail_serveur.">".$passage_ligne;
                		$header .= "Reply-to: \"".$nom_serveur_mail."\" <".$mail_serveur.">".$passage_ligne;
                		$header .= "MIME-Version: 1.0".$passage_ligne;
                		$header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
                		//=====Création du message.
                		$message = $passage_ligne."--".$boundary.$passage_ligne;
                		//=====Ajout du message au format texte.
                		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
                		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
                		$message.= $passage_ligne.$message_txt.$passage_ligne;
                		//==========
                		$message.= $passage_ligne."--".$boundary.$passage_ligne;
                		//=====Ajout du message au format HTML
                		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
                		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
                		$message.= $passage_ligne.$message_html.$passage_ligne;
                		//==========
                		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
                		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
                		//==========
                		//=====Envoi de l'e-mail.
                		mail($_POST['email2'],utf8_decode($sujet),utf8_decode($message),$header);
                		//==========
                		
                //		echo "<script>alert('Nouveau mot de passe sur votre adresse mail');</script>";
                		
                		header('Location: ../index.php');
                	}
 				 
 				}
        }
        else
        {
    //       echo "<script>alert('Il faut remplir votre email.');</script>";
                		
                		header('Location: ../index.php');
        }
    }
   
    else
    {
    //    echo "<script>alert('une erreur s est produite.');</script>";
                		
                		header('Location: ../index.php');
    }
}

else
{
   // echo "<script>alert('Vous n avez pas le droit de voir cette page');</script>";
                		
                		header('Location: ../index.php');
}
function newChaine( $chrs ) {

	if( $chrs == "" ) $chrs = 8;



	$chaine = "";



	$list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	mt_srand((double)microtime()*1000000);

	$newstring="";



	while( strlen( $newstring )< $chrs ) {

		$newstring .= $list[mt_rand(0, strlen($list)-1)];

	}

	return $newstring;

}
?>