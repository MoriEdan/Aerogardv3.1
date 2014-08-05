<?php
error_reporting(E_ALL ^ E_NOTICE);
/*=========== Database Configuraiton ==========*/
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = 'juliee';
$db_name = 'aerogard2';
/*=========== Website Configuration ==========*/
$defaultTitle = 'Aerogardv3 solution opensource de gestion des gardes.';
$defaultFooter = date('Y').' &copy; Aerogard';
/*=========== marge medicament ==========*/
//$marge_medic = 50;
/*=========== TVA ==========*/
//$tva = 0.2;
/*=========== mail ==========*/
$mail_serveur = "veterinairedegarde@free.fr";
$nom_serveur_mail = "Urgencesvet";
/*=========== url ==========*/
$url_serveur = "http://urgencesvet.no-ip.org/";
/*=========== language ==========*/
$default_lang = 'fr'; //langue par défaut
$dir_lang = './language/'; //répertoire des fichiers langues
$dir_lang2 = '../language/'; //répertoire des fichiers langues
$extension = '.php'; //extension des fichiers langue

//$langues = array('en', 'es', 'fr');
$langues = array('fr');
/*=========== horaire ==========*/
date_default_timezone_set('Europe/Paris');



?>