<?php 
/* This controller renders the home page */
class Rechercheclient{
	public function find(){		
			try{
				$client = requetemysql::findclient("client");
				if(empty($client)){
					throw new Exception("Error in findclient function , client part !");
				}		
				$animaux = requetemysql::findclient("ax");
					if(empty($animaux)){
					throw new Exception("Error in findclient function, ax part !");
					}
				$salle_attente = requetemysql::salle_attente("general");
					if(empty($salle_attente)){
					throw new Exception("Error in salle_attente function, general part !");
					}	
				$rapport_ref = requetemysql::rapport_ref("general");
					if(empty($rapport_ref)){
					throw new Exception("Error in rapport_ref function, general part !");
					}
				$rapport_redige = requetemysql::rapport_redige("general");
					if(empty($rapport_redige)){
					throw new Exception("Error in rapport_redige function, general part !");
					}
				$rapport_refere = requetemysql::rapport_refere("general");
					if(empty($rapport_refere)){
					throw new Exception("Error in rapport_refere fonction, general part !");
					}
				$liste_mur = requetemysql::liste_mur("general");
					if(empty($liste_mur)){
					throw new Exception("Error in liste_mur fonction, general part !");
					}
				// liste des vétos du tour de garde
				$vetos = requetemysql::listevetos();	
					if(empty($vetos)){
						throw new Exception("Error in listevetos fonction !");
					}
				$liste_message_recu_perso = requetemysql::liste_message(array('login'=>$_SESSION['login2'], 'choix'=>'recu'));	
					if(empty($liste_message_recu_perso)){
						throw new Exception("Error in liste_message function ! param : login: "+$_SESSION['login2']+" choix: recu");
					}
				$liste_message_recu_garde = requetemysql::liste_message(array('login'=>$_SESSION['login'], 'choix'=>'recu'));	
					if(empty($liste_message_recu_garde)){
						throw new Exception("Error in liste_message function ! param : login: "+$_SESSION['login']+" choix: recu");						
					}
				$liste_message_emis = requetemysql::liste_message(array('login'=>$_SESSION['login2'], 'choix'=>'emis'));	
					if(empty($liste_message_emis)){
						throw new Exception("Error in liste_message function ! param : login: "+$_SESSION['login2']+" choix: emis");
						
					}
				$historique = requetemysql::brouillard(array('choix'=>'historique2'));
						if(empty($historique)){
						throw new Exception("Error in brouillard function ! param : choix : historique");
					}
				$date_debut =  mktime(0, 0, 0, date("m")  , 1, date("Y"));
				$date_fin =  mktime(0, 0, 0, date("m")+1  , 1, date("Y"));
				
				$planning = requetemysql::liste_garde(array('debut' => $date_debut, 'fin' => $date_fin));				
				
				$info_tour = requetemysql::info_tour2(array('login'=>$_SESSION['tour']));
				if(empty($info_tour)){
					throw new Exception("Aucun tour dans la base de donnée !");
				}				
				$info_tour_deco = json_decode($info_tour,true);
				if($info_tour_deco[0]['envoi_mail']==0){					
				$date_envoi_mail =  mktime(0, 0, 0, date("m")  , date("d")+$info_tour_deco[0]['jour'], date("Y"));
				$deja_envoye = requetemysql::mail_allready_send(array('ma_date' => $date_envoi_mail));
					if(count($deja_envoye)==0 && date("H")>=0 && date("H")<=23){						
						$envoyer_mail = requetemysql::envoyer_mail(array('ma_date' => $date_envoi_mail));
										
						
						$mail_envoye = requetemysql::mail_send(array('ma_date' => $date_envoi_mail));					
						
					}				
				}
			//	$planning = requetemysql::planning(array('tour'=>$_SESSION['tour'], 'date_debut'=>$date_debut, 'date_fin'=>$date_fin, 'nature'=>1));	
			//		if(empty($planning)){
			//			throw new Exception("Error in planning function ! param : tour :"+$_SESSION['tour']+" date_debut : "+$date_debut+" date_fin : "+$date_fin+" nature : 1");
			//		}
			//	$planning2 = requetemysql::planning(array('tour'=>$_SESSION['tour'], 'date_debut'=>$date_debut, 'date_fin'=>$date_fin, 'nature'=>2));
			//		if(empty($planning2)){
			//			throw new Exception("Error in planning function ! param : tour :"+$_SESSION['tour']+" date_debut : "+$date_debut+" date_fin : "+$date_fin+" nature : 2");						
			//		}
				render('_accueil',array(
				'title'		=> TXT_RECHERCHECLIENT_CONTROLLER_TITTLE,
				'texte_recherche'	=> TXT_RECHERCHECLIENT_CONTROLLER_SEARCH1,
				'texte_recherche2'	=> TXT_RECHERCHECLIENT_CONTROLLER_SEARCH2,
				'client'	=> $client,
				'animaux'	=> $animaux,
				'salle_attente' => $salle_attente,
				'rapport_ref' => $rapport_ref,
				'rapport_redige' => $rapport_redige,
				'casrefere' => $rapport_refere,
				'liste_vetos' => $vetos,
				'liste_message_recu_perso' => $liste_message_recu_perso,
				'liste_message_recu_garde' => $liste_message_recu_garde,
				'liste_message_emis' => $liste_message_emis,
				'planning' => $planning,				
				'historique' => $historique,
				'liste_mur' => $liste_mur
				));
			} catch(Exception $e){
				echo $e->getMessage();
			}
	}
}?>