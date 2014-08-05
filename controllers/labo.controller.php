<?php 

class Labo{
	public function test(){
		$info_veto = requetemysql::info_veterinaire(array('login'=>$_SESSION['login']));
		if(empty($info_veto)){
			throw new Exception("Aucun animal dans la base de donnée !");
		}
		$liste_cat_delivre = array("tablette","tablettes","comprimé","compimés","flacon","ml","pipette","pipettes");		
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
		
		
		render('_labo',array(
				'title'		=> "Laboratoire d'Aerogard, zone de test et de recherche...",
				'liste_tournures' => $liste_tournures,
				'liste_cat_delivre' => $liste_cat_delivre,
				'info_veto' => $info_veto				
				)
		);
	}	
}
?>