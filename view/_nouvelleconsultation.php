<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">
$.fn.clicktoggle = function(a, b) {
    return this.each(function() {
        var clicked = false;
        $(this).click(function() {
            if (clicked) {
                clicked = false;
                return b.apply(this, arguments);
            }
            clicked = true;
            return a.apply(this, arguments);
        });
    });
};

$( document ).on( "pageinit", function( event ) {
	
	$("#formnouvelleconsultation ").keypress(function(e) {
		
		  if (e.which == 13 && e.target.id!='clinique') {
		    return false;
		  }
		});
	
    
	var origin = <?php echo json_encode($origin);?>;
	var allowUnload = true;
	var autorisation_quitter = true;
	window.onbeforeunload = function(e){
		if(origin =='_salle_attente' && autorisation_quitter){
				if(allowUnload){
				var message = "Les données en salle d'attente vont être perdues. Voulez-vous continuer ?",
				e = e||window.event;
				if(e)
				e.returnValue=message; // IE
				return message; // Safari
				}
			}
		};
	// document.getElementsByTagName('body')[0].onclick = function(){
	//		allowUnload = false;
			//setTimeout so we can reset allowUnload incase user didn't leave the page but randomly clicked.
	//		setTimeout(function(){ allowUnload = true; },100);
	 // };	
	var choix_medic = <?php echo json_encode($liste_tournures);?>;
	var liste_cat_delivre = <?php echo json_encode($liste_cat_delivre);?>;
	var info_veto = <?php echo $info_veto;?>;
	var choix_retour = <?php echo $historique; ?>;
	var animal = <?php echo $animal; ?>;
	var animal_id = <?php echo $id_ani; ?>;
	var client = <?php echo $client; ?>;
	var tva = <?php echo $tva; ?>;
	var marge_medic = <?php echo $marge_medic; ?>;
	var reste_du = <?php echo $restedu; ?>;
	var salle_attente_donnee = <?php echo $salle_attente_donnee; ?>;
	var cas = <?php echo json_encode($cas); ?>;
	var liste_vetos = <?php echo $liste_vetos; ?>;
	var veto_repartition = <?php echo $veto_repartition; ?>;
	var date_actu = new Date();
	var mon_login = findItem(<?php echo json_encode($_SESSION['login2']); ?>, liste_vetos);	
	var entete = "Dr "+<?php echo json_encode($_SESSION['login2']); ?>+" <br /> "+mon_login[0]['nom']+" <br /> "
			+mon_login[0]['adresse']+" <br /> "+mon_login[0]['code']+" <br /> "+mon_login[0]['commune']
			+" <br /><br /><br /><br />le "+date_actu.getDate()+"/"+(date_actu.getMonth())+"/"+(date_actu.getFullYear())
			+" <br /><br />Certificat concernant l'animal : "+animal[0]['nom_a']+" "+animal[0]['espece']+" "
			+animal[0]['sexe']+" "+animal[0]['race']+" <br />Num tatouage: "+animal[0]['num_t']+" "	
			+"Num puce: "+animal[0]['num_p']		
			+"<br /><br /><br /><br /><br /><br />Signature :";
	$('#dossier').fileTree({
 	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
 	 	script: './js/connectors/jqueryFileTree.php'
 	 	
        }, function(file) { 
        	//alert(file.substr(6));
        	
        	window.location.href = file.substr(6);

        });
	$(".animation_rappel").hide();
 	$(".animation_rappel2").hide();
 	$(".animation_rage").hide();
 	$(".animation_rage2").hide();
 	$(".animation_passeport").hide();
 	$(".animation_paiement").hide();
	console.log("donne salle attente :"+JSON.stringify(salle_attente_donnee));
	if(client[0]['ref']!=0){
		var ma_liste = $.grep(liste_vetos, function (e) {			
		    return e.id === client[0]['ref'];		   
		});
		console.log("liste    "+JSON.stringify(ma_liste));
		var msg_ref = '<p>Cet animal a comme vétérinaire traitant : '+ma_liste[0].nom+' à '+ma_liste[0].commune+' tel :'+ma_liste[0].tel+' mail :'+ma_liste[0].mail+'</p>';
		var button_ref = $("<button id='button_ref'>"+msg_ref+"</button>");
		$("#referent").append(button_ref);
		button_ref.button();
		$('#button_ref').on('click', function () {
			 var $popUp2 = $("#popup-1").popup({
			        dismissible: false,
			        theme: "b",
			        overlyaTheme: "e",
			        transition: "pop"
			    }).on("popupafterclose", function () {
			        //remove the popup when closing
			        $(this).remove();
			    }).css({
			        'width': '370px',
			            'height': '400px',
			            'padding': '5px'
			    });
			    //create a title for the popup
			    $("<h4/>", {
			        text: "Conduite à suivre définie par "+ma_liste[0].login
			    }).appendTo($popUp2);
				var ma_liste_ref = $("<div data-role='collapsible-set' data-theme='b' data-content-theme='a' data-collapsed-icon='arrow-r' data-expanded-icon='arrow-d' style='margin:0; width:350px;'></div>").appendTo($popUp2 );
				var ma_sous_liste_ref = $("<div data-role='collapsible' data-inset='false'></div>").appendTo( ma_liste_ref );
				$("<h2>Conduite à suivre définies par "+ma_liste[0].login+"</h2>").appendTo( ma_sous_liste_ref );
				var ma_sous_liste_conteneur_ref = $("<ul id='ref_1' data-role='listview'></ul>").appendTo( ma_sous_liste_ref );	  	                
				$.each($.parseJSON(ma_liste[0].conduite_suivre), function(key, val) {
					$("<li>"+val.nom+"</li>").appendTo( ma_sous_liste_conteneur_ref );	  
				 });
				var ma_sous_liste_ref2 = $("<div data-role='collapsible' data-inset='false'></div>").appendTo( ma_liste_ref );
				$("<h2>Spécialistes sélectionnés</h2>").appendTo( ma_sous_liste_ref2 );
				var ma_sous_liste_conteneur_ref2 = $("<ul id='ref_2' data-role='listview'></ul>").appendTo( ma_sous_liste_ref2 );	
				$.each($.parseJSON(ma_liste[0].choix_specialiste), function(key, val) {
					$("<li >"+val.nom+" : "+val.domaine+"</li>").appendTo( ma_sous_liste_conteneur_ref2 );
				 });
				var ma_sous_liste_ref3 = $("<div data-role='collapsible' data-inset='false'></div>").appendTo( ma_liste_ref );
				$("<h2>Commentaire</h2>").appendTo( ma_sous_liste_ref3 );
				var ma_sous_liste_conteneur_ref3 = $("<ul id='ref_3' data-role='listview'></ul>").appendTo( ma_sous_liste_ref3 );
				$("<li>"+ma_liste[0].mention_speciale+"</li>").appendTo( ma_sous_liste_conteneur_ref3 );				
			   
			    //create a back button
			    $("<a>", {
			        text: "Back",
			            "data-rel": "back"
			    }).buttonMarkup({
			        inline: false,
			        mini: true,
			        theme: "e",
			        icon: "back"
			    }).appendTo($popUp2);
			   
			    $popUp2.popup('open').trigger("create");
			    $('#ref_1').trigger('refresh');
			    $('#ref_2').trigger('refresh');
			    $('#ref_3').trigger('refresh');
		});			        
	}
	if(cas=='rapport_recus' || cas=='rapport_emis'){

		var ma_liste2 = $.grep(liste_vetos, function (e) {			
		    return e.login === salle_attente_donnee[0]['veto_origin'];		   
		});
		console.log("liste    "+JSON.stringify(ma_liste2));
		var msg_ref = '<p>Cet animal a été reçu en urgence par : '+ma_liste2[0].nom+' à '+ma_liste2[0].commune+' tel :'+ma_liste2[0].tel+'</p>';
		$("#urgence").append(msg_ref);
				
	}
	if(cas=='envoi_refere'){
		var ma_liste3 = $.grep(liste_vetos, function (e) {			
		    return e.login === salle_attente_donnee[0]['veto_origin'];		   
		});
		console.log("liste    "+JSON.stringify(ma_liste3));
		var msg_ref = '<p>Cet animal vous est référé par : '+ma_liste3[0].nom+' de '+ma_liste3[0].commune+' tel :'+ma_liste3[0].tel+'</p>';
		$("#urgence").append(msg_ref);
	}
		
	if(animal[0]['num_p']=='' && animal[0]['num_t']==''){
		$('#section_rage').hide();
		$('#section_pass').hide();
	}
	if(salle_attente_donnee!=0){
		$('#barre_resume').val(salle_attente_donnee[0]['resume']);	
		$('#date_consultation').val(salle_attente_donnee[0]['formatted_date']);
		$('#poids').val(salle_attente_donnee[0]['poids']);
		$('#poids').slider('refresh');
		$('#temperature').val(salle_attente_donnee[0]['temp']);
		$('#temperature').slider('refresh');
		$('#cardio').val(salle_attente_donnee[0]['freq_car']);
		$('#cardio').slider('refresh');
		$('#clinique').html(salle_attente_donnee[0]['clinique'].replace(/<br>/, '\n'));
		if(salle_attente_donnee[0]['rage1'] == 'oui'){
			$('#collaps_rage').trigger('expand');
			$(".animation_rage").show();
			$('#rage').val(salle_attente_donnee[0]['rage1']).slider('refresh');
			$('#rage2_1').val(salle_attente_donnee[0]['rage2']);
			is_int(salle_attente_donnee[0]['rage2']) ? $("#rage2 li:eq(salle_attente_donnee[0]['rage2'])").attr("data-theme", "b").removeClass("ui-btn-up-c").removeClass('ui-btn-hover-c').addClass("ui-btn-up-b").addClass('ui-btn-hover-b') : "";
			$('#rage3').val(salle_attente_donnee[0]['rage3']);
			$('#rage4').val(salle_attente_donnee[0]['rage4']);
			$('#date_vac_prec_rage ').val(salle_attente_donnee[0]['formatted_date2']);
			}else{
			$('#collaps_rage').trigger('collapse');
		}		
		if(salle_attente_donnee[0]['pass1'] == 'oui'){
			$('#collaps_pass').trigger('expand');
			$(".animation_passeport").show();
			$('#passeport').val(salle_attente_donnee[0]['pass1']).slider('refresh');
			$('#passeport2').val(salle_attente_donnee[0]['pass2']).slider('refresh');
			$('#passeport3').val(salle_attente_donnee[0]['pass3']).slider('refresh');			
		}
		
		salle_attente_donnee[0]['relance'] != '[]' ? $('#collaps_rappel').trigger('expand') : $('#collaps_rappel').trigger('collapse') ;
		salle_attente_donnee[0]['radio'] != '[]' ? $('#collaps_radio').trigger('expand') : $('#collaps_radio').trigger('collapse') ;
		(salle_attente_donnee[0]['analyse1'] != '[]' && (cas=='salle_attente' || cas=='rapport_recus' || cas=='rapport_emis' || cas=='envoi_refere')) ? $('#collaps_ana').trigger('expand') : $('#collaps_ana').trigger('collapse') ;
		$('#commentaire').val(salle_attente_donnee[0]['analyse2']);
		if(cas=='rapport_recus' || cas=='rapport_emis' || cas=='envoi_refere'){
			$('#section_refere').hide();
		}
		}

		function objet_relance(date, motif, id){
			this.date = ko.observable(date);
			this.motif = ko.observable(motif);
			this.id_select = ko.observable(id);
			
		};
		function objet_paiement(mode, montant, date, id, num_cheque){
			this.date = ko.observable(date);
			this.mode = ko.observable(mode);
			this.mode2 = ko.observable(String(mode)+' '+String(num_cheque));
			this.montant = ko.observable(montant);
			this.id_select = ko.observable(id);
			this.num_cheque = ko.observable(num_cheque);

		};
		function objet_reste_du_detail(data,index) {
			this.source = ko.observable(data['id_c']);	
			var maDate = new Number(data.date);	 	  	
	 	 	maDate = new Date(maDate);
	 	 	console.log("madate"+maDate);
	 	 	var somme_du = data.totalttc-data.reglementttc;
	 	 	this.detail = ko.observable(String($('#date_consultation').datebox('callFormat', '%d/%m/%Y', maDate))+" "+String(data.nom_a)+" "+String(somme_du)+"euros");
		};
		function objet_acte_creation(nom, prix_unitaire, date, id, quantite, remise) {

			//self.acte_ajoutes.push({nom : $(event.target).text(), prix_unitaire : $(event.target).attr('value'), remise : 0, quantite : 1, ma_date : $("#date_acte").val(), prix_total : $(event.target).attr('value'), id_select :  self.acte_ajoutes().length});
	
			this.nom = nom;
			this.prix_unitaire = prix_unitaire;
			this.remise = remise;
			this.quantite = ko.observable(quantite);	
			this.ma_date = date;
			var mon_prix_total = prix_unitaire*quantite*(1-(remise/100));
			this.prix_total = ko.observable(mon_prix_total.toFixed(2));
			this.id_select = id;
		}
		function objet_medic_creation(nom, lot, prix_unitaire, date, id, quantite, remise) {

			//self.acte_ajoutes.push({nom : $(event.target).text(), prix_unitaire : $(event.target).attr('value'), remise : 0, quantite : 1, ma_date : $("#date_acte").val(), prix_total : $(event.target).attr('value'), id_select :  self.acte_ajoutes().length});
	
			this.nom = nom;
			this.lot = lot;
			this.prix_unitaire = prix_unitaire;
			this.remise = remise;
			this.quantite = ko.observable(quantite);	
			this.ma_date = date;
			var mon_prix_total = prix_unitaire*quantite*(1-(remise/100));
			this.prix_total = ko.observable(mon_prix_total.toFixed(2));
			this.id_select = id;
		}
		function objet_liste_tarif(data,index) {
			this.valeur = ko.observable("tag"+String(data['taille']));
			this.nom = ko.observable(data['acte']);
			this.prix = ko.observable(data['tarifttc']);	
		}
		function objet_liste_resume(data,index) {
			this.valeur = ko.observable("tag"+String(data['valeur']));
			this.resume = ko.observable(data['nom']);

		}
		function objet_article_creation(data,index) {
			//var ma_liste = new String(data);
			//var ma_liste_array = ma_liste.split('_');
			//var mon_titre_soin = ma_liste_array[0].split(':');
			//this.medicament = ko.observable(mon_titre_soin[0]);
			//this.prix = ko.observable(ma_liste_array[1]);
			//this.lot = ko.observable(ma_liste_array[2]);

			this.medicament = ko.observable(String(data['nom'])+' Qté:'+String(data['quantite'])+' Rem:'+String(data['remise']));
			this.prix = ko.observable(data['prix_total']);
			this.date = ko.observable(data['ma_date']);			

		}
		function objet_soins_creation(data,index) {
	 	   this.id = ko.observable(data.id);
	 	   this.motif = ko.observable(data.motif);
	 	   this.resume = ko.observable(data.resume);
	 	  this.resume2 = ko.observable(data.resume.substr(0,50));
	 	   this.id_selectionne = ko.observable(index);
	 	  	var maDate = new Number(data.date);	 	  	
	 	 	maDate = new Date(maDate);
	 	 	console.log("madate"+maDate);
	 	 	this.date_consult= ko.observable($('#date_consultation').datebox('callFormat', '%d/%m/%Y', maDate));
	 	 	 	 		
	 	 	
		}
		function objet_rappel_creation(data,index) {
				this.motif_rappel=ko.observable(data.nom);
				this.valeur_rappel=ko.observable(data.valeur);
		}
		function objet_analyse(data,index) {
			this.nom=ko.observable(data.nom);
			this.unite=ko.observable(data.unite);
			this.base=ko.observable(data.base);
			this.id_selectionne2=ko.observable(index);
		}
		function objet_radio(data,index) {
			this.nom=ko.observable(data.nom);
			this.kv=ko.observable(data.kV);
			this.mas=ko.observable(data.mAS);
			this.sec=ko.observable(data.sec);
			this.id_selectionne3=ko.observable(index);
		}
		function objet_vetos(data,index) {
			this.nom=ko.observable(data['login']);
			this.id_selectionne4=ko.observable(data['id']);			
		}
		function ViewModel() {
		   var self = this;
		   var refreshTime = 1200000; // in milliseconds, so 20 minutes
		    window.setInterval( function() {
		    	//document.location.href="index.php";
		    	self.salle_attente("index")
		    }, refreshTime );

		    
		   self.tasks = ko.observableArray([]);
		    var soins = <?php echo $historique; ?>;
		    console.log("mon_array soins "+JSON.stringify(soins));
		     var objet_soin = $.map(soins, function(item,index) {
			   return new objet_soins_creation(item,index);               
           });
		   self.tasks(objet_soin);
		   self.resume = ko.observableArray([]);	
		   self.liste_histo_paiement=ko.observableArray([]);	   
		   var listeresume = <?php echo json_encode($liste_resume); ?>;
		   console.log(" ma liste "+listeresume);
		   var objet_resume = $.map(listeresume, function(item,index) {
               return new objet_liste_resume(item,index);              
           });
		   self.resume(objet_resume);
		   self.selectionne = ko.observableArray([]);
		   self.selectionne_para = ko.observable("");	   
		   self.article = ko.observableArray([]); 
		   self.article2 = ko.observableArray([]); 	
		   self.index_selectionne_historique = ko.observable();
		   self.affiche_resume2 = function(item, event) {
				var detail_consultation =''
			   $.each( soins, function( key, value ) {
					var maDate = new Number(value['date']);	 	  	
			 	 	maDate = new Date(maDate);
			 	 	detail_consultation+='Le '+$('#date_consultation').datebox('callFormat', '%d/%m/%Y', maDate)+' : '+value['motif']+' \n'+
			 	 						 value['resume']+'\nActes réalisés :\n';
			 	 			$.each( $.parseJSON(value['acte']), function( key, value_acte ) {
			 	 				detail_consultation+=' '+value_acte['nom'];
			 	 			});
			 	 			detail_consultation+= '\nMédicaments délivrés :\n ';
			 	 			$.each( $.parseJSON(value['medic']), function( key, value_medic ) {
			 	 				detail_consultation+=' '+value_medic['nom'];
					 	 			});
			 	 			detail_consultation+= '\n\n';					 	 							
				}
				);

			   $.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création de la feuille de résumé clinique...</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
		 		$.ajax({		    
		        	type: "POST",
		            url: "php/nouvelleconsultation.php?action=resume",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			texte : "Résumé des consultations de "+animal[0]['nom_a']+" "+animal[0]['espece']+" appartenant à "+client[0]['nom']+" "+client[0]['prenom'],
		 			detail_consultation : detail_consultation,
		 			animal_id : animal_id   
		            },	
		            success: function(data){
		            	$.mobile.loading('hide');		                        
		                  	window.open('aerogard/'+data);
		            	    $('#dossier').fileTree({
                    	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
                    	 	script: './js/connectors/jqueryFileTree.php'
                    	 	
                           }, function(file) { 
                           	//alert(file.substr(6));
                           	//var path = window.location.pathname;
                           	//window.location.href = path+file.substr(6);
                           	window.location.href = file.substr(6);

                           });
                   },
                   error: function(obj,text,error) {
	                       
                   	$.mobile.loading('hide');	
                   	           
                       alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                       if(obj.status=="400"){
                       document.location.href="index.php";
                       }
                   }	                           
		        });


		        

		   }
		   self.affiche_resume = function(item, event) {
			   $("#mon_resume").html('');
				 var $popUp_resume = $("#mon_resume").popup({
				        dismissible: false,
				        theme: "b",
				        overlyaTheme: "e",
				        transition: "pop"
				    }).on("popupafterclose", function () {
				        //remove the popup when closing
				        
				    }).css({
				    	'width': '500px',
    			       // 'height': '600px',
    			        'padding': '5px'
				    });
				//create a title for the popup
				  $("<h1/>", {
				        text: "Résumé des consultations de "+animal[0]['nom_a']+" "+animal[0]['espece']+" appartenant à "+client[0]['nom']+" "+client[0]['prenom']
				    }).appendTo($popUp_resume);

				  liste_consultation='<article>';
					$.each( soins, function( key, value ) {
						var maDate = new Number(value['date']);	 	  	
				 	 	maDate = new Date(maDate);
				 	 	liste_consultation+='<h2>Le '+$('#date_consultation').datebox('callFormat', '%d/%m/%Y', maDate)+' : '+value['motif']+'</h2><fieldset data-role="fieldcontain">'+
				 	 						 value['resume']+'</fieldset><fieldset data-role="fieldcontain">Actes réalisés :';
				 	 			$.each( $.parseJSON(value['acte']), function( key, value_acte ) {
				 	 				liste_consultation+='/ '+value_acte['nom'];
				 	 			});
				 	 			liste_consultation+= '</fieldset><fieldset data-role="fieldcontain">Médicaments délivrés : ';
				 	 			$.each( $.parseJSON(value['medic']), function( key, value_medic ) {
						 	 		liste_consultation+='/ '+value_medic['nom'];
						 	 			});
				 	 			liste_consultation+= '</fieldset>';					 	 							
					}
					);
					liste_consultation+='</article>';
		
				 var mon_destinataire = $(liste_consultation).appendTo( $popUp_resume );
				 				   			    			    
			    //create a back button
			    $("<a>", {
			        text: "Back",
			            "data-rel": "back"
			    }).buttonMarkup({
			        inline: true,
			        mini: true,
			        theme: "e",
			        icon: "back"
			    }).appendTo($popUp_resume);
			   
			    $popUp_resume.popup('open').trigger("create");
			    
		   };
		   self.recup_consultation = function(item, event) {
			   self.salle_attente("historique");
		   };	   
		   self.voir_resume_paiement = function(item, event) {
			   if(soins[$(event.target).attr('id')]['permission2']==<?php echo json_encode($_SESSION['login2']); ?>){
			   $("#bouton_recup_consultation").show();		
			   } 
			  
			   console.log("index selectionne "+$(event.target).attr('id'));
			   self.index_selectionne_historique(soins[$(event.target).attr('id')]['id']);
			   self.selectionne(soins[$(event.target).attr('id')]['resume'] );
			   self.selectionne_para("Poids :"+soins[$(event.target).attr('id')]['poids']+" Temp :"+soins[$(event.target).attr('id')]['temperature']+" Freq card :"+soins[$(event.target).attr('id')]['freq_cardiaque'] );	
			   self.liste_histo_paiement(soins[$(event.target).attr('id')]['paiement_historique']);
			   $("#resume").trigger('keyup');
			   self.total_a_paye("total ttc :"+soins[$(event.target).attr('id')]['totalttc']+"€");
			   self.total_paye("Réglé :"+soins[$(event.target).attr('id')]['reglementttc']+"€");	   
			  if($.parseJSON(soins[$(event.target).attr('id')]['acte']).length>0){
			   //var objet_article = $.map(soins[$(event.target).attr('id')]['detail'].split('/'), function(item,index) {
			   var objet_article = $.map($.parseJSON(soins[$(event.target).attr('id')]['acte']), function(item,index) {
				  
					//if(index+1!=$.parseJSON(soins[$(event.target).attr('id')]['acte']).length){
					console.log("acte selectionne2 "+item['nom']);
				 	  return new objet_article_creation(item,index);
					//}
			   });		
			   }else{
				   var objet_article = [];
			   }	   
			   self.article(objet_article);
			   if($.parseJSON(soins[$(event.target).attr('id')]['medic']).length>0){
				  
			   var objet_article2 = $.map($.parseJSON(soins[$(event.target).attr('id')]['medic']), function(item,index) {
					//if(index+1!=$.parseJSON(soins[$(event.target).attr('id')]['medic']).length){					
					  return new objet_article_creation(item,index);
				//}
			   });
			  // self.article().push(objet_article2);
			  
			   
			   }else{
				   var objet_article2 = [];
			   }	
			   self.article2(objet_article2);	   			
		   };
		   console.log("arrive 1");
		   self.ajoutbarreresume = function(item, event) {
			   var n=$("#barre_resume").val().lastIndexOf($(event.target).text());
			   
			   if(n==-1){
				   $("#barre_resume").val($("#barre_resume").val()+" "+$(event.target).text());
			   }else{
				   var m = $("#barre_resume").val().replace($(event.target).text(),"");
				   $("#barre_resume").val(m);
			   }
		   };
		   self.total_a_paye = ko.observableArray([]);
		   self.total_paye = ko.observableArray([]);    
		   var liste_motif_relance = <?php echo json_encode($liste_motif_relance); ?>;
			self.relance=ko.observableArray([]);
				var motif_lettre_rappel = $.map(liste_motif_relance, function(item,index) {
	               return new objet_rappel_creation(item,index);
	         	  });
				self.relance(motif_lettre_rappel);    
				self.analyse_defaut=ko.observable("");
				self.analyse = ko.observableArray([]);    
				var liste_analyse = <?php echo json_encode($liste_analyse); ?>; 
				var detail_analyse = $.map(liste_analyse, function(item,index) {
		               return new objet_analyse(item,index);
		         	  });
				self.analyse(detail_analyse); 
				 console.log("arrive 1_1");				   
				self.unite = ko.observable();
				self.mon_choix_analyse = ko.observable();
				self.ma_methode_analyse = ko.observable();
				self.selection_analyse = function(item, event) {
				console.log("index selectionne "+$(event.target).val());
				self.unite(liste_analyse[$(event.target).val()]['unite'] );	
				self.mon_choix_analyse(liste_analyse[$(event.target).val()]['nom'] );
				self.ma_methode_analyse(liste_analyse[$(event.target).val()]['methode'] );
				};	
				console.log("arrive 1_2");
				if (cas!='salle_attente' && cas!='rapport_recus' && cas!='rapport_emis' && cas!='envoi_refere') {
					self.analyses_ajoutees = ko.observableArray([]); 				
				 }else{
					self.analyses_ajoutees = ko.observableArray(JSON.parse(salle_attente_donnee[0]['analyse1'])); 
				 }
				self.ajout_analyse = function(){
					console.log(" ligne liste analyse choisie "+$("#choix_analyse").find(":selected").val());
					self.analyses_ajoutees.push({nom : $("#choix_analyse").find(":selected").text(), resultat : $("#choix_analyse2").val(), unite : $("#choix_analyse4").text(), methode : liste_analyse[$("#choix_analyse").find(":selected").val()]['methode'], ma_date : $("#choix_analyse3").val(), id_select :  self.analyses_ajoutees().length});
					console.log("nb d'enregistrement "+self.analyses_ajoutees().length);
					};
				self.supr_analyse =  function(){
					self.analyses_ajoutees.remove(this);
				};
				console.log("arrive 1_3");
				self.feuille_analyse =  function(){
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Chargement en cours...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					var jsonData = ko.toJS(self.analyses_ajoutees);
					console.log("mes analyses ajoutées "+ JSON.stringify(jsonData));
					$.ajax({
		            	type: "POST",
		                url: "php/nouvelleconsultation.php?action=analyse",
		                dataType: "json",
		                cache: false,
		                data:  {
		                    analyse: jsonData, commentaire: $("#commentaire").val(), animal : animal, client : client, animal_id : animal_id  
		                }		                           
		            })
		            .then( function ( response ) {
		            	$.mobile.loading('hide');
		            	 $('#dossier').fileTree({
	                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
	                     	 	script: './js/connectors/jqueryFileTree.php'
	                     	 	
	                            }, function(file) { 
	                            	//alert(file.substr(6));
	                            	
	                            	window.location.href = file.substr(6);

	                            });
		            		console.log("retour serveur "+response);
		            	
		            	
		            });
				};



				self.radio_defaut = ko.observable("");
				self.radio = ko.observableArray([]);    
				var liste_radio = <?php echo json_encode($liste_radio); ?>; 
				var detail_radio = $.map(liste_radio, function(item,index) {
		               return new objet_radio(item,index);
		         	  });
				self.radio(detail_radio); 				 		   
				self.kv = ko.observable();
				self.mon_choix_radio = ko.observable();
				self.mas = ko.observable();	
				self.sec = ko.observable();				
				self.selection_radio = function(item, event) {
				console.log("index selectionne "+$(event.target).val());
				self.kv(liste_radio[$(event.target).val()]['kV'] );	
				self.mon_choix_radio(liste_radio[$(event.target).val()]['nom'] );
				self.mas(liste_radio[$(event.target).val()]['mAS'] );
				self.sec(liste_radio[$(event.target).val()]['sec'] );
				};	
				self.vetos_defaut = ko.observable("");
				self.vetos = ko.observableArray([]);    
				var detail_vetos = $.map(liste_vetos, function(item,index) {
		               return new objet_vetos(item,index);
		         	  });
				self.vetos(detail_vetos); 
				var veto = <?php echo json_encode($veto); ?>;
				self.veto_preselec = ko.observable(veto);			
				console.log("veto preselec "+veto);
				 if (cas!='salle_attente' && cas!='rapport_recus' && cas!='rapport_emis' && cas!='envoi_refere') {
					self.radio_commentaire = ko.observable(); 				
				 }else{
					 self.radio_commentaire = ko.observable(salle_attente_donnee[0]['radio2']); 
				 }			
				 if(salle_attente_donnee==0){
					 console.log("valeur salle_attente_donnee "+salle_attente_donnee);	
					self.radios_ajoutees = ko.observableArray([]); 				
				 }else{
					self.radios_ajoutees = ko.observableArray(JSON.parse(salle_attente_donnee[0]['radio'])); 
				 }
				self.ajout_radio = function(){
					console.log(" ligne liste analyse choisie "+$("#choix_radio").find(":selected").val());
					self.radios_ajoutees.push({perso : $("#choix_radio6").find(":selected").text(), expo : $("#choix_radio2").val()+' kV-'+$("#choix_radio3").val()+' mAs-'+$("#choix_radio4").val(), zone : $("#choix_radio").find(":selected").text(), ma_date : $("#choix_radio5").val(), id_select :  self.analyses_ajoutees().length});
					console.log("nb d'enregistrement "+self.radios_ajoutees().length);
					};
				self.supr_radio =  function(){
					self.radios_ajoutees.remove(this);
				};					
				self.feuille_radio =  function(){
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Chargement en cours...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					var jsonData = ko.toJSON(self.radios_ajoutees);
					console.log("mes radios ajoutées "+ JSON.stringify(jsonData));
					$.ajax({
		            	type: "POST",
		                url: "php/nouvelleconsultation.php?action=radio",
		                dataType: "json",
		                cache: false,
		                data:  {
		                    radio: jsonData, commentaire2: $("#result_radio").val(), animal : animal, client : client, animal_id : animal_id  
		                }		                           
		            })
		            .then( function ( response ) {
		            	$.mobile.loading('hide');

		            	 $('#dossier').fileTree({
	                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
	                     	 	script: './js/connectors/jqueryFileTree.php'
	                     	 	
	                            }, function(file) { 
	                            	//alert(file.substr(6));
	                            	//var path = window.location.pathname;
	                            	//window.location.href = path+file.substr(6);
	                            	window.location.href = file.substr(6);

	                            });
                         
		            		console.log("retour serveur "+response);
		            	
		            	
		            });
				};




				
				console.log("arrive 2");
				self.liste_tarif = ko.observableArray([]);
				   var liste_des_tarif = <?php echo $tarif; ?>;
				   console.log(" ma liste "+liste_des_tarif);
				   var objet_tarif = $.map(liste_des_tarif, function(item,index) {
		               return new objet_liste_tarif(item,index);
		           });
				   self.liste_tarif(objet_tarif);				
				if(salle_attente_donnee!=0){
					self.acte_ajoutes = ko.observableArray(JSON.parse(salle_attente_donnee[0]['acte'])); 
				 }else{
				self.acte_ajoutes = ko.observableArray([]); 
				 }
				self.ajout_liste_acte = function(item, event) {
					 console.log(" nombre d acte deja choisi "+findnombre(ko.toJS(self.acte_ajoutes), $(event.target).text(), $(event.target).attr('value'), 0, $("#date_acte").val()));
					if( findnombre(ko.toJS(self.acte_ajoutes), $(event.target).text(), $(event.target).attr('value'), 0, $("#date_acte").val()) == -1 ){
						
							//var objet_acte =  function(){
							//	 return new objet_acte_creation($(event.target).text(), $(event.target).attr('value'), $("#date_acte").val(), self.acte_ajoutes().length, 1);
							//};

				            //self.acte_ajoutes.push(new objet_acte());

				            self.acte_ajoutes.push(new objet_acte($(event.target).text(), $(event.target).attr('value'), $("#date_acte").val(), self.acte_ajoutes().length, 1, 0));
				           
			        
						}else{
						var i = findnombre(ko.toJS(self.acte_ajoutes), $(event.target).text(), $(event.target).attr('value'), 0, $("#date_acte").val());
						//console.log(" nombre d acte deja choisi "+i+' '+ko.toJS(self.acte_ajoutes)[i]['quantite']);
						//var objet_acte =  function(){
						//	 return new objet_acte_creation($(event.target).text(), $(event.target).attr('value'), $("#date_acte").val(), self.acte_ajoutes().length, (ko.toJS(self.acte_ajoutes)[i]['quantite']+1));
						//};
						//self.acte_ajoutes.replace(self.acte_ajoutes()[i], new objet_acte());

						self.acte_ajoutes.replace(self.acte_ajoutes()[i], new objet_acte($(event.target).text(), $(event.target).attr('value'), $("#date_acte").val(), self.acte_ajoutes().length, (ko.toJS(self.acte_ajoutes)[i]['quantite']+1), 0));
					
					}					
				 };
				 self.ajout_acte = function() {
						if(findnombre(ko.toJS(self.acte_ajoutes),$( "#designation_acte" ).prev().find("input[data-type='search']").val(),$('#prix_acte').val(),$('#remise_acte').val(),$("#date_acte").val())==-1){

						
				            self.acte_ajoutes.push(new objet_acte($( "#designation_acte" ).prev().find("input[data-type='search']").val(), $('#prix_acte').val(), $("#date_acte").val(), self.acte_ajoutes().length, 1, $('#remise_acte').val()));

						}else{
							var i = findnombre(self.acte_ajoutes(), $( "#designation_acte" ).prev().find("input[data-type='search']").val(), $('#prix_acte').val(), $('#remise_acte').val(), $("#date_acte").val());
							
							self.acte_ajoutes.replace(self.acte_ajoutes()[i], new objet_acte($( "#designation_acte" ).prev().find("input[data-type='search']").val(), $('#prix_acte').val(), $("#date_acte").val(), self.acte_ajoutes().length, (ko.toJS(self.acte_ajoutes)[i]['quantite']+1), $('#remise_acte').val()));

						}					
					 };
				 self.supr_acte =  function(){
					 self.acte_ajoutes.remove(this);
					 self.repartition_ajoutees([]);
				};
				 self.supr_medic =  function(){
					 self.medic_ajoutes.remove(this);
				};
				if(cas == 'nouvelle_consult'){
					self.medic_ajoutes = ko.observableArray([]); 

				}else{
					if(salle_attente_donnee[0]['medic']!=''){
						self.medic_ajoutes = ko.observableArray(JSON.parse(salle_attente_donnee[0]['medic'])); 
				 	}else{
						self.medic_ajoutes = ko.observableArray([]); 
					 }
				}
				self.modif_lot = function() {
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Modification du numéro de lot...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
			 		$.ajax({				 				    
			        	type: "POST",
			        	url: "php/nouvelleconsultation.php?action=modif_lot",
			        	dataType: "json",
			            cache: false,
			            data:  {
			 			nom : $( "#designation_medic" ).prev().find("input[data-type='search']").val(),
			 			lot : $('#lot_medic').val(),  
			            },	
			            success: function(data){			            									        			
							     	$.mobile.loading('hide');					        								        			   
	                    },
	                    error: function(obj,text,error) {
		                       
	                    	$.mobile.loading('hide');	
	                    	           
	                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                        if(obj.status=="400"){
	                        document.location.href="index.php";
	                        }
	                    }                           
			        });
				};
				self.ajout_medic = function() {
					if(findnombre(ko.toJS(self.medic_ajoutes),$( "#designation_medic" ).prev().find("input[data-type='search']").val(),$('#prix_medic').val(),$('#remise_medic').val(),$("#date_medic").val())==-1){

					
			            self.medic_ajoutes.push(new objet_medic($( "#designation_medic" ).prev().find("input[data-type='search']").val(), $('#lot_medic').val(), $('#prix_medic').val(), $("#date_medic").val(), self.medic_ajoutes().length, 1, $('#remise_medic').val()));

					}else{
						var i = findnombre(self.medic_ajoutes(), $( "#designation_medic" ).prev().find("input[data-type='search']").val(), $('#prix_medic').val(), $('#remise_medic').val(), $("#date_medic").val());
						
						self.medic_ajoutes.replace(self.medic_ajoutes()[i], new objet_medic($( "#designation_medic" ).prev().find("input[data-type='search']").val(),$('#lot_medic').val(), $('#prix_medic').val(), $("#date_medic").val(), self.medic_ajoutes().length, (ko.toJS(self.medic_ajoutes)[i]['quantite']+1), $('#remise_medic').val()));

					}					
				 };
				 self.reste_du = ko.observable(0);
				 self.reste_du_detail = ko.observableArray([]);
				 var objet_reste_du = $.map(reste_du, function(item,index) {
					   console.log("reste du avant"+self.reste_du());
		               self.reste_du(self.reste_du()+(item['totalttc']-item['reglementttc']));
		               console.log("reste du apres"+self.reste_du());
		               return new objet_reste_du_detail(item,index); 		                          
		          });
		          self.reste_du_detail(objet_reste_du);
		          self.reste_du = ko.observable("Reste dû : "+String(self.reste_du())+" euros");					
		          console.log("arrive 3");
				 self.total_consultation = ko.computed(function(){
				        var total = 0;
				        ko.utils.arrayForEach(self.acte_ajoutes(), function(item) {
				            total += parseFloat(ko.utils.unwrapObservable(item.prix_total));
				        });
				        ko.utils.arrayForEach(self.medic_ajoutes(), function(item) {
				            total += parseFloat(ko.utils.unwrapObservable(item.prix_total));
				        });
				        return total.toFixed(2);
				    });
				 console.log("arrive 3_1 ");
				 self.supr_paiement =  function(){
					 self.paiement_ajoutes.remove(this);
				};
				if(salle_attente_donnee!=0){
					self.paiement_ajoutes = ko.observableArray(JSON.parse(salle_attente_donnee[0]['paiement'])); 
				 }else{
					self.paiement_ajoutes = ko.observableArray([]); 
				 }
				self.ajout_paiement = function() {
										
					self.paiement_ajoutes.push(new objet_paiement($('#choix_mode_paiement2').val(), $('#montant_paiement').val(), $("#date_paiement").val(), self.paiement_ajoutes().length,$('#num_cheque').val()));

										
				 };
				 console.log("arrive 3_2");
				 if(salle_attente_donnee!=0){
				 self.relance_ajoutes = ko.observableArray(JSON.parse(salle_attente_donnee[0]['relance'])); 
				 }else{
				 self.relance_ajoutes = ko.observableArray([]); 
				 }
				 
				 self.ajout_relance = function() {
					 console.log($('#choix_lettre_rappel2_1').val());
					 console.log("choix2 "+$('#choix_lettre_rappel2 li:eq('+parseInt($('#choix_lettre_rappel2_1').val())+') a').attr('value'));
						if($("#choix_lettre_rappel3").val()=='' && $('#choix_lettre_rappel2_1').val()!='' && $('#choix_lettre_rappel2_1').val()!=5 && $("#choix_lettre_rappel4_1").val()!=''){ 
						self.relance_ajoutes.push(new objet_relance(Date.today().addMonths(parseInt($('#choix_lettre_rappel2 li:eq('+parseInt($('#choix_lettre_rappel2_1').val())+') a').attr('value'))).toString("d/M/yyyy"),$('#choix_lettre_rappel4 li:eq('+parseInt($("#choix_lettre_rappel4_1").val())+') a').text(), self.relance_ajoutes().length));
						}else if($("#choix_lettre_rappel3").val()!='' && $("#choix_lettre_rappel4_1").val()!=''){							
						self.relance_ajoutes.push(new objet_relance($("#choix_lettre_rappel3").val(), $('#choix_lettre_rappel4 li:eq('+parseInt($("#choix_lettre_rappel4_1").val())+') a').text(), self.relance_ajoutes().length));							
						}	
						$("#choix_lettre_rappel3").val('');
						$('#choix_lettre_rappel2_1').val('');		
						$("#choix_lettre_rappel4_1").val('');
						$('#choix_lettre_rappel').val('non').slider('refresh');	
						$(".animation_rappel").hide();	
						$(".animation_rappel2").hide();								
				 };
				 self.supr_relance =  function(){
					 self.relance_ajoutes.remove(this);
				};
				self.total_acte = ko.computed(function(){
					var total = 0;
					ko.utils.arrayForEach(self.acte_ajoutes(), function(item) {
			            total += parseFloat(ko.utils.unwrapObservable(item.prix_total));
			        });
					return total;	
				 });
				self.total_medic = ko.computed(function(){
					var total = 0;
					 ko.utils.arrayForEach(self.medic_ajoutes(), function(item) {
				            total += parseFloat(ko.utils.unwrapObservable(item.prix_total));
				        });
					return total;	
				 });
				   self.total_a_regler = ko.computed(function(){
					   var total = 0;
				        ko.utils.arrayForEach(self.acte_ajoutes(), function(item) {
				            total += parseFloat(ko.utils.unwrapObservable(item.prix_total));
				        });
				        ko.utils.arrayForEach(self.medic_ajoutes(), function(item) {
				            total += parseFloat(ko.utils.unwrapObservable(item.prix_total));
				        });
				        if(cas!='historique'){
				        	$.map(reste_du, function(item,index) {							   
				    	    total+=item['totalttc']-item['reglementttc'];
				        	});
				        }
				        ko.utils.arrayForEach(self.paiement_ajoutes(), function(item) {
				            total -= parseFloat(ko.utils.unwrapObservable(item.montant));
				        });
					   return total.toFixed(2);				       
				   });
				   self.liste_veto = ko.observableArray(liste_vetos); 
				   console.log("repartition "+JSON.stringify(veto_repartition));	
				   if(veto_repartition==0){
				   self.repartition_ajoutees = ko.observableArray([]);
				   }else{
				   self.repartition_ajoutees = ko.observableArray(veto_repartition);
				   } 
				   self.valeur_a_repartir = ko.observable(0);
				   self.liste_des_veto = ko.observable("");
				   self.ajout_repartition = function(){
					   self.repartition_ajoutees.push({veto_desti : $("#choix_veto").find(":selected").text(), montant : $("#montant_veto").val(), id_select : self.repartition_ajoutees().length});
					   self.valeur_a_repartir(0);
					   $("#montant_veto").slider("refresh");
					    }
				    self.supr_repartition =  function(){
						 self.repartition_ajoutees.remove(this);
				   };				  
				   self.total_a_repartir = ko.computed(function(){
					   var total = self.total_acte();
					  // total += self.total_medic();
					   ko.utils.arrayForEach(self.repartition_ajoutees(), function(item) {
				            total -= parseFloat(ko.utils.unwrapObservable(item.montant));
				        });
						return total.toFixed(2);
				   });
				   console.log("arrive 4");
				   self.devis = function(){
					   var jsonData1 = ko.toJSON(self.acte_ajoutes);
		                alert("Could now send this to server: " + JSON.stringify(jsonData1));
		                var jsonData2 = ko.toJSON(self.medic_ajoutes);
		                alert("Could now send this to server: " + JSON.stringify(jsonData2));
		                
						 		
				 		$.mobile.loading( 'show', {
							textonly : "true",
						    textVisible : "true",
						    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du devis...</h2></span>",
							iconpos : "right",
						    theme: "a"
						             	 
						});
				 		$.ajax({		    
				        	type: "POST",
				            url: "php/nouvelleconsultation.php?action=dem_devis",
				            dataType: "json",
				            cache: false,
				            data:  {
				 			animal : animal, client : client, animal_id : animal_id, resume : $('#barre_resume').val(), clinique : $('#clinique').val(), acte : jsonData1, medic : jsonData2   
				            },	
				            success: function(data){
				            	$.mobile.loading('hide');		                        
		                        console.log("retour serveur "+data);
		                        window.open('./sauvegarde/animaux/'+animal_id+'/devis_'+data+'.pdf');
		                        $('#dossier').fileTree({
		                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
		                     	 	script: './js/connectors/jqueryFileTree.php'
		                     	 	
		                            }, function(file) { 
		                            	//alert(file.substr(6));
		                            	//var path = window.location.pathname;
		                            	//window.location.href = path+file.substr(6);
		                            	window.location.href = file.substr(6);

		                            });
	                            
		                    },
		                    error: function(obj,text,error) {
			                       
		                    	$.mobile.loading('hide');	
		                    	           
		                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
		                        if(obj.status=="400"){
		                        document.location.href="index.php";
		                        }
		                    }	                           
				        });
				        				 	
				 		
				 	};
				 	self.incineration = function(){				              
							 		
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du registre incinération individuelle...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({		    
					        	type: "POST",
					            url: "php/nouvelleconsultation.php?action=incineration&inci=indi",
					            dataType: "json",
					            cache: false,
					            data:  {
					 			animal : animal, client : client, animal_id : animal_id, poids : $('#poids').val()   
					            },	
					            success: function(data){
					            	$.mobile.loading('hide');		                        
			                        console.log("retour serveur "+data);
			                        window.open('./sauvegarde/animaux/'+animal_id+'/incineration_individuelle_'+data+'.pdf');
			                        $('#dossier').fileTree({
			                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
			                     	 	script: './js/connectors/jqueryFileTree.php'
			                     	 	
			                            }, function(file) { 
			                            	//alert(file.substr(6));
			                            	//var path = window.location.pathname;
			                            	//window.location.href = path+file.substr(6);
			                            	window.location.href = file.substr(6);

			                            });

		                            
			                    },
			                    error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }	                           
					        });
					        				 	
					 		
					 	};

					 	self.incineration2 = function(){				              
					 		
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du registre incinération...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({		    
					        	type: "POST",
					            url: "php/nouvelleconsultation.php?action=incineration&inci=norm",
					            dataType: "json",
					            cache: false,
					            data:  {
					 			animal : animal, client : client, animal_id : animal_id, poids : $('#poids').val()   
					            },	
					            success: function(data){
					            	$.mobile.loading('hide');		                        
			                        console.log("retour serveur "+data);
			                        window.open('./sauvegarde/animaux/'+animal_id+'/incineration_normale_'+data+'.pdf');			                        
			                        $('#dossier').fileTree({
			                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
			                     	 	script: './js/connectors/jqueryFileTree.php'
			                     	 	
			                            }, function(file) { 
			                            	//alert(file.substr(6));
			                            	//var path = window.location.pathname;
			                            	//window.location.href = path+file.substr(6);
			                            	window.location.href = file.substr(6);

			                            });
			                    },
			                    error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }	                           
					        });
					        				 	
					 		
					 	};
						self.eutha = function(){	          
					 		
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du certificat euthanasie...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({		    
					        	type: "POST",
					            url: "php/nouvelleconsultation.php?action=eutha",
					            dataType: "json",
					            cache: false,
					            data:  {
					 			animal : animal, client : client, animal_id : animal_id, poids : $('#poids').val() 
					            },	
					            success: function(data){
					            	$.mobile.loading('hide');		                        
			                        console.log("retour serveur "+data);
			                        window.open('./sauvegarde/animaux/'+animal_id+'/euthanasie_'+data+'.pdf');			                        
			                        $('#dossier').fileTree({
			                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
			                     	 	script: './js/connectors/jqueryFileTree.php'
			                     	 	
			                            }, function(file) { 
			                            	//alert(file.substr(6));
			                            	//var path = window.location.pathname;
			                            	//window.location.href = path+file.substr(6);
			                            	window.location.href = file.substr(6);

			                            });
			                    },
			                    error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }	                           
					        });
					        				 	
					 		
					 	};
							self.autre_certif = function(){
								$("#popup-2").html('');
								 var $popUp3 = $("#popup-2").popup({
								        dismissible: false,
								        theme: "b",
								        overlyaTheme: "e",
								        transition: "pop"
								    }).on("popupafterclose", function () {
								        //remove the popup when closing
								        
								    }).css({
								        
								    });
								//create a title for the popup
								  $("<h4/>", {
								        text: "Rédiger un certificat :"
								    }).appendTo($popUp3);				
										
									 var mon_message = $('<form method="post" action="somepage"><textarea name="content" style="width:100%">'+entete+'</textarea></form>').appendTo( $popUp3 );
									
							   			    			    
								    //create a back button
								    $("<a>", {
								        text: "Back",
								            "data-rel": "back"
								    }).buttonMarkup({
								        inline: false,
								        mini: true,
								        theme: "e",
								        icon: "back"
								    }).appendTo($popUp3);
								   
								    $popUp3.popup('open').trigger("create");

								    tinyMCE.init({
								        // General options
								        mode : "textareas",
								        theme : "advanced",
								        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

								        // Theme options
								        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
								        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
								        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
								        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
								        theme_advanced_toolbar_location : "top",
								        theme_advanced_toolbar_align : "left",
								        theme_advanced_statusbar_location : "bottom",
								        theme_advanced_resizing : true,

								        save_onsavecallback: function() {console.log("Save");

										        $.mobile.loading( 'show', {
													textonly : "true",
												    textVisible : "true",
												    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Sauvegarde du certificat...</h2></span>",
													iconpos : "right",
												    theme: "a"
												             	 
												});
										 		$.ajax({		    
										        	type: "POST",
										            url: "php/nouvelleconsultation.php?action=autre_certif",
										            dataType: "json",
										            cache: false,
										            data:  {
										 			animal_id : animal_id, message : tinyMCE.get('content').getContent()
										            },	
										            success: function(data){
										            	$.mobile.loading('hide');	
										            	window.open('./sauvegarde/animaux/'+animal_id+'/certificat_'+data+'.pdf');
										            	
										            	 $('#dossier').fileTree({
									                     	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
									                     	 	script: './js/connectors/jqueryFileTree.php'
									                     	 	
									                            }, function(file) { 
									                            	//alert(file.substr(6));
									                            	//var path = window.location.pathname;
									                            	//window.location.href = path+file.substr(6);
									                            	window.location.href = file.substr(6);

									                            });                       
								                        console.log("retour serveur "+data);
								                    },
								                    error: function(obj,text,error) {
									                       
								                    	$.mobile.loading('hide');	
								                    	           
								                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
								                        if(obj.status=="400"){
								                        document.location.href="index.php";
								                        }
								                    }	                           
										        });

								        },// fin save_onsavecallback

								        // Example content CSS (should be your site CSS)
								        content_css : "css/example.css",

								        // Drop lists for link/image/media/template dialogs
								        template_external_list_url : "js/template_list.js",
								        external_link_list_url : "js/link_list.js",
								        external_image_list_url : "js/image_list.js",
								        media_external_list_url : "js/media_list.js",

								        // Replace values for the template plugin
								        template_replace_values : {
								                username : "Some User",
								                staffid : "991234"
								        }
									});						 		
					        				 	
					 		
					 	};
					 	
					 	
					if(salle_attente_donnee!=0){
						if(salle_attente_donnee[0]['puce']!=''){
						self.ma_puce = ko.observable(salle_attente_donnee[0]['puce']); 
						}else{
						self.ma_puce = ko.observable();
						}
						if(salle_attente_donnee[0]['tatouage']!=''){
						self.mon_tatouage = ko.observable(salle_attente_donnee[0]['tatouage']); 
						}else{
						self.mon_tatouage = ko.observable();
						}
					 }else{
						 console.log("mon num de puce "+animal[0]['num_p']);
						 if(animal[0]['num_t']!=''){
						 self.ma_puce = ko.observable(animal[0]['num_t']); 
						 }else{
						 self.ma_puce = ko.observable(); 
						 }
						 if(animal[0]['num_p']!=''){
						 self.mon_tatouage = ko.observable(animal[0]['num_p']);
						 }else{
						 self.mon_tatouage = ko.observable(); 
						 }
					 }
				 	self.ajout_identification = function(categorie){
					 	var mon_choix;
					 	if(categorie=="puce"){
				 		var jsonData1 = ko.toJSON(self.ma_puce);
						 console.log("acte: " + JSON.stringify(jsonData1));
						 mon_choix = 'num_p';										 
					 	}else if(categorie=="tatouage"){
		                var jsonData1 = ko.toJSON(self.mon_tatouage);
					    console.log("medic: " + JSON.stringify(jsonData1));
					    mon_choix = 'num_t';
					 	}
					 	
					 	
				 		$.mobile.loading( 'show', {
							textonly : "true",
						    textVisible : "true",
						    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement de l'identification...</h2></span>",
							iconpos : "right",
						    theme: "a"
						             	 
						});
				 		$.ajax({		    
				        	type: "POST",
				            url: "php/nouvelleconsultation.php?action=identification",
				            dataType: "json",
				            cache: false,
				            data:  {
				 			animal_id : animal_id, choix : mon_choix, numero : jsonData1   
				            },	
				            success: function(data){
				            	$.mobile.loading('hide');			                       
				            	self.salle_attente("consultation");	
				            },
				            error: function(obj,text,error) {
				     				                       
				    			$.mobile.loading('hide');	
				    				                    	           
				    			alert("erreur "+obj.status+" "+error+" "+obj.responseText);
				    			if(obj.status=="400"){
				    			document.location.href="index.php";
				    			}
				    		 }	                           
				        });



				 	}
				 	self.salle_attente = function(suite){
				 		 	if(suite=="index" || suite=="animal" || suite=="proprio"){
				 			autorisation_quitter = false;
				 			self.salle_attente2(suite);	
	                        }
	                       /* else if(origin =='_salle_attente' && suite=="animal"){
	                          	$("#popup-2").html('');
	                        	 var $popUp = $("#popup-2").popup({
			        			        dismissible: false,
			        			        theme: "b",
			        			        overlyaTheme: "e",
			        			        transition: "pop"
			        			    }).on("popupafterclose", function () {
			        			        //remove the popup when closing
			        			       // $(this).remove();
			        			    }).css({
			        			        'width': '370px',
			        			            'height': '400px',
			        			            'padding': '5px'
			        			    });
			        			    //create a title for the popup
			        			    $("<h4/>", {
			        			        text: "Les données en salle d'attente vont être perdues. Voulez-vous continuer ?"
			        			    }).appendTo($popUp);
			        			  //Create a submit button(fake)
			        			    $("<a>", {
			        			        text : "oui"
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "check"
			        			    }).bind("click", function() {
			        			    	$popUp.popup("close");
			        			    	autorisation_quitter = false;
			        			    	self.salle_attente2(suite);							            
			        			    }).appendTo($popUp);
			        			    //create a back button
			        			    $("<a>", {
			        			        text : "non"
			        			       
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "back"
			        			    }).bind("click", function() {
			        			        $popUp.popup("close");			        			       
			        			    }).appendTo($popUp);
			        			    		        			   
			        			    $popUp.popup('open').trigger("create");		                        

		                    }else if(origin =='_salle_attente' && suite=="proprio"){
		                    	$("#popup-2").html('');
		                    	 var $popUp = $("#popup-2").popup({
			        			        dismissible: false,
			        			        theme: "b",
			        			        overlyaTheme: "e",
			        			        transition: "pop"
			        			    }).on("popupafterclose", function () {
			        			        //remove the popup when closing
			        			      //  $(this).remove();
			        			    }).css({
			        			        'width': '370px',
			        			            'height': '400px',
			        			            'padding': '5px'
			        			    });
			        			    //create a title for the popup
			        			    $("<h4/>", {
			        			        text: "Les données en salle d'attente vont être perdues. Voulez-vous continuer ?"
			        			    }).appendTo($popUp);
			        			  //Create a submit button(fake)
			        			    $("<a>", {
			        			        text : "oui"
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "check"
			        			    }).bind("click", function() {
			        			    	$popUp.popup("close");
			        			    	autorisation_quitter = false;
			        			    	self.salle_attente2(suite);							            
			        			    }).appendTo($popUp);
			        			    //create a back button
			        			    $("<a>", {
			        			        text : "non"
			        			       
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "back"
			        			    }).bind("click", function() {
			        			        $popUp.popup("close");			        			       
			        			    }).appendTo($popUp);
			        			    		        			   
			        			    $popUp.popup('open').trigger("create");		
	                        }
	                        */
	                        else if(origin =='_salle_attente' && suite=="consultation"){
	                        	$("#popup-2").html('');
	                        	 var $popUp = $("#popup-2").popup({
			        			        dismissible: false,
			        			        theme: "b",
			        			        overlyaTheme: "e",
			        			        transition: "pop"
			        			    }).on("popupafterclose", function () {
			        			        //remove the popup when closing
			        			      //  $(this).remove();
			        			    }).css({
			        			        'width': '370px',
			        			            'height': '400px',
			        			            'padding': '5px'
			        			    });
			        			    //create a title for the popup
			        			    $("<h4/>", {
			        			        text: "Les données en salle d'attente vont être perdues. Voulez-vous continuer ?"
			        			    }).appendTo($popUp);
			        			  //Create a submit button(fake)
			        			    $("<a>", {
			        			        text : "oui"
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "check"
			        			    }).bind("click", function() {
			        			    	$popUp.popup("close");
			        			    	autorisation_quitter = false;
			        			    	self.salle_attente2(suite);							            
			        			    }).appendTo($popUp);
			        			    //create a back button
			        			    $("<a>", {
			        			        text : "non"
			        			       
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "back"
			        			    }).bind("click", function() {
			        			        $popUp.popup("close");			        			       
			        			    }).appendTo($popUp);
			        			    		        			   
			        			    $popUp.popup('open').trigger("create");		
			        			    
	                        }else if(origin =='_salle_attente' &&  suite=="historique"){
	                        	$("#popup-2").html('');
	                        	 var $popUp = $("#popup-2").popup({
			        			        dismissible: false,
			        			        theme: "b",
			        			        overlyaTheme: "e",
			        			        transition: "pop"
			        			    }).on("popupafterclose", function () {
			        			        //remove the popup when closing
			        			      //  $(this).remove();
			        			    }).css({
			        			        'width': '370px',
			        			            'height': '400px',
			        			            'padding': '5px'
			        			    });
			        			    //create a title for the popup
			        			    $("<h4/>", {
			        			        text: "Les données en salle d'attente vont être perdues. Voulez-vous continuer ?"
			        			    }).appendTo($popUp);
			        			  //Create a submit button(fake)
			        			    $("<a>", {
			        			        text : "oui"
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "check"
			        			    }).bind("click", function() {
			        			    	$popUp.popup("close");
			        			    	autorisation_quitter = false;
			        			    	self.salle_attente2(suite);							            
			        			    }).appendTo($popUp);
			        			    //create a back button
			        			    $("<a>", {
			        			        text : "non"
			        			       
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "back"
			        			    }).bind("click", function() {
			        			        $popUp.popup("close");			        			       
			        			    }).appendTo($popUp);
			        			    		        			   
			        			    $popUp.popup('open').trigger("create");		
	                        }else{

	                        	self.salle_attente2(suite);	
	                        }
				 	}


				 self.salle_attente2 = function(suite){       
							var jsonData1 = ko.toJSON(self.acte_ajoutes);
							 console.log("acte: " + JSON.stringify(jsonData1));
			                var jsonData2 = ko.toJSON(self.medic_ajoutes);
						    console.log("medic: " + JSON.stringify(jsonData2));
			                var jsonData3 = ko.toJSON(self.analyses_ajoutees);
			                console.log("analyse: " + JSON.stringify(jsonData3));
			                var jsonData4 = ko.toJSON(self.paiement_ajoutes);
			                console.log("analyse: " + JSON.stringify(jsonData4));
			                var jsonData5 = ko.toJSON(self.relance_ajoutes);
			                console.log("analyse: " + JSON.stringify(jsonData5));
			                var jsonData6 = ko.toJSON(self.radios_ajoutees);
			                console.log("radio: " + JSON.stringify(jsonData6));
			                var formulaire = $('#formnouvelleconsultation').serializeFormJSON();
			                console.log("formulaire: " + JSON.stringify(formulaire));
							
							 		
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Mise en salle d'attente...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({		    
					        	type: "POST",
					            url: "php/nouvelleconsultation.php?action=salle_attente",
					            dataType: "json",
					            cache: false,
					            data:  {
					 			animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6   
					            },	
					            success: function(data){
					            	$.mobile.loading('hide');			                       
			                        console.log("retour serveur "+data);
			                        if(suite=="index"){
			                        document.location.href="index.php";
			                        }else if(suite=="animal"){
				                        if(origin =='_salle_attente'){
				                        	document.location.href="index.php?idpro2="+client[0]['id2']+"&idani="+animal_id+"&retour=consultation&id_salle_attente="+data+"&valeur_attente=regular";				                        
				                        }else{
			                      			document.location.href="index.php?idpro2="+client[0]['id2']+"&idani="+animal_id+"&retour=consultation&id_salle_attente="+data+"&valeur_attente=0";
				                        }
			                        }else if(suite=="proprio"){
			                        	if(origin =='_salle_attente'){
			                        		document.location.href="index.php?idpro="+client[0]['id2']+"&idani2="+animal_id+"&retour=consultation&id_salle_attente="+data+"&valeur_attente=regular";
			                        	}else{
			                       			 document.location.href="index.php?idpro="+client[0]['id2']+"&idani2="+animal_id+"&retour=consultation&id_salle_attente="+data+"&valeur_attente=0";
			                        	}
			                        }else if(suite=="consultation"){
			                        document.location.href="index.php?id_salle_attente="+data;
			                        }else if(suite=="historique"){
			                        document.location.href="index.php?id_consultation="+self.index_selectionne_historique();
			                        }
			                    },
			                    error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }                           
					        });
					        				 	
					 		
					 	};
					 	self.attacher = function(){
					 								
							 		
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement vigilance</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({	
			 				    
					        	type: "POST",
					        	url: "php/nouvelleconsultation.php?action=mur",
					        	dataType: "json",
					            cache: false,
					            data:  {
       			    				animal : animal[0]['nom_a'], client : client[0]['nom'], importance : $("#importance_vigilance").val()  
					            },	
					            success: function(data2){
					            	 $.mobile.loading('hide');			                       
				                      console.log("retour serveur "+data2);


				                      var $popUp4 = $("#popup-4").popup({
				        			        dismissible: false,
				        			        theme: "b",
				        			        overlyaTheme: "e",
				        			        transition: "pop"
				        			    }).on("popupafterclose", function () {
				        			        //remove the popup when closing
				        			        $(this).remove();
				        			    }).css({
				        			        'width': '300px',
				        			        'height': '200px',
				        			        'padding': '5px'
				        			    });
				        			    //create a title for the popup
				        			    $("<h4/>", {
				        			        text: "Cet animal a été ajouté à la zone de vigilance "
				        			    }).appendTo($popUp4);
				        			  //Create a submit button(fake)
				        			    $("<a>", {
				        			        text : "ok"
				        			    }).buttonMarkup({
				        			        inline : true,
				        			        icon : "check"
				        			    }).bind("click", function() {
				        			    	$popUp4.popup("close");		        			    	
										            
				        			    }).appendTo($popUp4);
			        			    		        			   
				        			    $popUp4.popup('open').trigger("create");				        			    
						            
					            },
					            error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }	                           
					        });

					 	};
					 	self.valider_specialiste = function(data){
							var jsonData1 = ko.toJSON(self.acte_ajoutes);
							 console.log("acte: " + JSON.stringify(jsonData1));
			                var jsonData2 = ko.toJSON(self.medic_ajoutes);
						    console.log("medic: " + JSON.stringify(jsonData2));
			                var jsonData3 = ko.toJSON(self.analyses_ajoutees);
			                console.log("analyse: " + JSON.stringify(jsonData3));
			                var jsonData4 = ko.toJSON(self.paiement_ajoutes);
			                console.log("analyse: " + JSON.stringify(jsonData4));
			                var jsonData5 = ko.toJSON(self.relance_ajoutes);
			                console.log("analyse: " + JSON.stringify(jsonData5));
			                var jsonData6 = ko.toJSON(self.radios_ajoutees);
			                console.log("radio: " + JSON.stringify(jsonData6));
			                var formulaire = $('#formnouvelleconsultation').serializeFormJSON();
			                console.log("formulaire: " + JSON.stringify(formulaire));
			                console.log("retour specialiste "+$("#choix_specialiste").val());
							 		
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement du référé...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({	
			 				    
					        	type: "POST",
					        	url: "php/nouvelleconsultation.php?action=refere",
					        	dataType: "json",
					            cache: false,
					            data:  {
        			    		animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, consultation : data, choix_specialiste : $("#choix_specialiste").val()  
					            },	
					            success: function(data2){
					            	 $.mobile.loading('hide');			                       
				                      console.log("retour serveur "+data2);


				                      var $popUp3 = $("#popup-3").popup({
				        			        dismissible: false,
				        			        theme: "b",
				        			        overlyaTheme: "e",
				        			        transition: "pop"
				        			    }).on("popupafterclose", function () {
				        			        //remove the popup when closing
				        			        $(this).remove();
				        			    }).css({
				        			        'width': '370px',
				        			        'height': '400px',
				        			        'padding': '5px'
				        			    });
				        			    //create a title for the popup
				        			    $("<h4/>", {
				        			        text: "Cet animal a été référé au vétérinaire "+$("#choix_specialiste").val()
				        			    }).appendTo($popUp3);
				        			  //Create a submit button(fake)
				        			    $("<a>", {
				        			        text : "ok"
				        			    }).buttonMarkup({
				        			        inline : true,
				        			        icon : "check"
				        			    }).bind("click", function() {
				        			    	$popUp3.popup("close");
				        			    	 document.location.href="index.php";		        			    	
										            
				        			    }).appendTo($popUp3);
			        			    		        			   
				        			    $popUp3.popup('open').trigger("create");				        			    
						            
					            },
					            error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }	                           
					        });
					        				 	
					 		
					 	};



					 	
						self.valider = function(){		
							autorisation_quitter = false;				
							$(".section_bouton").hide();
							var jsonData1 = ko.toJSON(self.acte_ajoutes);
							 console.log("acte: " + JSON.stringify(jsonData1));
			                var jsonData2 = ko.toJSON(self.medic_ajoutes);
						    console.log("medic: " + JSON.stringify(jsonData2));
			                var jsonData3 = ko.toJSON(self.analyses_ajoutees);
			                console.log("analyse: " + JSON.stringify(jsonData3));
			                var jsonData4 = ko.toJSON(self.paiement_ajoutes);
			                console.log("paiement: " + JSON.stringify(jsonData4));
			                var jsonData5 = ko.toJSON(self.relance_ajoutes);
			                console.log("analyse: " + JSON.stringify(jsonData5));
			                var jsonData6 = ko.toJSON(self.radios_ajoutees);
			                console.log("radio: " + JSON.stringify(jsonData6));
			                var jsonData7 = ko.toJSON(self.repartition_ajoutees);
			                console.log("repartition: " + JSON.stringify(jsonData7));
			                var formulaire = $('#formnouvelleconsultation').serializeFormJSON();
			                console.log("formulaire: " + JSON.stringify(formulaire));		                
							
			                
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement en cours...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		if(cas == "historique"){
					        	var consult = <?php echo $consult; ?>;
					        	$.ajax({	
				 				    
						        	type: "POST",
						        	url: "php/nouvelleconsultation.php?action=validation&valeur=2&consult="+consult,
						        	dataType: "json",
						            cache: false,
						            data:  {
						 			animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, repartition :jsonData7   
						            },	
						            success: function(data){
						            	$.mobile.loading('hide');
						            	 if(self.total_consultation() > 0 ){							                
					            			window.open('./sauvegarde/animaux/'+animal_id+'/facture_'+data+'.pdf');
						           		 }	
						        		if(client[0]['ref']!=0){	
						        					$("#popup-2").html('');					        			
							        				 var $popUp = $("#popup-2").popup({
							        			        dismissible: false,
							        			        theme: "b",
							        			        overlyaTheme: "e",
							        			        transition: "pop"
							        			    }).on("popupafterclose", function () {
							        			        //remove the popup when closing
							        			       // $(this).remove();
							        			    }).css({
							        			        'width': '370px',
							        			            'height': '400px',
							        			            'padding': '5px'
							        			    });
							        			    //create a title for the popup
							        			  if (window.google) {
							        			    $("<h4/>", {
							        			    	text: "Cet animal est suivi habituellement par : "+ma_liste[0].nom+" à "+ma_liste[0].commune+" tel :"+ma_liste[0].tel+". Voulez-vous envoyer un compte-rendu de consultation ?"
							        			    }).appendTo($popUp);
							        			  }else{
							        			    $("<h4/>", {
							        			    	text: "Cet animal est suivi habituellement par : "+ma_liste[0].nom+" à "+ma_liste[0].commune+" tel :"+ma_liste[0].tel+". Vous ne pouvez pas envoyer de compte-rendu car vous êtes déconnectés d'internet."							        			    	
							        			    	
									        		 }).appendTo($popUp);
							        			  }
							        			  //Create a submit button(fake)
							        			 if (window.google) { 
							        			    $("<a>", {
							        			        text : "Envoyer Rapport"
							        			    }).buttonMarkup({
							        			        inline : true,
							        			        icon : "check"
							        			    }).bind("click", function() {
							        			    	$popUp.popup("close");
							        			    	$.mobile.loading( 'show', {
															textonly : "true",
														    textVisible : "true",
														    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Mise à disposition du rapport...</h2></span>",
															iconpos : "right",
														    theme: "a"
														             	 
														});
							        			    	$.ajax({	
										 				    
												        	type: "POST",
												        	url: "php/nouvelleconsultation.php?action=rapport_ref",
												        	dataType: "json",
												            cache: false,
												            data:  {
							        			    		animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, consultation_id : data, veto_ref : ma_liste[0].login, veto_ref_mail : ma_liste[0].mail  
												            },	
												            success: function(data2){
												            	 $.mobile.loading('hide');			                       
											                      console.log("retour serveur "+data2);
											                      if($("#specialiste").val()=='oui'){
											                    	  self.valider_specialiste(data);
											                      }
											                      document.location.href="index.php";
													            
												            },
												            error: function(obj,text,error) {
											                       
										                    	$.mobile.loading('hide');	
										                    	           
										                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
										                        if(obj.status=="400"){
										                        document.location.href="index.php";
										                        }
										                    }	                           
												        });


													            
							        			    }).appendTo($popUp);
							        			 }
							        			    //create a back button
							        			    $("<a>", {							        			    	
							        			        text : window.google ? "Ne Pas Envoyer":"ok"							        			    							        			       
							        			    }).buttonMarkup({
							        			        inline : true,
							        			        icon : "back"
							        			    }).bind("click", function() {
							        			        $popUp.popup("close");
							        			        $.mobile.loading('hide');			                       
								                        console.log("retour serveur "+data);
								                        if($("#specialiste").val()=='oui'){
									                    	  self.valider_specialiste(data);
									                      }
								                        document.location.href="index.php";
							        			    }).appendTo($popUp);
							        			    		        			   
							        			    $popUp.popup('open').trigger("create");
						        			   
						        	     	
						        		}else{						            
						            	$.mobile.loading('hide');			                       
				                        console.log("retour serveur "+data);
				                        if($("#specialiste").val()=='oui'){
					                    	  self.valider_specialiste(data);
					                      }
				                        document.location.href="index.php";
						        		}
				                    },
				                    error: function(obj,text,error) {
					                       
				                    	$.mobile.loading('hide');	
				                    	           
				                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
				                        if(obj.status=="400"){
				                        document.location.href="index.php";
				                        }
				                    }	                           
						        });

				 			}else if(cas=='rapport_recus' || cas=='rapport_emis' || cas=='envoi_refere'){
				 				$.mobile.loading('hide');
				 				

				 				$("#popup-2").html('');
				 				 var $popUp = $("#popup-2").popup({
			        			        dismissible: false,
			        			        theme: "b",
			        			        overlyaTheme: "e",
			        			        transition: "pop"
			        			    }).on("popupafterclose", function () {
			        			        //remove the popup when closing
			        			        //$(this).remove();
			        			    	//$(this).popup("destroy");
			        			    }).css({
			        			        'width': '370px',
			        			            'height': '400px',
			        			            'padding': '5px'
			        			    });
			        			    //create a title for the popup
			        			    $("<h4/>", {
			        			        text: "Vous souhaitez enregistrer cette consultation en archive dans votre base de données."
			        			    }).appendTo($popUp);
			        			  //Create a submit button(fake)
			        			    $("<a>", {
			        			        text : "Nouveau dossier client et animal"
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "check"
			        			    }).bind("click", function() {
			        			    	$popUp.popup("close");
			        			    	$.mobile.loading( 'show', {
											textonly : "true",
										    textVisible : "true",
										    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement dans votre base Etape 1 sur 2...</h2></span>",
											iconpos : "right",
										    theme: "a"
										             	 
										});
										
			        			    	$.ajax({	
						 				    
								        	type: "POST",
								        	url: "php/nouvelleconsultation.php?action=drop_client_animal",
								        	dataType: "json",
								            cache: false,
								            data:  {
			        			    		client : client, animal_id : animal_id    
								            },	
								            success: function(data2){
								            	 $.mobile.loading('hide');			                       
							                      console.log("retour serveur "+data2); 							                       
							                      $.mobile.loading( 'show', {
														textonly : "true",
													    textVisible : "true",
													    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement dans votre base Etape 2 sur 2...</h2></span>",
														iconpos : "right",
													    theme: "a"
													             	 
													});
							                      $.ajax({				 				    
											        	type: "POST",
											        	url: "php/nouvelleconsultation.php?action=validation&valeur=3",
											        	dataType: "json",
											            cache: false,
											            data:  {
											 			animal : data2[1], client : data2[0], animal_id : data2[1][0]['id'], acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, repartition :jsonData7   
											            },	
											            success: function(data3){												

											            	$.mobile.loading('hide');			                       
									                        console.log("retour serveur "+data3);
									                        document.location.href="index.php";
											        		
									                    },
									                    error: function(obj,text,error) {
										                       
									                    	$.mobile.loading('hide');	
									                    	           
									                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
									                        if(obj.status=="400"){
									                        document.location.href="index.php";
									                        }
									                    }	                           
											        });									 											            
								            },
								            error: function(obj,text,error) {
							                       
						                    	$.mobile.loading('hide');	
						                    	           
						                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
						                        if(obj.status=="400"){
						                        document.location.href="index.php";
						                        }
						                    }	                           
								        });
									            
			        			    }).appendTo($popUp);

			        			    //create a back button
			        			    $("<a>", {
			        			        text : "Animal déjà dans la base",
				        			    id : "ajout_animal"
			        			       
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "gear"
			        			    }).bind("click", function() {
				        			    
			        			    	$("<label/>", {
					        			        text: "Numéro de cet animal."						        			    
					        			    }).appendTo($("#conteneur_num_animal"));
				        			    $("<input>", {
					        			        id : "num_animal"				        			       
					        			    }).textinput({
					        			    	theme: "b"				        			        
					        			    }).css({
					        			    	'width': '200px'
					        			    }).appendTo($("#conteneur_num_animal"));
				        			    $("<a>", {
				        			        text : "Enregistrer"				        			       
				        			    }).buttonMarkup({
				        			        inline : true,
				        			        icon : "back"
				        			    }).bind("click", function() {				        			    			                       
						                     						                       
						                      $.mobile.loading( 'show', {
													textonly : "true",
												    textVisible : "true",
												    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement dans votre base...</h2></span>",
													iconpos : "right",
												    theme: "a"
												             	 
												});
						                      $.ajax({				 				    
										        	type: "POST",
										        	url: "php/nouvelleconsultation.php?action=validation&valeur=3",
										        	dataType: "json",
										            cache: false,
										            data:  {
										 			animal : animal, client : client, animal_id : $("#num_animal").val(), acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, repartition :jsonData7   
										            },	
										            success: function(data3){												

										            	$.mobile.loading('hide');			                       
								                        console.log("retour serveur "+data3);
								                        document.location.href="index.php";
										        		
								                    },
								                    error: function(obj,text,error) {
									                       
								                    	$.mobile.loading('hide');	
								                    	           
								                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
								                        if(obj.status=="400"){
								                        document.location.href="index.php";
								                        }
								                    }                           
										        });			
										        
					                        console.log("retour serveur "+$("#num_animal").val());
					                       
				        			    }).appendTo($("#conteneur_num_animal"));	        			    	
				        			    
			        			       	
			        			    }).appendTo($popUp);
			        			    $("<div/>", {
			        			        text: "",
			        			       	id : "conteneur_num_animal"
			        			    }).appendTo($popUp);


			        			    $("<a>", {
			        			        text : "Accueil"
			        			       
			        			    }).buttonMarkup({
			        			        inline : true,
			        			        icon : "back"
			        			    }).bind("click", function() {
			        			        $popUp.popup("close");             
				                        document.location.href="index.php";
			        			    }).appendTo($popUp);		        			    
			        			    		        			   
			        			    $popUp.popup('open').trigger("create");		        			    


				 			}else{
				 				$.ajax({				 				    
						        	type: "POST",
						        	url: "php/nouvelleconsultation.php?action=validation&valeur=1",
						        	dataType: "json",
						            cache: false,
						            data:  {
						 			animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, repartition :jsonData7   
						            },	
						            success: function(data){
							            if(self.total_consultation() > 0 ){								                
						            			window.open('./sauvegarde/animaux/'+animal_id+'/facture_'+data+'.pdf');
							            }
										if(client[0]['ref']!=0){	
																										        			
										     	$.mobile.loading('hide');
										     	$("#popup-2").html('');	
							        			 var $popUp = $("#popup-2").popup({
							        			        dismissible: false,
							        			        theme: "b",
							        			        overlyaTheme: "e",
							        			        transition: "pop"
							        			    }).on("popupafterclose", function () {
							        			        //remove the popup when closing
							        			        //$(this).remove();
							        			    }).css({
							        			        'width': '370px',
							        			            'height': '400px',
							        			            'padding': '5px'
							        			    });
							        			    //create a title for the popup
							        			    if (window.google) {
							        			    $("<h4/>", {							        			    	
							        			        text: "Cet animal est suivi habituellement par : "+ma_liste[0].nom+" à "+ma_liste[0].commune+" tel :"+ma_liste[0].tel+". Voulez-vous envoyer un compte-rendu de consultation ?"
							        		  			   }).appendTo($popUp);
							        			    }else{
							        			    $("<h4/>", {
							        			    	text: "Cet animal est suivi habituellement par : "+ma_liste[0].nom+" à "+ma_liste[0].commune+" tel :"+ma_liste[0].tel+". Vous ne pouvez pas envoyer un compte-rendu car vous êtes déconnectés d'internet."							        			     	
								        			   }).appendTo($popUp);
							        			    }
							        			  //Create a submit button(fake)
							        			  if (window.google) {
							        			    $("<a>", {
							        			        text : "Envoyer Rapport"
							        			    }).buttonMarkup({
							        			        inline : true,
							        			        icon : "check"
							        			    }).bind("click", function() {
							        			    	$popUp.popup("close");
							        			    	$.mobile.loading( 'show', {
															textonly : "true",
														    textVisible : "true",
														    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Mise à disposition du rapport...</h2></span>",
															iconpos : "right",
														    theme: "a"
														             	 
														});
														console.log("numero consult "+data);
							        			    	$.ajax({	
										 				    
												        	type: "POST",
												        	url: "php/nouvelleconsultation.php?action=rapport_ref",
												        	dataType: "json",
												            cache: false,
												            data:  {
							        			    		animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio : jsonData6, consultation_id : data, veto_ref : ma_liste[0].login, veto_ref_mail : ma_liste[0].mail     
												            },	
												            success: function(data2){
												            	 $.mobile.loading('hide');			                       
											                      console.log("retour serveur "+data2);
											                      if($("#specialiste").val()=='oui'){
											                    	  self.valider_specialiste(data);
											                      }
											                      document.location.href="index.php";
													            
												            },
												            error: function(obj,text,error) {
											                       
										                    	$.mobile.loading('hide');	
										                    	           
										                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
										                        if(obj.status=="400"){
										                        document.location.href="index.php";
										                        }
										                    }	                           
												        });


													            
							        			    }).appendTo($popUp);
							        			  }
							        			    //create a back button
							        			    $("<a>", {
							        			    	 text : window.google ? "Ne Pas Envoyer":"ok"							        			       
							        			    }).buttonMarkup({
							        			        inline : true,
							        			        icon : "back"
							        			    }).bind("click", function() {
							        			        $popUp.popup("close");
							        			        $.mobile.loading('hide');			                       
								                        console.log("retour serveur "+data);
								                        if($("#specialiste").val()=='oui'){
									                    	  self.valider_specialiste(data);
									                      }
								                        document.location.href="index.php";
							        			    }).appendTo($popUp);
							        			    		        			   
							        			    $popUp.popup('open').trigger("create");
						        			   
						        	     	
						        		}else{

						            	$.mobile.loading('hide');			                       
				                       console.log("retour serveur "+JSON.stringify(data));
				                        if($("#specialiste").val()=='oui'){
					                    	  self.valider_specialiste(data);
					                      }
				                      //  document.location.href="index.php?idpro3="+client[0]['id2']+"&idani="+animal_id;
				                        document.location.href="index.php";
						        		}
				                    },
				                    error: function(obj,text,error) {
				                       
				                    	$.mobile.loading('hide');	
				                    	           
				                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
				                        if(obj.status=="400"){
				                        document.location.href="index.php";
				                        }
				                    }	                           
						        });

				 			}
					 		
					        				 	
					 		
					 	};// end valider
					 	/*=========== modification secondaire ==========*/
					 	self.valider2 = function(){
					 		$(".section_bouton").hide();
							var jsonData1 = ko.toJSON(self.acte_ajoutes);
							 console.log("acte: " + JSON.stringify(jsonData1));
			                var jsonData2 = ko.toJSON(self.medic_ajoutes);
						    console.log("medic: " + JSON.stringify(jsonData2));
			                var jsonData3 = ko.toJSON(self.analyses_ajoutees);
			                console.log("analyse: " + JSON.stringify(jsonData3));
			                var jsonData4 = ko.toJSON(self.paiement_ajoutes);
			                console.log("paiement: " + JSON.stringify(jsonData4));
			                var jsonData5 = ko.toJSON(self.relance_ajoutes);
			                console.log("analyse: " + JSON.stringify(jsonData5));
			                var jsonData6 = ko.toJSON(self.radios_ajoutees);
			                console.log("radio: " + JSON.stringify(jsonData6));
			                var jsonData7 = ko.toJSON(self.repartition_ajoutees);
			                console.log("repartition: " + JSON.stringify(jsonData7));
			                var formulaire = $('#formnouvelleconsultation').serializeFormJSON();
			                console.log("formulaire: " + JSON.stringify(formulaire));					
									
					 		$.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement secondaire en cours...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		var consult = <?php echo $consult; ?>;
					 		$.ajax({				 				    
					        	type: "POST",
					        	url: "php/nouvelleconsultation.php?action=validation&valeur=2&consult="+consult,
					        	dataType: "json",
					            cache: false,
					            data:  {
					 			animal : animal, client : client, animal_id : animal_id, acte : jsonData1, medic : jsonData2, analyse : jsonData3, formulaire : formulaire, paiement : jsonData4, relance :jsonData5, radio :jsonData6, repartition :jsonData7, origine : salle_attente_donnee[0]['veto_origin']   
					            },	
					            success: function(data){
					            									        			
									     	$.mobile.loading('hide');	
						        			document.location.href="index.php";						        			   
			                    },
			                    error: function(obj,text,error) {
				                       
			                    	$.mobile.loading('hide');	
			                    	           
			                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
			                        if(obj.status=="400"){
			                        document.location.href="index.php";
			                        }
			                    }                           
					        });

			 			}//end validation2
					 	self.document_plus = function(){
							
							window.open('../radio/'+$("#selection_fichier").val().replace(/^C:\\fakepath\\/i, ''));
							mon_texte = $("#clinique").val() +String('\n')+ String('http://78.246.224.132/radio/'+$("#selection_fichier").val().replace(/^C:\\fakepath\\/i, ''));
							$("#clinique").val(mon_texte).keyup();

				 			}	

						self.document_plus2 = function(){
						
							window.open('../laboratoire/'+$("#selection_fichier2").val().replace(/^C:\\fakepath\\/i, ''));
							mon_texte = $("#clinique").val() +String('\n')+ String('http://78.246.224.132/laboratoire/'+$("#selection_fichier2").val().replace(/^C:\\fakepath\\/i, ''));
							$("#clinique").val(mon_texte).keyup();

				 			}			 		
					 	
				 	
		          
		};
	ko.applyBindings(new ViewModel());
	console.log("retour requete "+JSON.stringify(choix_retour));



	console.log("arrive 6");
	$("#selection_fichier").on("change", function(){
		console.log($(this).val());
		
	});
	$("#retour").on("vclick", function(){
		document.location.href="index.php";
		
	});
	// module ordonnance
	$("#ordonnance").on("vclick", function(){
		$('#mon_ordonnance').ordonnance({
			choix_medic:choix_medic,
			liste_cat_delivre:liste_cat_delivre,
			info_veto:info_veto,
			veto:<?php echo json_encode($_SESSION['login2']);?>,
			ma_date:Date.today(),
			mon_id:animal_id,
			animal:'Pour le '+animal[0]['espece']+' '+animal[0]['nom_a'].toUpperCase()+' de '+client[0]['nom']+' '+client[0]['prenom']+' '+client[0]['adresse']+' '+client[0]['code']+' '+client[0]['ville'],
			mes_horaires:"Consultations d'urgence du lundi au vendredi de 19h à 8h. Le samedi et le dimanche ouverture toute la journée et la nuit.",
			mes_competences:"MEDECINE - CHIRURGIE - RADIOLOGIE - ECHOGRAPHIE - ANALYSES - HOSPITALISATION",
			ordo_complete: function(e, ui){ 				 
   	    			  window.open('aerogard/'+ui.valeur);   	    	 
   	    			  $('#dossier').fileTree({
                	 	root: '../../sauvegarde/animaux/'+ui.id_animal+'/',
                	 	script: './js/connectors/jqueryFileTree.php'
                	 	
                       }, function(file) { 
                       	//alert(file.substr(6));			                            	
                       	window.location.href = file.substr(6);
                       });

			}
		});
		
	});// end ordonnance
	$( "#designation_medic" ).on( "listviewbeforefilter", function ( e, data ) {
    	
 	     var $ul = $( this ),
         $input = $( data.input ),
         value = $input.val(),
         html = "";
     	 $ul.html( "" );
        if ( value && value.length > 3 ) {
         $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
         $ul.listview( "refresh" );
         $.ajax({

         	type: "GET",
             url: "php/nouvelleconsultation.php?action=medic",
             dataType: "json",
             cache: false,
             data:  {
                 recherche: $input.val()  
             }
                        
         })
         .then( function ( response ) {
        	 console.log(response);
         	  		 $.each( response, function ( i, val ) {                     
                     html += '<li ><a class='+((val['permission']=="tous") ? "situation_pb" : "situation_normale")+' id="listemedic-' + (i) + '"  data-number="'+ i +'" data-number2="'+ val['id'] +'" data-number3="'+ val['centrale'] +'" data-number4="'+ val['cip'] +'" data-number5="'+ val['prixht'] +'" data-number6="'+ val['lot'] +'">' + val['nom'] + '</a></li>';
                     
                      });
                 $ul.html( html );
                 $ul.listview( "refresh" );
                 $ul.trigger( "updatelayout");
                 $('[id^=listemedic]').on("click", function(){
                  	
                	 console.log("id selectionné actuel = " +$(this).attr('id'));  
                     console.log("medic selectionné = " +$(this).html());
                     console.log("medicament selectionné = " +$(this).attr('data-number5'));  
                   
                 	$('#lot_medic').val( $(this).attr('data-number6') );
                 	var prix_medic_ht = (Number($(this).attr('data-number5')) + (Number($(this).attr('data-number5'))*marge_medic/100));
                 	var prix_medic_ttc = prix_medic_ht + (prix_medic_ht*tva/100);
                 	$('#prix_medic').val( prix_medic_ttc.toFixed(2) );
                 	$( "#designation_medic" ).prev().find("input[data-type='search']").val($(this).html());
                 	$ul.html( "" );
                 	$ul.listview( "refresh" );
                    $ul.trigger( "updatelayout");                	
                	});
                
             });
        
        }
	});        	

	$( "#designation_acte" ).prev().find("input[data-type='search']").on("click", function (){
		$(this).val("");
	
	});
	$( "#designation_acte" ).on( "listviewbeforefilter", function ( e, data ) {
    	
	     var $ul = $( this ),
        $input = $( data.input ),
        value = $input.val(),
        html = "";
    	 $ul.html( "" );
       if ( value && value.length > 2 ) {
        $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
        $ul.listview( "refresh" );
        $.ajax({

        	type: "GET",
            url: "php/nouvelleconsultation.php?action=acte",
            dataType: "json",
            cache: false,
            data:  {
                recherche: $input.val()  
            }
                       
        })
        .then( function ( response ) {
       	 console.log(response);
        	  		 $.each( response, function ( i, val ) {                     
                    html += '<li ><a id="listeacte-' + (i) + '"  data-number="'+ i +'" data-number2="'+ val['id'] +'" data-number5="'+ val['tarifttc'] +'">' + val['acte'] + '</a></li>';
                    
                     });
                $ul.html( html );
                $ul.listview( "refresh" );
                $ul.trigger( "updatelayout");
                $('[id^=listeacte]').on("click", function(){
                 	
                   	$('#prix_acte').val( $(this).attr('data-number5') );
                	$( "#designation_acte" ).prev().find("input[data-type='search']").val($(this).html());
                	$ul.html( "" );
                	$ul.listview( "refresh" );
                   $ul.trigger( "updatelayout");                	
               	});
               
            });
       
       }
	});        	


	
	
	var objet_acte =  function(a,b,c,d,e,f){
		 return new objet_acte_creation(a, b, c, d, e, f);
	};
	var objet_medic =  function(a,b,c,d,e,f,g){
		 return new objet_medic_creation(a, b, c, d, e, f, g);
	};

	// create a style switch button
	var switcher = $('<a href="javascript:void(0)" class="btn">Change appearance</a>').clicktoggle(
		function(){
			$("#tags ul").hide().addClass("alt").fadeIn("fast");
		},
		function(){
			$("#tags ul").hide().removeClass("alt").fadeIn("fast");
		}
	);
 	$('#tags').append(switcher);
 	$("#passeport").on("change", function(event, ui){
		if(this.value=="non"){
			$(".animation_passeport").hide();
		}else{
			$(".animation_passeport").show();
						
		}
	});
	$("#rage").on("change", function(event, ui){
		if(this.value=="non"){
			$(".animation_rage").hide();
		}else{
			$(".animation_rage").show();
			$(".animation_rage2").hide();
			$('#rage2 li').attr("data-theme", "c").removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
			
		}
	});
	$("#rage2 li").on("click", function(){
		$('#rage2 li').removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
 	    $(this).attr("data-theme", "b").removeClass("ui-btn-up-c").removeClass('ui-btn-hover-c').addClass("ui-btn-up-b").addClass('ui-btn-hover-b');
 	   if( $(this).attr('data-choix')=="rappel" ){
			$(".animation_rage2").show();
			}else{
			$(".animation_rage2").hide();
			}
 	  $('#rage2_1').val($(this).attr('data-choix'));
	});
 	$( "#choix_lettre_rappel" ).on( "change", function(event, ui) { 		
 		  if(this.value=="non"){
				$(".animation_rappel").hide();							
 		  }else{ 	 		  
 				 $(".animation_rappel").show();		
 				 $(".animation_rappel2").hide();	
 				 $('#choix_lettre_rappel2 li').attr("data-theme", "c").removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
 				$('#choix_lettre_rappel4 li').attr("data-theme", "c").removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
  				
 		  }
 		});
 	$('#choix_lettre_rappel2 li').on('click', function () {
 	    $('#choix_lettre_rappel2 li').attr("data-theme", "c").removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
 	    $(this).attr("data-theme", "b").removeClass("ui-btn-up-c").removeClass('ui-btn-hover-c').addClass("ui-btn-up-b").addClass('ui-btn-hover-b');
		if( $(this).attr('data-choix')=="autre" ){
			$(".animation_rappel2").show();
			}else{
			$(".animation_rappel2").hide();
			}
		$('#choix_lettre_rappel2_1').val($(this).index());
 	 	});
 	$('#choix_lettre_rappel4 li').on('click', function () {
 	    $('#choix_lettre_rappel4 li').attr("data-theme", "c").removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
 	    $(this).attr("data-theme", "b").removeClass("ui-btn-up-c").removeClass('ui-btn-hover-c').addClass("ui-btn-up-b").addClass('ui-btn-hover-b');

 	   $('#choix_lettre_rappel4_1').val($(this).index());
  	   	});
 	$('#certif_sante').on('click', function () {
 		$.mobile.loading( 'show', {
			textonly : "true",
		    textVisible : "true",
		    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Chargement en cours...</h2></span>",
			iconpos : "right",
		    theme: "a"
		             	 
		});
 		$.ajax({
        	type: "POST",
            url: "php/nouvelleconsultation.php?action=certif_sante",
            dataType: "json",
            cache: false,
            data:  {
                animal : animal, client : client, animal_id : animal_id  
            }		                           
        })
        .then( function ( response ) {
        	$.mobile.loading('hide');
        	
        		console.log("retour serveur "+response);
        		window.open('./sauvegarde/animaux/'+animal_id+'/certif_sante_'+response+'.pdf');
                $('#dossier').fileTree({
             	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
             	 	script: './js/connectors/jqueryFileTree.php'
             	 	
                    }, function(file) { 
                    	//alert(file.substr(6));
                    	//var path = window.location.pathname;
                    	//window.location.href = path+file.substr(6);
                    	window.location.href = file.substr(6);

                    });
        	
        	
        });	
 		
 	}); 	
 	
 	$('#certif_sani').on('click', function () {
 		$.mobile.loading( 'show', {
			textonly : "true",
		    textVisible : "true",
		    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Chargement du certificat...</h2></span>",
			iconpos : "right",
		    theme: "a"
		             	 
		});
 		$.ajax({
        	type: "POST",
            url: "php/nouvelleconsultation.php?action=certif_sani",
            dataType: "json",
            cache: false,
            data:  {
                animal : animal, client : client, animal_id : animal_id  
            }		                           
        })
        .then( function ( response ) {
        	$.mobile.loading('hide');
        	
        		console.log("retour serveur "+response);
        		window.open('./sauvegarde/animaux/'+animal_id+'/certif_sanitaire_'+response+'.pdf');
                $('#dossier').fileTree({
             	 	root: '../../sauvegarde/animaux/'+animal_id+'/',
             	 	script: './js/connectors/jqueryFileTree.php'
             	 	
                    }, function(file) { 
                    	//alert(file.substr(6));
                    	//var path = window.location.pathname;
                    	//window.location.href = path+file.substr(6);
                    	window.location.href = file.substr(6);

                    });
        	
        });	
 		
 	}); 
 	
 	$('#total_consult').keydown(function(e){
 	    e.preventDefault();
 	});
 	$('#choix_mode_paiement li').on('click', function () {
 	    $('#choix_mode_paiement li').attr("data-theme", "c").removeClass("ui-btn-up-b").removeClass('ui-btn-hover-b').addClass("ui-btn-up-c").addClass('ui-btn-hover-c');
 	    $(this).attr("data-theme", "b").removeClass("ui-btn-up-c").removeClass('ui-btn-hover-c').addClass("ui-btn-up-b").addClass('ui-btn-hover-b');
 	   console.log("paiement choisi "+ $(this).find("a").text());
  	    $('#choix_mode_paiement2').val($(this).find("a").text());
		$(".animation_paiement").show();
		console.log("choix paiement "+$(this).index());
			if($(this).index() == 2){
			$('#numero_cheque').show();
			}else{
			$('#num_cheque').val('');
			$('#numero_cheque').hide();
			}			
 	 	});	 	
 	
 	
	
		$.fn.serializeFormJSON = function() {
			   var o = {};
			   var a = this.serializeArray();
			   $.each(a, function() {
				   if (o[this.name]) {
					   if (!o[this.name].push) {
						   o[this.name] = [o[this.name]];
					   }
					   o[this.name].push(this.value || '');
				   } else {
					   o[this.name] = this.value || '';
				   }
			   });
			   return o;
			};
	});
