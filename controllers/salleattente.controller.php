<?php 
/* This controller renders the home page */
class SalleAttente{	
	public function salle_attente($id_attente){
				$salle_attente = requetemysql::salle_attente($id_attente);
				if(empty($salle_attente)){
				throw new Exception("Aucun animal dans la salle d'attente !");
				}
				$salle_attente2 = json_decode($salle_attente, true);
				$id_pro = $salle_attente2['id_pro'];
				$id_ani = $salle_attente2['id_ani'];
				$animal = requetemysql::findunanimal(array('id_pro'=>$id_pro, 'id_ani'=>$id_ani ));
				if(empty($animal)){
					throw new Exception("Aucun animal de cet id dans la base de donnée !");
				}		
				$historique = requetemysql::historique(array('id'=>$id_ani ));
				if(empty($historique)){
					throw new Exception("Erreur dans la recherche des antécédents de l animal");
				}		
				$client = requetemysql::findunclient(array('id'=>$id_pro ));
				if(empty($client)){
					throw new Exception("Aucun client dans la base de donnée !");
				}
				$animal2 = json_decode($animal, true);
	 			$datenais = $animal2[0]['datenais'];
				$tarif = requetemysql::info_tarif();
				if(empty($tarif)){
					throw new Exception("Aucun tarif dans la base de donnée !");
				}
				// recherche dans la base de donnee les differents règlements pour le client
	 			$restedu = requetemysql::restedu(array('id_pro'=>$id_pro ));
				if(empty($restedu)){
					throw new Exception("Pas de règlement dans la base");
				}
				$liste_resume = array(
 					array ( nom=>"Vaccin",
                       		valeur=>5,
                       		),
                   	array ( nom=>"Gastrite",
                       		valeur=>3,
                       		),
                    array ( nom=>"Enterite",
                       		valeur=>4,
                       		),
                    array ( nom=>"Abattement",
                       		valeur=>2,
                       		),
                    array ( nom=>"boiterie",
                       		valeur=>4,
                       		),
                    array ( nom=>"Certificat",
                       		valeur=>1,
                       		),
                    array ( nom=>"Anorexie",
                       		valeur=>3,
                       		),
                    array ( nom=>"Otite",
                       		valeur=>2,
                       		),
                    array ( nom=>"Piroplasmose",
                       		valeur=>2,
                       		),
                    array ( nom=>"Epillet oreille",
                       		valeur=>3,
                       		),
                    array ( nom=>"Ovarioectomie",
                       		valeur=>1,
                       		),
                   	array ( nom=>"Castration",
                       		valeur=>1,
                       		),
                    array ( nom=>"Difficultés respiratoires",
                       		valeur=>1,
                       		),
                    array ( nom=>"Cardiaque",
                       		valeur=>3,
                       		),
                    array ( nom=>"Achat",
                       		valeur=>5,
                       		),
                   	array ( nom=>"Arthrose",
                       		valeur=>3,
                       		)
                        
                        );
                        
              $liste_motif_relance = array(
 					array ( nom=>"Vaccin",
                       		valeur=>1,
                       		),
                   	array ( nom=>"Anovulatoire",
                       		valeur=>2,
                       		),
                    array ( nom=>"Vermifuge",
                       		valeur=>3,
                       		)                        
                        );
                        
              $liste_analyse = array(
 					array ( nom=>"Urée",
                       		unite=>"g/l",
                       		base=>"< 0.7g/l",
                       		methode=>"reflotron",
                       		),
                   	array ( nom=>"Glucose",
                       		unite=>"g/l",
                       		base=>"< 1.3g/l",
                       		methode=>"reflotron",
                       		),
                    array ( nom=>"PAL",
                       		unite=>"UI/l",
                       		base=>"< 200UI/l",
                       		methode=>"reflotron",
                       		),
                    array ( nom=>"Globules Blancs",
                       		unite=>"m/l",
                       		base=>"< 15000m/l",
                       		methode=>"MS4",
                       		),
                    array ( nom=>"Hémathocrite",
                       		unite=>"g/l",
                       		base=>"> 8g/l",
                       		methode=>"MS4",
                       		)                        
                        );
                        render('_nouvelleconsultation',array(
						'title'		=> 'Fiche Animal',
						'id_pro'	=> 	$id_pro,
						'id_ani'	=> 	$id_ani,
						'animal'	=> $animal,
						'client'	=> $client,
						'origin'	=> $origin,
						'liste_resume'   => $liste_resume,
						'liste_motif_relance' => $liste_motif_relance,
						'liste_analyse' => $liste_analyse,
						'historique'	=> $historique,
						'datenaissance'	=> $datenais,
						'tarif'	=> $tarif,
						'tva'	=> 	$tva,
						'restedu' => $restedu,
						'marge_medic'	=> 	$marge_medic,
                        'salle_attente_donnee' => 	$salle_attente,
						'themechargement'	=> 'b',
					    'textechargement'	=> 'enregistrement en cours',
					    'race' => $race
						)
						);
	}
}?>