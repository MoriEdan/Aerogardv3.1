<?php 
/* This controller renders the home page */
class Modificationmembre{	
	public function find(){
				$mes_infos = requetemysql::mes_infos();
				if(empty($mes_infos)){
				throw new Exception("Error in mes_infos function, no param");
				}
				$info_tour = requetemysql::info_tour(array('login'=>$_SESSION['tour']));
				if(empty($info_tour)){
					throw new Exception("Aucun tour dans la base de donnée !");
				}
				$info_tour_array = json_decode($info_tour,true);
				$garde_dispo_affichage = 0;	
				$garde_dispo = array();
				$parti_deco = json_decode($info_tour_array[0]['participant']);
				foreach( $parti_deco as $obj )
				{
				  if($obj->login==$_SESSION['login2']){
						$garde_dispo_affichage = 1;
						$garde_dispo_new = array(login => $obj->login, jour_evi => $obj->jour_evi, jour_evi2 => $obj->jour_evi2);
						array_push($garde_dispo, $garde_dispo_new);
					}
				}
								
				$info_tour_array[0]['participant'] = json_encode($garde_dispo);
				$garde_vac = array();
				$vac_deco = json_decode($info_tour_array[0]['vacances']);
				foreach( $vac_deco as $obj )
				{
					if($obj->login==$_SESSION['login2']){
						array_push($garde_vac, $obj);
					}
				}
		//		while (list($key, $value) = each($info_tour_array[0]['vacances']))
		//		{
		//			if($value['login']==$_SESSION['login2']){
		//				array_push($garde_vac, $value);
		//			}
		//		}
				$info_tour_array[0]['vacances'] = json_encode($garde_vac);
				$info_tour_array[0]['importance'] = '[]';
				$info_tour_array[0]['liaison'] = '[]';			
				$info_tour_array=json_encode($info_tour_array);
				$mes_specialites = requetemysql::mes_specialites();
				if(empty($mes_specialites)){
				throw new Exception("Error in mes_specialites function, no param");
				}
				$tarif = requetemysql::info_tarif();
				if(empty($tarif)){
				throw new Exception("Error in info_tarif function, no param");
				}
				$tarif = json_decode($tarif);
				$tarif2 = requetemysql::info_tarif2();
				while (list($key_paiement, $value_paiement) = each($tarif)) 
					{ 
					$tarif[$key_paiement]->id_select = $key_paiement;	
					}
				$tarif=json_encode($tarif);
				if(empty($tarif2)){
					throw new Exception("Error in info_tarif2 function, no param");
				}
				$tarif2 = json_decode($tarif2);
				while (list($key_paiement, $value_paiement) = each($tarif2))
				{
					$tarif2[$key_paiement]->id_select = $key_paiement;
				}
				$tarif2=json_encode($tarif2);
				$tarif_medoc = requetemysql::info_tarif_medoc();
				if(empty($tarif_medoc)){
					throw new Exception("Error in info_tarif_medoc function, no param");
				}
				$tarif_medoc = json_decode($tarif_medoc);
				while (list($key_medoc, $value_medoc) = each($tarif_medoc))
				{
					$tarif_medoc[$key_medoc]->id_select = $key_medoc;
				}
				$tarif_medoc=json_encode($tarif_medoc);				
				$liste_specialite = array(
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_GENERALSURGERY
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_BEHAVIOR
                       		),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_ULTRASOUND
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_ULTRASOUNDHEART
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_ENDOSCOPY
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_HORSE
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_INTERNALMEDICINE
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_NEWPET
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_OPHTALMOLOGY
						),
					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_ORTHOPEDY
						),
                   	array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_SCANNER
                       		)                       
                        );
                   $liste_conduite = array(
 					array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_RETURNTOME
                       		),
                   	array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTNOPB
                       		),
                    array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTFORSURGERY
                       		),
                    array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTFORRADIOGRAPHY
                    		),                    
                    array ( nom=>TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTIFMOREPERSONNALNEED
                       		)                        
                        );  
             
                        render('_modificationmembre',array(
						'title'		=> TXT_MODIFICATIONMEMBRE_CONTROLLER_ABOUTME,
						'mes_infos'	=> 	$mes_infos,
						'liste_specialite'	=> $liste_specialite,
						'liste_conduite'	=> $liste_conduite,
                        'tarif'	=> $tarif,
                        'tarif2'=> $tarif2,
                        'mes_specialite'=> $mes_specialites,
                        'tarif_medoc' => $tarif_medoc,
                        'garde_dispo_affichage'=> $garde_dispo_affichage,
                        'info_tour_array' => $info_tour_array
							));
	}
}?>