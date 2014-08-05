<?php 
/* This controller renders the home page */
class Nouvelleconsult{
//	public function find($id_pro,$id_ani, $origin, $tva, $marge_medic){	
	public function find($id_pro,$id_ani, $origin){		
			$mes_infos = requetemysql::mes_infos();
			if(empty($mes_infos)){
				throw new Exception("Vous êtes introuvables dans la bdd identification!");
			}	
			$mes_infos = json_decode($mes_infos, true);
			$tva = $mes_infos[0]['tva'];
			$marge_medic = $mes_infos[0]['marge'];
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
			$vetos = requetemysql::listevetos();	
			if(empty($vetos)){
				throw new Exception("Pas de vetos dans la base");
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
 					array ( nom=>"vaccin",
                       		valeur=>1,
                       		),
                   	array ( nom=>"anovulatoire",
                       		valeur=>2,
                       		),
                    array ( nom=>"vermifuge",
                       		valeur=>3,
                       		),
                    array ( nom=>"puces",
                       		valeur=>4,
                       		),
                    array ( nom=>"rendezvous",
                       		valeur=>5,
                       		),
                    array ( nom=>"impaye",
                       		valeur=>6,
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
 			$liste_radio = array(
 					array ( nom=>"thorax profil petit chien",
                       		kV=>"70",
                       		mAS=>"5", 
                       		sec=>"5",                      		
                       		),
                   array ( nom=>"abdomen face petit chien",
                       		kV=>"80",
                       		mAS=>"10",
                       		sec=>"6",                        		
                       		),
                   array ( nom=>"Carpe gros chien",
                       		kV=>"20",
                       		mAS=>"3",
                       		sec=>"7",                        		
                       		),
                   array ( nom=>"bassin petit chat",
                       		kV=>"60",
                       		mAS=>"7",  
                       		sec=>"8",                      		
                       		)                
                        );
 			$presence_historique = requetemysql::presence_historique(array('id_ani'=>$id_ani));
 			if(!empty($presence_historique)){
 				echo '<script>alert("Animal déjà présent en attente")</script>';
 			} 			
                        
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
			'liste_radio' => $liste_radio,
			'liste_vetos' => $vetos,
			'veto' => $_SESSION['login'],
			'historique'	=> $historique,
			'datenaissance'	=> $datenais,
			'tarif'	=> $tarif,
			'tva'	=> 	$tva,
			'restedu' => $restedu,
			'salle_attente_donnee' => 	0,
			'marge_medic'	=> 	$marge_medic,
			'themechargement'	=> 'b',
			'cas' => 'nouvelle_consult',
			'consult' => 0,
			'veto_repartition' => 0,
			'liste_tournures' => ordo_settings_tourn(),
			'liste_cat_delivre' => ordo_settings_cat(),
			'info_veto' => info_clini(),
		    'textechargement'	=> 'enregistrement en cours'
		    
			)
			);
	}
	// public function lecture_rapport_emis($id_attente, $origin, $tva, $marge_medic){
	public function lecture_rapport_emis($id_attente, $origin){
				$mes_infos = requetemysql::mes_infos();
				if(empty($mes_infos)){
					throw new Exception("Vous êtes introuvables dans la bdd identification!");
				}
				$mes_infos = json_decode($mes_infos, true);
				$tva = $mes_infos[0]['tva'];
				$marge_medic = $mes_infos[0]['marge'];
				$salle_attente = requetemysql::rapport_redige($id_attente);
				if(empty($salle_attente)){
				throw new Exception("Aucun animal dans la salle d'attente !");
				}
				$salle_attente2 = json_decode($salle_attente, true);
				$id_pro = $salle_attente2[0]['id_pro'];
				$id_ani = $salle_attente2[0]['id_ani'];
				$animal = requetemysql::findunanimal(array('id_pro'=>$id_pro, 'id_ani'=>$id_ani ));
				if(empty($animal)){
					throw new Exception("Aucun animal de cet id dans la base de donnée !");
				}		
				$historique = requetemysql::historique(array('id'=>$id_ani ));
				if(empty($historique)){
					throw new Exception("Erreur dans la recherche des antécédents de l animal");
				}		
				$client = requetemysql::findunclient2(array('id'=>$id_pro ));
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
				$vetos = requetemysql::listevetos();	
						if(empty($vetos)){
							throw new Exception("Pas de vetos dans la base");
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
                $liste_radio = array(
 					array ( nom=>"thorax profil petit chien",
                       		kV=>"70",
                       		mAS=>"5", 
                       		sec=>"5",                      		
                       		),
                   array ( nom=>"abdomen face petit chien",
                       		kV=>"80",
                       		mAS=>"10",
                       		sec=>"6",                        		
                       		),
                   array ( nom=>"Carpe gros chien",
                       		kV=>"20",
                       		mAS=>"3",
                       		sec=>"7",                        		
                       		),
                   array ( nom=>"bassin petit chat",
                       		kV=>"60",
                       		mAS=>"7",  
                       		sec=>"8",                      		
                       		)                
                        );
			//	$efface_salle_attente = requetemysql::supr_salle_attente($id_attente);
			//	if(empty($efface_salle_attente)){
			//	throw new Exception("Effaçage de la salle d attente impossible !");
			//	}
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
                        'liste_radio' => $liste_radio,
                        'liste_vetos' => $vetos,
                        'veto' => $_SESSION['login'],
						'historique'	=> $historique,
						'datenaissance'	=> $datenais,
						'tarif'	=> $tarif,
						'tva'	=> 	$tva,
						'restedu' => $restedu,
						'marge_medic'	=> 	$marge_medic,
                        'salle_attente_donnee' => 	$salle_attente,
						'themechargement'	=> 'b',
                        'cas' => 'rapport_emis',
                        'consult' => 0,
                        'veto_repartition' => 0,
                        'liste_tournures' => ordo_settings_tourn(),
						'liste_cat_delivre' => ordo_settings_cat(),
                        'info_veto' => info_clini(),
					    'textechargement'	=> 'enregistrement en cours'
					    
						)
						);
	
	}
