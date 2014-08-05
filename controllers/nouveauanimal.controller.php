<?php 
/* This controller renders the home page */
class NouveauAnimal{
	public function find($id_pro,$id_ani, $origin, $id_salle_attente, $valeur_attente){		
		try{		
			$animal = requetemysql::findunanimal(array('id_pro'=>$id_pro, 'id_ani'=>$id_ani ));
			if(empty($animal)){
				throw new Exception("Error in findunanimal function ! param id_pro "+$id_pro+" id_ani"+$id_ani);
			}		
			$client = requetemysql::findunclient(array('id'=>$id_pro ));
			if(empty($client)){
				throw new Exception("Error in findunclient function ! param id "+$id_pro);
			}		
			$race = array(TXT_NOUVEAUANIMAL_CONTROLLER_DOG,TXT_NOUVEAUANIMAL_CONTROLLER_CAT,TXT_NOUVEAUANIMAL_CONTROLLER_OTHER);
			if($id_ani==0){
				$datenais=time()*1000;
			}else{
			$animal2 = json_decode($animal, true);
 			$datenais = $animal2[0]['datenais'];			
			}
			
			render('_nouveauanimal',array(
			'title'		=> TXT_NOUVEAUANIMAL_CONTROLLER_TITLE,
			'id_pro'	=> 	$id_pro,
			'id_ani'	=> 	$id_ani,
			'animal'	=> $animal,
			'client'	=> $client,
			'origin'	=> $origin,
			'valeur_attente' => $valeur_attente,
			'datenaissance'	=> $datenais,
			'id_salle_attente' => $id_salle_attente,
			'themechargement'	=> 'b',
		    'textechargement'	=> TXT_NOUVEAUANIMAL_CONTROLLER_DOWNLOADINPROGRESS,
		    'race' => $race
			)
			);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
}?>