function is_int(value){
	  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){ 
	      alert ('value est un integer');
	      return true;
	  } else {
	      alert ("value n'est pas un integer");
	      return false;
	  }
	}
function findnombre (mon_array, mon_nom, mon_prix, ma_remise, ma_date) {
	var mon_nombre = -1;
	console.log("ma variable "+mon_array);
    for (var i=0;i<mon_array.length;i++) {
        var item = mon_array[i];
        console.log("ma variable2 "+item['nom']+' '+item['prix_unitaire']+' '+item['remise']);
        if(item['nom']==mon_nom && item['prix_unitaire']==mon_prix && item['remise']==ma_remise && item['ma_date']==ma_date){
        	mon_nombre = i;
        }
    }
    return mon_nombre;
}
function findItem (term, liste) {
    var items = [];
    for (var i=0;i<liste.length;i++) {
        var item = liste[i];
        
            var detail = item['login'].toString().toLowerCase();
            var detail2 = term.toString().toLowerCase();
            if (detail.indexOf(detail2)>-1) {
                items.push(item);
                break;               
            }
       
    }
    return items;
}
function openFile(file) {
    // do something with file
	 window.location = file;
}
</script>
<section id="mapage" class="nouveauclient cf">
<?php 
   
$client2 = json_decode($client, true);
$animal2 = json_decode($animal, true);
$salle_attente_donnee2 = json_decode($salle_attente_donnee, true);
if(isset($salle_attente_donnee2[0]['veto_origin'])){
	?>
	<legend>Consultation réalisée par <?php echo $salle_attente_donnee2[0]['veto_origin']?> le <?php echo $salle_attente_donnee2[0]['formatted_date']?> concernant : <b><?php echo $animal2[0]['nom_a']." ".$animal2[0]['espece']."</b> numéro id :".$id_ani." appartenant à ".$client2[0]['nom']." ".$client2[0]['prenom']." id propriétaire :".$id_pro; ?></legend>
	<?php 
}else{
	?>
	<legend>Nouvelle consultation pour : <b><?php echo $animal2[0]['nom_a']." ".$animal2[0]['espece']."</b> numéro id :".$id_ani." appartenant à ".$client2[0]['nom']." ".$client2[0]['prenom']." id propriétaire :".$id_pro; ?></legend>
	<?php 
	
}
?>
	<div id="referent"></div>
