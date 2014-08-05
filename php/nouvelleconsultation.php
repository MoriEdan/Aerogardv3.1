<?php
/* il faut demarrer la session*/
session_start();
if (empty($_SESSION['id'])){
	
	header('HTTP/1.1 400 Bad Request');
	exit("votre session a expiré. Reconnectez-vous !!");	
	
}elseif(!empty($_SESSION['id'])){
	

require_once "config.php";
require_once "connexionmysql.php";
if($_GET['action']=='autre_certif'){
require('html_pdf/html2pdf.php');
}else{
require('fpdf/fpdf.php');
require('fpdi/fpdi.php');
}
require_once "requetemysql.php";

if($_GET['action']=='radio'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
if(empty($info_veto)){
throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
}
$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$commentaire=$_POST['commentaire2'];
$variable = round(microtime(true));
$info_radio2 = json_decode($_POST['radio'], TRUE);
$lien_radio = creation_resultat_radio($info_client, $info_animal, $info_radio2, $commentaire, $variable,$filename,$info_veto);
echo $lien_radio;
}else if($_GET['action']=='analyse'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
if(empty($info_veto)){
throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
}else{
$info_veto = json_decode($info_veto, true);
}
$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$info_analyse=$_POST['analyse'];
$commentaire=$_POST['commentaire'];
$variable = round(microtime(true));
//$info_client = json_decode($info_client, true);
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
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
				$pdf->Ln(15);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->MultiCell(85,5,"Examen effectué par ".$_SESSION['login2'],0,'L');
				$pdf->SetFont('Times','',18);	
				$pdf->SetFillColor(153,153,153);
   			    $pdf->SetTextColor(0,0,0);
 			    $pdf->SetDrawColor(153,153,153);
 			    $pdf->SetLineWidth(.3);
   			    $pdf->SetFont('','B');
				$pdf->MultiCell(60,12,utf8_decode("Résultat d'analyse"),0,'L', true);
				$pdf->Cell(50,12,utf8_decode("Animal :"),'LTB',0, false);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,12,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
				 // Largeurs des colonnes
    			$w = array(190/4, 190/6, 190/6, 190/4, 190/6);
    			$header = array(utf8_decode('Référence'), utf8_decode('Résultat'), utf8_decode('Unité'), utf8_decode('Méthode'), utf8_decode("date d'analyse"));
    				// En-tête
   				 for($i=0;$i<count($header);$i++)
     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
   					  $pdf->Ln();
    			// Données
   				 foreach($info_analyse as $row)
  			  {
       			 $pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['nom']),'LR',0,'C');
        		 $pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['resultat']),'LR',0,'R');
       			 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['unite']),'LR',0,'C');
      			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['methode']),'LR',0,'C');
      			 $pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
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
			 
$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/analyse_'.$variable.'.pdf', F);


echo json_encode($variable);
}elseif ($_GET['action']=='certif_sante'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
	$info_client=$_POST['client'];
	$info_animal=$_POST['animal'];
	$variable = round(microtime(true));
	if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
	  	  die('Echec lors de la création des répertoires...');
		}
	}
	$array_chien = array(
	array('valeur' => 'veto', 'posx' => 40, 'posy' => 40.8),
	array('base' => 'veto', 'valeur' => 'ordre', 'posx' => 59.5, 'posy' => 48),
	array('base' => 'veto', 'valeur' => 'adresse', 'posx' => 16.8, 'posy' => 57.8),
	array('base' => 'veto', 'valeur' => 'code', 'posx' => 22.4, 'posy' => 64.4),
	array('base' => 'veto', 'valeur' => 'commune', 'posx' => 49.7, 'posy' => 64.4),
	array('base' => 'client', 'valeur' => 'nom', 'posx' => 110.5, 'posy' => 46.6),
	array('base' => 'client', 'valeur' => 'adresse', 'posx' => 110.1, 'posy' => 60.5),
	array('base' => 'client', 'valeur' => 'code', 'posx' => 116.1, 'posy' => 66.4),
	array('base' => 'client', 'valeur' => 'ville', 'posx' => 143.4, 'posy' => 66.4),
	array('base' => 'animal', 'valeur' => 'nom_a', 'posx' => 24.3, 'posy' => 82.8),
	array('base' => 'animal', 'valeur' => 'num_p', 'posx' => 50.2, 'posy' => 88),
	array('base' => 'animal', 'valeur' => 'num_t', 'posx' => 113.6, 'posy' => 88),
	array('base' => 'animal', 'valeur' => 'num_pa', 'posx' => 47.4, 'posy' => 97.8)	,
	array('valeur' => 'date', 'posx' => 90.8, 'posy' => 166.9),	
	array('valeur' => 'date2', 'posx' => 169.2, 'posy' => 262.8)	
	);
	$array_chat = array(
	array('valeur' => 'veto', 'posx' => 44, 'posy' => 46),
	array('base' => 'veto', 'valeur' => 'ordre', 'posx' => 65, 'posy' => 52),
	array('base' => 'veto', 'valeur' => 'adresse', 'posx' => 22, 'posy' => 63),
	array('base' => 'veto', 'valeur' => 'code', 'posx' => 27, 'posy' => 69),
	array('base' => 'veto', 'valeur' => 'commune', 'posx' => 55, 'posy' => 69),
	array('base' => 'client', 'valeur' => 'nom', 'posx' => 117, 'posy' => 52),
	array('base' => 'client', 'valeur' => 'adresse', 'posx' => 116, 'posy' => 66),
	array('base' => 'client', 'valeur' => 'code', 'posx' => 121, 'posy' => 70),
	array('base' => 'client', 'valeur' => 'ville', 'posx' => 150, 'posy' => 70),
	array('base' => 'animal', 'valeur' => 'nom_a', 'posx' => 42, 'posy' => 84),
	array('base' => 'animal', 'valeur' => 'num_p', 'posx' => 56, 'posy' => 91),
	array('base' => 'animal', 'valeur' => 'num_t', 'posx' => 119, 'posy' => 91),
	array('base' => 'animal', 'valeur' => 'num_pa', 'posx' => 53, 'posy' => 104),
	array('valeur' => 'date', 'posx' => 96, 'posy' => 158),	
	array('valeur' => 'date2', 'posx' => 174, 'posy' => 261)	
	);
		$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login2'])));
		if(empty($info_veto)){
		throw new Exception("Erreur dans la recherche des antécédents de l animal");
		}else{
		$info_veto = json_decode($info_veto, true);
		}
		
				// initiate FPDI
				$pdf = new FPDI();
				// add a page
				$pdf->AddPage();
				// set the source file
				$pdf->setSourceFile( $info_animal[0]['espece']== 'chien' ? "../image/patron_image/certif_sante_chien.pdf" : "../image/patron_image/certif_sante_chat.pdf");
				// import page 1
				$tplIdx = $pdf->importPage(1);
				// use the imported page and place it at point 10,10 with a width of 100 mm
				$pdf->useTemplate($tplIdx, 0, 0, 0);
				
				// now write some text above the imported page
				$pdf->SetFont('Helvetica');
				$pdf->SetFontSize(12);
				$pdf->SetTextColor(0, 0, 0);
				
				 foreach(($info_animal[0]['espece']== 'chien' ? $array_chien : $array_chat) as $row)
  			  {
  			  		$pdf->SetXY($row['posx'], $row['posy']);
  			  		$pdf->Write(0, requetemysql::gestion_string_maj(remplissage_formulaire($row, $info_veto, $info_client, $info_animal)));
  			  }
				
				
				$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/certif_sante_'.$variable.'.pdf', F);
	
	echo json_encode($variable);
}




