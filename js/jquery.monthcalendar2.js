(function($) {

		$.widget('ui.monthCalendar2', (function() {

		return {
			options: {
			ma_date: new Date(),
			shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			firstDayOfWeek: 0,
			data:[],
			planning:[],
			horaire_trouver:[], // json représentant les differents horaires et garde necessaires
			horaire_ferie_trouver:[],
			nb_choix:0, // nombre de catégories (garde, astreinte...)
			liste_cat_planning:[], // les catégories
			date_cre_deb:'',
			date_cre_fin:'',
			ajout_1:[], // membre sur une période
			ajout_2:[], // liste membre préférence jour et indispo jour
			indispo_1:[], // periode d'indisponnibilité des membres
			importance:[],
			espace_garde:5, //espace minimum entre 2 gardes
			rythme:[], // frequence des gardes régulières
			perso:"", // login compte perso
			membre_lie:[],  // array représentant les membres liés
			penalite_fonction:true, // mettre une penalité en fonction garde ou astreinte
			valeur_penalite:2, // valeur de cette penalité
			mode_change:false,
			liste:[]
			},
			_create: function() {  
					var self = this;  
					o = self.options;  
					el = self.element;
					var days = [0, 1, 2, 3, 4, 5, 6];
					self._rotate(days, o.firstDayOfWeek);
					self._creation_calendrier(o.ma_date, days);
			},
			_creation_planning: function() {
				 var renderRow, self = this;
				 o = self.options; 
				 var ajout_1_1 = new Array();
				 var liste_lie = new Array();
				    
				//    console.log("ajout_1 "+JSON.stringify(o.ajout_1));
				//    console.log("ajout_1 "+JSON.stringify(o.horaire_trouver));
				    // creation du planning
				    var date_encours2 = o.date_cre_deb;
				    var mon_planning_ob = new Array();
					var mon_planning = new Array();
					var z=0;	
				//	console.log("date en cours "+o.ma_date.clone().toString('d-MMM-yyyy HH-mm'));
					while(date_encours2.isBefore(o.date_cre_fin)){
				//		console.log("date en cours 1"+date_encours2.clone().toString('d-MMM-yyyy HH-mm'));						
						mon_planning_ob[z]=new Array();
						var test_si_ferie = false;
						// detection si jour ferie
						for (var k = 0; k < o.horaire_ferie_trouver.length; k++) {
							var d=Date.parse(o.horaire_ferie_trouver[k]['date_jour_ferie']);
							if(date_encours2.getTime() == d.getTime()  ){
								mon_planning_ob[z][k]=new Array();
								// le jour est ferie
								test_si_ferie = true;
								// on remplit les objectifs de planning en fonction du nombre de choix
								// z num jour k num horaire jour ferie l choix (0 objectif 1 atteint)
								for (var l = 0; l < o.nb_choix; l++) {
									mon_planning_ob[z][k][l]=new Array();
									if(l==0){
											mon_planning_ob[z][k][l][0]=o.horaire_ferie_trouver[k]['team_g'];
											mon_planning_ob[z][k][l][1]=0;
											
											
									}else if(l==1){
											mon_planning_ob[z][k][l][0]=o.horaire_ferie_trouver[k]['team_a'];
											mon_planning_ob[z][k][l][1]=0;
									}
								}
							}
						}
						// le jour n'est pas ferie
						//if(test_si_ferie == false ){
							
							for (var k = 0; k < ajout_1_1.length; k++) {
								if(ajout_1_1[k][3]==false){
								mon_planning.push([ajout_1_1[k][0], ajout_1_1[k][1] , date_encours2.clone().getTime(), date_encours2.clone().set({ hour: 0 }).getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[ajout_1_1[k][2]]['heure_fin'] }).getTime()])
								}else{
								mon_planning.push([ajout_1_1[k][0], ajout_1_1[k][1] , date_encours2.clone().getTime(), date_encours2.clone().set({ hour: 0 }).getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[ajout_1_1[k][2]]['heure_fin'] }).getTime()])
									}
							
							
							}
							
				
						ajout_1_1 = new Array();
												
						if(test_si_ferie == false){
				//			console.log("passe par la");
							// on remplit les objectifs de planning en fonction du nombre de choix
							// z jour global, k jour de la semaine, l choix asteintevgarde, 0 objectif, 1: resultat actuel
							
							for (var k = 0; k < o.horaire_trouver.length; k++) {
								if(o.horaire_trouver[k]['id_jour']==date_encours2.clone().getDay())
								{
									mon_planning_ob[z][k]=new Array();
									for (var l = 0; l < o.nb_choix; l++) {
										mon_planning_ob[z][k][l]=new Array();
										if(l==0){
												
												mon_planning_ob[z][k][l][0]=o.horaire_trouver[k]['team_g'];
												mon_planning_ob[z][k][l][1]=0;
										}else if(l==1){
												
												mon_planning_ob[z][k][l][0]=o.horaire_trouver[k]['team_a'];
												mon_planning_ob[z][k][l][1]=0;
										}
									}								
									
								}
							}
							
														
							for (var k = 0; k < o.ajout_1.length; k++) {		
								
								if(date_encours2.getTime() >= o.ajout_1[k]['debut'] &&  date_encours2.getTime() <= o.ajout_1[k]['fin']){
									
										if(o.horaire_trouver[o.ajout_1[k]['choix_horaire2']]['id_jour']==date_encours2.clone().getDay())
										{				
											
											if(mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']][o.ajout_1[k]['cat2']][1] < mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']][o.ajout_1[k]['cat2']][0]){
												
												if(o.horaire_trouver[(o.ajout_1[k]['choix_horaire2'])]['nuit']=="non"){
													
												mon_planning.push([o.ajout_1[k]['login'], o.ajout_1[k]['cat2'] , date_encours2.clone().clearTime().getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[o.ajout_1[k]['choix_horaire2']]['heure_debut'] }).getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[o.ajout_1[k]['choix_horaire2']]['heure_fin'] }).getTime()])
													
													}else{
												mon_planning.push([o.ajout_1[k]['login'], o.ajout_1[k]['cat2'] , date_encours2.clone().getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[o.ajout_1[k]['choix_horaire2']]['heure_debut'] }).getTime(), date_encours2.clone().add(1).days().clearTime().getTime()])
													
												ajout_1_1.push([o.ajout_1[k]['login'], o.ajout_1[k]['cat2'], o.ajout_1[k]['choix_horaire2'],false]);									
													}
												mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']][o.ajout_1[k]['cat2']][1]++;
											}
											
										}								
									
								}						
							}
							liste_lie = new Array();
							for (var k = 0; k < o.horaire_trouver.length; k++) {
								if(o.horaire_trouver[k]['id_jour']==date_encours2.clone().getDay())
								{
									
									for (var l = 0; l < o.nb_choix; l++) {
										
										while (mon_planning_ob[z][k][l][1] < mon_planning_ob[z][k][l][0]) {
											
											var cat_garde = l;
										
												if(o.horaire_trouver[k]['nuit']=="non"){
													
												mon_planning.push([this._trouve_un_membre(date_encours2, false, k, liste_lie, l), cat_garde , date_encours2.clone().clearTime().getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[k]['heure_debut'] }).getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[k]['heure_fin'] }).getTime()])
												liste_lie.push([mon_planning[(mon_planning.length-1)][0],l]);
													}else{
														
													var nouveau_choix = this._trouve_un_membre(date_encours2, false, k, liste_lie, l)
												mon_planning.push([nouveau_choix, cat_garde, date_encours2.clone().getTime(), date_encours2.clone().set({ hour: o.horaire_trouver[k]['heure_debut'] }).getTime(), date_encours2.clone().add(1).days().clearTime().getTime()])
												liste_lie.push([mon_planning[(mon_planning.length-1)][0],l]);
												ajout_1_1.push([nouveau_choix, cat_garde, k, false]);									
													}
									
										mon_planning_ob[z][k][l][1]++;
										}//while
										
									}	//for							
									
								}//if
							}//for			
														
						}else{							
										
						//			for (var k = 0; k < o.ajout_1.length; k++) {								
						//		
						//				if(date_encours2.getTime() >= o.ajout_1[k]['debut'] &&  date_encours2.getTime() <= o.ajout_1[k]['fin']){
						//					if(typeof mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']] !== "undefined"){
						//						if(mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']][o.ajout_1[k]['cat2']][1] < mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']][o.ajout_1[k]['cat2']][0]){
						//							if(o.horaire_trouver[(o.ajout_1[k]['choix_horaire2'])]['nuit']=="non"){
						//							
						//						mon_planning.push([o.ajout_1[k]['login'], o.ajout_1[k]['cat2'], date_encours2.clone().clearTime().getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[o.ajout_1[k]['choix_horaire2']]['heure_debut'] }).getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[o.ajout_1[k]['choix_horaire2']]['heure_fin'] }).getTime()])
						//							
						//							}else{
						//						mon_planning.push([o.ajout_1[k]['login'], o.ajout_1[k]['cat2'] , date_encours2.clone().getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[o.ajout_1[k]['choix_horaire2']]['heure_debut'] }).getTime(), date_encours2.clone().add(1).days().clearTime().getTime()])
						//							
						//						ajout_1_1.push([o.ajout_1[k]['login'], o.ajout_1[k]['cat2'], o.ajout_1[k]['choix_horaire2'],true]);									
						//							}
						//						mon_planning_ob[z][o.ajout_1[k]['choix_horaire2']][o.ajout_1[k]['cat2']][1]++;
						//						}// if
						//				  }//if type of
						//				}	//if							
						//				
						//		}//for						
							
						
						for (var k = 0; k < o.horaire_ferie_trouver.length; k++) {
							var d=Date.parse(o.horaire_ferie_trouver[k]['date_jour_ferie']);
							if(date_encours2.getTime() == d.getTime()  ){
								liste_lie = new Array();
								for (var l = 0; l < o.nb_choix; l++) {
									while (mon_planning_ob[z][k][l][1] < mon_planning_ob[z][k][l][0]) {
										var cat_garde = l;
									
											if(o.horaire_ferie_trouver[k]['nuit']=="non"){
											
											mon_planning.push([this._trouve_un_membre(date_encours2, true, k, liste_lie, l), cat_garde , date_encours2.clone().clearTime().getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[k]['heure_debut'] }).getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[k]['heure_fin'] }).getTime()])
											liste_lie.push([mon_planning[(mon_planning.length-1)][0],l]);
												}else{
													
												var nouveau_choix = this._trouve_un_membre(date_encours2, true, k, liste_lie, l)
											mon_planning.push([nouveau_choix, cat_garde, date_encours2.clone().getTime(), date_encours2.clone().set({ hour: o.horaire_ferie_trouver[k]['heure_debut'] }).getTime(), date_encours2.clone().add(1).days().clearTime().getTime()])
											liste_lie.push([mon_planning[(mon_planning.length-1)][0],l]);
											ajout_1_1.push([nouveau_choix, cat_garde, k,true]);									
												}
								
									mon_planning_ob[z][k][l][1]++;
									}//while
									
								}//for	
								
							}// if
								
						}//for
					}
						
						
									
						z++;
						
						date_encours2 = date_encours2.clone().add(1).days(); 
						
					}
					
					
	//			console.log("mon planning "+JSON.stringify(mon_planning));
				o.planning=mon_planning;
				$(this.element).html('');
				
				var days = [0, 1, 2, 3, 4, 5, 6];
				this._rotate(days, o.firstDayOfWeek);
	//			console.log("date envoyée "+o.ma_date);
				this._creation_calendrier(o.ma_date, days);
				var proceed = self._trigger('synthese', null, {
					'leplanning': mon_planning
					});	
				
				
			},
			// si ferie 1 sinon jour normal 2
			_trouve_un_membre: function(date_encours,ferie, id_horaire, liste_lie, ma_cat) {
				 var renderRow, self = this;
				 o = self.options; 
				 var indispo;
				 var dispo;
				 var dispo2;
				 var dispo3;
				 var liste_membre_lie;
				 var ma_penalite;
				// penalité
				 if(o.penalite_fonction==true){
					 ma_penalite = parseFloat(o.valeur_penalite)*parseFloat(ma_cat);
				 }
				// recherche des membres liés
				 liste_membre_lie = $.grep(o.membre_lie, function( n, i ) {
					 var trouve = false;
					 for (var m = 0; m < liste_lie.length; m++) {	
						 if(liste_lie[m][0]!="???"){
							if(n['login']==liste_lie[m][0] && n['cat_1']==liste_lie[m][1] && n['cat2_1']==ma_cat){
								trouve = true;							
							}
						 }
					 }
					 return trouve==true;
				 });
				// recherche des membres indisponnibles
				indispo = $.grep(o.indispo_1, function( n, i ) {
					return ( date_encours.getTime() >=n.debut && date_encours.getTime() <n.fin );
				});
				//console.log("t1 "+JSON.stringify(indispo));
				// recherche des membres dispo
				dispo = $.grep(o.ajout_2, function( n, i ) {
					console.log("login "+n['login']+" date_modif "+n['date_modif']+" rythme_actu"+n['rythme_actu']+" date actu "+date_encours+" objectif "+o.rythme[n['rythme']]['valeur']['base']);
					if(date_encours.clone().add(-7).days().getTime() >= n['date_modif'] && n['rythme_actu']!=1){
						n['date_modif']=date_encours.getTime();
						if(n['rythme_actu']>o.rythme[n['rythme']]['valeur']['base']){
							n['rythme_actu']=1;
							
						}else{
							n['rythme_actu']=parseFloat(n['rythme_actu'])+1;
						}
					}
					
					var trouve = false;
					for (var m = 0; m < indispo.length; m++) {													
						if(indispo[m]['login']==n.login){
							trouve = true;
						}
					}
					if(ferie==false){						
							for (var m = 0; m < n['jour_evi'].length; m++) {
			//					if(o.horaire_trouver[id_horaire]['id_jour']==date_encours.clone().getDay() && n['jour_evi'][m]==o.horaire_trouver[id_horaire]['id_select'])
								if(o.horaire_trouver[id_horaire]['id_jour']==date_encours.clone().getDay() && n['jour_evi'][m]==id_horaire)

								{
									trouve = true;
									}
							}
													
						}
					return trouve==false;
																	
				});
				// on selectionne les membre dispo en fonction liaisons
				dispo3 = $.grep(dispo, function( n, i ) {
					var trouve=false;
					for (var m = 0; m < liste_membre_lie.length; m++) {
						if(n['login']==liste_membre_lie[m]['login2']){
							trouve=true;
						}						
					}
					return trouve==true;
				});
				//console.log("t2 "+JSON.stringify(dispo));
				// recherche dans garde régulière
				dispo2 = $.grep(dispo, function( n, i ) {
					var trouve=false;
					for (var m = 0; m < n['jour_favo'].length; m++) {													
						//console.log("t3 "+o.horaire_trouver[id_horaire]['id_jour']+" _ "+date_encours.clone().getDay()+" * "+n.jour_favo[m]+" _ "+o.horaire_trouver[id_horaire]['id_select']+" * "+n.rythme_actu);
		//			if( o.horaire_trouver[id_horaire]['id_jour']==date_encours.clone().getDay() && Number(+n.jour_favo[m])==o.horaire_trouver[id_horaire]['id_select'] && n.rythme_actu==1){

						if( o.horaire_trouver[id_horaire]['id_jour']==date_encours.clone().getDay() && Number(+n.jour_favo[m])==id_horaire && n.rythme_actu==1){
								trouve=true;
							}					
					}
					return trouve==true;							
				});
				// on verifie avec une distance minimale entre les gardes
				dispo = $.grep(dispo, function( n, i ) {
					var trouve=false;
					console.log("date en cours "+date_encours.clone().add(-(o.espace_garde)).days().getTime()+" date recherchée "+n['date_modif2'])
					if(date_encours.clone().add(-(o.espace_garde)).days().getTime() >= n['date_modif2']){
								trouve=true;
					}else if(n['date_modif2']==o.ma_date.getTime()){
						trouve=true;						
					}					
					
					return trouve==true;							
				});
			//	console.log("t3 "+JSON.stringify(dispo2));
				// on classe tout par ordre croissant
				if(dispo3.length>1){
					dispo3.sort(function(a,b) {

						  // assuming distance is always a valid integer
						  return parseFloat(a.points) - parseFloat(b.points)

						});
					}
				console.log("dispo3 "+JSON.stringify(dispo3));
				if(dispo2.length>1){
					dispo2.sort(function(a,b) {

						  // assuming distance is always a valid integer
						  return parseFloat(a.points) - parseFloat(b.points)

						});
					}
				console.log("dispo2 "+JSON.stringify(dispo2));
				if(dispo.length>1){
				dispo.sort(function(a,b) {

					  // assuming distance is always a valid integer
					  return parseFloat(a.points) - parseFloat(b.points)

					});
				}
				console.log("dispo"+JSON.stringify(dispo));
				if(dispo3.length>0){
					var coef_importance =1;
					$.each( o.importance, function( key, value ) {
						if(dispo3[0]['login'] == value['login'] ){									
							coef_importance = value['importance'] ;
							console.log("ok "+value['login']);
						}								
					});
					for (var k = 0; k < o.ajout_2.length; k++) {
						if(o.ajout_2[k]['id_select']==dispo3[0]['id_select']){
														
							if(ferie==true){
								o.ajout_2[k]['points'] = parseFloat(o.ajout_2[k]['points']) + ((parseFloat(o.horaire_ferie_trouver[id_horaire]['temps']) - parseFloat(ma_penalite))/coef_importance);
								
							}else{
								o.ajout_2[k]['points'] = parseFloat(o.ajout_2[k]['points']) + ((parseFloat(o.horaire_trouver[id_horaire]['temps']) - parseFloat(ma_penalite))/coef_importance);
							}
							o.ajout_2[k]['date_modif']=date_encours.getTime();
						}
					}
					return dispo3[0]['login'];
					
					
				}else if(dispo2.length>0){
					var coef_importance =1;
					$.each( o.importance, function( key, value ) {
						if(dispo2[0]['login'] == value['login'] ){									
							coef_importance = value['importance'] ;
							console.log("ok "+value['login']);
						}								
					});
	//				console.log("resultat 2 :"+dispo2[0]['login']+" rythme_actu "+dispo2[0]['rythme_actu']+"base "+o.rythme[dispo2[0]['rythme']]['valeur']['base'])
					for (var k = 0; k < o.ajout_2.length; k++) {
						if(o.ajout_2[k]['id_select']==dispo2[0]['id_select']){
							
							//if(o.ajout_2[k]['rythme_actu']>o.rythme[o.ajout_2[k]['rythme']]['valeur']['base']){
							//	o.ajout_2[k]['rythme_actu']=1;
								
							//}else{
								o.ajout_2[k]['rythme_actu']=parseFloat(o.ajout_2[k]['rythme_actu'])+1;
							//}
							//o.ajout_2[k]['points']+=o.espace_garde;
							if(ferie==true){
								o.ajout_2[k]['points'] = parseFloat(o.ajout_2[k]['points']) + ((parseFloat(o.horaire_ferie_trouver[id_horaire]['temps']) - parseFloat(ma_penalite))/coef_importance);
								
							}else{
								o.ajout_2[k]['points'] = parseFloat(o.ajout_2[k]['points']) + ((parseFloat(o.horaire_trouver[id_horaire]['temps']) - parseFloat(ma_penalite))/coef_importance);
							}
							o.ajout_2[k]['date_modif']=date_encours.getTime();
						}
					}
					return dispo2[0]['login'];
					
				}else if(dispo.length>0){
					var coef_importance =1;
					$.each( o.importance, function( key, value ) {
						if(dispo[0]['login'] == value['login'] ){									
							coef_importance = value['importance'] ;
							console.log("ok "+value['login']);
						}								
					});
					for (var k = 0; k < o.ajout_2.length; k++) {
						if(o.ajout_2[k]['id_select']==dispo[0]['id_select']){
							
							if(o.ajout_2[k]['rythme_actu']>o.rythme[o.ajout_2[k]['rythme']]['valeur']['base']){
						//		o.ajout_2[k]['rythme_actu']=1;
								
							}else{
						//		o.ajout_2[k]['rythme_actu']+=1;
							}
						//	o.ajout_2[k]['points']+=o.espace_garde;	
							if(ferie==true){
								o.ajout_2[k]['points'] = parseFloat(o.ajout_2[k]['points']) + ((parseFloat(o.horaire_ferie_trouver[id_horaire]['temps']) - parseFloat(ma_penalite))/coef_importance);
								
							}else{
								o.ajout_2[k]['points'] = parseFloat(o.ajout_2[k]['points']) + ((parseFloat(o.horaire_trouver[id_horaire]['temps']) - parseFloat(ma_penalite))/coef_importance);
							}
							o.ajout_2[k]['date_modif2']=date_encours.getTime();
						}
					}					
				return dispo[0]['login'];	
		//		console.log("resultat 1 :"+dispo2[0]['login'])
				}else{
				return "???";					
				}
				
			},
			_creation_calendrier: function(date_encours, ordre_jour) {
			    var renderRow, self = this;
			    o = self.options;  
			    var ajout = new Array();
			   
			    
			    
			    // affichage du calendrier
				
				renderRow = '<div id=\"calcontainer\">';
				renderRow += '<div id=\"calheader\">';
				renderRow += '<h2><input type="button" class="button_pre" />'+o.shortMonths[date_encours.getMonth()]+' '+date_encours.getFullYear()+'<input type="button" class="button_sui" /></h2>';
				renderRow += '</div>';// fin calheader
				renderRow += '<div id=\"daysweek\">';
				for (var j = 0; j < ordre_jour.length; j++) {
					renderRow += '<div class=\"dayweek\"><p>'+o.shortDays[ordre_jour[j]]+'</p></div>';				
					}
				renderRow += '</div>';// fin daysweek  
				renderRow += '<div id=\"daysmonth\">';
				// first add empty block on the first week
				renderRow += '<div class=\"week\">';
				
				for (var i = 0; i < ordre_jour.length; i++) {
						if(date_encours.clone().moveToFirstDayOfMonth().getDay() == ordre_jour[i]){
						 break;
						}else{
							renderRow += '<div class=\"day\">';
							renderRow += '<div class=\"daybar\"><p></p></div>';
							renderRow += '<div class=\"dots\">';
							renderRow += '</div>';
							renderRow += '<div class=\"open2\">';
							renderRow += '</div>';
							renderRow += '</div>';						
						}
				
				}
				
						var date_actu = new Date();
						
		//				console.log("valeur o.data"+JSON.stringify(o.data));
						// avant tous les o.data : JSON.parse(o.data) : maintenant parse au depart 
						
						
						for (var j = 1; j <= Date.getDaysInMonth(date_encours.getFullYear(),date_encours.getMonth()) ; j++) {
							
							renderRow += '<div class=\"day\">';
							if(date_actu.getDate() == j && date_actu.getFullYear()==date_encours.getFullYear() && date_actu.getMonth()==date_encours.getMonth()){
							renderRow += '<div class=\"daybar\"><p class="blue">'+j+'</p></div>';
							
							}else if(o.date_cre_deb !="" && o.date_cre_fin != ""){								
								if(date_encours.clone().set({ day: j }).getTime()>= o.date_cre_deb.getTime() && date_encours.clone().set({ day: j }).getTime()< o.date_cre_fin.getTime()){
								renderRow += '<div class=\"daybar\"><p class="green">'+j+'</p></div>';
								}else{
								renderRow += '<div class=\"daybar\"><p class="yellow">'+j+'</p></div>';
								}
							}else{								
							renderRow += '<div class=\"daybar\"><p class="yellow">'+j+'</p></div>';
							}
							
							renderRow += '<div class=\"dots\">';
								renderRow += '<ul>';
								if(typeof o.data[j] !== "undefined"){
									for (var k = 0; k < o.data[j].length; k++) {
										if(o.data[j][k]['login']=='???'){
											renderRow +='<li id=\"marqueur'+o.data[j][k]['id']+'\" class=\"yellow\"></li>';
										}else if(o.data[j][k]['login'] == o.perso){
											renderRow +='<li id=\"marqueur'+o.data[j][k]['id']+'\" class=\"red\"></li>';
										}										
										else{
											renderRow +='<li id=\"marqueur'+o.data[j][k]['id']+'\" class=\"green\"></li>';
										}								
									}
								}else{
									renderRow +='<li id=\"marqueur'+j+'\" class=\"yellow\"></li>';
								}

		//							if(typeof o.data[j] !== "undefined"){
		//								if(o.data[j]['login']==o.perso){
		//									renderRow +='<li id=\"marqueur'+j+'\" class=\"red\"></li>';
		//								}else if(o.data[j]['login']=='???'){
		//									renderRow +='<li id=\"marqueur'+j+'\" class=\"yellow\"></li>';
		//								}else{
		//									renderRow +='<li id=\"marqueur'+j+'\" class=\"green\"></li>';
		//								}
		//							}else{
		//								renderRow +='<li id=\"marqueur'+j+'\" class=\"yellow\"></li>';
		//							}
		//							if(typeof o.data2[j] !== "undefined"){
		//								if(o.data2[j]['login']==o.perso){
		//								renderRow +='<li id=\"2marqueur'+j+'\" class=\"red\"></li>';
		//							}else if(o.data2[j]['login']=='???'){
		//								renderRow +='<li id=\"2marqueur'+j+'\" class=\"yellow\"></li>';
		//							}else{
		//								renderRow +='<li id=\"2marqueur'+j+'\" class=\"green\"></li>';
		//							}
		//							}else{
		//								renderRow +='<li id=\"2marqueur'+j+'\" class=\"yellow\"></li>';
		//							}
								renderRow += '</ul>';
							renderRow += '</div>'; // fin dots
							renderRow += '<div class=\"open2\">';
					if(o.date_cre_deb =="" && o.date_cre_fin == ""){
							if(typeof o.data[j] !== "undefined"){
								for (var k = 0; k < o.data[j].length; k++) {
								//if(JSON.parse(o.data).j.hasOwnProperty(k)){
								if(typeof o.data[j][k] !== "undefined"){
									
									var dest = "";	
									var nature = Number(o.data[j][k]['nature'])+1;	
									renderRow +='<p id=\"vetochoisi'+j+k+'\">'+nature+': <span id=\"veto_choisi'+o.data[j][k]['id']+'\">'+o.data[j][k]['login']+'</span> '+o.data[j][k]['start_heure']+'h-'+o.data[j][k]['end_heure']+'h</p>';					
																		
										if(o.mode_change==true){							
												renderRow +='<p><select id=\"selection_veto'+o.data[j][k]['id']+'\" name=\"selection_veto'+o.data[j][k]['id']+'\" number=\"'+o.data[j][k]['id']+'\">';
												renderRow += '<option value=\"???\">???</option>';
														$.each(o.liste, function(i, value) {
															renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
														});							
												renderRow += '</select></p>';
												renderRow += '<button id=\"bouton'+o.data[j][k]['id']+'\" number=\"'+o.data[j][k]['id']+'\">ok</button>';					
														
										}
									
									}
							}
						}else{
						renderRow +='<p id=\"veto_absent'+j+'\">???</p>';				
							
						}
						
					}else{
							if(date_encours.clone().set({ day: j }).getTime()< o.date_cre_deb.getTime() || date_encours.clone().set({ day: j }).getTime()> o.date_cre_fin.getTime() ){
									//if(JSON.parse(o.data).hasOwnProperty(j)){
								if(typeof o.data[j] !== "undefined"){
									for (var k = 0; k < o.data[j].length; k++) {
									//if(JSON.parse(o.data).j.hasOwnProperty(k)){
									if(typeof o.data[j][k] !== "undefined"){   
										var dest = "";											
										var nature = Number(o.data[j][k]['nature'])+1;	
										renderRow +='<p id=\"veto_choisi'+j+k+'\">'+dest.concat(nature,": ",o.data[j][k]['login']," ",o.data[j][k]['start_heure'],"h-",o.data[j][k]['end_heure'],"h")+'</p>';					
										}
								}
								}else{
								renderRow +='<p id=\"veto_absent'+j+'\">???</p>';				
									
								}
						}// fin resultat planning creation quand curseur different intervalle planning
						else{
							//if(test_si_ferie == false ){
							//	for (var k = 0; k < ajout.length; k++) {
							//		var dest="";
							//		renderRow +='<p id=\"veto_cherche'+j+ajout[k][1]+'\">'+dest.concat("0h-",o.horaire_trouver[ajout[k][1]]['heure_fin'],"h G:",o.horaire_trouver[ajout[k][1]]['team_g']," A:",o.horaire_trouver[ajout[k][1]]['team_a'])+'</p>';					
									
									
							
							test_si_ferie = false;
							// detection si jour ferie
							for (var k = 0; k < o.horaire_ferie_trouver.length; k++) {
								var d=Date.parse(o.horaire_ferie_trouver[k]['date_jour_ferie']);
								if(date_encours.clone().set({ day: j }).clearTime().getTime() == d.getTime()  ){
									// le jour est ferie
									test_si_ferie = true;
									// on remplit les objectifs de planning en fonction du nombre de choix
									// z num jour k num horaire jour ferie l choix (0 objectif 1 atteint)
									
											var dest="";
											renderRow +='<p id=\"veto_cherche'+j+k+'\">'+dest.concat(o.horaire_ferie_trouver[k]['heure_debut'],"h-",o.horaire_ferie_trouver[k]['heure_fin'],"h G:",o.horaire_ferie_trouver[k]['team_g']," A:",o.horaire_ferie_trouver[k]['team_a'])+'</p>';					
																				
										
									
								}
							}						
							if(test_si_ferie == false){
								// on remplit les objectifs de planning en fonction du nombre de choix
								// z jour global, k jour de la semaine, l choix asteintevgarde, 0 objectif, 1: resultat actuel
								
								for (var k = 0; k < o.horaire_trouver.length; k++) {
									if(o.horaire_trouver[k]['id_jour']==date_encours.clone().set({ day: j }).clearTime().getDay())
									{
										var dest="";
										renderRow +='<p id=\"veto_cherche'+j+k+'\">'+dest.concat(o.horaire_trouver[k]['heure_debut'],"h-",o.horaire_trouver[k]['heure_fin'],"h G:",o.horaire_trouver[k]['team_g']," A:",o.horaire_trouver[k]['team_a'])+'</p>';					
																		
									}
								}
							}
							test_si_ferie = false;						
							
						}
									
									for (var z = 0; z <= o.planning.length ; z++) {
										if(typeof o.planning[z] !== "undefined"){
											if(typeof o.planning[z][2] !== "undefined"){
												if(o.planning[z][2] == date_encours.clone().set({ day: j }).clearTime().getTime()){
													var madate = new Date(o.planning[z][3]);
													var madate2 = new Date(o.planning[z][4]);
													var niveau = o.planning[z][1]+1;
													renderRow +='<p>'+niveau+': '+(typeof o.planning[z][0] !== "undefined" ? o.planning[z][0] : '???' )+'  '+madate.getHours()+'h-'+madate2.getHours()+'h</p>';
												}
											}
										}
									}
								

								
							//	}
								
					//		}else if(test_si_ferie == true){
					//			for (var k = 0; k < ajout.length; k++) {
					//				var dest="";
					//				renderRow +='<p id=\"veto_cherche'+j+ajout[k][1]+'\">'+dest.concat("0h-",o.horaire_ferie_trouver[ajout[k][1]]['heure_fin'],"h G:",o.horaire_ferie_trouver[ajout[k][1]]['team_g']," A:",o.horaire_ferie_trouver[ajout[k][1]]['team_a'])+'</p>';					
					//			}								
					//		}
					//		ajout = new Array();
					//		var test_si_ferie = false;
					//		for (var k = 0; k < o.horaire_ferie_trouver.length; k++) {
					//			var d=Date.parse(o.horaire_ferie_trouver[k]['date_jour_ferie']);
					//			if(date_encours.clone().set({ day: j }).getTime() == d.getTime()  ){
									
					//				if(o.horaire_ferie_trouver[k]['nuit']=="non"){
					//				var dest="";
					//				renderRow +='<p id=\"veto_cherche'+j+k+'\">'+dest.concat(o.horaire_ferie_trouver[k]['heure_debut'],"h-",o.horaire_ferie_trouver[k]['heure_fin'],"h G:",o.horaire_ferie_trouver[k]['team_g']," A:",o.horaire_ferie_trouver[k]['team_a'])+'</p>';					
					//				test_si_ferie = true;
					//				}else{
					//				var dest="";
					//				renderRow +='<p id=\"veto_cherche'+j+k+'\">'+dest.concat(o.horaire_ferie_trouver[k]['heure_debut'],"h-24h G:",o.horaire_ferie_trouver[k]['team_g']," A:",o.horaire_ferie_trouver[k]['team_a'])+'</p>';					
					//				test_si_ferie = true;
					//				ajout.push([j,k]);	
					//				}
					//			}
					//		}
							
					//		for (var k = 0; k < o.horaire_trouver.length; k++) {
					//			if(o.horaire_trouver[k]['id_jour']==date_encours.clone().set({ day: j }).getDay())
					//			{
					//				if(o.horaire_trouver[k]['nuit']=="non"){
					//				var dest="";
					//				renderRow +='<p id=\"veto_cherche'+j+k+'\">'+dest.concat(o.horaire_trouver[k]['heure_debut'],"h-",o.horaire_trouver[k]['heure_fin'],"h G:",o.horaire_trouver[k]['team_g']," A:",o.horaire_trouver[k]['team_a'])+'</p>';					
					//				for (var z = 0; z <= o.planning.length ; z++) {
					//					if(o.planning[z][2] == date_encours.clone().set({ day: j }).clearTime().getTime()){
					//						renderRow +='<p>'+o.planning[z][0]+'</p>';
					//					}
					//				}
					//				}else{
					//				var dest="";
					//				renderRow +='<p id=\"veto_cherche'+j+k+'\">'+dest.concat(o.horaire_trouver[k]['heure_debut'],"h-24h G:",o.horaire_trouver[k]['team_g']," A:",o.horaire_trouver[k]['team_a'])+'</p>';					
					//				ajout.push([j,k]);	
					//				for (var z = 0; z <= o.planning.length ; z++) {
					//					if(o.planning[z][2] == date_encours.clone().set({ day: j }).clearTime().getTime()){
					//						renderRow +='<p>'+o.planning[z][0]+'</p>';
					//					}
					//				}
					//				}		
					//				
					//			}								
					//		}							
						}  //else
				//			renderRow +='<p id=\"veto_choisi'+j+'\">1er :'+(o.data.hasOwnProperty(j) ? o.data[j]['login'] : '???')+'</p>';							
				//			renderRow +='<select id=\"selection_veto'+j+'\" name=\"selection_veto'+j+'\" number=\"'+j+'\">';
				//			renderRow += '<option value=\"???\">???</option>';
				//				$.each(o.liste, function(i, value) {
				//						renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
				//				});							
				//			renderRow += '</select>';
				//			renderRow += '<button id=\"bouton'+j+'\" number=\"'+j+'\">ok</button>';							
				//			renderRow +='<p id=\"veto2_choisi'+j+'\">2d :'+(o.data2.hasOwnProperty(j) ? o.data2[j]['login'] : '???')+'</p>';							
				//			renderRow +='<select id=\"selection2_veto'+j+'\" name=\"selection2_veto'+j+'\" number=\"'+j+'\">';
				//			renderRow += '<option value=\"???\">???</option>';
				//			$.each(o.liste, function(i, value) {
				//					renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
				//			});							
				//			renderRow += '</select>';
				//			renderRow += '<button id=\"2bouton'+j+'\" number=\"'+j+'\">ok</button>';
							renderRow += '</div>'; // fin open2
							renderRow += '</div>'; // fin day
						
						
							if(date_encours.clone().moveToFirstDayOfMonth().addDays(j-1).getDay()==ordre_jour[(ordre_jour.length-1)]){
								renderRow += '</div>'; //fin week
								renderRow += '<div class=\"week\">';
							}						
						}
						
						var comptage_jour = false;
						var nb_jour = 0;
						for (var i = 0; i < ordre_jour.length; i++) {
								if(comptage_jour){
								nb_jour++;
								}
								if(date_encours.clone().moveToLastDayOfMonth().getDay() == ordre_jour[i]){
								comptage_jour = true;								
								}														
						}					
						for (var i = 0; i < nb_jour; i++) {
						
							renderRow += '<div class=\"day\">';
							renderRow += '<div class=\"daybar\"><p></p></div>';
							renderRow += '<div class=\"dots\">';
							renderRow += '</div>';
							renderRow += '<div class=\"open\">';
							renderRow += '</div>';
							renderRow += '</div>';				
				
							}
				
				
				renderRow += '</div>'; // fin week						
				renderRow += '</div>'; // fin daysmonth
				renderRow += '</div>';
				$(renderRow).appendTo($(self.element));
				
				$(self.element).find('.day').addClass("clickable");
				$(self.element).find('.day').hover(function(){window.status = $(this)}, function(){window.status = ""});
				
				$(self.element).find('.open2').hide();
				
				$(self.element).find('.dots').click(
					function() {
						$(this).parents('div:eq(0)').find('.open2').slideToggle('fast');	
					}
				);	
				
				
		//		renderRow +='<p id=\"veto2_choisi'+j+'\">2d :'+(o.data2.hasOwnProperty(j) ? o.data2[j]['login'] : '???')+'</p>';							
		//		renderRow +='<select id=\"selection2_veto'+j+'\" name=\"selection2_veto'+j+'\" number=\"'+j+'\">';
		//		renderRow += '<option value=\"???\">???</option>';
		//			$.each(o.liste, function(i, value) {
		//					renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
		//			});							
		//		renderRow += '</select>';
		//		renderRow += '<button id=\"2bouton'+j+'\" number=\"'+j+'\">ok</button>';
				
		//		$(self.element).find('[id^=2bouton]').click(
		//				function() {
		//				$("#veto2_choisi"+$(this).attr('number')).text($("#selection2_veto"+$(this).attr('number')).val());						
		//				if($("#selection2_veto"+$(this).attr('number')).val()==o.perso){
		//					$("#2marqueur"+$(this).attr('number')).attr('class', 'red');
		//				}else if($("#selection2_veto"+$(this).attr('number')).val()=='???'){
		//					$("#2marqueur"+$(this).attr('number')).attr('class', 'yellow');
		//				}else{
		//					$("#2marqueur"+$(this).attr('number')).attr('class', 'green');
		//					}										
						
		//				var proceed = self._trigger('changement_veto', null, {
		//					'jour': $(this).attr('number'),
		//					'date_depart': o.date,
		//					'veto': $("#selection2_veto"+$(this).attr('number')).val(),
		//					'valeur':2
		//					});
		//				$(this).parents('div:eq(1)').find('.open2').slideToggle('fast');
		//		
		//				}
		//		
		//		);
				
				
				$(self.element).find('[id^=bouton]').click(
						function() {
						//alert($(this).attr('number') );
						//alert($("#selection_veto"+$(this).attr('number')).val());
						$("#veto_choisi"+$(this).attr('number')).text($("#selection_veto"+$(this).attr('number')).val());						
						
					//	if($("#selection_veto"+$(this).attr('number')).val()==o.perso){
					//		$("#marqueur"+$(this).attr('number')).attr('class', 'red');
					//	}else 
						if($("#selection_veto"+$(this).attr('number')).val()=='???'){
							$("#marqueur"+$(this).attr('number')).attr('class', 'yellow');
						}else{
							$("#marqueur"+$(this).attr('number')).attr('class', 'green');
								}										
						
						var proceed = self._trigger('changement_veto', null, {
							'id_garde': $(this).attr('number'),
							'veto': $("#selection_veto"+$(this).attr('number')).val()
							});
						$(this).parents('div:eq(1)').find('.open2').slideToggle('fast');
				
						}
				
				);
				
				$(self.element).find('.button_pre').click(
						function() {
						
								var proceed = self._trigger('rechargement', null, {
									'date_ref': o.ma_date.clone().add({ months: -1 }).getTime()
									});						
						
						}				
				);
				$(self.element).find('.button_sui').click(
						function() {
						
								var proceed = self._trigger('rechargement', null, {
									'date_ref': o.ma_date.clone().add({ months: 1 }).getTime()
									});						
						
						}				
				);
				
				
			},// end fonction _creation_calendrier
			raffraichir: function(donnees,date_centrage) {
				var renderRow, self = this;
			    o = self.options;  
			o.ma_date = new Date(date_centrage);
			o.data = $.parseJSON(donnees);
			
			
			$(this.element).html('');
			
			var days = [0, 1, 2, 3, 4, 5, 6];
			this._rotate(days, o.firstDayOfWeek);
			this._creation_calendrier(o.ma_date, days);

			},
			_destroy: function () 
			{
				this.element.html('');
			},	  
			_rotate: function(a /*array*/, p /* integer, positive integer rotate to the right, negative to the left... */) {
					for (var l = a.length, p = (Math.abs(p) >= l && (p %= l), p < 0 && (p += l), p), i, x; p; p = (Math.ceil(l / p) - 1) * p - l + (l = p)) {
					for (i = l; i > p; x = a[--i], a[i] = a[i - p], a[i - p] = x) {}
					}
					return a;
			},// end function _rotate
			fini: function(choix, ma_liste){
				var renderRow, self = this;
			    o = self.options;
				var proceed = self._trigger('fini', null, {
					
					});	
			},
			ajout_planning: function(choix, ma_liste1, ma_liste2, ma_liste3, ma_liste4, ma_liste5){
				
				var renderRow, self = this;
			    o = self.options; 
			    if(choix==1){
			    o.ajout_1 = ma_liste;
			    self._creation_planning();
			    }else if(choix==2){
				o.indispo_1 = ma_liste;
				self._creation_planning();
				}else if(choix==3){
				o.ajout_2 = ma_liste;
				self._creation_planning();
				}else if(choix==4){
					o.ajout_1 = ma_liste1;
					o.indispo_1 = ma_liste2;
					o.ajout_2 = ma_liste3;
					o.membre_lie = ma_liste4;
					o.importance = ma_liste5;
					self._creation_planning();
				}
			    
			}// end fonction ajout
			
		}; // end of widget function return
		})() //end of widget function closure execution
		);
		
			$.extend($.ui.monthCalendar2, {
				  version: '1.0-dev'
			});

})(jQuery);
