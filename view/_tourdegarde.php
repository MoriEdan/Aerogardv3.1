<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">
$( document ).ready(  function() {

	//besoin pour creation tour de garde
	var list_text = <?php echo TXT_ACCUEIL_JSPARTS;?>;
	
	var liste_jour = <?php echo json_encode($liste_jour);?>;
	var liste_moment = <?php echo json_encode($liste_moment);?>;
	var liste_equipe = <?php echo json_encode($liste_equipe);?>;
	var liste_membre = <?php echo json_encode($liste_membre);?>;
	var liste_rythme = <?php echo json_encode($liste_rythme);?>;
	var liste_cat_planning = <?php echo json_encode($liste_cat_planning);?>;
	var info_tour = <?php echo $info_tour;?>;
	var recherche_tot_garde = <?php echo json_encode($recherche_tot_garde);?>;
	var selection_membre;
	var selection_membre2;	
	$(".second").hide();
	function ViewModel() {
		   var self = this;
		   self.mon_resultat = ko.observableArray([]);
		   self.listejour_defaut = ko.observable("");
		   self.listejour = ko.observableArray(liste_jour);  
		   self.selection_jour1 = function(item, event) {  

		   }
		   self.listemoment_defaut = ko.observable("");
		   self.listemoment_defaut_ferie = ko.observable("");
		   self.recup_point = ko.observableArray([]);
		   self.moment_commentaire = ko.observable();
		   self.moment_commentaire2 = ko.observable();
		   self.listemoment = ko.observableArray(liste_moment);  
		   self.selection_moment1 = function(item, event) {  
			   self.moment_commentaire(liste_moment[$(event.target)[0].selectedIndex]['commentaire'] );	
		   }
		   self.selection_moment_ferie1 = function(item, event) {  
			   self.moment_commentaire2(liste_moment[$(event.target)[0].selectedIndex]['commentaire'] );	
		   }
		   self.listeequipe_defaut = ko.observable("");
		   self.listeequipe_defaut_ferie = ko.observable("");
		   self.listeequipe = ko.observableArray(liste_equipe);  
		   self.selection_equipe1 = function(item, event) {  
			   
		   }
 			self.selection_equipe_ferie1 = function(item, event) {  
			   
		   }
		   //$("#choix_radio6").find(":selected").text()
		  if(info_tour[0]){
			   if(!is_int(info_tour[0]['id'])){
			  	 self.jour_ajoutees = ko.observableArray([]);
			   }else{				  
			  	 self.jour_ajoutees = ko.observableArray(JSON.parse(info_tour[0]['horaire']));
			   }
		  }else{
			  self.jour_ajoutees = ko.observableArray([]);
		  }
		   self.ajout_jour = function(){
				//console.log(" ligne liste jour choisie "+listejour[$("#liste_jour1")[0].selectedIndex]['nom']);
				self.jour_ajoutees.push({nom_jour : liste_jour[$("#liste_jour1")[0].selectedIndex]['nom'], id_jour : liste_jour[$("#liste_jour1")[0].selectedIndex]['valeur'], temps : liste_moment[$("#liste_moment1")[0].selectedIndex]['temps'], moment : liste_moment[$("#liste_moment1")[0].selectedIndex]['commentaire'], heure_debut : liste_moment[$("#liste_moment1")[0].selectedIndex]['valeur']['debut'], heure_fin : liste_moment[$("#liste_moment1")[0].selectedIndex]['valeur']['fin'], team_nom : liste_equipe[$("#liste_equipe1")[0].selectedIndex]['nom'], team_g : liste_equipe[$("#liste_equipe1")[0].selectedIndex]['valeur']['garde'], team_a : liste_equipe[$("#liste_equipe1")[0].selectedIndex]['valeur']['astreinte'], nuit : liste_moment[$("#liste_moment1")[0].selectedIndex]['nuit'], id_select :  self.jour_ajoutees().length});
				//self.jour_ajoutees.sort(function(left, right) { return left.id_jour > right.id_jour ? 1 : (left.id_jour < right.id_jour ? -1 : (left.heure_debut <= right.heure_debut ? -1 : 1)) })
				console.log("nb d'enregistrement "+self.jour_ajoutees().length);
				 
				};
		   // ajout garde a chercher dans les paramètres du tour de garde
			self.ajout_jour2 = function(){

				 self.jour_ajoutees.push({nom_jour : liste_jour[$("#liste_jour10")[0].selectedIndex]['nom'], id_jour : liste_jour[$("#liste_jour10")[0].selectedIndex]['valeur'], temps : liste_moment[$("#liste_moment10")[0].selectedIndex]['temps'], moment : liste_moment[$("#liste_moment10")[0].selectedIndex]['commentaire'], heure_debut : liste_moment[$("#liste_moment10")[0].selectedIndex]['valeur']['debut'], heure_fin : liste_moment[$("#liste_moment10")[0].selectedIndex]['valeur']['fin'], team_nom : liste_equipe[$("#liste_equipe10")[0].selectedIndex]['nom'], team_g : liste_equipe[$("#liste_equipe10")[0].selectedIndex]['valeur']['garde'], team_a : liste_equipe[$("#liste_equipe10")[0].selectedIndex]['valeur']['astreinte'], nuit : liste_moment[$("#liste_moment10")[0].selectedIndex]['nuit'], id_select :  self.jour_ajoutees().length});
										 
					};
			self.supr_jour_ajoute =  function(){
					self.jour_ajoutees.remove(this);					
				};	
		    self.jour_ajoutees_ferie = ko.observableArray([]);
		    self.ajout_jour_ferie = function(){
						//console.log(" ligne liste jour choisie "+listejour[$("#liste_jour1")[0].selectedIndex]['nom']);
						self.jour_ajoutees_ferie.push({date_jour_ferie : $('#date_ferie').datebox('callFormat', '%d/%m/%Y', $('#date_ferie').datebox('getTheDate')), date_jour_ferie2 : $('#date_ferie').datebox('getTheDate'), temps : liste_moment[$("#liste_moment_ferie")[0].selectedIndex]['temps'], moment : liste_moment[$("#liste_moment_ferie")[0].selectedIndex]['commentaire'], heure_debut : liste_moment[$("#liste_moment_ferie")[0].selectedIndex]['valeur']['debut'], heure_fin : liste_moment[$("#liste_moment_ferie")[0].selectedIndex]['valeur']['fin'], team_nom : liste_equipe[$("#liste_equipe_ferie")[0].selectedIndex]['nom'], team_g : liste_equipe[$("#liste_equipe_ferie")[0].selectedIndex]['valeur']['garde'], team_a : liste_equipe[$("#liste_equipe_ferie")[0].selectedIndex]['valeur']['astreinte'], nuit : liste_moment[$("#liste_moment_ferie")[0].selectedIndex]['nuit'], id_select :  self.jour_ajoutees_ferie().length});
						//self.jour_ajoutees_ferie.sort(function(left, right) { return $('#date_ferie').datebox('callFormat', '%j',left.date_jour_ferie2) > $('#date_ferie').datebox('callFormat', '%j',right.date_jour_ferie2) ? 1 : ($('#date_ferie').datebox('callFormat', '%j',left.date_jour_ferie2) < $('#date_ferie').datebox('callFormat', '%j',right.date_jour_ferie2) ? -1 : (left.heure_debut <= right.heure_debut ? -1 : 1)) })
						console.log("nb d'enregistrement "+self.jour_ajoutees_ferie().length);
						};
			self.supr_jour_ferie_ajoute =  function(){
							self.jour_ajoutees_ferie.remove(this);							
						};	
			self.historique_calendrier = function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Récupération des points...</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
		 		$.ajax({		    
		        	type: "POST",
		            url: "php/tourdegarde.php?action=recup_point",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			nb_choix : liste_cat_planning.length, date_debut : $('#date_debut1').datebox('callFormat', '%s', $('#date_debut1').datebox('getTheDate').clone().add(-12).months()), date_fin : $('#date_debut1').datebox('callFormat', '%s', $('#date_debut1').datebox('getTheDate'))   
		            },	
		            success: function(data){
		            	$.mobile.loading('hide');			                       
		            	self.recup_point(data);	
		            			            	
		            	var mon_index_rotation = 0;
						ko.utils.arrayForEach(self.membre_rotation(), function(item) {
				    			ko.utils.arrayForEach(self.recup_point(), function(item2) {			    					    		
									if(item2.login==item.login){
										item.points = item2.somme_heure; 
									}
				    			});
				    			item.id_select = mon_index_rotation;
				    			item.rythme_actu = 1;
				    			item.date_modif = $('#date_debut1').datebox('getTheDate').clearTime().getTime();
				    			item.date_modif2 = $('#date_debut1').datebox('getTheDate').clearTime().getTime();
			    				mon_index_rotation++;
				        });	
						self.recup_historique(data);
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
			self.recup_historique = function(histo_points){

				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Récupération de l'historique du mois...</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
		 		$.ajax({		    
		        	type: "POST",
		            url: "php/tourdegarde.php?action=recup_historique",
		            dataType: "json",
		            cache: false,
		            data:  {
		            date_debut : $('#date_debut1').datebox('callFormat', '%s', $('#date_debut1').datebox('getTheDate').clone().set({ day: 1 })) , date_fin : $('#date_debut1').datebox('callFormat', '%s', $('#date_debut1').datebox('getTheDate').clone().set({ day: 1 }).add({ months: 1 }))   
		            },	
		            success: function(data){
		            	$.mobile.loading('hide');			                       
		            	self.creation_calendrier(histo_points, data);	
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
			self.creation_calendrier = function(histo_points, historique_mois){


				$('#calendar2').monthCalendar2({

					shortMonths: list_text.shortMonths,
					shortDays:list_text.shortDays,
					firstDayOfWeek: -1,
					data:$.parseJSON(historique_mois),
					rythme:liste_rythme,
					nb_choix : liste_cat_planning.length,
					liste_cat_planning : liste_cat_planning,
					ma_date:$('#date_debut1').datebox('getTheDate').clearTime(),
					date_cre_deb:$('#date_debut1').datebox('getTheDate').clearTime(),
					date_cre_fin:$('#date_fin1').datebox('getTheDate').clearTime(),
					horaire_trouver : $.parseJSON(ko.toJSON(self.jour_ajoutees)),
					horaire_ferie_trouver : $.parseJSON(ko.toJSON(self.jour_ajoutees_ferie)),					
							synthese: function(e, ui){
								selection_membre2 = ui.leplanning;
								self.mon_resultat([]);
								ko.utils.arrayForEach(self.membre_rotation(), function(item) {
									selection_membre = $.grep(ui.leplanning, function( n, i ) {		
										return ko.utils.unwrapObservable(item.login)==n[0];																											
									});
									self.mon_resultat.push({login : ko.utils.unwrapObservable(item.login),
										mon_tableau :  selection_membre
										});
									var total = parseFloat(ko.utils.unwrapObservable(item.point_avant));
									$.each( selection_membre, function( key, value ) {
										total +=  (value[4]-value[3])/(1000*60*60);
										item.point_apres(total);	
																			
									});
									
								});
								$('#calendar2').monthCalendar2('fini');				            
							
							},
							fini: function(e, ui){ 								
								$.mobile.loading('hide');
							},
							rechargement: function(e, ui){ 
								    var ma_date2=new Date(ui.date_ref);
									 $.mobile.loading( 'show', {
									 textonly : "true",
									 textVisible : "true",
								     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.uploadinprogress+"</h2></span>",
									 iconpos : "right",
									 theme: "a"
								   	 });
						   	     													
								        		$.ajax({	
											 		type: "POST",
											 		url: "php/tourdegarde.php?action=recup_historique",
													dataType: "json",
											            cache: false,
											            data:  {
											            date_debut : $('#date_debut1').datebox('callFormat', '%s', ma_date2.clone().set({ day: 1 })) , date_fin : $('#date_debut1').datebox('callFormat', '%s', ma_date2.clone().set({ day: 1 }).add({ months: 1 }))   
											            },	
											        
													success: function(data){												
													$.mobile.loading('hide');	
													$('#calendar2').monthCalendar2('raffraichir', data, ma_date2.getTime());	
																						   
														            
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
					});
		//		$('#second_part').show();
				$(".second").show();
				$(".premier").hide();
			}
			self.listemembre1_defaut = ko.observable("");
			self.listemembre = ko.observableArray([]);
			self.listemembre(JSON.parse(liste_membre));
			self.selection_membre1 = function(item, event) {  

			   }
			self.listemoment2_defaut = ko.observable("");
			self.moment_commentaire2 = ko.observable();
			self.selection_moment2 = function(item, event) {  
				if($.parseJSON(ko.toJSON(self.jour_ajoutees)).length>0){
				 self.moment_commentaire2($.parseJSON(ko.toJSON(self.jour_ajoutees))[$(event.target)[0].selectedIndex]['moment'] );
				}else{
				 self.moment_commentaire2("");
				}
			}

			self.listechoix_defaut = ko.observable("");
			self.listechoix = ko.observableArray([]);
			self.listechoix(liste_cat_planning);
			self.selection_choix1 = function(item, event) {  

			   }
			self.ajout_1 = ko.observableArray([]);
			self.ajout_planning1 = function(){
				self.ajout_1.push({login : JSON.parse(liste_membre)[$("#liste_membre1")[0].selectedIndex]['login'],
					 cat : liste_cat_planning[$("#liste_choix1")[0].selectedIndex]['nom'],
					  cat2 : liste_cat_planning[$("#liste_choix1")[0].selectedIndex]['valeur'],
					   debut :  $('#date_debut2').datebox('getTheDate').clearTime().getTime(),
					   debut2 :  $('#date_debut2').datebox('getTheDate').toString('d/M/yyyy'),					   
					   fin :  $('#date_fin2').datebox('getTheDate').clearTime().getTime(), 
					    fin2 :  $('#date_fin2').datebox('getTheDate').toString('d/M/yyyy'), 
					    choix_horaire : $.parseJSON(ko.toJSON(self.jour_ajoutees))[$("#liste_moment2")[0].selectedIndex]['nom_jour']+' '+$.parseJSON(ko.toJSON(self.jour_ajoutees))[$("#liste_moment2")[0].selectedIndex]['moment'],
					     choix_horaire2 : $.parseJSON(ko.toJSON(self.jour_ajoutees))[$("#liste_moment2")[0].selectedIndex]['id_select'],
					      id_select :  self.ajout_1().length});

			//	$('#calendar2').monthCalendar2('ajout_planning', 1, $.parseJSON(ko.toJSON(self.ajout_1)));

			} 
			self.supr_ajout_1 =  function(){
				self.ajout_1.remove(this);
			//	self.somme_membre();
			//	$('#calendar2').monthCalendar2('ajout_planning', 1, $.parseJSON(ko.toJSON(self.ajout_1)));
			};


			self.listemembre2_defaut = ko.observable("");
			self.selection_membre_2 = function(item, event) {  

			   }
			if(info_tour[0]){
				 if(!is_int(info_tour[0]['id'])){
					 self.indispo_1 = ko.observableArray([]);
				   }else{					   
					   self.indispo_1 = ko.observableArray(JSON.parse(info_tour[0]['vacances']));
				   }
				}else{
					self.indispo_1 = ko.observableArray([]);
				}
			
			self.ajout_indispo1 = function(){
				self.indispo_1.push({login : JSON.parse(liste_membre)[$("#liste_membre2")[0].selectedIndex]['login'],
					debut :  $('#date_debut3').datebox('getTheDate').clearTime().getTime(),
					  debut2 :  $('#date_debut3').datebox('getTheDate').toString('d/M/yyyy'),					   
					   fin :  $('#date_fin3').datebox('getTheDate').clearTime().getTime(), 
					    fin2 :  $('#date_fin3').datebox('getTheDate').toString('d/M/yyyy'), 
					     id_select :  self.indispo_1().length});

			//	$('#calendar2').monthCalendar2('ajout_planning', 2, $.parseJSON(ko.toJSON(self.indispo_1)));

			} 
			// function pour parametre du tour
			self.ajout_indispo2 = function(){
				self.indispo_1.push({login : JSON.parse(liste_membre)[$("#liste_membre13")[0].selectedIndex]['login'],
					debut :  $('#date_debut13').datebox('getTheDate').clearTime().getTime(),
					  debut2 :  $('#date_debut13').datebox('getTheDate').toString('d/M/yyyy'),					   
					   fin :  $('#date_fin13').datebox('getTheDate').clearTime().getTime(), 
					    fin2 :  $('#date_fin13').datebox('getTheDate').toString('d/M/yyyy'), 
					     id_select :  self.indispo_1().length});
			} 			
			self.supr_indispo_1 =  function(){
				self.indispo_1.remove(this);
			//	self.somme_membre();
			//	$('#calendar2').monthCalendar2('ajout_planning', 2, $.parseJSON(ko.toJSON(self.indispo_1)));
			};


			if(info_tour[0]){
				 if(!is_int(info_tour[0]['id'])){
					 self.importance = ko.observableArray([]);
				   }else{					   
					   self.importance = ko.observableArray(JSON.parse(info_tour[0]['importance']));
				   }
				}else{
					self.importance = ko.observableArray([]);
				}
			
			self.ajout_importance = function(){
				self.importance.push({login : JSON.parse(liste_membre)[$("#liste_membre14")[0].selectedIndex]['login'],
					importance :  $('#range14').val(),
					  id_select :  self.importance().length});

			//	$('#calendar2').monthCalendar2('ajout_planning', 2, $.parseJSON(ko.toJSON(self.indispo_1)));

			} 
			// gestion importance membre dans le planning 
			listemembre15_defaut = ko.observable("");
			self.ajout_importance2 = function(){
				self.importance.push({login : JSON.parse(liste_membre)[$("#liste_membre15")[0].selectedIndex]['login'],
					importance :  $('#range15').val(),
					  id_select :  self.importance().length});

			} 

			self.supr_importance =  function(){
				self.importance.remove(this);
			//	self.somme_membre();
			//	$('#calendar2').monthCalendar2('ajout_planning', 2, $.parseJSON(ko.toJSON(self.indispo_1)));
			};			
			
			listemembre3_defaut = ko.observable("");
			self.selection_membre3 = function(item, event) {  

			   };
			listerythme_defaut = ko.observable("");			
			self.listerythme = ko.observableArray(liste_rythme);
			self.selection_rythme3 = function(item, event) {  

			   };
			self.jourfavo = ko.observableArray();
			self.jourevi = ko.observableArray();
			self.jourfavo2 = ko.observableArray();
			self.jourevi2 = ko.observableArray();
			if(info_tour[0]){
			 if(!is_int(info_tour[0]['id'])){
				 self.rotation_ajoute = ko.observableArray();
			   }else{
				   self.rotation_ajoute = ko.observableArray(JSON.parse(info_tour[0]['participant']));
			   }
			}else{
				self.rotation_ajoute = ko.observableArray();
			}
			self.membre_rotation = ko.observableArray();
		    self.ajout_rotation = function(){
		    	var total_favo = '';
		    	ko.utils.arrayForEach(self.jourfavo(), function(item) {
		    		total_favo += ko.utils.unwrapObservable(self.jour_ajoutees()[item].nom_jour)+" "+ko.utils.unwrapObservable(self.jour_ajoutees()[item].moment)+" "+liste_rythme[$("#listerythme")[0].selectedIndex]['nom']+"<br\>";
		        });
		    	var total_evi = '';
		    	ko.utils.arrayForEach(self.jourevi(), function(item) {
		    		total_evi += ko.utils.unwrapObservable(self.jour_ajoutees()[item].nom_jour)+" "+ko.utils.unwrapObservable(self.jour_ajoutees()[item].moment)+"   ";
		        });				
				var mon_nombre_de_point = 0;
		    	ko.utils.arrayForEach(self.recup_point(), function(item) {
					if(item.login==JSON.parse(liste_membre)[$("#liste_membre3")[0].selectedIndex]['login']){
						mon_nombre_de_point = item.somme_heure; 
					}
		        });			
			    
					self.rotation_ajoute.push({login : JSON.parse(liste_membre)[$("#liste_membre3")[0].selectedIndex]['login'],
						jour_favo :  $.parseJSON(ko.toJSON(self.jourfavo)),
						jour_favo2 :  total_favo,
						  rythme :  liste_rythme[$("#listerythme")[0].selectedIndex]['mon_index'],
						 	 rythme2 :  liste_rythme[$("#listerythme")[0].selectedIndex]['nom'],						   
						 		jour_evi :  $.parseJSON(ko.toJSON(self.jourevi)),
						 		jour_evi2 :  total_evi,
						          id_select :  self.rotation_ajoute().length,
						          	points : mon_nombre_de_point,
						          	  points1 : 0,
						          	    points2 : 0,
						          	      points3 : 0,
						          	        rythme_actu : 1,
						          	          date_modif : $('#date_debut1').datebox('getTheDate').clearTime().getTime(),
						          	 	       date_modif2 : $('#date_debut1').datebox('getTheDate').clearTime().getTime()
															          	
						          });
					
					
	
				} 
		// selection des membres et des indisponibilités dans les paramètres du tour de garde
		    self.ajout_rotation2 = function(){
		    	var total_favo = '';
		    	ko.utils.arrayForEach(self.jourfavo2(), function(item) {
		    		total_favo += ko.utils.unwrapObservable(self.jour_ajoutees()[item].nom_jour)+" "+ko.utils.unwrapObservable(self.jour_ajoutees()[item].moment)+" "+liste_rythme[$("#listerythme")[0].selectedIndex]['nom']+"<br\>";
		        });
		    	var total_evi = '';
		    	ko.utils.arrayForEach(self.jourevi2(), function(item) {
		    		total_evi += ko.utils.unwrapObservable(self.jour_ajoutees()[item].nom_jour)+" "+ko.utils.unwrapObservable(self.jour_ajoutees()[item].moment)+"   ";
		        });				
				var mon_nombre_de_point = 0;
		    	ko.utils.arrayForEach(self.recup_point(), function(item) {
					if(item.login==JSON.parse(liste_membre)[$("#liste_membre9")[0].selectedIndex]['login']){
						mon_nombre_de_point = item.somme_heure; 
					}
		        });			
			    
					self.rotation_ajoute.push({login : JSON.parse(liste_membre)[$("#liste_membre9")[0].selectedIndex]['login'],
						jour_favo :  $.parseJSON(ko.toJSON(self.jourfavo2)),
						jour_favo2 :  total_favo,
						  rythme :  liste_rythme[$("#listerythme9")[0].selectedIndex]['mon_index'],
						 	 rythme2 :  liste_rythme[$("#listerythme9")[0].selectedIndex]['nom'],						   
						 		jour_evi :  $.parseJSON(ko.toJSON(self.jourevi2)),
						 		jour_evi2 :  total_evi,
						          id_select :  self.rotation_ajoute().length,
						          	points : mon_nombre_de_point,
						          	  points1 : 0,
						          	    points2 : 0,
						          	      points3 : 0,
						          	        rythme_actu : 1,
						          	          date_modif : $('#date_debut1').datebox('getTheDate').clearTime().getTime(),
						          	 	       date_modif2 : $('#date_debut1').datebox('getTheDate').clearTime().getTime()
															          	
						          });
					
				} 
				
		    self.somme_membre =  function(){		
		    	   
		    	
		    	self.membre_rotation([]);	

				ko.utils.arrayForEach(self.rotation_ajoute(), function(item2) {
						var ajout_membre_rotation = true;
						var ajout_membre_rotation2 = true;	
						
					ko.utils.arrayForEach(self.membre_rotation(), function(item) {
						if(item.login==item2.login){
							ajout_membre_rotation = false;
						}
					});
					if(ajout_membre_rotation == true){
						ko.utils.arrayForEach(self.recup_point(), function(item) {
							if(item.login==item2.login){
								self.membre_rotation.push({login : ko.observable(item2.login),
									point_avant :  ko.observable(item.somme_heure),
									point_apres :  ko.observable(item.somme_heure),
									id_select :  self.membre_rotation().length
								});
								ajout_membre_rotation2 = false;
							}
				        });
						if(ajout_membre_rotation2 == true){
							self.membre_rotation.push({login : ko.observable(item2.login),
								point_avant :  ko.observable(0),
								point_apres :  ko.observable(0),
								id_select :  self.membre_rotation().length
							});
						}
					}
				});
		    }
		    self.choisir = function(){

		    	 self.ajout_garde_membre([]);
					if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit'] == "non"){ 
					 self.ajout_garde_membre.push({login : this.login,
							cat: liste_cat_planning[$("#cat_garde2")[0].selectedIndex]['valeur'],
							ma_date :  $('#modif_date').datebox('getTheDate').clone().clearTime().getTime(),
							date_debut : $('#modif_date').datebox('getTheDate').clone().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['debut'] }).getTime(),
							date_fin : $('#modif_date').datebox('getTheDate').clone().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['fin'] }).getTime(),
							
						}); 
					}else{
						self.ajout_garde_membre.push({login : this.login,
							cat: liste_cat_planning[$("#cat_garde2")[0].selectedIndex]['valeur'],
							ma_date :  $('#modif_date').datebox('getTheDate').clone().clearTime().getTime(),
							date_debut : $('#modif_date').datebox('getTheDate').clone().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['debut'] }).getTime(),
							date_fin : $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().getTime(),
							
						}); 
						self.ajout_garde_membre.push({login : this.login,
							cat: liste_cat_planning[$("#cat_garde2")[0].selectedIndex]['valeur'],
							ma_date :  $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().getTime(),
							date_debut : $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().getTime(),
							date_fin : $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['fin'] }).getTime(),
							
						}); 

					}


					 $("#mon_popup1").html('');
					   var $popUp = $("#mon_popup1").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					       
					    }).css({
					        'width': '370px',
					            'height': '400px',
					            'padding': '5px'
					    });
					    //create a title for the popup
					    $("<h4/>", {
					        text: "Voulez-vous enregistrer cette garde ?"
					    }).appendTo($popUp);
					  //Create a submit button(fake)
					    $("<a>", {
					        text : "oui"
					    }).buttonMarkup({
					        inline : true,
					        icon : "check"
					    }).bind("click", function() {
					    	$popUp.popup("close");
							    	$.mobile.loading( 'show', {
										textonly : "true",
									    textVisible : "true",
									    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement en cours...</h2></span>",
										iconpos : "right",
									    theme: "a"
									             	 
									});
							    	$.ajax({	
					 				    
							        	type: "POST",
							        	url: "php/tourdegarde.php?action=save_planning2",
							        	dataType: "json",
							            cache: false,
							            data:  {
							    		planning2 : ko.toJSON(self.ajout_garde_membre),
							    		},	
							            success: function(data2){
								            	alert("Garde enregistrée");
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


					    


		    };
		    self.supr_rotation_ajoute =  function(){
				self.rotation_ajoute.remove(this);
			//	self.somme_membre();
			//	$('#calendar2').monthCalendar2('ajout_planning', 3, $.parseJSON(ko.toJSON(self.rotation_ajoute)));				
			};
			 var ObsNumber = function(i) {
			        this.value = ko.observable(i);
			    }
			 self.computedData = ko.computed(function() {
			        return ko.utils.arrayMap(self.membre_rotation(), function(item) {
			            return { login: item.login(), point_avant: item.point_avant(),point_apres: item.point_apres() };
			        });
			    });
			 creer_planning = function(){
				 $.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du planning...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
				 self.somme_membre();
					$('#calendar2').monthCalendar2('ajout_planning', 4, $.parseJSON(ko.toJSON(self.ajout_1)), $.parseJSON(ko.toJSON(self.indispo_1)), $.parseJSON(ko.toJSON(self.rotation_ajoute)), $.parseJSON(ko.toJSON(self.membre_lie)), $.parseJSON(ko.toJSON(self.importance)));
				//	$('#calendar2').monthCalendar2('ajout_planning', 1, $.parseJSON(ko.toJSON(self.ajout_1)));
				//	$('#calendar2').monthCalendar2('ajout_planning', 2, $.parseJSON(ko.toJSON(self.indispo_1)));

			 }
			 self.creer_document = function(){
				 $.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création des documents...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
			 		$.ajax({		    
			        	type: "POST",
			            url: "php/tourdegarde.php?action=document",
			            dataType: "json",
			            cache: false,
			            data:  {
			            planning : ko.toJSON(self.mon_resultat),
			            contrainte1 : ko.toJSON(self.ajout_1),
			            contrainte2 : ko.toJSON(self.indispo_1),
			            contrainte3 : ko.toJSON(self.rotation_ajoute),
			            contrainte4 : ko.toJSON(self.importance),
			            date_debut : $('#date_debut1').datebox('getTheDate').toString('d/M/yyyy'), 
			            date_fin : $('#date_fin1').datebox('getTheDate').toString('d/M/yyyy'),
			            date_debut2 : $('#date_debut1').datebox('getTheDate').toString('d_M_yyyy'), 
			            date_fin2 : $('#date_fin1').datebox('getTheDate').toString('d_M_yyyy'),
			            date_debut3 : $('#date_debut1').datebox('getTheDate').clearTime().getTime(), 
			            date_fin3 : $('#date_fin1').datebox('getTheDate').clearTime().getTime(), 
			            base1 :  ko.toJSON(self.jour_ajoutees),
			            base2 :  ko.toJSON(self.jour_ajoutees_ferie),
			            bilan : ko.toJSON(self.computedData),
			            planning2 : JSON.stringify(selection_membre2),
			            liste_jour : liste_jour
					    },	
			            success: function(data){
			            	$.mobile.loading('hide');
			            	window.open('aerogard/'+data);	
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
			 self.save_planning = function(){

				 $("#mon_popup1").html('');
				   var $popUp = $("#mon_popup1").popup({
				        dismissible: false,
				        theme: "b",
				        overlyaTheme: "e",
				        transition: "pop"
				    }).on("popupafterclose", function () {
				       
				    }).css({
				        'width': '370px',
				            'height': '400px',
				            'padding': '5px'
				    });
				    //create a title for the popup
				    $("<h4/>", {
				        text: "Voulez-vous enregistrer ce planning ?"
				    }).appendTo($popUp);
				  //Create a submit button(fake)
				    $("<a>", {
				        text : "oui"
				    }).buttonMarkup({
				        inline : true,
				        icon : "check"
				    }).bind("click", function() {
				    	$popUp.popup("close");
						    	$.mobile.loading( 'show', {
									textonly : "true",
								    textVisible : "true",
								    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement en cours...</h2></span>",
									iconpos : "right",
								    theme: "a"
								             	 
								});
						    	$.ajax({	
				 				    
						        	type: "POST",
						        	url: "php/tourdegarde.php?action=save_planning",
						        	dataType: "json",
						            cache: false,
						            data:  {
						    		planning2 : JSON.stringify(selection_membre2),
						    		date_debut : $('#date_debut1').datebox('getTheDate').clearTime().getTime(), 
						            date_fin : $('#date_fin1').datebox('getTheDate').clearTime().getTime()						   
						            },	
						            success: function(data2){
							            	alert("Gardes enregistrées : "+data2);
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
				    

			 };
			 self.listemoment_modif_defaut = ko.observable("");
			 self.moment_commentaire_modif = ko.observable();
			 self.selection_modif = function(item, event) {  
				   self.moment_commentaire_modif(liste_moment[$(event.target)[0].selectedIndex]['commentaire'] );	
			    };
			 self.listechoix_modif_defaut = ko.observable("");
			 self.selection_modif_choix1 = function(item, event) {  
			 	};
			 self.listemembre4_defaut = ko.observable("");
			 self.selection_membre4 = function(item, event) {  
			 	};
			 self.ajout_garde_membre = ko.observableArray();
			 self.ajout_garde = function() { 
				 self.ajout_garde_membre([]);
				if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit'] == "non"){ 
				 self.ajout_garde_membre.push({login : JSON.parse(liste_membre)[$("#liste_membre4")[0].selectedIndex]['login'],
						cat: liste_cat_planning[$("#liste_choix_modif1")[0].selectedIndex]['valeur'],
						ma_date :  $('#modif_date').datebox('getTheDate').clone().clearTime().getTime(),
						date_debut : $('#modif_date').datebox('getTheDate').clone().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['debut'] }).getTime(),
						date_fin : $('#modif_date').datebox('getTheDate').clone().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['fin'] }).getTime(),
						
					}); 
				}else{
					self.ajout_garde_membre.push({login : JSON.parse(liste_membre)[$("#liste_membre4")[0].selectedIndex]['login'],
						cat: liste_cat_planning[$("#liste_choix_modif1")[0].selectedIndex]['valeur'],
						ma_date :  $('#modif_date').datebox('getTheDate').clone().clearTime().getTime(),
						date_debut : $('#modif_date').datebox('getTheDate').clone().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['debut'] }).getTime(),
						date_fin : $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().getTime(),
						
					}); 
					self.ajout_garde_membre.push({login : JSON.parse(liste_membre)[$("#liste_membre4")[0].selectedIndex]['login'],
						cat: liste_cat_planning[$("#liste_choix_modif1")[0].selectedIndex]['valeur'],
						ma_date :  $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().getTime(),
						date_debut : $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().getTime(),
						date_fin : $('#modif_date').datebox('getTheDate').clone().add(1).days().clearTime().set({ hour: $.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['valeur']['fin'] }).getTime(),
						
					}); 

				}


				 $("#mon_popup1").html('');
				   var $popUp = $("#mon_popup1").popup({
				        dismissible: false,
				        theme: "b",
				        overlyaTheme: "e",
				        transition: "pop"
				    }).on("popupafterclose", function () {
				       
				    }).css({
				        'width': '370px',
				            'height': '400px',
				            'padding': '5px'
				    });
				    //create a title for the popup
				    $("<h4/>", {
				        text: "Voulez-vous enregistrer cette garde ?"
				    }).appendTo($popUp);
				  //Create a submit button(fake)
				    $("<a>", {
				        text : "oui"
				    }).buttonMarkup({
				        inline : true,
				        icon : "check"
				    }).bind("click", function() {
				    	$popUp.popup("close");
						    	$.mobile.loading( 'show', {
									textonly : "true",
								    textVisible : "true",
								    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement en cours...</h2></span>",
									iconpos : "right",
								    theme: "a"
								             	 
								});
						    	$.ajax({	
				 				    
						        	type: "POST",
						        	url: "php/tourdegarde.php?action=save_planning2",
						        	dataType: "json",
						            cache: false,
						            data:  {
						    		planning2 : ko.toJSON(self.ajout_garde_membre),
						    		},	
						            success: function(data2){
							            	alert("Garde enregistrée");
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
					
			 	};
			 	self.creation_tableau = ko.observableArray();
			 	$('#supr_date').on('datebox', function(e, p) {
			 	   if ( p.method === 'close' ) {
			 		  $.mobile.loading( 'show', {
							textonly : "true",
						    textVisible : "true",
						    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Récupération des gardes...</h2></span>",
							iconpos : "right",
						    theme: "a"
						             	 
						});
				 		$.ajax({		    
				        	type: "POST",
				            url: "php/tourdegarde.php?action=recup_historique2",
				            dataType: "json",
				            cache: false,
				            data:  {
				            date_debut : $('#supr_date').datebox('callFormat', '%s', $('#supr_date').datebox('getTheDate').clone().clearTime()) , date_fin : $('#supr_date').datebox('callFormat', '%s', $('#supr_date').datebox('getTheDate').clone().clearTime().add(1).days())   
				            },	
				            success: function(data){
				            	$.mobile.loading('hide');			                       
				            	self.creation_tableau(data);	
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
			 	});
			 	self.supr_garde  = function(item, event) { 

					 $("#mon_popup1").html('');
					   var $popUp = $("#mon_popup1").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					       
					    }).css({
					        'width': '370px',
					            'height': '400px',
					            'padding': '5px'
					    });
					    //create a title for the popup
					    $("<h4/>", {
					        text: "Voulez-vous supprimer cette garde ?"
					    }).appendTo($popUp);
					  //Create a submit button(fake)
					    $("<a>", {
					        text : "oui"
					    }).buttonMarkup({
					        inline : true,
					        icon : "check"
					    }).bind("click", function() {
					    	$popUp.popup("close");
							    	$.mobile.loading( 'show', {
										textonly : "true",
									    textVisible : "true",
									    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Suppression en cours...</h2></span>",
										iconpos : "right",
									    theme: "a"
									             	 
									});
							    	$.ajax({	
					 				    
							        	type: "POST",
							        	url: "php/tourdegarde.php?action=supr_planning",
							        	dataType: "json",
							            cache: false,
							            data:  {
							    		supr_id : $(event.target).attr('value'),
							    		},	
							            success: function(data2){
								              alert("Garde supprimée");
								              self.creation_tableau.remove(function(item) { return item.id == $(event.target).attr('value') });
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
				          	
			 	self.membre_points = ko.observableArray();
			 	self.recup_point2 = ko.observableArray([]);
			 	self.recup_point3 = ko.observableArray([]);
			 	
			 	self.cat_garde_defaut = ko.observable("");
			 	self.cat_garde_defaut2 = ko.observable("");
			 	self.moment_garde_defaut = ko.observable("");	
			 	self.liste_cat_planning_obs = ko.observableArray(liste_cat_planning);		 	
			 	self.recherche_tot_garde_obs = ko.observableArray(recherche_tot_garde);	 	

			 	self.recherche_point2 = function() {

			 		$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>recherche historique garde...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
			    	$.ajax({	
	 				    
			        	type: "POST",
			        	url: "php/tourdegarde.php?action=recherche_next_der",
			        	dataType: "json",
			            cache: false,
			            data:  {
			            date_actu : $('#modif_date').datebox('callFormat', '%s', $('#modif_date').datebox('getTheDate')),
						liste_recherche : ko.toJSON(self.rotation_ajoute),
			           
				            },	
			            success: function(donne_next_pre){
			            	$.mobile.loading('hide');
			            	//$('#point_date').on('datebox', function(e, p) {
						 	 //  if ( p.method === 'close' ) {
						 		  $.mobile.loading( 'show', {
										textonly : "true",
									    textVisible : "true",
									    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Récupération des points...</h2></span>",
										iconpos : "right",
									    theme: "a"
									             	 
									});

									var cat_garde_jour; 
									if($('#modif_date').datebox('getTheDate').clone().is().monday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 2;								
										}else{
											cat_garde_jour = 1;	

										}
									}else if($('#modif_date').datebox('getTheDate').clone().is().tuesday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 1;								
										}else{
											cat_garde_jour = 1;	

										}
									}else if($('#modif_date').datebox('getTheDate').clone().is().wednesday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 1;								
										}else{
											cat_garde_jour = 1;	

										}
									}else if($('#modif_date').datebox('getTheDate').clone().is().thursday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 1;								
										}else{
											cat_garde_jour = 1;	

										}

									}else if($('#modif_date').datebox('getTheDate').clone().is().friday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 2;								
										}else{
											cat_garde_jour = 3;	

										}

									}else if($('#modif_date').datebox('getTheDate').clone().is().saturday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 2;								
										}else{
											cat_garde_jour = 3;	

										}

									}else if($('#modif_date').datebox('getTheDate').clone().is().sunday()){
										if($.parseJSON(ko.toJSON(self.listemoment))[$("#liste_moment_modif")[0].selectedIndex]['nuit']=="oui"){
											cat_garde_jour = 2;								
										}else{
											cat_garde_jour = 3;	

										}

									}
							 		$.ajax({
							 			type: "POST",
							            url: "php/tourdegarde.php?action=recup_point2",
							            dataType: "json",
							            cache: false,
							            data:  {
							            date_debut : $('#modif_date').datebox('callFormat', '%s', $('#modif_date').datebox('getTheDate').clone().add(-12).months()),
							 			date_fin : $('#modif_date').datebox('callFormat', '%s', $('#modif_date').datebox('getTheDate').clone().add(3).months()),
							 			cat_garde : liste_cat_planning[$("#cat_garde2")[0].selectedIndex]['valeur'],
							 			recherche_tot_garde : cat_garde_jour
							            },	
							            success: function(data){
							            	$.mobile.loading('hide');

							            	self.recup_point2([]);
							            	var detail_point = $.map($.parseJSON(ko.toJSON(self.rotation_ajoute)), function(item,index) {
							            		var point_trouve = false;
							            		var var1 = "";
							            		var var2 = 0;
							            		var var6 = "?";
							            		var var7 = "?";
							            		var date_der;
							            		var ts;
							            		var date_next;
							            		
							            		$.each( donne_next_pre, function( key, value ) {
							            			if(value['login']==item['login']){
														if(value['date_der']!=null){															
															var timeDiff = Math.abs($('#modif_date').datebox('getTheDate').clone().clearTime().getTime() - (value['date_der']*1000));
															var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 																
													     	var6 = diffDays;	
														}else{
															var6 = "?";
														}
														if(value['date_next']!=null){
															
															var timeDiff = Math.abs($('#modif_date').datebox('getTheDate').clone().clearTime().getTime() - (value['date_next']*1000));
															var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 													
													     	var7 = diffDays;	
														}else{
															var7 = "?";
														}	
							            			}

							            		});
							            		$.each( data, function( key, value ) {
													if(value['login']==item['login']){
														point_trouve = true;
														var1 = value['login'];
														var2 = value['somme_heure'];
																					
													}																			
												});
							            		var point_trouve2 = false;
							            		var var3 = "";
							            		$.each( item['jour_evi'], function( key, value ) {
													if($('#modif_date').datebox('getTheDate').clone().getDay() == $.parseJSON(ko.toJSON(self.jour_ajoutees))[value]['id_jour']){
														point_trouve2 = true;
														var3 = "erreur";
													}																			
												});
												if(point_trouve2==false){
													var3 = "";
												}
												
												var point_trouve3 = false;
							            		var var4 = "";
							            		$.each( $.parseJSON(ko.toJSON(self.indispo_1)), function( key, value ) {
													if($('#modif_date').datebox('getTheDate').clone().clearTime().getTime() >= value['debut'] && $('#modif_date').datebox('getTheDate').clone().clearTime().getTime() < value['fin'] && value['login']==item['login']){
														point_trouve3 = true;
														var4 = "erreur";
													}																			
												});
												if(point_trouve3==false){
													var4 = "";
												}
												
												var point_trouve4 = false;
							            		var var5 = "";
							            		$.each( $.parseJSON(ko.toJSON(self.membre_lie)), function( key, value ) {
													if( JSON.parse(liste_membre)[$("#liste_membre4")[0].selectedIndex]['login'] == value['login'] ){
														point_trouve4 = true;
														var5 = "erreur";
													}																			
												});
												if(point_trouve4==false){
													var5 = "";
												}
												$.each( $.parseJSON(ko.toJSON(self.membre_lie)), function( key, value ) {
													if( item['login'] == value['login2'] && JSON.parse(liste_membre)[$("#liste_membre4")[0].selectedIndex]['login'] == value['login']){
														point_trouve4 = false;
														var5 = "A choisir";
													}																			
												});

												var point_trouve8 = false;
							            		var var8 = "";
							            		$.each( $.parseJSON(ko.toJSON(self.importance)), function( key, value ) {
							            			if(value['login']==item['login']){
								            			var2 = var2/value['importance'];				            			
														
													}																			
												});
																					
												
												if(point_trouve==true){
													return {login : var1, somme_heure : var2, pb_jour : var3, pb_vac : var4, pb_paire : var5, pb_freq : var6+'/'+var7};
												}else{
													return {login : item['login'], somme_heure : 0, pb_jour : var3, pb_vac : var4, pb_paire : var5, pb_freq: var6+'/'+var7};
												}

							         	  });
							            	detail_point.sort(function(a,b) {

												  // assuming distance is always a valid integer
												  return parseFloat(a.somme_heure) - parseFloat(b.somme_heure)

												});
											 
											self.recup_point3(detail_point);

							            },
							            error: function(obj,text,error) {
							     				                       
							    			$.mobile.loading('hide');	
							    				                    	           
							    			alert("erreur "+obj.status+" "+error+" "+obj.responseText);
							    			if(obj.status=="400"){
							    			document.location.href="index.php";
							    			}
							    		 }	                           
							        });	
						
						// 	});      
		                     			                      								            
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
			 	
			 	self.recherche_point = function() {
			 	//$('#point_date').on('datebox', function(e, p) {
			 	 //  if ( p.method === 'close' ) {
			 		  $.mobile.loading( 'show', {
							textonly : "true",
						    textVisible : "true",
						    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Récupération des points...</h2></span>",
							iconpos : "right",
						    theme: "a"
						             	 
						});
				 		$.ajax({
				 			type: "POST",
				            url: "php/tourdegarde.php?action=recup_point2",
				            dataType: "json",
				            cache: false,
				            data:  {
				 			nb_choix : liste_cat_planning.length,
				 			date_debut : $('#point_date').datebox('callFormat', '%s', $('#point_date').datebox('getTheDate').clone().add(-12).months()),
				 			date_fin : $('#point_date').datebox('callFormat', '%s', $('#point_date').datebox('getTheDate')),
				 			cat_garde : liste_cat_planning[$("#cat_garde")[0].selectedIndex]['valeur'],
				 			recherche_tot_garde : recherche_tot_garde[$("#moment_garde")[0].selectedIndex]['valeur']
				            },	
				            success: function(data){
				            	$.mobile.loading('hide');

				            	self.recup_point2([]);
				            	var detail_point = $.map($.parseJSON(ko.toJSON(self.rotation_ajoute)), function(item,index) {
				            		var point_trouve = false;
				            		var var1 = "";
				            		var var2 = 0;
				            		$.each( data, function( key, value ) {
										if(value['login']==item['login']){
											point_trouve = true;
											var1 = value['login'];
											var2 = value['somme_heure'];
										}																			
									});
									if(point_trouve==true){
										return {login : var1, somme_heure : var2};
									}else{
										return {login : item['login'], somme_heure : 0};
									}

				         	  });
								self.recup_point2(detail_point);

				            },
				            error: function(obj,text,error) {
				     				                       
				    			$.mobile.loading('hide');	
				    				                    	           
				    			alert("erreur "+obj.status+" "+error+" "+obj.responseText);
				    			if(obj.status=="400"){
				    			document.location.href="index.php";
				    			}
				    		 }	                           
				        });	
			
			// 	});
			 	}
			 	
			 	self.imprimer_planning1 = function() { 
			 		$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>création pdf en cours...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
			    	$.ajax({	
	 				    
			        	type: "POST",
			        	url: "php/tourdegarde.php?action=imprimer_planning",
			        	dataType: "json",
			            cache: false,
			            data:  {
			            date_debut : $('#date_debut_imp1').datebox('getTheDate').toString('d/M/yyyy'), 
					    date_fin : $('#date_fin_imp1').datebox('getTheDate').toString('d/M/yyyy'),
					    date_debut2 : $('#date_debut_imp1').datebox('getTheDate').toString('d_M_yyyy'), 
					    date_fin2 : $('#date_fin_imp1').datebox('getTheDate').toString('d_M_yyyy'),
					    date_debut3 : $('#date_debut_imp1').datebox('getTheDate').clearTime().getTime(), 
					    date_fin3 : $('#date_fin_imp1').datebox('getTheDate').clearTime().getTime(), 
					    liste_jour : liste_jour,
					    login:''
				            },	
			            success: function(data){
			            	$.mobile.loading('hide');
			            	window.open('aerogard/'+data);	                      
		                     			                      								            
			            }

			    	});

			 	}
			 	self.imprimer_planning3 = function() { 
			 		$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>création pdf en cours...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
			    	$.ajax({	
	 				    
			        	type: "POST",
			        	url: "php/tourdegarde.php?action=imprimer_planning",
			        	dataType: "json",
			            cache: false,
			            data:  {
			            date_debut : $('#date_debut_imp3').datebox('getTheDate').toString('d/M/yyyy'), 
					    date_fin : $('#date_fin_imp3').datebox('getTheDate').toString('d/M/yyyy'),
					    date_debut2 : $('#date_debut_imp3').datebox('getTheDate').toString('d_M_yyyy'), 
					    date_fin2 : $('#date_fin_imp3').datebox('getTheDate').toString('d_M_yyyy'),
					    date_debut3 : $('#date_debut_imp3').datebox('getTheDate').clearTime().getTime(), 
					    date_fin3 : $('#date_fin_imp3').datebox('getTheDate').clearTime().getTime(), 
					    liste_jour : liste_jour,
					    login:JSON.parse(liste_membre)[$("#liste_membre8")[0].selectedIndex]['login']
				            },	
			            success: function(data){
			            	$.mobile.loading('hide');
			            	window.open('aerogard/'+data);	
			            	$("#mon_popup2").html('');
							   var $popUp = $("#mon_popup2").popup({
							        dismissible: false,
							        theme: "b",
							        overlyaTheme: "e",
							        transition: "pop"
							    }).on("popupafterclose", function () {
							       
							    }).css({
							        'width': '370px',
							            'height': '400px',
							            'padding': '5px'
							    });
							    //create a title for the popup
							    $("<h4/>", {
							        text: "Voulez-vous envoyer ces documents à ce membre par mail?"
							    }).appendTo($popUp);
							  //Create a submit button(fake)
							    $("<a>", {
							        text : "oui"
							    }).buttonMarkup({
							        inline : true,
							        icon : "check"
							    }).bind("click", function() {
							    	$popUp.popup("close");
									    	$.mobile.loading( 'show', {
												textonly : "true",
											    textVisible : "true",
											    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Envoi des documents</h2></span>",
												iconpos : "right",
											    theme: "a"
											             	 
											});
									    	$.ajax({	
							 				    
									        	type: "POST",
									        	url: "php/tourdegarde.php?action=envoi_mail",
									        	dataType: "json",
									            cache: false,
									            data:  {
										        nom : JSON.parse(liste_membre)[$("#liste_membre8")[0].selectedIndex]['login'],
									    		document : data,
									    		adresse : JSON.parse(liste_membre)[$("#liste_membre8")[0].selectedIndex]['mail'],
									    		},	
									            success: function(data2){
										              alert("Mail envoyé");
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

			    	});

			 	}
			 	
			 	self.imprimer_planning2 = function() { 
			 		$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>création pdf en cours...</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
			    	$.ajax({	
	 				    
			        	type: "POST",
			        	url: "php/tourdegarde.php?action=imprimer_planning",
			        	dataType: "json",
			            cache: false,
			            data:  {
			            date_debut : $('#date_debut_imp2').datebox('getTheDate').toString('d/M/yyyy'), 
					    date_fin : $('#date_fin_imp2').datebox('getTheDate').toString('d/M/yyyy'),
					    date_debut2 : $('#date_debut_imp2').datebox('getTheDate').toString('d_M_yyyy'), 
					    date_fin2 : $('#date_fin_imp2').datebox('getTheDate').toString('d_M_yyyy'),
					    date_debut3 : $('#date_debut_imp2').datebox('getTheDate').clearTime().getTime(), 
					    date_fin3 : $('#date_fin_imp2').datebox('getTheDate').clearTime().getTime(), 
					    liste_jour : liste_jour,
					    login:<?php echo json_encode($_SESSION['login2']);?>
				            },	
			            success: function(data){
			            	$.mobile.loading('hide');
			            	window.open('aerogard/'+data);	                      
		                     			                      								            
			            }

			    	});

			 	}

				self.listemembre5_defaut = ko.observable("");
				self.selection_membre5 = function(item, event) {  

				   }
				self.listechoix5_defaut = ko.observable("");
				self.selection_choix5 = function(item, event) {  

				   }
				self.listemembre6_defaut = ko.observable("");
				self.selection_membre6 = function(item, event) {  

				   }
				self.listechoix6_defaut = ko.observable("");
				self.selection_choix6 = function(item, event) {  

				   }   
				if(info_tour[0]){
					 if(!is_int(info_tour[0]['id'])){
						 self.membre_lie = ko.observableArray([]);
					   }else{
						   
						   self.membre_lie = ko.observableArray(JSON.parse(info_tour[0]['liaison']));
					   }
					}else{
						self.membre_lie = ko.observableArray([]);
					}
				
				self.attacher_membre = function(){
					self.membre_lie.push({login : JSON.parse(liste_membre)[$("#liste_membre5")[0].selectedIndex]['login'],
						 cat : liste_cat_planning[$("#liste_choix5")[0].selectedIndex]['nom'],
						  cat_1 : liste_cat_planning[$("#liste_choix5")[0].selectedIndex]['valeur'],
						  login2 : JSON.parse(liste_membre)[$("#liste_membre6")[0].selectedIndex]['login'],
							 cat2 : liste_cat_planning[$("#liste_choix6")[0].selectedIndex]['nom'],
							  cat2_1 : liste_cat_planning[$("#liste_choix6")[0].selectedIndex]['valeur'],				
							   id_select :  self.membre_lie().length});
				
				} 
				// fonction pour la partie paramètre
				self.attacher_membre2 = function(){
					self.membre_lie.push({login : JSON.parse(liste_membre)[$("#liste_membre11")[0].selectedIndex]['login'],
						 cat : liste_cat_planning[$("#liste_choix11")[0].selectedIndex]['nom'],
						  cat_1 : liste_cat_planning[$("#liste_choix11")[0].selectedIndex]['valeur'],
						  login2 : JSON.parse(liste_membre)[$("#liste_membre12")[0].selectedIndex]['login'],
							 cat2 : liste_cat_planning[$("#liste_choix12")[0].selectedIndex]['nom'],
							  cat2_1 : liste_cat_planning[$("#liste_choix12")[0].selectedIndex]['valeur'],				
							   id_select :  self.membre_lie().length});
				
				} 
				self.supr_membre_lie =  function(){
					self.membre_lie.remove(this);
				};			




				self.listemembre7_defaut = ko.observable("");
				self.selection_membre7 = function(item, event) {  

				   }
				self.listemembre8_defaut = ko.observable("");
				self.selection_membre8 = function(item, event) {  

				   }
				self.membre_point = ko.observableArray([]);	
				self.attacher_point = function(){
					var point_trouve = false;
					ko.utils.arrayForEach(self.membre_point(), function(item) {						
						if(item.login==JSON.parse(liste_membre)[$("#liste_membre7")[0].selectedIndex]['login']){
							point_trouve = true;
							self.membre_point.remove(function(item2) { return item2.login == JSON.parse(liste_membre)[$("#liste_membre7")[0].selectedIndex]['login'] }) 
						}
					});
					
					self.membre_point.push({
						login : JSON.parse(liste_membre)[$("#liste_membre7")[0].selectedIndex]['login'],
						point : ko.observable($("#point7").val())
						 });
								 
					ko.utils.arrayForEach(self.recup_point(), function(item) {
						if(item.login==JSON.parse(liste_membre)[$("#liste_membre7")[0].selectedIndex]['login']){
							item.somme_heure = $("#point7").val()
						}
			        });
					ko.utils.arrayForEach(self.membre_rotation(), function(item) {
						if(item.login==JSON.parse(liste_membre)[$("#liste_membre7")[0].selectedIndex]['login']){

							item.point_avant($("#point7").val());	
						}
					});					
					ko.utils.arrayForEach(self.rotation_ajoute(), function(item) {
						if(item.login==JSON.parse(liste_membre)[$("#liste_membre7")[0].selectedIndex]['login']){

							item.points($("#point7").val());	
						}
					});
					
				}
				$('#modifgarde').on('datebox', function(e, p) {
					 if ( p.method === 'close' ) {
						$.mobile.loading( 'show', {
							textonly : "true",
						    textVisible : "true",
						    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Récupération de l'historique du mois...</h2></span>",
							iconpos : "right",
						    theme: "a"
						             	 
						});
						$.ajax({		    
				        	type: "POST",
				            url: "php/tourdegarde.php?action=recup_historique",
				            dataType: "json",
				            cache: false,
				            data:  {
				            date_debut : $('#modifgarde').datebox('callFormat', '%s', $('#modifgarde').datebox('getTheDate').clone().set({ day: 1 })) , date_fin : $('#modifgarde').datebox('callFormat', '%s', $('#modifgarde').datebox('getTheDate').clone().set({ day: 1 }).add({ months: 1 }))   
				            },	
				            success: function(data){
				            	$.mobile.loading('hide');
				            	if($('#calendar3').is(':ui-monthCalendar2')) {
					            	
				            		$('#calendar3').monthCalendar2('destroy');
				            	}			                       
				            	$('#calendar3').monthCalendar2({
				            		shortMonths: list_text.shortMonths,
				            		shortDays:list_text.shortDays,
				            		firstDayOfWeek: -1,
				            		data:$.parseJSON(data),
				            		ma_date: $('#modifgarde').datebox('getTheDate').clone().set({ day: 1 }).clearTime(),
				            		date_cre_deb:"",
				            		date_cre_fin:"",
				            		mode_change: true,
				            		liste:$.parseJSON(ko.toJSON(self.listemembre)),
				            		changement_veto: function(e, ui){ 
				            		    
				            			 $.mobile.loading( 'show', {
				            			 textonly : "true",
				            			 textVisible : "true",
				            		     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Modification en cours...</h2></span>",
				            			 iconpos : "right",
				            			 theme: "a"
				            		   	 });
				            			 $.ajax({	
		            					 		type: "POST",
		            					 		url: "php/tourdegarde.php?action=modif_veto",
		            							dataType: "json",
		            					            cache: false,
		            					            data:  {
		            					            id_garde : ui.id_garde , login : ui.veto   
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

				            		},
				            		rechargement: function(e, ui){ 
				            		    var ma_date2=new Date(ui.date_ref);
				            			 $.mobile.loading( 'show', {
				            			 textonly : "true",
				            			 textVisible : "true",
				            		     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.uploadinprogress+"</h2></span>",
				            			 iconpos : "right",
				            			 theme: "a"
				            		   	 });
				               	     													
				            		        		$.ajax({	
				            					 		type: "POST",
				            					 		url: "php/tourdegarde.php?action=recup_historique",
				            							dataType: "json",
				            					            cache: false,
				            					            data:  {
				            					            date_debut : (ma_date2.clone().set({ day: 1 }).clearTime().getTime()/1000) , date_fin : (ma_date2.clone().set({ day: 1 }).add({ months: 1 }).clearTime().getTime()/1000)   
				            					            },	
				            					        
				            							success: function(data){												
				            							$.mobile.loading('hide');	
				            							$('#calendar3').monthCalendar2('raffraichir', data, ma_date2.getTime());	
				            																   
				            								            
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
				});



				self.save_tour = function() { 
				 	   
				 		  $.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement paramètes...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
					 		$.ajax({
					 			type: "POST",
					            url: "php/tourdegarde.php?action=save_tour",
					            dataType: "json",
					            cache: false,
					            data:  {
					            	horaire : ko.toJSON(self.jour_ajoutees),
					            	participant : ko.toJSON(self.rotation_ajoute),
					            	liaison : ko.toJSON(self.membre_lie),
					            	vacances : ko.toJSON(self.indispo_1),
					            	importance : ko.toJSON(self.importance),
					            	envoi_mail : $('input[name=envoi_mail]:checked').val(),
					            	jour : $('#envoi_mail2').val(),
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


			 	
	};
	ko.applyBindings(new ViewModel());
	
});
function is_int(value){
	  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){ 
	     
	      return true;
	  } else {
	    
	      return false;
	  }
	}
</script>

<ul data-role="listview" data-count-theme="c" data-inset="true">
	
	<?php  if($_SESSION['login2']==$_SESSION['tour']){	
$info_tour2 = json_decode($info_tour, true);
?>
  <li>  
   <div data-role="collapsible" id="tourdegarde">
            <h2>Paramétrer son tour de garde :</h2>
             <ul data-role="listview" data-count-theme="c" data-inset="true">
 				 <li>
 				 <div data-role="collapsible">
 				 <h3>Liste des jours où affecter une équipe</h3>
 				 <fieldset class="ui-grid-c">
       				 <div class="ui-block-a" style="width:25%">
       					 <label for="liste_jour10">Liste des jours travaillés:</label>
						 <select id="liste_jour10" data-bind="value: listejour_defaut, options: listejour, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_jour1 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:30%">
 						 <label for="liste_moment10">horaire où affecter l'équipe</label>
						 <select id="liste_moment10" data-bind="value: listemoment_defaut, options: listemoment, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_moment1 }">     						    
       				 	 </select>
       				 	 <span data-bind="text: moment_commentaire" id="choix_moment10"></span>
       				 </div>
       				 <div class="ui-block-c" style="width:25%">
       				 	 <label for="liste_equipe10">Composition de l'équipe</label>
  						  <select id="liste_equipe10" data-bind="value: listeequipe_defaut, options: listeequipe, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_equipe1 }">     						    
       				 	 </select>
       				 </div>
       				 <div class="ui-block-d" style="width:15%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_jour2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>jour</th><th>moment</th><th>equipe à trouver</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: jour_ajoutees">
        		<tr>
		            <td data-bind="text: nom_jour"></td>
		            <td data-bind="text: moment"></td>
		            <td data-bind="text: team_nom"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_jour_ajoute" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li>
 				 <li>
 				 <div data-role="collapsible">
 				 <h3>Sélectionner les membres exerçant</h3>
 				 <fieldset class="ui-grid-d">
       				 <div class="ui-block-a" style="width:15%">
       					 <label for="liste_membre9">Liste des membres avant sélection</label>
						 <select id="liste_membre9" data-bind="value: listemembre3_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre3 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:25%">
       				 		<label>jours à privilégier :</label>
	      					 <fieldset data-role="controlgroup" data-bind="foreach: jour_ajoutees">
							    <input data-bind="attr: { value: id_select}, checked: $parent.jourfavo2" type="checkbox" data-role="none" data-mini="true" />
							    <label data-role="none">
							        <span data-bind="text: nom_jour+' '+moment"></span>
							    </label>
							</fieldset>
       				 </div>
       				 <div class="ui-block-c" style="width:20%">
      					<label for="listerythme9">A quel rythme :</label>
						 <select id="listerythme9" data-bind="value: listerythme_defaut, options: listerythme, optionsText: 'nom', optionsValue: 'mon_index', event: { change: selection_rythme3 }">     						    
    					</select>
    				 </div> 
    				 <div class="ui-block-d" style="width:25%">
       				 		<label>jours à éviter :</label>
	      					 <fieldset data-role="controlgroup" data-type="horizontal" data-bind="foreach: jour_ajoutees">
							    <input data-bind="attr: { value: id_select}, checked: $parent.jourevi2" type="checkbox" data-role="none" data-mini="true" />
							    <label data-role="none" >
							        <span data-bind="text: nom_jour+' '+moment "></span>
							    </label>
							</fieldset>
       				 </div>
    				 <div class="ui-block-e" style="width:8%">
						<a href="index.html" data-role="button" data-bind="click: ajout_rotation2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
    				  
    				 </div>       				 
       	 		  </fieldset>   
       	 		   <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th style="width:10%">login</th><th>jour à favoriser</th><th>jour à éviter</th><th style="width:5%"></th></tr>
    			</thead>
   				<tbody data-bind="foreach: rotation_ajoute">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="html: jour_favo2"></td>
		            <td data-bind="html: jour_evi2"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_rotation_ajoute" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table>
				</div>
 				 </li>
 				 <li>
 				  <div data-role="collapsible">
 				 <h3>Noter les vacances des membres</h3>
 				 <fieldset class="ui-grid-c">
       				 <div class="ui-block-a">
       					 <label for="liste_membre13">Liste des membres</label>
						 <select id="liste_membre13" data-bind="value: listemembre2_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre_2 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b">
      					 <label for="date_debut13">Date de début indisponilité: </label>
						 <input type="date" data-role="datebox" name="date_debut13" id="date_debut13" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-c">
      					 <label for="date_fin13">Date de fin indisponibilité: </label>
						 <input type="date" data-role="datebox" name="date_fin13" id="date_fin13" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   					 </div>
       				 <div class="ui-block-d">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_indispo2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          		<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>date debut indispo</th><th>date fin indispo</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: indispo_1">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: debut2"></td>
		            <td data-bind="text: fin2"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_indispo_1" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table>
 				 </div>
 				 </li>			 
 				 <li>
       			  <div data-role="collapsible">
 				 <h3>Lier les membres entre eux:</h3>
 				 <fieldset class="ui-grid-d">
       				 <div class="ui-block-a" style="width:20%">
       					 <label for="liste_membre11">Liste des membres</label>
						 <select id="liste_membre11" data-bind="value: listemembre5_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre5 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:10%">      					
       				 	 <label for="liste_choix11">poste</label>
  						  <select id="liste_choix11" data-bind="value: listechoix5_defaut, options: listechoix, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_choix5 }">     						    
       				 	 </select>
       				 </div>       				
       				 <div class="ui-block-c" style="width:20%">
       					 <label for="liste_membre12">Lier à ce membre</label>
						 <select id="liste_membre12" data-bind="value: listemembre6_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre6 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-d" style="width:10%">      					
       				 	 <label for="liste_choix12">poste</label>
  						  <select id="liste_choix12" data-bind="value: listechoix6_defaut, options: listechoix, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_choix6 }">     						    
       				 	 </select>
       				 </div>  
       				 <div class="ui-block-e" style="width:10%">
       				 	  <a href="index.html" data-role="button" data-bind="click: attacher_membre2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">attacher ces membres</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>fonction</th><th>membre lié</th><th>poste</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: membre_lie">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: cat"></td>
		            <td data-bind="text: login2"></td>
		            <td data-bind="text: cat2"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_membre_lie" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li>
 				 
 				 <li>
       			  <div data-role="collapsible">
 				 <h3>Donner du poids à un membre</h3>
 				 <fieldset class="ui-grid-b">
       				 <div class="ui-block-a" style="width:30%">
       					 <label for="liste_membre14">Liste des membres</label>
						 <select id="liste_membre14" data-bind="value: listemembre5_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre5 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:20%">      					
       				 	  <label for="range14">importance :</label>
						<input type="range" name="range14" id="range14" value="1" min="1" max="10" step="1">
       				 </div>       				
       				<div class="ui-block-c" style="width:10%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_importance" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">valider</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>importance</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: importance">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: importance"></td>
		            <td><button data-bind="click: $parent.supr_importance" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li>
			 	<li>
			  		<div data-role="collapsible">
			            <h2>Gestion des mails automatiques</h2>            
			       			<fieldset class="ui-grid-a">
			       				 <div class="ui-block-a" style="width:40%">
			       					<fieldset data-role="controlgroup" data-type="horizontal" >           
							             	<legend>Je veux envoyer des mails automatiques aux membres pour annoncer les gardes</legend>						
							       			<input type="radio" name="envoi_mail" id="radio1" value="0" <?php echo ((count($info_tour2)==0) ? 'checked="checked"' : ($info_tour2[0]['envoi_mail']==0 ? 'checked="checked"' : ''));?> />
							         	<label for="radio1">oui</label>
							
							         	<input type="radio" name="envoi_mail" id="radio2" value="1" <?php echo ((count($info_tour2)==0) ? '' : ($info_tour2[0]['envoi_mail']==0 ? '' : 'checked="checked"'));?> />
							         	<label for="radio2">non</label>      				   				
			       		 			</fieldset>
			       				  </div>
			       				 <div class="ui-block-b" style="width:25%">
			 							<label for="envoi_mail2">Nombre de jour avant la garde :</label>
										<input type="range" name="envoi_mail2" id="envoi_mail2" <?php echo ((count($info_tour2)==0) ? 'value="2"' : 'value="'.$info_tour2[0]['jour'].'"');?> min="1" max="10" step="1">
			       				            
			       				 </div>			       				
			       			 </fieldset>	
			       			 <div id="mon_popup3" data-role="popup"></div>	            
			 		 </div>
			    </li> 				 
 				  <li>
       			    <a name="valider5" id="valider5"  data-bind='click: save_tour' data-role="button">Enregistrer les paramètres</a>
       			  </li>
 				 </ul>
            
  </div>
  </li>
  <li>  
  <div data-role="collapsible" id="tourdegarde">
            <h2>Créer un tour de garde :</h2>
         	 <ul data-role="listview" data-count-theme="c" data-inset="true">
 				 <li class="premier">
 				 <h3>Liste des jours où affecter une équipe</h3>
 				 <fieldset class="ui-grid-c">
       				 <div class="ui-block-a" style="width:25%">
       					 <label for="liste_jour1">Liste des jours travaillés:</label>
						 <select id="liste_jour1" data-bind="value: listejour_defaut, options: listejour, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_jour1 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:30%">
 						 <label for="liste_moment1">horaire où affecter l'équipe</label>
						 <select id="liste_moment1" data-bind="value: listemoment_defaut, options: listemoment, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_moment1 }">     						    
       				 	 </select>
       				 	 <span data-bind="text: moment_commentaire" id="choix_moment"></span>
       				 </div>
       				 <div class="ui-block-c" style="width:25%">
       				 	 <label for="liste_equipe1">Composition de l'équipe</label>
  						  <select id="liste_equipe1" data-bind="value: listeequipe_defaut, options: listeequipe, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_equipe1 }">     						    
       				 	 </select>
       				 </div>
       				 <div class="ui-block-d" style="width:15%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_jour" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>jour</th><th>moment</th><th>equipe à trouver</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: jour_ajoutees">
        		<tr>
		            <td data-bind="text: nom_jour"></td>
		            <td data-bind="text: moment"></td>
		            <td data-bind="text: team_nom"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_jour_ajoute" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table>
 				 
 				 </li>
 				  <li class="premier">
 				 <h3>Période où créer un tour de garde</h3>
 				 <fieldset class="ui-grid-a">
       				 <div class="ui-block-a" style="width:45%">
       					 <label for="date_debut1">Date de début</label>
						 <input type="date" data-role="datebox" name="date_debut1" id="date_debut1" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-b" style="width:45%">
       					 <label for="date_fin1">Date de fin</label>
						 <input type="date" data-role="datebox" name="date_fin1" id="date_fin1" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       			 </fieldset>
       			 </li>
       			 <li class="premier">
       			  <div data-role="collapsible">
       			 <h3>Jours fériés ou particuliers dans la période :</h3>
       			 <fieldset class="ui-grid-c">
       				 <div class="ui-block-a" style="width:25%">
       					 <label for="date_ferie">Date du jour férié</label>
						 <input type="date" data-role="datebox" name="date_ferie" id="date_ferie" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-b" style="width:25%">
 						 <label for="liste_moment_ferie">horaires où affecter l'équipe</label>
						 <select id="liste_moment_ferie" data-bind="value: listemoment_defaut_ferie, options: listemoment, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_moment_ferie1 }">     						    
       				 	 </select>
       				 	 <span data-bind="text: moment_commentaire" id="choix_moment"></span>
       				 </div>
       				 <div class="ui-block-c" style="width:25%">
       				 	 <label for="liste_equipe_ferie">Composition de l'équipe</label>
  						  <select id="liste_equipe_ferie" data-bind="value: listeequipe_defaut_ferie, options: listeequipe, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_equipe_ferie1 }">     						    
       				 	 </select>
       				 </div>
       				 <div class="ui-block-d" style="width:15%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_jour_ferie" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       			 </fieldset>
       			
       			 <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
	          		<thead>
	      			  <tr class="ui-bar-e"><th>jour</th><th>horaire</th><th>equipe à trouver</th><th></th></tr>
	    			</thead>
	   				<tbody data-bind="foreach: jour_ajoutees_ferie">
	        		<tr>
			            <td data-bind="text: date_jour_ferie"></td>
			            <td data-bind="text: moment"></td>
			            <td data-bind="text: team_nom"></td>
			            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_jour_ferie_ajoute" class="ui-shadow ui-btn ui-corner-all">-</button></td>
				    </tr>
				    </tbody>
				</table>
				</div>
       			 </li>
       			 <li class="premier">
       			    <a name="valider1" id="valider1"  data-bind='click: historique_calendrier' data-role="button">Création du calendrier</a>
       			  </li>
       			 <li class="second">
       			 <div id="calendar2"></div>
       			 </li>        			 
       			 <li style="clear:both" class="second">
       			  <div data-role="collapsible">
 				 <h3>Modifier les points d'un membre</h3>
 				 <fieldset class="ui-grid-b">
       				 <div class="ui-block-a" style="width:30%">
       					 <label for="liste_membre7">Liste des membres</label>
						 <select id="liste_membre7" data-bind="value: listemembre7_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre7 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:20%">      					
       				 	 <label for="point7">points</label>
  						 <input type="text" name="point7" id="point7" value="">
       				 </div>       				
       				 <div class="ui-block-c" style="width:20%">
       				 	  <a href="index.html" data-role="button" data-bind="click: attacher_point" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">modifier les points de ce membre</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>point</th></tr>
    			</thead>
   				<tbody data-bind="foreach: membre_point">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: point"></td>
		        </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li> 				     			 
       			 <li style="clear:both" class="second">
       			  <div data-role="collapsible">
 				 <h3>Lier les membres entre eux:</h3>
 				 <fieldset class="ui-grid-d">
       				 <div class="ui-block-a" style="width:20%">
       					 <label for="liste_membre5">Liste des membres</label>
						 <select id="liste_membre5" data-bind="value: listemembre5_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre5 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:10%">      					
       				 	 <label for="liste_choix5">poste</label>
  						  <select id="liste_choix5" data-bind="value: listechoix5_defaut, options: listechoix, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_choix5 }">     						    
       				 	 </select>
       				 </div>       				
       				 <div class="ui-block-c" style="width:20%">
       					 <label for="liste_membre6">Lier à ce membre</label>
						 <select id="liste_membre6" data-bind="value: listemembre6_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre6 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-d" style="width:10%">      					
       				 	 <label for="liste_choix6">poste</label>
  						  <select id="liste_choix6" data-bind="value: listechoix6_defaut, options: listechoix, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_choix6 }">     						    
       				 	 </select>
       				 </div>  
       				 <div class="ui-block-e" style="width:10%">
       				 	  <a href="index.html" data-role="button" data-bind="click: attacher_membre" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">attacher ces membres</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>fonction</th><th>membre lié</th><th>poste</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: membre_lie">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: cat"></td>
		            <td data-bind="text: login2"></td>
		            <td data-bind="text: cat2"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_membre_lie" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li> 					 
       			 <li style="clear:both" class="second">
       			  <div data-role="collapsible">
 				 <h3>Affecter un membre sur une période:</h3>
 				 <fieldset class="ui-grid-e">
       				 <div class="ui-block-a" style="width:20%">
       					 <label for="liste_membre1">Liste des membres</label>
						 <select id="liste_membre1" data-bind="value: listemembre1_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre1 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:15%">
      					 <label for="date_debut2">Date de début: </label>
						 <input type="date" data-role="datebox" name="date_debut2" id="date_debut2" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-c" style="width:15%">
      					 <label for="date_fin2">Date de fin: </label>
						 <input type="date" data-role="datebox" name="date_fin2" id="date_fin2" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   					 </div>
       				 <div class="ui-block-d" style="width:30%">
 						 <label for="liste_moment2">horaire où affecter l'équipe</label>
						 <select id="liste_moment2" data-bind="value: listemoment2_defaut, options: jour_ajoutees, optionsText: function(item){ return item.nom_jour+' '+item.moment }, optionsValue: 'id_select', event: { change: selection_moment2 }">     						    
       				 	 </select>
       				 	 <span data-bind="text: moment_commentaire2" id="choix_moment2"></span>
       				 </div>
       				 <div class="ui-block-e" style="width:10%">
       				 	 <label for="liste_choix1">Role</label>
  						  <select id="liste_choix1" data-bind="value: listechoix_defaut, options: listechoix, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_choix1 }">     						    
       				 	 </select>
       				 </div>
       				 <div class="ui-block-f" style="width:10%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_planning1" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>date debut</th><th>date fin</th><th>catégorie</th><th>horaire</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: ajout_1">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: debut2"></td>
		            <td data-bind="text: fin2"></td>
		            <td data-bind="text: cat"></td>
		            <td data-bind="text: choix_horaire"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_ajout_1" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li> 				 
 				 <li class="second">
 				  <div data-role="collapsible">
 				 <h3>Noter les indisponnibilités d'un membre</h3>
 				 <fieldset class="ui-grid-c">
       				 <div class="ui-block-a">
       					 <label for="liste_membre2">Liste des membres</label>
						 <select id="liste_membre2" data-bind="value: listemembre2_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre_2 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b">
      					 <label for="date_debut3">Date de début indisponilité: </label>
						 <input type="date" data-role="datebox" name="date_debut3" id="date_debut3" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-c">
      					 <label for="date_fin3">Date de fin indisponibilité: </label>
						 <input type="date" data-role="datebox" name="date_fin3" id="date_fin3" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   					 </div>
       				 <div class="ui-block-d">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_indispo1" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>date debut indispo</th><th>date fin indispo</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: indispo_1">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: debut2"></td>
		            <td data-bind="text: fin2"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_indispo_1" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table>
 				 </div>
 				 </li>		 				 
 				 <li class="second">
       			  <div data-role="collapsible">
 				 <h3>Donner du poids à un membre</h3>
 				 <fieldset class="ui-grid-b">
       				 <div class="ui-block-a" style="width:30%">
       					 <label for="liste_membre15">Liste des membres</label>
						 <select id="liste_membre15" data-bind="value: listemembre15_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login'">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:20%">      					
       				 	  <label for="range15">importance :</label>
						<input type="range" name="range15" id="range15" value="1" min="1" max="10" step="1">
       				 </div>       				
       				<div class="ui-block-c" style="width:10%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_importance2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">valider</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       	 		  </fieldset>       	 		  
       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>importance</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: importance">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: importance"></td>
		            <td><button data-bind="click: $parent.supr_importance" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table> 	
				</div>			 
 				 </li> 				 		 
 				  <li class="second">
 				 <h3>Sélectionner le tour qui remplira l'agenda</h3>
 				 <fieldset class="ui-grid-d">
       				 <div class="ui-block-a" style="width:15%">
       					 <label for="liste_membre3">Liste des membres avant sélection</label>
						 <select id="liste_membre3" data-bind="value: listemembre3_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre3 }">     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" style="width:25%">
       				 		<label>jours à privilégier :</label>
	      					 <fieldset data-role="controlgroup" data-bind="foreach: jour_ajoutees">
							    <input data-bind="attr: { value: id_select}, checked: $parent.jourfavo" type="checkbox" data-role="none" data-mini="true" />
							    <label data-role="none">
							        <span data-bind="text: nom_jour+' '+moment"></span>
							    </label>
							</fieldset>
       				 </div>
       				 <div class="ui-block-c" style="width:20%">
      					<label for="listerythme">A quel rythme :</label>
						 <select id="listerythme" data-bind="value: listerythme_defaut, options: listerythme, optionsText: 'nom', optionsValue: 'mon_index', event: { change: selection_rythme3 }">     						    
    					</select>
    				 </div> 
    				 <div class="ui-block-d" style="width:25%">
       				 		<label>jours à éviter :</label>
	      					 <fieldset data-role="controlgroup" data-type="horizontal" data-bind="foreach: jour_ajoutees">
							    <input data-bind="attr: { value: id_select}, checked: $parent.jourevi" type="checkbox" data-role="none" data-mini="true" />
							    <label data-role="none" >
							        <span data-bind="text: nom_jour+' '+moment "></span>
							    </label>
							</fieldset>
       				 </div>
    				 <div class="ui-block-e" style="width:8%">
						<a href="index.html" data-role="button" data-bind="click: ajout_rotation" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
    				 </select> 
    				 </div>       				 
       	 		  </fieldset>   
       	 		   <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th style="width:10%">login</th><th>jour à favoriser</th><th>jour à éviter</th><th style="width:5%"></th></tr>
    			</thead>
   				<tbody data-bind="foreach: rotation_ajoute">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="html: jour_favo2"></td>
		            <td data-bind="html: jour_evi2"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_rotation_ajoute" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table>
				
 				 </li>
 				 <li class="second">
 				 <h3>Récapitulatif activité membre</h3>
 				 <fieldset>
 				 <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
         		<thead>
      			  <tr class="ui-bar-e"><th>login</th><th>points avant</th><th>points après</th></tr>
    			</thead>
   				<tbody data-bind="foreach: computedData">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: point_avant"></td>
		            <td data-bind="text: point_apres"></td>
		        </tr>
			    </tbody>
				</table>
 				 </fieldset>
 				 </li>
 				  <li class="second">
       			    <a name="valider4" id="valider4"  data-bind='click: creer_planning' data-role="button">Créer le planning</a>
       			  </li>
 				  <li class="second">
       			    <a name="valider2" id="valider2"  data-bind='click: creer_document' data-role="button">Afficher les documents</a>
       			  </li>
 				 <li class="second">
       			    <a name="valider3" id="valider3"  data-bind='click: save_planning' data-role="button">Enregistrer le planning</a>
       			 	 <div id="mon_popup1" data-role="popup"></div>
       			  </li>
 				 
 				
 			</ul>           
   </div>
   </li>
    <li>
  <div data-role="collapsible" id="ajoutgarde">
            <h2>Créer garde en fonction date :</h2>
            <fieldset class="ui-grid-d">
       				 <div class="ui-block-a" style="width:15%">
       					 <label for="modif_date">Date où créer une garde</label>
						 <input type="date" data-role="datebox" name="modif_date" id="modif_date" data-options='{"mode":"calbox", "useFocus": true, "useButton": false, "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-b" style="width:25%">
 						 <label for="liste_moment_modif">horaires où affecter l'équipe</label>
						 <select id="liste_moment_modif" data-bind="value: listemoment_modif_defaut, options: listemoment, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_modif }">     						    
       				 	 </select>
       				 	 <span data-bind="text: moment_commentaire_modif" id="moment_commentaire_modif"></span>
       				 </div>
       				 <div class="ui-block-c" style="width:15%">
 						 <label for="liste_choix_modif1">Statut</label>
  						  <select id="liste_choix_modif1" data-bind="value: listechoix_modif_defaut, options: listechoix, optionsText: 'nom', optionsValue: 'valeur', event: { change: selection_modif_choix1 }">     						    
       				 	 </select>       				 	 	
       				 </div>
       				  <div class="ui-block-d" style="width:25%">
 						  <label for="liste_membre4">Liste des membres avant sélection</label>
						 <select id="liste_membre4" data-bind="value: listemembre4_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre4 }">     						    
    					</select>       				  
       				 </div>
       				 <div class="ui-block-e" style="width:15%">
       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_garde" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette garde</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 </div>
       			 </fieldset>
       			 <ul>
				        <li>
				  		<div data-role="collapsible">
				            <h2>choisir les autres membres de l'équipe</h2> 
					             <fieldset class="ui-grid-a">
	       				 			<div class="ui-block-a" style="width:30%">
						          		 <label for="cat_garde2">catégorie</label>
										 <select id="cat_garde2" data-bind="value: cat_garde_defaut, options: liste_cat_planning_obs, optionsText: 'nom', optionsValue: 'valeur'">     						    
						       			 </select>  
					       			 </div> 	       			
					       			<div class="ui-block-b" style="width:10%"> 
	       								 <a href="index.html" data-role="button" data-bind="click: recherche_point2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Rechercher le meilleur membre</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
	       							</div>
	       							</fieldset>
				       			<table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
				         		<thead>
				      			  <tr class="ui-bar-e"><th style="width:10%">login</th><th>points</th><th>Pb jour</th><th>Pb vacances</th><th>Pb paire</th><th>Pb frequence</th><th></th></tr>
				    			</thead>
				   				<tbody data-bind="foreach: recup_point3">
				        		<tr>
						            <td data-bind="text: login"></td>
						            <td data-bind="text: somme_heure"></td>	
						            <td data-bind="text: pb_jour"></td>	
						            <td data-bind="text: pb_vac"></td>
						            <td data-bind="text: pb_paire"></td>
						            <td data-bind="text: pb_freq"></td>		
						            <td><button data-bind="click: $parent.choisir" class="ui-shadow ui-btn ui-corner-all">choisir</button></td>
						                        
						        </tr>
							    </tbody>
								</table>			            
				 		 </div>
				    </li>
       			 
       			 </ul>
            
    </div>
    </li>    
     <li>
  <div data-role="collapsible" id="suprgarde">
            <h2>Supprimer une garde</h2>
            
       					 <label for="supr_date">Date où supprimer la garde</label>
						 <input type="date" data-role="datebox" name="supr_date" id="supr_date"  data-options='{"mode":"calbox", "useFocus": true, "useButton": false}'>             
       			<table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th style="width:10%">login</th><th>catégorie</th><th>date</th><th>heure de début</th><th>heure de fin</th><th style="width:5%"></th></tr>
    			</thead>
   				<tbody data-bind="foreach: creation_tableau">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: nature"></td>
		            <td data-bind="text: ma_date2"></td>
		            <td data-bind="text: start_heure"></td>
		             <td data-bind="text: end_heure"></td>
		            <td><button data-bind="attr: {id: id, value: id}, click: $parent.supr_garde" class="ui-shadow ui-btn ui-corner-all">-</button></td>
			    </tr>
			    </tbody>
				</table>			            
  </div>
    </li>
    <li>	
    <div data-role="collapsible" id="tourdegarde"> 
    <h2>Modifier les gardes</h2> 
    		<ul data-role="listview" data-count-theme="c" data-inset="true">
    		 <li>	            
	             <input type="date" data-role="datebox" name="modifgarde" id="modifgarde"  data-options='{"mode":"calbox", "useFocus": true, "useButton": false}'>             
			</li>	
			<li class="cf"> 
				 <div id="calendar3"></div>
			</li>
			</ul>
	</div>
    </li>
     <li>
  		<div data-role="collapsible" id="voirpoint">
            <h2>Voir les points des membres</h2> 
            <fieldset class="ui-grid-c">
       				 <div class="ui-block-a" style="width:20%">
       					 <label for="cat_garde">catégorie</label>
						 <select id="cat_garde" data-bind="value: cat_garde_defaut2, options: liste_cat_planning_obs, optionsText: 'nom', optionsValue: 'valeur'">     						    
       				 	 </select>       				 </div>
       				 <div class="ui-block-b" style="width:30%">
 						 <label for="moment_garde">Quelle catégorie d'horaire ?</label>
						 <select id="moment_garde" data-bind="value: moment_garde_defaut, options: recherche_tot_garde_obs, optionsText: 'nom', optionsValue: 'valeur'">     						    
       				 	 </select>
       				 </div>  
       				 <div class="ui-block-c" style="width:30%">          
       					 <label for="point_date">A quelle date ?</label>
						<input type="date" data-role="datebox" name="point_date" id="point_date"  data-options='{"mode":"calbox", "useFocus": true, "useButton": false, "showInitialValue": true}'>             
       				</div>
       				<div class="ui-block-d" style="width:10%"> 
       				 <a href="index.html" data-role="button" data-bind="click: recherche_point" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Rechercher les points</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				</div>
       		</fieldset>
       			
       			
       			<table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
         		<thead>
      			  <tr class="ui-bar-e"><th style="width:10%">login</th><th>points</th></tr>
    			</thead>
   				<tbody data-bind="foreach: recup_point2">
        		<tr>
		            <td data-bind="text: login"></td>
		            <td data-bind="text: somme_heure"></td>		            
		        </tr>
			    </tbody>
				</table>			            
 		 </div>
    </li>
     <li>
  		<div data-role="collapsible" id="imprimer_planning1">
            <h2>Imprimer le planning</h2>            
       			<fieldset class="ui-grid-b">
       				 <div class="ui-block-a" style="width:35%">
       					 <label for="date_debut_imp1">Date de début</label>
						 <input type="date" data-role="datebox" name="date_debut_imp1" id="date_debut_imp1" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-b" style="width:35%">
       					 <label for="date_fin_imp1">Date de fin</label>
						 <input type="date" data-role="datebox" name="date_fin_imp1" id="date_fin_imp1" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-c" style="width:20%">
       				 <a href="index.html" data-role="button" data-bind="click: imprimer_planning1" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Imprimer le planning</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 
       				 </div>
       			 </fieldset>		            
 		 </div>
    </li>
    <li>
  		<div data-role="collapsible" id="imprimer_planning2">
            <h2>Imprimer le planning d'un membre</h2>            
       			<fieldset class="ui-grid-c">
       				 <div class="ui-block-a" style="width:30%">
       					 <label for="liste_membre8">membre</label>
						 <select id="liste_membre8" data-bind="value: listemembre8_defaut, options: listemembre, optionsText: 'login', optionsValue: 'login', event: { change: selection_membre8 }">     						    
       				  	</select>
       				  </div>
       				 <div class="ui-block-b" style="width:25%">
       					 <label for="date_debut_imp3">Date de début</label>
						 <input type="date" data-role="datebox" name="date_debut_imp3" id="date_debut_imp3" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-c" style="width:25%">
       					 <label for="date_fin_imp3">Date de fin</label>
						 <input type="date" data-role="datebox" name="date_fin_imp3" id="date_fin_imp3" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-d" style="width:10%">
       				 <a href="index.html" data-role="button" data-bind="click: imprimer_planning3" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Imprimer le planning</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 
       				 </div>
       			 </fieldset>	
       			 <div id="mon_popup2" data-role="popup"></div>	            
 		 </div>
    </li>    
    <?php  }else{?>
    <li>
  		<div data-role="collapsible" id="imprimer_planning2">
            <h2>Imprimer mon planning perso</h2>            
       			<fieldset class="ui-grid-b">
       				 <div class="ui-block-a" style="width:35%">
       					 <label for="date_debut_imp2">Date de début</label>
						 <input type="date" data-role="datebox" name="date_debut_imp2" id="date_debut_imp2" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-b" style="width:35%">
       					 <label for="date_fin_imp2">Date de fin</label>
						 <input type="date" data-role="datebox" name="date_fin_imp2" id="date_fin_imp2" data-options='{"mode": "datebox", "showInitialValue": true}' />              
       				 </div>
       				 <div class="ui-block-c" style="width:20%">
       				 <a href="index.html" data-role="button" data-bind="click: imprimer_planning2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Imprimer le planning</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
       				 
       				 </div>
       			 </fieldset>		            
 		 </div>
    </li>
    <?php }?>
</ul>

<?php render('_footer')?>