<div id="popup-1" data-role="popup"></div>
<div id="popup-2" data-role="popup"></div>
<div id="urgence"></div>
	<fieldset class="ui-grid-b section_bouton">
        <div class="ui-block-a">
        <a name="fiche_animal" id="fiche_animal" <?php echo( ($cas=='historique' || $cas=='rapport_recus' || $cas=='rapport_emis' || $cas=='envoi_refere') ? 'class="ui-disabled"' : '' ); ?> data-role="button" data-bind='click: function() { salle_attente("animal")}'>Ouvrir fiche Animal <?php echo $animal2[0]['nom_a'];?></a>  
        </div>
        <div class="ui-block-b">
        <a name="fiche_proprio" id="fiche_proprio" <?php echo( ($cas=='historique' || $cas=='rapport_recus' || $cas=='rapport_emis' || $cas=='envoi_refere') ? 'class="ui-disabled"' : '' ); ?> data-role="button" data-bind='click: function() { salle_attente("proprio")}'>Ouvrir fiche Proprio <?php echo $client2[0]['nom'];?></a>          
        </div>
        <div class="ui-block-c">
        <a name="ouvrir_agenda" target="_blank" id="ouvrir_agenda" href="index.php?agenda=<?php echo $id_ani; ?>" data-role="button" rel="external">Prendre un rendez-vous</a>
        </div>
    </fieldset>  
       