//	public function lecture_rapport_recu($id_attente, $origin, $tva, $marge_medic, $cas){
	public function lecture_rapport_recu($id_attente, $origin, $cas){
				$mes_infos = requetemysql::mes_infos();
				if(empty($mes_infos)){
					throw new Exception("Vous êtes introuvables dans la bdd identification!");
				}
				$mes_infos = json_decode($mes_infos, true);
				$tva = $mes_infos[0]['tva'];
				$marge_medic = $mes_infos[0]['marge'];
				if($cas=="rapport_recus"){
				$salle_attente = requetemysql::rapport_ref($id_attente);
				}elseif($cas=="envoi_refere"){
				$salle_attente = requetemysql::rapport_refere($id_attente);
				}
				if(empty($salle_attente)){
				throw new Exception("Aucun animal dans la salle d'attente !");
				}
				$salle_attente2 = json_decode($salle_attente, true);
				$id_pro = $salle_attente2[0]['id_pro'];
				$id_ani = $salle_attente2[0]['id_ani'];
				$animal = requetemysql::findunanimal(array('id_pro'=>$id_pro, 'id_ani'=>$id_ani ));
				if(empty($animal)){
					throw new Exception("Aucun animal de cet id dans la base de donnée !");
				}		
				$historique = requetemysql::historique(array('id'=>$id_ani ));
				if(empty($historique)){
					throw new Exception("Erreur dans la recherche des antécédents de l animal");
				}		
				$client = requetemysql::findunclient2(array('id'=>$id_pro ));
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
				$vetos = requetemysql::listevetos();	
						if(empty($vetos)){
							throw new Exception("Pas de vetos dans la base");
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
                $liste_radio = array(
 					array ( nom=>"thorax profil petit chien",
                       		kV=>"70",
                       		mAS=>"5", 
                       		sec=>"5",                      		
                       		),
                   array ( nom=>"abdomen face petit chien",
                       		kV=>"80",
                       		mAS=>"10",
                       		sec=>"6",                        		
                       		),
                   array ( nom=>"Carpe gros chien",
                       		kV=>"20",
                       		mAS=>"3",
                       		sec=>"7",                        		
                       		),
                   array ( nom=>"bassin petit chat",
                       		kV=>"60",
                       		mAS=>"7",  
                       		sec=>"8",                      		
                       		)                
                        );
			//	$efface_salle_attente = requetemysql::supr_salle_attente($id_attente);
			//	if(empty($efface_salle_attente)){
			//	throw new Exception("Effaçage de la salle d attente impossible !");
			//	}
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
                        'liste_radio' => $liste_radio,
                        'liste_vetos' => $vetos,
                        'veto' => $_SESSION['login'],
						'historique'	=> $historique,
						'datenaissance'	=> $datenais,
						'tarif'	=> $tarif,
						'tva'	=> 	$tva,
						'restedu' => $restedu,
						'marge_medic'	=> 	$marge_medic,
                        'salle_attente_donnee' => 	$salle_attente,
						'themechargement'	=> 'b',
                        'cas' => $cas,
                        'consult' => 0,
                        'veto_repartition' => 0,
                        'liste_tournures' => ordo_settings_tourn(),
						'liste_cat_delivre' => ordo_settings_cat(),
                        'info_veto' => info_clini(),
					    'textechargement'	=> 'enregistrement en cours'
					    
						)
						);
						
						
						
	
	
	}
