<?php 
/* This controller renders the home page */
class Gestion{	
	public function gestion_admin(){
		try{
				$tour = requetemysql::tour_dispo(array('tour'=>'' ));
				if(empty($tour)){
					throw new Exception("Error in tour_dispo function ! param : tour: '' ");
				}
				
				$membre = requetemysql::membre(array('tour'=>''));
				if(empty($membre)){
					throw new Exception("Error in membre function ! param : tour: '' ");
				}
				
				$membre_supr = requetemysql::membre_supr(array('delete'=>''));
				if(empty($membre_supr)){
					throw new Exception("Error in membre_supr function ! param : delete: '' ");
				}
				
				render('_gestion_prog',array(
						'title'		=> TXT_GESTION_PROG_CONTROLLER_USERMANAGEMENT,
						'tour'	=> 	$tour,
						'membre'	=> $membre,
						'membre_sup'	=> 	$membre_supr
						));
				
				} catch(Exception $e){
					echo $e->getMessage();
				}
				
			}
	}
?>

	
	
	