<form id="formnouvelleconsultation">	
<ul data-role="listview" data-count-theme="c" data-inset="true">
  <li>
  <div data-role="collapsible" id="commemo">
            <h2>Commémoratifs :</h2>
	<fieldset class="ui-grid-a">
        <div class="ui-block-a">
       		 <div id="commemo_resume" data-iscroll>
        		 <ul id="listesoins" data-bind="foreach: tasks" data-role="listview" data-filter="true" data-inset="true"> 
  					   <li><a data-bind="click: $parent.voir_resume_paiement, attr: {id: id_selectionne}">
   							<p data-bind="text: motif, attr: {id: id_selectionne}"><strong></strong></p>
   							<p data-bind="html: resume2, attr: {id: id_selectionne}"></p>
       						<p class="ui-li-aside" data-bind="text: date_consult, attr: {id: id_selectionne}"></p>
   						</a>
    				</li> 
				</ul> 
			 </div>  
			 <div>
			 	<a id="bouton_affiche_resume" data-role="button" data-bind="click: affiche_resume" data-icon="info" data-theme="c" >Afficher un résumé de tout le dossier</a>
			 	<a id="bouton_affiche_resume2" data-role="button" data-bind="click: affiche_resume2" data-icon="grid" data-theme="c" >Créer une feuille de résumé de dossier</a>
			 </div>             
        </div>
        <div class="ui-block-b">
        	<div class="paragraphe">
      		  <span name="resume" id="resume" data-bind="html: selectionne"></span>
      		 </div>
      		 <div class="paragraphe">
      		 <span name="parametre" id="parametre" data-bind="html: selectionne_para"></span>
      		 </div>
      		 <div class="paragraphe">      		 
      		 <table data-role="table" id="listepaiement" name="listepaiement" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			       
			    			<thead>
			      			  <tr><th>date</th><th></th><th>prix</th></tr>
			    			</thead>
			   				<tbody data-bind="foreach: article">
					        <tr>
					            <td data-bind="text: date"></td>
					            <td data-bind="text: medicament"></td>
					            <td data-bind="text: prix"></td>					            		            
					        </tr>
					    </tbody>
					</table>
					<table data-role="table" id="listepaiement2" name="listepaiement2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			       
			    			<thead>
			      			  <tr><th>date</th><th></th><th>prix</th></tr>
			    			</thead>
			   				<tbody data-bind="foreach: article2">
					        <tr>
					            <td data-bind="text: date"></td>
					            <td data-bind="text: medicament"></td>
					            <td data-bind="text: prix"></td>					            		            
					        </tr>
					    </tbody>
					</table>					
				</div>
				<div class="paragraphe">		
				<table data-role="table" id="historique_paiement" name="historique_paiement" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			       
			    			<thead>
			      			  <tr><th>date de paiement</th><th>Montant</th><th>Mode</th></tr>
			    			</thead>
			   				<tbody data-bind="foreach: liste_histo_paiement">
					        <tr>
					            <td data-bind="text: date_p"></td>
					            <td data-bind="text: montant"></td>
					            <td data-bind="text: mode"></td>					            		            
					        </tr>
					    </tbody>
					</table>
				</div>
				<div id="mon_resume" data-role="popup"></div>
				<div class="paragraphe">				
				<fieldset class="ui-grid-b">
       				 <div class="ui-block-a" style="width:40%">
       					<label data-bind="text: total_a_paye"></label>
					 </div>
					 <div class="ui-block-b" style="width:15%">
       					<a style="display:none;" id="bouton_recup_consultation" data-role="button" data-bind="click: recup_consultation" data-icon="star" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Modifier cette consultation" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Modifier cette consultation</span><span class="ui-icon ui-icon-star ui-icon-shadow">&nbsp;</span></span></a>
					 </div>					  
       				 <div class="ui-block-c" style="width:40%">
       					 <label data-bind="text: total_paye"></label>
       				 </div>
    			</fieldset>
    			</div>
       	</div>
    </fieldset>
    </div>
    </li>
    <li>
    <fieldset class="ui-grid-a">
       				 <div class="ui-block-a" id="div_date_consultation">
       				<label for="date_consultation">Date de consultation :</label>
					<input type="date" data-role="datebox" name="date_consultation" id="date_consultation" data-options='{"mode": "datebox", "showInitialValue": true}' />
					 </div>
       				 <div class="ui-block-b" id="div_resume_consultation">
       					<fieldset data-role="fieldcontain"> 
							<label for="barre_resume">Résumé :</label>
							<input type="text" name="barre_resume" id="barre_resume">
						</fieldset>
								<div id="tags">
						<ul id="listeresume" data-bind="foreach: resume">
							<li data-bind="attr: {class: valeur}"><a href="#" data-bind="text: resume,click: $parent.ajoutbarreresume"></a></li>   							
						</ul>
								</div>
	       				 </div>
    </fieldset>
        </li>
        <li>	
         <fieldset class="ui-grid-b">
       				 <div class="ui-block-a">
       					 <label for="poids">Poids :</label>
						<input type="range" name="poids" id="poids" value="10" min="0" max="100" step=".1">
       				 </div>
       				 <div class="ui-block-b">
       					 <label for="temperature">température :</label>
  						 <input type="range" name="temperature" id="temperature" min="30" max="43" step=".1" value="38.8">
       				 </div>
       				 <div class="ui-block-c">
       				 	 <label for="cardio">Freq cardiaque :</label>
  						 <input type="range" name="cardio" id="cardio" min="20" max="250" step="20" value="140">
       				 </div>
       	 </fieldset>
       	 
        </li>
         <li>
          <fieldset data-role="fieldcontain"> 
				<fieldset class="ui-grid-a">
       				 <div class="ui-block-a" style="width:49%">			
						<fieldset class="ui-grid-a">
								<label style="width:100%" for="clinique">Envoi radio :</label>
						</fieldset>
						<fieldset class="ui-grid-a">
								<input id="selection_fichier" style="width:100%" type="file">
																
						</fieldset>
						
						<a href="index.html" data-role="button" data-bind="click: function() { document_plus()}" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter cette radio" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette radio</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
						<fieldset class="ui-grid-a">
								<label style="width:100%" for="clinique">Envoi analyse :</label>
						</fieldset>
						<fieldset class="ui-grid-a">
								<input id="selection_fichier2" style="width:100%" type="file">
																
						</fieldset>
						
						<a href="index.html" data-role="button" data-bind="click: function() { document_plus2()}" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter cette analyse" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette analyse</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		
				  
					 </div>
       				 <div class="ui-block-b" style="width:50%">
				<fieldset class="ui-grid-a">
								<label style="width:100%" for="clinique">Résumé examen clinique :</label>
						</fieldset>
				<textarea name="clinique" id="clinique" style="width:100%;height:100%;margin:0; padding:0; border:none; display:block;"></textarea>
				    </div>
					</fieldset>
			</fieldset>
         </li>
    <li>
        <div data-role="collapsible" id="collaps_rappel">
            <h2>Envoyer une lettre de rappel</h2>
             <fieldset class="ui-grid-c">
             		 <div class="ui-block-a">
       					 <label for="choix_lettre_rappel">Envoyer une lettre de rappel ?</label>
						 <select name="choix_lettre_rappel" id="choix_lettre_rappel" data-role="slider">
   								 <option value="non">non</option>
   								 <option value="oui">oui</option>
						 </select>
       				 </div>
       				 <div class="ui-block-b animation_rappel">
       				     <label for="choix_lettre_rappel2">Date d'envoi :</label>
       					 <ul data-role="listview" id="choix_lettre_rappel2" name="choix_lettre_rappel2" data-inset="true">
              				  <li><a value="12">1 an</a></li>
              				  <li><a value="6" >6 mois</a></li>
              				  <li><a value="5" >5 mois</a></li>
              				  <li><a value="3" >3 mois</a></li>
              				  <li><a value="24">2 ans</a></li>
              				  <li data-choix="autre"><a href="#">autre...</a></li>
          				 </ul>
          				 <input style="display:none;" type="text" name="choix_lettre_rappel2_1" id="choix_lettre_rappel2_1" value="">
       				 </div>
       				 <div class="ui-block-c animation_rappel animation_rappel2">
       						<label for="choix_lettre_rappel3">Date d'envoi particulière :</label>
							<input type="date" data-role="datebox" name="choix_lettre_rappel3" id="choix_lettre_rappel3" data-options='{"mode": "datebox"}' />
       				 </div>
       				 <div class="ui-block-d animation_rappel">
       				 	 <label for="choix_lettre_rappel4">Motif :</label>
       					 <ul data-role="listview" id="choix_lettre_rappel4" data-bind="foreach: relance" data-inset="true">
              				  <li data-bind="attr: {value: valeur_rappel}"><a href="#" data-bind="text: motif_rappel"></a></li>              				  
          				 </ul>
          				 <a  data-role="button" data-bind="click: ajout_relance" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter une relance" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter une relance</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
          				 <input style="display:none;" type="text" name="choix_lettre_rappel4_1" id="choix_lettre_rappel4_1" value="">
       				 </div>
       	 </fieldset>
          <table data-role="table" id="table_relance" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
			    	<thead>
			        <tr class="ui-bar-e"><th>date</th><th>motif</th><th></th></tr>
			    	</thead>
			   	<tbody data-bind="foreach: relance_ajoutes">
			        <tr>
			            <td data-bind="text: date"></td>
			            <td data-bind="text: motif"></td>
			            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_relance" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			        </tr>
			    </tbody>
			</table>	
        </div>
    </li>
    <li id="identification">
    	 <div data-role="collapsible" id="collaps_identification">
            <h2>Identification de l'animal : <?php echo $animal2[0]['num_p']." | ".$animal2[0]['num_t'];?></h2>
             <fieldset class="ui-grid-a">
             		 <div class="ui-block-a">
             		 <label for="puce">puce électronique</label>
					 <input data-bind="value: ma_puce, valueUpdate: 'afterkeydown'" id="puce" name="puce">	
					 <a data-role="button" data-bind='click: function() { ajout_identification("puce")}' data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Enregistrer la puce électronique" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Enregistrer la puce électronique</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
					 </div>
					 <div class="ui-block-b">
					 <label for="taouage">tatouage</label>
					 <input data-bind="value: mon_tatouage, valueUpdate: 'afterkeydown'" id="tatouage" name="tatouage">	
					 <a data-role="button" data-bind='click: function() { ajout_identification("tatouage")}' data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Enregistrer un tatouage" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Enregistrer un tatouage</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
					 </div>
			 </fieldset>
			 </div>
     </li>
    <li id="section_rage">
        <div data-role="collapsible" id="collaps_rage">
            <h2>Vaccin rage </h2>
             <fieldset class="ui-grid-c">
             		 <div class="ui-block-a">
       					 <label for="rage">Réalisation d'une vaccination antirabique ?</label>
						 <select name="rage" id="rage" data-role="slider">
   								 <option value="non">non</option>
   								 <option value="oui">oui</option>
						 </select>
       				 </div>
       				 <div class="ui-block-b animation_rage">
       				     <label for="rage2">Catégorie :</label>
       					 <ul data-role="listview" id="rage2" name="rage2" data-inset="true">
              				  <li data-choix="primo"><a href="#">Primovaccination</a></li>
              				  <li data-choix="rappel"><a href="#">Rappel</a></li>              				 
          				 </ul>
          				  <input style="display:none;" type="text" name="rage2_1" id="rage2_1" value="">
       				 </div>
       				 <div class="ui-block-c animation_rage ">
       						<label for="rage3">Numero de lot</label>
							<input type="text" name="rage3" id="rage3">		
       				 </div>
       				 <div class="ui-block-d animation_rage animation_rage2">
       				 		<label for="rage4">Par (véto)</label>
							<input type="text" name="rage4" id="rage4">
							<label for="date_vac_prec_rage">Date précédent vaccin :</label>
							<input type="date" data-role="datebox" name="date_vac_prec_rage" id="date_vac_prec_rage" data-options='{"mode": "datebox"}' />

       				 </div>
       	 </fieldset>
          
        </div>
    </li>
    <li id="section_pass">
        <div data-role="collapsible" id="collaps_pass">
            <h2>délivrance passeport</h2>
             <fieldset class="ui-grid-a">
             		 <div class="ui-block-a">
       					 <label for="passeport">Un passeport est-il délivré ?</label>
						 <select name="passeport" id="passeport" data-role="slider">
   								 <option value="non">non</option>
   								 <option value="oui">oui</option>
						 </select>
       				 </div>
       				 <div class="ui-block-b animation_passeport">
       				    	<label for="passeport2">Numero de passeport</label>
							<input type="text" name="passeport2" id="passeport2">
							<label for="passeport3">propriétaire de l'animal sur le passeport</label>
							<input type="text" name="passeport3" id="passeport3">
       				 </div>       				
       	 </fieldset>
          
        </div>
    </li>
     <li>
        <div data-role="collapsible" id="collaps_ana">
            <h2>Analyse de sang</h2>
             <fieldset class="ui-grid-c">
              <label for="choix_analyse">Sélectionner l'analyse :</label>
             		 <div class="ui-block-a">       					
						 <select id="choix_analyse" data-bind="value: analyse_defaut, options: analyse, optionsText: 'nom', optionsValue: 'id_selectionne2', event: { change: selection_analyse }">
     						   <!-- <option data-bind="text: nom, click: $parent.selection_analyse, attr: {id: id_selectionne}"></option> --> 
    					</select>
       				 </div>
       				 <div class="ui-block-b" data-bind="visible: unite">
       				 	
       				    	<input type="text" name="choix_analyse2" id="choix_analyse2" placeholder="Entrer la valeur de l'analyse">
							<span data-bind="text: unite" id="choix_analyse4" ></span>
								
					 </div>  
					 <div class="ui-block-c" data-bind="visible: unite">
							<input type="date" data-role="datebox" name="choix_analyse3" id="choix_analyse3" data-options='{"mode": "datebox", "showInitialValue": true}' />
						</div>   
					 <div class="ui-block-d" data-bind="visible: unite">
							<a href="index.html" data-role="button" data-bind="click: ajout_analyse" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette analyse</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
							</div>       				
       	 </fieldset>
          <table data-role="table" id="table_analyse" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>Paramètre</th><th>Résultat</th><th>Unité</th><th>Méthode</th><th>Date d'analyse</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: analyses_ajoutees">
        <tr>
            <td data-bind="text: nom"></td>
            <td data-bind="text: resultat"></td>
            <td data-bind="text: unite"></td>
            <td data-bind="text: methode"></td>
            <td data-bind="text: ma_date"></td>
            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_analyse" class="ui-shadow ui-btn ui-corner-all">-</button></td>
        </tr>
    </tbody>
