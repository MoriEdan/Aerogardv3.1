<?php 
class Tourdegarde{
	public function zone_garde(){
		$info_tour = requetemysql::info_tour(array('login'=>$_SESSION['login2']));
		if(empty($info_tour)){
			throw new Exception("Aucun tour dans la base de donnée !");
		}		
		$liste_jour = array(
				array ( nom=>"lundi",
						valeur=>1,
				),
				array ( nom=>"mardi",
						valeur=>2,
				),
				array ( nom=>"mercredi",
						valeur=>3,
				),
				array ( nom=>"jeudi",
						valeur=>4,
				),
				array ( nom=>"vendredi",
						valeur=>5,
				),
				array ( nom=>"samedi",
						valeur=>6,
				),
				array ( nom=>"dimanche",
						valeur=>0,
				)
		);
		$recherche_tot_garde = array(
				array ( nom=>"points total",
						valeur=>0,
				),
				array ( nom=>"nuit semaine",
						valeur=>1,
				),
				array ( nom=>"nuit week-end",
						valeur=>2,
				),
				array ( nom=>"jour week-end",
						valeur=>3,
				)
		);
		$liste_moment = array(
				array ( nom=>"nuit",
						valeur=> array( debut => 19, fin => 8),
						commentaire=>"nuit : 19h - 8h(J+1)",
						nuit=>"oui",
						temps=>13,
				),
				array ( nom=>"après-midi",
						valeur=> array( debut => 14, fin => 19),
						commentaire=>"après-midi : 14h - 19h",
						nuit=>"non",
						temps=>7,
				),
				array ( nom=>"journée",
						valeur=> array( debut => 8, fin => 19),
						commentaire=>"journée : 8h - 19h",
						nuit=>"non",
						temps=>11,
				),
				array ( nom=>"matinée",
						valeur=> array( debut => 8, fin => 12),
						commentaire=>"matinée : 8h - 12h",
						nuit=>"non",
						temps=>4,
				),				
				array ( nom=>"soirée",
						valeur=> array( debut => 19, fin => 23),
						commentaire=>"soirée : 19h - 23h",
						nuit=>"non",
						temps=>4,
				),				
				array ( nom=>"aprem+nuit",
						valeur=> array( debut => 12, fin => 8),
						commentaire=>"aprem+nuit : 12h - 8h(J+1)",
						nuit=>"oui",
						temps=>20,
				)
		);
		$liste_cat_planning = array(
					array ( nom=>"garde",
							valeur=> 0,
					),array ( nom=>"astreinte",
							valeur=> 1	,					
					)			
				);
		
		$liste_equipe = array(
				array ( nom=>"1 véto de garde et 1 d'astreinte",
						valeur=> array( garde => 1, astreinte => 1)
				),
				array ( nom=>"1 véto de garde",
						valeur=> array( garde => 1, astreinte => 0)						
				),				
				array ( nom=>"1 véto d'astreinte",
						valeur=> array( garde => 0, astreinte => 1)	
				),
				array ( nom=>"2 vétos de garde et 1 d'astreinte",
						valeur=> array( garde => 2, astreinte => 1)	
				),
				array ( nom=>"2 vétos de garde et 0 d'astreinte",
						valeur=> array( garde => 2, astreinte => 0)	
				)
		);
		$liste_rythme = array(
				array ( nom=>"1 sem/2",
						mon_index=>0,
						valeur=> array( rythme => 1, base => 2)
				),
				array ( nom=>"1 sem/4",
						mon_index=>1,
						valeur=> array( rythme => 1, base => 4)
				),
				array ( nom=>"toutes les semaines",
						mon_index=>2,
						valeur=> array( rythme => 1, base => 1)
				),				
				array ( nom=>"1 sem/3",
						mon_index=>3,
						valeur=> array( rythme => 1, base => 3)
				),				
				array ( nom=>"1 sem/5",
						mon_index=>4,
						valeur=> array( rythme => 1, base => 5)
				)
		);
			$vetos = requetemysql::listevetos();	
			if(empty($vetos)){
				throw new Exception("Pas de vetos dans la base");
			}
		
		
		render('_tourdegarde',array(
				'title'		=> "Tour de garde: zone de gestion",
				'liste_moment' => $liste_moment,
				'liste_jour' => $liste_jour,
				'liste_equipe' => $liste_equipe,
				'liste_membre' => $vetos,
				'liste_rythme' => $liste_rythme,
				'liste_cat_planning' => $liste_cat_planning,
				'info_tour' => $info_tour,
				'recherche_tot_garde' => $recherche_tot_garde
				
				)
		);
	}	
}
?>