<?php
class requetemysql{	
	/*
		The find static method selects categories
		from the database and returns them as
		an array of Category objects.
	*/
	
	   
	public static function findclient($arr){
		try{
			global $db;
			if($arr=="client"){
				$st = $db->prepare("select animal.id_p, client.nom, client.prenom, client.id2, client.ville, client.variable, animal.id, animal.nom_a, animal.espece, animal.sexe, animal.race, animal.variable2, animal.mort, animal.datenais FROM animal inner JOIN client ON client.id2=animal.id_p where (client.permission2='".$_SESSION['login']."' and animal.permission='".$_SESSION['login']."') order by client.nom ASC");
				$st->execute();	
				return json_encode($st->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP), JSON_FORCE_OBJECT);
			}else if($arr=="ax"){
				$st = $db->prepare("select animal.id_p, client.nom, client.prenom, client.id2, client.ville, client.variable, animal.id, animal.nom_a, animal.espece, animal.sexe, animal.race, animal.variable2, animal.mort, animal.datenais FROM animal inner JOIN client ON client.id2=animal.id_p where (client.permission2='".$_SESSION['login']."' and animal.permission='".$_SESSION['login']."') order by animal.nom_a ASC");
				$st->execute();	
				return json_encode($st->fetchAll());		
			}
			//else if($arr['id']){
			//	$st = $db->prepare("select client.nom, client.prenom, client.ville, client.variable, animal.id, animal.id_p, animal.nom_a, animal.espece, animal.sexe, animal.race, animal.variable2, animal.mort, animal.datenais FROM animal inner JOIN client ON client.id2=animal.id_p where (client.permission2='$data' and animal.permission='$data') order by client.nom ASC");
			//}
			//else{
			//	throw new Exception("Unsupported property!");
			//}
		} catch(Exception $e){
			return '';
		}
		
		
	}
	public static function findunclient($arr){
		try{
			if($arr['id']==0){
			return json_encode($arr['id']);			
			}else{
			global $db;
			$st = $db->prepare("select id2, nom, prenom, adresse, code, ville, tel1, tel2, mail, envoimail, variable, variable3, variable4, ref from client where id2='".$arr['id']."' and permission2='".$_SESSION['login']."' order by id2 desc limit 1");
			$st->execute();	
			return json_encode($st->fetchAll());		
			}	
		} catch(Exception $e){
			return '';
		}			
		
		
	}
	// recherche des coordonnees d un client dans le cadre de la lecture d un rapport 
	public static function findunclient2($arr){
		
		if($arr['id']==0){
		return json_encode($arr['id']);			
		}else{
		global $db;
		$st = $db->prepare("select id2, nom, prenom, adresse, code, ville, tel1, tel2, mail, envoimail, variable, variable3, variable4, ref from client where id2='".$arr['id']."' order by id2 desc limit 1");
		$st->execute();	
		return json_encode($st->fetchAll());		
		}				
		
		
	}
	public static function findunanimal($arr){	
		try{
			if($arr['id_ani']==0){
			return json_encode($arr['id_ani']);		
			}else{
			global $db;
			$st = $db->prepare("select nom_a, espece, sexe, race, datenais, num_t, num_p, num_pa, mort, variable2, repro from animal where id='".$arr['id_ani']."' order by id desc limit 1");
			$st->execute();	
			return json_encode($st->fetchAll());			
			}
		} catch(Exception $e){
			return '';
		}
	}
	public static function findwhodelete($arr){
		try{
			
				global $db;
				$st = $db->prepare("select DATE_FORMAT(date, '%d/%m/%Y %H:%i') as ma_date, login from salle_attente_supr where id_s='".$arr['id_s']."' and permission = '".$_SESSION['login']."' order by id desc limit 1");
				$st->execute();
				return json_encode($st->fetchAll());
			
		} catch(Exception $e){
			return '';
		}
	}
	public static function historique($arr){
		global $db;
		$st = $db->prepare("select c.id AS id, c.date AS date, c.motif AS motif, c.resume AS resume,
		c.permission2 AS permission2, c.temperature AS temperature, c.poids AS poids, c.freq_cardiaque AS freq_cardiaque, 
		f.acte AS acte, f.medic AS medic, f.totalttc AS totalttc, f.reglementttc AS reglementttc,
		p.id_fac AS id_fac, p.montant AS montant, p.mode AS mode, DATE_FORMAT(FROM_UNIXTIME(f.date/1000), '%d/%m/%Y') AS date_p
		FROM consultation AS c
		JOIN facturation AS f ON c.id=f.id_c
		LEFT JOIN paiement AS p ON f.id=p.id_fac
		WHERE (c.permission='".$_SESSION['login']."' and c.id_c='".$arr['id']."') order by c.date DESC LIMIT 200");
		$st->execute();	
		//return json_encode($st->fetchAll());	
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
				$entries = array();
				//$ma_var2 = array();
				while (list($key_row, $value_row) = each($ma_var)) 					{  
					
							if (!isset($entries[$value_row['id_fac']])) { 						
								
								$entries[$value_row['id_fac']] = $key_row;
								if (!isset($ma_var[$key_row]['montant'])) {
								//	$ma_var[$key_row]['paiement_historique'][]=array();			
								}else{
									$ma_var[$key_row]['paiement_historique'][]=array('montant' => $ma_var[$key_row]['montant'],'mode' => $ma_var[$key_row]['mode'],'date_p' => $ma_var[$key_row]['date_p']);
								}		
							}else{
								if (!isset($ma_var[$key_row]['montant'])) {
									
								}else{
									$ma_var[$entries[$value_row['id_fac']]]['paiement_historique'][] = array('montant' => $ma_var[$key_row]['montant'],'mode' => $ma_var[$key_row]['mode'],'date_p' => $ma_var[$key_row]['date_p']);
								}
								//unset($ma_var[$key_row]);
							
							}
					
						 //$ma_var[$key_row]['commentaire']=""; 
						 //$ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_con']; 
					}	
		
		$st->closeCursor();
		return json_encode($ma_var);			
		}
	public static function info_tarif(){
		try{
			global $db;
			$st = $db->prepare("select acte, tarifttc, intitule,taille from tarif where permission='".$_SESSION['login']."' order by acte ASC");
			$st->execute();	
			return json_encode($st->fetchAll());
		} catch(Exception $e){
			return '';
		}
	
	}
	// recherche des droits acceder à une consultation
	public static function recherche_consult($arr){
		global $db;
		$st = $db->prepare("select date from consultation where id='$arr' and permission='".$_SESSION['login']."'");
		$st->execute();
		return json_encode($st->fetchAll());
		
	}
	public static function info_tarif2(){
		try{
			global $db;
			$st = $db->prepare("select acte, tarifttc, intitule from tarif2 where permission='".$_SESSION['login']."' order by acte ASC ");
			$st->execute();
			return json_encode($st->fetchAll());
		} catch(Exception $e){
			return '';
		}
	
	}
	public static function info_tarif_medoc(){
		try{
			global $db;
			$st = $db->prepare("select nom, prixht, lot, centrale from medicament where permission='".$_SESSION['login']."' order by nom ASC ");
			$st->execute();
			return json_encode($st->fetchAll());
		} catch(Exception $e){
			return '';
		}
	
	}
	public static function info_veterinaire($arr){
		global $db;
		$st3 = $db->prepare("select login, nom, tel, adresse, code, commune, ordre, siret, num_tva, tva, marge from identification where login='".$arr['login']."' order by id desc limit 1");
		$st3->execute();	
		return json_encode($st3->fetchAll());
	
	}
	// recupère les paramètres du tour de garde pour la section tour de garde
	public static function info_tour($arr){
		try{
			global $db;
			$st3 = $db->prepare("select id, horaire, participant, liaison, vacances, importance, envoi_mail, jour from tourdegarde2 where tour='".$arr['login']."' order by id desc limit 1");
			$st3->execute();
			return json_encode($st3->fetchAll());
		} catch(Exception $e){
			return '';
		}
	
	}
	// récupère les paramètre pour l'accueil
	public static function info_tour2($arr){
		try{
			global $db;
			$st3 = $db->prepare("select id, envoi_mail, jour from tourdegarde2 where tour='".$arr['login']."' order by id desc limit 1");
			$st3->execute();
			return json_encode($st3->fetchAll());
		} catch(Exception $e){
			return '';
		}
	
	}
	// gestion de l'affichage texte : retrait / \  , et gestion apostrophe
	public static function gestion_string_ok($arr){
		return utf8_decode(ucfirst(stripslashes($arr)));
	}
	// gestion de l'affichage texte : retrait / \  , première lettre majuscule, et gestion apostrophe
	public static function gestion_string_maj($arr){
			return utf8_decode(ucfirst(strtolower(stripslashes($arr))));	
	}
// gestion de l'affichage texte : retrait / \  , tout minuscule, et gestion apostrophe
	public static function gestion_string_norm($arr){
			return utf8_decode(strtolower(stripslashes($arr)));	
	}
	public static function recup_medic($arr){
		global $db;
		$st = $db->prepare("select id, nom, centrale, cip, prixht, lot, permission from medicament where nom LIKE '%".$arr['nom']."%' and (permission='".$_SESSION['login']."' or permission='tous') order by nom ASC");
		$st->execute();	
		return json_encode($st->fetchAll());	
	}
	public static function recup_acte($arr){
		global $db;
		$st = $db->prepare("select id, acte, tarifttc from tarif2 where acte LIKE '%".$arr['nom']."%' AND permission='".$_SESSION['login']."' order by id ASC");
		$st->execute();
		return json_encode($st->fetchAll());
	}
	public static function restedu($arr){
		global $db;
		$st = $db->prepare("select consultation.id, consultation.id_c, consultation.date, facturation.totalttc, facturation.reglementttc, animal.nom_a FROM consultation inner JOIN facturation ON consultation.id=facturation.id_c inner JOIN animal ON animal.id=consultation.id_c where (consultation.permission='".$_SESSION['login']."' and facturation.veto='".$_SESSION['login']."' and facturation.totalttc!=facturation.reglementttc and animal.id_p='".$arr['id_pro']."') order by consultation.date DESC LIMIT 200");
		$st->execute();	
		return json_encode($st->fetchAll());			
		}
	// fonction de recherche dans la bdd concernant la salle attente
	// recherche general : recherche partielle nécéssaire dans accueil.php
	// total : necessary for nouvelleconsultation.controller.php : query the totality of the line
	public static function salle_attente($arr){
		try{
			if($arr=="general"){
			global $db;
			$st = $db->prepare("select id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume from salle_attente5 where permission='".$_SESSION['login']."' order by id desc limit 15");
			$st->execute();	
			return json_encode($st->fetchAll());	
			}else{
			global $db;
			$st = $db->prepare("SELECT id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, poids, temp, freq_car, clinique, relance, rage1, rage2, rage3, rage4, DATE_FORMAT(rage5, '%d/%m/%Y') AS formatted_date2, pass1, pass2, pass3, analyse1, analyse2, radio1 AS radio, radio2, acte, medic, paiement, permission, puce, tatouage FROM  salle_attente5 where id = '$arr' and permission='".$_SESSION['login']."' order by id desc limit 1");
			$st->execute();	
			return json_encode($st->fetchAll());			
			}	
		} catch(Exception $e){
			return '';
		}	
	}
	public static function supr_salle_attente($arr, $nom_a, $nom_p){
		global $db;	
		
		$sql = "INSERT INTO `salle_attente_supr`(`id`, `id_s`, `date`, `nom_c`, `nom_a`, `login`, `permission`) 
				VALUES ('' , :id_s, NOW(), :nom_p, :nom_a, :login, :permission)";
		$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':permission' => $_SESSION['login'], 
				':login' => $_SESSION['login2'],
				':nom_a' => $nom_a,
				':nom_p' => $nom_p,
				':id_s' => $arr));
		$id = $db->lastInsertId();
		$sth->closeCursor();
		$id = $id - 30;
		$st = $db->prepare("DELETE FROM `aerogard2`.`salle_attente_supr` where id<'$id'");
		$st->execute();
		//mysql_query("DELETE FROM salle_attente_supr WHERE id<'$id'");			
		$st = $db->prepare("DELETE FROM `aerogard2`.`salle_attente5` where id='$arr'");
		$st->execute();	
		return json_encode("ok");
	}
	
	// fonction de recherche dans la bdd concernant les rapports reçus
	// recherche general : recherche partielle nécéssaire dans accueil.php
	// total : necessary for nouvelleconsultation.controller.php : query the totality of the line
	public static function rapport_ref($arr){
		try{
			if($arr=="general"){
			global $db;
			$st = $db->prepare("select id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, veto_origin from rapport_ref where permission='".$_SESSION['login']."' order by id");
			$st->execute();	
			return json_encode($st->fetchAll());	
			}else{
			global $db;
			$st = $db->prepare("SELECT id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, poids, temp, freq_car, clinique, relance, rage1, rage2, rage3, rage4, DATE_FORMAT(rage5, '%d/%m/%Y') AS formatted_date2, pass1, pass2, pass3, analyse1, analyse2, radio1 AS radio, radio2, acte, medic, paiement, permission, puce, tatouage, num_consult, veto_origin FROM  rapport_ref where id = '$arr' and permission='".$_SESSION['login']."' order by id desc limit 1");
			$st->execute();	
			return json_encode($st->fetchAll());			
			}		
		} catch(Exception $e){
			return '';
		}
	}
	
	// fonction de recherche dans la bdd concernant les rapports rédigés
	// recherche general : recherche partielle nécéssaire dans accueil.php
	// total : necessary for nouvelleconsultation.controller.php : query the totality of the line
	public static function rapport_redige($arr){
		try{
			if($arr=="general"){
			global $db;
			$st = $db->prepare("select id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, permission  from rapport_ref where veto_origin='".$_SESSION['login']."' order by id");
			$st->execute();	
			return json_encode($st->fetchAll());	
			}else{
			global $db;
			$st = $db->prepare("SELECT id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, poids, temp, freq_car, clinique, relance, rage1, rage2, rage3, rage4, DATE_FORMAT(rage5, '%d/%m/%Y') AS formatted_date2, pass1, pass2, pass3, analyse1, analyse2, radio1 AS radio, radio2, acte, medic, paiement, permission, puce, tatouage, num_consult, veto_origin FROM  rapport_ref where id = '$arr' and veto_origin='".$_SESSION['login']."' order by id desc limit 1");
			$st->execute();	
			return json_encode($st->fetchAll());			
			}	
		} catch(Exception $e){
			return '';
		}	
	}
	
	// recherche dans la base des cas refere permission --> celui qui le reçoit
	//veto_origin celui qui l'envoi
	// general : recherche correspondant a accueil
	// else : recherche global pour visualiser le cas
	public static function rapport_refere($arr){
		try{
			if($arr=="general"){
				global $db;
				$st = $db->prepare("select id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, veto_origin from rapport_spe where permission='".$_SESSION['login']."' order by id");
				$st->execute();	
				return json_encode($st->fetchAll());	
			}else{
				global $db;
				$st = $db->prepare("SELECT id, id_pro, id_ani, nom_a, nom_p, DATE_FORMAT(date_con, '%d/%m/%Y') AS formatted_date, resume, poids, temp, freq_car, clinique, relance, rage1, rage2, rage3, rage4, DATE_FORMAT(rage5, '%d/%m/%Y') AS formatted_date2, pass1, pass2, pass3, analyse1, analyse2, radio1 AS radio, radio2, acte, medic, paiement, permission, puce, tatouage, num_consult, veto_origin FROM  rapport_spe where id = '$arr' and permission='".$_SESSION['login']."' order by id desc limit 1");
				$st->execute();	
				return json_encode($st->fetchAll());			
			}	
		} catch(Exception $e){
			return '';
		}	
	}
	public static function supr_rapport_refere($arr){
		global $db;
		$st = $db->prepare("DELETE FROM `aerogard2`.`rapport_ref` where id='$arr'");
		$st->execute();	
		return json_encode("ok");
	}
	
	
	
	public static function reste_a_payer2($arr){
		global $db;
		$st = $db->prepare("select f.id, f.totalttc, f.reglementttc, f.total_acte, f.reglement_acte
						, f.total_medic, f.reglement_medic FROM facturation AS f 
						JOIN consultation AS c ON c.id=f.id_c
						WHERE (c.permission=:permission and f.veto=:permission 
						and c.id=:id_consult
						and f.totalttc!=f.reglementttc						
						);");		
		$st->execute(array(':id_consult' => $arr['consult'], ':permission' => $_SESSION['login']));	
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();	
		return json_encode($ma_var);
		
	}
	public static function reste_a_payer($arr){
		global $db;
		$st = $db->prepare("select f.id, f.totalttc, f.reglementttc, f.total_medic, f.reglement_medic
						, f.total_acte, f.reglement_acte FROM facturation AS f 
						JOIN consultation AS c ON c.id=f.id_c
						JOIN animal AS a ON a.id=c.id_c
						WHERE (c.permission=:permission and f.veto=:permission and a.permission=:permission
						and a.id_p=:id_proprio
						and f.totalttc!=f.reglementttc						
						) order by f.id DESC LIMIT 200");		
		$st->execute(array(':id_proprio' => $arr['id_proprio'], ':permission' => $_SESSION['login']));	
		return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
	}
	public static function suprimer_consultation($arr){
	global $db;
	// DELETE FROM facturation AS f WHERE (f.veto=:permission and f.id_c = :id_consult);
	$sql = "
	DELETE p,f FROM paiement AS p INNER JOIN facturation AS f ON p.id_fac=f.id INNER JOIN consultation AS c ON c.id=f.id_c WHERE
    c.permission=:permission and f.veto=:permission and p.permission=:permission and c.id = :id_consult;
    DELETE FROM facturation WHERE (veto=:permission and id_c = :id_consult);
    DELETE FROM consultation WHERE (permission=:permission and id = :id_consult);
	DELETE FROM rappel WHERE (permission=:permission	and id_con = :id_consult);	
	DELETE FROM vac_rage WHERE (permission=:permission and consult = :id_consult);	
	DELETE FROM passeport WHERE (permission=:permission and id_consult = :id_consult);
	DELETE FROM radiographie WHERE (permission=:permission and id_consult = :id_consult);
	DELETE FROM echange WHERE (permission=:permission and id_consult = :id_consult);	
	";
		try {
	    $stmt = $db->prepare($sql);
	    $stmt->execute(array(':id_consult' => $arr['consult'], ':permission' => $_SESSION['login']));
	    $stmt->closeCursor();
	    return 'ok';
	    }
		catch (PDOException $e)
		{
	    echo $e->getMessage();
	    die();
		}
	}
	public static function repartition($arr){
		global $db;
		$sql = "select e.veto_desti AS veto_desti, e.montant AS montant
		FROM echange AS e
		WHERE (e.id_consult = :id_consult and e.veto_desti != :permission);";
		$st = $db->prepare($sql);
		$st->execute(array(':id_consult' => $arr['id_consult'], ':permission' => $_SESSION['login2']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		while (list($key_paiement, $value_paiement) = each($ma_var))
		{
			$ma_var[$key_paiement]['id_select'] = $key_paiement;
		}
		return json_encode($ma_var);
		
	}// end repartition
	public static function recup_element_consult($arr){
	global $db;
	$mon_array = array();
	
	//partie consult animal et facturation
	$sql = "select a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS formatted_date
						, c.motif AS resume, c.poids AS poids, c.temperature AS temp
						, c.freq_cardiaque AS freq_car, c.resume AS clinique, c.permission2 as veto_origin
						,a.num_t AS tatouage, a.num_p AS puce,	
						f.acte AS acte, f.medic AS medic						
						FROM consultation AS c 
						JOIN animal AS a ON a.id=c.id_c
						JOIN facturation AS f ON c.id=f.id_c
						WHERE (c.permission=:permission and f.veto=:permission 
						and c.id = :id_consult)
						LIMIT 1;";
	$st = $db->prepare($sql);
	$st->execute(array(':id_consult' => $arr, ':permission' => $_SESSION['login']));
	$ma_var1 = $st->fetchAll(PDO::FETCH_ASSOC);
	$st->closeCursor();
	
	$sql = "select DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y') AS date, p.mode AS mode, p.mode AS mode2, p.montant AS montant, p.numero_cheque AS num_cheque
						FROM consultation AS c 
						JOIN facturation AS f ON c.id=f.id_c
						JOIN paiement AS p ON p.id_fac=f.id
						WHERE (c.permission=:permission and f.veto=:permission and p.permission=:permission
						and c.id = :id_consult)
						LIMIT 50;";
	$st = $db->prepare($sql);
	$st->execute(array(':id_consult' => $arr, ':permission' => $_SESSION['login']));
	$ma_var2 = $st->fetchAll(PDO::FETCH_ASSOC);
	$st->closeCursor();
	
		

	$sql = "select DATE_FORMAT(FROM_UNIXTIME(r.date/1000), '%d/%m/%Y') AS date, r.type AS motif
						FROM rappel AS r 
						WHERE (r.permission=:permission
						and r.id_con = :id_consult)
						LIMIT 50;";
	$st = $db->prepare($sql);
	$st->execute(array(':id_consult' => $arr, ':permission' => $_SESSION['login']));
	$ma_var3 = $st->fetchAll(PDO::FETCH_ASSOC);
	$st->closeCursor();
						
	$sql = "select v.valeur AS rage2, v.lot AS rage3, v.vaccinateur AS rage4, STR_TO_DATE(v.date2, '%d/%m/%Y') AS formatted_date2
						FROM vac_rage AS v 
						WHERE (v.permission=:permission
						and v.consult = :id_consult)
						LIMIT 1;";
	$st = $db->prepare($sql);
	$st->execute(array(':id_consult' => $arr, ':permission' => $_SESSION['login']));
	$ma_var4 = $st->fetchAll(PDO::FETCH_ASSOC);
	$st->closeCursor();
	
	$sql = "select p.num_pass AS pass2, p.proprietaire AS pass3
						FROM passeport AS p 
						WHERE (p.permission=:permission
						and p.id_consult = :id_consult)
						LIMIT 1;";
	$st = $db->prepare($sql);
	$st->execute(array(':id_consult' => $arr, ':permission' => $_SESSION['login']));
	$ma_var5 = $st->fetchAll(PDO::FETCH_ASSOC);
	$st->closeCursor();
	
	$sql = "select r.nom AS perso, DATE_FORMAT(r.date, '%d/%m/%Y') AS ma_date, r.zone AS zone, r.expo AS expo 
						FROM radiographie AS r 
						WHERE (r.permission=:permission
						and r.id_consult = :id_consult)
						LIMIT 1;";
	$st = $db->prepare($sql);
	$st->execute(array(':id_consult' => $arr, ':permission' => $_SESSION['login']));
	$ma_var6 = $st->fetchAll(PDO::FETCH_ASSOC);
	$st->closeCursor();

	while (list($key_paiement, $value_paiement) = each($ma_var2)) 
		{ 
		$ma_var2[$key_paiement]['id_select'] = $key_paiement;	
		}
	while (list($key_paiement, $value_paiement) = each($ma_var3)) 
		{ 
		$ma_var3[$key_paiement]['id_select'] = $key_paiement;	
		}
	while (list($key_paiement, $value_paiement) = each($ma_var6)) 
		{ 
		$ma_var6[$key_paiement]['id_select'] = $key_paiement;	
		}
	$ma_var1[0]['paiement']=json_encode($ma_var2);
	$ma_var1[0]['relance']=json_encode($ma_var3);
	$ma_var1[0]['radio']=json_encode($ma_var6);
	$result = array_merge($ma_var1,$ma_var4,$ma_var5);
	return json_encode($result);
	
	
	}
	public static function mes_infos(){
		try{
			global $db;
			$st = $db->prepare("select id, login, nom, tel, adresse, code, commune, tour, chef, mail, tel2, ordre, siret, num_tva, conduite_suivre,
			choix_specialiste, mention_speciale, mail2, marge, tva
			from identification where login='".$_SESSION['login']."' LIMIT 1;");
			$st->execute();	
			return json_encode($st->fetchAll());	
		} catch(Exception $e){
			return '';
		}
	}
	public static function liste_specialistes($arr){
		global $db;
		$st = $db->prepare("select id, id_spe, nom, domaine, commune from specialiste where domaine=:domaine;");
		$st->execute(array(':domaine' => $arr['domaine']));	
		return json_encode($st->fetchAll());	
	}
	public static function mes_specialites(){
		try{
			global $db;
			$st = $db->prepare("select id, id_spe, nom, domaine, commune from specialiste where nom=:nom;");
			$st->execute(array(':nom' => $_SESSION['login']));	
			return json_encode($st->fetchAll());	
		} catch(Exception $e){
			return '';
		}
	}
	public static function listevetos(){
		try{
			global $db;
			$st = $db->prepare("select id, login,nom ,adresse, code, commune, mail2 AS mail, tel, conduite_suivre, choix_specialiste, mention_speciale, delete2 from identification where tour='".$_SESSION['tour']."' or login = '".$_SESSION['tour']."' order by login ASC;");
			$st->execute();	
			return json_encode($st->fetchAll());
		} catch(Exception $e){
			return '';
		}	
	}
	// liste des vétérinaires dans l ordre de rechercher de la page nouveauclient
	public static function listevetos2(){
		try{
			global $db;
			$st = $db->prepare("select id, login,nom ,adresse, code, commune, mail2 AS mail, tel, conduite_suivre, choix_specialiste, mention_speciale, delete2 from identification where (tour='".$_SESSION['tour']."' or login = '".$_SESSION['tour']."') and referent='0' order by commune ASC;");
			$st->execute();
			return json_encode($st->fetchAll());
		} catch(Exception $e){
			return '';
		}
	}
	public static function liste_mur(){
		try{
			global $db;
			$st = $db->prepare("select id, texte, importance from liste_mur where permission='".$_SESSION['login']."'");
			$st->execute();	
			return json_encode($st->fetchAll());	
		} catch(Exception $e){
			return '';
		}
	}
	public static function supr_mur($arr){
		global $db;
		$st = $db->prepare("DELETE FROM `aerogard2`.`liste_mur` where id='$arr' and permission='".$_SESSION['login']."'");
		$st->execute();	
		return json_encode("ok");
	}
	public static function brouillard ($arr){
		try{
				global $db;
				if($arr['choix']=="total"){
					$sql = "select c.id AS id_c, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y %H:%i') AS date_consult
					, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y %H:%i') AS date_paiement, p.date AS date_paiement2, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, p.montant AS montant, p.mode AS mode, p.numero_cheque AS numero_cheque						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN paiement AS p ON p.id_fac=f.id	
									JOIN client AS cl ON cl.id2=a.id_p					
									WHERE (c.permission=:permission and f.veto=:permission and p.permission=:permission and cl.permission2=:permission
									and p.date >= :date_min and p.date < :date_max ) order by p.date ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='cheque'){
					$sql = "select c.id AS id_c, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y %H:%i') AS date_consult
					, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y %H:%i') AS date_paiement, p.date AS date_paiement2, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, p.montant AS montant, p.mode AS mode, p.numero_cheque AS numero_cheque						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN paiement AS p ON p.id_fac=f.id	
									JOIN client AS cl ON cl.id2=a.id_p					
									WHERE (c.permission=:permission and f.veto=:permission and p.permission=:permission and cl.permission2=:permission
									and p.date >= :date_min and p.date < :date_max and p.mode=:cheque) order by p.montant ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $_SESSION['login'], ':cheque' => $arr['choix']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='veto'){
					// recette réelle sur les consultations effectuées sur la période 
					$sql = "select c.id AS id_c, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
					, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y') AS date_paiement, p.date AS date_paiement2, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, p.montant AS montant, p.mode AS mode, p.numero_cheque AS numero_cheque						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN paiement AS p ON p.id_fac=f.id	
									JOIN client AS cl ON cl.id2=a.id_p					
									WHERE (c.permission2=:permission and f.veto=:permission_de_voir and f.veto2=:permission and p.permission=:permission_de_voir and cl.permission2=:permission_de_voir
									and f.date >= :date_min and f.date < :date_max ) order by f.date ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $arr['veto'], ':permission_de_voir' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='veto2'){
					// recette totale sur la période (tous les encaissements : aux autres vétos, les consultations antérieures...)
					$sql = "select c.id AS id_c, c.permission2 AS permission2, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
					, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y') AS date_paiement, p.date AS date_paiement2, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, p.montant AS montant, p.mode AS mode, p.numero_cheque AS numero_cheque, f.veto2 AS destinataire						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN paiement AS p ON p.id_fac=f.id	
									JOIN client AS cl ON cl.id2=a.id_p					
									WHERE (c.permission=:permission_de_voir and f.veto=:permission_de_voir and p.permission2=:permission and cl.permission2=:permission_de_voir
									and p.date >= :date_min and p.date < :date_max ) order by p.date ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $arr['veto'], ':permission_de_voir' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='veto3'){
					// recette théorique sur la période
					$sql = "select c.id AS id_c, c.permission2 AS permission2, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
					, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, f.totalttc, f.reglementttc, f.total_acte, f.reglement_acte, f.total_medic, f.reglement_medic						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN client AS cl ON cl.id2=a.id_p					
									WHERE (c.permission2=:permission and f.veto=:permission_de_voir and f.veto2=:permission and cl.permission2=:permission_de_voir
									and f.date >= :date_min and f.date < :date_max ) order by f.date ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $arr['veto'], ':permission_de_voir' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='veto4'){
					$sql = "select e.veto_desti AS veto_desti, e.montant AS montant
							,c.id AS id_c, c.permission2 AS permission2, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
							, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
							, cl.code AS code_p, cl.ville AS ville_p, f.totalttc, f.total_acte, f.reglement_acte						
									FROM consultation AS c 
									JOIN echange AS e ON c.id=e.id_consult
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN client AS cl ON cl.id2=a.id_p					
									WHERE (f.veto=:permission_de_voir and cl.permission2=:permission_de_voir and (e.veto_desti=:permission)
									and e.date >= DATE_FORMAT(FROM_UNIXTIME(:date_min/1000), '%Y-%m-%d %H:%i:%s') and e.date < DATE_FORMAT(FROM_UNIXTIME(:date_max/1000), '%Y-%m-%d %H:%i:%s') ) order by f.date ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $arr['veto'], ':permission_de_voir' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='veto5'){
					// recette totale sur la période des consultations effectuées pendant la période et en dehors
					$sql = "select c.id AS id_c, c.permission2 AS permission2, a.id_p AS id_pro, a.id AS id_ani, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
					, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y') AS date_paiement, p.date AS date_paiement2, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, p.montant AS montant, p.mode AS mode, p.numero_cheque AS numero_cheque, f.totalttc, f.reglementttc, f.total_acte, f.reglement_acte, f.total_medic, f.reglement_medic						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c										
									JOIN client AS cl ON cl.id2=a.id_p	
									JOIN paiement AS p ON p.id_fac=f.id				
									WHERE (c.permission=:permission_de_voir and f.veto=:permission_de_voir and f.veto2=:permission and p.permission=:permission_de_voir and cl.permission2=:permission_de_voir
									and p.date >= :date_min and p.date < :date_max and f.date < :date_min) order by p.date ASC;";
						$st = $db->prepare($sql);
						$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $arr['veto'], ':permission_de_voir' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='historique'){
					$sql = "select c.id AS id_c, c.motif AS motif, a.id_p AS id_pro, a.id AS id_ani, a.espece AS espece, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
					, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y') AS date_paiement, p.date AS date_paiement2, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, p.montant AS montant, p.mode AS mode, p.numero_cheque AS numero_cheque						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN client AS cl ON cl.id2=a.id_p	
									JOIN paiement AS p ON p.id_fac=f.id				
									WHERE (c.permission=:permission and f.veto=:permission and cl.permission2=:permission)
									 order by c.date DESC Limit 100;";
						$st = $db->prepare($sql);
						$st->execute(array(':permission' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}elseif($arr['choix']=='historique2'){
					$sql = "select c.id AS id_c, c.motif AS motif, a.id_p AS id_pro, a.id AS id_ani, a.espece AS espece, a.nom_a AS nom_a, DATE_FORMAT(FROM_UNIXTIME(c.date/1000), '%d/%m/%Y') AS date_consult
					, cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p
					, cl.code AS code_p, cl.ville AS ville_p, f.reglementttc AS montant						
									FROM consultation AS c 
									JOIN animal AS a ON a.id=c.id_c
									JOIN facturation AS f ON c.id=f.id_c
									JOIN client AS cl ON cl.id2=a.id_p														
									WHERE (c.permission=:permission and f.veto=:permission and cl.permission2=:permission)
									 order by c.date DESC Limit 100;";
						$st = $db->prepare($sql);
						$st->execute(array(':permission' => $_SESSION['login']));
						$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
						$st->closeCursor();
						while (list($key_row, $value_row) = each($ma_var)) 
						{  				 
						 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
						}
						return json_encode($ma_var);
					}
			} catch(Exception $e){
				return '';
			}
		}	
	public static function duclient ($arr){
		global $db;
			$st = $db->prepare("select f.id, DATE_FORMAT(FROM_UNIXTIME(f.date/1000), '%d/%m/%Y') as date, ROUND(f.totalttc,2) as montant_r, ROUND(f.totalttc-f.reglementttc,2) as montant_d,
			 CONCAT(cl.nom, ' ', cl.prenom, ' ', cl.adresse, ' ',cl.code, ' ',cl.ville, ' ', cl.tel1, ' ', cl.tel2) as nom,
			 c.id as id_c, a.id as animal
			 FROM consultation AS c 
			 JOIN animal AS a ON a.id=c.id_c
			 JOIN facturation AS f ON c.id=f.id_c
			 JOIN client AS cl ON cl.id2=a.id_p	
			 WHERE (c.permission=:permission and f.veto=:permission and cl.permission2=:permission)
			 and f.totalttc > f.reglementttc+1 and f.date>=:date_min and f.date<:date_max order by f.date DESC;");
			$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $_SESSION['login']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			while (list($key_row, $value_row) = each($ma_var))
			{
				$ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c'];
				$ma_var[$key_row]['url2']="/sauvegarde/animaux/".$ma_var[$key_row]['animal']."/facture_".$ma_var[$key_row]['id'];
			}
			return json_encode($ma_var);		
		
	}
	public static function totaux ($arr){
		global $db;
		$st = $db->prepare("select montant, mode from paiement where permission=:permission and date >= :date_min and date < :date_max;");
		$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $_SESSION['login']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return json_encode($ma_var);
	
	}
	public static function totaux2 ($arr){
		global $db;
		$array_total = array();
		while (list($key_row, $value_row) = each($arr['recherche'])) {  				 
		$st = $db->prepare("select p.montant as montant, p.mode, DATE_FORMAT(FROM_UNIXTIME(p.date/1000), '%d/%m/%Y') AS date, p.date AS date2 from paiement as p
				where p.mode=:choix and	p.permission=:permission and 
				p.date >= :date_min and p.date < :date_max ;");
		$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'],
				 ':permission' => $_SESSION['login'], ':choix' => $value_row));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		$array_total = array_merge($array_total, $ma_var);
		}
		return json_encode($array_total);
	
	}
	public static function search_remise ($arr){
		global $db;
			$st = $db->prepare("select `id`, `numero_remise`, DATE_FORMAT(FROM_UNIXTIME(date/1000), '%d/%m/%Y') AS date, `remise` from remise where veto=:permission and numero_remise LIKE :numero_remise or remise LIKE :numero_remise;");
			$st->execute(array(':numero_remise' => '%'. $arr['numero_remise']. '%', ':permission' => $_SESSION['login']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			return json_encode($ma_var);		
		
	}	
	public static function rappel ($arr){
		global $db;
			$sql = "select r.id AS rappel_id, r.id_chien AS id_chien, r.id_pro AS id_pro, r.id_con AS id_c, r.type AS type, DATE_FORMAT(FROM_UNIXTIME(r.date/1000), '%d/%m/%Y') AS date_rappel,
			 cl.nom AS nom_p, cl.prenom AS prenom_p, cl.adresse AS adresse_p, cl.code AS code_p, cl.ville AS ville_p,
			 a.nom_a AS nom_a, a.espece AS espece
			 FROM rappel AS r
			 JOIN animal AS a ON a.id=r.id_chien
			 JOIN client AS cl ON cl.id2=r.id_pro
			 WHERE (cl.permission2=:permission and r.permission=:permission and a.permission=:permission
			 and r.date >= :date_min and r.date < :date_max ) order by r.date ASC;";			
			$st = $db->prepare($sql);
			$st->execute(array(':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':permission' => $_SESSION['login']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			while (list($key_row, $value_row) = each($ma_var)) 
				{  
				 $ma_var[$key_row]['commentaire']=""; 
				 $ma_var[$key_row]['url']="index.php?id_consultation=".$ma_var[$key_row]['id_c']; 
				}
			return json_encode($ma_var);
			}
		public static function radio ($arr){
					global $db;
					$st = $db->prepare("select * from radiographie where permission=:permission and date >= :date_min and date < :date_max;");
					$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
					$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
					$st->closeCursor();
					return json_encode($ma_var);
			}
		public static function stat ($arr){
					global $db;
					$st = $db->prepare("select SUM(montant) AS total from paiement where permission=:permission and date >= :date_min and date < :date_max GROUP BY permission;");
					$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
					$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
					$st->closeCursor();
					return $ma_var;
			}
		public static function sous_tot ($arr){
					global $db;
							if($arr['recherche']=='total'){
								$st = $db->prepare("select SUM(facturation.reglement_acte)  AS reglement_acte,
										SUM(facturation.reglement_medic)  AS reglement_medic,
										SUM(facturation.total_acte)  AS total_acte,
										SUM(facturation.total_medic)  AS total_medic,
										SUM(facturation.totalttc)  AS totalttc,
										SUM(facturation.reglementttc)  AS reglementttc
										from facturation
										where  facturation.veto2=:permission and facturation.veto=:ma_session
										and facturation.date >= :date_min and facturation.date < :date_max GROUP BY facturation.veto2;");			
								$st->execute(array(':permission' => $arr['permission'],':ma_session' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
													
							}elseif($arr['recherche']=='totale'){
					$st = $db->prepare("select SUM(montant) AS total from paiement where permission2=:permission and permission=:ma_session and date >= :date_min and date < :date_max GROUP BY permission2;");
					$st->execute(array(':permission' => $arr['permission'],':ma_session' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
							}elseif($arr['recherche']=='essai'){
				//	$st = $db->prepare("select SUM(p.montant) AS total,
				//			(   select SUM(facturation.reglement_acte) from facturation 
         		//					 where  p.id_fac=f.id and facturation.veto2=:permission and facturation.veto=:ma_session
				//						and p.date >= :date_min and p.date < :date_max) AS reglement_acte, 
				//			(   select SUM(facturation.reglement_medic) from facturation 
         		//					 where  p.id_fac=f.id and facturation.veto2=:permission and facturation.veto=:ma_session
				//						and p.date >= :date_min and p.date < :date_max) AS reglement_medic,
				//			(   select SUM(facturation.total_acte) from facturation 
         		//					 where  p.id_fac=f.id and facturation.veto2=:permission and facturation.veto=:ma_session
				//						and p.date >= :date_min and p.date < :date_max) AS total_acte, 
				//			(   select SUM(facturation.total_medic) from facturation 
         		//					 where  p.id_fac=f.id and facturation.veto2=:permission and facturation.veto=:ma_session
				//						and p.date >= :date_min and p.date < :date_max) AS total_medic
				//			FROM facturation AS f
				//			JOIN paiement AS p ON p.id_fac=f.id	
				//			where  f.veto2=:permission and f.veto=:ma_session and p.date >= :date_min and p.date < :date_max GROUP BY f.veto;");
				//	$st->execute(array(':permission' => $arr['permission'],':ma_session' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
								
							}elseif($arr['recherche']=='repartition'){	
					$st = $db->prepare("select SUM(montant) AS total from echange where veto_desti=:permission and date >= DATE_FORMAT(FROM_UNIXTIME(:date_min/1000), '%Y-%m-%d %H:%i:%s') and date < DATE_FORMAT(FROM_UNIXTIME(:date_max/1000), '%Y-%m-%d %H:%i:%s') GROUP BY veto_desti;");
					$st->execute(array(':permission' => $arr['permission'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
												
							}else{
					$st = $db->prepare("select SUM(montant) AS total from paiement where permission2=:permission and permission=:ma_session and date >= :date_min and date < :date_max and mode=:recherche GROUP BY permission;");
					$st->execute(array(':permission' => $arr['permission'],':ma_session' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':recherche' => $arr['recherche']));
							}
					$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
					$st->closeCursor();
					return $ma_var;
			}
		public static function vente ($arr){
					global $db;
					$st = $db->prepare("select DATE_FORMAT(FROM_UNIXTIME(date/1000), '%d/%m/%Y') AS date_medoc, medic from facturation where veto=:permission and date >= :date_min and date < :date_max;");
					$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
					$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
					$st->closeCursor();
					return $ma_var;
			}
		public static function pharmaco ($arr){
			global $db;
			$sql = "select f.id_c AS id_c, DATE_FORMAT(FROM_UNIXTIME(f.date/1000), '%d/%m/%Y') AS date_vente,
			 CONCAT(cl.nom,' ', cl.prenom,' ', cl.adresse,' ', cl.code,' ', cl.ville,' ',cl.tel1,' ',cl.tel2) AS nom,
			 a.nom_a AS nom_a, a.espece AS espece
			 FROM facturation AS f
			 JOIN consultation AS c ON f.id_c=c.id
			 JOIN animal AS a ON a.id=c.id_c
			 JOIN client AS cl ON cl.id2=a.id_p
			 WHERE (cl.permission2=:permission and a.permission=:permission and c.permission=:permission
			 and f.medic LIKE :lot) order by f.date DESC LIMIT 100;";
			$st = $db->prepare($sql);
			$st->execute(array(':lot' => '%'. $arr['lot']. '%', ':permission' => $_SESSION['login']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			return json_encode($ma_var);		
		
	}	
	public static function envoyer_mail($arr){
		global $db;
		$st = $db->prepare("select i.login AS login, i.mail AS mail
				FROM tourdegarde AS t
				JOIN identification AS i ON t.login=i.login
				where t.date=FROM_UNIXTIME(:ma_date) and t.date!=t.date_debut and (t.permission=:permission or t.permission=:permission2);");
		$st->execute(array(':ma_date' => $arr['ma_date'], ':permission' => $_SESSION['login'], ':permission2' => $_SESSION['tour']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		$mail_serveur = "veterinairedegarde@free.fr";
		$nom_serveur_mail = "Urgencesvet";
		foreach($ma_var as $row)
		{
		
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $row['mail']))
			{
				$passage_ligne = "\r\n";
			}
			else
			{
				$passage_ligne = "\n";
			}
			//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = "Bonjour ".$row['login'].". <br>Le tour de garde vous rappelle votre participation : le ".date("d/m/y",$date_envoi_mail).".<br>
			Veuillez consulter le serveur pour connaître les modalités : horaires, équipe et fonction. <br>
			";
			$message_txt .= "<br>Sincères salutations";
		
			$message_html = "<html><head></head><body><p>Bonjour ".$row['login'].". </p><section><aside>Le tour de garde vous rappelle votre participation : le ".date("d/m/y",$date_envoi_mail).".</aside><article>
			<p>Veuillez consulter le serveur pour connaître les modalités : horaires, équipe et fonction.</p>";
			$message_html .= "</article></section><footer><br>Sincères salutations</footer></body></html>";
		
			//==========
			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			//=====Définition du sujet.
			$sujet = "Rappel participation maison de garde: ";
			//=========
			//=====Création du header de l'e-mail
			$header = "From: \"".$nom_serveur_mail."\"<".$mail_serveur.">".$passage_ligne;
			$header .= "Reply-to: \"".$nom_serveur_mail."\" <".$mail_serveur.">".$passage_ligne;
			$header .= "MIME-Version: 1.0".$passage_ligne;
			$header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//=====Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format texte.
			$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_txt.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========
			//=====Envoi de l'e-mail.
			mail($row['mail'],utf8_decode($sujet),utf8_decode($message),$header);
				
		
		
		}
		return $ma_var;	
		
	}
	public static function findunanimal2($arr){	
		try{
				if($arr['id_ani']==0){
					return json_encode($arr['id_ani']);		
				}else{
					global $db;
					$st = $db->prepare("select a.id AS id_c, a.id_p AS id_p, a.nom_a AS nom_a, c.nom AS nom_p, c.tel1 AS tel1,
					c.tel2 AS tel2		
					FROM animal AS a
					JOIN client AS c ON c.id2=a.id_p
					where a.id=:animal and c.permission2=:permission limit 1;");
					$st->execute(array(':animal' => $arr['id_ani'], ':permission' => $_SESSION['login']));
					$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
					$st->closeCursor();
					return json_encode($ma_var);			
				}
			} catch(Exception $e){
				return '';
			}
	}
	public static function marquelu($arr){
		global $db;
		$sql="UPDATE message set lu=0 WHERE id=:message AND destinataire=:permission LIMIT 1";
		$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$st2->execute(array(':message' => $arr['message'], ':permission' => $arr['login']));
		$st2->closeCursor();
		return json_encode("ok");
	}
	public static function modif_veto($arr){
		global $db;
		$sql="UPDATE tourdegarde set login=:login WHERE id=:id_garde AND permission=:permission LIMIT 1";
		$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$st2->execute(array(':login' => $arr['login'], ':id_garde' => $arr['id_garde'], ':permission' => $_SESSION['login']));
		$st2->closeCursor();
		return json_encode("ok");
	}
	public static function liste_message($arr){
		try{
				global $db;
				if($arr['choix']=='recu'){
				$sql = "select m.id AS id_m, DATE_FORMAT(m.date, '%d/%m/%Y') AS ma_date, m.from AS envoye_par, m.titre AS titre,
				m.message AS message, m.lu AS lu, m.destinataire AS desti 
				FROM message AS m 
				WHERE (m.destinataire=:permission) ORDER BY m.date DESC LIMIT 100;";
				}elseif($arr['choix']=='emis'){
				$sql = "select m.id AS id_m, DATE_FORMAT(m.date, '%d/%m/%Y') AS ma_date, m.destinataire AS desti, m.titre AS titre,
				m.message AS message, m.lu AS lu, m.from AS envoye_par 
				FROM message AS m 
				WHERE (m.from=:permission) ORDER BY m.date DESC LIMIT 100;";			
				}
				$st = $db->prepare($sql);
				$st->execute(array(':permission' => $arr['login']));
				$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
				$st->closeCursor();
				while (list($key_message, $value_message) = each($ma_var)) 
					{ 
					$ma_var[$key_message]['id_s'] = $key_message;	
					}
				return json_encode($ma_var);
			} catch(Exception $e){
				return '';
			}
	}
	public static function liste_rdv ($arr){
			global $db;
			$st = $db->prepare("select `id`, DATE_FORMAT(`date_debut`, '%Y/%d/%m %H:%i') as start, DATE_FORMAT(`date_fin`, '%Y/%d/%m %H:%i') as end, `titre` as title from rendezvous where permission=:permission and date_debut >= FROM_UNIXTIME(:debut/1000) and date_debut < FROM_UNIXTIME(:fin/1000);");
			$st->execute(array(':permission' => $arr['permission'],':debut' => $arr['debut'],':fin' => $arr['fin']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			return json_encode($ma_var);		
		
	}
	public static function mail_send ($arr){
		global $db;
		$sql = "
	DELETE FROM `aerogard2`.`tourdegarde3` where (tour=:login or tour=:login2) ;
	INSERT INTO `aerogard2`.`tourdegarde3` (tour,date) 
	VALUES(:login, FROM_UNIXTIME(:ma_date));";
	
	$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':login' => $_SESSION['tour'], 
			':login2' => $_SESSION['login'],
			':ma_date' => $arr['ma_date']
			));
	$sth->closeCursor();
	echo json_encode("ok");
	
	}	
	public static function mail_allready_send ($arr){
		global $db;
		$st = $db->prepare("select `id`, `tour`from tourdegarde3 where 
		(tour=:permission OR tour=:permission2) and date = FROM_UNIXTIME(:ma_date);");
		$st->execute(array(':permission' => $_SESSION['tour'],
				':permission2' => $_SESSION['login'],
				':ma_date' => $arr['ma_date']
				));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
		
	}
	public static function liste_garde ($arr){
		global $db;
		$st = $db->prepare("select `id`, `login`, DATE_FORMAT(`date`, '%e') as garde_jour, DATE_FORMAT(`date`, '%d/%m/%Y') as ma_date2, UNIX_TIMESTAMP(`date`) AS ma_date, `nature`, UNIX_TIMESTAMP(`date_debut`) AS date_debut, DATE_FORMAT(`date_debut`, '%H:%i') as start_heure, UNIX_TIMESTAMP(`date_fin`) AS date_fin, DATE_FORMAT(`date_fin`, '%H:%i') as end_heure from tourdegarde where (permission=:permission OR permission=:permission2) and date_debut >= FROM_UNIXTIME(:debut) and date_debut < FROM_UNIXTIME(:fin) order by id asc;");
		$st->execute(array(':permission' => $_SESSION['tour'],
				':permission2' => $_SESSION['login'],
				':debut' => $arr['debut'],
				':fin' => $arr['fin']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		$ma_var2 = array();
		while (list($key, $value) = each($ma_var))
					{
						$ma_var2[$value['garde_jour']][]=$value;
						
					}
		return json_encode($ma_var2);		
	}
	public static function liste_garde2 ($arr){
		global $db;
		$st = $db->prepare("select `id`, `login`, DATE_FORMAT(`date`, '%e') as garde_jour, DATE_FORMAT(`date`, '%d/%m/%Y') as ma_date2, UNIX_TIMESTAMP(`date`) AS ma_date, `nature`, UNIX_TIMESTAMP(`date_debut`) AS date_debut, DATE_FORMAT(`date_debut`, '%H:%i') as start_heure, UNIX_TIMESTAMP(`date_fin`) AS date_fin, DATE_FORMAT(`date_fin`, '%H:%i') as end_heure from tourdegarde where (permission=:permission OR permission=:permission2) and date_debut >= FROM_UNIXTIME(:debut) and date_debut < FROM_UNIXTIME(:fin) order by id asc;");
		$st->execute(array(':permission' => $_SESSION['tour'],
				':permission2' => $_SESSION['login'],
				':debut' => $arr['debut'],
				':fin' => $arr['fin']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();		
		return json_encode($ma_var);
	}
	public static function liste_point ($arr){
		global $db;
		$st = $db->prepare("select login, (SUM(TIME_TO_SEC(date_debut) - TIME_TO_SEC(date_fin))) DIV 3600 AS somme_heure from tourdegarde where permission=:permission and date_debut >= FROM_UNIXTIME(:date_min) and date_fin < FROM_UNIXTIME(:date_max) GROUP BY login;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
	
	}	
	public static function liste_point2 ($arr){
		global $db;
		$st = $db->prepare("select login, (SUM(TIMESTAMPDIFF(SECOND,date_debut,date_fin))) DIV 3600 AS somme_heure, nature from tourdegarde where permission=:permission and date_debut >= FROM_UNIXTIME(:date_min) and date_fin < FROM_UNIXTIME(:date_max) and nature = :choix GROUP BY login;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':choix' => $arr['nb_choix']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
		
	}
	// recherche du nombre de points nuit semaine
	public static function liste_point3 ($arr){
		global $db;
		$st = $db->prepare("select login, (SUM(TIMESTAMPDIFF(SECOND,date_debut,date_fin))) DIV 3600 AS somme_heure, nature from tourdegarde where permission=:permission and date_debut >= FROM_UNIXTIME(:date_min) and date_fin < FROM_UNIXTIME(:date_max) and nature = :choix and ( (DATE_FORMAT(date, '%w')=1  &&  DATE_FORMAT(date_debut, '%k')!=0) || DATE_FORMAT(date, '%w')=2 || DATE_FORMAT(date, '%w')=3 || DATE_FORMAT(date, '%w')=4) GROUP BY login;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':choix' => $arr['nb_choix']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
	
	}
	// recherche du nombre de points nuit we
	public static function liste_point4 ($arr){
		global $db;
		$st = $db->prepare("select login, (SUM(TIMESTAMPDIFF(SECOND,date_debut,date_fin))) DIV 3600 AS somme_heure, nature from tourdegarde where permission=:permission and date_debut >= FROM_UNIXTIME(:date_min) and date_fin < FROM_UNIXTIME(:date_max) and nature = :choix and ( (DATE_FORMAT(date, '%w')=6 &&  DATE_FORMAT(date_debut, '%k')=0)  || (DATE_FORMAT(date, '%w')=0  &&  DATE_FORMAT(date_debut, '%k')=0) || (DATE_FORMAT(date, '%w')=1 &&  DATE_FORMAT(date_debut, '%k')=0) ) GROUP BY login;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':choix' => $arr['nb_choix']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
	
	}
	// recherche du nombre de points jour we
	public static function liste_point5 ($arr){
		global $db;
		$st = $db->prepare("select login, (SUM(TIMESTAMPDIFF(SECOND,date_debut,date_fin))) DIV 3600 AS somme_heure, nature from tourdegarde where permission=:permission and date_debut >= FROM_UNIXTIME(:date_min) and date_fin < FROM_UNIXTIME(:date_max) and nature = :choix and ( (DATE_FORMAT(date, '%w')=6 &&  DATE_FORMAT(date_debut, '%k')!=0 &&  DATE_FORMAT(date_fin, '%k')!=0)  || (DATE_FORMAT(date, '%w')=0  &&  DATE_FORMAT(date_debut, '%k')=0 &&  DATE_FORMAT(date_fin, '%k')!=0) ) GROUP BY login;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_min' => $arr['debut'], ':date_max' => $arr['fin'], ':choix' => $arr['nb_choix']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
	
	}
// recherche la date de la dernière garde réalisée par le membre
	public static function recherche_der_garde ($arr){
		global $db;
		$st = $db->prepare("select UNIX_TIMESTAMP(date) AS date from tourdegarde where permission=:permission and login=:login and date < FROM_UNIXTIME(:date_actu) ORDER BY  date DESC LIMIT 1;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_actu' => $arr['date_actu'], ':login' => $arr['login']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
	
	}
	// recherche la date de la prochaine garde réalisée par le membre
	public static function recherche_next_garde ($arr){
		global $db;
		$st = $db->prepare("select UNIX_TIMESTAMP(date) AS date from tourdegarde where permission=:permission and login=:login and date > FROM_UNIXTIME(:date_actu) ORDER BY  date ASC LIMIT 1;");
		$st->execute(array(':permission' => $_SESSION['login'], ':date_actu' => $arr['date_actu'], ':login' => $arr['login']));
		$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $ma_var;
	
	}
	public static function recup_groupe ($arr){
			global $db;
			$st = $db->prepare("select `login` from identification where groupe=:groupe and login != '".$_SESSION['login2']."' and login != '".$_SESSION['login']."';");
			$st->execute(array(':groupe' => $arr['groupe']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			return $ma_var;		
		
	}
	public static function planning ($arr){
			try{
				global $db;
				$st = $db->prepare("select `login`,DATE_FORMAT(date, '%e') AS jour, DATE_FORMAT(date, '%d/%m/%Y') AS ma_date,`nature` from tourdegarde where tour=:tour and date >= FROM_UNIXTIME(:date_debut,'%Y/%m/%d') and date < FROM_UNIXTIME(:date_fin,'%Y/%m/%d') and nature=:nature;");
				$st->execute(array(':tour' => $arr['tour'], ':date_debut' => $arr['date_debut'], ':date_fin' => $arr['date_fin'], ':nature' => $arr['nature']));
				$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
				$st->closeCursor();
				$ma_var2 = array();
				while (list($key, $value) = each($ma_var)) 
					{ 
					$ma_var2[$value['jour']] = $value;	
					}
				return json_encode($ma_var2);		
			} catch(Exception $e){
				return '';
			}
	}	
	public static function tour_dispo ($arr){
			try{
				global $db;
				$st = $db->prepare("select `login` as login2, `groupe`, `tour`, `delete2` from identification where tour=:tour and login!='' order by login ASC;");
				$st->execute(array(':tour' => $arr['tour']));
				$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
				$st->closeCursor();
				return json_encode($ma_var);	
			} catch(Exception $e){
				return '';
			}	
		
	}
	public static function membre ($arr){
		try{
			global $db;
			$st = $db->prepare("select `login` as login2, `mail`, `groupe`, `tour`, `delete2` from identification where tour!=:tour and delete2='' order by login ASC;");
			$st->execute(array(':tour' => $arr['tour']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			return json_encode($ma_var);		
		} catch(Exception $e){
			return '';
		}
	}
	public static function membre_supr ($arr){
			try{
				global $db;
				$st = $db->prepare("select `login` as login2, `mail`, `groupe`, `tour`, `delete2` from identification where delete2!=:delete order by delete2 ASC;");
				$st->execute(array(':delete' => $arr['delete']));
				$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
				$st->closeCursor();
				return json_encode($ma_var);		
			} catch(Exception $e){
				return '';
			}
	}
	public static function presence_historique($arr){
		try{
			global $db;
			$st = $db->prepare("select id from salle_attente5 where permission='".$_SESSION['login']."' and id_ani=:id_ani ");
			$st->execute(array(':id_ani' => $arr['id_ani']));
			$ma_var = $st->fetchAll(PDO::FETCH_ASSOC);
			return $ma_var;
		}catch(Exception $e){
				return '';
		}
	}
			
}?>