</table>
<label for="commentaire">Commentaire :</label>
<textarea cols="40" rows="8" name="commentaire" id="commentaire"></textarea>
<div class="section_bouton">
<button id="analyse_pdf" data-bind="click: feuille_analyse" class="ui-shadow ui-btn ui-corner-all">Créer une feuille de résultat</button>
</div>
</div>
    </li>   
     <li>
        <div data-role="collapsible" id="collaps_radio">
            <h2>Radiographie</h2>
             <fieldset class="ui-grid-c">
              
             		 <div class="ui-block-a">    
             			 <label for="choix_radio">Sélectionner zone :</label>   					
						 <select id="choix_radio" data-bind="value: radio_defaut, options: radio, optionsText: 'nom', optionsValue: 'id_selectionne3', event: { change: selection_radio }">
     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" data-bind="visible: kv">       				 	
       				    	<label for="choix_radio2">Valeur de kV</label>
       				    	<input type="text" name="choix_radio2" id="choix_radio2" data-bind="attr: {value: kv}">
							<!--  <span data-bind="text: kv" id="choix_radio2" ></span> -->
							<label for="choix_radio3">Valeur des mAs</label>
							<input type="text" name="choix_radio3" id="choix_radio3" data-bind="attr: {value: mas}">
							<!--  <span data-bind="text: mas" id="choix_radio3" ></span> -->
							<label for="choix_radio4">Valeur des sec</label>
							<input type="text" name="choix_radio4" id="choix_radio4" data-bind="attr: {value: sec}">
							<!--  <span data-bind="text: sec" id="choix_radio4" ></span>	-->							
					 </div>  
					 <div class="ui-block-c" data-bind="visible: kv">
							<label for="choix_radio6">Date cliché:</label>   	
							<input type="date" data-role="datebox" name="choix_radio5" id="choix_radio5" data-options='{"mode": "datebox", "showInitialValue": true}' />
						</div>   
					 <div class="ui-block-d" data-bind="visible: kv">
						 <label for="choix_radio6">Personnel exposé:</label>   					
						 <select id="choix_radio6" data-bind="value: vetos_defaut, options: vetos, optionsText: 'nom', optionsValue: 'id_selectionne4', selectedOptions: veto_preselec">
     				     </select>
						 <a href="index.html" data-role="button" data-bind="click: ajout_radio" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette radiographie</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
							</div>       				
       	 </fieldset>
          <table data-role="table" id="table_radio" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>Personnel</th><th>Exposition</th><th>Zone</th><th>Date cliché</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: radios_ajoutees">
        		<tr>
            <td data-bind="text: perso"></td>
            <td data-bind="text: expo"></td>
            <td data-bind="text: zone"></td>
            <td data-bind="text: ma_date"></td>
            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_radio" class="ui-shadow ui-btn ui-corner-all">-</button></td>
        </tr>
    </tbody>
