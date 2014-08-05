<?php 
/* This controller renders the home page */

class Identification{
	public function handleRequest(){		
	
		$liste_choix = array(
					array ( nom=>TXT_IDENTIFICATION_CONTROLLER_LIST_CHOICE1, cas =>2
                       		),
 					array ( nom=>TXT_IDENTIFICATION_CONTROLLER_LIST_CHOICE2, cas=>1
                       		),
                    array ( nom=>TXT_IDENTIFICATION_CONTROLLER_LIST_CHOICE3, cas =>3
                       		)                    
                        );
		render('_identification',array(
			'liste_choix' => $liste_choix,
			'title'		=> TXT_IDENTIFICATION_CONTROLLER_TITTLE,
			'action'	=> TXT_IDENTIFICATION_CONTROLLER_ACTION,
			'themechargement'	=> 'b',
		    'textechargement'	=> TXT_IDENTIFICATION_CONTROLLER_DOWNLOAD_TEXT
		));
	}
}

?>