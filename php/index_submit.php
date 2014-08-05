<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";
if (empty($_SESSION['id'])) //les membres connecte ne peuvent pas s'inscrire
{
    /* il faut que toutes les variables du formulaires existent*/
    if(isset($_POST['usermail']) && isset($_POST['password']))
    {
        /*il faut que tous les champs soient renseignes*/
        if($_POST['usermail']!="" && $_POST['password']!="")
        {
            
            /*on crypte le mot de passe pour faire le test*/
            $passwordhache = md5($_POST['password']);
 
            /* on verifie qu'un membre a bien ce pseudo et ce mot de passe*/
            $req = $db->prepare('SELECT * FROM identification WHERE mail = :mail AND pass= :pass ');
	    $req->execute(array('mail'=> $_POST['usermail'], 'pass'=> $passwordhache));
	    $resultat=$req->fetch();
 
            /*si il n'y a pas de resultats, on renvoie a la page de connexion*/
            if(!$resultat)
	   		 {
	   		 	?><script>alert('erreur adresse mail ou mot de passe.');</script><?php
	   		 			header('Location: index.php');
	   		 }
	   		 else
	  		  {
                /* on demarre la session */
                session_start();
 
               /* on cree les variables de session du membre qui lui serviront pendant sa session*/
               $_SESSION['id']= $resultat['id'];
	       $_SESSION['login']= $resultat['login'];
	       $_SESSION['mail']= $resultat['mail'];
 
                /*on renvoie sur la page d'accueil*/
                header('Location: index.php');              
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