elseif ($_GET['action']=='certif_sani'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
	$info_client=$_POST['client'];
	$info_animal=$_POST['animal'];
	$variable = round(microtime(true));
	if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
	  	  die('Echec lors de la création des répertoires...');
		}
	}
	$array_sani = array(
	array('valeur' => 'veto', 'posx' => 75, 'posy' => 73),
	array('base' => 'veto', 'valeur' => 'ordre', 'posx' => 140, 'posy' => 73),
	array('base' => 'veto', 'valeur' => 'adresse', 'posx' => 35, 'posy' => 82),
	array('base' => 'veto', 'valeur' => 'code', 'posx' => 140, 'posy' => 82),
	array('base' => 'veto', 'valeur' => 'commune', 'posx' => 160, 'posy' => 82),
	array('base' => 'client', 'valeur' => 'nom', 'posx' => 50, 'posy' => 165),
	array('base' => 'client', 'valeur' => 'adresse', 'posx' => 45, 'posy' => 172),
	array('base' => 'client', 'valeur' => 'code', 'posx' => 45, 'posy' => 179),
	array('base' => 'client', 'valeur' => 'ville', 'posx' => 70, 'posy' => 179),
	array('base' => 'animal', 'valeur' => 'nom_a', 'posx' => 30, 'posy' => 119),
	array('base' => 'animal', 'valeur' => 'num_p', 'posx' => 110, 'posy' => 135),
	array('base' => 'animal', 'valeur' => 'num_t', 'posx' => 140, 'posy' => 135),
	array('base' => 'animal', 'valeur' => 'espece', 'posx' => 50, 'posy' => 110),
	array('base' => 'animal', 'valeur' => 'sexe', 'posx' => 35, 'posy' => 128),
	array('base' => 'animal', 'valeur' => 'race', 'posx' => 35, 'posy' => 145),
	array('valeur' => 'date', 'posx' => 95, 'posy' => 240),	
	array('valeur' => 'veto', 'posx' => 30, 'posy' => 240)
	);
	
		$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
		if(empty($info_veto)){
		throw new Exception("Erreur dans la recherche des antécédents de l animal");
		}else{
		$info_veto = json_decode($info_veto, true);
		}
		
				// initiate FPDI
				$pdf = new FPDI();
				// add a page
				$pdf->AddPage();
				// set the source file
				$pdf->setSourceFile( "../image/patron_image/certificat_sanitaire.pdf");
				// import page 1
				$tplIdx = $pdf->importPage(1);
				// use the imported page and place it at point 10,10 with a width of 100 mm
				$pdf->useTemplate($tplIdx, 0, 0, 0);
				
				// now write some text above the imported page
				$pdf->SetFont('Helvetica');
				$pdf->SetFontSize(12);
				$pdf->SetTextColor(0, 0, 0);
				
				 foreach($array_sani as $row)
  			  {
  			  		$pdf->SetXY($row['posx'], $row['posy']);
  			  		$pdf->Write(0, requetemysql::gestion_string_maj(remplissage_formulaire($row, $info_veto, $info_client, $info_animal)));
  			  }
				
				
				$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/certif_sanitaire_'.$variable.'.pdf', F);
	
	echo json_encode($variable);
}elseif ($_GET['action']=='modif_lot'){
	
		$sql="UPDATE medicament set lot=:lot WHERE nom=:nom and permission=:permission LIMIT 1";
		$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$st2->execute(array(':lot' => $_POST['lot'],
				':nom' => $_POST['nom'],
				':permission' => $_SESSION['login']));
		$st2->closeCursor();
		echo json_encode("ok");	
}elseif ($_GET['action']=='medic'){

	$medicament = requetemysql::recup_medic(array('nom'=>$_GET['recherche']));
			if(empty($medicament)){
				//throw new Exception("Aucun medicament dans la base de donnee !");
			}
	echo $medicament;	
}elseif ($_GET['action']=='acte'){

	$medicament = requetemysql::recup_acte(array('nom'=>$_GET['recherche']));
			if(empty($medicament)){
				//throw new Exception("Aucun medicament dans la base de donnee !");
			}
	echo $medicament;	
}elseif ($_GET['action']=='dem_devis'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
if(empty($info_veto)){
throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
}else{
$info_veto = json_decode($info_veto, true);
}
$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$info_resume=$_POST['resume'];
$info_clinique=$_POST['clinique'];
$info_acte=json_decode($_POST['acte'], true);
$info_medic=json_decode($_POST['medic'], true);
$tva = $info_veto[0]['tva'];
$variable = round(microtime(true));
//$info_client = json_decode($info_client, true);
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
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
				$pdf->Ln(15);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->MultiCell(85,5,"Devis effectué par ".$_SESSION['login2'],0,'L');
				$pdf->SetFont('Times','',18);	
				$pdf->SetFillColor(153,153,153);
   			    $pdf->SetTextColor(0,0,0);
 			    $pdf->SetDrawColor(153,153,153);
 			    $pdf->SetLineWidth(.3);
   			    $pdf->SetFont('','B');
				$pdf->MultiCell(60,12,utf8_decode("Devis N°").$variable,0,'L', true);
				$pdf->Cell(50,12,utf8_decode("Animal :"),'LTB',0, false);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,12,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
				 $pdf->Cell(50,20,utf8_decode("Ce devis concerne :"),0,0, false);
				 $pdf->MultiCell(0,20,requetemysql::gestion_string_maj($info_resume),0,'L', false);	
				 $prix_ht = 0;
				  $prix_tva = 0;
				   $prix_ttc = 0;
				$pdf->SetFont('Times','',10);
				// Largeurs des colonnes
    			$w = array(20, 75, 25, 15, 15, 25, 15);
    			$header = array(utf8_decode('Date'), utf8_decode('Acte'), utf8_decode('Prix unit TTC'), utf8_decode('Qte'), utf8_decode('Rem'), utf8_decode("Prix total TTC"), utf8_decode("TVA"));
    				// En-tête
   				 for($i=0;$i<count($header);$i++)
     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
   					  $pdf->Ln();
    			// Données
   				 foreach($info_acte as $row)
  			  {
  			  	 $pdf->Cell($w[0],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
       			 $pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row['nom']),'LR',0,'C');
        		 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['prix_unitaire']),'LR',0,'R');
       			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['quantite']),'LR',0,'C');
      			 $pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['remise']),'LR',0,'C');
      			 $pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['prix_total']),'LR',0,'C');
      			 $ma_tva=number_format($row['prix_total']-($row['prix_total']/(1+($tva/100))),2);
      			 $prix_ht+=number_format($row['prix_total']/(1+($tva/100)),2);
      			 $prix_tva+= $ma_tva;
      			 $prix_ttc+=$row['prix_total'];
      			 $pdf->Cell($w[6],6,requetemysql::gestion_string_norm($ma_tva),'LR',0,'C');
      			 $pdf->Ln();
   			 }
    		// Trait de terminaison
   			 $pdf->Cell(array_sum($w),0,'','T');			
			 $pdf->Ln(8);
			 $header = array(utf8_decode('Date'), utf8_decode('Médicament et lot'), utf8_decode('Prix unitaire TTC'), utf8_decode('Quantite'), utf8_decode('Remise'), utf8_decode("Prix total TTC"), utf8_decode("TVA"));
    				// En-tête
   				 for($i=0;$i<count($header);$i++)
     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
   					  $pdf->Ln();
    			// Données
   				 foreach($info_medic as $row)
  			  {
  			  	 $pdf->Cell($w[0],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
       			 $pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row['nom']).' '.requetemysql::gestion_string_norm($row['lot']),'LR',0,'C');
        		 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['prix_unitaire']),'LR',0,'R');
       			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['quantite']),'LR',0,'C');
      			 $pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['remise']),'LR',0,'C');
      			 $pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['prix_total']),'LR',0,'C');
      			 $ma_tva=number_format($row['prix_total']-($row['prix_total']/(1+($tva/100))),2);
      			 $prix_ht+=number_format($row['prix_total']/(1+($tva/100)),2);
      			 $prix_tva+= $ma_tva;
      			 $prix_ttc+=$row['prix_total'];
      			 $pdf->Cell($w[6],6,requetemysql::gestion_string_norm($ma_tva),'LR',0,'C');
      			 $pdf->Ln();
   			 }
    		// Trait de terminaison
   			 $pdf->Cell(array_sum($w),0,'','T');			
			 $pdf->Ln(8);
			  $pdf->Cell(35,7,'dont','LTR',0,'C');
			 $pdf->Cell(35,7,'HT',1,0,'C');
			 $pdf->Cell(35,7,'TVA',1,0,'C');
	 $pdf->Ln();
	 $tva2=$tva;
	 $pdf->Cell(35,7,'TVA '.$tva2.'%','LRB',0,'C');
	 $pdf->Cell(35,7,$prix_ht,'LRB',0,'C');
	  $pdf->Cell(35,7,$prix_tva,'LRB',0,'C');
	   $pdf->Cell(15,7,'',0,0,'C');
	   $pdf->Cell(65,7,'Total TTC : '.$prix_ttc.' euros',1,0,'C', true);	  
	 
	 $pdf->Ln(8);
			 $pdf->Cell(50,20,utf8_decode("Commentaire :"),0,0, false);
			 $pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),0,'L', false);	
			 $pdf->Ln(8);
			 $pdf->Cell(50,20,utf8_decode("Signature :"),0,0, false);
			 $pdf->MultiCell(0,20,"",0,'L', false);	
			  $pdf->Ln();
	//    $titre=requetemysql::gestion_string_maj("Membre d'une association de gestion agréée. Le règlement des honoraires par chèque est accepté.");
	   	  $titre=requetemysql::gestion_string_maj("");
     
	    $w=$pdf->GetStringWidth(stripslashes($titre))+6;
      $pdf->SetX((210-$w)/2);
	  $pdf->Cell($w,2,$titre,0,1,'C',false);
	   $pdf->Ln();
	   $titre2=requetemysql::gestion_string_norm("Siret: ".requetemysql::gestion_string_norm($info_veto[0]['siret'])." N° TVA :".requetemysql::gestion_string_norm($info_veto[0]['num_tva']));
	   $w=$pdf->GetStringWidth(stripslashes($titre2))+6;
      $pdf->SetX((210-$w)/2);
	  $pdf->Cell($w,2,$titre2,0,1,'C',false);
$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/devis_'.$variable.'.pdf', F);


echo json_encode($variable);
}elseif ($_GET['action']=='resume'){
	$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
	$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
	if(empty($info_veto)){
		throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
	}else{
		$info_veto = json_decode($info_veto, true);
	}
	$pdf = new FPDF();
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
	$pdf->Ln(20);
	$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
	$pdf->SetFont('Times','',18);
	$pdf->MultiCell(190,10,requetemysql::gestion_string_maj($_POST['texte']),'0','L');
	$pdf->Ln();
	$pdf->SetFont('Times','',12);
	$pdf->MultiCell(190,5,requetemysql::gestion_string_maj($_POST['detail_consultation']),0,'L');
	$pdf->Ln(15);
	$pdf->Cell(50,20,utf8_decode("signature du vétérinaire :"),0,0, false);
	$mon_url = '../sauvegarde/animaux/'.$_POST['animal_id'].'/resume_'.date("d_m_y").uniqid().'.pdf';
	$pdf->Output($mon_url, F);
	echo json_encode($mon_url);
}elseif ($_GET['action']=='incineration'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
if(empty($info_veto)){
throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
}else{
$info_veto = json_decode($info_veto, true);
}
$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$poids=$_POST['poids'];
$variable = round(microtime(true));
//$info_client = json_decode($info_client, true);
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
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
				$pdf->Ln(15);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->SetFont('Times','',24);
				$pdf->MultiCell(120,25,requetemysql::gestion_string_maj("Demande d'incinération :"),0,'L');
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(120,5,requetemysql::gestion_string_maj("Opération enregistrée par ".$_SESSION['login2']),0,'L');
				$pdf->Ln(8);
				$pdf->SetFont('Times','',18);	
				$pdf->SetFillColor(153,153,153);
   			    $pdf->SetTextColor(0,0,0);
 			    $pdf->SetDrawColor(153,153,153);
 			    $pdf->SetLineWidth(.3);
   			    $pdf->SetFont('','B');
				$pdf->MultiCell(150,12,utf8_decode("Incinération N°").$variable,0,'L', true);
				$pdf->Cell(50,30,utf8_decode("Animal :"),'LTB',0, false);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,30,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
				$pdf->MultiCell(0,10,requetemysql::gestion_string_maj("Poids de l'animal :".$poids.' kg'),'LTRB','L', false);	
				$pdf->Cell(50,20,utf8_decode("Mention particulière :"),0,0, false);
				$pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Mention 1 : Je soussigné ".$info_client[0]['nom']." ".$info_client[0]['prenom']." ou                                    représentant les propriétaires de cet animal, certifie que cet animal n'a ni mordu, ni griffé personne depuis 15 jours."),0,'L', false);	
					 
			 $pdf->Ln(8);
			 $pdf->Cell(50,20,utf8_decode("Commentaire :"),0,0, false);
			 if($_GET['inci']=='indi'){
			 $pdf->SetFont('Times','',14);
			 $pdf->MultiCell(0,5,requetemysql::gestion_string_maj("L'incinération sera individuelle. Une urne sera restituée aux propriétaires"),0,'L', false);	
			 $pdf->SetFont('Times','',12);
			 $pdf->Cell(50,20,utf8_decode("Date du retour de l'urne :"),0,0, false);
			 }elseif($_GET['inci']=='norm'){
			 
			 $pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Le mode d'incinération choisi ne conduit pas à la restitution d'une urne"),0,'L', false);	
			 
			 }
			 $pdf->Ln(8);
			 $pdf->Cell(100,20,utf8_decode("Nom, prénom, signature du propriétaire de l'animal :"),0,0, false);
			 $pdf->Cell(50,20,utf8_decode("signature du vétérinaire :"),0,0, false);
			 $pdf->MultiCell(0,20,"",0,'L', false);	
			
	//   $titre2=requetemysql::gestion_string_norm("Siret: ".requetemysql::gestion_string_norm($info_veto[0]['siret'])." N° TVA :".requetemysql::gestion_string_norm($info_veto[0]['num_tva']));
	//   $w=$pdf->GetStringWidth(stripslashes($titre2))+6;
    //  $pdf->SetX((210-$w)/2);
	//  $pdf->Cell($w,2,$titre2,0,1,'C',false);
			 if($_GET['inci']=='indi'){
$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/incineration_individuelle_'.$variable.'.pdf', F);
			}elseif($_GET['inci']=='norm'){
$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/incineration_normale_'.$variable.'.pdf', F);
			}

echo json_encode($variable);

}elseif ($_GET['action']=='eutha'){
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
if(empty($info_veto)){
throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
}else{
$info_veto = json_decode($info_veto, true);
}
$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$poids=$_POST['poids'];
$variable = round(microtime(true));
//$info_client = json_decode($info_client, true);
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
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
				$pdf->Ln(15);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->SetFont('Times','',24);
				$pdf->MultiCell(85,20,requetemysql::gestion_string_maj("Demande d'euthanasie :"),'0','L');
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Je soussigné ".$info_client[0]['nom']." ".$info_client[0]['prenom']." ou son représentant :                                       demande à la clinique ".$info_veto[0]['nom']." représentée par ".$_SESSION['login2']." de procéder à l'euthanasie de l'animal dont la description est précisée ci-après"),0,'L');
				$pdf->Ln(15);
				$pdf->SetFont('Times','',18);	
				$pdf->SetFillColor(153,153,153);
   			    $pdf->SetTextColor(0,0,0);
 			    $pdf->SetDrawColor(153,153,153);
 			    $pdf->SetLineWidth(.3);
   			    $pdf->SetFont('','B');
				$pdf->MultiCell(150,12,utf8_decode("Euthanasie N°").$variable,0,'L', true);
				$pdf->Cell(50,30,utf8_decode("Animal :"),'LTB',0, false);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,30,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
				$pdf->MultiCell(0,10,requetemysql::gestion_string_maj("Poids de l'animal :".$poids.' kg'),'LTRB','L', false);	
				$pdf->Cell(50,20,utf8_decode("Mention particulière :"),0,0, false);
				$pdf->MultiCell(0,5,requetemysql::gestion_string_maj("Mention 1 : Je soussigné ".$info_client[0]['nom']." ".$info_client[0]['prenom']." ou                                    représentant les propriétaires de cet animal, certifie que cet animal n'a ni mordu, ni griffé personne depuis 15 jours."),0,'L', false);	
			 
			 $pdf->Ln(8);
			 $pdf->Cell(100,20,utf8_decode("Nom, prénom, signature du propriétaire de l'animal :"),0,0, false);
			 $pdf->Cell(50,20,utf8_decode("signature du vétérinaire :"),0,0, false);
			 $pdf->MultiCell(0,20,"",0,'L', false);	
			
	//   $titre2=requetemysql::gestion_string_norm("Siret: ".requetemysql::gestion_string_norm($info_veto[0]['siret'])." N° TVA :".requetemysql::gestion_string_norm($info_veto[0]['num_tva']));
	//   $w=$pdf->GetStringWidth(stripslashes($titre2))+6;
    //  $pdf->SetX((210-$w)/2);
	//  $pdf->Cell($w,2,$titre2,0,1,'C',false);
			
		$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/euthanasie_'.$variable.'.pdf', F);
		echo json_encode($variable);
}elseif($_GET['action']=='autre_certif'){

$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$variable = round(microtime(true));
//$info_client = json_decode($info_client, true);
if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
	  	  die('Echec lors de la création des répertoires...');
		}
	}
