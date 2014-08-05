<?php 

	 //---------------------------------------------------------
	 // identification.controller.php
	 //---------------------------------------------------------
	
	define('TXT_IDENTIFICATION_CONTROLLER_LIST_CHOICE1', 'Garde partagée');
	define('TXT_IDENTIFICATION_CONTROLLER_LIST_CHOICE2', 'Clinique perso');
	define('TXT_IDENTIFICATION_CONTROLLER_LIST_CHOICE3', 'Affichage planning');
	define('TXT_IDENTIFICATION_CONTROLLER_TITTLE', 'Espace de connexion au logiciel de gestion des gardes !');
	define('TXT_IDENTIFICATION_CONTROLLER_ACTION', 'Entrez votre mail et votre mot de passe.');
	define('TXT_IDENTIFICATION_CONTROLLER_DOWNLOAD_TEXT', 'Connexion');
 	 //---------------------------------------------------------
 	 // _identification.php
 	 //---------------------------------------------------------
				// js part
				$list_text = array(
						erreur1 => "Connexion au serveur impossible : erreur 1.", 
						QapTcha_txtLock => "Vérouillé : bouge le curseur pour changer de mot de passe",
						QapTcha_txtUnlock => "débloqué : mail envoyé",
						check => "Vérification en cours...",
				);
				define('TXT_IDENTIFICATION_JSPARTS', json_encode($list_text));
				// html part
			 	 define('TXT_IDENTIFICATION_EMAIL', 'Email');
			 	 define('TXT_IDENTIFICATION_EMAIL2', 'monnom@email.com');
			 	 define('TXT_IDENTIFICATION_PASS', 'Password');
			 	 define('TXT_IDENTIFICATION_PASS_FORGOTTEN', 'mot de passe oublié.');
			 	 define('TXT_IDENTIFICATION_PASS_FORGOTTEN2', 'Vous avez oublié votre mot de passe ?');
			 	 define('TXT_IDENTIFICATION_PASS_FORGOTTEN3', 'votre email:');
			 	 define('TXT_IDENTIFICATION_PASS_SUBMIT', 'envoyer');
			 	 
	 //---------------------------------------------------------
	 // identification.php
	 //---------------------------------------------------------

			 	 define('TXTIDENTIFICATION_ERROR2', 'erreur adresse mail ou mot de passe.');
			 	 define('TXTIDENTIFICATION_ERROR3', "Votre compte a été désactivé. Contactez l'administrateur.");
			 	 define('TXTIDENTIFICATION_ERROR4', "Vous n'êtes pas inscrit dans un tour de garde. Contactez l'administrateur pour vous inscrire.");
			 	 define('TXTIDENTIFICATION_ERROR5', "Il faut remplir tous les champs");
			 	 define('TXTIDENTIFICATION_ERROR6', "Une erreur s'est produite");
			 	 define('TXTIDENTIFICATION_ERROR7', "Vous êtes déjà connecté ! recharger la page sans utiliser le formulaire !");
			 	 
			 	 
 	 //---------------------------------------------------------
 	 // rechercheclient.controller.php
 	 //---------------------------------------------------------

			 	 define('TXT_RECHERCHECLIENT_CONTROLLER_TITTLE', 'Accueil Aerogard  Fichier client/gestion clinique/statistiques');
			 	 define('TXT_RECHERCHECLIENT_CONTROLLER_SEARCH1', 'Rechercher dans la liste :');
			 	 define('TXT_RECHERCHECLIENT_CONTROLLER_SEARCH2', 'Recherche manuelle : 3 lettres min');

	 //---------------------------------------------------------
	 // _accueil.php
	 //---------------------------------------------------------
			 	 // js part
			 	 $list_text = array(
			 	 		erreur1 => "Connexion au serveur impossible : erreur 1.",
			 	 		delete_case => "Effacement du cas",
			 	 		refered_case => "Cas référé du",
			 	 		refered_case2 => "Référé par",
			 	 		delete_ => "Supprimer",
			 	 		refered_case3 => "Cas Référé :",
			 	 		refered_case_to_deal => "cas à traiter",
			 	 		delete_2 => "Suppression en cours...",
			 	 		check => "Vérification en cours...",
						casetotreat => "cas à traiter.",
			 	 		gardedu => "Garde du :",
			 	 		drafted => "/Rédigé à ",
			 	 		reportdrafted => "Rapport rédigés : ",
			 	 		reporttodraft => "rapport(s) à traiter",
			 	 		reportdrafted => "Rapport rédigés : ",
			 	 		reportreceived => "Rapport de gardes reçus : ",
			 	 		youhave => "vous avez ",
			 	 		animalsinwaitingroom => " animal(aux) en salle d'attente",
			 	 		deleteinprogress => "Suppression en cours...",
			 	 		saveotheranimal => "Enregistrer un autre animal",
			 	 		yearold => " an(s)",
			 	 		changetheanimalfolder => "Modifier fiche animal",
			 	 		changethecustomerfolder => "Modifier fiche client",
			 	 		chooseananimal => "Choisissez un animal...",
			 	 		saveanotheranimal => "Enregistrer un autre animal",
			 	 		shortMonths => array('Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'),
			 	 		shortDays => array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'),
			 	 		saveinprogress => "Enregistrement en cours...",
			 	 		uploadinprogress => "Chargement en cours...",
			 	 		uploadorganizerinprogress => "Chargement de l'agenda...",
			 	 		consultationsearch => "Recherche de la consultation...",
			 	 		update => "mise à jour...",
			 	 		today => "Aujourd'hui",
			 	 		longMonths => array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
			 	 		longDays => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
			 	 		organizerchange => "Pour modifier l'agenda d'un autre compte, vous devez vous connecter à ce compte",
			 	 		saverendezvous => "Enregistrer un rendez-vous :",
			 	 		sortofrendezvous => "Nature du rendez-vous :",
			 	 		saverendezvous2 => "Enregistrer un rendez-vous",
			 	 		pasteanimalnews => "Coller infos animal",
			 	 		deleterendezvous => "Supprimer ce rendez-vous",
			 	 		
			 	 );
			 	 
			 	 define('TXT_ACCUEIL_JSPARTS', json_encode($list_text));
			 	 // html part
			 	 define('TXT_ACCUEIL_OWNER', 'Proprio');
			 	 define('TXT_ACCUEIL_PET', 'Animal');
			 	 define('TXT_ACCUEIL_BEGIN', 'Débute');
			 	 define('TXT_ACCUEIL_CONTAIN', 'Contient');
			 	 define('TXT_ACCUEIL_CHOOSEAOWNER', 'Choisissez un client...');
			 	 define('TXT_ACCUEIL_SEEWAITINGROOM', "Voir la salle d'attente :");
			 	 define('TXT_ACCUEIL_REPORTEMERGENCY', "Rapport des animaux reçus en garde :");
			 	 define('TXT_ACCUEIL_YOURDRAFTREPORT', "Rapport des animaux reçus en garde :");
			 	 define('TXT_ACCUEIL_REFERREDCASE', "Cas référés :");
			 	 define('TXT_ACCUEIL_HISTORYCONSULTATION', "historique des consultations :");
			 	 define('TXT_ACCUEIL_CONSULTATIONSEARCH', "Rechercher une consultation (entrer le N°) :");
			 	 define('TXT_ACCUEIL_SEARCH', "rechercher");
			 	 
			 	 
	 //---------------------------------------------------------
	 // _header.php
	 //---------------------------------------------------------
			 	 
			 	 
			 	 define('TXT_HEADER_NEWCUSTOMER', 'Nouveau client');
			 	 define('TXT_HEADER_YOURSETTINGS', 'Paramètre de votre compte');
			 	 define('TXT_HEADER_CLINICMANAGEMENT', 'Gestion de la clinique');
			 	 define('TXT_HEADER_ORGANIZER', 'Agenda');
			 	 define('TXT_HEADER_TURN_DUTY', 'tour de garde');
			 	 define('TXT_HEADER_LABORATORY', 'Labo');
			 	 define('TXT_HEADER_DISCONNECTION', 'Déconnexion');
			 	 
	 //---------------------------------------------------------
	 // _footer.php
	 //---------------------------------------------------------

			 	 define('TXT_FOOTER_FROM', ' du ');
			 	 define('TXT_FOOTER_AVAILABLE_UNDER', ' est mis à disposition selon les termes de la ');
			 	 define('TXT_FOOTER_AVAILABLE', 'Fondé(e) sur une œuvre disponible sur ');
			 	 
	 //---------------------------------------------------------
	 // _footer2.php
	 //---------------------------------------------------------
			 	 
			 	 // js part
			 	 $list_text = array(
			 	 		care_you_have => "Garde : vous avez ",
			 	 		new_message => " nouveaux message(s).",	
			 	 		personal_you_have => "Perso : vous avez ",
			 	 		message_list => "liste des messages envoyés",
			 	 		write_message => "Ecrire un message",
			 	 		download_message => "récupération des messages...",
			 	 		private_message => "Mes messages perso :",
			 	 		care_message => "Mes messages perso :",
			 	 		message_you_send => "Les messages que vous avez envoyé :",
			 	 		send_by => "Envoyé par :",
			 	 		bound => "A destination de :",
			 	 		download_mes => "récupération du message...",
			 	 		message_send_by => "Message envoyé par :",
			 	 		at => " le :",
			 	 		send_message => "Envoyer un message à un membre :",
			 	 		recipient => "Destinataire :",
			 	 		title_message => "titre du message :",
			 	 		send_message2 => "Envoyer un message :",
			 	 		send_message3 => "Envoi du message...",
			 	 		
			 	 
			 	 );
			 	 
			 	 define('TXT_FOOTER2_JSPARTS', json_encode($list_text));
			 	 // html part
			 	 define('TXT_FOOTER2_PAD', 'bloc-note');
			 	 define('TXT_FOOTER2_MANAGEMENT', 'Administration');
			 	 define('TXT_FOOTER2_SEARCHMESSAGE', 'rechercher un message...');
			 	 define('TXT_FOOTER2_PERSONALMESSAGE', 'Mes messages perso :');
			 	 
	 //---------------------------------------------------------
	 // _agenda.php
	 //---------------------------------------------------------
	 
			 	 // js part
			 	 $list_text = array(
			 	 		download_organizer => "Chargement de l'agenda...",
			 	 		up_date => "mise à jour...",
			 	 		today => "Aujourd'hui",
			 	 		shortMonths => array('Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'),
			 	 		shortDays => array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'),
			 	 		longMonths => array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
			 	 		longDays => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
			 	 		upload_ => "Chargement...",
			 	 		new_event_text => "Nouvelle consultation",
			 	 		organizerchange => "Pour modifier l'agenda d'un autre compte, vous devez vous connecter à ce compte",
			 	 		saverendezvous => "Enregistrer un rendez-vous :",
			 	 		sortofrendezvous => "Nature du rendez-vous :",
			 	 		saverendezvous2 => "Enregistrer un rendez-vous",
			 	 		send_message => "Envoi du message...",
			 	 		pasteanimaldata => "Coller infos animal",
			 	 		deleterendezvous => "Supprimer ce rendez-vous",
			 	 		deleteinprogress => "Suppression en cours...",
			 	 );
			 	 
			 	 define('TXT_AGENDA_JSPARTS', json_encode($list_text));			 	 
	 			// html part
			 	 define('TXT_AGENDA_SELECTDATE', 'Sélectionner une date :');
			 	 define('TXT_AGENDA_SELECTORGANIZER', 'Sélectionner un agenda : ');
			 	 
	 //---------------------------------------------------------
	 // _gestion_prog.controller.php
	 //---------------------------------------------------------
			 	 define('TXT_GESTION_PROG_CONTROLLER_USERMANAGEMENT', "Administration des membres");
			 	 
	 //---------------------------------------------------------
	 // _gestion_prog.php
	 //---------------------------------------------------------
	 
			 	 // js part
			 	 $list_text = array(
			 	 		error_writting_pass1 => "Erreur dans la saisie du mot de passe",
			 	 		error_writting_pass2 => "Vous n'avez pas saisie le même mot de passe",
			 	 		login_check => "Verification du login",
			 	 		login_already_use1 => "login ou mail déjà pris !!",
			 	 		login_already_use2 => "Changer de login et de mail",
			 	 		delete_care1 => "Voulez-vous dissoudre ce tour de garde ? Dans ce cas, le compte sera effacé et le tour de tous les membres réinitialisés.",
			 	 		yes_ => "oui",
			 	 		delete_care2 => "Dissolution du tour de garde...",
			 	 		no_ => "Non",
			 	 		delete_user => "Voulez-vous désactiver ce membre ? Dans ce cas, le compte sera desactivé.",
			 	 		delete_in_progress => "désactivation en cours...",
			 	 		reborn1 => "Voulez-vous réactiver ce membre ou ce tour ?",
			 	 		reborn2 => "réactivation en cours...",
			 	 		change_user_care => "Voulez-vous affecter ce tour de garde à ce membre ?",
			 	 		change_user_care2 => "modification tour de garde...",
			 	 		group_to_membre => "Voulez-vous affecter ce groupe à ce membre ?",
			 	 		changing_group => "modification groupe...",
			 	 		mail_to_membre => "Voulez-vous affecter le mail de ce membre ?",
			 	 		changing_mail => "modification mail...",
			 	 		changing_pass => "modification pass...",
			 	 		
			 	 		
			 	 );
			 	 			 	 
			 	 define('TXT_GESTION_PROG_JSPARTS', json_encode($list_text));
			 	 // html part
			 	 define('TXT_GESTION_PROG_SOFTWAREMANAGEMENT', "Espace de gestion d'Aerogard :");
			 	 define('TXT_GESTION_PROG_ADDCARE', "Ajouter un tour de garde :");
			 	 define('TXT_GESTION_PROG_LOGINNEWCARE', "Login de ce tour :");
			 	 define('TXT_GESTION_PROG_EMAILNEWCARE', "mail de ce tour :");
			 	 define('TXT_GESTION_PROG_PASSNEWCARE', "Passeword de ce tour :");
			 	 define('TXT_GESTION_PROG_REWRITTEPASSNEWCARE', "Retapez le passeword :");
			 	 define('TXT_GESTION_PROG_ADDTHISCARE', "Ajouter ce tour de garde");
			 	 define('TXT_GESTION_PROG_DELETEACARE', "Dissoudre un tour de garde :");
			 	 define('TXT_GESTION_PROG_CHOOSEACARETODELETE', "Choisir un tour à dissoudre :");
			 	 define('TXT_GESTION_PROG_ADDUSER', "Ajouter un membre :");
			 	 define('TXT_GESTION_PROG_USERLOGIN', "Login de ce membre :");
			 	 define('TXT_GESTION_PROG_USEREMAIL', "mail de ce membre :");
			 	 define('TXT_GESTION_PROG_USERPASS', "Password :");
			 	 define('TXT_GESTION_PROG_USERREPASS', "Retapez le password :");
			 	 define('TXT_GESTION_PROG_ADDUSER', "Ajouter ce membre");
			 	 define('TXT_GESTION_PROG_DELETEUSER', "Supprimer un membre :");
			 	 define('TXT_GESTION_PROG_CHOOSEUSERTODELETE', "Choisir un membre à désactiver :");
			 	 define('TXT_GESTION_PROG_DELETEUSER2', "Désactiver un membre");
			 	 define('TXT_GESTION_PROG_USERREBORN', "Réactiver un membre ou un tour:");
			 	 define('TXT_GESTION_PROG_CHOOSEUSERORCARETOREBORN', "Choisir un membre ou un tour à réactiver :");
			 	 define('TXT_GESTION_PROG_CHOOSEUSERORCARETOREBORN2', "Réactiver un membre ou un tour");
			 	 define('TXT_GESTION_PROG_CHANGECAREOFUSER', "Modifier le tour de garde d'un membre :");
			 	 define('TXT_GESTION_PROG_CHOOSEUSER', "Choisir un membre :");
			 	 define('TXT_GESTION_PROG_SELECTCARE', "Choisir le tour à sélectionner :");
			 	 define('TXT_GESTION_PROG_CHANGECAREUSER', "modifier le tour de ce membre");
			 	 define('TXT_GESTION_PROG_LOGIN', "login");
			 	 define('TXT_GESTION_PROG_CARE', "tour");
			 	 define('TXT_GESTION_PROG_CHANGEGROUPUSER', "Modifier le groupe d'un membre :");
			 	 define('TXT_GESTION_PROG_CHOOSEUSER2', "Choisir un membre :");
			 	 define('TXT_GESTION_PROG_CHOOSEGROUPTOSELECT', "Choisir le groupe à sélectionner :");
			 	 define('TXT_GESTION_PROG_CHANGEGROUPUSER2', "modifier le groupe de ce membre");
			 	 define('TXT_GESTION_PROG_GROUP', "group");
			 	 define('TXT_GESTION_PROG_CHANGEEMAIL', "Modifier le mail de connexion d'un membre :");
			 	 define('TXT_GESTION_PROG_NEWMAIL', "nouveau mail :");
			 	 define('TXT_GESTION_PROG_CHANGEEMAIL2', "modifier le mail de ce membre");
			 	 define('TXT_GESTION_PROG_EMAIL', "mail");
			 	 define('TXT_GESTION_PROG_CHANGEPASS', "Modifier le pass d'un membre :");
			 	 define('TXT_GESTION_PROG_NEWPASS', "nouveau pass :");
			 	 define('TXT_GESTION_PROG_CHANGEPASS2', "modifier le pass de ce membre");
			 	 
	 //---------------------------------------------------------
	 // _nouveauclient.controller.php
 	 //---------------------------------------------------------
			 	 define('TXT_NOUVEAUCLIENT_CONTROLLER_USERMANAGEMENT', "Fiche Client");
			 	 define('TXT_NOUVEAUCLIENT_CONTROLLER_DOWNLOADINPROGRESS', "enregistrement en cours");
	 
	 //---------------------------------------------------------
	 // _nouveauclient.php
	 //---------------------------------------------------------
			 	 // js part
			 	 $list_text = array(
			 	 		changing_data => "Modification des données",
			 	 		);			 	 		
			 	 define('TXT_NOUVEAUCLIENT_JSPARTS', json_encode($list_text));
			 	 // html part
			 	 define('TXT_NOUVEAUCLIENT_NEWCLIENT', "Création fiche nouveau client");
			 	 define('TXT_NOUVEAUCLIENT_UPDATECLIENTCARD', "Mise à jour de la fiche du client :");
			 	 define('TXT_NOUVEAUCLIENT_VETREFERRING', "Vétérinaire référent :");
			 	 define('TXT_NOUVEAUCLIENT_NOTAPPICABLE', "Sans Objet");
			 	 define('TXT_NOUVEAUCLIENT_LASTNAME', "Nom :");
			 	 define('TXT_NOUVEAUCLIENT_FIRSTNAME', "Prenom :");
			 	 define('TXT_NOUVEAUCLIENT_ADDRESS', "Adresse :");
			 	 define('TXT_NOUVEAUCLIENT_ZIP', "Code postal:");
			 	 define('TXT_NOUVEAUCLIENT_TOWN', "Commune :");
			 	 define('TXT_NOUVEAUCLIENT_GEOLOCATION', "Géolocaliser");
			 	 define('TXT_NOUVEAUCLIENT_PHONENUMBER', "Téléphone");
			 	 define('TXT_NOUVEAUCLIENT_EMAIL', "Email:");
			 	 define('TXT_NOUVEAUCLIENT_SENDREMINDERSBYMAIL', "Envoyer les relances par mail:");
			 	 define('TXT_NOUVEAUCLIENT_NO', "non");
			 	 define('TXT_NOUVEAUCLIENT_YES', "oui");
			 	 define('TXT_NOUVEAUCLIENT_UNWANTED', "Indésirable :");
			 	 define('TXT_NOUVEAUCLIENT_DATA', "donnée");
			 	 define('TXT_NOUVEAUCLIENT_BACK', "retour");
			 	 define('TXT_NOUVEAUCLIENT_OK', "valider");
			 	 
	 //---------------------------------------------------------
	 // nouveauanimal.controller.php
	 //---------------------------------------------------------
			 	 define('TXT_NOUVEAUANIMAL_CONTROLLER_DOG', "chien");
			 	 define('TXT_NOUVEAUANIMAL_CONTROLLER_CAT', "chat");
			 	 define('TXT_NOUVEAUANIMAL_CONTROLLER_OTHER', "autres");
			 	 define('TXT_NOUVEAUANIMAL_CONTROLLER_TITLE', "Fiche Animal");
			 	 define('TXT_NOUVEAUANIMAL_CONTROLLER_DOWNLOADINPROGRESS', "enregistrement en cours");
			 	 
	 //---------------------------------------------------------
	 // _nouveauanimal.php
	 //---------------------------------------------------------
			 	 
			 	 // html part
			 	 define('TXT_NOUVEAUANIMAL_CREATEANIMALCARD', "Création fiche nouvel animal");
			 	 define('TXT_NOUVEAUANIMAL_FORTHECLIENT', "pour le client");
			 	 define('TXT_NOUVEAUANIMAL_IDCLIENT', "id propriétaire :");
			 	 define('TXT_NOUVEAUANIMAL_UPDATEANIMALCARD', "Mise à jour de la fiche de l'animal :");
			 	 define('TXT_NOUVEAUANIMAL_NUMBERID', "numéro id :");
			 	 define('TXT_NOUVEAUANIMAL_OWNED', "appartenant à");
			 	 define('TXT_NOUVEAUANIMAL_NAME', "Nom :");
			 	 define('TXT_NOUVEAUANIMAL_SPECIES', "Espece :");
			 	 define('TXT_NOUVEAUANIMAL_GENDER', "Sexe :");
			 	 define('TXT_NOUVEAUANIMAL_FEMALE', "femelle");
			 	 define('TXT_NOUVEAUANIMAL_FEMALESTERILIZED', "femelle sterilisée");
			 	 define('TXT_NOUVEAUANIMAL_MALE', "male");
			 	 define('TXT_NOUVEAUANIMAL_MALESTERILIZED', "male castré");
			 	 define('TXT_NOUVEAUANIMAL_BIRTHDAY', "Date de Naissance :");
			 	 define('TXT_NOUVEAUANIMAL_AGE', "Age (Year-Month-Day):");
			 	 define('TXT_NOUVEAUANIMAL_CHIPSNUMBER', "Numero puce electronique :");
			 	 define('TXT_NOUVEAUANIMAL_TATOONUMBER', "Numero tatouage :");
			 	 define('TXT_NOUVEAUANIMAL_PASSNUMBER', "Numero passeport :");
			 	 define('TXT_NOUVEAUANIMAL_DANGEROUSANIMAL', "Animal dangereux ?");
			 	 define('TXT_NOUVEAUANIMAL_YES', "oui");
			 	 define('TXT_NOUVEAUANIMAL_NO', "non");
			 	 define('TXT_NOUVEAUANIMAL_AVAILABLEFORBREEDING', "Animal disponible pour la reproduction ?");
			 	 define('TXT_NOUVEAUANIMAL_DATEOFDEATH', "Date de Décès :");
			 	 define('TXT_NOUVEAUANIMAL_BACK', "retour");
			 	 define('TXT_NOUVEAUANIMAL_OK', "valider");
			 	 define('TXT_NOUVEAUANIMAL_ANIMALFILES', "Fichiers sauvegardé concernant cet animal  :");
			 	 
	 //---------------------------------------------------------
	 // modificationmembre.controller.php
	 //---------------------------------------------------------
			 
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_GENERALSURGERY', "Chirurgie générale");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_BEHAVIOR', "Comportement");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_ULTRASOUND', "Echographie");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_ULTRASOUNDHEART', "Echographie cardiaque");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_ENDOSCOPY', "Endoscopie");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_HORSE', "Equine");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_INTERNALMEDICINE', "Médecine interne");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_NEWPET', "NAC");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_OPHTALMOLOGY', "Ophtalmologie");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_ORTHOPEDY', "Orthopédie");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_SCANNER', "Scanner");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_RETURNTOME', "Diriger l'animal vers mon établissement au plus tôt. Je déciderai si un spécialiste est nécessaire.");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTNOPB', "Diriger l'animal vers le spécialiste (avec l'adhésion des maîtres) en cas de doute.");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTFORSURGERY', "Diriger l'animal vers le spécialiste pour tout problème chirurgical.");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTFORRADIOGRAPHY', "Diriger l'animal vers le spécialiste pour tout diagnostic d'imagerie.");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_GOTOTHESPECIALISTIFMOREPERSONNALNEED', "Diriger l'animal vers le spécialiste pour tout acte nécessitant plus de 2 mains.");
			 	 define('TXT_MODIFICATIONMEMBRE_CONTROLLER_ABOUTME', "Fiche Vétérinaire");
			 	 
		//---------------------------------------------------------
		// _modificationmembre.php
		//---------------------------------------------------------
			 	 
			 	// js part
			 	 $list_text = array(
			 	 		medicine_rate_change_alert => "La liste des tarifs des medicaments a été modifiée.",
			 	 		medicine_paper_setting_up => "Création de la feuille de tarif en cours...",
			 	 		percent_medicine_rate_change => "La liste des tarifs des actes a subit une variation de ",
			 	 		round_act_rate => "Arrondi effectué sur la liste des tarifs des actes avec ",
			 	 		number_after_decimal => " chiffre(s) après la virgule",
			 	 		percent_act_rate_change => "La liste des tarifs des actes a subit une variation de ",
			 	 		search => "Recherche en cours...",
			 	 		record => "Enregistrement en cours...",
			 	 		rate_paper_setting_up => "Création de la feuille de tarif en cours...",			 	 		
			 	 );
			 	 			 	 
			 	 define('TXT_MODIFICATIONMEMBRE_JSPARTS', json_encode($list_text));
			 	 
	 //---------------------------------------------------------
 	 // Fin
 	 //---------------------------------------------------------



?>