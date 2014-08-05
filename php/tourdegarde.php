<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";
require_once "requetemysql.php";
require('fpdf/fpdf.php');
require('fpdi/fpdi.php');
require_once "requetemysql.php";
if ($_GET['action']=='save_tour'){
	
	$sql = "
	DELETE FROM `aerogard2`.`tourdegarde2` where tour=:login;
	INSERT INTO `aerogard2`.`tourdegarde2` (tour,horaire,participant,liaison,vacances,importance,envoi_mail,jour) 
	VALUES(:login, :horaire, :participant, :liaison, :vacances, :importance, :envoi_mail, :jour);";
	
	$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':login' => $_SESSION['login2'], 
			':horaire' => $_POST['horaire'],
			':participant' => $_POST['participant'],
			':liaison' => $_POST['liaison'],
			':vacances' => $_POST['vacances'],
			':importance' => $_POST['importance'],
			':envoi_mail' => $_POST['envoi_mail'],
			':jour' => $_POST['jour'],
			));
	$sth->closeCursor();
	echo json_encode("ok");
	
	
	
}elseif ($_GET['action']=='recup_point'){
	
		$liste_point_perso = requetemysql::liste_point(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin']));
		
	echo json_encode($liste_point_perso);
	
}elseif ($_GET['action']=='recherche_next_der'){
	
	$liste_recherche = json_decode($_POST['liste_recherche'],true);
	
	while (list($key, $value) = each($liste_recherche))
	{
		$recherche_der_garde = requetemysql::recherche_der_garde(array('permission' => $_SESSION['login'], 'login' => $value['login'], 'date_actu' => $_POST['date_actu']));
		$liste_recherche[$key]['date_der'] = $recherche_der_garde[0]['date'];
		$recherche_next_garde = requetemysql::recherche_next_garde(array('permission' => $_SESSION['login'], 'login' => $value['login'], 'date_actu' => $_POST['date_actu']));
		$liste_recherche[$key]['date_next'] = $recherche_next_garde[0]['date'];
	}
	
	echo json_encode($liste_recherche);
		
		
}elseif ($_GET['action']=='recup_point2'){
	
	if($_POST['recherche_tot_garde']==0){
		$liste_point = requetemysql::liste_point2(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin'], 'nb_choix' => $_POST['cat_garde']));
	}else if($_POST['recherche_tot_garde']==1){
		$liste_point = requetemysql::liste_point3(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin'], 'nb_choix' => $_POST['cat_garde']));
	}else if($_POST['recherche_tot_garde']==2){
		$liste_point = requetemysql::liste_point4(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin'], 'nb_choix' => $_POST['cat_garde']));
	}else if($_POST['recherche_tot_garde']==3){
		$liste_point = requetemysql::liste_point5(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin'], 'nb_choix' => $_POST['cat_garde']));
	}
	
	
	echo json_encode($liste_point);
}elseif ($_GET['action']=='modif_veto'){
	$modif_veto = requetemysql::modif_veto(array('login' => $_POST['login'], 'id_garde' => $_POST['id_garde']));
	
	echo $modif_veto;


}elseif ($_GET['action']=='recup_historique'){
	
	$liste_garde = requetemysql::liste_garde(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin']));
	
	echo json_encode($liste_garde);
	
}elseif ($_GET['action']=='recup_historique2'){
	
	$liste_garde = requetemysql::liste_garde2(array('permission' => $_SESSION['login'], 'debut' => $_POST['date_debut'], 'fin' => $_POST['date_fin']));
	
	echo $liste_garde;
	
}elseif ($_GET['action']=='supr_planning'){
	$st = $db->prepare("DELETE FROM `aerogard2`.`tourdegarde` where id='".$_POST['supr_id']."'");
	$st->execute();
	echo json_encode("ok");

}elseif ($_GET['action']=='save_planning2'){
	
	$planning2 = json_decode($_POST['planning2'],true);
	$sql = "INSERT INTO `aerogard2`.`tourdegarde` (`login`, `from`, `date`, `nature`, `tour`, `permission`, `date_debut`, `date_fin`)
	 VALUES ";
	$qPart = array_fill(0, count($planning2), "(?, ?, FROM_UNIXTIME(?/1000), ?, ?, ?, FROM_UNIXTIME(?/1000), FROM_UNIXTIME(?/1000))");
	$sql .=  implode(",",$qPart);
	$stmt = $db -> prepare($sql);
	$i = 1;
	foreach($planning2 as $item) {
		$stmt -> bindParam($i++, $item["login"]);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $item["ma_date"]);
		$stmt -> bindParam($i++, $item["cat"]);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $item["date_debut"]);
		$stmt -> bindParam($i++, $item["date_fin"]);
		
	}
	$stmt -> execute();
	$stmt->closeCursor();
	
	echo json_encode('ok');
	
	
}elseif ($_GET['action']=='save_planning'){
	$st = $db->prepare("DELETE FROM `aerogard2`.`tourdegarde` where date_debut>=FROM_UNIXTIME('".$_POST['date_debut']."'/1000) AND date_fin<FROM_UNIXTIME('".$_POST['date_fin']."'/1000)");
	$st->execute();
	$planning2 = json_decode($_POST['planning2'],true);
	$sql = "INSERT INTO `aerogard2`.`tourdegarde` (`login`, `from`, `date`, `nature`, `tour`, `permission`, `date_debut`, `date_fin`)
	 VALUES ";
	$qPart = array_fill(0, count($planning2), "(?, ?, FROM_UNIXTIME(?/1000), ?, ?, ?, FROM_UNIXTIME(?/1000), FROM_UNIXTIME(?/1000))");
	$sql .=  implode(",",$qPart);
	$stmt = $db -> prepare($sql);
	$i = 1;
	foreach($planning2 as $item) {
		$stmt -> bindParam($i++, $item[0]);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $item[2]);
		$stmt -> bindParam($i++, $item[1]);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $item[3]);
		$stmt -> bindParam($i++, $item[4]);
		
	}
	$stmt -> execute();
	$stmt->closeCursor();
	$total = $i/8;
	echo json_encode($total);
}elseif ($_GET['action']=='envoi_mail'){
	
	$filename = $_POST['document'];
	$files_name = explode("/", $filename);

		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['adresse']))
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}
		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Bonjour ".$_POST['nom'].". <br>Le tour de garde de ".$_SESSION['login']." met à votre connaissance un planning de garde disponible en pièce jointe.<br>
		Ce document comporte 3 parties : <br>
		- Un planning mensuel personnel.<br>
		- Un planning mensuel général.<br>
		- Un résumé de votre activité dans le tour de garde.<br>";
		$message_txt .= "<br>Sincères salutations";
	
		$message_html = "<html><head></head><body><p>Bonjour ".$_POST['nom'].". </p><section><aside>Le tour de garde de  ".$_SESSION['login']."  met à votre connaissance un planning de garde disponible en pièce jointe.</aside><article>
		<p>Ce document comporte 3 parties :</p>
		<p>- Un planning mensuel personnel.</p>
		<p>- Un planning mensuel général.</p>
		<p>- Un résumé de votre activité dans le tour de garde.</p>";
		$message_html .= "</article></section><footer><br>Sincères salutations</footer></body></html>";
		
			//=====Lecture et mise en forme de la pièce jointe analyse.
			$fichier   = fopen($filename, "r");
			$attachement = fread($fichier, filesize($filename));
			$attachement = chunk_split(base64_encode($attachement));
			fclose($fichier);
		
		//==========
		//==========
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		$boundary_alt = "-----=".md5(rand());
		//==========
		//=====Définition du sujet.
		$sujet = "Votre planning de garde Urgencesvet";
		//=========
		//=====Création du header de l'e-mail
		$header = "From: \"".$nom_serveur_mail."\"<".$mail_serveur.">".$passage_ligne;
		$header .= "Reply-to: \"".$nom_serveur_mail."\" <".$mail_serveur.">".$passage_ligne;
		$header .= "MIME-Version: 1.0".$passage_ligne;
		$header .= "Content-Type:  multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
		//=====Création du message.
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
		$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
		//=====Ajout du message au format texte.
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_txt.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
		//=====Ajout du message au format HTML
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_html.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
	
		//==========
		
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout de la pièce jointe analyse.
			$message.= "Content-Type: application/pdf; name=\"".$files_name[count($files_name)-1]."\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
			$message.= "Content-Disposition: attachment; filename=\"".$files_name[count($files_name)-1]."\"".$passage_ligne;
			$message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
	
			//==========
		
		
	
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		//=====Envoi de l'e-mail.
		mail( $_POST['adresse'],utf8_decode($sujet),$message,$header);
		//==========
	
		echo json_encode('ok');
}elseif ($_GET['action']=='imprimer_planning'){	
	$filename = '../sauvegarde/clinique/planning/'.$_SESSION['login'];
	if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
			die('Echec lors de la création des répertoires...');
		}
	}
	$pdf = new FPDF();
	
	$date_debut =  mktime(0, 0, 0, date("m",$_POST['date_debut3']/1000)  , date("d",$_POST['date_debut3']/1000), date("Y",$_POST['date_debut3']/1000));
	$date_debut_o = $date_debut;
	$date_fin =  mktime(0, 0, 0, date("m",$_POST['date_fin3']/1000), date("d",$_POST['date_fin3']/1000), date("Y",$_POST['date_fin3']/1000));
	$planning = requetemysql::liste_garde2(array('debut' => $date_debut, 'fin' => $date_fin));
	
	if(empty($planning)){
		throw new Exception("Erreur dans la recherche des plannings");
	}
	$planning2 = json_decode($planning,true);
	while ($date_debut <= $date_fin) {
		if($date_debut == mktime(0, 0, 0, date("m",$date_debut),  1, date("Y",$date_debut)) || $date_debut ==$date_debut_o){
			$pdf->AddPage();
	$pdf->SetFont('Times','',12);
			$pdf->Cell(90);
			$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
			$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
			$pdf->SetFont('Times','',18);
			$pdf->SetFillColor(153,153,153);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(153,153,153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B');
			if($_POST['login']==''){
			$pdf->MultiCell(0,12,utf8_decode("Planning pour la période :".requetemysql::gestion_string_norm(strftime("%B",$date_debut))." ".requetemysql::gestion_string_norm(date("Y",$date_debut))),0,'', true);
			}else{
			$pdf->MultiCell(0,12,utf8_decode("Planning perso ".requetemysql::gestion_string_maj(htmlentities($_POST['login']))." :".requetemysql::gestion_string_norm(strftime("%B",$date_debut))." ".requetemysql::gestion_string_norm(date("Y",$date_debut))),0,'', true);
			}
			$pdf->SetFont('Times','',10);
			// Largeurs des colonnes
			$w = array(190/7, 190/7, 190/7, 190/7, 190/7, 190/7, 190/7);
			$header = $_POST['liste_jour'];
			$hauteur = 4;
			$x2 = $pdf-> GetY();
			// En-tête
			for($i=0;$i<count($header);$i++){
				$pdf->SetXY(($w[$i]*$i)+10, $x2);
				$pdf->MultiCell($w[$i],$hauteur,$header[$i]["nom"],0,'C');
				if($header[$i]["valeur"]==date("w",$date_debut)){
					$case_vide=$i-1;
				}
			}
			$pdf->Ln();
			$x2 = $pdf-> GetY();
			if($case_vide>=0){
				for($i=0;$i<=$case_vide;$i++){
					$pdf->SetXY(($w[$i]*$i)+10, $x2);
					$pdf->MultiCell($w[0],$hauteur,"--",'LRTB','C');
	
				}
			}
			$x3 = (($case_vide+1)*$w[$i])+10;
			$max_y = 0;
		}
			
		// Données
		$pdf->SetFont('Times','',8);
			
		$trouve=false;
	
		$mon_texte = date("d",$date_debut)."\n";
		foreach($planning2 as $row)
		{
			if($date_debut==($row['ma_date'])){
				if($_POST['login']==''){
				$mon_texte .= ($row['nature']==0 ? "G" : "A").": ".$row['login']." ".$row['start_heure']."h-".$row['end_heure']."h \n" ;
				}else{
					if($row['login']==$_POST['login']){
						$mon_texte .= ($row['nature']==0 ? "G" : "A").": ".$row['login']." ".$row['start_heure']."-".$row['end_heure']." \n" ;
					}else{
				//		$mon_texte .= ($row['nature']==0 ? "G" : "A").": ".$row['start_heure']."-".$row['end_heure']." \n" ;
					}
				}
				$trouve=true;
			}
	
	
		}
		if($trouve==true){
			$pdf->SetXY($x3, $x2);
			$pdf->MultiCell($w[0],$hauteur,requetemysql::gestion_string_maj($mon_texte),'LRTB','C',false);
			$max_y2 = $pdf-> GetY();
			if($max_y2>$max_y){
				$max_y = $max_y2;
			}
		}
		if($trouve==false){
			$pdf->SetXY($x3, $x2);
			$pdf->Cell($w[0],$hauteur,requetemysql::gestion_string_maj(date("d",$date_debut)),'LRTB','C',false);
			$max_y2 = $pdf-> GetY();
			if($max_y2>$max_y){
				$max_y = $max_y2;
			}
		}
		if(date("w",$date_debut)==$header[(count($header)-1)]["valeur"]){
			$x2 = $max_y;
			$x2 += $hauteur;
			$x3 =  10;
			$max_y = 0;
		}else{
			$x3 += $w[0];
		}
			
		$date_debut =   mktime(0, 0, 0, date("m",$date_debut),  date("d",$date_debut)+1, date("Y",$date_debut));
	}
	if($_POST['login']!=''){	
		
		$date_debut =  mktime(0, 0, 0, date("m",$_POST['date_debut3']/1000)  , date("d",$_POST['date_debut3']/1000), date("Y",$_POST['date_debut3']/1000));
		$date_debut_o = $date_debut;
		$date_fin =  mktime(0, 0, 0, date("m",$_POST['date_fin3']/1000), date("d",$_POST['date_fin3']/1000), date("Y",$_POST['date_fin3']/1000));
		$planning = requetemysql::liste_garde2(array('debut' => $date_debut, 'fin' => $date_fin));
		
		if(empty($planning)){
			throw new Exception("Erreur dans la recherche des plannings");
		}
		$planning2 = json_decode($planning,true);
		while ($date_debut <= $date_fin) {
			if($date_debut == mktime(0, 0, 0, date("m",$date_debut),  1, date("Y",$date_debut)) || $date_debut ==$date_debut_o){
				$pdf->AddPage();
				$pdf->SetFont('Times','',12);
				$pdf->Cell(90);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
				$pdf->SetFont('Times','',18);
				$pdf->SetFillColor(153,153,153);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetDrawColor(153,153,153);
				$pdf->SetLineWidth(.3);
				$pdf->SetFont('','B');
				$pdf->MultiCell(0,12,utf8_decode("Planning général :".requetemysql::gestion_string_norm(strftime("%B",$date_debut))." ".requetemysql::gestion_string_norm(date("Y",$date_debut))),0,'', true);
				$pdf->SetFont('Times','',10);
				// Largeurs des colonnes
				$w = array(190/7, 190/7, 190/7, 190/7, 190/7, 190/7, 190/7);
				$header = $_POST['liste_jour'];
				$hauteur = 4;
				$x2 = $pdf-> GetY();
				// En-tête
				for($i=0;$i<count($header);$i++){
					$pdf->SetXY(($w[$i]*$i)+10, $x2);
					$pdf->MultiCell($w[$i],$hauteur,$header[$i]["nom"],0,'C');
					if($header[$i]["valeur"]==date("w",$date_debut)){
						$case_vide=$i-1;
					}
				}
				$pdf->Ln();
				$x2 = $pdf-> GetY();
				if($case_vide>=0){
					for($i=0;$i<=$case_vide;$i++){
						$pdf->SetXY(($w[$i]*$i)+10, $x2);
						$pdf->MultiCell($w[0],$hauteur,"--",'LRTB','C');
		
					}
				}
				$x3 = (($case_vide+1)*$w[$i])+10;
				$max_y = 0;
			}
				
			// Données
			$pdf->SetFont('Times','',8);
				
			$trouve=false;
		
			$mon_texte = date("d",$date_debut)."\n";
			foreach($planning2 as $row)
			{
				if($date_debut==($row['ma_date'])){
					
					$mon_texte .= ($row['nature']==0 ? "G" : "A").": ".$row['login']." ".$row['start_heure']."h-".$row['end_heure']."h \n" ;
					
					$trouve=true;
				}
		
		
			}
			if($trouve==true){
				$pdf->SetXY($x3, $x2);
				$pdf->MultiCell($w[0],$hauteur,requetemysql::gestion_string_maj($mon_texte),'LRTB','C',false);
				$max_y2 = $pdf-> GetY();
				if($max_y2>$max_y){
					$max_y = $max_y2;
				}
			}
			if($trouve==false){
				$pdf->SetXY($x3, $x2);
				$pdf->Cell($w[0],$hauteur,requetemysql::gestion_string_maj(date("d",$date_debut)),'LRTB','C',false);
				$max_y2 = $pdf-> GetY();
				if($max_y2>$max_y){
					$max_y = $max_y2;
				}
			}
			if(date("w",$date_debut)==$header[(count($header)-1)]["valeur"]){
				$x2 = $max_y;
				$x2 += $hauteur;
				$x3 =  10;
				$max_y = 0;
			}else{
				$x3 += $w[0];
			}
				
			$date_debut =   mktime(0, 0, 0, date("m",$date_debut),  date("d",$date_debut)+1, date("Y",$date_debut));
		}	
		
		$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
		if(empty($info_veto)){
			throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
		}else{
			$info_veto = json_decode($info_veto, true);
		}
		$info_veto2 = requetemysql::info_veterinaire(array('login'=>strtolower($_POST['login'])));
		if(empty($info_veto2)){
			throw new Exception("Erreur dans la recherche des informations sur le vétérinaire cible");
		}else{
			$info_veto2 = json_decode($info_veto2, true);
		}
		$pdf->AddPage();
		$pdf->Image('../image/logo/essai1.jpg',10,6,30);
		$pdf->SetFont('Times','',18);
		$titre3=utf8_decode(stripslashes(ucfirst($info_veto[0]['nom'])));
		$w=$pdf->GetStringWidth(stripslashes($titre3))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,$titre3,0,'C');
		$pdf->Ln();
		$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['adresse'])))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['adresse'])),0,'C');
		$pdf->Ln();
		$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])),0,'C');
		$pdf->Ln();
		$pdf->SetFont('Times','',12);
		$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
		$pdf->Ln(15);
		$pdf->Cell(90);
		$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_veto2[0]['nom'])."\n".requetemysql::gestion_string_norm($info_veto2[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_veto2[0]['code']).' '.requetemysql::gestion_string_norm($info_veto2[0]['commune']),0,'C');
		$pdf->Ln(25);
		$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
		$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
		$pdf->SetFont('Times','',18);
		$pdf->SetFillColor(153,153,153);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetDrawColor(153,153,153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->MultiCell(0,12,utf8_decode("Liste des gardes et des astreintes qui sont attribuées à :".requetemysql::gestion_string_maj(htmlentities($_POST['login']))." sur la période du :".requetemysql::gestion_string_norm($_POST['date_debut'])." au ".requetemysql::gestion_string_norm($_POST['date_fin'])),0,'', true);
		$pdf->SetFont('Times','',12);
		// Largeurs des colonnes
		$w = array(190/6, 190/6, 190/10, 190/10, 190/10, 190/6, 190/6);
		$header = array(utf8_decode('Date'), utf8_decode('Nature'), utf8_decode("nuit sem"), utf8_decode("nuit we"), utf8_decode("jour we"), utf8_decode('heure de début'), utf8_decode('heure de fin'));
		
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
			// Données
			$pdf->SetFont('Times','',8);
			foreach($planning2 as $row)
				{
					if($row['login']==$_POST['login']){
						$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['ma_date2']),'LR',0,'C');
						$pdf->Cell($w[1],6,requetemysql::gestion_string_norm(($row['nature']==0 ? "garde" : "astreinte")),'LR',0,'R');
						$pdf->Cell($w[2],6,requetemysql::gestion_string_norm(touvelejour(date("w",$row['date_debut']), date("G",$row['date_debut']))==1 ? "X" : "" ),'LR',0,'R');
						$pdf->Cell($w[3],6,requetemysql::gestion_string_norm(touvelejour(date("w",$row['date_debut']), date("G",$row['date_debut']))==2 ? "X" : "" ),'LR',0,'R');
						$pdf->Cell($w[4],6,requetemysql::gestion_string_norm(touvelejour(date("w",$row['date_debut']), date("G",$row['date_debut']))==3 ? "X" : "" ),'LR',0,'R');
						$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['start_heure']),'LR',0,'R');
						$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($row['end_heure']),'LR',0,'R');
						$pdf->Ln();
					}
				
			}
			// Trait de terminaison
			$pdf->Cell(array_sum($w),0,'','T');
			$pdf->Ln(20);		
			
	}
	$mon_url = '../sauvegarde/clinique/planning/'.$_SESSION['login'].'/planning_'.requetemysql::gestion_string_norm($_POST['date_debut2'])."_".requetemysql::gestion_string_norm($_POST['date_fin2']).uniqid().'.pdf';
	//$pdf->Output($mon_url, F);
	$pdf->Output($mon_url, F);
	echo json_encode($mon_url);
	
	
}elseif ($_GET['action']=='document'){
	$planning = json_decode($_POST['planning'],true);
	$planning2 = json_decode($_POST['planning2'],true);
	$base1 = json_decode($_POST['base1'],true);
	$base2 = json_decode($_POST['base2'],true);
	$contrainte1 = json_decode($_POST['contrainte1'],true);
	$contrainte2 = json_decode($_POST['contrainte2'],true);
	$contrainte3 = json_decode($_POST['contrainte3'],true);
	$contrainte4 = json_decode($_POST['contrainte4'],true);
	$filename = '../sauvegarde/clinique/planning/'.$_SESSION['login'];
	if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
			die('Echec lors de la création des répertoires...');
		}
	}
	$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
	if(empty($info_veto)){
		throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
	}else{
		$info_veto = json_decode($info_veto, true);
	}
	$pdf = new FPDF();
	$pdf->AliasNbPages();	
	setlocale(LC_TIME, 'fra', 'fr_FR');	
	
	
	$pdf->AddPage();
	$pdf->Image('../image/logo/essai1.jpg',10,6,30);
	$pdf->SetFont('Times','',18);
	$titre3=utf8_decode(stripslashes(ucfirst($info_veto[0]['nom'])));
	$w=$pdf->GetStringWidth(stripslashes($titre3))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,$titre3,0,'C');
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['adresse'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['adresse'])),0,'C');
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])),0,'C');
	$pdf->Ln();
	$pdf->SetFont('Times','',12);
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
	$pdf->Ln(15);
	$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
	$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
	$pdf->SetFont('Times','',18);
	$pdf->SetFillColor(153,153,153);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetDrawColor(153,153,153);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	$pdf->MultiCell(0,12,utf8_decode("Contrainte imposées au programme pour la création du tour de garde sur la période du :".requetemysql::gestion_string_norm($_POST['date_debut'])." au ".requetemysql::gestion_string_norm($_POST['date_fin'])),0,'', true);
	$pdf->SetFont('Times','',14);
	$pdf->Ln(15);
	$pdf->MultiCell(0,12,utf8_decode("Organisation ressource humaine journée classique"),0,'', true);	
	$pdf->SetFont('Times','',12);
	// Largeurs des colonnes
	$w = array(190/4, 190/4, 190/4, 190/4);
	$header = array(utf8_decode('Nom jour'), utf8_decode('Moment'), utf8_decode('Besoin'), utf8_decode("remarque"));
	
	// En-tête
	for($i=0;$i<count($header);$i++)
		$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
		$pdf->Ln();
		// Données
		$pdf->SetFont('Times','',8);
		foreach($base1 as $row)
		{
			
		$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row["nom_jour"]),'LR',0,'C');
		$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row["moment"]),'LR',0,'C');
		$pdf->Cell($w[2],6,requetemysql::gestion_string_maj($row["team_nom"]),'LR',0,'C');
		$pdf->Cell($w[3],6,requetemysql::gestion_string_maj(""),'LR',0,'C');
		$pdf->Ln();			
		}
		// Trait de terminaison
		$pdf->Cell(array_sum($w),0,'','T');
		$pdf->Ln(20);	

		
		
		$pdf->SetFont('Times','',14);
		$pdf->Ln(15);
		$pdf->MultiCell(0,12,utf8_decode("Importance spéciale dans le tour de garde"),0,'', true);
		$pdf->SetFont('Times','',12);
		// Largeurs des colonnes
		$w = array(190/4, 190/4);
		$header = array(utf8_decode('veto'), utf8_decode('importance'));
		
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
			// Données
			$pdf->SetFont('Times','',8);
			foreach($contrainte4 as $row)
			{
				
			$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row["login"]),'LR',0,'C');
			$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row["importance"]),'LR',0,'C');
			$pdf->Ln();
			}
			// Trait de terminaison
			$pdf->Cell(array_sum($w),0,'','T');
			$pdf->Ln(20);			
		$pdf->SetFont('Times','',14);
		$pdf->MultiCell(0,12,utf8_decode("Organisation ressource humaine journée spéciale"),0,'', true);
		$pdf->SetFont('Times','',12);
		// Largeurs des colonnes
		$w = array(190/4, 190/4, 190/4, 190/4);
		$header = array(utf8_decode('Date jour'), utf8_decode('Moment'), utf8_decode('Besoin'), utf8_decode("remarque"));
		
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
			// Données
			$pdf->SetFont('Times','',8);
			foreach($base2 as $row)
			{				
				$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row["date_jour_ferie"]),'LR',0,'C');
				$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row["moment"]),'LR',0,'C');
				$pdf->Cell($w[2],6,requetemysql::gestion_string_maj($row["team_nom"]),'LR',0,'C');
				$pdf->Cell($w[3],6,requetemysql::gestion_string_maj(""),'LR',0,'C');
				$pdf->Ln();
			}
			// Trait de terminaison
			$pdf->Cell(array_sum($w),0,'','T');
			$pdf->Ln(20);		
			$pdf->SetFont('Times','',14);
			$pdf->MultiCell(0,12,utf8_decode("Membres actifs sur période"),0,'', true);
			$pdf->SetFont('Times','',12);
			// Largeurs des colonnes
			$w = array(190/5, 190/10, 190/5, 190/5, 190/5, 190/10);
			$header = array(utf8_decode('Identifiant'), utf8_decode('Catégorie'), utf8_decode('Début'), utf8_decode('Fin'), utf8_decode("Moment"), utf8_decode("Remarque"));
			
			// En-tête
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
				$pdf->Ln();
				// Données
				$pdf->SetFont('Times','',8);
						foreach($contrainte1 as $row)
						{
						$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row["login"]),'LR',0,'C');
						$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row["cat"]),'LR',0,'C');
						$pdf->Cell($w[2],6,requetemysql::gestion_string_maj($row["debut2"]),'LR',0,'C');
						$pdf->Cell($w[3],6,requetemysql::gestion_string_maj($row["fin2"]),'LR',0,'C');
						$pdf->Cell($w[4],6,requetemysql::gestion_string_maj($row["choix_horaire"]),'LR',0,'C');
						$pdf->Cell($w[5],6,requetemysql::gestion_string_maj(""),'LR',0,'C');
						$pdf->Ln();
				}
				// Trait de terminaison
				$pdf->Cell(array_sum($w),0,'','T');
				$pdf->Ln(20);					
				$pdf->SetFont('Times','',14);
				$pdf->MultiCell(0,12,utf8_decode("Membres indisponible sur période"),0,'', true);
				$pdf->SetFont('Times','',12);
				// Largeurs des colonnes
				$w = array(190/4, 190/4, 190/4, 190/4);
				$header = array(utf8_decode('Identifiant'), utf8_decode('Début'), utf8_decode('Fin'), utf8_decode("Remarque"));
					
				// En-tête
				for($i=0;$i<count($header);$i++)
					$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
					$pdf->Ln();
					// Données
					$pdf->SetFont('Times','',8);
					foreach($contrainte2 as $row)
					{
					$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row["login"]),'LR',0,'C');
					$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row["debut2"]),'LR',0,'C');
					$pdf->Cell($w[2],6,requetemysql::gestion_string_maj($row["fin2"]),'LR',0,'C');
					$pdf->Cell($w[3],6,requetemysql::gestion_string_maj(""),'LR',0,'C');
					$pdf->Ln();
					}
					// Trait de terminaison
					$pdf->Cell(array_sum($w),0,'','T');
					$pdf->Ln(20);					
					$pdf->SetFont('Times','',14);
					$pdf->MultiCell(0,12,utf8_decode("Membres sélectionnés dans le tour de garde"),0,'', true);
					$pdf->SetFont('Times','',12);
					// Largeurs des colonnes
					$w = array(190/9, 190/3, 190/9, 190/3, 190/9);
					$header = array(utf8_decode('Identifiant'), utf8_decode('jour demandé'), utf8_decode('fréquence'), utf8_decode("jour évité"), utf8_decode("remarque"));
						
					// En-tête
					for($i=0;$i<count($header);$i++)
						$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
						$pdf->Ln();
						// Données
						$pdf->SetFont('Times','',8);
								foreach($contrainte3 as $row)
								{
						$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row["login"]),'LR',0,'C');
						$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row["jour_favo2"]),'LR',0,'C');
						$pdf->Cell($w[2],6,requetemysql::gestion_string_maj($row["rythme2"]),'LR',0,'C');
						$pdf->Cell($w[3],6,requetemysql::gestion_string_maj($row["jour_evi2"]),'LR',0,'C');						
						$pdf->Cell($w[4],6,requetemysql::gestion_string_maj(""),'LR',0,'C');
						$pdf->Ln();
						}
						// Trait de terminaison
						$pdf->Cell(array_sum($w),0,'','T');
						$pdf->Ln(20);	
	
	$date_debut =  mktime(0, 0, 0, date("m",$_POST['date_debut3']/1000)  , 1, date("Y",$_POST['date_debut3']/1000));
	$date_debut_o = $date_debut;
	$date_fin =  mktime(0, 0, 0, date("m",$_POST['date_fin3']/1000)+1  , 1, date("Y",$_POST['date_fin3']/1000));
	while ($date_debut <= $date_fin) {
		if($date_debut == mktime(0, 0, 0, date("m",$date_debut),  1, date("Y",$date_debut)) || $date_debut==$date_debut_o){
			$pdf->AddPage();
			$pdf->Image('../image/logo/essai1.jpg',10,6,30);
			$pdf->Cell(90);
			$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
			$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
			$pdf->SetFont('Times','',18);
			$pdf->SetFillColor(153,153,153);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(153,153,153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B');
			$pdf->MultiCell(0,12,utf8_decode("Planning pour la période :".requetemysql::gestion_string_norm(strftime("%B",$date_debut))." ".requetemysql::gestion_string_norm(date("Y",$date_debut))),0,'', true);
			$pdf->SetFont('Times','',11);
			
			$pdf->Ln();
			// Largeurs des colonnes
			$w = array(190/7, 190/7, 190/7, 190/7, 190/7, 190/7, 190/7);
			$header = $_POST['liste_jour'];
			$hauteur = 5;
			$x2 = $pdf-> GetY();
			// En-tête
			for($i=0;$i<count($header);$i++){				
				$pdf->SetXY(($w[$i]*$i)+10, $x2);
				$pdf->MultiCell($w[$i],$hauteur,$header[$i]["nom"],0,'C');
				if($header[$i]["valeur"]==date("w",$date_debut)){
					$case_vide=$i-1;
				}
			}
			$pdf->Ln();
			$x2 = $pdf-> GetY();
			if($case_vide>=0){
				for($i=0;$i<=$case_vide;$i++){
					$pdf->SetXY(($w[$i]*$i)+10, $x2);
					$pdf->MultiCell($w[0],$hauteur,"--",'LRTB','C');
	
				}
			}
			$x3 = (($case_vide+1)*$w[$i])+10;
			$max_y = 0;
		}
			
		// Données
		$pdf->SetFont('Times','',8);
			
		$trouve=false;
		
		$mon_texte = date("d",$date_debut)."\n";
		foreach($planning2 as $row)
		{
			if($date_debut==($row[2]/1000)){
				$mon_texte .= ($row[1]==0 ? "G" : "A").": ".$row[0]." ".date("H",$row[3]/1000)."h-".date("H",$row[4]/1000)."h \n" ;
				$trouve=true;
			}
	
	
		}
		if($trouve==true){
			$pdf->SetXY($x3, $x2);
			$pdf->MultiCell($w[0],$hauteur,requetemysql::gestion_string_maj($mon_texte),'LRTB','C',false);
			$max_y2 = $pdf-> GetY();
			if($max_y2>$max_y){
				$max_y = $max_y2;
			}
		}
		if($trouve==false){
			$pdf->SetXY($x3, $x2);
			$pdf->Cell($w[0],$hauteur,requetemysql::gestion_string_maj(date("d",$date_debut)),'LRTB','C',false);
			$max_y2 = $pdf-> GetY();
			if($max_y2>$max_y){
				$max_y = $max_y2;
			} 
		}
		if(date("w",$date_debut)==$header[(count($header)-1)]["valeur"]){
			$x2 = $max_y;
			$x2 += $hauteur;
			$x3 =  10;
			$max_y = 0;
		}else{
			$x3 += $w[0];
		}
			
		$date_debut =   mktime(0, 0, 0, date("m",$date_debut),  date("d",$date_debut)+1, date("Y",$date_debut));
	}
	
	
	
	
	while (list($key_planning, $value_planning) = each($planning))
	{
	$info_veto2 = requetemysql::info_veterinaire(array('login'=>strtolower($value_planning['login'])));
	if(empty($info_veto2)){
		throw new Exception("Erreur dans la recherche des informations sur le vétérinaire cible");
	}else{
		$info_veto2 = json_decode($info_veto2, true);
	}	
	$pdf->AddPage();
	$pdf->Image('../image/logo/essai1.jpg',10,6,30);
	$pdf->SetFont('Times','',18);
	$titre3=utf8_decode(stripslashes(ucfirst($info_veto[0]['nom'])));
	$w=$pdf->GetStringWidth(stripslashes($titre3))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,$titre3,0,'C');
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['adresse'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['adresse'])),0,'C');
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])),0,'C');
	$pdf->Ln();
	$pdf->SetFont('Times','',12);
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
	$pdf->Ln(15);
	$pdf->Cell(90);
	$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_veto2[0]['nom'])."\n".requetemysql::gestion_string_norm($info_veto2[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_veto2[0]['code']).' '.requetemysql::gestion_string_norm($info_veto2[0]['commune']),0,'C');
	$pdf->Ln(25);
	$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
	$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
	$pdf->SetFont('Times','',18);
	$pdf->SetFillColor(153,153,153);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetDrawColor(153,153,153);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	$pdf->MultiCell(0,12,utf8_decode("Liste des gardes et des astreintes qui sont attribuées à :".requetemysql::gestion_string_maj(htmlentities($value_planning['login']))." sur la période du :".requetemysql::gestion_string_norm($_POST['date_debut'])." au ".requetemysql::gestion_string_norm($_POST['date_fin'])),0,'', true);
	$pdf->SetFont('Times','',12);
	// Largeurs des colonnes
	$w = array(190/6, 190/6, 190/6, 190/6, 190/4);
	$header = array(utf8_decode('Date'), utf8_decode('Nature'), utf8_decode('heure de début'), utf8_decode('heure de fin'), utf8_decode("remarque"));
		
	// En-tête
		for($i=0;$i<count($header);$i++)
		$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
		$pdf->Ln();
		// Données
		$pdf->SetFont('Times','',8);
		foreach($value_planning['mon_tableau'] as $row)
		{
			
				$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(date("d-m-Y",$row[2]/1000)),'LR',0,'C');
				$pdf->Cell($w[1],6,requetemysql::gestion_string_norm(($row[1]==0 ? "garde" : "astreinte")),'LR',0,'R');
				$pdf->Cell($w[2],6,requetemysql::gestion_string_norm(date("d-m-Y H:i",$row[3]/1000)),'LR',0,'R');
				$pdf->Cell($w[3],6,requetemysql::gestion_string_norm(date("d-m-Y H:i",$row[4]/1000)),'LR',0,'R');
				$pdf->Cell($w[4],6,requetemysql::gestion_string_norm(""),'LR',0,'C');
				$pdf->Ln();		
			
		}
		// Trait de terminaison
		$pdf->Cell(array_sum($w),0,'','T');
		$pdf->Ln(20);
		setlocale(LC_TIME, 'fra', 'fr_FR');
		$date_debut =  mktime(0, 0, 0, date("m",$_POST['date_debut3']/1000)  , 1, date("Y",$_POST['date_debut3']/1000));
		$date_fin =  mktime(0, 0, 0, date("m",$_POST['date_fin3']/1000)+1  , 1, date("Y",$_POST['date_fin3']/1000));	
		while ($date_debut <= $date_fin) {
			if($date_debut == mktime(0, 0, 0, date("m",$date_debut),  1, date("Y",$date_debut))){
				$pdf->AddPage();
				$pdf->Image('../image/logo/essai1.jpg',10,6,30);
				$pdf->SetFont('Times','',18);
				$titre3=utf8_decode(stripslashes(ucfirst($info_veto[0]['nom'])));
				$w=$pdf->GetStringWidth(stripslashes($titre3))+6;
				$pdf->SetX((210-$w)/2);
				$pdf->Cell($w,7,$titre3,0,'C');
				$pdf->Ln();
				$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['adresse'])))+6;
				$pdf->SetX((210-$w)/2);
				$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['adresse'])),0,'C');
				$pdf->Ln();
				$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])))+6;
				$pdf->SetX((210-$w)/2);
				$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['code']." ".$info_veto[0]['commune'])),0,'C');
				$pdf->Ln();
				$pdf->SetFont('Times','',12);
				$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
				$pdf->SetX((210-$w)/2);
				$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
				$pdf->Ln(15);
				$pdf->Cell(90);
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_veto2[0]['nom'])."\n".requetemysql::gestion_string_norm($info_veto2[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_veto2[0]['code']).' '.requetemysql::gestion_string_norm($info_veto2[0]['commune']),0,'C');
				$pdf->Ln(25);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
				$pdf->SetFont('Times','',18);
				$pdf->SetFillColor(153,153,153);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetDrawColor(153,153,153);
				$pdf->SetLineWidth(.3);
				$pdf->SetFont('','B');
				$pdf->MultiCell(0,12,utf8_decode("Planning pour la période :".requetemysql::gestion_string_norm(strftime("%B",$date_debut))." ".requetemysql::gestion_string_norm(date("Y",$date_debut)).", concernant : ".requetemysql::gestion_string_maj(htmlentities($value_planning['login']))),0,'', true);
				$pdf->SetFont('Times','',12);
				// Largeurs des colonnes
				$w = array(190/7, 190/7, 190/7, 190/7, 190/7, 190/7, 190/7);
				$header = $_POST['liste_jour'];
				
				// En-tête
				for($i=0;$i<count($header);$i++){
					$pdf->Cell($w[$i],7,$header[$i]["nom"],1,0,'C');
					if($header[$i]["valeur"]==date("w",$date_debut)){
						$case_vide=$i-1;
					}
				}
					$pdf->Ln();	
					if($case_vide>=0){
						for($i=0;$i<=$case_vide;$i++){
							$pdf->Cell($w[0],6,"--",'LRTB',0,'C');
								
						}
					}			
			}
			
			// Données
			$pdf->SetFont('Times','',8);
			
			$trouve=false;
			foreach($value_planning['mon_tableau'] as $row)
			{
				if($date_debut==($row[2]/1000)){
				$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(date("d",$date_debut)." ".($row[1]==0 ? "G" : "A")." ".date("H:i",$row[3]/1000)."h-".date("H:i",$row[4]/1000)."h"),'LRTB',0,'C',true);				
				$trouve=true;
				}					
				
								
			}
			if($trouve==false){
				$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(date("d",$date_debut)),'LRTB',0,'C',false);				
			}
			if(date("w",$date_debut)==$header[(count($header)-1)]["valeur"]){
				$pdf->Ln();				
			}		
			
			$date_debut =   mktime(0, 0, 0, date("m",$date_debut),  date("d",$date_debut)+1, date("Y",$date_debut));
		}
		
	}
		$mon_url = '../sauvegarde/clinique/planning/'.$_SESSION['login'].'/planning_'.requetemysql::gestion_string_norm($_POST['date_debut2'])."_".requetemysql::gestion_string_norm($_POST['date_fin2']).uniqid().'.pdf';
		//$pdf->Output($mon_url, F);
		$pdf->Output($mon_url, F);
		echo json_encode($mon_url);
	
}
function touvelejour($jour, $heure){
	
	if(($jour==0 || $jour==1 || $jour==6) && $heure==0){
		return 2;		
	}else if(($jour==2 || $jour==3 || $jour==4 || $jour==5) && $heure==0){
		return 1;
	}else if(($jour==6 || $jour==0) && $heure!=0){
		return 3;		
	}else if(($jour==1 || $jour==2 || $jour==3 || $jour==4 || $jour==5) && $heure!=0){
		return 1;		
	}
}
?>