<?php 
/* This controller renders the home page */
// pour imprimer selon les cartes MSD de 20*9
//imprimer en enveloppe japonaise N 4 
// carte à droite , la face image vers le haut, le chien (gauche) à l avant
class Reglage{	
	//public function zone_reglage($tva){	
	public function zone_reglage(){
					// hour beginning and finish for search in the database
					$heure_ref = 12;
					$info_veto = requetemysql::info_veterinaire(array('login'=>$_SESSION['login']));
						if(empty($info_veto)){
							throw new Exception("Aucun animal dans la base de donnée !");
						}
						// liste des vétos du tour de garde
					$vetos = requetemysql::listevetos();	
							if(empty($vetos)){
								throw new Exception("Pas de vetos dans la base");
							}
					// remplassement dans les courrier des mots clées par des variables
					$data_mot = array(1 => "animal", 2=> "nom_chien", 3=> "date_rappel", 4=> "clinique_veto");					
			
					// réglage du mois et de l'annee du livre des recettes directement dans _reglage.php à cause de la compatibilité de la datebox en mode custom
                     $texte_rappel = array(
		 					array ( nom=>"vaccin",
		                       		texte=>"        Madame, Monsieur, toute vaccination nécessite des injections de rappel pour rester efficace. Pour assurer la protection de votre animal : nom_chien, la prochaine injection devra être pratiquée vers le date_rappel par le vétérinaire de votre choix.\nPensez à vermifuger votre animal une semaine avant ou après la visite de vaccination.\n \n Sincères salutations",
		                       		),
		                   	array ( nom=>"anovulatoire",
		                       		texte=>"        Madame, Monsieur, pour assurer la protection de votre animal : nom_chien, la prochaine injection anti-chaleur devra être pratiquée au plus tard le date_rappel par le vétérinaire de votre choix.\n \n Sincères salutations.",
		                       		),
		                    array ( nom=>"vermifuge",
		                       		texte=>"        Madame, Monsieur, pour assurer la protection de votre animal : nom_chien, le prochain traitement vermifuge devra être pratiqué vers le date_rappel.\n\n Sincères salutations.",
		                       		),
		                    array ( nom=>"vermifuge",
		                       		texte=>"        Madame, Monsieur, pour assurer la protection de votre animal : nom_chien, le prochain traitement vermifuge devra être pratiqué vers le date_rappel.\n\n Sincères salutations.",
		                       		),
		                    array ( nom=>"puces",
		                       		texte=>"        Madame, Monsieur, pour assurer la protection de votre animal : nom_chien, le prochain traitement anti-parasitaire devra être pratiqué vers le date_rappel.\n\n Sincères salutations",
		                       		),
		                    array ( nom=>"rendezvous",
		                       		texte=>"        Madame, Monsieur, suite à notre dernier entretien, je vous confirme notre consultation du date_rappel. Cette consultation aura lieu à la clinique vétérinaire : clinique_veto\n\n Sincères salutations",
		                       		),
		                    array ( nom=>"impaye",
		                       		texte=>"Objet : retard de paiement de facture \n        Madame, Monsieur, sauf erreur ou omission de notre part, nous constatons que votre compte client présente à ce jour un solde débiteur. Ce débit correspond à nos factures suivantes restées impayées. L’échéance étant dépassée, nous vous demandons de bien vouloir régulariser cette situation par retour de courrier. Dans le cas où votre règlement aurait été adressé entre temps, nous vous prions de ne pas tenir compte de la présente. \n\n Sincères salutations",
		                       		),
		                    array ( nom=>"pied_de_page",
		                       		texte=>"Pensez à prendre rendez-vous au 04.67.31.63.51. N\'oubliez pas votre livret de santé.",
		                       		),                       
                        );
						
						render('_reglage',array(
						'title'		=> 'Gestion de la clinique',
                    	'info_veto' =>  $info_veto,
						'texte_rappel' =>  $texte_rappel,
						'data_mot' => $data_mot,
						'heure_ref' => $heure_ref,
						'liste_vetos' => $vetos,
                    	//'tva'  =>  $tva
							)
						);
						
	}
}?>