$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetFont('Times','',12);	
$pdf->WriteHTML(utf8_decode(stripslashes($_POST['message'])));
$pdf->Output('../sauvegarde/animaux/'.$_POST['animal_id'].'/certificat_'.$variable.'.pdf', F);
echo json_encode($variable);
}elseif ($_GET['action']=='drop_client_animal'){
	$info_client=$_POST['client'];
	$sql=" CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM client WHERE id2 = :clien_id;
	UPDATE tmptable_1 SET id2 = NULL, permission2=:permission;	
	INSERT INTO client SELECT * FROM tmptable_1;
	DROP TEMPORARY TABLE IF EXISTS tmptable_1;";
	$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$st2->execute(array(':clien_id' => $info_client[0]['id2'], ':permission' => $_SESSION['login'] ));
	$st2->closeCursor();	
	
	
 	$sql=" CREATE TEMPORARY TABLE tmptable_2 SELECT * FROM animal WHERE id = :animal_id;
	UPDATE tmptable_2 SET id = NULL, permission=:permission ;	
	INSERT INTO animal SELECT * FROM tmptable_2;
	DROP TEMPORARY TABLE IF EXISTS tmptable_2;";
	$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$st2->execute(array(':animal_id' => $_POST['animal_id'], ':permission' => $_SESSION['login'] ));
	$st2->closeCursor();	

	$sql="SELECT * FROM client ORDER BY id2 DESC LIMIT 1";
	$st2 = $db->prepare($sql);
	$st2->execute();
	$client = $st2->fetchAll();
	$st2->closeCursor();
	
	$sql="UPDATE animal set id_p=:nouveauclient ORDER BY id DESC LIMIT 1";
	$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$st2->execute(array(':nouveauclient' => $client[0]['id2']));
	$st2->closeCursor();
	
	$sql="SELECT * FROM animal ORDER BY id DESC LIMIT 1";
	$st2 = $db->prepare($sql);
	$st2->execute();
	$animal = $st2->fetchAll();
	$st2->closeCursor();
	
	
 				 	
	$mon_array = array($client,$animal);

	echo json_encode($mon_array);

}elseif ($_GET['action']=='validation'){
	try {
$ma_valeur = $_GET['valeur'];
	if($ma_valeur == 2 && $_SESSION['login']==$_SESSION['login2'] && $_SESSION['login']==$_SESSION['tour']){		
		$_SESSION['login2']= addslashes($_POST['origine']);
		$illusion_compte = 1;
	}	
	if($ma_valeur == 2){
	$info_veto = requetemysql::suprimer_consultation(array('consult'=>$_GET['consult']));
	}
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$filename2 = '../archive/animaux/'.$_POST['animal_id'];
$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$info_acte=$_POST['acte'];
$info_medic=$_POST['medic'];
$repartition=json_decode($_POST['repartition'], TRUE);
$info_analyse=$_POST['analyse'];
$info_radio=json_decode($_POST['radio'], TRUE);
$info_paiement=json_decode($_POST['paiement'], TRUE);
$total_reglement = 0;
foreach($info_paiement as $key=>$val){  
 $total_reglement +=$val['montant']; 
 }
 $info_relance=json_decode($_POST['relance'], TRUE);
$info_formulaire=$_POST['formulaire'];

$clinique = $info_formulaire['clinique'];
 foreach(json_decode($_POST['analyse'], TRUE) as $key=>$val){  
 $clinique .="&#10;".$val['ma_date']." ".$val['nom']." : ".$val['resultat']." ".$val['unite']; 
 }
 if($info_formulaire['result_radio']!=''){
 $clinique .="&#10;".$info_formulaire['result_radio'];
 }

 if($ma_valeur != 3){
		if($info_formulaire['montant_paiement2']!=0){
		  $sql="UPDATE `aerogard2`.`client` SET variable = :mavariable where id2= :client_id limit 1;";
		  $st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		  $st2->execute(array(':mavariable' => '0',
		 				':client_id' => $info_client[0]['id2']
		 				 ));	
		  $st2->closeCursor();	 
		 }else{
		 $sql="UPDATE `aerogard2`.`client` SET variable = :mavariable where id2= :client_id limit 1;";
		  $st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		  $st2->execute(array(':mavariable' => '1',
		 				':client_id' => $info_client[0]['id2']
		 				 ));	
		  $st2->closeCursor();
		 
		 }
 }
 
$sql = "INSERT INTO  `aerogard2`.`consultation` (`id`, `date`, `id_c`, `motif`, `resume`, `permission`, `permission2`, `temperature`, `poids`, `freq_cardiaque` ) VALUES ('', ( UNIX_TIMESTAMP(STR_TO_DATE(:date_consult,'%d/%m/%Y %H:%i')) *1000 ), :id_chien, :resume, :clinique, :login, :login2, :temperature, :poids, :freq_cardiaque)";
$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':date_consult' => ($info_formulaire['date_consultation']==date("d/m/Y")) ? $info_formulaire['date_consultation']." ".date("H:i") : $info_formulaire['date_consultation']." 00:00", ':id_chien' => $_POST['animal_id'], ':resume' => $info_formulaire['barre_resume'], ':clinique' => $clinique, ':login' => $_SESSION['login'], ':login2' => $_SESSION['login2'], ':temperature' => $info_formulaire['temperature'], ':poids' => $info_formulaire['poids'], ':freq_cardiaque' => $info_formulaire['cardio']));
$consultation_id = $db->lastInsertId();

if($ma_valeur != 3){
$sql = "INSERT INTO `aerogard2`.`facturation` (`id`, `id_c`, `date`, `veto`, `veto2`, `detail`, `totalttc`,`reglementttc`, `total_acte`,`reglement_acte`, `total_medic`,`reglement_medic`, `acte`, `medic` ) VALUES('' , :consultation_id, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_consult,'%d/%m/%Y %H:%i')) *1000 ), '".$_SESSION['login']."', '".$_SESSION['login2']."', '', :totalttc, :reglementttc, :total_acte, :reglement_acte, :total_medic, :reglement_medic, :acte, :medic)";
$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
//$sth->execute(array(':consultation_id' => $consultation_id, ':date_consult' => $info_formulaire['date_consultation'], ':totalttc' => $info_formulaire['total_consult'], ':reglementttc' => $total_reglement, ':acte' => $info_acte, ':medic' => $info_medic));
$sth->execute(array(':consultation_id' => $consultation_id, ':date_consult' => ($info_formulaire['date_consultation']==date("d/m/Y")) ? $info_formulaire['date_consultation']." ".date("H:i") : $info_formulaire['date_consultation']." 00:00", ':totalttc' => $info_formulaire['total_consult'], ':reglementttc' => 0, ':total_acte' => $info_formulaire['montant_acte'], ':reglement_acte' => 0, ':total_medic' => $info_formulaire['montant_medic'], ':reglement_medic' => 0, ':acte' => $info_acte, ':medic' => $info_medic));
$facturation_id = $db->lastInsertId();
}elseif($ma_valeur == 3){
$sql = "INSERT INTO `aerogard2`.`facturation` (`id`, `id_c`, `date`, `veto`, `veto2`, `detail`, `totalttc`,`reglementttc`, `total_acte`,`reglement_acte`, `total_medic`,`reglement_medic`, `acte`, `medic` ) VALUES('' , :consultation_id, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_consult,'%d/%m/%Y %H:%i')) *1000 ), '".$_SESSION['login']."', '".$_SESSION['login2']."', '', :totalttc, :reglementttc, :total_acte, :reglement_acte, :total_medic, :reglement_medic, :acte, :medic)";
$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
//$sth->execute(array(':consultation_id' => $consultation_id, ':date_consult' => $info_formulaire['date_consultation'], ':totalttc' => $info_formulaire['total_consult'], ':reglementttc' => $total_reglement, ':acte' => $info_acte, ':medic' => $info_medic));
$sth->execute(array(':consultation_id' => $consultation_id, ':date_consult' => ($info_formulaire['date_consultation']==date("d/m/Y")) ? $info_formulaire['date_consultation']." ".date("H:i") : $info_formulaire['date_consultation']." 00:00", ':totalttc' => 0, ':reglementttc' => 0, ':total_acte' => 0, ':reglement_acte' => 0, ':total_medic' => 0, ':reglement_medic' => 0, ':acte' => $info_acte, ':medic' => $info_medic));
$facturation_id = $db->lastInsertId();
}


$mon_array = array();
	if($ma_valeur == 2){
	$reste_a_payer_json = requetemysql::reste_a_payer2(array('consult'=>$consultation_id));
	}else{
	$reste_a_payer_json = requetemysql::reste_a_payer(array('id_proprio'=>$info_client[0]['id2']));
	}
$reste_a_payer = json_decode($reste_a_payer_json, TRUE);
$reste_a_payer2=$reste_a_payer;
array_push(	$mon_array, $info_paiement);
//$mavariable = array();
$reste_a_payer_2=$reste_a_payer;
$info_paiement_2=$info_paiement;

	
	

