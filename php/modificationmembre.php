<?php
/* il faut demarrer la session*/
session_start();
if (empty($_SESSION['id'])){

	header('HTTP/1.1 400 Bad Request');
	exit("votre session a expiré. Reconnectez-vous !!");

}elseif(!empty($_SESSION['id'])){
	require_once "config.php";
	require_once "connexionmysql.php";
	require_once "requetemysql.php";
	require('fpdf/fpdf.php');
	require('fpdi/fpdi.php');
	$data3= $_GET['action']; 
	    
	if ($data3=="enregistrement"){
	
	$formulaire = $_POST['formulaire'];
	$conduite = $_POST['conduite'];
	$specialiste = $_POST['specialiste'];
	$specialite = json_decode($_POST['specialite'],true);
	$tarif = json_decode($_POST['tarif'],true);
	$tarif2 = json_decode($_POST['tarif2'],true);
	$tarif_medic = json_decode($_POST['tarif_medic'],true);
	$indispo = json_decode($_POST['indispo'],true);
	$vacances = json_decode($_POST['vacances'],true);
	try {
	$st = $db->prepare("UPDATE `identification` SET `nom`=:nom,`tel`=:tel,`adresse`=:adresse,`code`=:code,`commune`=:commune,
	`mail2`=:mail,`ip`=:ip,`tel2`=:tel2,`ordre`=:ordre,`siret`=:siret,`num_tva`=:numtva,`conduite_suivre`=:conduite,
	`choix_specialiste`=:choix_specialiste,`mention_speciale`=:mention_speciale,`marge`=:marge,`tva`=:tva,`referent`=:referent where login='".$_SESSION['login']."' LIMIT 1;");
	$st->execute(array(':nom' => $formulaire['nom']
	, ':tel' => $formulaire['telephone']
	, ':adresse' => $formulaire['adresse']
	, ':code' => $formulaire['codepostal']
	, ':commune' => $formulaire['commune']
	, ':mail' => $formulaire['email']
	, ':ip' => $_SERVER["REMOTE_ADDR"]
	, ':tel2' => $formulaire['telephone2']
	, ':ordre' => $formulaire['ordre']
	, ':siret' => $formulaire['siret']
	, ':numtva' => $formulaire['numtva']
	, ':conduite' => $conduite
	, ':choix_specialiste' => $specialiste
	, ':mention_speciale' => $formulaire['commentaire']
	, ':marge' => $formulaire['marge_medic']
	, ':tva' => $formulaire['tva']
	, ':referent' => $formulaire['referent']
	));
	
	$st->closeCursor();
	
	$sql ="DELETE FROM `specialiste` WHERE nom=:nom";
	$st = $db->prepare($sql);
	$st->execute(array(':nom' => $_SESSION['login']));
	$st->closeCursor();
	
	if(count($specialite)>0){ 	
	
	
	$sql = "INSERT INTO `specialiste`(`id_spe`, `domaine`, `commune`, `nom`) VALUES "; 
	$qPart = array_fill(0, count($specialite), "(?, ?, ?, ?)");
	$sql .=  implode(",",$qPart);
	$stmt = $db -> prepare($sql); 
	$i = 1;
	foreach($specialite as $item) { 
	   $stmt -> bindParam($i++, $_POST['id_veto']);
	   $stmt -> bindParam($i++, $item['nom']);
	   $stmt -> bindParam($i++, $formulaire['commune']);
	   $stmt -> bindParam($i++, $_SESSION['login']);  
				}
		$stmt -> execute(); 
		}
		
	$sql = "DELETE FROM tarif WHERE (permission=:permission);";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':permission' => $_SESSION['login']));
	$stmt->closeCursor();
		
	if(count($tarif)>0){ 
			
		    
			$sql = "INSERT INTO `tarif` (`acte`, `tarifttc`, `permission`, `taille`) VALUES "; 
			$qPart = array_fill(0, count($tarif), "(?, ?, ?, ?)");
			$sql .=  implode(",",$qPart);
			$stmt = $db -> prepare($sql); 
			$i = 1;
			foreach($tarif as $item) { 
			   $stmt -> bindParam($i++, $item['acte']);
			   $stmt -> bindParam($i++, $item['tarifttc']);
			   $stmt -> bindParam($i++, $_SESSION['login']);  
			   $stmt -> bindParam($i++, $item['taille']);		  
						}
			   $stmt -> execute(); 
					
			}	
	$sql = "DELETE FROM tarif2 WHERE (permission=:permission);";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':permission' => $_SESSION['login']));
	$stmt->closeCursor();
			
	if(count($tarif2)>0){				
				 
				$sql = "INSERT INTO `tarif2` (`acte`, `tarifttc`, `permission`) VALUES ";
				$qPart = array_fill(0, count($tarif2), "(?, ?, ?)");
				$sql .=  implode(",",$qPart);
				$stmt = $db -> prepare($sql);
				$i = 1;
				foreach($tarif2 as $item) {
					$stmt -> bindParam($i++, $item['acte']);
					$stmt -> bindParam($i++, $item['tarifttc']);
					$stmt -> bindParam($i++, $_SESSION['login']);				
				}
				$stmt -> execute();
			
			}
		
			$sql = "DELETE FROM medicament WHERE (permission=:permission);";
			$stmt = $db->prepare($sql);
			$stmt->execute(array(':permission' => $_SESSION['login']));
			$stmt->closeCursor();
			
			if(count($tarif_medic)>0){
					
				$sql = "INSERT INTO `medicament` (`nom`, `centrale`, `prixht`, `lot`, `permission`) VALUES ";
				$qPart = array_fill(0, count($tarif_medic), "(?, ?, ?, ?, ?)");
				$sql .=  implode(",",$qPart);
				$stmt = $db -> prepare($sql);
				$i = 1;
				foreach($tarif_medic as $item) {
					$stmt -> bindParam($i++, $item['nom']);
					$stmt -> bindParam($i++, $item['centrale']);
					$stmt -> bindParam($i++, $item['prixht']);
					$stmt -> bindParam($i++, $item['lot']);
					$stmt -> bindParam($i++, $_SESSION['login']);
				}
				$stmt -> execute();
					
			}
			
			$info_tour = requetemysql::info_tour(array('login'=>$_SESSION['tour']));
			if(empty($info_tour)){
				throw new Exception("Aucun tour dans la base de donnée !");
			}
			$info_tour_array = json_decode($info_tour,true);
			$parti_deco = json_decode($info_tour_array[0]['participant']);
			while (list($key, $value) = each($parti_deco))
			{
			
				if($value->login==$indispo['login']){
					$parti_deco[$key]->jour_evi = $indispo['jour_evi'];
					$parti_deco[$key]->jour_evi2 = $indispo['jour_evi2'];
						
				}
			}
			$parti_deco_enco = json_encode($parti_deco);
		//	while (list($key, $value) = each($info_tour_array['participant']))
		//	{
		//		if($value['login']==$indispo['login']){
		//			$info_tour_array['participant'][$key]['jour_evi'] = $indispo['jour_evi'];
		//			$info_tour_array['participant'][$key]['jour_evi2'] = $indispo['jour_evi2'];
		//			
		//		}
		//	}
			$garde_vac = array();
			$vac_deco = json_decode($info_tour_array[0]['vacances']);
			while (list($key, $value) = each($vac_deco))
			{							
					if($value->login != $_SESSION['login2']){
						array_push($garde_vac, $value);
							
					}				
				
			}
			
	//		while (list($key, $value) = each($info_tour_array['vacances']))
	//		{
	//			while (list($key2, $value2) = each($indispo['vacances']))
	//			{
	//				if($value['login']!=$value2['login']){
	//					array_push($garde_vac, $info_tour_array['vacances'][$key]);
													
	//				}
	//			}
	//		}
			while (list($key2, $value2) = each($vacances))
			{
				array_push($garde_vac, $value2);
			}
			$vac_deco_enco = json_encode($garde_vac);
			
			$sql="UPDATE tourdegarde2 set participant=:participant, vacances=:vacances WHERE id=:mon_id AND tour=:permission LIMIT 1";
			$st2 = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$st2->execute(array(':participant' => $parti_deco_enco, ':vacances' => $vac_deco_enco, ':mon_id' => $info_tour_array[0]['id'], ':permission' => $_SESSION['tour']));
			$st2->closeCursor();
			
	echo json_encode($parti_deco_enco);
	}catch (PDOException $e) {
	        die($e->getMessage());
	 }
	
	}elseif ($data3=="recherche"){
	
		$specialiste = requetemysql::liste_specialistes(array('domaine'=>$_POST['valeur']));
		echo $specialiste;
		}else if($data3=='print_tarif'){
	$filename = '../sauvegarde/clinique/impression_tarif/'.$_SESSION['login'];
	$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
	if(empty($info_veto)){
	throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
	}else{
	$info_veto = json_decode($info_veto, true);
	}
	$tarifs=$_POST['tarifs'];
	
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
					$pdf->MultiCell(60,12,utf8_decode("Liste des tarifs"),0,'', true);
					$pdf->SetFont('Times','',12);
					 // Largeurs des colonnes
	    			$w = array(190/3, 190/5);
	    			$header = array(utf8_decode('Intitulé'), utf8_decode('Tarif TTC'));
	    				// En-tête
	   				 for($i=0;$i<count($header);$i++)
	     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	   					  $pdf->Ln();
	    			// Données
	   				 foreach($tarifs as $row)
	  			  {
	       			 $pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['acte']),'LR',0,'C');
	        		 $pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['tarifttc']),'LR',0,'R');
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
				 $mon_url = '../sauvegarde/clinique/impression_tarif/'.$_SESSION['login'].'/tarifs_'.date("d_m_y").uniqid().'.pdf';
				 //$pdf->Output($mon_url, F);
				 $pdf->Output($mon_url, F);
				 echo json_encode($mon_url);
		
	}else if($data3=='print_tarif2'){
	$filename = '../sauvegarde/clinique/impression_tarif/'.$_SESSION['login'];
	$info_veto = requetemysql::info_veterinaire(array('login'=>strtolower($_SESSION['login'])));
	if(empty($info_veto)){
	throw new Exception("Erreur dans la recherche des informations sur le vétérinaire");
	}else{
	$info_veto = json_decode($info_veto, true);
	}
	$tarifs=$_POST['tarifs'];
	
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
					$pdf->MultiCell(180,12,utf8_decode("Liste des tarifs des médicaments:"),0,'', true);
					$pdf->SetFont('Times','',12);
					$pdf->MultiCell(180,12,requetemysql::gestion_string_maj("Cette liste est définie avec une marge de ".$_POST['marge']."% et une TVA de ".$_POST['tva']."%"),0,'L');
					// Largeurs des colonnes
	    			$w = array(190/3, 190/5);
	    			$header = array(utf8_decode('Intitulé'), utf8_decode('Tarif ttc'));
	    				// En-tête
	   				 for($i=0;$i<count($header);$i++)
	     				   $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	   					  $pdf->Ln();
	    			// Données
	   				 foreach($tarifs as $row)
	  			  {
	       			 $pdf->Cell($w[0],6,requetemysql::gestion_string_maj($row['nom']),'LR',0,'C');
	        		 $pdf->Cell($w[1],6,requetemysql::gestion_string_norm($row['prixttc']),'LR',0,'R');
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
				 $mon_url = '../sauvegarde/clinique/impression_tarif/'.$_SESSION['login'].'/tarifs_'.date("d_m_y").uniqid().'.pdf';
				 //$pdf->Output($mon_url, F);
				 $pdf->Output($mon_url, F);
				 echo json_encode($mon_url);
		
	}
}
?>