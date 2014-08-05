<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">
function formatCurrency(value) {
    return "€" + value.toFixed(2);
}
function formatCurrency2(value) {
    return String(value).replace(".", ",");
}
$( document ).ready(  function() {
	var refreshTime = 1200000; // in milliseconds, so 20 minutes
    window.setInterval( function() {
    	document.location.href="index.php";
    }, refreshTime );
	var heure_ref = <?php echo $heure_ref;?>;
	var tomorrow = new Date().add(1).day(); 
	var yesterday = new Date().add(-1).day();

	$("#heure_deb_1").html(heure_ref+"h:0"); 
	$("#heure_deb_2").html(heure_ref+"h:0");
	$("#heure_deb_15").html(heure_ref+"h:0");
	$("#heure_fin_15").html(heure_ref+"h:0");
	$("#heure_deb_4").html("00h:00");
	$("#heure_deb_8").html("00h:00"); 
	$("#heure_deb_10").html("00h:00"); 
	$("#heure_deb_13").html(heure_ref+"h:0");
	$("#heure_fin_1").html(heure_ref+"h:0");
	$("#heure_fin_2").html(heure_ref+"h:0");
	$("#heure_fin_4").html("00h:00");
	$("#heure_fin_8").html("00h:00");
	$("#heure_fin_10").html("00h:00");
	$("#heure_fin_13").html(heure_ref+"h:0");
	//$("#date_fin1").val($('#date_fin1').datebox('callFormat', '%d/%m/%Y', tomorrow));
		
	//$('#date_fin1').trigger('datebox', {'method':'set', 'value':$('#date_fin1').datebox('callFormat', '%d/%m/%Y', tomorrow), 'date':new Date($('#date_fin1').datebox('callFormat', '%d/%m/%Y', tomorrow))});
	
	$('#date_debut4').trigger('datebox', {'method':'set', 'value':$('#date_debut4').datebox('callFormat', '%d/%m/%Y', yesterday), 'date':new Date($('#date_debut4').datebox('callFormat', '%d/%m/%Y', yesterday))});
	$('#date_fin4').trigger('datebox', {'method':'set', 'value':$('#date_fin4').datebox('callFormat', '%d/%m/%Y', tomorrow), 'date':new Date($('#date_fin4').datebox('callFormat', '%d/%m/%Y', tomorrow))});

	$('#date_fin8').trigger('datebox', {'method':'set', 'value':$('#date_fin8').datebox('callFormat', '%d/%m/%Y', tomorrow), 'date':new Date($('#date_fin8').datebox('callFormat', '%d/%m/%Y', tomorrow))});
	$('#date_fin10').trigger('datebox', {'method':'set', 'value':$('#date_fin10').datebox('callFormat', '%d/%m/%Y', tomorrow), 'date':new Date($('#date_fin10').datebox('callFormat', '%d/%m/%Y', tomorrow))});
	
	// on masque les paramètres de la recherche de remise
	$(".animation_remise").hide();
	$(".animation_rappel").hide();	
	$(".animation_radio").hide();
	$(".animation_sous_tot").hide();	

	//$('#date_debut1').on('datebox', function (e, passed) {
	//	  if ( passed.method === 'close' ) {
	//		  $("#heure_deb_1").html($(this).datebox('getTheDate').getHours()+"h:"+$(this).datebox('getTheDate').getMinutes());
	//
	//		 }
	//	});
		
	//$("#date_debut4").trigger('datebox', {
     //   'method': 'doset',
     //   'value': yesterday
    //});
	//$("#date_debut4").val($('#date_debut4').datebox('callFormat', '%d/%m/%Y', yesterday));
	//$("#date_fin4").val($('#date_fin4').datebox('callFormat', '%d/%m/%Y', tomorrow));
		
	var texte_rappel = <?php echo json_encode($texte_rappel);?>;
	var info_veto = <?php echo $info_veto;?>;
	var tva = info_veto[0]['tva']/100;
	var data_mot =  <?php echo json_encode($data_mot);?>;
	var liste_vetos = <?php echo $liste_vetos;?>;
	 var pagerOptions = {

			    // target the pager markup - see the HTML block below
			    container: $(".pager"),

			    // use this url format "http:/mydatabase.com?page={page}&size={size}&{sortList:col}"
			    ajaxUrl: null,

			    // modify the url after all processing has been applied
			    customAjaxUrl: function(table, url) { return url; },

			    // process ajax so that the data object is returned along with the total number of rows
			    // example: { "data" : [{ "ID": 1, "Name": "Foo", "Last": "Bar" }], "total_rows" : 100 }
			    ajaxProcessing: function(ajax){
			      if (ajax && ajax.hasOwnProperty('data')) {
			        // return [ "data", "total_rows" ];
			        return [ ajax.total_rows, ajax.data ];
			      }
			    },

			    // output string - default is '{page}/{totalPages}'
			    // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
			    output: '{startRow} to {endRow} ({totalRows})',

			    // apply disabled classname to the pager arrows when the rows at either extreme is visible - default is true
			    updateArrows: true,

			    // starting page of the pager (zero based index)
			    page: 0,

			    // Number of visible rows - default is 10
			    size: 15,

			    // Save pager page & size if the storage script is loaded (requires $.tablesorter.storage in jquery.tablesorter.widgets.js)
			    savePages : true,

			    // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
			    // table row set to a height to compensate; default is false
			    fixedHeight: true,

			    // remove rows from the table to speed up the sort of large tables.
			    // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
			    removeRows: false,

			    // css class names of pager arrows
			    cssNext: '.next', // next page arrow
			    cssPrev: '.prev', // previous page arrow
			    cssFirst: '.first', // go to first page arrow
			    cssLast: '.last', // go to last page arrow
			    cssGoto: '.gotoPage', // select dropdown to allow choosing a page

			    cssPageDisplay: '.pagedisplay', // location of where the "output" is displayed
			    cssPageSize: '.pagesize', // page size selector - select dropdown that sets the "size" option

			    // class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
			    cssDisabled: 'disabled', // Note there is no period "." in front of this class name
			    cssErrorRow: 'tablesorter-errorRow' // ajax error information row

			  };



	  
	$.tablesorter.defaults.widgets = ['zebra']; 
	
	$("#table_brouillard").tablesorter(); 
	$("#table_brouillard").tablesorterPager(pagerOptions);

	        
		//function objet_brouillard_creation(data,index) {
		//	this.valeur = ko.observable("tag"+String(data['importance']));
		//	this.id=ko.observable(data.id);
		//	this.texte=ko.observable(data.texte);
		//};
		function objet_livre_creation(data,index){
				var newDate = new Date(Number(data['date_paiement2']));
			   this.livre_rec_jour = ko.observable(newDate.getDate());
			   this.livre_rec_nom = ko.observable(String(data['nom_p'])+" "+String(data['adresse_p'])+" "+String(data['code_p'])+" "+String(data['ville_p']));
				if(data['mode']=='espece'){
					this.livre_rec_ttc_caisse = ko.observable(data['montant']);
				 	this.livre_rec_ttc_banque = ko.observable("");
				 	this.livre_rec_ttc_virement = ko.observable("");
				}else if(data['mode']=='cheque' || data['mode']=='carte'){
					this.livre_rec_ttc_caisse = ko.observable("");
				 	this.livre_rec_ttc_banque = ko.observable(data['montant']);
				 	this.livre_rec_ttc_virement = ko.observable("");
				}else{
					this.livre_rec_ttc_caisse = ko.observable("");
				 	this.livre_rec_ttc_banque = ko.observable("");
				 	this.livre_rec_ttc_virement = ko.observable(data['montant']);
				}
			   this.livre_rec_ht = ko.observable(data['montant']/(1+tva));	   
			   this.livre_rec_tva = ko.observable(data['montant']-(data['montant']/(1+tva)));
			   

		}
		function objet_reglement_honoraires_creation(data,index){
			this.nom = ko.observable(data['nom']);
			this.base_ht = ko.observable(data['base_ht']);
			this.retribution_acte = ko.observable('<input style="width:100%" type="text" name="hono_acte_'+index+'" id="hono_acte_'+index+'">');
			this.retribution_acte2 = ko.observable(0);
			this.medic_ht = ko.observable(data['medic_ht']);
			this.retribution_medic = ko.observable('<input style="width:100%" type="text" name="hono_medic_'+index+'" id="hono_medic_'+index+'">');
			this.retribution_medic2 = ko.observable(0);
			this.repartition_ht = ko.observable(data['repartition_ht']);
			this.retribution_repartition = ko.observable('<input style="width:100%" type="text" name="hono_repar_'+index+'" id="hono_repar_'+index+'">');
			this.retribution_repartition2 = ko.observable(0);
			this.id_select = ko.observable(index);

		};
		function objet_remise_creation(data,index){
			this.date_paiement = ko.observable(data['date_paiement']);
			this.montant = ko.observable(data['montant']);			
			if(data['numero_cheque']==''){
				this.numero = ko.observable('<input type="text" name="num_cheque'+index+'" id="num_cheque'+index+'">');
				this.numero_valeur = ko.observable('');
			}else{
				this.numero = ko.observable(data['numero_cheque']);
				this.numero_valeur = ko.observable(data['numero_cheque']);
			}			
			this.nom = ko.observable(String(data['nom_p'])+" "+String(data['adresse_p'])+" "+String(data['code_p'])+" "+String(data['ville_p']));
			this.date_consult = ko.observable(data['date_consult']);
			this.id_c = data['id_c'];
			this.url = data['url'];
			this.id_select = index;

		}		
		function objet_remise2(montant, nom, numero, id) {

				this.nom2 = nom;
				this.montant2 = montant;
				this.numero2 = numero;
				this.id_select2 = id;
		}
		function ViewModel() {
		   var self = this;
		   self.liste_brouillard = ko.observableArray([]);	
		   self.liste_brouillard_date = ko.observableArray([]);
		   self.liste_livre = ko.observableArray([]);
		   self.liste_remise = ko.observableArray([]);	
		   self.liste_remise2 = ko.observableArray([]);	
		   self.total_remise = ko.observable(0);
		   self.nb_cheque_remise = ko.observable(0);
		   // recherche remise en fonction numero de remise
		   self.liste_remise3 = ko.observableArray([]);
		   self.liste_remise4 = ko.observableArray([]);		
		   self.remise_id = ko.observable("sélectionnez une remise");
		   self.remise_select = ko.observableArray(['Choose...']);
		   self.remise_selectionne = ko.observable(false);
		   self.liste_rappel = ko.observableArray([]);
		   self.liste_radio = ko.observableArray([]);
		   self.liste_sous_totaux = ko.observableArray([]);
		   self.sous_totaux_def = ko.observable(0);
		   self.liste_vente  = ko.observableArray([]);
		   self.liste_pharmaco = ko.observableArray([]);
		  
		   self.print_brouillard = function(){
			   var liste_brouillard = ko.toJSON(self.liste_brouillard);
			   var liste_brouillard_date = ko.toJSON(self.liste_brouillard_date);
			   $.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du pdf</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
			   $.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=print_brouillard",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			date : liste_brouillard_date, brouillard : liste_brouillard 
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
		   }
		   $("#valider1_1").on("vclick", function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche du brouillard de caisse</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=brouillard",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			debut : $('#date_debut1_1').datebox('callFormat', '%s', $('#date_debut1_1').datebox('getTheDate').clearTime()), fin : $('#date_debut1_1').datebox('callFormat', '%s', $('#date_debut1_1').datebox('getTheDate').clearTime().clone().add(1).day()) 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.liste_brouillard(data);	
		            	self.liste_brouillard_date({date_debut : $('#date_debut1_1').datebox('callFormat', '%d-%m-%y %H-%M', $('#date_debut1_1').datebox('getTheDate').clearTime()) });
		            	var resort = true, // re-apply the current sort
		                callback = function(){
		                  // do something after the updateAll method has completed
		                };

		              // let the plugin know that we made a update, then the plugin will
		              // automatically sort the table based on the header settings
		              $("#table_brouillard").trigger("updateAll", [ resort, callback ]);
		            	 
		            },
		            error: function(obj,text,error) {
	                       
                   	$.mobile.loading('hide');	
                   	           
                       alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                       if(obj.status=="400"){
                       document.location.href="index.php";
                       }
                   }  	                           
		        });
			});
		   $("#valider1").on("vclick", function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche du brouillard de caisse</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=brouillard",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			debut : $('#date_debut1').datebox('callFormat', '%s', $('#date_debut1').datebox('getTheDate').set({ hour: heure_ref })), fin : $('#date_fin1').datebox('callFormat', '%s', $('#date_fin1').datebox('getTheDate').set({ hour: heure_ref })) 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.liste_brouillard(data);
		            	self.liste_brouillard_date({date_debut : $('#date_debut1').datebox('callFormat', '%d-%m-%y %H-%M', $('#date_debut1').datebox('getTheDate').set({ hour: heure_ref })), date_fin : $('#date_fin1').datebox('callFormat', '%d-%m-%y %H%M', $('#date_fin1').datebox('getTheDate').set({ hour: heure_ref })) });	
		            	var resort = true, // re-apply the current sort
		                callback = function(){
		                  // do something after the updateAll method has completed
		                };

		              // let the plugin know that we made a update, then the plugin will
		              // automatically sort the table based on the header settings
		              $("#table_brouillard").trigger("updateAll", [ resort, callback ]);
		            	 
		            },
		            error: function(obj,text,error) {
	                       
                    	$.mobile.loading('hide');	
                    	           
                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                        if(obj.status=="400"){
                        document.location.href="index.php";
                        }
                    }  	                           
		        });
			});


		   $("#valider2").on("vclick", function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des totaux</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=totaux",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			debut : $('#date_debut2').datebox('callFormat', '%s', $('#date_debut2').datebox('getTheDate').set({ hour: heure_ref })), fin : $('#date_fin2').datebox('callFormat', '%s', $('#date_fin2').datebox('getTheDate').set({ hour: heure_ref })) 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.calcul_totaux(data);	
		            	$.mobile.loading( 'show', {
							textonly : "true",
						    textVisible : "true",
						    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche les totaux journaliers</h2></span>",
							iconpos : "right",
						    theme: "a"
						             	 
						});
						$.ajax({		    
				        	type: "POST",
				            url: "php/reglage.php?action=totaux2",
				            dataType: "json",
				            cache: false,
				            data:  {
				 			debut : $('#date_debut2').datebox('callFormat', '%s', $('#date_debut2').datebox('getTheDate').set({ hour: heure_ref })), fin : $('#date_fin2').datebox('callFormat', '%s', $('#date_fin2').datebox('getTheDate').set({ hour: heure_ref })) 
				            },	 
				            success: function(data2){
				            	$.mobile.loading('hide');
				            	self.calcul_totaux2(data2);		            	 
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
			});

		   self.mon_total_ttc = ko.observable(0);
		   self.mon_total_ht = ko.observable(0);
		   self.mon_total_tva = ko.observable(0);
		   self.espece = ko.observable(0);
		   self.carte = ko.observable(0);
		   self.cheque = ko.observable(0);
		   self.virement = ko.observable(0);
		   self.liste_totaux_jour = ko.observableArray([]);


		   self.print_totaux = function(){
			   var liste_totaux_jour = ko.toJSON(self.liste_totaux_jour);
			   $.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du pdf</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
			   $.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=print_totaux",
		            dataType: "json",
		            cache: false,
		            data:  {
			        date_debut : $('#date_debut2').datebox('callFormat', '%d_%m_%Y', $('#date_debut2').datebox('getTheDate').set({ hour: heure_ref })),
				    date_fin : $('#date_fin2').datebox('callFormat', '%d_%m_%Y', $('#date_fin2').datebox('getTheDate').set({ hour: heure_ref })),
		            liste_totaux_jour : liste_totaux_jour,
		 			mon_total_ttc : parseFloat(ko.utils.unwrapObservable(self.mon_total_ttc())).toFixed(2),
		 		    mon_total_ht : parseFloat(ko.utils.unwrapObservable(self.mon_total_ht())).toFixed(2),
		 		    mon_total_tva : parseFloat(ko.utils.unwrapObservable(self.mon_total_tva())).toFixed(2),
		 		    espece : parseFloat(ko.utils.unwrapObservable(self.espece())).toFixed(2),
		 		    carte : parseFloat(ko.utils.unwrapObservable(self.carte())).toFixed(2),
		 		    cheque : parseFloat(ko.utils.unwrapObservable(self.cheque())).toFixed(2),
		 		    virement : parseFloat(ko.utils.unwrapObservable(self.virement())).toFixed(2),
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
		   }		   
		   self.calcul_totaux2 = function(data){	
			//   self.liste_totaux_jour = ko.observableArray([]);	
			   data.sort(function(a,b) {

					  // assuming distance is always a valid integer
					  return parseFloat(a.date2) - parseFloat(b.date2)

					});
			   self.liste_totaux_jour([]);
			   console.log(JSON.stringify(data));	
			   $.each( data, function( key, value ) {
				   var trouve=false;
				   ko.utils.arrayForEach(self.liste_totaux_jour(), function(item) {
						if(ko.utils.unwrapObservable(item.date)==value['date'] ){
								trouve=true;
								var totalttcjour = parseFloat(ko.utils.unwrapObservable(item.totalttc()));
								totalttcjour = Number(totalttcjour) + Number(value['montant']);
								var totalhtjour = parseFloat(ko.utils.unwrapObservable(item.totalht()));
								totalhtjour = Number(totalhtjour) + (Number(value['montant'])/(1+tva));
								var totaltvajour = parseFloat(ko.utils.unwrapObservable(item.totaltva()));
								totaltvajour = Number(totaltvajour) + (Number(value['montant']) - (Number(value['montant'])/(1+tva)));
								item.totalttc(totalttcjour.toFixed(2));
								item.totalht(totalhtjour.toFixed(2));
								item.totaltva(totaltvajour.toFixed(2));
								var totalmodejour = parseFloat(ko.utils.unwrapObservable(item[value['mode']]()));
console.log("totalmodejour "+totalmodejour);
								var totalmodejour = Number(totalmodejour) + Number(value['montant']);
								item[value['mode']](totalmodejour.toFixed(2));
							}
					});
				   if(trouve==false){
					   var totalttcjour = Number(value['montant']).toFixed(2);
					   var totalhtjour = Number(value['montant']/(1+tva)).toFixed(2);
					   var totaltvajour = Number(value['montant'] - (value['montant']/(1+tva))).toFixed(2);					   
					   if(value['mode']=='espece'){
					   self.liste_totaux_jour.push({totalttc : ko.observable(totalttcjour), totalht : ko.observable(totalhtjour), totaltva : ko.observable(totaltvajour), date : ko.observable(value['date']), espece : ko.observable(value['montant']), cheque : ko.observable(0), carte : ko.observable(0), virement : ko.observable(0), id_select :  ko.observable(self.liste_totaux_jour().length)});
					   }else if(value['mode']=='cheque'){
					   self.liste_totaux_jour.push({totalttc : ko.observable(totalttcjour), totalht : ko.observable(totalhtjour), totaltva : ko.observable(totaltvajour), date : ko.observable(value['date']), espece : ko.observable(0), cheque : ko.observable(value['montant']), carte : ko.observable(0), virement : ko.observable(0), id_select :  ko.observable(self.liste_totaux_jour().length)});
					   }else if(value['mode']=='carte'){
					   self.liste_totaux_jour.push({totalttc : ko.observable(totalttcjour), totalht : ko.observable(totalhtjour), totaltva : ko.observable(totaltvajour), date : ko.observable(value['date']), espece : ko.observable(0), cheque : ko.observable(0), carte : ko.observable(value['montant']), virement : ko.observable(0), id_select :  ko.observable(self.liste_totaux_jour().length)});
					   }else if(value['mode']=='virement'){
					   self.liste_totaux_jour.push({totalttc : ko.observable(totalttcjour), totalht : ko.observable(totalhtjour), totaltva : ko.observable(totaltvajour), date : ko.observable(value['date']), espece : ko.observable(0), cheque : ko.observable(0), carte : ko.observable(0), virement : ko.observable(value['montant']), id_select :  ko.observable(self.liste_totaux_jour().length)});
					   }
				   }

			   });
			
		   }
		   self.calcul_totaux = function(data){				

			   var array_espece = $.grep(data, function(value, i) {
				   return ( value['mode'] == 'espece' );
		        });
		      
			   self.espece($.sum(array_espece));
			   var array_carte = $.grep(data, function(value, i) {
				   return (value['mode'] == 'carte' );
		        });
			   self.carte($.sum(array_carte));
			   var array_cheque = $.grep(data, function(value, i) {
				   return (value['mode'] == 'cheque' );
		        });
			   self.cheque($.sum(array_cheque));
			   var array_virement = $.grep(data, function(value, i) {
				   return ( value['mode'] == 'virement' );
		        });
			   self.virement($.sum(array_virement));

			   self.mon_total_ttc(self.espece()+self.carte()+self.cheque()+self.virement());
			   self.mon_total_ht(self.mon_total_ttc()/(1+tva));
			   self.mon_total_tva(self.mon_total_ttc()-self.mon_total_ht());
			   

		   }
		   self.liste_duclient = ko.observableArray([]);
		   
		   self.print_duclient = function(){
			   var liste_duclient = ko.toJSON(self.liste_duclient);
			   $.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du pdf</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
			   $.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=print_duclient",
		            dataType: "json",
		            cache: false,
		            data:  {
			        date_debut : $('#date_debut15').datebox('callFormat', '%d_%m_%Y', $('#date_debut15').datebox('getTheDate').set({ hour: heure_ref })),
				    date_fin : $('#date_fin15').datebox('callFormat', '%d_%m_%Y', $('#date_fin15').datebox('getTheDate').set({ hour: heure_ref })),
				    liste_duclient : liste_duclient,		 			
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
		   }
		   
		   $("#valider15").on("vclick", function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des consultations impayées</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=duclient",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			debut : $('#date_debut15').datebox('callFormat', '%s', $('#date_debut15').datebox('getTheDate').set({ hour: heure_ref })), fin : $('#date_fin15').datebox('callFormat', '%s', $('#date_fin15').datebox('getTheDate').set({ hour: heure_ref })) 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.liste_duclient(data);	          	
				        	            	 
		            },
		            error: function(obj,text,error) {
	                       
                   	$.mobile.loading('hide');	
                   	           
                       alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                       if(obj.status=="400"){
                       document.location.href="index.php";
                       }
                   }  	                           
		        });
			});



			


		   $("#valider3").on("vclick", function(){
			   var date_debut = Date.parse($("#date_debut3").val());			   
			   var date_fin = date_debut;
			  

			   //console.log("date de debut "+date_debut+" date de fin "+date_fin.add(1).months());
			   
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche du livre des recettes</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=brouillard",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			debut : $('#date_debut2').datebox('callFormat', '%s', date_debut), fin : $('#date_debut2').datebox('callFormat', '%s', date_fin.add(1).months()) 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.liste_livre([]);
		    		    var objet_livre = $.map(data, function(item,index) {
		    			   return new objet_livre_creation(item,index);               
		              	});
		    		   self.liste_livre(objet_livre);
            	 
		            },
		            error: function(obj,text,error) {
	                       
                    	$.mobile.loading('hide');	
                    	           
                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                        if(obj.status=="400"){
                        document.location.href="index.php";
                        }
                    }  	                           
		        });
			});	   
		    // section concernant les remises de chèques
		    
		   $("#valider4").on("vclick", function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des chèques à encaisser</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=remise",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			debut : $('#date_debut4').datebox('callFormat', '%s', $('#date_debut4').datebox('getTheDate')), fin : $('#date_fin4').datebox('callFormat', '%s', $('#date_fin4').datebox('getTheDate')) 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.liste_remise([]);
		    		    var objet_remise = $.map(data, function(item,index) {
		    			   return new objet_remise_creation(item,index);               
		              	});
		    		    self.liste_remise(objet_remise);
		            	 
		            },
		            error: function(obj,text,error) {
	                       
                    	$.mobile.loading('hide');	
                    	           
                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                        if(obj.status=="400"){
                        document.location.href="index.php";
                        }
                    }                             
		        });
			});
		  
		   self.add_cheque =  function(){
			   console.log("valeur input "+ $('#num_cheque'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val() );
			   console.log("valeur numero_valeur "+ ko.utils.unwrapObservable(this.numero_valeur) )
			     self.liste_remise2.push(new objet_remise2(this.montant, this.nom, ko.utils.unwrapObservable(this.numero_valeur) == '' ? $('#num_cheque'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val() : this.numero_valeur , self.liste_remise2().length));	
			     self.total_remise(self.total_remise()+parseFloat(ko.utils.unwrapObservable(this.montant)));
			     self.nb_cheque_remise(self.liste_remise2().length);
					self.liste_remise.remove(this);
			};

			self.ajout_remise_manu=  function(){
				 self.total_remise(self.total_remise()+parseFloat($('#montant').val()));
			     self.liste_remise2.push(new objet_remise2($('#montant').val(), $('#client').val(), $('#numero').val(), self.liste_remise2().length));	
			     self.nb_cheque_remise(self.liste_remise2().length);
			};
			self.supr_cheque =  function(){
				self.total_remise(self.total_remise()-parseFloat(ko.utils.unwrapObservable(this.montant2)));
			    self.liste_remise2.remove(this);
			    self.nb_cheque_remise(self.liste_remise2().length);
			};
			$("#valider5").on("vclick", function(){
				var jsonData = ko.toJSON(self.liste_remise2);
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement de la remise</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=save_remise",
		            dataType: "json",
		            cache: false,
		            data:  {
					remise_num : $('#remise_num').val(), liste_remise : jsonData 
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
			});

			$("#valider6").on("vclick", function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche dans la base de données</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=search_remise",
		            dataType: "json",
		            cache: false,
		            data:  {
					remise_num : $('#remise_id').val() 
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.liste_remise3(data);
		            	if(self.liste_remise3().length>0){
		            		self.remise_selectionne(true);
		            		$(".animation_remise").show();
		            	}else{
		            		self.remise_selectionne(false);
		            		$(".animation_remise").hide();
		            		
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
			});
			self.selection_remise = function(item, event) {
				console.log("index selectionne "+$("#choix_remise").prop("selectedIndex"));
				if($("#choix_remise").prop("selectedIndex")!=0){
					self.remise_id('Remise enregistrée le'+String(self.liste_remise3()[$("#choix_remise").prop("selectedIndex")-1]['date'])+' sous la référence: '+
							String(self.liste_remise3()[$("#choix_remise").prop("selectedIndex")-1]['numero_remise']));
					self.liste_remise4(JSON.parse(self.liste_remise3()[$("#choix_remise").prop("selectedIndex")-1]['remise']));
				}				
			}
			self.supr_cheque3 =  function(){
				self.liste_remise4.remove(this);			    
			};

			$("#valider7").on("vclick", function(){
				var jsonData = ko.toJSON(self.liste_remise4);
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Mise à jour de la remise</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=resave_remise",
		            dataType: "json",
		            cache: false,
		            data:  {
					remise_num : self.liste_remise3()[$("#choix_remise").prop("selectedIndex")-1]['id'], liste_remise : jsonData 
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
			});
			 $("#valider8").on("vclick", function(){
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des relances</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/reglage.php?action=rappel",
			            dataType: "json",
			            cache: false,
			            data:  {
			 			debut : $('#date_debut8').datebox('callFormat', '%s', $('#date_debut8').datebox('getTheDate')), fin : $('#date_fin8').datebox('callFormat', '%s', $('#date_fin8').datebox('getTheDate')) 
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	self.liste_rappel(data);	
			            	if(self.liste_rappel().length>0){
			            		$(".animation_rappel").show();
			            	}else{
			            		$(".animation_rappel").hide();
			            		
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
				});
			 self.supr_rappel =  function(){
					self.liste_rappel.remove(this);
					if(self.liste_rappel().length>0){
	            		$(".animation_rappel").show();
	            	}else{
	            		$(".animation_rappel").hide();
	            		
	            	}
									    
				};
			self.client_remise = function(ma_var){ return ma_var.nom_p+' '+ma_var.prenom_p+' '+ma_var.adresse_p+' '+ma_var.code_p+' '+ma_var.ville_p;}
			$("#valider9, #valider9_1").on("vclick", function(){
				console.log("choix "+$(this).data('choix'));
				var jsonData = ko.toJSON(self.liste_rappel);
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du fichier des rappels</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=rappel2",
		            dataType: "json",
		            cache: false,
		            data:  {					
					choix : $(this).data('choix'), data_mot : data_mot, liste_rappel : jsonData, info_veto : info_veto, texte_rappel : texte_rappel, debut : $('#date_debut8').datebox('callFormat', '%d_%m_%Y', $('#date_debut8').datebox('getTheDate')), fin : $('#date_fin8').datebox('callFormat', '%d_%m_%Y', $('#date_fin8').datebox('getTheDate')) 
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
			});		
			 $("#valider10").on("vclick", function(){
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des radiographies réalisées</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/reglage.php?action=radio",
			            dataType: "json",
			            cache: false,
			            data:  {
			 			debut : $('#date_debut10').datebox('callFormat', '%Y_%m_%d', $('#date_debut10').datebox('getTheDate')), fin : $('#date_fin10').datebox('callFormat', '%Y_%m_%d', $('#date_fin10').datebox('getTheDate')) 
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	self.liste_radio(data);	
			            	if(self.liste_radio().length>0){
			            		$(".animation_radio").show();
			            	}else{
			            		$(".animation_radio").hide();
			            		
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
				});	


			 $("#valider11").on("vclick", function(){
				var mon_array = new Array();
				var date_debut = Date.today().moveToFirstDayOfMonth();
				var date_fin = new Date(date_debut).add(1).months();
				var date_debut2 = new Date(date_debut).add(-12).months();
				var date_fin2 = new Date(date_fin).add(-12).months();				
				for (var i=11;i>=0;i--)
				{ 					
					mon_array.push(new Array(new Date(date_debut).add(-i).months().getTime(), new Date(date_fin).add(-i).months().getTime(),new Date(date_debut2).add(-i).months().getTime(), new Date(date_fin2).add(-i).months().getTime() ));
				}
				console.log("mon_array"+JSON.stringify(mon_array));
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche dans la base de données</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
				$.ajax({		    
		        	type: "POST",
		            url: "php/reglage.php?action=stat",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			mes_dates : mon_array 
		            },	 
		            success: function(data){
		            	console.log("mon_array"+JSON.stringify(data));
		            	$.mobile.loading('hide');
		            	Morris.Line({
		            		  element: 'graphique_line',
		            		  data: data,
		            		  xkey: 'mois',
		            		  ykeys: ['ca_a', 'ca_b'],
		            		  labels: [date_debut.getFullYear(), date_debut2.getFullYear()]
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
					
				});	
			 self.liste_reglement_honoraires = ko.observableArray([]);
			 self.liste_liste_honoraires = ko.observableArray([]);
			 
			 $("#valider16").on("vclick", function(){
				 $.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des consultations effectuées</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/reglage.php?action=paiement_veto",
			            dataType: "json",
			            cache: false,
			            data:  {
			 			debut : $('#date_debut16').datebox('callFormat', '%s', $('#date_debut16').datebox('getTheDate').set({ hour: 0 })),
			 			fin : $('#date_fin16').datebox('callFormat', '%s', $('#date_fin16').datebox('getTheDate').set({ hour: 0 })),
			 			debut2 : $('#date_debut16').datebox('callFormat', '%d/%m/%Y', $('#date_debut16').datebox('getTheDate').set({ hour: 0 })),
			 			fin2 : $('#date_fin16').datebox('callFormat', '%d/%m/%Y', $('#date_fin16').datebox('getTheDate').set({ hour: 0 })),
			 			liste_vetos : liste_vetos,
			 			tva : tva
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	self.liste_reglement_honoraires([]);
			    		    var objet_reglement_honoraires = $.map(data, function(item,index) {
			    			   return new objet_reglement_honoraires_creation(item,index);               
			              	});
			    		    self.liste_reglement_honoraires(objet_reglement_honoraires);
			    		           	
					        	            	 
			            },
			            error: function(obj,text,error) {
		                       
	                   	$.mobile.loading('hide');	
	                   	           
	                       alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                       if(obj.status=="400"){
	                       document.location.href="index.php";
	                       }
	                   }  	                           
			        });				

			 });
			 self.valid_paiement =  function(){
				 for (var i = 0, j = self.liste_reglement_honoraires().length; i < j; i++) {
						
	 				 if(parseFloat(ko.utils.unwrapObservable(this.id_select))==parseFloat(ko.utils.unwrapObservable(self.liste_reglement_honoraires()[i].id_select))){
	
	  	 				self.liste_reglement_honoraires()[i]['retribution_acte2']($('#hono_acte_'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val());
	  	 				self.liste_reglement_honoraires()[i]['retribution_medic2']($('#hono_medic_'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val());
	  	 				self.liste_reglement_honoraires()[i]['retribution_repartition2']($('#hono_repar_'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val());

	  	 				var liste_reglement_honoraires = ko.toJSON(self.liste_reglement_honoraires()[i]);
	  				   $.mobile.loading( 'show', {
	  						textonly : "true",
	  					    textVisible : "true",
	  					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Création du pdf</h2></span>",
	  						iconpos : "right",
	  					    theme: "a"
	  					             	 
	  					});
	  				   $.ajax({		    
	  			        	type: "POST",
	  			            url: "php/reglage.php?action=print_reglement_veto",
	  			            dataType: "json",
	  			            cache: false,
	  			            data:  {
	  			            	debut : $('#date_debut16').datebox('callFormat', '%s', $('#date_debut16').datebox('getTheDate').set({ hour: 0 })),
	  				 			fin : $('#date_fin16').datebox('callFormat', '%s', $('#date_fin16').datebox('getTheDate').set({ hour: 0 })),
	  				 			debut2 : $('#date_debut16').datebox('callFormat', '%d/%m/%Y', $('#date_debut16').datebox('getTheDate').set({ hour: 0 })),
	  				 			fin2 : $('#date_fin16').datebox('callFormat', '%d/%m/%Y', $('#date_fin16').datebox('getTheDate').set({ hour: 0 })),
	  			                liste_reglement_honoraires : liste_reglement_honoraires,
	  			                iteration : i,	 			
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

		  	 			  
	  	 			  }
	 			  }	
			 }
			 $("#valider12").on("vclick", function(){

				 var startdate = $('#date_debut12').datebox('getTheDate'),
				 startdate2 = $('#heure_debut12').datebox('getTheDate'),
				 final_start_date = new Date(startdate.getFullYear(), startdate.getMonth(), startdate.getDate(), startdate2.getHours(), startdate2.getMinutes(), startdate.getSeconds(),0);

				 var enddate = $('#date_fin12').datebox('getTheDate'),
				 enddate2 = $('#heure_fin12').datebox('getTheDate'),
				 final_end_date = new Date(enddate.getFullYear(), enddate.getMonth(), enddate.getDate(), enddate2.getHours(), enddate2.getMinutes(), enddate.getSeconds(),0);
				 

						 $.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des sous-totaux</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
							$.ajax({		    
					        	type: "POST",
					            url: "php/reglage.php?action=sous_tot",
					            dataType: "json",
					            cache: false,
					            data:  {
					 			debut : final_start_date.getTime(), fin : final_end_date.getTime(), liste_vetos : liste_vetos, tva : tva 
					            },	 
					            success: function(data){
					            	$.mobile.loading('hide');
					            	self.liste_sous_totaux(data);	
					            	self.sous_totaux_def("Recherche du "+$('#date_debut12').datebox('callFormat', '%d/%m/%Y %k:%M', final_start_date)+" au "+$('#date_fin12').datebox('callFormat', '%d/%m/%Y %k:%M', final_end_date));
					            	if(self.liste_sous_totaux().length>0){
					            		$(".animation_sous_tot").show();
					            	}else{
					            		$(".animation_sous_tot").hide();
					            		
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
					
				});	
			 $("#valider13").on("vclick", function(){
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des médicaments vendus</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/reglage.php?action=vente",
			            dataType: "json",
			            cache: false,
			            data:  {
			 			debut : $('#date_debut13').datebox('callFormat', '%s', $('#date_debut13').datebox('getTheDate').set({ hour: heure_ref })), fin : $('#date_fin13').datebox('callFormat', '%s', $('#date_fin13').datebox('getTheDate').set({ hour: heure_ref })) 
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	self.liste_vente(data);	
			            	
			            	 
			            },
			            error: function(obj,text,error) {
		                       
	                    	$.mobile.loading('hide');	
	                    	           
	                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                        if(obj.status=="400"){
	                        document.location.href="index.php";
	                        }
	                    }  	                           
			        });
				});	
			 $("#valider14").on("vclick", function(){
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche du client</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/reglage.php?action=pharmaco",
			            dataType: "json",
			            cache: false,
			            data:  {
						lot : $('#pharmaco').val() 
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	self.liste_pharmaco(data);			            	
			            	 
			            },
			            error: function(obj,text,error) {
		                       
	                    	$.mobile.loading('hide');	
	                    	           
	                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                        if(obj.status=="400"){
	                        document.location.href="index.php";
	                        }
	                    }  	                           
			        });
				});
			self.url = function(){
				window.open("index.php?id_consultation="+this.id_c);
			}
			self.url2 = function(){
				window.open("/aerogard/sauvegarde/animaux/"+this.animal+"/facture_"+this.id+".pdf");
			}	
			self.veto_print = function(){

				 var startdate = $('#date_debut12').datebox('getTheDate'),
				 startdate2 = $('#heure_debut12').datebox('getTheDate'),
				 final_start_date = new Date(startdate.getFullYear(), startdate.getMonth(), startdate.getDate(), startdate2.getHours(), startdate2.getMinutes(), startdate.getSeconds(),0);

				 var enddate = $('#date_fin12').datebox('getTheDate'),
				 enddate2 = $('#heure_fin12').datebox('getTheDate'),
				 final_end_date = new Date(enddate.getFullYear(), enddate.getMonth(), enddate.getDate(), enddate2.getHours(), enddate2.getMinutes(), enddate.getSeconds(),0);

				var nom_veto = this.sous_tot_nom; 
			
			$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des consultations du veterinaire</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
			$.ajax({		    
	        	type: "POST",
	            url: "php/reglage.php?action=print_consult",
	            dataType: "json",
	            cache: false,
	            data:  {
				veto : this.sous_tot_nom, debut : final_start_date.getTime(), fin : final_end_date.getTime()
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
			}
			self.veto_consult = function(){

				 var startdate = $('#date_debut12').datebox('getTheDate'),
				 startdate2 = $('#heure_debut12').datebox('getTheDate'),
				 final_start_date = new Date(startdate.getFullYear(), startdate.getMonth(), startdate.getDate(), startdate2.getHours(), startdate2.getMinutes(), startdate.getSeconds(),0);

				 var enddate = $('#date_fin12').datebox('getTheDate'),
				 enddate2 = $('#heure_fin12').datebox('getTheDate'),
				 final_end_date = new Date(enddate.getFullYear(), enddate.getMonth(), enddate.getDate(), enddate2.getHours(), enddate2.getMinutes(), enddate.getSeconds(),0);

				var nom_veto = this.sous_tot_nom; 
			
			$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des consultations du veterinaire</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
			$.ajax({		    
	        	type: "POST",
	            url: "php/reglage.php?action=recherche_consult",
	            dataType: "json",
	            cache: false,
	            data:  {
				veto : this.sous_tot_nom, debut : final_start_date.getTime(), fin : final_end_date.getTime()
	            },	 
	            success: function(data){
	            	$.mobile.loading('hide');
	            	$("#ma_liste_consult").html('');
			    	 var $popup_liste_consult = $("#ma_liste_consult").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					       
					    }).css({
					        'width': "800px",
					        'height': "600px",
					        'padding': '5px'
					    });
					    //create a title for the popup
					    $("<h3/>", {
					        text: "Règlements et consultations du Dr "+nom_veto+" sur la période du : "+$('#date_debut12').datebox('callFormat', '%d/%m/%Y %k:%M', final_start_date)+" au "+$('#date_fin12').datebox('callFormat', '%d/%m/%Y %k:%M', final_end_date)
					    }).appendTo($popup_liste_consult);

					    var consult_veto='';
						$.each( data, function( key, value ) {
							consult_veto+='<tr><td>'+value['date_consult']+'</td><td>'+value['date_paiement']+'</td><td>'+value['nom_p']+
							'</td><td>'+value['nom_a']+'</td><td>'+value['montant']+'</td><td>'+value['mode']+'</td><td><a href="'+value['url']+'" data-role="button" rel="external">'+value['id_c']+'</a></td></tr>';
								}
							);
						 var consult_a_afficher = $('<table data-role="table" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">'+ 
								 '<thead><tr><th style="width:10%">Date c</th><th style="width:10%">Date p</th><th>Nom client</th><th>Nom ani</th><th>montant</th><th>mode</th><th>N° consult</th>'+
								 '</thead><tbody>'+consult_veto+'</tbody></table>').appendTo( $popup_liste_consult );						
						//create a back button
						    $("<a>", {
						        text: "Back",
						            "data-rel": "back"
						    }).buttonMarkup({
						        inline: false,
						        mini: true,
						        theme: "e",
						        icon: "back"
						    }).appendTo($popup_liste_consult);
						   
						$popup_liste_consult.popup('open').trigger("create");			            	
	            	 
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




			self.veto_consult2 = function(){

				 var startdate = $('#date_debut12').datebox('getTheDate'),
				 startdate2 = $('#heure_debut12').datebox('getTheDate'),
				 final_start_date = new Date(startdate.getFullYear(), startdate.getMonth(), startdate.getDate(), startdate2.getHours(), startdate2.getMinutes(), startdate.getSeconds(),0);

				 var enddate = $('#date_fin12').datebox('getTheDate'),
				 enddate2 = $('#heure_fin12').datebox('getTheDate'),
				 final_end_date = new Date(enddate.getFullYear(), enddate.getMonth(), enddate.getDate(), enddate2.getHours(), enddate2.getMinutes(), enddate.getSeconds(),0);

				var nom_veto = this.sous_tot_nom; 
			
			$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des paiements reçu par le veterinaire</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
			$.ajax({		    
	        	type: "POST",
	            url: "php/reglage.php?action=recherche_consult2",
	            dataType: "json",
	            cache: false,
	            data:  {
				veto : this.sous_tot_nom, debut : final_start_date.getTime(), fin : final_end_date.getTime()
	            },	 
	            success: function(data){
	            	$.mobile.loading('hide');
	            	$("#ma_liste_consult").html('');
			    	 var $popup_liste_consult = $("#ma_liste_consult").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					       
					    }).css({
					      //  'width': "800px",
					      // 'height': "600px",
					        'padding': '5px'
					    });
					    //create a title for the popup
					    $("<h3/>", {
					        text: "Règlements reçus par le Dr "+nom_veto+" sur la période du : "+$('#date_debut12').datebox('callFormat', '%d/%m/%Y %k:%M', final_start_date)+" au "+$('#date_fin12').datebox('callFormat', '%d/%m/%Y %k:%M', final_end_date)
					    }).appendTo($popup_liste_consult);

					    var consult_veto='';
						$.each( data, function( key, value ) {
							consult_veto+='<tr><td>'+value['date_consult']+'</td><td>'+value['date_paiement']+'</td><td>'+value['permission2']+'</td><td>'+value['nom_p']+
							'</td><td>'+value['nom_a']+'</td><td>'+value['montant']+'</td><td>'+value['mode']+'</td><td><a href="'+value['url']+'" data-role="button" rel="external">'+value['id_c']+'</a></td></tr>';
								}
							);
						 var consult_a_afficher = $('<table data-role="table" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">'+ 
								 '<thead><tr><th style="width:10%">Date c</th><th style="width:10%">Date p</th><th>veto</th><th>Nom client</th><th>Nom ani</th><th>montant</th><th>mode</th><th>N° consult</th>'+
								 '</thead><tbody>'+consult_veto+'</tbody></table>').appendTo( $popup_liste_consult );						
						//create a back button
						    $("<a>", {
						        text: "Back",
						            "data-rel": "back"
						    }).buttonMarkup({
						        inline: false,
						        mini: true,
						        theme: "e",
						        icon: "back"
						    }).appendTo($popup_liste_consult);
						   
						$popup_liste_consult.popup('open').trigger("create");			            	
	            	 
	            },
	            error: function(obj,text,error) {
                    
                	$.mobile.loading('hide');	
                	           
                    alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                    if(obj.status=="400"){
                    document.location.href="index.php";
                    }
                }  	                           
	        });
			}//	end veto_consult2
			self.veto_consult3 = function(){

				 var startdate = $('#date_debut12').datebox('getTheDate'),
				 startdate2 = $('#heure_debut12').datebox('getTheDate'),
				 final_start_date = new Date(startdate.getFullYear(), startdate.getMonth(), startdate.getDate(), startdate2.getHours(), startdate2.getMinutes(), startdate.getSeconds(),0);

				 var enddate = $('#date_fin12').datebox('getTheDate'),
				 enddate2 = $('#heure_fin12').datebox('getTheDate'),
				 final_end_date = new Date(enddate.getFullYear(), enddate.getMonth(), enddate.getDate(), enddate2.getHours(), enddate2.getMinutes(), enddate.getSeconds(),0);

				var nom_veto = this.sous_tot_nom; 
			
			$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des consultations effectuées par le veterinaire</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
			$.ajax({		    
	        	type: "POST",
	            url: "php/reglage.php?action=recherche_consult3",
	            dataType: "json",
	            cache: false,
	            data:  {
				veto : this.sous_tot_nom, debut : final_start_date.getTime(), fin : final_end_date.getTime()
	            },	 
	            success: function(data){
	            	$.mobile.loading('hide');
	            	$("#ma_liste_consult").html('');
			    	 var $popup_liste_consult = $("#ma_liste_consult").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					       
					    }).css({
					       // 'width': "800px",
					        'padding': '5px'
					    });
					    //create a title for the popup
					    $("<h3/>", {
					        text: "Liste des consultations du Dr "+nom_veto+" sur la période du : "+$('#date_debut12').datebox('callFormat', '%d/%m/%Y %k:%M', final_start_date)+" au "+$('#date_fin12').datebox('callFormat', '%d/%m/%Y %k:%M', final_end_date)
					    }).appendTo($popup_liste_consult);

					    var consult_veto='';
						$.each( data, function( key, value ) {
							consult_veto+='<tr><td>'+value['date_consult']+'</td><td>'+value['permission2']+'</td><td>'+value['nom_p']+
							'</td><td>'+value['nom_a']+'</td><td>'+value['totalttc']+'</td><td>'+value['total_acte']+'</td><td>'+value['reglement_acte']+'</td><td><a href="'+value['url']+'" data-role="button" rel="external">'+value['id_c']+'</a></td></tr>';
								}
							);
						 var consult_a_afficher = $('<table data-role="table" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">'+ 
								 '<thead><tr><th style="width:10%">Date c</th><th>veto</th><th>Nom client</th><th>Nom ani</th><th>Total ttc</th><th>total acte</th><th>règlement acte</th><th>N° consult</th>'+
								 '</thead><tbody>'+consult_veto+'</tbody></table>').appendTo( $popup_liste_consult );						
						//create a back button
						    $("<a>", {
						        text: "Back",
						            "data-rel": "back"
						    }).buttonMarkup({
						        inline: false,
						        mini: true,
						        theme: "e",
						        icon: "back"
						    }).appendTo($popup_liste_consult);
						   
						$popup_liste_consult.popup('open').trigger("create");			            	
	            	 
	            },
	            error: function(obj,text,error) {
                    
                	$.mobile.loading('hide');	
                	           
                    alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                    if(obj.status=="400"){
                    document.location.href="index.php";
                    }
                }  	                           
	        });
			}//	end veto_consult3


			self.veto_consult4 = function(){

				 var startdate = $('#date_debut12').datebox('getTheDate'),
				 startdate2 = $('#heure_debut12').datebox('getTheDate'),
				 final_start_date = new Date(startdate.getFullYear(), startdate.getMonth(), startdate.getDate(), startdate2.getHours(), startdate2.getMinutes(), startdate.getSeconds(),0);

				 var enddate = $('#date_fin12').datebox('getTheDate'),
				 enddate2 = $('#heure_fin12').datebox('getTheDate'),
				 final_end_date = new Date(enddate.getFullYear(), enddate.getMonth(), enddate.getDate(), enddate2.getHours(), enddate2.getMinutes(), enddate.getSeconds(),0);

				var nom_veto = this.sous_tot_nom; 
			
			$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Recherche des consultations effectuées par le veterinaire</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
			$.ajax({		    
	        	type: "POST",
	            url: "php/reglage.php?action=recherche_consult4",
	            dataType: "json",
	            cache: false,
	            data:  {
				veto : this.sous_tot_nom, debut : final_start_date.getTime(), fin : final_end_date.getTime()
	            },	 
	            success: function(data){
	            	$.mobile.loading('hide');
	            	$("#ma_liste_consult").html('');
			    	 var $popup_liste_consult = $("#ma_liste_consult").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					       
					    }).css({
					       // 'width': "800px",
					        'padding': '5px'
					    });
					    //create a title for the popup
					    $("<h3/>", {
					        text: "Liste des consultations avec honoaires partagés du Dr "+nom_veto+" sur la période du : "+$('#date_debut12').datebox('callFormat', '%d/%m/%Y %k:%M', final_start_date)+" au "+$('#date_fin12').datebox('callFormat', '%d/%m/%Y %k:%M', final_end_date)
					    }).appendTo($popup_liste_consult);

					    var consult_veto='';
						$.each( data, function( key, value ) {
							consult_veto+='<tr><td>'+value['date_consult']+'</td><td>'+value['permission2']+'</td><td>'+value['veto_desti']+
							'</td><td>'+value['montant']+'</td><td>'+value['total_acte']+'</td><td>'+value['reglement_acte']+'</td><td><a href="'+value['url']+'" data-role="button" rel="external">'+value['id_c']+'</a></td></tr>';
								}
							);
						 var consult_a_afficher = $('<table data-role="table" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">'+ 
								 '<thead><tr><th style="width:10%">Date c</th><th>veto responsable</th><th>veto destinataire</th><th>Total repartition</th><th>total acte</th><th>règlement acte</th><th>N° consult</th>'+
								 '</thead><tbody>'+consult_veto+'</tbody></table>').appendTo( $popup_liste_consult );						
						//create a back button
						    $("<a>", {
						        text: "Back",
						            "data-rel": "back"
						    }).buttonMarkup({
						        inline: false,
						        mini: true,
						        theme: "e",
						        icon: "back"
						    }).appendTo($popup_liste_consult);
						   
						$popup_liste_consult.popup('open').trigger("create");			            	
	            	 
	            },
	            error: function(obj,text,error) {
                    
                	$.mobile.loading('hide');	
                	           
                    alert("erreur "+obj.status+" "+error+" "+obj.responseText);
                    if(obj.status=="400"){
                    document.location.href="index.php";
                    }
                }  	                           
	        });
			}//	end veto_consult4


						
		};
		ko.applyBindings(new ViewModel());
		

		$.sum = function(arr) {
		    var r = 0;
		    $.each(arr, function(i, v) {
		        r += Number(v['montant']);
		    });
		    return r;
		}
$("#date_debut3").on('change', function(){
$arr = $(this).val().split(",");
$(this).val(liste_mois[$arr[0]]+' '+liste_annee[$arr[1]]);
});

$('#copy_sous_tot').click(function(){
	  var element = document.getElementById('table_sous_totaux');
	  if (document.body.createTextRange) { // ms
	    var range = document.body.createTextRange();
	    range.moveToElementText(element);
	    range.select();
	  } else if (window.getSelection) { // moz, opera, webkit
	    var selection = window.getSelection();
	    var range = document.createRange();
	    range.selectNodeContents(element);
	    selection.removeAllRanges();
	    selection.addRange(range);
	  }
	});	
$('#selectionner10').click(function(){
	  var element = document.getElementById('table_radio');
	  if (document.body.createTextRange) { // ms
	    var range = document.body.createTextRange();
	    range.moveToElementText(element);
	    range.select();
	  } else if (window.getSelection) { // moz, opera, webkit
	    var selection = window.getSelection();
	    var range = document.createRange();
	    range.selectNodeContents(element);
	    selection.removeAllRanges();
	    selection.addRange(range);
	  }
	});		
$('#copy_livre_recette').click(function(){
	  var element = document.getElementById('table_livre');
	  if (document.body.createTextRange) { // ms
	    var range = document.body.createTextRange();
	    range.moveToElementText(element);
	    range.select();
	  } else if (window.getSelection) { // moz, opera, webkit
	    var selection = window.getSelection();
	    var range = document.createRange();
	    range.selectNodeContents(element);
	    selection.removeAllRanges();
	    selection.addRange(range);
	  }
	});
$('#copy_remise').click(function(){
	  var element = document.getElementById('table_remise2');
	  if (document.body.createTextRange) { // ms
	    var range = document.body.createTextRange();
	    range.moveToElementText(element);
	    range.select();
	  } else if (window.getSelection) { // moz, opera, webkit
	    var selection = window.getSelection();
	    var range = document.createRange();
	    range.selectNodeContents(element);
	    selection.removeAllRanges();
	    selection.addRange(range);
	  }
	});
$('#copy_remise2').click(function(){
	  var element = document.getElementById('table_remise3');
	  if (document.body.createTextRange) { // ms
	    var range = document.body.createTextRange();
	    range.moveToElementText(element);
	    range.select();
	  } else if (window.getSelection) { // moz, opera, webkit
	    var selection = window.getSelection();
	    var range = document.createRange();
	    range.selectNodeContents(element);
	    selection.removeAllRanges();
	    selection.addRange(range);
	  }
	});
});
var liste_mois = ["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"];
var liste_mois2 = ['January','February','March','April','May','June','July','August','September','October','November','December'];
var liste_annee = [2012,2013,2014,2015,2016,2009,2010,2011];
jQuery.extend(jQuery.mobile.datebox.prototype.options, {
    'customData': [
      {'input': true, 'name':'mois', 'data':liste_mois},
      {'input': true, 'name':'annee', 'data':liste_annee}
    ],
    'useNewStyle': true,
    'overrideStyleClass': 'ui-icon-dice'
  });
</script>
<?php $info_veto2 = json_decode($info_veto, true);?>
<section class="nouveauclient cf">
<legend>Interface de gestion de l'établissement :<b><?php echo $info_veto2[0]['nom']."</b> : ".$info_veto2[0]['adresse']." ".$info_veto2[0]['code']." ".$info_veto2[0]['commune']; ?></legend>
<ul data-role="listview" data-count-theme="c" data-inset="true">
  <li>
  <div data-role="collapsible" id="brouillard">
            <h2>Brouillard de caisse :</h2>
	<fieldset class="ui-grid-b">
        <div class="ui-block-a">
      			 <label for="date_debut1">Date de début: <p id="heure_deb_1"></p></label>
				 <input type="date" data-role="datebox" name="date_debut1" id="date_debut1" data-options='{"mode": "datebox", "showInitialValue": true}' />              
        </div>
        <div class="ui-block-b">
      			 <label for="date_fin1">Date de fin: <p id="heure_fin_1"></p></label>
				 <input type="date" data-role="datebox" name="date_fin1" id="date_fin1" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   		 </div>
   		 <div class="ui-block-c">
        		 <a name="valider1" id="valider1" data-role="button">Rechercher</a>        	
   		 </div>
   	</fieldset>
   	<fieldset class="ui-grid-a">
        <div class="ui-block-a" style="width:66%">
      			 <label for="date_debut1_1">Recherche journalière: Date de recherche : deb-0h<p id="heure_deb_1"></p></label>
				 <input type="date" data-role="datebox" name="date_debut1_1" id="date_debut1_1" data-options='{"mode": "datebox", "showInitialValue": true}' />              
        </div>
        <div class="ui-block-b" style="width:33%">
        		 <a name="valider1_1" id="valider1_1" data-role="button">Rechercher</a>        	
   		</div>   		 
   	</fieldset>
   	<a name="printbrouillard" id="printbrouillard" data-role="button" data-bind='click: print_brouillard'>Imprimer</a>
   	<div id="pager" class="pager">
		  <form>
		    <img src="image/icons/first.png" class="first"/>
		    <img src="image/icons/prev.png" class="prev"/>
		    <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
		    <img src="image/icons/next.png" class="next"/>
		    <img src="image/icons/last.png" class="last"/>
		  </form>
	</div>
   	<table data-role="table" id="table_brouillard" name="table_brouillard" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
       
    			<thead>
      			  <tr><th>date paiement</th><th>Montant</th><th>Mode</th><th>Nom</th><th>Prenom</th><th>Date consult</th><th>Animal</th></tr>
    			</thead>
   				<tbody data-bind="foreach: liste_brouillard">
		        <tr>
		            <td data-bind="text: date_paiement"></td>
		            <td data-bind="text: montant"></td>
		            <td data-bind="text: mode"></td>
		            <td><a data-bind="text: nom_p, click: $parent.url, attr: {href: url} "></a></td>
		            <td data-bind="text: prenom_p"></td>
		            <td data-bind="text: date_consult"></td>
		            <td data-bind="text: nom_a"></td>		            
		        </tr>
		    </tbody>
		</table>
		</div>
    </li>   
    <li>
  <div data-role="collapsible" id="totaux">
            <h2>totaux, tva, ht :</h2>
	<fieldset class="ui-grid-b">
        <div class="ui-block-a">
      			 <label for="date_debut2">Date de début :<p id="heure_deb_2"></p></label>
				 <input type="date" data-role="datebox" name="date_debut2" id="date_debut2" data-options='{"mode": "datebox", "showInitialValue": true}' />              
        </div>
        <div class="ui-block-b">
      			 <label for="date_fin2">Date de fin :<p id="heure_fin_2"></p></label>
				 <input type="date" data-role="datebox" name="date_fin2" id="date_fin2" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   		 </div>
   		 <div class="ui-block-c">
        		 <a name="valider2" id="valider2" data-role="button">Rechercher</a>           		      	
   		 </div>
   	</fieldset>
   	<a name="printtotaux" id="printtotaux" data-role="button" data-bind='click: print_totaux'>Imprimer</a>
   	
   	<table data-role="table" id="table_totaux" name="table_totaux" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
       
    			<thead>
      			  <tr><th>total ttc</th><th>total ht</th><th>tva</th><th>espece</th><th>cheque</th><th>carte</th><th>virement</th></tr>
    			</thead>
   				<tbody>
		        <tr>
		            <td data-bind='text: formatCurrency(mon_total_ttc())'></td>
		            <td data-bind="text: formatCurrency(mon_total_ht())"></td>
		            <td data-bind="text: formatCurrency(mon_total_tva())"></td>
		            <td data-bind="text: formatCurrency(espece())"></td>
		            <td data-bind="text: formatCurrency(cheque())"></td>
		            <td data-bind="text: formatCurrency(carte())"></td>
		            <td data-bind="text: formatCurrency(virement())"></td>		            
		        </tr>
		    </tbody>
		</table>
		
		
			<table data-role="table" id="table_totaux2" name="table_totaux2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
       
    			<thead>
      			  <tr><th>date</th><th>total ttc</th><th>total ht</th><th>total tva</th><th>espece</th><th>cheque</th><th>carte</th><th>virement</th></tr>
    			</thead>
   				<tbody data-bind="foreach: liste_totaux_jour">
		        <tr>
		            <td data-bind='text: date'></td>
		            <td data-bind="text: totalttc"></td>
		            <td data-bind="text: totalht"></td>
		            <td data-bind="text: totaltva"></td>
		            <td data-bind="text: espece"></td>
		            <td data-bind="text: cheque"></td>
		            <td data-bind="text: carte"></td>	
		            <td data-bind="text: virement"></td>		            
		        </tr>
		    </tbody>
		</table>
		</div>
    </li>    
     <li>
  <div data-role="collapsible" id="livre_recette">
            <h2>livre des recettes :</h2>
		<fieldset class="ui-grid-a">
	        <div class="ui-block-a">
	      			 <label for="date_debut3">Sélectionner le mois :</label>
					 <input type="date" data-role="datebox" name="date_debut3" id="date_debut3" data-options='{"mode": "custombox"}' />              
	        </div>
	        <div class="ui-block-c">
	       			 <a name="valider3" id="valider3" data-role="button">Rechercher</a>
	        		 <a name="copy_livre_recette" id="copy_livre_recette" data-role="button">Selectionner le livre des recettes</a>        	
	   		 </div>
	   	</fieldset>
	   <table data-role="table" id="table_livre" name="table_livre" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
	       
	    			<thead>
	    			<tr><th style="width:5%">Jour</th><th style="width:20%">nom client</th><th style="width:5%">Caisse</th><th style="width:5%">Banque</th><th style="width:4%">Vir</th><th style="width:25%">tva</th><th style="width:25%">ht</th></tr>
	    			</thead>
	   				<tbody data-bind="foreach: liste_livre">
			        <tr>
			            <td data-bind="text: livre_rec_jour"></td>
			            <td data-bind="text: livre_rec_nom"></td>
			            <td data-bind="text: formatCurrency2(livre_rec_ttc_caisse())"></td>
			            <td data-bind="text: formatCurrency2(livre_rec_ttc_banque())"></td>
			            <td data-bind="text: formatCurrency2(livre_rec_ttc_virement())"></td>
			            <td data-bind="text: formatCurrency2(livre_rec_tva())"></td>
			            <td data-bind="text: formatCurrency2(livre_rec_ht())"></td>		            
			        </tr>
			    </tbody>
			</table>
			</div>
    </li>    
    <li>
  <div data-role="collapsible" id="duclient">
            <h2>consultations non réglées :</h2>
	<fieldset class="ui-grid-b">
        <div class="ui-block-a">
      			 <label for="date_debut15">Date de début :<p id="heure_deb_15"></p></label>
				 <input type="date" data-role="datebox" name="date_debut15" id="date_debut15" data-options='{"mode": "datebox", "showInitialValue": true}' />              
        </div>
        <div class="ui-block-b">
      			 <label for="date_fin15">Date de fin :<p id="heure_fin_15"></p></label>
				 <input type="date" data-role="datebox" name="date_fin15" id="date_fin15" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   		 </div>
   		 <div class="ui-block-c">
        		 <a name="valider15" id="valider15" data-role="button">Rechercher</a>           		      	
   		 </div>
   	</fieldset>
   	<label>Seul les impayés supérieurs à 1 euro apparaissent dans cette liste.</label>
   	<a name="printduclient" id="printduclient" data-role="button" data-bind='click: print_duclient'>Imprimer</a>
   	
   	<table data-role="table" id="table_duclient" name="table_duclient" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
       
    			<thead>
      			  <tr><th>date</th><th>nom</th><th>montant réglé</th><th>montant dû</th><th>consultation</th><th>facture</th></tr>
    			</thead>
   				<tbody data-bind="foreach: liste_duclient">
		        <tr>
		            <td data-bind='text: date'></td>
		            <td data-bind="text: nom"></td>
		            <td data-bind="text: montant_r"></td>
		            <td data-bind="text: montant_d"></td>
		            <td class="color_bleu" data-bind="text: id_c, click: $parent.url, attr: {href: url} "></td>	
		            <td data-bind="click: $parent.url2, attr: {href: url2} "><a>facture</a></td>		          		            
		        </tr>
		    </tbody>
		</table>		
		</div>
    </li>        
 <li>
  <div data-role="collapsible" id="id_remise">
            <h2>dépot des chèques :</h2>
            
            
            <ul data-role="listview" data-count-theme="c" data-inset="true">
  					<li>
  				<div data-role="collapsible" id="liste_des_cheques">
           			 <h2>Liste des cheques :</h2>
						<fieldset class="ui-grid-b">
				        <div class="ui-block-a">
				      			 <label for="date_debut4">Date de début :<p id="heure_deb_4"></p></label>
								 <input type="date" data-role="datebox" name="date_debut4" id="date_debut4" data-options='{"mode": "datebox", "showInitialValue": true}' />              
				        </div>
				        <div class="ui-block-b">
				      			 <label for="date_fin1">Date de fin :<p id="heure_fin_4"></p></label>
								 <input type="date" data-role="datebox" name="date_fin4" id="date_fin4" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
				   		 </div>
				   		 <div class="ui-block-c">
				        		 <a name="valider4" id="valider4" data-role="button">Rechercher</a>        	
				   		 </div>
				   		</fieldset>
					   	<table data-role="table" id="table_remise" name="table_remise" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
				       
				    			<thead>
				      			  <tr><th>date paiement</th><th>Montant</th><th>numero</th><th>nom</th><th>Date consult</th><th>Validation</th></tr>
				    			</thead>
				   				<tbody data-bind="foreach: liste_remise">
						        <tr>
						            <td data-bind="text: date_paiement"></td>
						            <td data-bind="text: montant"></td>
						            <td data-bind="html: numero()"></td>
						            <td><a data-bind="text: nom, click: $parent.url, attr: {href: url}"></a></td>
						            <td data-bind="text: date_consult"></td>
						            <td><button data-bind="attr: {id: id_select}, click: $parent.add_cheque" class="ui-shadow ui-btn ui-corner-all">+</button></td>		            
						        </tr>
						    </tbody>
						</table>
						</div>
						</li>
						<li>
						<div data-role="collapsible" id="ajout_cheque_manu">
           			 		<h2>Ajout manuel :</h2>
						<fieldset class="ui-grid-c">
				        	<div class="ui-block-a">
				        	<label for="montant">Montant:</label>
							<input type="text" name="montant" id="montant">				        	
				        	</div>
				        	<div class="ui-block-b">
				        	<label for="numero">Numero cheque:</label>
							<input type="text" name="numero" id="numero">				        	
				        	</div>
				        	<div class="ui-block-c">
				        	<label for="client">Identité client:</label>
							<input type="text" name="client" id="client">				        	
				        	</div>
				        	<div class="ui-block-d">
				        	<a href="" data-role="button" data-bind="click: ajout_remise_manu" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette remise</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		        	
				        	</div>
				        </fieldset>
				        </div>
				        </li>
				        <li>
						<div data-role="collapsible" id="ajout_cheque_manu">
           			 		<h2>Remise de cheque :</h2>
		           			 		<fieldset class="ui-grid-b">
			           			 	 <div class="ui-block-a">
							      			<label for="total_rem">total de la remise:</label>
											<span name="total_rem" id="total_rem" data-bind="html: total_remise"></span>
											<label for="nb_cheque">nombre de cheque:</label>
											<span name="nb_cheque" id="nb_cheque" data-bind="html: nb_cheque_remise"></span>
							      	</div>	
							        <div class="ui-block-b">							      			
											<label for="remise_num">Identifiant remise:</label>
											<input type="text" name="remise_num" id="remise_num">		
							      	</div>					       
							        <div class="ui-block-c">
							      			 <a name="copy_remise" id="copy_remise" data-role="button">Sélectionner</a>
							        		 <a name="valider5" id="valider5" data-role="button">enregistrer dans la base</a>        	
							   		 </div>
						   		</fieldset>
							   	<table data-role="table" id="table_remise2" name="table_remise2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
						       
						    			<thead>
						      			  <tr><th>montant</th><th>client</th><th>numero</th><th>effacer</th></tr>
						    			</thead>
						   				<tbody data-bind="foreach: liste_remise2">
								        <tr>
								            <td data-bind="text: montant2"></td>
								            <td data-bind="text: nom2"></td>
								            <td data-bind="text: numero2"></td>
								            <td><button data-bind="attr: {id: id_select2}, click: $parent.supr_cheque" class="ui-shadow ui-btn ui-corner-all">-</button></td>		            
										        </tr>
										    </tbody>
										</table>
			           			 	</div>
			           			 	</li>
			           			 	
			           			 	
			           			 	</ul>
					</div>
				   	</li>
				   	<li>
						<div data-role="collapsible" id="recherche_remise">
           			 		<h2>Rechercher une remise :</h2>
		           			 		<fieldset class="ui-grid-a">
				           			 	 <div class="ui-block-a">							      			
												<label for="remise_id">Identifiant remise ou caractéristique chèque:</label>
												<input type="text" name="remise_id" id="remise_id">		
								      	</div>					       
								        <div class="ui-block-b">
								      			 <a name="valider6" id="valider6" data-role="button">Rechercher</a>        	
								   		 </div>
						   		</fieldset>
						   		<div class="animation_remise">
						   		<select id="choix_remise" data-bind="enable: remise_selectionne, value : remise_select, options: liste_remise3, optionsText: 'numero_remise', optionsValue: 'id', optionsCaption: 'Choose...', event: { change: selection_remise }">
     						    
    							</select>
    					
						   		<span name="table_remise3_id" id="table_remise3_id" data-bind="text: remise_id"></span>
						   		<a name="copy_remise2" id="copy_remise2" data-role="button">Selectionner</a>
						   		<a name="valider7" id="valider7" data-role="button">Mettre à jour la remise</a>
						   		</div>
							   	<table data-role="table" id="table_remise3" name="table_remise3" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
						       
						    			<thead>
						      			  <tr><th>montant</th><th>client</th><th>numero</th><th>effacer</th></tr>
						    			</thead>
						   				<tbody data-bind="foreach: liste_remise4">
								        <tr>
								            <td data-bind="text: montant2"></td>
								            <td data-bind="text: nom2"></td>
								            <td data-bind="text: numero2"></td>
								            <td><button data-bind="attr: {id: id_select2}, click: $parent.supr_cheque3" class="ui-shadow ui-btn ui-corner-all">-</button></td>		            
								        </tr>
								    </tbody>
								</table>
           			 	</div>
           			 	</li>
		           		<li>
		  <div data-role="collapsible" id="rappel">
		            <h2>Envoi des lettres de rappel :</h2>
			<fieldset class="ui-grid-b">
		        <div class="ui-block-a">
		      			 <label for="date_debut8">Date de début :<p id="heure_deb_8"></p></label>
						 <input type="date" data-role="datebox" name="date_debut8" id="date_debut8" data-options='{"mode": "datebox", "showInitialValue": true}' />              
		        </div>
		        <div class="ui-block-b">
		      			 <label for="date_fin8">Date de fin :<p id="heure_fin_8"></p></label>
						 <input type="date" data-role="datebox" name="date_fin8" id="date_fin8" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
		   		 </div>
		   		 <div class="ui-block-c">
		        		 <a name="valider8" id="valider8" data-role="button">Rechercher</a>  
		        		 <div class="animation_rappel">
		        		 <a name="valider9" id="valider9" data-role="button" data-choix="A4" title="Créer les feuilles de rappel version A4">Créer les feuilles de rappel version A4</a>
		        		 <a name="valider9_1" id="valider9_1" data-role="button" data-choix="lettre" title="Créer les feuilles de rappel version lettre">Créer les feuilles de rappel version lettre</a>  
		        		 </div>     	
		   		 </div>
		   	</fieldset>
		   <table data-role="table" id="table_rappel" name="table_rappel" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
		       
		    			<thead>
		      			  <tr><th>date evènement</th><th>nature</th><th>Nom</th><th>Animal</th><th>Remarque</th><th>Supprimer</th></tr>
		    			</thead>
		   				<tbody data-bind="foreach: liste_rappel">
				        <tr>
				            <td data-bind="text: date_rappel"></td>
				            <td data-bind="text: type"></td>
				            <td><a data-bind="text: nom_p, click: $parent.url, attr: {title: $parent.client_remise($data), href: url}"></a></td>
				            <td data-bind="text: nom_a"></td>
				            <td><input type="text" data-bind="value: commentaire, valueUpdate: 'afterkeydown'"></td>
				            <td><button data-bind="click: $parent.supr_rappel" class="ui-shadow ui-btn ui-corner-all">-</button></td>				            		            
				        </tr>
				    </tbody>
				</table>
				</div>
		    </li>     
    <li>
		  <div data-role="collapsible" id="radio">
		            <h2>Exposition aux radiographies :</h2>
			<fieldset class="ui-grid-b">
		        <div class="ui-block-a">
		      			 <label for="date_debut10">Date de début :<p id="heure_deb_10"></p></label>
						 <input type="date" data-role="datebox" name="date_debut10" id="date_debut10" data-options='{"mode": "datebox", "showInitialValue": true}' />              
		        </div>
		        <div class="ui-block-b">
		      			 <label for="date_fin10">Date de fin :<p id="heure_fin_10"></p></label>
						 <input type="date" data-role="datebox" name="date_fin10" id="date_fin10" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
		   		 </div>
		   		 <div class="ui-block-c">
		        		 <a name="valider10" id="valider10" data-role="button">Rechercher</a>  
		        		 <div class="animation_radio">
		        		 <a name="selectionner10" id="selectionner10" data-role="button">Selectionner</a>  
		        		 </div>     	
		   		 </div>
		   	</fieldset>
		   <table data-role="table" id="table_radio" name="table_radio" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
		       
		    			<thead>
		      			  <tr><th>date</th><th>Personnel exposé</th><th>Zone</th><th>Constante</th></tr>
		    			</thead>
		   				<tbody data-bind="foreach: liste_radio">
				        <tr>
				            <td data-bind="text: date"></td>
				            <td data-bind="text: nom"></td>
				            <td data-bind="text: zone"></td>
				            <td data-bind="text: expo"></td>				            			            		            
				        </tr>
				    </tbody>
				</table>
				</div>
		    </li> 		   
		    <li>
		  	<div data-role="collapsible" id="stat">
		            <h2>Statistiques :</h2>
			
		        		 <a name="valider11" id="valider11" data-role="button">Rechercher</a>  		        		
		   				<div id="graphique_line"></div>
				</div>
		    </li>  		   
					<?php // if($_SESSION['login']==$_SESSION['login2']){?>
					<div id="ma_liste_consult" data-role="popup"></div>
					 <li>
			  		<div data-role="collapsible" id="sous_totaux">
			            <h2>totaux par utilisateur :</h2>
							<fieldset class="ui-grid-b">
						        <div class="ui-block-a">
						      			 <label for="date_debut12">Date de début :</label>
						      	</div>
						      	<div class="ui-block-b">
										 <input type="date" data-role="datebox" name="date_debut12" id="date_debut12" data-options='{"mode": "datebox", "closeCallback": "$(\"#heure_debut12\").datebox(\"open\");", "showInitialValue": true}' />              
						        </div>
						        <div class="ui-block-c">
										 <input name="heure_debut12" id="heure_debut12" type="date" data-role="datebox" data-options='{"mode": "timebox"}'>        
						        </div>
						    </fieldset>
						    <fieldset class="ui-grid-b">
						        <div class="ui-block-a">
						      			 <label for="date_fin12">Date de fin :</label>
						      	</div>
						      	<div class="ui-block-b">
										 <input type="date" data-role="datebox" name="date_fin12" id="date_fin12" data-options='{"mode": "datebox", "closeCallback": "$(\"#heure_fin12\").datebox(\"open\");", "showInitialValue": true}' />              
						        </div>
						        <div class="ui-block-c">
										 <input name="heure_fin12" id="heure_fin12" type="date" data-role="datebox" data-options='{"mode": "timebox"}'>        
						        </div>
						    </fieldset>
			   				<a name="valider12" id="valider12" data-role="button">Rechercher</a>  
			   				
			   				<div class="animation_sous_tot">						   		   					
								<a name="copy_sous_tot" id="copy_sous_tot" data-role="button">Selectionner</a>					   		
			   					<span name="table_sous_totaux_def" id="table_sous_totaux_def" data-bind="text: sous_totaux_def"></span>
			   				</div>
			   				
			   				<table data-role="table" id="table_sous_totaux" name="table_sous_totaux" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
			       
			    			<thead>
			      		<!--  	  <tr><th>nom</th><th>total ttc</th><th>total ht</th><th>tva</th><th>total acte réel</th><th>total medic réel</th><th>total acte théorique</th><th>total medic théorique</th><th>$$$</th><th>espece</th><th>cheque</th><th>carte</th><th>virement</th></tr>  -->
			    		<tr><th>nom</th><th>total facturation reçu</th><th>total acte réel</th><th>total medic réel</th><th>total facturation théorique</th><th>total acte théorique</th><th>total medic théorique</th><th>total paiement</th><th>espece</th><th>cheque</th><th>carte</th><th>virement</th><th>total répartition</th></tr>
			    		    			</thead>
			   				<tbody data-bind="foreach: liste_sous_totaux">
					        <tr>
					            <td data-bind="text: sous_tot_nom, click: $parent.veto_print" data-role="button"></td>
					            <td class="color_bleu" data-bind="text: sous_tot_ttc3, click: $parent.veto_consult"></td>
					           <!--  <td data-bind="text: sous_tot_ht"></td>--> 
					           <!--  <td data-bind="text: sous_tot_tva"></td>--> 
					            <td data-bind="text: sous_tot_acte"></td>
					            <td data-bind="text: sous_tot_medic"></td>
					            <td class="color_bleu"  data-bind="text: sous_tot_ttc2, click: $parent.veto_consult3"></td>
					            <td data-bind="text: sous_tot_tot_acte"></td>
					             <td data-bind="text: sous_tot_tot_medic"></td>
					            <td class="color_bleu"  data-bind="text: sous_tot_ttc, click: $parent.veto_consult2"></td>					           
					            <td data-bind="text: sous_tot_espece"></td>
					            <td data-bind="text: sous_tot_cheque"></td>
					            <td data-bind="text: sous_tot_carte"></td>	
					            <td data-bind="text: sous_tot_virement"></td>
					            <td class="color_bleu"  data-bind="text: sous_tot_repartition, click: $parent.veto_consult4"></td>		            
					        </tr>
					    </tbody>
					</table>
					</div>
			    </li> 
			    <?php // }?>
			    		    
			    
			    
			    <li>
  					<div data-role="collapsible" id="paiement_veto">
            			<h2>Régler les honoraires des vétérinaires</h2>
						<fieldset class="ui-grid-b">
        					<div class="ui-block-a">
      						 <label for="date_debut16">Date de début :<p id="heure_deb_16"></p></label>
							 <input type="date" data-role="datebox" name="date_debut16" id="date_debut16" data-options='{"mode": "datebox", "showInitialValue": true}' />              
        					</div>
       						 <div class="ui-block-b">
      						 <label for="date_fin16">Date de fin :<p id="heure_fin_16"></p></label>
							 <input type="date" data-role="datebox" name="date_fin16" id="date_fin16" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
   							 </div>
   		 					<div class="ui-block-c">
        					 <a name="valider16" id="valider16" data-role="button">Rechercher</a>           		      	
   						 </div>
   						</fieldset>
   					  	
   					<table data-role="table" id="table_honoraire" name="table_honoraire" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
           			<thead>
      			  <tr><th>nom</th><th>acte ht</th><th>% A</th><th>medic ht</th><th>% M</th><th>répartition</th><th>% R</th><th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: liste_reglement_honoraires">
		        <tr>
		            <td data-bind='text: nom'></td>
		            <td data-bind="text: base_ht"></td>
		            <td data-bind="html: retribution_acte"></td>
		            <td data-bind="text: medic_ht"></td>
		            <td data-bind="html: retribution_medic"></td>
		            <td data-bind="text: repartition_ht"></td>
		            <td data-bind="html: retribution_repartition"></td>
		            <td><button data-bind="attr: {id: id_select}, click: $parent.valid_paiement" class="ui-shadow ui-btn ui-corner-all">document</button></td>	      		          		            
		        </tr>
		    </tbody>
		</table>		
		</div>
    </li> 
    <li>   
        
			  <div data-role="collapsible" id="vente">
			            <h2>Liste des ventes :</h2>
				<fieldset class="ui-grid-b">
			        <div class="ui-block-a">
			      			 <label for="date_debut13">Date de début :<p id="heure_deb_13"></p></label>
							 <input type="date" data-role="datebox" name="date_debut13" id="date_debut13" data-options='{"mode": "datebox", "showInitialValue": true}' />              
			        </div>
			        <div class="ui-block-b">
			      			 <label for="date_fin13">Date de fin :<p id="heure_fin_13"></p></label>
							 <input type="date" data-role="datebox" name="date_fin13" id="date_fin13" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
			   		 </div>
			   		 <div class="ui-block-c">
			        		 <a name="valider13" id="valider13" data-role="button">Rechercher</a>        	
			   		 </div>
			   	</fieldset>
			   	<div id="pager" class="pager">
					  <form>
					    <img src="image/icons/first.png" class="first"/>
					    <img src="image/icons/prev.png" class="prev"/>
					    <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
					    <img src="image/icons/next.png" class="next"/>
					    <img src="image/icons/last.png" class="last"/>
					  </form>
				</div>
			   	<table data-role="table" id="table_vente" name="table_vente" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
			       
			    			<thead>
			      			  <tr><th>date de vente</th><th>Nom du médicament</th><th>Nombre</th></tr>
			    			</thead>
			   				<tbody data-bind="foreach: liste_vente">
					        <tr>
					            <td data-bind="text: date_vente"></td>
					            <td data-bind="text: nom"></td>
					            <td data-bind="text: nombre"></td>					            		            
					        </tr>
					    </tbody>
					</table>
					</div>
			    </li>   		    
			    
			    <li>
			  <div data-role="collapsible" id="pharmacologie">
			            <h2>pharmacovigilance :</h2>
				
			        <legend for="pharmaco">Numéro de lot ou nom médicament :</legend>
	    			<input type="text"  name="pharmaco" id="pharmaco">
 					<a name="valider14" id="valider14" data-role="button">Rechercher</a>  
			       
			   
			   	<table data-role="table" id="table_pharmaco" name="table_pharmaco" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive tablesorter">
			       
			    			<thead>
			      			  <tr><th>date de vente</th><th>Nom client</th><th>Nom animal</th></tr>
			    			</thead>
			   				<tbody data-bind="foreach: liste_pharmaco">
					        <tr>
					            <td data-bind="text: date_vente"></td>
					            <td data-bind="text: nom"></td>
					            <td><a href="#" data-bind="text: nom_a, click: $parent.url"></a></td>					            		            
					        </tr>
					    </tbody>
					</table>
					</div>
			    </li>   
			   
			    
			    
    
		    
		    
		    
		    

</ul>
</section>

<?php render('_footer')?>