if(count($info_paiement)>0 && $ma_valeur != 3){

while (list($key_paiement, $value_paiement) = each($info_paiement)) 
{   
	//$mavariable[$key_paiement]["cat"]=$value_paiement['montant'];
	//$mavariable[$key_paiement]["long"]=count($reste_a_payer);
	
		while (list($key_a_payer, $value_a_payer) = each($reste_a_payer)) 
		{ 	
			
			$reste_du = difference($value_a_payer['totalttc'],$value_a_payer['reglementttc']);
			
			if(difference($reste_du, $value_paiement['montant'])==0 && $value_paiement['montant']>0){
				$ma_var = ($value_a_payer['reglementttc']+$value_paiement['montant']);
				//$mavariable[$key_paiement][$key_a_payer]["choix"]="egalite";
				//$mavariable[$key_paiement][$key_a_payer]["reste_du"]=$reste_du;
				//$mavariable[$key_paiement][$key_a_payer]["value_paiement_montant"]=$value_paiement['montant'];
				//$mavariable[$key_paiement][$key_a_payer]["reglementttc"]=$value_a_payer['reglementttc'];
				//$mavariable[$key_paiement][$key_a_payer]["totalttc"]=$value_a_payer['totalttc'];
				//$mavariable[$key_paiement][$key_a_payer]["ma_var"]=$ma_var;
				
				 $sql="UPDATE `aerogard2`.`facturation` SET reglementttc = :reglementttc where id= :id_facturation limit 1;
				  INSERT INTO `aerogard2`.`paiement` (id, id_fac, date, montant, numero_cheque, permission, mode, permission2) VALUES ('', :id_facturation, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_paiement,'%d/%m/%Y %H:%i')) *1000 ), :montant_ttc, :numero_cheque, :permission, :mode, :permission2 )";
				 $st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
 				 $st2->execute(array(':reglementttc' => $ma_var,
 				 			 ':id_facturation' => $value_a_payer['id'],
							 ':date_paiement' => ($value_paiement['date']==date("d/m/Y")) ? $value_paiement['date']." ".date("H:i") : $value_paiement['date']." 00:00",
			 				 ':montant_ttc' => $value_paiement['montant'],
			 				 ':numero_cheque' => $value_paiement['num_cheque'],
			 				 ':permission' => $_SESSION['login'],
 							// ':mode' => $value_paiement['mode2'],
 						 	 ':mode' => $value_paiement['mode'],
 							 ':permission2' => $_SESSION['login2']
 				 	));	 
 				  $st2->closeCursor();
 				 	$reste_a_payer[$key_a_payer]['reglementttc'] = $reste_a_payer[$key_a_payer]['totalttc'];
 				 	$value_a_payer['reglementttc']=$reste_a_payer[$key_a_payer]['totalttc'];
 				 	$key_a_payer==0 ? $reste_a_payer2[$key_a_payer]['reglementttc'] = $reste_a_payer[$key_a_payer]['totalttc'] : '';
 				 	$value_paiement['montant'] = 0;
 				 	$info_paiement[$key_paiement]['montant'] = 0; 				 	
					//unset($info_paiement[$key_paiement]); 
					//unset($reste_a_payer[$key_a_payer]); 					
 				 	//break 1;			
				}elseif(difference($reste_du, $value_paiement['montant'])>0 && $value_paiement['montant']>0){
				$ma_var = ($value_a_payer['reglementttc']+$value_paiement['montant']);
				//$mavariable[$key_paiement][$key_a_payer]["choix"]="inferieur";
				//$mavariable[$key_paiement][$key_a_payer]["reste_du"]=$reste_du;
				//$mavariable[$key_paiement][$key_a_payer]["value_paiement_montant"]=$value_paiement['montant'];
				//$mavariable[$key_paiement][$key_a_payer]["reglementttc"]=$value_a_payer['reglementttc'];
				//$mavariable[$key_paiement][$key_a_payer]["totalttc"]=$value_a_payer['totalttc'];
				//$mavariable[$key_paiement][$key_a_payer]["ma_var"]=$ma_var;
					 $sql="UPDATE `aerogard2`.`facturation` SET reglementttc = :reglementttc where id= :id_facturation limit 1;
					 INSERT INTO `aerogard2`.`paiement` (id, id_fac, date, montant, numero_cheque, permission, mode, permission2) VALUES ('', :id_facturation, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_paiement,'%d/%m/%Y %H:%i')) *1000 ), :montant_ttc, :numero_cheque, :permission, :mode, :permission2)";
					 $st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	 				 $st2->execute(array(':reglementttc' => $ma_var,
	 				 			 ':id_facturation' => $value_a_payer['id'],
								 ':date_paiement' => ($value_paiement['date']==date("d/m/Y")) ? $value_paiement['date']." ".date("H:i") : $value_paiement['date']." 00:00",
				 				 ':montant_ttc' => $value_paiement['montant'],
				 				 ':numero_cheque' => $value_paiement['num_cheque'],
				 				 ':permission' => $_SESSION['login'],
	 							 //':mode' => $value_paiement['mode2'],
	 							 ':mode' => $value_paiement['mode'],
	 				 			':permission2' => $_SESSION['login2']
	 				 	));	 
	 				 $st2->closeCursor();
						$reste_a_payer[$key_a_payer]['reglementttc']=$ma_var;
						//
						$value_a_payer['reglementttc']=$ma_var;
						//
						$key_a_payer==0 ? $reste_a_payer2[$key_a_payer]['reglementttc']+=$value_paiement['montant'] : '';
						$value_paiement['montant'] = 0;
						$info_paiement[$key_paiement]['montant'] = 0;
						//unset($info_paiement[$key_paiement]);  				 	
				
				}elseif(difference($reste_du, $value_paiement['montant'])<0 && $value_paiement['montant']>0 && $reste_du>0){
				$ma_var = ($value_a_payer['totalttc']-$value_a_payer['reglementttc']);
				//$mavariable[$key_paiement][$key_a_payer]["choix"]="superieur";
				//$mavariable[$key_paiement][$key_a_payer]["reste_du"]=$reste_du;
				//$mavariable[$key_paiement][$key_a_payer]["value_paiement_montant"]=$value_paiement['montant'];
				//$mavariable[$key_paiement][$key_a_payer]["reglementttc"]=$value_a_payer['reglementttc'];
				//$mavariable[$key_paiement][$key_a_payer]["totalttc"]=$value_a_payer['totalttc'];
				//$mavariable[$key_paiement][$key_a_payer]["ma_var"]=$ma_var;
				
					 $sql="UPDATE `aerogard2`.`facturation` SET reglementttc = :reglementttc where id= :id_facturation limit 1;
					 INSERT INTO `aerogard2`.`paiement` (id, id_fac, date, montant, numero_cheque, permission, mode, permission2) VALUES ('', :id_facturation, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_paiement,'%d/%m/%Y %H:%i')) *1000 ), :montant_ttc, :numero_cheque, :permission, :mode, :permission2)";
					 $st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	 				 $st2->execute(array(':reglementttc' => $value_a_payer['totalttc'],
	 				 			 ':id_facturation' => $value_a_payer['id'],
								 ':date_paiement' => ($value_paiement['date']==date("d/m/Y")) ? $value_paiement['date']." ".date("H:i") : $value_paiement['date']." 00:00",
				 				 ':montant_ttc' => $ma_var,
				 				 ':numero_cheque' => $value_paiement['num_cheque'],
				 				 ':permission' => $_SESSION['login'],
	 							 // ':mode' => $value_paiement['mode2'],
	 							 ':mode' => $value_paiement['mode'],
	 				 			 ':permission2' => $_SESSION['login2']
	 				 	));	 
	 				 $st2->closeCursor();
						$reste_a_payer[$key_a_payer]['reglementttc']=$reste_a_payer[$key_a_payer]['totalttc'];
						
						$key_a_payer==0 ? $reste_a_payer2[$key_a_payer]['reglementttc']=$reste_a_payer[$key_a_payer]['totalttc'] : '';
						$value_paiement['montant']=$value_paiement['montant']-($value_a_payer['totalttc']-$value_a_payer['reglementttc']);
						$info_paiement[$key_paiement]['montant']=$info_paiement[$key_paiement]['montant']-($value_a_payer['totalttc']-$value_a_payer['reglementttc']);			 	
						//
						$value_a_payer['reglementttc']=$reste_a_payer[$key_a_payer]['totalttc'];
						//
						//unset($reste_a_payer[$key_a_payer]); 
 				 					
				}else{					
					//$mavariable[$key_paiement][$key_a_payer]["choix"]="garage";
					//$mavariable[$key_paiement][$key_a_payer]["reste_du"]=$reste_du;
					//$mavariable[$key_paiement][$key_a_payer]["value_paiement_montant"]=$value_paiement['montant'];
					//$mavariable[$key_paiement][$key_a_payer]["reglementttc"]=$value_a_payer['reglementttc'];
					//$mavariable[$key_paiement][$key_a_payer]["totalttc"]=$value_a_payer['totalttc'];
										
				}
			}
			reset($reste_a_payer);
		}	
		