</table>
<label for="result_radio">Résultat de l'examen :</label>
<textarea cols="40" rows="8" data-bind="text: radio_commentaire" name="result_radio" id="result_radio"></textarea>
<button id="analyse_pdf" data-bind="click: feuille_radio" class="ui-shadow ui-btn ui-corner-all">Créer une feuille de résultat radiologique</button>
        </div>
    </li>   
    <li>
        <div data-role="collapsible">
            <h2>Documents :</h2>
            <div class="section_bouton">
            <a id="certif_sante" name="certif_sante" data-role="button">Certificat de bonne santé</a>
            <a name="certif_sani" id="certif_sani" data-role="button">Certificat sanitaire de transit</a>
            <a name="dem_inci" id="dem_inci" data-role="button" data-bind='click: incineration'>Demande incinération individuelle</a>
            <a name="dem_inci" id="dem_inci2" data-role="button" data-bind='click: incineration2'>Demande incinération normale</a>
            <a name="dem_eutha" id="dem_eutha" data-role="button" data-bind='click: eutha'>Demande euthanasie</a>
            <a name="dem_devis" id="dem_devis" data-role="button" data-bind='click: devis'>Devis</a>
            <a name="autre_certif" id="autre_certif" data-role="button" data-bind='click: autre_certif'>Autre certificat</a>           
        </div>
        </div>   
    </li>
     <li>
    <fieldset class="ui-grid-a">
       				 <div class="ui-block-a" id="tarif_pre_remplis" style="width:30%">
       				 <input type="date" data-role="datebox" name="date_acte" id="date_acte" data-options='{"mode": "datebox", "showInitialValue": true}' />
	       						 <div id="tags">
											<ul id="liste_acte" data-bind="foreach: liste_tarif">
											<li data-bind="attr: {class: valeur}"><a href="#" data-bind="text: nom, click: $parent.ajout_liste_acte, attr: {value: prix}"></a></li>   							
											</ul>
									</div>
	       				 </div>
       				 <div class="ui-block-b" id="tarification" style="width:69%">
			    <table data-role="table" id="table_acte" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
			    	<thead>
			        <tr class="ui-bar-e"><th>Désignation</th><th>prix unitaire</th><th>quantité</th><th>remise</th><th>prix total</th><th></th></tr>
			    	</thead>
			   	<tbody data-bind="foreach: acte_ajoutes">
			        <tr>
			            <td data-bind="text: nom"></td>
			            <td data-bind="text: prix_unitaire"></td>
			            <td data-bind="text: quantite"></td>
			            <td data-bind="text: remise"></td>
			            <td data-bind="text: prix_total"></td>
			            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_acte" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			        </tr>
			    </tbody>
			</table>
			 <fieldset class="ui-grid-b" >
       				 <div class="ui-block-a" style="width:60%">
       				  <label for="designation_acte">Désignation :</label>
       				   <ul id="designation_acte" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="recherche acte" data-filter-theme="d" data-split-icon="gear"></ul>
       				  
     				<!--   <input type="text" name="designation_acte" id="designation_acte" value=""> -->
       				 </div>
       				 <div class="ui-block-b" style="width:20%">
       				 <label for="prix_acte">Prix :</label>
     				 <input type="text" name="prix_acte" id="prix_acte" value="">
     				 <label for="remise_acte">Remise :</label>     				  
    					<select name="remise_acte" id="remise_acte">
    					 	<option value="0">0%</option>
					        <option value="10">10%</option>
					        <option value="20">20%</option>
					        <option value="25">25%</option>
					        <option value="30">30%</option>
					    </select>
       				 </div>
       				 <input type='hidden' name="montant_acte" id="montant_acte" data-bind="attr: {value: total_acte}">
       				 <div class="ui-block-c" style="width:10%; padding: 15px;" >
       				 	<div>
       				 <a  href="index.html" data-role="button" data-bind="click: ajout_acte" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter cet acte" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cet acte</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 	</div>
       				  </div>
       				  
     	 </fieldset>
       				 </div>
      </fieldset>
      
      </li>
      <li>
       <fieldset class="ui-grid-a">
       <div class="ui-block-a" style="width:30%">
       		 <input type="date" data-role="datebox" name="date_medic" id="date_medic" data-options='{"mode": "datebox", "showInitialValue": true}' />
       </div>
        <div class="ui-block-b" style="width:69%">
      <table data-role="table" id="table_medic" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
			    	<thead>
			        <tr class="ui-bar-e"><th>Nom</th><th>lot</th><th>prix unitaire</th><th>quantité</th><th>remise</th><th>prix total</th><th></th></tr>
			    	</thead>
			   	<tbody data-bind="foreach: medic_ajoutes">
			        <tr>
			            <td data-bind="text: nom"></td>
			            <td data-bind="text: lot"></td>
			            <td data-bind="text: prix_unitaire"></td>
			            <td data-bind="text: quantite"></td>
			            <td data-bind="text: remise"></td>
			            <td data-bind="text: prix_total"></td>
			            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_medic" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			        </tr>
			    </tbody>
			</table>			 
     	 </div>
     	 </fieldset>
     	 <div>
     	 <fieldset class="ui-grid-d" >
       				 <div class="ui-block-a" style="width:39%;">
       				  <label for="designation_medic">Nom :</label>
       				  <ul id="designation_medic" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="recherche medicament" data-filter-theme="d" data-split-icon="gear"></ul>
       				  
     				<!--   <input type="text" name="designation_medic" id="designation_medic" value=""> -->
       				 </div>
       				  <div class="ui-block-b" style="width:15%;">
       				  <label for="lot_medic" class="color_bleu" title="Mettre à jour le numéro de lot" data-bind="click: modif_lot">lot :</label>
     				 <input type="text" name="lot_medic" id="lot_medic" value="">
       				 </div>
       				 <div class="ui-block-c" style="width:15%;">
       				 <label for="prix_medic">Prix unitaire :</label>
     				 <input type="text" name="prix_medic" id="prix_medic" value="">
       				 </div>
       				 <div class="ui-block-d" style="width:20%;">
       				 <label for="remise_acte">Remise :</label>     				  
    					<select name="remise_medic" id="remise_medic">
    					 	<option value="0">0%</option>
					        <option value="10">10%</option>
					        <option value="20">20%</option>
					        <option value="25">25%</option>
					        <option value="30">30%</option>
					    </select>
       				 </div>
       				 <div class="ui-block-e" style="padding: 15px; width:10%;">
       				 <div>
       				 <a  href="index.html" data-role="button" data-bind="click: ajout_medic" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter ce medicament" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce medicament</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       				<input type='hidden' name="montant_medic" id="montant_medic" data-bind="attr: {value: total_medic}">
       				 </div>
     	 </fieldset>    	 
     	 </div>
     	 </li>
     	  <li>
       <fieldset class="ui-grid-c">
        <div class="ui-block-a" style="width:30%;">
	      
				    <legend for="restedu" <?php echo($cas=='historique' ?  'style=" color : #C0C0C0 "' : '');?>>Reste dû avant cette consultation :</legend>
	        		 <select name="restedu" <?php echo($cas=='historique' ?  'disabled="disabled"' : '');?> id="restedu" data-native-menu="false" data-bind="options: reste_du_detail, optionsText: 'detail', value: 'source', optionsCaption: reste_du"></select>
			
				    <legend for="total_consult">Prix de cette consultation :</legend>
	    			<input type="text" name="total_consult" id="total_consult" data-bind="attr: {value: total_consultation}">
	         	
        </div>
        <div class="ui-block-b" style="width:25%;">
       				     <label for="choix_mode_paiement">Mode de Paiement :</label>
       					 <ul data-role="listview" id="choix_mode_paiement" name="choix_mode_paiement" data-inset="true">
              				  <li><a href="#">espece</a></li>
              				  <li><a href="#">carte</a></li>
              				  <li><a href="#">cheque</a></li>
              				  <li><a href="#">virement</a></li>              				 
          				 </ul>   
          		<div id="numero_cheque" style="display:none;">	        				 
          		<legend for="montant_paiement">Numero du chèque :</legend>
	    		<input type="text" name="num_cheque" id="num_cheque">
	    		</div>	
				<input style="display:none;" type="text" name="choix_mode_paiement2" id="choix_mode_paiement2" value="">		
						
       				 </div>
       	<div class="ui-block-c" style="width:20%;">
       			<div class="animation_paiement">
       				 <legend for="montant_paiement">Montant du règlement :</legend>
	    			<input type="text" name="montant_paiement" id="montant_paiement" data-bind="attr: {value: total_a_regler}">
	    		</div>
	    		<div class="animation_paiement">
       				 <legend for="montant_paiement3" style="color:#C0C0C0 ">Reste à régler :</legend>
	    			<input data-role="none" style="width:60%;" type="text" disabled="disabled" name="montant_paiement3" id="montant_paiement3" data-bind="attr: {value: total_a_regler}">
	    		</div>
	    		<input type="text" style="display: none;" name="montant_paiement2" id="montant_paiement2" data-bind="attr: {value: total_a_regler}">
	    		
	    		
	    </div>
	    <div class="ui-block-d" style="width:20%;">
	   			<div class="animation_paiement">
	   			<legend for="date_paiement">date du règlement :</legend>
	       		<input type="date" data-role="datebox" name="date_paiement" id="date_paiement" data-options='{"mode": "datebox", "showInitialValue": true}' />
	   			<a  data-role="button" data-bind="click: ajout_paiement" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter un paiement" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter un paiement</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
	   			
	   			</div>
	    </div>
	    </fieldset>
	     	 <table data-role="table" id="table_paiement" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
			      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
			    	<thead>
			        <tr class="ui-bar-e"><th>date</th><th>mode</th><th>montant</th><th></th></tr>
			    	</thead>
			   	<tbody data-bind="foreach: paiement_ajoutes">
			        <tr>
			            <td data-bind="text: date"></td>
			            <td data-bind="text: mode2"></td>
			            <td data-bind="text: montant"></td>
			           
			            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_paiement" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			        </tr>
			    </tbody>
			</table>    
	    </li>
	    
	    
	    
	    
	    
	    <li>
        <div data-role="collapsible" id="repartition_hono">
            <h2>Répartition des honoraires</h2>
             <fieldset class="ui-grid-b">
              
             		 <div class="ui-block-a">    
             			 <label for="choix_veto">Choix vétérinaire :</label>   					
						 <select id="choix_veto" data-bind="value: liste_des_veto, options: liste_veto, optionsText: 'login', optionsValue: 'login'"></select>
       				 </div>
       				 <div class="ui-block-b">       				 	
       				    	<label for="montant_veto">montant</label>
       				    	<input type="range" name="montant_veto" id="montant_veto" min="0" data-bind="attr:{max: total_a_repartir}, value: valeur_a_repartir"/>						
					 </div>  
					 <div class="ui-block-c">
					 		 <legend for="reste_repartir" style="color:#C0C0C0 ">A répartir :</legend>
	    					 <input type="text" disabled="disabled" name="reste_repartir" id="reste_repartir" data-bind="attr: {value: total_a_repartir}">
					 
							 <a href="index.html" data-role="button" data-bind="click: ajout_repartition" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">mettre en place ce règlement</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
					</div>   
					     				
       	 </fieldset>
          <table data-role="table" id="table_repartition" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>Personnel</th><th>montant</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: repartition_ajoutees">
        		<tr>
            <td data-bind="text: veto_desti"></td>
            <td data-bind="text: montant"></td>
            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_repartition" class="ui-shadow ui-btn ui-corner-all">-</button></td>
        </tr>
    </tbody>
