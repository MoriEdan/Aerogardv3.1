<?php 
/* This controller renders the home page */
class Agenda{	
	public function zone_agenda($id_c,$choix){	
			try{
				if($id_c!=0){		
					$animal = requetemysql::findunanimal2(array('id_ani'=>$id_c ));
							if(empty($animal)){
								throw new Exception("Error in findunanimal2 function ! param : id_ani: "+$id_c);
							}
				}else{				
						$animal=json_encode($id_c);
				}
						// liste des vétos du tour de garde
					$vetos = requetemysql::listevetos();	
							if(empty($vetos)){
								throw new Exception("Error in listevetos fonction !");
							}					
					if($choix=="week"){
						render('_agenda',array(
						'title'		=> 'Agenda partagé du tour de garde',
                    	'animal' =>  $animal,
						'liste_vetos' => $vetos
                    		)
						);
					}elseif ($choix=="day"){
						render('_agenda2',array(
						'title'		=> 'Agenda du '+date("d/m/y"),
                    	'animal' =>  $animal,
						'liste_vetos' => $vetos
                    		)
						);
					
					}
			} catch(Exception $e){
				echo $e->getMessage();
			}
	}
}?>