// $st2->closeCursor();				
				
		while (list($key_paiement, $value_paiement) = each($info_paiement_2))
		{
			//$mavariable[$key_paiement]["cat"]=$value_paiement['montant'];
				
			while (list($key_a_payer, $value_a_payer) = each($reste_a_payer_2))
			{
					
				$reste_du_medic = difference($value_a_payer['total_medic'],$value_a_payer['reglement_medic']);
				$reste_du_acte = difference($value_a_payer['total_acte'],$value_a_payer['reglement_acte']);
				
				if(difference($reste_du_medic, $value_paiement['montant'])==0 && $value_paiement['montant']>0){
					$ma_var = ($value_a_payer['reglement_medic']+$value_paiement['montant']);
							
					$sql="UPDATE `aerogard2`.`facturation` SET reglement_medic = :reglement_medic where id= :id_facturation limit 1";
					$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$st2->execute(array(':reglement_medic' => $ma_var,
							':id_facturation' => $value_a_payer['id']														
					));
					$st2->closeCursor();
					$reste_a_payer_2[$key_a_payer]['reglement_medic'] = $reste_a_payer_2[$key_a_payer]['total_medic'];
					$value_a_payer['reglement_medic']=$reste_a_payer_2[$key_a_payer]['total_medic'];
					$value_paiement['montant'] = 0;
					$info_paiement_2[$key_paiement]['montant'] = 0;
					
				}elseif(difference($reste_du_medic, $value_paiement['montant'])>0 && $value_paiement['montant']>0){
					$ma_var = ($value_a_payer['reglement_medic']+$value_paiement['montant']);
					
					$sql="UPDATE `aerogard2`.`facturation` SET reglement_medic = :reglement_medic where id= :id_facturation limit 1";
					$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$st2->execute(array(':reglement_medic' => $ma_var,
							':id_facturation' => $value_a_payer['id']
					));
					$st2->closeCursor();
					$reste_a_payer_2[$key_a_payer]['reglement_medic']=$ma_var;
					$value_a_payer['reglement_medic']=$ma_var;
					
					$value_paiement['montant'] = 0;
					$info_paiement_2[$key_paiement]['montant'] = 0;
				
		
				}elseif(difference($reste_du_medic, $value_paiement['montant'])<0 && $value_paiement['montant']>0 && $reste_du_medic>0){
					$ma_var = ($value_a_payer['total_medic']-$value_a_payer['reglement_medic']);
					
										
					$sql="UPDATE `aerogard2`.`facturation` SET reglement_medic = :reglement_medic where id= :id_facturation limit 1";
					$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$st2->execute(array(':reglement_medic' => $value_a_payer['total_medic'],
							':id_facturation' => $value_a_payer['id']							
					));
					$st2->closeCursor();
					$reste_a_payer_2[$key_a_payer]['reglement_medic']=$reste_a_payer_2[$key_a_payer]['total_medic'];

					$value_paiement['montant']=$value_paiement['montant']-($value_a_payer['total_medic']-$value_a_payer['reglement_medic']);
					$info_paiement_2[$key_paiement]['montant']=$info_paiement_2[$key_paiement]['montant']-($value_a_payer['total_medic']-$value_a_payer['reglement_medic']);
					//
					$value_a_payer['reglement_medic']=$reste_a_payer_2[$key_a_payer]['total_medic'];
					
																									
								if(difference($reste_du_acte, $value_paiement['montant'])==0 && $value_paiement['montant']>0){
									$ma_var = ($value_a_payer['reglement_acte']+$value_paiement['montant']);
										
																	
									$sql="UPDATE `aerogard2`.`facturation` SET reglement_acte = :reglement_acte where id= :id_facturation limit 1";
									$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
									$st2->execute(array(':reglement_acte' => $ma_var,
											':id_facturation' => $value_a_payer['id']											
									));
									$st2->closeCursor();
									$reste_a_payer_2[$key_a_payer]['reglement_acte'] = $reste_a_payer_2[$key_a_payer]['total_acte'];
									$value_a_payer['reglement_acte']=$reste_a_payer_2[$key_a_payer]['total_acte'];
									$value_paiement['montant'] = 0;
									$info_paiement_2[$key_paiement]['montant'] = 0;
										
								}elseif(difference($reste_du_acte, $value_paiement['montant'])>0 && $value_paiement['montant']>0){
									$ma_var = ($value_a_payer['reglement_acte']+$value_paiement['montant']);
									
														
									$sql="UPDATE `aerogard2`.`facturation` SET reglement_acte = :reglement_acte where id= :id_facturation limit 1";
									$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
									$st2->execute(array(':reglement_acte' => $ma_var,
											':id_facturation' => $value_a_payer['id']
									));
									$st2->closeCursor();
									$reste_a_payer_2[$key_a_payer]['reglement_acte']=$ma_var;
									$value_a_payer['reglement_acte']=$ma_var;
										
									$value_paiement['montant'] = 0;
									$info_paiement_2[$key_paiement]['montant'] = 0;
								
								
								}elseif(difference($reste_du_acte, $value_paiement['montant'])<0 && $value_paiement['montant']>0 && $reste_du_acte>0){
									$ma_var = ($value_a_payer['total_acte']-$value_a_payer['reglement_acte']);
									
																
									$sql="UPDATE `aerogard2`.`facturation` SET reglement_acte = :reglement_acte where id= :id_facturation limit 1";
									$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
									$st2->execute(array(':reglement_acte' => $value_a_payer['total_acte'],
											':id_facturation' => $value_a_payer['id']
									));
									$st2->closeCursor();
									$reste_a_payer_2[$key_a_payer]['reglement_acte']=$reste_a_payer[$key_a_payer]['total_acte'];
								
									$value_paiement['montant']=$value_paiement['montant']-($value_a_payer['total_acte']-$value_a_payer['reglement_acte']);
									$info_paiement_2[$key_paiement]['montant']=$info_paiement_2[$key_paiement]['montant']-($value_a_payer['total_acte']-$value_a_payer['reglement_acte']);
									//
									$value_a_payer['reglement_acte']=$reste_a_payer_2[$key_a_payer]['total_acte'];
										
										
										
								}				
					
				}
				elseif (difference($reste_du_acte, $value_paiement['montant'])==0 && $value_paiement['montant']>0){
					$ma_var = ($value_a_payer['reglement_acte']+$value_paiement['montant']);
				
									
					$sql="UPDATE `aerogard2`.`facturation` SET reglement_acte = :reglement_acte where id= :id_facturation limit 1";
					$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$st2->execute(array(':reglement_acte' => $ma_var,
							':id_facturation' => $value_a_payer['id']							
					));
					$st2->closeCursor();
					$reste_a_payer_2[$key_a_payer]['reglement_acte'] = $reste_a_payer_2[$key_a_payer]['total_acte'];
					$value_a_payer['reglement_acte']=$reste_a_payer_2[$key_a_payer]['total_acte'];
					$value_paiement['montant'] = 0;
					$info_paiement_2[$key_paiement]['montant'] = 0;
				
				}elseif(difference($reste_du_acte, $value_paiement['montant'])>0 && $value_paiement['montant']>0){
					$ma_var = ($value_a_payer['reglement_acte']+$value_paiement['montant']);
				
									
					$sql="UPDATE `aerogard2`.`facturation` SET reglement_acte = :reglement_acte where id= :id_facturation limit 1";
					$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$st2->execute(array(':reglement_acte' => $ma_var,
							':id_facturation' => $value_a_payer['id']
					));
					$st2->closeCursor();
					$reste_a_payer_2[$key_a_payer]['reglement_acte']=$ma_var;
					$value_a_payer['reglement_acte']=$ma_var;
				
					$value_paiement['montant'] = 0;
					$info_paiement_2[$key_paiement]['montant'] = 0;
				
				
				}elseif(difference($reste_du_acte, $value_paiement['montant'])<0 && $value_paiement['montant']>0 && $reste_du_acte>0){
					$ma_var = ($value_a_payer['total_acte']-$value_a_payer['reglement_acte']);
				
									
					$sql="UPDATE `aerogard2`.`facturation` SET reglement_acte = :reglement_acte where id= :id_facturation limit 1";
					$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$st2->execute(array(':reglement_acte' => $value_a_payer['total_acte'],
							':id_facturation' => $value_a_payer['id']
					));
					$st2->closeCursor();
					$reste_a_payer_2[$key_a_payer]['reglement_acte']=$reste_a_payer[$key_a_payer]['total_acte'];
				
					$value_paiement['montant']=$value_paiement['montant']-($value_a_payer['total_acte']-$value_a_payer['reglement_acte']);
					$info_paiement_2[$key_paiement]['montant']=$info_paiement_2[$key_paiement]['montant']-($value_a_payer['total_acte']-$value_a_payer['reglement_acte']);
					//
					$value_a_payer['reglement_acte']=$reste_a_payer_2[$key_a_payer]['total_acte'];
				
				
				
				}
			}
			reset($reste_a_payer_2);
		}	
		
		
		
		
}
//if(count($info_paiement)>0){
//$sql = "INSERT INTO `aerogard2`.`paiement` (id_fac, date, montant, numero_cheque, permission) VALUES "; 
//$qPart = array_fill(0, count($info_paiement), "(?, ( UNIX_TIMESTAMP(STR_TO_DATE(?,'%d/%m/%Y')) *1000 ), ?, ?, ?)");
//$sql .=  implode(",",$qPart);
//$stmt = $dbh -> prepare($sql); 
//$i = 1;
//foreach($info_paiement as $item) { 
//   $stmt -> bindParam($i++, $facturation_id);
//   $stmt -> bindParam($i++, $item['date']);
//   $stmt -> bindParam($i++, $item['montant']);
//   $stmt -> bindParam($i++, $item['num_cheque']);
//   $stmt -> bindParam($i++, $_SESSION['login']);  
//}
//$stmt -> execute(); 
//}
$ma_variable_repar = date("d/m/Y H:i");
if(count($repartition)>0 && $ma_valeur != 3){
	$sql = "INSERT INTO `aerogard2`.`echange` (`id_consult`, `permission`, `veto_desti`, `montant`, `date`) VALUES ";
	$qPart = array_fill(0, count($repartition), "(?, ?, ?, ?, STR_TO_DATE(?,'%d/%m/%Y %H:%i')) , (?, ?, ?, ?, STR_TO_DATE(?,'%d/%m/%Y %H:%i'))");
	$sql .=  implode(",",$qPart);
	$stmt = $db -> prepare($sql);
	$i = 1;
	foreach($repartition as $item) {
		$stmt -> bindParam($i++, $consultation_id);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $item['veto_desti']);
		$stmt -> bindParam($i++, $item['montant']);
		$stmt -> bindParam($i++, $ma_variable_repar);
		$stmt -> bindParam($i++, $consultation_id);
		$stmt -> bindParam($i++, $_SESSION['login']);
		$stmt -> bindParam($i++, $_SESSION['login2']);
		$montant2=-$item['montant'];
		$stmt -> bindValue($i++, $montant2);
		$stmt -> bindParam($i++, $ma_variable_repar);
				
	}
	$stmt -> execute();
	$stmt->closeCursor();
}
if(count($info_radio)>0 && $ma_valeur != 3){
$sql = "INSERT INTO `aerogard2`.`radiographie` (`nom`, `date`, `zone`, `expo`, `id_consult`, `permission`) VALUES "; 
$qPart = array_fill(0, count($info_radio), "(?, STR_TO_DATE(?,'%d/%m/%Y'), ?, ?, ?, ?)");
$sql .=  implode(",",$qPart);
$stmt = $db -> prepare($sql); 
$i = 1;
foreach($info_radio as $item) { 
   $stmt -> bindParam($i++, $item['perso']);
   $stmt -> bindParam($i++, $item['ma_date']);
   $stmt -> bindParam($i++, $item['zone']);
   $stmt -> bindParam($i++, $item['expo']);
   $stmt -> bindParam($i++, $consultation_id);
   $stmt -> bindParam($i++, $_SESSION['login']);  
}
$stmt -> execute(); 
$stmt->closeCursor();
}
if(count($info_relance)>0 && $ma_valeur != 3){
$sql = "INSERT INTO `aerogard2`.`rappel` (id_chien,id_pro,id_con,type,date,envoye,permission) VALUES "; 
$qPart = array_fill(0, count($info_relance), "(?, ?, ?, ?, ( UNIX_TIMESTAMP(STR_TO_DATE(?,'%d/%m/%Y')) *1000 ), '0', ?)");
$sql .=  implode(",",$qPart);
$stmt = $db -> prepare($sql); 
$i = 1;
foreach($info_relance as $item) { 
   $stmt -> bindParam($i++, $_POST['animal_id']);
   $stmt -> bindParam($i++, $info_client[0]['id2']);
   $stmt -> bindParam($i++, $consultation_id);
   $stmt -> bindParam($i++, $item['motif']);
   $stmt -> bindParam($i++, $item['date']);
   $stmt -> bindParam($i++, $_SESSION['login']);  
}
$stmt -> execute(); 
}
if($info_formulaire['rage']=='oui' && $ma_valeur != 3){
$sql = "INSERT INTO `aerogard2`.`vac_rage` (id,id_chien,identification,date,consult,valeur,lot,date2,vaccinateur,permission) VALUES('' , :id_chien, :identification, ( UNIX_TIMESTAMP(STR_TO_DATE(:date_consult,'%d/%m/%Y')) *1000 ), :id_consult, :valeur, :lot, :date2, :vaccinateur,  '".$_SESSION['login']."')";
$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':id_chien' => $_POST['animal_id'], ':identification' => ($info_animal[0]['num_p']=='' ? $info_animal[0]['num_t'] : $info_animal[0]['num_p']), ':date_consult' => $info_formulaire['date_consultation'], ':id_consult' => $consultation_id, ':valeur' => $info_formulaire['rage2_1'], ':lot' => $info_formulaire['rage3'], ':date2' => $info_formulaire['date_vac_prec_rage'], ':vaccinateur' => $info_formulaire['rage4']));
}

