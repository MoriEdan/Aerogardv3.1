<?php 
/* This controller renders the home page */
class NouveauClient{
	public function find($id_pro, $origin, $id_salleattente, $valeur_attente){
		try{				
			$client = requetemysql::findunclient(array('id'=>$id_pro ));
			if(empty($client)){
				throw new Exception("Error in findunclient function ! param id "+$id_pro);
			}		
			$vetos = requetemysql::listevetos2();	
				if(empty($vetos)){
					throw new Exception("Error in listevetos function no param");
				}
			render('_nouveauclient',array(
			'title'		=> TXT_NOUVEAUCLIENT_CONTROLLER_USERMANAGEMENT,
			'id_pro'	=> 	$id_pro,
			'client'	=> $client,
			'origin'	=> $origin,
			'valeur_attente' => $valeur_attente,
			'vetos'	=> $vetos,
			'idani'	=> $idani,
			'idsalleattente'	=> $id_salleattente,
			'themechargement'	=> 'b',
		    'textechargement'	=> TXT_NOUVEAUCLIENT_CONTROLLER_DOWNLOADINPROGRESS
			)
			);
		}catch(Exception $e){
				echo $e->getMessage();
		}
	}
}?>