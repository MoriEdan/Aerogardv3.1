<?php
/* il faut demarrer la session*/
session_start();
if (empty($_SESSION['id'])){

	header('HTTP/1.1 400 Bad Request');
	exit("votre session a expiré. Reconnectez-vous !!");

}elseif(!empty($_SESSION['id'])){
require_once "config.php";
require_once "connexionmysql.php";
require('fpdf/fpdf.php');
require('fpdi/fpdi.php');
require_once "requetemysql.php";
$data3= $_GET['action'];

if($data3=="brouillard"){
	$recup_brouillard = requetemysql::brouillard(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000), 'choix'=>'total' ));
	echo $recup_brouillard;	
	
}elseif($data3=="print_duclient"){
	
		$liste_duclient = json_decode($_POST['liste_duclient'],true);
	
		$filename = '../sauvegarde/clinique/duclient/'.$_SESSION['login'];
		$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
		if(empty($info_veto)){
			throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
		}else{
			$info_veto = json_decode($info_veto, true);
		}
		if (!file_exists($filename)) {
			if (!mkdir($filename, 0755, true)) {
				die('Echec lors de la création des répertoires...');
			}
		}
			
			
		$pdf = new FPDF();
		$pdf->AliasNbPages();
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
		$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
		$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
		$pdf->SetFont('Times','',18);
		$pdf->SetFillColor(153,153,153);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetDrawColor(153,153,153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->MultiCell(0,12,utf8_decode("consultation non réglées sur la période du :".$_POST['date_debut']." au ".$_POST['date_fin']),0,'', true);
		$pdf->SetFont('Times','',12);
		// Largeurs des colonnes
		$w = array(190/8, 190/2, 190/8, 190/8, 190/8);
		$header = array(utf8_decode('Date'), utf8_decode('nom'), utf8_decode('montant tot'), utf8_decode('montant du'), utf8_decode("consultation"));
			
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
			// Données
			$pdf->SetFont('Times','',8);
			foreach($liste_duclient as $row)
			{
			$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date']),'LR',0,'C');
			$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['nom']),'LR',0,'R');
			$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['montant_r']),'LR',0,'R');
			$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['montant_d']),'LR',0,'R');
			$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['id_c']),'LR',0,'C');
			$pdf->Ln();
				
			}
			// Trait de terminaison
			$pdf->Cell(array_sum($w),0,'','T');
			$pdf->Ln(8);
								
					$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
					$pdf->MultiCell(0,20,"",'TRB','L', false);
					$pdf->Ln(8);
					$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
					$pdf->MultiCell(0,20,"",'TRB','L', false);
					$mon_url = '../sauvegarde/clinique/duclient/'.$_SESSION['login'].'/totaux_'.date("d_m_y").uniqid().'.pdf';
					//$pdf->Output($mon_url, F);
					$pdf->Output($mon_url, F);
					echo json_encode($mon_url);
	
}elseif($data3=="print_totaux"){
	
	$liste_totaux_jour = json_decode($_POST['liste_totaux_jour'],true);
		
			$filename = '../sauvegarde/clinique/totaux/'.$_SESSION['login'];
			$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
			if(empty($info_veto)){
				throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
			}else{
				$info_veto = json_decode($info_veto, true);
			}
			if (!file_exists($filename)) {
				if (!mkdir($filename, 0755, true)) {
					die('Echec lors de la création des répertoires...');
				}
			}
			
			
			$pdf = new FPDF();
			$pdf->AliasNbPages();
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
			$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
			$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
			$pdf->SetFont('Times','',18);
			$pdf->SetFillColor(153,153,153);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(153,153,153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B');
			$pdf->MultiCell(0,12,utf8_decode("totaux sur la période du :".$_POST['date_debut']." au ".$_POST['date_fin']),0,'', true);
			$pdf->SetFont('Times','',12);
			// Largeurs des colonnes
			$w = array(190/8, 190/8, 190/8, 190/8, 190/8, 190/8, 190/8);
			$header = array(utf8_decode('Date'), utf8_decode('tot ttc'), utf8_decode('tot ht'), utf8_decode('tot tva'), utf8_decode("espece"), utf8_decode("cheque"), utf8_decode("carte"), utf8_decode("virement"));
			
			// En-tête
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
				$pdf->Ln();
				// Données
				$pdf->SetFont('Times','',8);
						foreach($liste_totaux_jour as $row)
						{
							$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date']),'LR',0,'C');
							$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['totalttc']),'LR',0,'R');
							$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['totalht']),'LR',0,'R');
							$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['totaltva']),'LR',0,'R');
							$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['espece']),'LR',0,'C');
							$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['cheque']),'LR',0,'R');
							$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($row['carte']),'LR',0,'R');
							$pdf->Cell($w[7],6,requetemysql::gestion_string_norm($row['virement']),'LR',0,'R');
							$pdf->Ln();
							
						}
							// Trait de terminaison
							$pdf->Cell(array_sum($w),0,'','T');
							$pdf->Ln(8);
							$pdf->SetFont('Times','',18);
							$pdf->MultiCell(0,12,utf8_decode("Totaux :"),0,L, true);
							$pdf->SetFont('Times','',10);
							$pdf->Ln(8);		
							$header2 = array(utf8_decode(''), utf8_decode('tot ttc'), utf8_decode('tot ht'), utf8_decode('tot tva'), utf8_decode("espece"), utf8_decode("cheque"), utf8_decode("carte"), utf8_decode("virement"));
							// En-tête
							for($i=0;$i<count($header2);$i++)
								$pdf->Cell($w[$i],7,$header2[$i],1,0,'C');
								$pdf->Ln();
							$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(""),'LRTB',0,'C');
							$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($_POST['mon_total_ttc']),'LRTB',0,'R');
							$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($_POST['mon_total_ht']),'LRTB',0,'R');
							$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($_POST['mon_total_tva']),'LRTB',0,'R');
							$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($_POST['espece']),'LRTB',0,'C');
							$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($_POST['cheque']),'LRTB',0,'R');
							$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($_POST['carte']),'LRTB',0,'R');
							$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($_POST['virement']),'LRTB',0,'R');
							$pdf->Ln(8);							
							$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
							$pdf->MultiCell(0,20,"",'TRB','L', false);
							$pdf->Ln(8);
							$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
							$pdf->MultiCell(0,20,"",'TRB','L', false);
							$mon_url = '../sauvegarde/clinique/totaux/'.$_SESSION['login'].'/totaux_'.date("d_m_y").uniqid().'.pdf';
							//$pdf->Output($mon_url, F);
							$pdf->Output($mon_url, F);
							echo json_encode($mon_url);	
	
}elseif($data3=="print_brouillard"){
	
	$liste_brouillard_date = json_decode($_POST['date'],true);
	$liste_brouillard = json_decode($_POST['brouillard'],true);
	$mes_totaux = array("total" => 0, "espece" => 0, "cheque" => 0, "carte" => 0);
	
			$filename = '../sauvegarde/clinique/brouillard_caisse/'.$_SESSION['login'];
			$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
			if(empty($info_veto)){
				throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
			}else{
				$info_veto = json_decode($info_veto, true);
			}
			if (!file_exists($filename)) {
				if (!mkdir($filename, 0755, true)) {
					die('Echec lors de la création des répertoires...');
				}
			}
			
			
			$pdf = new FPDF();
			$pdf->AliasNbPages();
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
			$pdf->Ln(35);
			$pdf->Cell(90);
			$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
			$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
			$pdf->SetFont('Times','',18);
			$pdf->SetFillColor(153,153,153);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(153,153,153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B');
			$pdf->MultiCell(0,12,utf8_decode("Brouillard de caisse sur la période du :".$liste_brouillard_date["date_debut"].(isset($liste_brouillard_date[0]["date_fin"]) ? (" au ".$liste_brouillard_date[0]["date_fin"]) : "")),0,'', true);
			$pdf->SetFont('Times','',12);
			// Largeurs des colonnes
			$w = array(190/7, 190/8, 190/8, 190/8, 190/4, 190/10, 190/10);
			$header = array(utf8_decode('Date paiement'), utf8_decode('Espece'), utf8_decode('Chèque'), utf8_decode('Carte'), utf8_decode("Client"), utf8_decode("Num cheq"), utf8_decode("consult"));
			
			// En-tête
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
				$pdf->Ln();
				// Données
				$pdf->SetFont('Times','',8);
						foreach($liste_brouillard as $row)
						{
							$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date_paiement']),'LR',0,'C');
							$pdf->Cell($w[1],6,requetemysql::gestion_string_norm(($row['mode']=='espece' ? $row['montant'] : "")),'LR',0,'R');
							$pdf->Cell($w[2],6,requetemysql::gestion_string_norm(($row['mode']=='cheque' ? $row['montant'] : "")),'LR',0,'R');
							$pdf->Cell($w[3],6,requetemysql::gestion_string_norm(($row['mode']=='carte' ? $row['montant'] : "")),'LR',0,'R');
							$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['nom_p']." ".$row['prenom_p']." ".$row['ville_p']),'LR',0,'C');
							$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['numero_cheque']),'LR',0,'R');
							$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($row['id_c']),'LR',0,'R');
							$pdf->Ln();
							$mes_totaux['total'] += $row['montant'];
							$mes_totaux[$row['mode']] += $row['montant'];						
							
						}
							// Trait de terminaison
							$pdf->Cell(array_sum($w),0,'','T');
							$pdf->Ln(8);
							$pdf->SetFont('Times','',18);
							$pdf->MultiCell(0,12,utf8_decode("Totaux :"),0,L, true);
							$pdf->SetFont('Times','',10);
							$pdf->Ln(8);		
							$header2 = array(utf8_decode(''), utf8_decode('Espece'), utf8_decode('Chèque'), utf8_decode('Carte'), utf8_decode(""), utf8_decode(""), utf8_decode(""));
							// En-tête
							for($i=0;$i<count($header2);$i++)
								$pdf->Cell($w[$i],7,$header2[$i],1,0,'C');
								$pdf->Ln();
							$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($mes_totaux['total']),'LRTB',0,'C');
							$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($mes_totaux['espece']),'LRTB',0,'R');
							$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($mes_totaux['cheque']),'LRTB',0,'R');
							$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($mes_totaux['carte']),'LRTB',0,'R');
							$pdf->Cell($w[4],6,requetemysql::gestion_string_norm(""),'LRTB',0,'C');
							$pdf->Cell($w[5],6,requetemysql::gestion_string_norm(""),'LRTB',0,'R');
							$pdf->Cell($w[6],6,requetemysql::gestion_string_norm(""),'LRTB',0,'R');
							$pdf->Ln(8);							
							$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
							$pdf->MultiCell(0,20,"",'TRB','L', false);
							$pdf->Ln(8);
							$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
							$pdf->MultiCell(0,20,"",'TRB','L', false);
							$mon_url = '../sauvegarde/clinique/brouillard_caisse/'.$_SESSION['login'].'/brouillard_'.date("d_m_y").uniqid().'.pdf';
							//$pdf->Output($mon_url, F);
							$pdf->Output($mon_url, F);
							echo json_encode($mon_url);
			
	
	
}elseif($data3=="print_consult"){
	$recup_consult = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto', 'veto'=>$_POST['veto']));
	$recup_consult2 = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto2', 'veto'=>$_POST['veto']));
	$recup_consult3 = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto3', 'veto'=>$_POST['veto']));
	$recup_consult4 = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto4', 'veto'=>$_POST['veto']));
	$recup_consult5 = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto5', 'veto'=>$_POST['veto']));
	
	$recup_consult = json_decode($recup_consult, true);
	$recup_consult2 = json_decode($recup_consult2, true);
	$recup_consult3 = json_decode($recup_consult3, true);
	$recup_consult4 = json_decode($recup_consult4, true);
	$recup_consult5 = json_decode($recup_consult5, true);
	
	
	$donne_init2 = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'totale'));
	$donne_init = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'total'));
	$donne_esp = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'espece'));
	$donne_cheq = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'cheque'));
	$donne_carte = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'carte'));
	$donne_vir = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'virement'));
	$donne_repartition = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $_POST['veto'], 'recherche'=> 'repartition'));
	
	
	
			
		$filename = '../sauvegarde/clinique/releve_honoraire/'.$_SESSION['login'];
		$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
		if(empty($info_veto)){
			throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
		}else{
			$info_veto = json_decode($info_veto, true);
		}
		if (!file_exists($filename)) {
			if (!mkdir($filename, 0755, true)) {
				die('Echec lors de la création des répertoires...');
			}
		}
		$pdf = new FPDF();
		$pdf->AddPage();
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
		$pdf->SetFont('Times','',12);
		$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
		$pdf->Ln(35);
		$pdf->Cell(90);
		$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
		$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
		$pdf->SetFont('Times','',18);
		$pdf->SetFillColor(153,153,153);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetDrawColor(153,153,153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->MultiCell(0,12,utf8_decode("Récapitulatif des recettes reçues par :".$_POST['veto']." sur la période du :".date("d-m-Y-H:i:s",$_POST['debut']/1000)." au ".date("d-m-Y-H:i:s",$_POST['fin']/1000)),0,'', true);
		$pdf->SetFont('Times','',12);
		// Largeurs des colonnes
		$w = array(190/6, 190/6, 190/6, 190/6, 190/6, 190/6);
		$header = array(utf8_decode('total ttc'), utf8_decode('reglement ttc'), utf8_decode('total acte'), utf8_decode('reglement acte'), utf8_decode("total medic"), utf8_decode("reglement medic"));
		$pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Résumé honoraires reçus pour ses propres consultations"),0,'L');
		$pdf->Ln();
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
		
		$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(round($donne_init[0]['totalttc'],2)),'LRB',0,'C');
		$pdf->Cell($w[1],6,requetemysql::gestion_string_norm(round($donne_init[0]['reglementttc'],2)),'LRB',0,'R');
		$pdf->Cell($w[2],6,requetemysql::gestion_string_norm(round($donne_init[0]['total_acte'],2)),'LRB',0,'R');
		$pdf->Cell($w[3],6,requetemysql::gestion_string_norm(round($donne_init[0]['reglement_acte'],2)),'LRB',0,'R');
		$pdf->Cell($w[4],6,requetemysql::gestion_string_norm(round($donne_init[0]['total_medic'],2)),'LRB',0,'R');
		$pdf->Cell($w[5],6,requetemysql::gestion_string_norm(round($donne_init[0]['reglement_medic'],2)),'LRB',0,'R');
		
			// Trait de terminaison
			
			$pdf->Ln(8);
			$pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Résumé recettes collectées pour son compte ou pas"),0,'L');
			$pdf->Ln();
			// Largeurs des colonnes
			$w = array(190/6, 190/6, 190/6, 190/6);
			$header = array(utf8_decode('total'), utf8_decode('espece'), utf8_decode('carte'), utf8_decode('cheque'));
				
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
				$pdf->Ln();
			
				$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(round($donne_init2[0]['total'],2)),'LRB',0,'C');
				$pdf->Cell($w[1],6,requetemysql::gestion_string_norm(round($donne_esp[0]['total'],2)),'LRB',0,'R');
				$pdf->Cell($w[2],6,requetemysql::gestion_string_norm(round($donne_carte[0]['total'],2)),'LRB',0,'R');
				$pdf->Cell($w[3],6,requetemysql::gestion_string_norm(round($donne_cheq[0]['total'],2)),'LRB',0,'R');
							
				// Trait de terminaison
				$pdf->Ln(8);
			
				$pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Résumé retribution honoraire"),0,'L');
				$pdf->Ln();
				// Largeurs des colonnes
				$w = array(190/6, 190/6, 190/6, 190/6);
				$header = array(utf8_decode('total retribution'));
				
				for($i=0;$i<count($header);$i++)
					$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
					$pdf->Ln();
						
					$pdf->Cell($w[0],6,requetemysql::gestion_string_maj(round($donne_repartition[0]['total'],2)),'LRB',0,'C');
					// Trait de terminaison
					
					$pdf->Ln(8);
					
					
			$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
					$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),'TRB','L', false);
							$pdf->Ln(8);
							$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
							$pdf->MultiCell(0,20,"",'TRB','L', false);
		
							
							
							
		$pdf->AddPage();
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
		$pdf->SetFont('Times','',12);
		$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
		$pdf->Ln(35);
		$pdf->Cell(90);
		$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
		$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
		$pdf->SetFont('Times','',18);
		$pdf->SetFillColor(153,153,153);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetDrawColor(153,153,153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->MultiCell(0,12,utf8_decode("Liste des recettes encaissées (pour son compte ou celui d'un autre vétérinaire) par :".$_POST['veto'].". Sur la période du :".date("d-m-Y-H:i:s",$_POST['debut']/1000)." au ".date("d-m-Y-H:i:s",$_POST['fin']/1000)),0,'', true);
		$pdf->SetFont('Times','',8);
		// Largeurs des colonnes
		$w = array(190/8, 190/8, 190/4, 190/16, 190/16, 190/16, 190/16, 190/8);
    	$header = array(utf8_decode('Date consult'), utf8_decode('Date paiement'), utf8_decode('Identité client'), utf8_decode('Montant'), utf8_decode("Mode"), utf8_decode("Num cheq"), utf8_decode("Num consult"), utf8_decode("destinataire"));
		
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
		// Données
		$pdf->SetFont('Times','',8);
		foreach($recup_consult2 as $row)
		{
		$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date_consult']),'LR',0,'C');
		$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['date_paiement']),'LR',0,'C');
		$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['nom_p']." ".$row['prenom_p']." ".$row['ville_p']),'LR',0,'C');
		$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['montant']),'LR',0,'R');
		$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['mode']),'LR',0,'R');
		$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['numero_cheque']),'LR',0,'R');
		$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($row['id_c']),'LR',0,'R');
		$pdf->Cell($w[7],6,requetemysql::gestion_string_norm($row['destinataire']),'LR',0,'R');
		$pdf->Ln();
		}
		// Trait de terminaison
		$pdf->Cell(array_sum($w),0,'','T');
		$pdf->Ln(8);
		$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
		$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),'TRB','L', false);
		$pdf->Ln(8);
		$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
		$pdf->MultiCell(0,20,"",'TRB','L', false);
		
		
		
		$pdf->AddPage();
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
		$pdf->SetFont('Times','',12);
		$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
		$pdf->SetX((210-$w)/2);
		$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
		$pdf->Ln(35);
		$pdf->Cell(90);
		$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
		$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
		$pdf->SetFont('Times','',18);
		$pdf->SetFillColor(153,153,153);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetDrawColor(153,153,153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->MultiCell(0,12,utf8_decode("Répartition des honoraires reçu par :".$_POST['veto'].". concernant Acte/medicament sur la période du :".date("d-m-Y-H:i:s",$_POST['debut']/1000)." au ".date("d-m-Y-H:i:s",$_POST['fin']/1000)),0,'', true);
		$pdf->SetFont('Times','',8);
		// Largeurs des colonnes
		$w = array(190/8, 190/3, 190/16, 190/16, 190/8, 190/8, 190/16, 190/16);
		$header = array(utf8_decode('Date consult'), utf8_decode('Identité client'), utf8_decode('total'), utf8_decode("reglement"), utf8_decode("total acte"), utf8_decode("reglement acte"), utf8_decode("total medic"), utf8_decode("reglement medic"));
		
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
			// Données
			foreach($recup_consult3 as $row)
			{
			$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date_consult']),'LR',0,'C');					
							$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['nom_p']." ".$row['prenom_p']." ".$row['ville_p']),'LR',0,'C');
							$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['totalttc']),'LR',0,'R');
							$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['reglementttc']),'LR',0,'R');
							$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['total_acte']),'LR',0,'R');
							$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['reglement_acte']),'LR',0,'R');
							$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($row['total_medic']),'LR',0,'R');
							$pdf->Cell($w[7],6,requetemysql::gestion_string_norm($row['reglement_medic']),'LR',0,'R');
							$pdf->Ln();
			}
			// Trait de terminaison
			$pdf->Cell(array_sum($w),0,'','T');
			$pdf->Ln(8);
			$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
					$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),'TRB','L', false);
							$pdf->Ln(8);
							$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
							$pdf->MultiCell(0,20,"",'TRB','L', false);
		
		
							
							
							
							$pdf->AddPage();
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
							$pdf->SetFont('Times','',12);
							$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
							$pdf->SetX((210-$w)/2);
							$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
							$pdf->Ln(35);
							$pdf->Cell(90);
							$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
							$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
							$pdf->SetFont('Times','',18);
							$pdf->SetFillColor(153,153,153);
							$pdf->SetTextColor(0,0,0);
							$pdf->SetDrawColor(153,153,153);
							$pdf->SetLineWidth(.3);
							$pdf->SetFont('','B');
							$pdf->MultiCell(0,12,utf8_decode("Liste des honoraires rétrocédés reçus ou reversés par :".$_POST['veto']." sur la période du :".date("d-m-Y-H:i:s",$_POST['debut']/1000)." au ".date("d-m-Y-H:i:s",$_POST['fin']/1000)),0,'', true);
							$pdf->SetFont('Times','',8);
							// Largeurs des colonnes
							$w = array(190/8, 190/4, 190/5, 190/8, 190/8);
							$header = array(utf8_decode('Date consult'), utf8_decode('Identité client'), utf8_decode('Veto destinataire'), utf8_decode("montant"), utf8_decode("id consult"));
							
							// En-tête
							for($i=0;$i<count($header);$i++)
								$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
								$pdf->Ln();
								// Données
								foreach($recup_consult4 as $row)
								{
								$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date_consult']),'LR',0,'C');
								$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['nom_p']." ".$row['prenom_p']." ".$row['ville_p']),'LR',0,'R');
								$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['veto_desti']),'LR',0,'R');
								$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['montant']),'LR',0,'R');
								$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['id_c']),'LR',0,'R');
								$pdf->Ln();
								}
								// Trait de terminaison
								$pdf->Cell(array_sum($w),0,'','T');
								$pdf->Ln(8);
								$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
								$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),'TRB','L', false);
								$pdf->Ln(8);
								$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
								$pdf->MultiCell(0,20,"",'TRB','L', false);
							
								
												
												
								$pdf->AddPage();
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
								$pdf->SetFont('Times','',12);
								$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
								$pdf->SetX((210-$w)/2);
								$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
								$pdf->Ln(35);
								$pdf->Cell(90);
								$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
								$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']),0,'L');
								$pdf->SetFont('Times','',18);
								$pdf->SetFillColor(153,153,153);
								$pdf->SetTextColor(0,0,0);
								$pdf->SetDrawColor(153,153,153);
								$pdf->SetLineWidth(.3);
								$pdf->SetFont('','B');
								$pdf->MultiCell(0,12,utf8_decode("Liste des honoraires reçus par :".$_POST['veto']." et correspondant à des consultations antérieures à la période du :".date("d-m-Y-H:i:s",$_POST['debut']/1000)." au ".date("d-m-Y-H:i:s",$_POST['fin']/1000).". Les paiements ont eu lieu dans cette période."),0,'', true);
								$pdf->SetFont('Times','',8);
								// Largeurs des colonnes
								$w = array(190/12, 190/12, 190/5, 190/15, 190/12, 190/15, 190/15, 190/15, 190/20, 190/20, 190/20, 190/20);
								$header = array(utf8_decode('Date consult'), utf8_decode('Date paiement'), utf8_decode('Identité client'), utf8_decode('reglement'), utf8_decode("mode"), utf8_decode("num cheq"), utf8_decode('tot à régler'), utf8_decode("tot reglé"), utf8_decode("total A"), utf8_decode("reg A"), utf8_decode("total M"), utf8_decode("reg M"));
								// En-tête
								for($i=0;$i<count($header);$i++)
									$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
									$pdf->Ln();
								// Données
								foreach($recup_consult5 as $row)
								{
								$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['date_consult']),'LR',0,'C');
								$pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row['date_paiement']),'LR',0,'C');
								$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['nom_p']." ".$row['prenom_p']." ".$row['ville_p']),'LR',0,'c');
								$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['montant']),'LR',0,'R');
								$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['mode']),'LR',0,'R');
								$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['numero_cheque']),'LR',0,'R');
								$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($row['totalttc']),'LR',0,'R');
								$pdf->Cell($w[7],6,requetemysql::gestion_string_norm($row['reglementttc']),'LR',0,'R');
								$pdf->Cell($w[8],6,requetemysql::gestion_string_norm($row['total_acte']),'LR',0,'R');
								$pdf->Cell($w[9],6,requetemysql::gestion_string_norm($row['reglement_acte']),'LR',0,'R');
								$pdf->Cell($w[10],6,requetemysql::gestion_string_norm($row['total_medic']),'LR',0,'R');
								$pdf->Cell($w[11],6,requetemysql::gestion_string_norm($row['reglement_medic']),'LR',0,'R');
								$pdf->Ln();
								}
								// Trait de terminaison
								$pdf->Cell(array_sum($w),0,'','T');
								$pdf->Ln(8);
								$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
								$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),'TRB','L', false);
								$pdf->Ln(8);
								$pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
								$pdf->MultiCell(0,20,"",'TRB','L', false);	
		
		
						$mon_url = '../sauvegarde/clinique/releve_honoraire/'.$_SESSION['login'].'/honoraire_'.htmlentities($_POST['veto']).date("d_m_y").uniqid().'.pdf';
		//$pdf->Output($mon_url, F);
		$pdf->Output($mon_url, F);
		echo json_encode($mon_url);
		
			
	
	
	
}elseif($data3=="duclient"){
	$recup_duclient = requetemysql::duclient(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000)));
	echo $recup_duclient;
}elseif($data3=="recherche_consult"){
	$recup_consult = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto', 'veto'=>$_POST['veto']));
	echo $recup_consult;
}elseif($data3=="recherche_consult2"){
	$recup_consult = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto2', 'veto'=>$_POST['veto']));
	echo $recup_consult;
}elseif($data3=="recherche_consult3"){
	$recup_consult = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto3', 'veto'=>$_POST['veto']));
	echo $recup_consult;
}elseif($data3=="recherche_consult4"){
	$recup_consult = requetemysql::brouillard(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'choix'=>'veto4', 'veto'=>$_POST['veto']));
	echo $recup_consult;
}elseif($data3=="totaux"){
	$recup_totaux = requetemysql::totaux(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000), 'choix'=>'total' ));
	echo $recup_totaux;
}elseif($data3=="totaux2"){
	$recup_totaux = requetemysql::totaux2(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000), 'choix'=>'total', 'recherche' => array("espece", "cheque", "carte", "virement") ));
	echo $recup_totaux;
}elseif($data3=="remise"){
	$recup_totaux = requetemysql::brouillard(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000), 'choix'=>'cheque' ));
	echo $recup_totaux;
}elseif($data3=="save_remise"){
	$date_actu = date("d/m/Y");
	$sql = "INSERT INTO `remise`(`id`, `numero_remise`, `date`, `remise`, `veto`) VALUES ('', :remise_num, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_actu,'%d/%m/%Y')) *1000 ), :liste_remise, :permission)";
	$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':remise_num' => $_POST['remise_num'], ':date_actu' => $date_actu, ':liste_remise' => $_POST['liste_remise'], ':permission' => $_SESSION['login']));
	$remise_id = $db->lastInsertId();
	echo $remise_id;
}elseif($data3=="search_remise"){
	$recup_totaux = requetemysql::search_remise(array('numero_remise'=>$_POST['remise_num']));
	echo $recup_totaux;
}elseif($data3=="resave_remise"){
	$sql="UPDATE `aerogard2`.`remise` SET remise = :liste_remise where id= :remise_num and veto=:permission limit 1;";
 	$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':liste_remise' => $_POST['liste_remise'], ':permission' => $_SESSION['login'], ':remise_num' => $_POST['remise_num']));
	echo $_POST['remise_num'];
}elseif($data3=="rappel"){
	$recup_rappel = requetemysql::rappel(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000)));
	echo $recup_rappel;
}elseif($data3=="rappel2"){
		$liste_rappel=$_POST['liste_rappel'];
		$texte_rappel=$_POST['texte_rappel'];
		$info_veto=$_POST['info_veto'];
		$debut=$_POST['debut'];
		$fin=$_POST['fin'];
		$data_mot=$_POST['data_mot'];
		$choix=$_POST['choix'];
		$filename = '../sauvegarde/clinique/rappel_vaccin/'.$_SESSION['login'];
		echo json_encode(creation_rappels($liste_rappel, $texte_rappel, $filename,$info_veto, $debut, $fin, $data_mot, $choix));
}elseif($data3=="radio"){
	$recup_rappel = requetemysql::radio(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin']));
	echo $recup_rappel;
}elseif($data3=="stat"){
	$donnee_an = array();
		foreach ($_POST['mes_dates'] as $key => $value) {
			$donne_mois = requetemysql::stat(array('debut'=>$value[0], 'fin'=>$value[1]));
			$donne_mois2 = requetemysql::stat(array('debut'=>$value[2], 'fin'=>$value[3]));	
			$array_tempo = array('mois'=>date("Y-m",$value[0]/1000), 'ca_a'=>($donne_mois[0]['total'] ?  $donne_mois[0]['total'] : "0") , 'ca_b'=>($donne_mois2[0]['total'] ? $donne_mois2[0]['total'] : "0"));
			
			array_push($donnee_an, $array_tempo);	    
		}
		echo json_encode($donnee_an);
		
}elseif($data3=="print_reglement_veto"){	
	
	$print_reglement_veto = json_decode($_POST['liste_reglement_honoraires'],true);
	
	$filename = '../sauvegarde/clinique/facture_veto/'.$print_reglement_veto['nom'];
	$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($print_reglement_veto['nom'])));
	if(empty($info_veto)){
		throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
	}else{
		$info_veto = json_decode($info_veto, true);
	}
	$info_client= requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
	if(empty($info_client)){
		throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
	}else{
		$info_client = json_decode($info_client, true);
	}
	if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
			die('Echec lors de la création des répertoires...');
		}
	}
		
		
	$pdf = new FPDF();
	$pdf->AliasNbPages();
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
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['siret']." ".$info_veto[0]['num_tva'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['siret']." ".$info_veto[0]['num_tva'])),0,'C');
	$pdf->Ln(35);				
	$pdf->Cell(90);
	$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
	$pdf->Ln(15);	
	$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
	$pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Document édité par ".$_SESSION['login2']." et validé par ".$print_reglement_veto['nom']),0,'L');
	$pdf->SetFont('Times','',18);
	$pdf->SetFillColor(153,153,153);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetDrawColor(153,153,153);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	$pdf->MultiCell(0,12,utf8_decode("Facture N°".date("d_m_y").uniqid()." pour la période du :".$_POST['debut2']." au ".$_POST['fin2']),0,'', true);
	$pdf->SetFont('Times','',12);
	$pdf->MultiCell(0,7,requetemysql::gestion_string_maj("Facture correspondant aux actes effectués pour le compte de :".$info_client[0]['nom']." par le Docteur ".utf8_decode(stripslashes($info_veto[0]['nom']))." conformément aux accords sur les taux de rétrocession contractuels : "),0,'L');
	$pdf->Ln();
	$pdf->MultiCell(0,12,utf8_decode("% : pourcentage de rétrocession (en pour-cent)"),0,'L');
	
	// Largeurs des colonnes
	$w = array(190/8, 190/10, 190/8, 190/10, 190/8, 190/10, 190/5);
	
		$header = array(utf8_decode('acte ht'), utf8_decode('% acte'), utf8_decode('medic ht'), utf8_decode('% medic'), utf8_decode("repartition ht"), utf8_decode("% repar"), utf8_decode("TOTAL"));
		
		// En-tête
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			$pdf->Ln();
		// Données
		$pdf->SetFont('Times','',8);
		
			if($print_reglement_veto['retribution_acte2']==''){
				$print_reglement_veto['retribution_acte2']=0;
			}
			if($print_reglement_veto['retribution_medic2']==''){
				$print_reglement_veto['retribution_medic2']=0;
			}
			if($print_reglement_veto['retribution_repartition2']==''){
				$print_reglement_veto['retribution_repartition2']=0;
			}
				$total = ($print_reglement_veto['base_ht']*$print_reglement_veto['retribution_acte2']/100) + ($print_reglement_veto['medic_ht']*$print_reglement_veto['retribution_medic2']/100) + ($print_reglement_veto['repartition_ht']*$print_reglement_veto['retribution_repartition2']/100);
				$total = arrondi($total);
				$pdf->Cell($w[0],6,requetemysql::gestion_string_maj($print_reglement_veto['base_ht']),'LR',0,'C');
		$pdf->Cell($w[1],6,requetemysql::gestion_string_norm($print_reglement_veto['retribution_acte2']),'LR',0,'C');
		$pdf->Cell($w[2],6,requetemysql::gestion_string_norm($print_reglement_veto['medic_ht']),'LR',0,'C');
		$pdf->Cell($w[3],6,requetemysql::gestion_string_norm($print_reglement_veto['retribution_medic2']),'LR',0,'R');
		$pdf->Cell($w[4],6,requetemysql::gestion_string_norm($print_reglement_veto['repartition_ht']),'LR',0,'R');
		$pdf->Cell($w[5],6,requetemysql::gestion_string_norm($print_reglement_veto['retribution_repartition2']),'LR',0,'R');
		$pdf->Cell($w[6],6,requetemysql::gestion_string_norm($total),'LR',0,'R');
		$pdf->Ln();
		
		
		// Trait de terminaison
		$pdf->Cell(array_sum($w),0,'','T');
		$pdf->Ln();
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(0,7,requetemysql::gestion_string_maj("A cette somme doit être ajoutés les paiements pour les consultations antérieures à cette période et non rémunérées pour le moment. Ces paiements sont détaillés en annexe."),0,'L');
		$pdf->Ln(20);
		$pdf->Cell(50,20,utf8_decode("Signature :"),'',0, false);
		$mon_url = '../sauvegarde/clinique/facture_veto/'.$print_reglement_veto['nom'].'/facture_'.date("d_m_y").uniqid().'.pdf';
		//$pdf->Output($mon_url, F);
		$pdf->Output($mon_url, F);
		echo json_encode($mon_url);
				
				
				
		
}elseif($data3=="paiement_veto"){
	$donnee_an = array();
	try {
		foreach ($_POST['liste_vetos'] as $key => $value) {
			$donne_init = requetemysql::sous_tot(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000), 'permission'=> $value['login'], 'recherche'=> 'total'));
			$donne_repartition = requetemysql::sous_tot(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000), 'permission'=> $value['login'], 'recherche'=> 'repartition'));
		
			$array_tempo = array('nom' => $value['login'],
					'date_debut' => $_POST['debut2'],
					'date_fin' => $_POST['fin2'],
					'base_ttc' => arrondi($donne_init[0]['reglement_acte']),
					'base_ht' => arrondi($donne_init[0]['reglement_acte']/(1+$_POST['tva'])),
					'medic_ttc' => arrondi($donne_init[0]['reglement_medic']),
					'medic_ht' => arrondi($donne_init[0]['reglement_medic']/(1+$_POST['tva'])), 
					'repartition_ttc' => arrondi($donne_repartition[0]['total']),
					'repartition_ht' => arrondi($donne_repartition[0]['total']/(1+$_POST['tva']))
					);
			array_push($donnee_an, $array_tempo);
		}
		echo json_encode($donnee_an);
		}catch (PDOException $e) {
			echo ($e->getMessage());
		}
	
}elseif($data3=="sous_tot"){
		$donnee_an = array();
		try {
		foreach ($_POST['liste_vetos'] as $key => $value) {
				$donne_init2 = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'totale'));
				$donne_init = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'total'));
				$donne_esp = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'espece'));
				$donne_cheq = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'cheque'));
				$donne_carte = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'carte'));
				$donne_vir = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'virement'));
				$donne_repartition = requetemysql::sous_tot(array('debut'=>$_POST['debut'], 'fin'=>$_POST['fin'], 'permission'=> $value['login'], 'recherche'=> 'repartition'));
				
				$array_tempo = array('sous_tot_nom' => $value['login'], 'sous_tot_ttc' => arrondi($donne_init2[0]['total']), 'sous_tot_ht' => arrondi($donne_init[0]['total']/(1+$_POST['tva']))
				, 'sous_tot_tva' => arrondi($donne_init[0]['total']-($donne_init[0]['total']/(1+$_POST['tva']))), 'sous_tot_ttc2' => arrondi($donne_init[0]['totalttc'])
				, 'sous_tot_ttc3' => arrondi($donne_init[0]['reglementttc']), 'sous_tot_acte' => arrondi($donne_init[0]['reglement_acte'])
				, 'sous_tot_medic' => arrondi($donne_init[0]['reglement_medic']), 'sous_tot_tot_acte' => arrondi($donne_init[0]['total_acte'])
				, 'sous_tot_tot_medic' => arrondi($donne_init[0]['total_medic']), 'sous_tot_espece' => arrondi($donne_esp[0]['total'])
				, 'sous_tot_cheque' => arrondi($donne_cheq[0]['total']), 'sous_tot_carte' => arrondi($donne_carte[0]['total'])
				, 'sous_tot_virement' => arrondi($donne_vir[0]['total']), 'sous_tot_repartition' => arrondi($donne_repartition[0]['total']));
				
				array_push($donnee_an, $array_tempo);
		
		}
		echo json_encode($donnee_an);
		}catch (PDOException $e) {
			echo ($e->getMessage());
		}
}elseif($data3=="vente"){
			$donnee = array();
			$search_vente = requetemysql::vente(array('debut'=>($_POST['debut']*1000), 'fin'=>($_POST['fin']*1000)));
			foreach ($search_vente as $key => $value) {
					$value2 = json_decode($value['medic'], true);
						foreach ($value2 as $key_unit => $value_unit) {
							$array_tempo = array('date_vente' => $value['date_medoc'], 'nom' => $value_unit['nom'], 'nombre' => $value_unit['quantite']);
							array_push($donnee, $array_tempo);						
						}				
			
			}
			echo json_encode($donnee);
}elseif($data3=="pharmaco"){
	$recup_pharmaco = requetemysql::pharmaco(array('lot'=>$_POST['lot']));
	echo $recup_pharmaco;
}
}
function arrondi($ma_valeur){

return (round($ma_valeur*100))/100 ;

}