if($info_formulaire['passeport']=='oui' && $ma_valeur != 3){
$sql = "INSERT INTO `aerogard2`.`passeport` (id,date,id_consult,id_proprio,espece,id_chien,identification,num_pass,proprietaire,permission) VALUES('' ,( UNIX_TIMESTAMP(STR_TO_DATE(:date_consult,'%d/%m/%Y')) *1000 ), :id_consult, :id_proprio, :espece, :id_chien, :identification, :numpass, :proprio,'".$_SESSION['login']."')";
$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':date_consult' => $info_formulaire['date_consultation'], ':id_consult' => $consultation_id, ':id_proprio' => $info_client[0]['id2'], ':espece' => $info_animal[0]['espece'],':id_chien' => $_POST['animal_id'], ':identification' => ($info_animal[0]['num_p']=='' ? $info_animal[0]['num_t'] : $info_animal[0]['num_p']), ':numpass' => $info_formulaire['passeport2'], ':proprio' => $info_formulaire['passeport3']));
}
 
$st2 = $db->prepare("UPDATE `aerogard2`.`animal` SET num_p = '".$info_formulaire['puce']."', num_t='".$info_formulaire['tatouage']."', num_pa='".$info_formulaire['passeport2']."' where id='".$_POST['animal_id']."' limit 1;");
$st2->execute();

