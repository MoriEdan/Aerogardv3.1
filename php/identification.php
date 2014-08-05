<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";
require_once "language2.php";
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
            $req = $db->prepare('SELECT * FROM identification WHERE mail = :mail AND pass = :pass ');
	   		$req->execute(array('mail'=> $_POST['usermail'], 'pass'=> $passwordhache));
	   		$resultat=$req->fetch();
 
            /*si il n'y a pas de resultats, on renvoie a la page de connexion*/
            if(!$resultat)
	   		 {
	   		 	echo TXTIDENTIFICATION_ERROR2; 
	   		 	
	   		 			//header('Location: index.php');
	   		 }
	   		 else
	  		  {
                /* on demarre la session */
                session_start();
 
               /* on cree les variables de session du membre qui lui serviront pendant sa session*/
                if($_POST['liste_choix']==2){
                	if($resultat['delete2']!=''){
                	
                	echo TXTIDENTIFICATION_ERROR3;
                		
                	}elseif($resultat['tour']=='0'){
                	
                	echo TXTIDENTIFICATION_ERROR4;
                	
                	}elseif($resultat['tour']==''){
                		$_SESSION['id']= addslashes($resultat['id']);
                		//$_SESSION['login']= addslashes(strtoupper($resultat['tour']));
                		$_SESSION['login']= addslashes($resultat['login']);
                		//$_SESSION['login2']= addslashes(strtoupper($resultat['login']));
                		$_SESSION['login2']= addslashes($resultat['login']);
                		$_SESSION['tour']= addslashes($resultat['login']);
                		$_SESSION['groupe']= addslashes($resultat['groupe']);
                		echo "ok";
                		
                	}else{
                	   $_SESSION['id']= addslashes($resultat['id']);
			     	   //$_SESSION['login']= addslashes(strtoupper($resultat['tour']));
			     	   $_SESSION['login']= addslashes($resultat['tour']);
			     	   //$_SESSION['login2']= addslashes(strtoupper($resultat['login']));
			     	   $_SESSION['login2']= addslashes($resultat['login']);
			     	   $_SESSION['tour']= addslashes($resultat['tour']);
			      	   $_SESSION['groupe']= addslashes($resultat['groupe']);     
			      	   echo "ok";   
                	}        
                }elseif($_POST['liste_choix']==1){                
		               $_SESSION['id']= addslashes($resultat['id']);
			     	   //$_SESSION['login']= addslashes(strtoupper($resultat['login']));
			     	   $_SESSION['login']= addslashes($resultat['login']);
			     	   //$_SESSION['login2']= addslashes(strtoupper($resultat['login']));
			     	   $_SESSION['login2']= addslashes($resultat['login']);
			     	   $_SESSION['tour']= addslashes($resultat['tour']);
			      	   $_SESSION['groupe']= addslashes($resultat['groupe']);
			      	   echo "ok";	      	   
                }elseif($_POST['liste_choix']==3){               
		               $_SESSION['id']= addslashes($resultat['id']);
			     	  //$_SESSION['login']= addslashes(strtoupper($resultat['login']));
			     	   $_SESSION['login']= addslashes($resultat['login']);
			     	   //$_SESSION['login2']= addslashes(strtoupper($resultat['login']));
			     	   $_SESSION['login2']= addslashes($resultat['login']);
			     	   $_SESSION['tour']= addslashes($resultat['tour']);
			      	   $_SESSION['groupe']= addslashes($resultat['groupe']);
			      	   echo "agenda";	      	   
                }
 				 
 							
                              
            }
        }
        else
        {
           echo TXTIDENTIFICATION_ERROR5; 
        }
    }
    else
    {
        echo TXTIDENTIFICATION_ERROR6;
    }
}
else
{
     echo TXTIDENTIFICATION_ERROR7;
}
?>