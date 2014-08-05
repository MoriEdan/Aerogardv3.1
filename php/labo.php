<?php
/* il faut demarrer la session*/
session_start();
require_once "config.php";
require_once "connexionmysql.php";
require_once "requetemysql.php";
require('fpdf/fpdf.php');
require('fpdi/fpdi.php');
require_once "requetemysql.php";
if ($_GET['action']=='save'){
	
		//define('UPLOAD_DIR', '../sauvegarde/animaux/'.$_POST['id'].'/');
		//$img = $_POST['imgBase64'];
		//$img = str_replace('data:image/png;base64,', '', $img);
		//$img = str_replace(' ', '+', $img);
		//$data = base64_decode($img);
		//$file = UPLOAD_DIR . 'ordonnance'. uniqid() . '.png';
		//$success = file_put_contents($file, $data);
		//print $success ? $file : 'Unable to save the file.';
		
	$filename = '../sauvegarde/animaux/'.$_POST['animal_id'];
	$info_veto = $_POST['veto'];
	$mon_logo = $_POST['logo'];
	$mon_animal = $_POST['animal'];
	$medoc = $_POST['medoc'];
	$commentaire = $_POST['commentaire'];	
	$horaires = $_POST['horaires'];
	$competences = $_POST['competences'];
	if (!file_exists($filename)) {
		if (!mkdir($filename, 0755, true)) {
			die('Echec lors de la création des répertoires...');
		}
	}
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Times','',22);
	$pdf->Image('../image/logo/essai1.jpg',10,10,50,40);
	$titre3=utf8_decode(stripslashes(ucfirst($info_veto[0]['nom'])));
	$w=$pdf->GetStringWidth(stripslashes($titre3))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,$titre3,0,'C');
	$pdf->SetFont('Times','',18);
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['adresse'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['adresse'])),0,'C');
	$pdf->Ln();
	$w=$pdf->GetStringWidth(utf8_decode(stripslashes($info_veto[0]['tel'])))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,7,utf8_decode(stripslashes($info_veto[0]['tel'])),0,'C');
	$pdf->Ln(30);
	$pdf->SetFont('Times','',12);
	$pdf->MultiCell(85,5,"Le ".date("d.m.y"),0,'L');
	$pdf->Ln();
	$pdf->MultiCell(85,5,requetemysql::gestion_string_ok("Ordonnance rédigée par le Dr ".requetemysql::gestion_string_maj($_SESSION['login2'])),0,'L');
	$pdf->Cell(90);
	$pdf->SetFont('Times','B',13);
	$pdf->MultiCell(70,7,requetemysql::gestion_string_ok($mon_animal),0,'L');
	$pdf->SetFont('Times','',12);
	$pdf->Ln(25);
	if(count($medoc)!=0){
		foreach($medoc as $key => $row)
		{
			$numero_medoc = $key+1;
			$pdf->SetFont('Arial','BU',14);
			$pdf->Cell(100,6,$numero_medoc."- ".requetemysql::gestion_string_maj($row['nom']),0,0,'L');
			$pdf->SetFont('Times','',12);
			$w=$pdf->GetStringWidth(requetemysql::gestion_string_maj($row['qte']))+6;
			$pdf->SetX(210-$w);
			$pdf->Cell($w,6,requetemysql::gestion_string_norm($row['qte']),0,0,'L');
			$pdf->Ln(8);
			$pdf->MultiCell(0,10,requetemysql::gestion_string_norm($row['texte']),0,'L');
			$pdf->Ln(8);
		}
	}
	$pdf->Ln(20);
	if($commentaire!=''){
	$pdf->Cell(50,20,utf8_decode("Commentaire :"),0,0, false);
	$pdf->MultiCell(0,20,requetemysql::gestion_string_maj($commentaire),0,'L', false);
	$pdf->Ln(8);
	}
	//$pdf->Cell(50,20,utf8_decode("Signature :"),0,0, false);
	//$pdf->MultiCell(0,20,"",0,'L', false);	
	//$pdf->Ln(8);
	$titre=requetemysql::gestion_string_ok($horaires);
	$w=130;
	$pdf->SetXY((210-$w)/2,-45);
	$pdf->MultiCell($w,7,$titre,0,'C',false);
	$pdf->Ln();
	$titre2=requetemysql::gestion_string_ok($competences);
	$w=$pdf->GetStringWidth(stripslashes($titre2))+6;
	$pdf->SetX((210-$w)/2);
	$pdf->Cell($w,2,$titre2,0,1,'C',false);
	$mon_url = $filename.'/ordonnance_'.date("d_m_y").uniqid().'.pdf';
	//$pdf->Output($mon_url, F);
	$pdf->Output($mon_url, F);
	echo json_encode($mon_url);	
}
?>