$st3 = $db->prepare("select login, nom, tel, adresse, code, commune, ordre, siret, num_tva, tva, marge from identification where login='".strtolower($_SESSION['login'])."' order by id desc limit 1");
$st3->execute();	
$info_veto =  json_encode($st3->fetchAll());
$variable = $consultation_id;		
$info_acte_a=json_decode($info_acte, TRUE);
$info_medic_a=json_decode($info_medic, TRUE);
if($ma_valeur == 2){
	if (!file_exists($filename2)) {
			if (!mkdir($filename2, 0755, true)) {
			  	  die('Echec lors de la création des répertoires...');
			}
	}
	if (file_exists($filename.'/facture_'.$_GET['consult'].'.pdf')) {	
		rename($filename.'/facture_'.$_GET['consult'].'.pdf', $filename2.'/facture_'.$_GET['consult'].'.pdf');
	}
	if (file_exists($filename.'/radio_'.$_GET['consult'].'.pdf')) {	
		rename($filename.'/radio_'.$_GET['consult'].'.pdf', $filename2.'/radio_'.$_GET['consult'].'.pdf');
	}
}
if($ma_valeur != 3){
	if(count($info_acte_a)>0 || count($info_medic_a)>0){
	//$lien_facture = creation_facture($info_client, $info_animal, json_decode($_POST['acte'], true), json_decode($_POST['medic'], true),"", $facturation_id, $total_reglement,$filename,$info_veto );
	$lien_facture = creation_facture($info_client, $info_animal, json_decode($_POST['acte'], true), json_decode($_POST['medic'], true),"", $facturation_id, $reste_a_payer2[0]['reglementttc'],$filename,$info_veto,$variable );
	}
}
$info_analyse2 = json_decode($_POST['analyse'], TRUE);
if(count($info_analyse2)>0){
$lien_analyse = creation_resultat_analyse($info_client, $info_animal, $info_analyse2, $info_formulaire['commentaire'], $variable,$filename,$info_veto);
}
$info_radio2 = json_decode($_POST['radio'], TRUE);
if(count($info_radio2)>0 || $info_formulaire['result_radio']!=''){
$lien_radio = creation_resultat_radio($info_client, $info_animal, $info_radio2, $info_formulaire['result_radio'], $variable,$filename,$info_veto);
}
if($ma_valeur == 2 && $illusion_compte==1 && $_SESSION['login']==$_SESSION['tour'] ){
	$_SESSION['login2']= $_SESSION['login'];
}
//echo json_encode($lien_facture);
//echo json_encode($mavariable);
echo json_encode($variable);
}catch (PDOException $e) {
	echo ($e->getMessage());
}
//echo json_encode($variable);
	
}elseif ($_GET['action']=='identification'){
$numero=json_decode($_POST['numero']);
$mon_choix = $_POST['choix'];
$st2 = $db->prepare("UPDATE `aerogard2`.`animal` SET $mon_choix='$numero' where id='".$_POST['animal_id']."' limit 1;");
$st2->execute();
echo json_encode("ok");
}elseif ($_GET['action']=='refere'){

$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$info_acte=$_POST['acte'];
$info_medic=$_POST['medic'];
$info_analyse=$_POST['analyse'];
$info_radio=$_POST['radio'];
$info_paiement=$_POST['paiement'];
$info_relance=$_POST['relance'];

$info_formulaire=$_POST['formulaire'];
$clinique = $info_formulaire['clinique'].' <br>Animal examiné par '.$_SESSION['login'];

$st = $db->prepare("INSERT INTO  `aerogard2`.`rapport_spe` (`id` ,`id_pro` ,`id_ani` ,`date_con` ,`resume` ,`poids` ,`temp` ,`freq_car` ,`clinique` ,`relance`,`rage1` ,`rage2` ,`rage3` ,`rage4` ,`rage5` ,`pass1` ,`pass2` ,`pass3` ,`analyse1` ,`analyse2` ,`acte` ,`medic`,`paiement`,`nom_a`,`nom_p`,`permission`,`puce`,`tatouage`,`radio1`,`radio2`,`num_consult`,`veto_origin`) VALUES ('' ,  ?,  ?,  STR_TO_DATE(?,'%d/%m/%Y'),  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,   STR_TO_DATE(?,'%d/%m/%Y'),  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");

$st->bindParam(1,$info_client[0]['id2']);
$st->bindParam(2,$_POST['animal_id']);
$st->bindParam(3,$info_formulaire['date_consultation']);
$st->bindParam(4,$info_formulaire['barre_resume']);
$st->bindParam(5,$info_formulaire['poids']);
$st->bindParam(6,$info_formulaire['temperature']);
$st->bindParam(7,$info_formulaire['cardio']);
$st->bindParam(8,$clinique);
$st->bindParam(9,$info_relance);
$st->bindParam(10,$info_formulaire['rage']);
$st->bindParam(11,$info_formulaire['rage2_1']);
$st->bindParam(12,$info_formulaire['rage3']);
$st->bindParam(13,$info_formulaire['rage4']);
$st->bindParam(14,$info_formulaire['date_vac_prec_rage']);
$st->bindParam(15,$info_formulaire['passeport']);
$st->bindParam(16,$info_formulaire['passeport2']);
$st->bindParam(17,$info_formulaire['passeport3']);
$st->bindParam(18,$info_analyse);
$st->bindParam(19,$info_formulaire['commentaire']);
$st->bindParam(20,$info_acte);
$st->bindParam(21,$info_medic);
$st->bindParam(22,$info_paiement);
$st->bindParam(23,$info_animal[0]['nom_a']);
$st->bindParam(24,$info_client[0]['nom']);
$st->bindParam(25,$_POST['choix_specialiste']);
$st->bindParam(26,$info_formulaire['puce']);
$st->bindParam(27,$info_formulaire['tatouage']);
$st->bindParam(28,$info_radio);
$st->bindParam(29,$info_formulaire['result_radio']);
$st->bindParam(30,$_POST['consultation']);
$st->bindParam(31,$_SESSION['login2']);

$st->execute();
$client_id = $db->lastInsertId();

$ma_var = $info_client[0]['nom'].' '.$info_animal[0]['nom_a'];
$st = $db->prepare("INSERT INTO  `aerogard2`.`liste_mur` (`id` , `texte`, `importance` ,`permission`) VALUES ('' ,  ?,  '2',  ?);");
$st->bindParam(1,$ma_var);
$st->bindParam(2,$_SESSION['login']);

$st->execute();

echo json_encode($client_id);

}elseif ($_GET['action']=='rapport_ref'){

$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$info_acte=$_POST['acte'];
$info_medic=$_POST['medic'];
$info_analyse=$_POST['analyse'];
$info_radio=$_POST['radio'];
$info_paiement=$_POST['paiement'];
$info_relance=$_POST['relance'];

$info_formulaire=$_POST['formulaire'];
$clinique = $info_formulaire['clinique'].' <br>Animal examiné par '.$_SESSION['login'];

$st = $db->prepare("INSERT INTO  `aerogard2`.`rapport_ref` (`id` ,`id_pro` ,`id_ani` ,`date_con` ,`resume` ,`poids` ,`temp` ,`freq_car` ,`clinique` ,`relance`,`rage1` ,`rage2` ,`rage3` ,`rage4` ,`rage5` ,`pass1` ,`pass2` ,`pass3` ,`analyse1` ,`analyse2` ,`acte` ,`medic`,`paiement`,`nom_a`,`nom_p`,`permission`,`puce`,`tatouage`,`radio1`,`radio2`,`num_consult`,`veto_origin`) VALUES ('' ,  ?,  ?,  STR_TO_DATE(?,'%d/%m/%Y'),  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,   STR_TO_DATE(?,'%d/%m/%Y'),  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");

$st->bindParam(1,$info_client[0]['id2']);
$st->bindParam(2,$_POST['animal_id']);
$st->bindParam(3,$info_formulaire['date_consultation']);
$st->bindParam(4,$info_formulaire['barre_resume']);
$st->bindParam(5,$info_formulaire['poids']);
$st->bindParam(6,$info_formulaire['temperature']);
$st->bindParam(7,$info_formulaire['cardio']);
//$st->bindParam(8,$info_formulaire['clinique']);
$st->bindParam(8,$clinique);
$st->bindParam(9,$info_relance);
$st->bindParam(10,$info_formulaire['rage']);
$st->bindParam(11,$info_formulaire['rage2_1']);
$st->bindParam(12,$info_formulaire['rage3']);
$st->bindParam(13,$info_formulaire['rage4']);
$st->bindParam(14,$info_formulaire['date_vac_prec_rage']);
$st->bindParam(15,$info_formulaire['passeport']);
$st->bindParam(16,$info_formulaire['passeport2']);
$st->bindParam(17,$info_formulaire['passeport3']);
$st->bindParam(18,$info_analyse);
$st->bindParam(19,$info_formulaire['commentaire']);
$st->bindParam(20,$info_acte);
$st->bindParam(21,$info_medic);
$st->bindParam(22,$info_paiement);
$st->bindParam(23,$info_animal[0]['nom_a']);
$st->bindParam(24,$info_client[0]['nom']);
$st->bindParam(25,$_POST['veto_ref']);
$st->bindParam(26,$info_formulaire['puce']);
$st->bindParam(27,$info_formulaire['tatouage']);
$st->bindParam(28,$info_radio);
$st->bindParam(29,$info_formulaire['result_radio']);
$st->bindParam(30,$_POST['consultation_id']);
$st->bindParam(31,$_SESSION['login2']);

$st->execute();
$client_id = $db->lastInsertId();

$info_analyse2 = json_decode($info_analyse);
$info_radio2 = json_decode($info_radio);
$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
$info_acte2 = json_decode($info_acte, TRUE);
$info_medic2 = json_decode($info_medic, TRUE);
if($_POST['veto_ref_mail']!=''){
	if(count($info_analyse2)>0 && count($info_radio2)>0 ){
	envoi_mail2($_POST['veto_ref_mail'], $_POST['veto_ref'], $info_client[0]['nom'], $info_animal[0]['nom_a'], $info_formulaire['date_consultation'], $info_formulaire['barre_resume'], $clinique, $info_acte2, $info_medic2,$_POST['consultation_id'],1,1, $nom_serveur_mail, $mail_serveur, $filename);
	}elseif(count($info_analyse2)==0 && count($info_radio2)>0){
	envoi_mail2($_POST['veto_ref_mail'], $_POST['veto_ref'], $info_client[0]['nom'], $info_animal[0]['nom_a'], $info_formulaire['date_consultation'], $info_formulaire['barre_resume'], $clinique, $info_acte2, $info_medic2,$_POST['consultation_id'],0,1, $nom_serveur_mail, $mail_serveur, $filename);
	}elseif(count($info_analyse2)>0 && count($info_radio2)==0 ){
	envoi_mail2($_POST['veto_ref_mail'], $_POST['veto_ref'], $info_client[0]['nom'], $info_animal[0]['nom_a'], $info_formulaire['date_consultation'], $info_formulaire['barre_resume'], $clinique, $info_acte2, $info_medic2,$_POST['consultation_id'],1,0, $nom_serveur_mail, $mail_serveur, $filename);
	}else{
	envoi_mail($_POST['veto_ref_mail'], $_POST['veto_ref'], $info_client[0]['nom'], $info_animal[0]['nom_a'], $info_formulaire['date_consultation'], $info_formulaire['barre_resume'], $clinique, $info_acte2, $info_medic2,$_POST['consultation_id'], $nom_serveur_mail, $mail_serveur);
	}
}
echo json_encode($client_id);

}elseif ($_GET['action']=='mur'){

$nom_client=$_POST['client'];
$nom_animal=$_POST['animal'];
$importance=$_POST['importance'];
$ma_var = $_POST['client'].' '.$_POST['animal'];

$st = $db->prepare("INSERT INTO  `aerogard2`.`liste_mur` (`id` ,`texte` ,`importance` ,`permission`) VALUES ('' , ?, ?, ?);");

$st->bindParam(1,$ma_var);
$st->bindParam(2,$importance);
$st->bindParam(3,$_SESSION['login']);

$st->execute();
$id_liste_mur = $db->lastInsertId();

echo json_encode($id_liste_mur);


}
elseif ($_GET['action']=='salle_attente'){


$info_client=$_POST['client'];
$info_animal=$_POST['animal'];
$info_acte=$_POST['acte'];
$info_medic=$_POST['medic'];
$info_analyse=$_POST['analyse'];
$info_radio=$_POST['radio'];
$info_paiement=$_POST['paiement'];
$info_relance=$_POST['relance'];

$info_formulaire=$_POST['formulaire'];

$st = $db->prepare("INSERT INTO  `aerogard2`.`salle_attente5` (`id` ,`id_pro` ,`id_ani` ,`date_con` ,`resume` ,`poids` ,`temp` ,`freq_car` ,`clinique` ,`relance`,`rage1` ,`rage2` ,`rage3` ,`rage4` ,`rage5` ,`pass1` ,`pass2` ,`pass3` ,`analyse1` ,`analyse2` ,`acte` ,`medic`,`paiement`,`nom_a`,`nom_p`,`permission`,`puce`,`tatouage`,`radio1`,`radio2`) VALUES ('' ,  ?,  ?,  STR_TO_DATE(?,'%d/%m/%Y'),  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,   STR_TO_DATE(?,'%d/%m/%Y'),  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?, ?, ?, ?, ?, ?, ?, ?);");

$st->bindParam(1,$info_client[0]['id2']);
$st->bindParam(2,$_POST['animal_id']);
$st->bindParam(3,$info_formulaire['date_consultation']);
$st->bindParam(4,$info_formulaire['barre_resume']);
$st->bindParam(5,$info_formulaire['poids']);
$st->bindParam(6,$info_formulaire['temperature']);
$st->bindParam(7,$info_formulaire['cardio']);
$st->bindParam(8,$info_formulaire['clinique']);
$st->bindParam(9,$info_relance);
$st->bindParam(10,$info_formulaire['rage']);
$st->bindParam(11,$info_formulaire['rage2_1']);
$st->bindParam(12,$info_formulaire['rage3']);
$st->bindParam(13,$info_formulaire['rage4']);
$st->bindParam(14,$info_formulaire['date_vac_prec_rage']);
$st->bindParam(15,$info_formulaire['passeport']);
$st->bindParam(16,$info_formulaire['passeport2']);
$st->bindParam(17,$info_formulaire['passeport3']);
$st->bindParam(18,$info_analyse);
$st->bindParam(19,$info_formulaire['commentaire']);
$st->bindParam(20,$info_acte);
$st->bindParam(21,$info_medic);
$st->bindParam(22,$info_paiement);
$st->bindParam(23,$info_animal[0]['nom_a']);
$st->bindParam(24,$info_client[0]['nom']);
$st->bindParam(25,$_SESSION['login']);
$st->bindParam(26,$info_formulaire['puce']);
$st->bindParam(27,$info_formulaire['tatouage']);
$st->bindParam(28,$info_radio);
$st->bindParam(29,$info_formulaire['result_radio']);

$st->execute();
$client_id = $db->lastInsertId();



echo json_encode($client_id);
}
}
function remplissage_formulaire($mon_array, $info_veto, $info_client, $info_animal){
	if($mon_array['valeur']=='veto'){
		return substr($_SESSION['login2'],0,40);	
	}elseif($mon_array['base']=='veto'){
		return substr($info_veto[0][$mon_array['valeur']],0,40);	
	}elseif($mon_array['base']=='client'){
		return substr($info_client[0][$mon_array['valeur']],0,40);	
	}elseif ($mon_array['base']=='animal'){
		return substr($info_animal[0][$mon_array['valeur']],0,40);	
	}else {
	    return date("d.m.y");
	}
}
function creation_facture($info_client, $info_animal, $info_acte, $info_medic,$titre, $facturation, $total_paye, $filename,$info_veto,$variable){			
			$info_veto = json_decode($info_veto, true);
			$tva=$info_veto[0]['tva'];
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
							 $pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
							 $pdf->Ln(15);
							 $pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
							 $pdf->MultiCell(85,5,requetemysql::gestion_string_maj("Facture éditée par ".$_SESSION['login2']),0,'L');
						   	 $pdf->SetFont('Times','',18);	
							 $pdf->SetFillColor(153,153,153);
			   			     $pdf->SetTextColor(0,0,0);
			 			     $pdf->SetDrawColor(153,153,153);
			 			     $pdf->SetLineWidth(.3);
			   			     $pdf->SetFont('','B');
							 $pdf->MultiCell(60,12,utf8_decode("Facture N°").$facturation,0,'L', true);
							 $pdf->Cell(50,12,utf8_decode("Animal :"),'LTB',0, false);
							 $pdf->SetFont('Times','',12);
							 $pdf->MultiCell(0,12,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
							 $prix_ht = 0;
							 $prix_tva = 0;
							 $prix_ttc = 0;
							 $pdf->SetFont('Times','',10);
							// Largeurs des colonnes
			    			$w = array(20, 75, 25, 15, 15, 25, 15);
			    			$header = array(utf8_decode('Date'), utf8_decode('Acte'), utf8_decode('Prix unit TTC'), utf8_decode('Qte'), utf8_decode('Rem'), utf8_decode("Prix total TTC"), utf8_decode("TVA"));
			    				// En-tête
			   				 for($i=0;$i<count($header);$i++)
			     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			   					  $pdf->Ln();
			    			// Données
			   				 foreach($info_acte as $row)
			  			  {
			  			  	 $pdf->Cell($w[0],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
			       			 $pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row['nom']),'LR',0,'L');
			        		 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['prix_unitaire']),'LR',0,'R');
			       			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['quantite']),'LR',0,'C');
			      			 $pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['remise']),'LR',0,'C');
			      			 $pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['prix_total']),'LR',0,'C');
			      			 $ma_tva=number_format($row['prix_total']-($row['prix_total']/(1+($tva/100))),2);
			      			 $prix_ht+=number_format($row['prix_total']/(1+($tva/100)),2);
			      			 $prix_tva+= $ma_tva;
			      			 $prix_ttc+=$row['prix_total'];
			      			 $pdf->Cell($w[6],6,requetemysql::gestion_string_norm($ma_tva),'LR',0,'C');
			      			 $pdf->Ln();
			   			 }
			    		// Trait de terminaison
			   			 $pdf->Cell(array_sum($w),0,'','T');			
						 $pdf->Ln(8);
						 $header = array(utf8_decode('Date'), utf8_decode('Médicament et lot'), utf8_decode('Prix unitaire TTC'), utf8_decode('Quantite'), utf8_decode('Remise'), utf8_decode("Prix total TTC"), utf8_decode("TVA"));
			    				// En-tête
			   				 for($i=0;$i<count($header);$i++)
			     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			   					  $pdf->Ln();
			    			// Données
			   				 foreach($info_medic as $row)
			  			  {
			  			  	 $pdf->Cell($w[0],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
			       			 $pdf->Cell($w[1],6,requetemysql::gestion_string_maj($row['nom']).' '.requetemysql::gestion_string_norm($row['lot']),'LR',0,'L');
			        		 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['prix_unitaire']),'LR',0,'R');
			       			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['quantite']),'LR',0,'C');
			      			 $pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['remise']),'LR',0,'C');
			      			 $pdf->Cell($w[5],6,requetemysql::gestion_string_norm($row['prix_total']),'LR',0,'C');
			      			 $ma_tva=number_format($row['prix_total']-($row['prix_total']/(1+($tva/100))),2);
			      			 $prix_ht+=number_format($row['prix_total']/(1+($tva/100)),2);
			      			 $prix_tva+= $ma_tva;
			      			 $prix_ttc+=$row['prix_total'];
			      			 $pdf->Cell($w[6],6,requetemysql::gestion_string_norm($ma_tva),'LR',0,'C');
			      			 $pdf->Ln();
			   			 }
			    		// Trait de terminaison
			   			 $pdf->Cell(array_sum($w),0,'','T');			
						 $pdf->Ln(8);
						 $pdf->Cell(35,7,'dont','LTR',0,'C');
						 $pdf->Cell(35,7,'HT',1,0,'C');
						 $pdf->Cell(35,7,'TVA',1,0,'C');
						 $pdf->Ln();
						 $tva2=$tva;
						 $pdf->Cell(35,7,'TVA '.$tva2.'%','LRB',0,'C');
						 $pdf->Cell(35,7,$prix_ht,'LRB',0,'C');
						 $pdf->Cell(35,7,$prix_tva,'LRB',0,'C');
						 $pdf->Cell(15,7,'',0,0,'C');
						 $pdf->Cell(65,7,'Total TTC : '.$prix_ttc.' euros',1,2,'C', true);	  
				 		 $pdf->Cell(65,7,requetemysql::gestion_string_maj('Réglé : '.$total_paye.' euros'),1,0,'C', true);
						 $pdf->Ln(8);
					//	 $titre=requetemysql::gestion_string_maj("Membre d'une association de gestion agréée. Le règlement des honoraires par chèque est accepté.");
						 $titre=requetemysql::gestion_string_maj("");
						 $w=$pdf->GetStringWidth(stripslashes($titre))+6;
			     		 $pdf->SetX((210-$w)/2);
				 		 $pdf->Cell($w,2,$titre,0,1,'C',false);
				 		 $pdf->Ln();
				  		 $titre2=requetemysql::gestion_string_norm("Siret: ".requetemysql::gestion_string_norm($info_veto[0]['siret'])." N° TVA :".requetemysql::gestion_string_norm($info_veto[0]['num_tva']));
				 		 $w=$pdf->GetStringWidth(stripslashes($titre2))+6;
					     $pdf->SetX((210-$w)/2);
						 $pdf->Cell($w,2,$titre2,0,1,'C',false);
					 	 $pdf->Output($filename.'/facture_'.$variable.'.pdf', F);
			return $variable;	

}