//	public function salleattente($id_attente, $origin, $tva, $marge_medic){
	public function salleattente($id_attente, $origin){
				$mes_infos = requetemysql::mes_infos();
				if(empty($mes_infos)){
					throw new Exception("Vous êtes introuvables dans la bdd identification!");
				}
				$mes_infos = json_decode($mes_infos, true);
				$tva = $mes_infos[0]['tva'];
				$marge_medic = $mes_infos[0]['marge'];
				$salle_attente = requetemysql::salle_attente($id_attente);
				if(empty($salle_attente)){
				throw new Exception("Aucun animal dans la salle d'attente !");
				}
				$salle_attente2 = json_decode($salle_attente, true);
				$id_pro = $salle_attente2[0]['id_pro'];
				$id_ani = $salle_attente2[0]['id_ani'];
				$nom_a = $salle_attente2[0]['nom_a'];
				$nom_p = $salle_attente2[0]['nom_p'];
				$animal = requetemysql::findunanimal(array('id_pro'=>$id_pro, 'id_ani'=>$id_ani ));
				if(json_decode($animal)==''){	
					$quiadelete = requetemysql::findwhodelete(array('id_s'=>$id_attente));
					if(count(json_decode($quiadelete))==0){
						header('HTTP/1.1 403 Forbidden');
						exit(utf8_decode("Cet animal n'est plus en salle d'attente. La fiche a été validée ou les données ont été perdues lors d'un changement de page."));
						//throw new Exception("Aucun animal de cet id dans la base de donnée !");											
					}else{
						$quiadelete2 = json_decode($quiadelete, true);
						header('HTTP/1.1 403 Forbidden');
						exit(utf8_decode("Cet animal n'est plus en salle d'attente. La fiche a été validée ou supprimée par ".$quiadelete2[0]['login']." le ".$quiadelete2[0]['ma_date']));
					}
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
				$vetos = requetemysql::listevetos();	
						if(empty($vetos)){
							throw new Exception("Pas de vetos dans la base");
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
                $liste_radio = array(
 					array ( nom=>"thorax profil petit chien",
                       		kV=>"70",
                       		mAS=>"5", 
                       		sec=>"5",                      		
                       		),
                   array ( nom=>"abdomen face petit chien",
                       		kV=>"80",
                       		mAS=>"10",
                       		sec=>"6",                        		
                       		),
                   array ( nom=>"Carpe gros chien",
                       		kV=>"20",
                       		mAS=>"3",
                       		sec=>"7",                        		
                       		),
                   array ( nom=>"bassin petit chat",
                       		kV=>"60",
                       		mAS=>"7",  
                       		sec=>"8",                      		
                       		)                
                        );
               
				$efface_salle_attente = requetemysql::supr_salle_attente($id_attente, $nom_a, $nom_p);
				if(empty($efface_salle_attente)){
				throw new Exception("Effaçage de la salle d attente impossible !");
				}
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
                        'liste_radio' => $liste_radio,
                        'liste_vetos' => $vetos,
                        'veto' => $_SESSION['login'],
						'historique'	=> $historique,
						'datenaissance'	=> $datenais,
						'tarif'	=> $tarif,
						'tva'	=> 	$tva,
						'restedu' => $restedu,
						'marge_medic'	=> 	$marge_medic,
                        'salle_attente_donnee' => 	$salle_attente,
						'themechargement'	=> 'b',
                        'cas' => 'salle_attente',
                        'consult' => 0,
                        'veto_repartition' => 0,
                        'liste_tournures' => ordo_settings_tourn(),
						'liste_cat_delivre' => ordo_settings_cat(),
                        'info_veto' => info_clini(),
					    'textechargement'	=> 'enregistrement en cours'
					    
						)
						);
	}
	// public function historique($id_consult, $origin, $tva, $marge_medic){
	public function historique($id_consult, $origin){
				$mes_infos = requetemysql::mes_infos();
				if(empty($mes_infos)){
					throw new Exception("Vous êtes introuvables dans la bdd identification!");
				}
				$mes_infos = json_decode($mes_infos, true);
				$tva = $mes_infos[0]['tva'];
				$marge_medic = $mes_infos[0]['marge'];
				$salle_attente = requetemysql::recup_element_consult($id_consult);
				if(empty($salle_attente)){
				throw new Exception("Aucun animal dans la salle d'attente !");					
				}
				$salle_attente2 = json_decode($salle_attente, true);
				$id_pro = $salle_attente2[0]['id_pro'];
				$id_ani = $salle_attente2[0]['id_ani'];
				
				$animal = requetemysql::findunanimal(array('id_pro'=>$id_pro, 'id_ani'=>$id_ani ));
				if(json_decode($animal)==''){	
					header('HTTP/1.1 403 Forbidden');
					exit(utf8_decode("Cette consultation n'existe plus. Elle a déjà été modifiée par le vétérinaire qui en est responsable. Elle porte maintenant un autre numéro."));
					//throw new Exception("Aucun animal de cet id dans la base de donnée !");
				}
				$veto_repartition = requetemysql::repartition(array('id_consult'=>$id_consult ));
				if(empty($veto_repartition)){
					throw new Exception("Erreur dans la recherche de la répartition des honoraires");
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
				$vetos = requetemysql::listevetos();	
							if(empty($vetos)){
								throw new Exception("Pas de vetos dans la base");
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
                   $liste_radio = array(
 					array ( nom=>"thorax profil petit chien",
                       		kV=>"70",
                       		mAS=>"5", 
                       		sec=>"5",                      		
                       		),
                   array ( nom=>"abdomen face petit chien",
                       		kV=>"80",
                       		mAS=>"10",
                       		sec=>"6",                        		
                       		),
                   array ( nom=>"Carpe gros chien",
                       		kV=>"20",
                       		mAS=>"3",
                       		sec=>"7",                        		
                       		),
                   array ( nom=>"bassin petit chat",
                       		kV=>"60",
                       		mAS=>"7",  
                       		sec=>"8",                      		
                       		)                
                        );
		//		$efface_salle_attente = requetemysql::supr_salle_attente($id_attente);
		//		if(empty($efface_salle_attente)){
		//		throw new Exception("Effaçage de la salle d attente impossible !");
		//		}
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
                        'liste_radio' => $liste_radio,
                        'liste_vetos' => $vetos,
                        'veto' => $_SESSION['login'],
						'historique'	=> $historique,
						'datenaissance'	=> $datenais,
						'tarif'	=> $tarif,
						'tva'	=> 	$tva,
						'restedu' => $restedu,
						'marge_medic'	=> 	$marge_medic,
                        'salle_attente_donnee' => 	$salle_attente,
						'themechargement'	=> 'b',
                        'cas' => 'historique',
                        'consult' => $id_consult,
                        'veto_repartition' => $veto_repartition,
                       'liste_tournures' => ordo_settings_tourn(),
						'liste_cat_delivre' => ordo_settings_cat(),
                        'info_veto' => info_clini(),
					    'textechargement'	=> 'enregistrement en cours'
					    
						)
						);
	}
}
// gestion des ordonnances : les tournures
function ordo_settings_cat(){
	
	$liste_cat_delivre = array("tablette","tablettes","comprimé","compimés","flacon","ml","pipette","pipettes");
	return $liste_cat_delivre;
}
function ordo_settings_tourn(){
	$liste_tournures = array(
			array ( nom=>""
	
			),
			array ( nom=>"Comprimés/gélules",
					seq1=>"Administer",
					defaut=>"p1220",
					nombre=>array (1,1,4,0.25),
					nature=> array ("","comprimé","comprimés","gélule","gélules"),
					rythme=> array ("","par jour",
							"matin et soir",
							"matin, midi et soir"),
					duree=> array ("3 jours", "5 jours", "7 jours" , "10 jours", "15 jours", "1 mois", "3 mois"),
					suite=> array ("","puis 1 jour sur 2","puis 1 jour sur 2, 4 fois","puis 2 fois par semaine")
			),
			array ( nom=>"Solution buvable",
					seq1=>"Administer",
					defaut=>"p1110",
					nombre=>array (0,10,40, 1),
					nature=> array (""," graduations : la dose correspondant au poids","ml","graduations"),
					rythme=> array ("","une fois par jour",
							"matin et soir",
							"matin, midi et soir"),
					duree=> array ("3 jours", "5 jours", "7 jours" , "10 jours", "15 jours", "1 mois", "3 mois"),
					suite=> array ("","puis 1 jour sur 2","puis 1 jour sur 2, 4 fois","puis 2 fois par semaine")
			),
			array ( nom=>"collyre",
					seq1=>">Instiller",
					defaut=>"p1220",
					nombre=>array (1,1,3,1),
					nature=> array ("","goutte","giclée"),
					rythme=> array ("","par jour",
							"matin et soir",
							"matin, midi et soir",
							"toutes les 2 heures"),
					duree=> array ("3 jours", "5 jours", "7 jours" , "10 jours", "15 jours", "1 mois", "3 mois"),
					suite=> array ("","puis 1 jour sur 2","puis 1 jour sur 2, 4 fois","puis 2 fois par semaine")
	
			),
			array ( nom=>"suspension auriculaire",
					seq1=>"Instiller",
					defaut=>"p2130",
					nombre=>array (1,1,3,1),
					nature=> array ("","goutte","giclée"),
					rythme=> array ("","par jour",
							"matin et soir",
							"matin, midi et soir",
							"toutes les 2 heures"),
					duree=> array ("3 jours", "5 jours", "7 jours" , "10 jours", "15 jours", "1 mois", "3 mois"),
					suite=> array ("","puis 1 jour sur 2","puis 1 jour sur 2, 4 fois","puis 2 fois par semaine")
	
			),
			array ( nom=>"shampoing",
					seq1=>"Mouiller, shampoiner, laisser agir 5 minutes et rincer",
					defaut=>"310",
					rythme=> array ("","tous les jours",
							"un jour sur deux",
							"une fois par semaine",
							"tous les 15 jours",
							"tous les mois"),
					duree=> array ("15 jours", "1 mois", "3 mois"),
					suite=> array ("","puis une fois par mois","puis tous les 15 jours")
	
			),
			array ( nom=>"friction",
					defaut=>"1p1242",
					dilution1=>array ("Diluer",""),
					dilution2=>array (0,10,50,2),
					nature=> array ("","ml"),
					dilution=> array ("","dans un litre d'eau", "dans 1/2 litre d'eau", "dans un fond de verre d'eau"),
					seq1=>"Frictionner l'animal",
					rythme=> array ("","tous les jours",
							"matin et soir",
							"un jour sur deux",
							"deux fois par semaine",
							"une fois par semaine",
							"tous les 15 jours",
							"tous les mois"),
					duree=> array ("7 jours.","15 jours.", "1 mois.", "3 mois.")
	
	
			),
			array ( nom=>"injection",
					defaut=>"p112",
					seq1=>"Injecter",
					nombre=>array (0,0,40,1),
					nature=> array ("","ml","graduations"),
					rythme=> array ("","par jour",
							"matin et soir",
							"un jour sur deux"),
					duree=> array ("3 jours.", "5 jours.", "7 jours." , "10 jours.", "15 jours.", "1 mois.", "3 mois.")
	
			),
			array ( nom=>"spot-on",
					defaut=>"3",
					seq1=>"Instiller entre les épaules la quantité de la pipette",
					rythme=> array ("","toutes les semaines.",
							"tous les 15 jours.",
							"tous les mois.",
							"tous les 2 mois.")
			),
	);
	return $liste_tournures;
}
// fin de la gestion des ordonnances
function info_clini(){
	
	$info_veto = requetemysql::info_veterinaire(array('login'=>$_SESSION['login']));
	if(empty($info_veto)){
		throw new Exception("Aucun animal dans la base de donnée !");
	}
	return $info_veto;
}

?>