</table>
    </div>
    </li>
    
    
    
    
	    <li>
	     <fieldset class="ui-grid-c section_bouton">
        <div class="ui-block-a" style="width:15%;">
	    <a name="retour" id="retour" data-role="button">Retour</a>
	    </div>
	    <div class="ui-block-b" style="width:30%;">
	    <a name="salle_attente" <?php echo((($_SESSION['login']==$_SESSION['login2'] && $_SESSION['login']==$_SESSION['tour'] ) || $cas=='historique' || $cas=='rapport_recus' || $cas=='rapport_emis' || $cas=='envoi_refere') ?  'class="ui-disabled"' : '');?> id="salle_attente" data-role="button" data-bind='click: function() { salle_attente("index")}'>Mettre dossier Attente</a>    
	    </div>
	     <div class="ui-block-c" style="width:22%;">
	    <a name="ordonnance" <?php echo((($_SESSION['login']==$_SESSION['login2'] && $_SESSION['login']==$_SESSION['tour'] ) || $cas=='historique' || $cas=='rapport_recus' || $cas=='rapport_emis' || $cas=='envoi_refere') ?  'class="ui-disabled"' : '');?> id="ordonnance" data-role="button" data-icon="alert" data-theme="a">Ordonnance</a>
	    </div>
	     <div class="ui-block-d" style="width:30%;">
	     <?php 
	    if($_SESSION['login']==$_SESSION['login2'] && $_SESSION['login']==$_SESSION['tour'] ){
	    	if($cas=='historique'){
	    ?>
	    <a name="valider2" id="valider2"  data-bind='click: valider2' data-role="button">Modification secondaire</a>
	    <?php 
	    	}else{
	    		
	    	}
	    }else{
	    ?>	    
	    <a name="valider" id="valider" <?php echo( ($cas=='historique' && $salle_attente_donnee2[0]['veto_origin']!=$_SESSION['login2'] ) ? 'class="ui-disabled"' : '' ); ?>  data-bind='click: valider' data-role="button"><?php echo(($cas=='rapport_recus' || $cas=='rapport_emis' || $cas=='envoi_refere') ? "Enregistrer dans votre base" : "Valider"); ?></a>
	    <?php 
	    }
	    ?>
	    </div>
	    </fieldset>
	    <div id="popup-2" data-role="popup"></div>
	    </li>
	    <li id="section_refere">
        <div data-role="collapsible" id="collaps_refere">
            <h2>Référer cet animal</h2>
             <fieldset class="ui-grid-a">
            		  <div class="ui-block-a">
       					 <label for="specialiste">Référer cet animal à un autre vétérinaire ?</label>
						 <select name="specialiste" id="specialiste" data-role="slider">
   								 <option value="non">non</option>
   								 <option value="oui">oui</option>
						 </select>
       				 </div>
             		 <div class="ui-block-b">
       					<label for="choix_specialiste">A quel vétérinaire référer ce cas ?</label>				
						 <select id="choix_specialiste"> 
						    <option value="0">Sans Objet</option>
							<?php foreach(json_decode($liste_vetos, true) as  $key => $mb) {?>
								<option value="<?php echo $mb['login']; ?>"><?php echo ($mb['login'].' '.$mb['nom'].' '.$mb['commune']);?></option>
							<?php } ?>    						    
    					 </select>
       				 </div>       				   				
       		 </fieldset>          
        </div>
        <div id="mon_ordonnance"></div>
         <div id="popup-3" data-role="popup"></div>
    </li>
     <li>
	     <fieldset class="ui-grid-b">
        <div class="ui-block-a">
	    <a name="retour" id="retour" data-role="button" data-bind='click: attacher'>Mettre en vigilance</a>
	    </div>
	    <div class="ui-block-b">
	    	 <label for="temperature">Importance :</label>
  			 <input type="range" name="importance_vigilance" id="importance_vigilance" min="1" max="5" step="1" value="2">       				 
	    </div>
	    <div class="ui-block-c">
	    </div>
	    </fieldset>
	    <div id="popup-4" data-role="popup"></div>
	  </li>
    
    
    
</ul>
</form>
<div class="paragraphe">
			<h2>Fichiers concernant cet animal sauvegardé :</h2>
			<div id="dossier" class="explorateur_fichier"></div>
		</div>
</section>

<?php render('_footer')?>