function difference($a, $b){
	$c=$a-$b;
	return $c;
}
function creation_resultat_radio($info_client, $info_animal, $info_radio, $commentaire, $mon_nombre, $filename,$info_veto){

$info_veto = json_decode($info_veto, true);
$variable = $mon_nombre;
//$info_client = json_decode($info_client, true);
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
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
				$pdf->Ln(15);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->SetFont('Times','',18);	
				$pdf->SetFillColor(153,153,153);
   			    $pdf->SetTextColor(0,0,0);
 			    $pdf->SetDrawColor(153,153,153);
 			    $pdf->SetLineWidth(.3);
   			    $pdf->SetFont('','B');
				$pdf->MultiCell(0,12,utf8_decode("Compte-rendu de l'examen radiologique"),0,'L', true);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,12,utf8_decode("Examen réalisé par").' '.requetemysql::gestion_string_maj($_SESSION['login2']),0,'L', true);
				$pdf->Cell(50,12,utf8_decode("Animal :"),'LTB',0, false);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,12,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
				 // Largeurs des colonnes
    			$w = array(190/4, 190/4, 190/4, 190/4);
    			$header = array(utf8_decode('Personnel exposé aux RX'), utf8_decode('constantes'), utf8_decode('date'), utf8_decode('Zone radiographiée'));
    				// En-tête
   				 for($i=0;$i<count($header);$i++)
     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
   					  $pdf->Ln();
    			// Données
   				 foreach($info_radio as $row)
  			  {
  			  
       			 $pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['perso']),'LR',0,'R');
        		 $pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['expo']),'LR',0,'R');
       			 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
      			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['zone']),'LR',0,'C');
      			 $pdf->Ln();
   			 }
    		// Trait de terminaison
   			 $pdf->Cell(array_sum($w),0,'','T');			
			 $pdf->Ln(4);
			 $pdf->MultiCell(0,10,utf8_decode("Résultat de la lecture de cliché :"),'LTRB','C', false);
			 $pdf->Ln(4);
			 $pdf->Write(5,requetemysql::gestion_string_maj($commentaire));
			 //$pdf->MultiCell(0,60,requetemysql::gestion_string_maj($commentaire),'LRB','J', false);	
			 $pdf->Ln(8);
			 $pdf->Cell(50,20,utf8_decode("Signature :"),'LTB',0, false);
			 $pdf->MultiCell(0,20,"",'TRB','L', false);	
			 
$pdf->Output($filename.'/radio_'.$variable.'.pdf', F);

return $variable;

}
function creation_resultat_analyse($info_client, $info_animal, $info_analyse, $commentaire, $mon_nombre, $filename,$info_veto){


$info_veto = json_decode($info_veto, true);
$variable = $mon_nombre;
//$info_client = json_decode($info_client, true);
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
				$pdf->MultiCell(85,5,requetemysql::gestion_string_maj($info_client[0]['nom']).' '.requetemysql::gestion_string_norm($info_client[0]['prenom'])."\n".requetemysql::gestion_string_norm($info_client[0]['adresse'])."\n".requetemysql::gestion_string_norm($info_client[0]['code']).' '.requetemysql::gestion_string_norm($info_client[0]['ville']),0,'C');
				$pdf->Ln(15);
				$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
				$pdf->SetFont('Times','',18);	
				$pdf->SetFillColor(153,153,153);
   			    $pdf->SetTextColor(0,0,0);
 			    $pdf->SetDrawColor(153,153,153);
 			    $pdf->SetLineWidth(.3);
   			    $pdf->SetFont('','B');
				$pdf->MultiCell(60,12,utf8_decode("Résultat d'analyse"),0,'L', true);
				$pdf->Cell(50,12,utf8_decode("Animal :"),'LTB',0, false);
				$pdf->SetFont('Times','',12);
				$pdf->MultiCell(0,12,requetemysql::gestion_string_maj($info_animal[0]['nom_a']).' '.requetemysql::gestion_string_norm(($info_animal[0]['espece']=='') ? "" : $info_animal[0]['espece']).' '.requetemysql::gestion_string_norm(($info_animal[0]['sexe']=='') ? "" : $info_animal[0]['sexe']).' '.requetemysql::gestion_string_norm(($info_animal[0]['race']=='') ? "" : $info_animal[0]['race']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_t']=='') ? "" : $info_animal[0]['num_t']).' '.requetemysql::gestion_string_norm(($info_animal[0]['num_p']=='') ? "" : $info_animal[0]['num_p']),'TRB','L', false);	
				 // Largeurs des colonnes
    			$w = array(190/4, 190/6, 190/6, 190/4, 190/6);
    			$header = array(utf8_decode('Référence'), utf8_decode('Résultat'), utf8_decode('Unité'), utf8_decode('Méthode'), utf8_decode("date d'analyse"));
    				// En-tête
   				 for($i=0;$i<count($header);$i++)
     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
   					  $pdf->Ln();
    			// Données
   				 foreach($info_analyse as $row)
  			  {
       			 $pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['nom']),'LR',0,'C');
        		 $pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['resultat']),'LR',0,'R');
       			 $pdf->Cell($w[2],6,requetemysql::gestion_string_norm($row['unite']),'LR',0,'C');
      			 $pdf->Cell($w[3],6,requetemysql::gestion_string_norm($row['methode']),'LR',0,'C');
      			 $pdf->Cell($w[4],6,requetemysql::gestion_string_norm($row['ma_date']),'LR',0,'C');
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
			 
$pdf->Output($filename.'/analyse_'.$variable.'.pdf', F);


return $variable;


}
function envoi_mail($mon_mail, $desti, $client_nom, $animal_nom, $date_consult, $resume, $clinique, $acte, $medic, $id_consult, $nom_serveur_mail, $mail_serveur) {

//	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mon_mail))
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	$message_txt = "Bonjour ".$desti.". <br>".$_SESSION['login2']." a reçu un de vos patients.".$_SESSION['login2']." a examiné l'animal le ".$date_consult.".<br>
	Client : ".$client_nom." Patient : ".$animal_nom." <br>
	Résumé de consultation : ".$resume."<br>
	Résumé de l'examen clinique : ".$clinique."<br>
	Actes réalisés :<br>";
	foreach($acte as $row)
	{
		$message_txt.="-".$row['nom']."-";
	}
	$message_txt .= "<br>Médicaments délivrés :<br>";
	foreach($medic as $row2)
	{
		$message_txt.="-".$row2['nom']."-";
	}
	$message_txt .= "<br>Vous pouvez voir les détails de cette consultation sur votre espace du serveur ou en sélectionnant le N° de consultation: ".$id_consult."  :<br>Sincères salutations";

	$message_html = "<html><head></head><body><p>Bonjour ".$desti.". </p><section><aside>".$_SESSION['login']." a reçu un de vos patients.".$_SESSION['login2']." a examiné l'animal le <time>".$date_consult."</time>.</aside><article>
	<p>Client : ".$client_nom." Patient : ".$animal_nom." </p>
	<p>Résumé de consultation : ".$resume."</p>
	<p>Résumé de l'examen clinique : ".$clinique."</p>
	<p>Actes réalisés :</p><ul>";
	foreach($acte as $row)
	{
		$message_html.="<li>".$row['nom']."</li>";
	}
	$message_html .= "</ul><p>Médicaments délivrés :</p><ul>";
	foreach($medic as $row2)
	{
		$message_html.="<li>".$row2['nom']."</li>";
	}
	$message_html .= "</ul></article></section><footer>Vous pouvez voir les détails de cette consultation sur votre espace du serveur ou en sélectionnant le N° de consultation: ".$id_consult."  :<br>Sincères salutations</footer></body></html>";

	//==========
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	//=====Définition du sujet.
	$sujet = "Rapport Urgencesvet cas: ".$client_nom;
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
	mail($mon_mail,utf8_decode($sujet),utf8_decode($message),$header);
	//==========

}
function envoi_mail2($mon_mail, $desti, $client_nom, $animal_nom, $date_consult, $resume, $clinique, $acte, $medic, $id_consult, $analyse, $radio, $nom_serveur_mail, $mail_serveur, $filename) {

 //	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mon_mail))
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	$message_txt = "Bonjour ".$desti.". <br>".$_SESSION['login']." a reçu un de vos patients.".$_SESSION['login2']." a examiné l'animal le ".$date_consult.".<br>
	Client : ".$client_nom." Patient : ".$animal_nom." <br>
	Résumé de consultation : ".$resume."<br>
	Résumé de l'examen clinique : ".$clinique."<br>
	Actes réalisés :<br>";
	foreach($acte as $row)
	{
		$message_txt.="-".$row['nom']."-";
	}
	$message_txt .= "<br>Médicaments délivrés :<br>";
	foreach($medic as $row2)
	{
		$message_txt.="-".$row2['nom']."-";
	}
	$message_txt .= "<br>Vous pouvez voir les détails de cette consultation sur votre espace du serveur ou en sélectionnant le N° de consultation: ".$id_consult."  :<br>Sincères salutations";

	$message_html = "<html><head></head><body><p>Bonjour ".$desti.". </p><section><aside>".$_SESSION['login']." a reçu un de vos patients.".$_SESSION['login2']." a examiné l'animal le <time>".$date_consult."</time>.</aside><article>
	<p>Client : ".$client_nom." Patient : ".$animal_nom." </p>
	<p>Résumé de consultation : ".$resume."</p>
	<p>Résumé de l'examen clinique : ".$clinique."</p>
	<p>Actes réalisés :</p><ul>";
	foreach($acte as $row)
	{
		$message_html.="<li>".$row['nom']."</li>";
	}
	$message_html .= "</ul><p>Médicaments délivrés :</p><ul>";
	foreach($medic as $row2)
	{
		$message_html.="<li>".$row2['nom']."</li>";
	}
	$message_html .= "</ul></article></section><footer>Vous pouvez voir les détails de cette consultation sur votre espace du serveur ou en sélectionnant le N° de consultation: ".$id_consult."  :<br>Sincères salutations</footer></body></html>";
	if($analyse==1){
	//=====Lecture et mise en forme de la pièce jointe analyse.
		$fichier   = fopen($filename."/analyse_".$id_consult.".pdf", "r");
		$attachement = fread($fichier, filesize($filename."/analyse_".$id_consult.".pdf"));
		$attachement = chunk_split(base64_encode($attachement));
		fclose($fichier);
	}
	if($radio==1){
		//=====Lecture et mise en forme de la pièce jointe radio.
		$fichier2   = fopen($filename."/radio_".$id_consult.".pdf", "r");
		$attachement2 = fread($fichier2, filesize($filename."/radio_".$id_consult.".pdf"));
		$attachement2 = chunk_split(base64_encode($attachement2));
		fclose($fichier2);
	}
	//==========
	//==========
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	$boundary_alt = "-----=".md5(rand());
	//==========
	//=====Définition du sujet.
	$sujet = "Rapport Urgencesvet cas: ".$client_nom;
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
	if($analyse==1){
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout de la pièce jointe analyse.
		$message.= "Content-Type: application/pdf; name=\"analyse_".$id_consult.".pdf\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
		$message.= "Content-Disposition: attachment; filename=\"analyse_".$id_consult.".pdf\"".$passage_ligne;
		$message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
		
		//==========
	}
	if($radio==1){
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout de la pièce jointe analyse.
		$message.= "Content-Type: application/pdf; name=\"radio_".$id_consult.".pdf\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
		$message.= "Content-Disposition: attachment; filename=\"radio_".$id_consult.".pdf\"".$passage_ligne;
		$message.= $passage_ligne.$attachement2.$passage_ligne.$passage_ligne;
	
		//==========
	}
	
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//=====Envoi de l'e-mail.
	mail($mon_mail,utf8_decode($sujet),$message,$header);
	//==========

}

?>