<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";

// pour mettre à jour les base de données médicaments : vider la table medicament
// ouvrir produits.php dans txt et garder les balises de début :
// <?php $xmlstr = <<<XML
// et de fin
// XML; (pointinterrogation)>
// changer le xml
// executer cette page




include '../txt/produits.php';
try {
$st = $db->prepare("INSERT INTO `medicament` ( nom, centrale, cip, prixht, lot ) VALUES ( :nom, :centrale, :cip, :prixht, '');", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$medicaments = new SimpleXMLElement($xmlstr);

	/* Pour chaque <character>, nous affichons un <name>. */
	foreach ($medicaments->produits->produit as $medic) {
		if ((string) $medic->liblabo != 'MATERIEL' && (string) $medic->liblabo != 'DIVERS' &&  ((string) $medic->categ == '1' ||  (string) $medic->categ == '2')) {
		     $st->execute(array(':nom' => $medic->libelle, ':centrale' => $medic->produitID, ':cip' => $medic->cip, ':prixht' => $medic->prixhteuro));	
				//echo $medic->libelle. "<br />";
		}
	  
	}
} catch (PDOException $e) {
        die($e->getMessage());
 }
?>