function creation_rappels($liste_rappel2, $texte_rappel, $filename, $info_veto, $debut, $fin, $data_mot, $choix){

$liste_rappel = json_decode($liste_rappel2, true);


//$info_client = json_decode($info_client, true);
if (!file_exists($filename)) {
		if (!mkdir($filename, 0, true)) {
	  	  die('Echec lors de la création des répertoires...');
		}
	}
				if($choix=="A4"){
				$pdf=new FPDF('P','mm','A4');
				}elseif($choix=="lettre"){
				$pdf=new FPDF('L','mm',array(200,90));				
				}		
				
				while (list($key_rappel, $value_rappel) = each($liste_rappel)) 
			{
				
						
						if($choix=="lettre"){
										$pdf->AddPage();
										$pdf->SetFont('Times','',12);
										$pdf->SetXY(15,20);
										$pdf->Cell(70,0,requetemysql::gestion_string_maj($value_rappel['nom_a']),0,0,'L');
										$pdf->SetXY(120,40);
										$pdf->MultiCell(70,7,requetemysql::gestion_string_maj($value_rappel['nom_p']).' '.requetemysql::gestion_string_norm($value_rappel['prenom_p'])."\n".requetemysql::gestion_string_norm($value_rappel['adresse_p'])."\n".requetemysql::gestion_string_norm($value_rappel['code_p']).' '.requetemysql::gestion_string_norm($value_rappel['ville_p']),0,'L');
										$pdf->SetXY(25,50);
										$pdf->Cell(70,0,requetemysql::gestion_string_maj($value_rappel['date_rappel']),0,0,'L');
										$pdf->SetXY(20,68);
										$pdf->Cell(75,0,requetemysql::gestion_string_maj($info_veto[0]['nom']).' '.requetemysql::gestion_string_norm($info_veto[0]['tel']),0,0,'L');
																										
								
						}elseif($choix=="A4"){
									foreach ($texte_rappel as $key_texte => $value_texte)   
										{ 
											
													if($value_texte['nom']==$value_rappel['type']){			
										
													$pdf->AddPage();
													$pdf->SetFont('Times','',12);	
													$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_veto[0]['nom'])."\n".requetemysql::gestion_string_norm($info_veto[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_veto[0]['code']).' '.requetemysql::gestion_string_norm($info_veto[0]['commune'])."\n".requetemysql::gestion_string_norm($info_veto[0]['tel']),0,'L');
													$pdf->Ln(15);
													$pdf->Cell(90);
													$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($value_rappel['nom_p']).' '.requetemysql::gestion_string_norm($value_rappel['prenom_p'])."\n".requetemysql::gestion_string_norm($value_rappel['adresse_p'])."\n".requetemysql::gestion_string_norm($value_rappel['code_p']).' '.requetemysql::gestion_string_norm($value_rappel['ville_p']),0,'C');
													$pdf->Ln(25);
													$date2=$ligne['date']/1000;
													$mon_texte = $value_texte['texte'];
													$donnee_mot = array(requetemysql::gestion_string_norm($value_rappel['espece']), requetemysql::gestion_string_maj($value_rappel['nom_a']), requetemysql::gestion_string_maj($value_rappel['date_rappel']), requetemysql::gestion_string_maj($info_veto[0]['nom']));
													$str = str_replace($data_mot, $donnee_mot, $mon_texte);								
													$pdf->MultiCell(0, 5,utf8_decode($str),0,'J');				
													$pdf->Ln(25);
													if($value_rappel['commentaire']!=''){
													$pdf->Cell(50,20,utf8_decode("Commentaire :"),'LTB',0, false);
								 					$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($value_rappel['commentaire']),'TRB','L', false);	
								 					$pdf->Ln(10);
													}
													$pdf->Cell(90);
													$pdf->MultiCell(85,5,"Dr ".$_SESSION['login'],0,'C');
													$pdf->SetY(-40);
													$pdf->Cell(0,10,utf8_decode('Pensez à prendre rendez-vous au '.requetemysql::gestion_string_norm($info_veto[0]['tel']).'. N\'oubliez pas le livret de santé.'),0,0,'C');				
									
													}	// fermeture if $value_texte							
								
							}//fermeture seconde boucle
				
					}//fermeture if choix A4
				
}// fermeture premiere boucle
$variable = round(microtime(true));	 
$pdf->Output($filename.'/relance__'.$debut.'__'.$fin.'_'.$variable.'.pdf', F);
$variable_lien = $filename.'/relance__'.$debut.'__'.$fin.'_'.$variable.'.pdf';
return $variable_lien;

}


?>