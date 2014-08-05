<?php
/* il faut demarrer la session*/
session_start();
require_once "../config.php";
require_once "../connexionmysql.php";

// pour importer sa base de donnee
// il faut créer la table acteune colonne acte : en text et medicament
// et uriliser ce programme qui prend du temps : 100requettes toutes les minutes...
//

function creation_array($item)
{
	$mon_array_conteneur_conteneur = array();
	$mon_array_conteneur = array();
	$array_liste = explode("/", $item['detail']);
		if(count($array_liste)>1){
		
		foreach ($array_liste as $key2 => $value) {
		if($value!=''){
			
			$mon_array_ligne = array();
		    $separation_ligne1 = explode(" _", $value);
		    $mon_array_ligne['nom'] = $separation_ligne1[0];
		    $mon_array_ligne['prix_unitaire'] = $separation_ligne1[1];
		    $mon_array_ligne['remise'] = 0;
		    $mon_array_ligne['quantite'] = 1;
		    $mon_array_ligne['prix_total'] = $separation_ligne1[1];
		    $mon_array_ligne['id_select'] = $key2;
		    $mon_array_ligne['ma_date'] = $item['formatted_date'];
		   	   

		    array_push ( $mon_array_conteneur, $mon_array_ligne);
		   
		     
		}
		}
		
	}
	 array_push ( $mon_array_conteneur_conteneur, $mon_array_conteneur, $item['id']);
	 return $mon_array_conteneur_conteneur;
}
 
global $db;
$st = $db->prepare("SELECT id, FROM_UNIXTIME(date/1000,'%d/%m/%Y') AS formatted_date, detail FROM  facturation where veto='LABADIE' order by id asc");
$st->execute();	
$mon_array_debut = $st->fetchAll();
$mon_array = array();
$mon_array = array_map('creation_array', $mon_array_debut);
 echo var_dump($mon_array);
foreach($mon_array as $value){

	 $st2 = $db->prepare("UPDATE `aerogard2`.`facturation` SET acte='".json_encode($value[0])."' where id='$value[1]' limit 1;");
     $st2->execute();

   }
?>