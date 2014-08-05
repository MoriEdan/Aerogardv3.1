<?php
/*


*/
session_start();
require_once "php/main.php";


try {

	if (empty($_SESSION['id'])){
		$c = new Identification();
		$c->handleRequest();
	}elseif(!empty($_SESSION['id'])){
			if(!empty($_SESSION['administrateur'])){
				$c = new Gestion();
				$c->gestion_admin();		
			}
			else if (isset($_REQUEST['idpro3'])){
				$c = new Nouvelleconsult();
			//	$c->find($_REQUEST['idpro3'],$_REQUEST['idani'],"_accueil", $tva, $marge_medic);
				$c->find($_REQUEST['idpro3'],$_REQUEST['idani'],"_accueil");
			}
			else if(isset($_REQUEST['reglage'])){
				$c = new Reglage();
			//	$c->zone_reglage($tva);		
				$c->zone_reglage();
			}
			else if(isset($_REQUEST['admin'])){
				$c = new log_admin();
				$c->login_admin();			
			}
			else if(isset($_REQUEST['agenda'])){
				$c = new Agenda();
				$c->zone_agenda($_REQUEST['agenda'],"week");			
			}
			else if(isset($_REQUEST['tourdegarde'])){
				$c = new Tourdegarde();
				$c->zone_garde();
			}
			else if(isset($_REQUEST['labo'])){
				$c = new Labo();
				$c->test();
			}
			else if(isset($_REQUEST['agenda2'])){
				$c = new Agenda();
				$c->zone_agenda($_REQUEST['agenda'],"day");			
			}
			else if (isset($_REQUEST['idani'])){
			// composition de la  classe NouveauAnimal fonction find :
			// 1 : id proprio 2 : id animal 3 : page de retour après exécution 4 : id dans la salle d'attente (falcultatif) 5 : deja en salle attente avant click (falcultatif)
				$c = new NouveauAnimal();
				// si on envoie en GET une indication de retour particulière type consultation
				if (isset($_REQUEST['retour'])){
				$c->find($_REQUEST['idpro2'],$_REQUEST['idani'],$_REQUEST['retour'],$_REQUEST['id_salle_attente'], $_REQUEST['valeur_attente']);
				}else{// sinon on retourne en page accueil
				$c->find($_REQUEST['idpro'],$_REQUEST['idani'],"_accueil","",0);
				}
				
			}else if (isset($_REQUEST['idpro'])){
			//composition de la classe NouveauClient fonction find
			// 1 : id proprio 2 : page de retour requete terminée 3 : id salle attente (falcutatif) 4 : deja en salle attente avant click (falcultatif
				$c = new NouveauClient();
				// si on envoie en GET une indication de retour exemple consultation
				if (isset($_REQUEST['retour'])){
				$c->find($_REQUEST['idpro'],$_REQUEST['retour'], $_REQUEST['id_salle_attente'], $_REQUEST['valeur_attente']);
				}else{
				$c->find($_REQUEST['idpro'],"_accueil", 0, 0);
				}
			}else if(isset($_REQUEST['gestion_membre'])){
				$c = new Modificationmembre();
				$c->find();			
			}else if(isset($_REQUEST['id_casrefere'])){
				$c = new Nouvelleconsult();
		//		$c->lecture_rapport_recu($_REQUEST['id_casrefere'],"_accueil", $tva, $marge_medic, "envoi_refere");	
				$c->lecture_rapport_recu($_REQUEST['id_casrefere'],"_accueil", "envoi_refere");
			}else if(isset($_REQUEST['id_rapport_ref'])){
				$c = new Nouvelleconsult();
		//		$c->lecture_rapport_recu($_REQUEST['id_rapport_ref'],"_accueil", $tva, $marge_medic, "rapport_recus");
				$c->lecture_rapport_recu($_REQUEST['id_rapport_ref'],"_accueil", "rapport_recus");
			}else if(isset($_REQUEST['id_rapport_redige'])){
				$c = new Nouvelleconsult();
		//		$c->lecture_rapport_emis($_REQUEST['id_rapport_redige'],"_accueil", $tva, $marge_medic);	
				$c->lecture_rapport_emis($_REQUEST['id_rapport_redige'],"_accueil");
			}else if(isset($_REQUEST['id_salle_attente'])){
				$c = new Nouvelleconsult();
				if(isset($_REQUEST['valeur_attente'])){
				//	$c->salleattente($_REQUEST['id_salle_attente'],"_salle_attente", $tva, $marge_medic);
					$c->salleattente($_REQUEST['id_salle_attente'],"_salle_attente");
				}else{
				//	$c->salleattente($_REQUEST['id_salle_attente'],"_modif", $tva, $marge_medic);
					$c->salleattente($_REQUEST['id_salle_attente'],"_modif");
				}
							
			}else if(isset($_REQUEST['id_consultation'])){
				$c = new Nouvelleconsult();
		//		$c->historique($_REQUEST['id_consultation'],"_accueil", $tva, $marge_medic);
				$c->historique($_REQUEST['id_consultation'],"_accueil");
			}else{		
				$c = new Rechercheclient();
				$c->find();
			}
	}
	else throw new Exception('Wrong page!');
	
	
}
catch(Exception $e) {
	// Display the error page using the "render()" helper function:
	
}


?>

