<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";
if (!empty($_SESSION['id'])) //seuls membres connecte peuvent s'inscrire
{
    /* il faut que toutes les variables du formulaires existent*/
    if(isset($_POST['email']) && isset($_POST['password']))
    {
        /*il faut que tous les champs soient renseignes*/
        if($_POST['email']!="" && $_POST['password']!="")
        {
            
            /*on crypte le mot de passe pour faire le test*/
            $passwordhache = md5($_POST['password']);
 
            /* on verifie qu'un membre a bien ce pseudo et ce mot de passe*/
            $req = $db->prepare('SELECT * FROM identification WHERE mail = :mail AND pass= :pass AND chef = :admin ');
	   		$req->execute(array('mail'=> $_POST['email'], 'pass'=> $passwordhache, 'admin'=> 'oui'));
	   		$resultat=$req->fetch();
 
            /*si il n'y a pas de resultats, on renvoie a la page de connexion*/
            if(!$resultat)
	   		 {
	   		 	echo "erreur adresse mail ou mot de passe."; 
	   		 	
	   		 			//header('Location: index.php');
	   		 }
	   		 else
	  		  {
                /* on demarre la session */
                session_start();
                /* on cree les variables de session du membre qui lui serviront pendant sa session*/
  				$_SESSION['administrateur']= addslashes($resultat['id']);
  				echo "true"; 
                							
                              
            }
        }
        else
        {
           echo "Il faut remplir tous les champs"; 
        }
    }
    else
    {
        echo "Une erreur s'est produite";
    }
}
else
{
     echo "Vous n'avez pas le droit d'acceder a cette page";
}
?>