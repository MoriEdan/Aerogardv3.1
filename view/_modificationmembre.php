<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">
$( document ).on( "pageinit", function( event ) { 
	// sécurité pour les sessions
	var refreshTime = 1200000; // in milliseconds, so 20 minutes
    window.setInterval( function() {
    	document.location.href="index.php";
    }, refreshTime );
	var mes_specialite = <?php echo $mes_specialite;?>;
	var veto = <?php echo $mes_infos;?>;
	var tarif = <?php echo $tarif;?>;
	var tarif2 = <?php echo $tarif2;?>;
	var tarif_medoc = <?php echo $tarif_medoc;?>;
	var liste_conduite = <?php echo json_encode($liste_conduite);?>;
	var liste_specialite = <?php echo json_encode($liste_specialite);?>;
	var list_text = <?php echo TXT_MODIFICATIONMEMBRE_JSPARTS;?>;
	var info_tour = <?php echo $info_tour_array;?>;
	
	function objet_conduite_creation(data,index) {
	 	   this.id = ko.observable(index);
	 	   this.nom = ko.observable(data['nom']);	 	  
		};
	function objet_specialite_creation(data,index) {
	 	   this.id = ko.observable(index);
	 	   this.nom = ko.observable(data['nom']);
	 	   this.commune = ko.observable(veto[0]['commune']);	 	  
		};
	function objet_specialiste_creation(data,index) {
	 	   this.id_selectionne = ko.observable(index);
	 	   this.nom = ko.observable(data['nom']);	 	  
		};
	function objet_tarif2_creation(data,index){
			this.acte = ko.observable(data['acte']);
			this.tarifttc = ko.observable(data['tarifttc']);	
		//	this.nouveautarif = ko.observable('<input type="text" name="tarif2_'+index+'" id="tarif2_'+index+'">');
			this.id_select = ko.observable(index);			
			};	
	function objet_medic_creation(data,index){
			this.nom = ko.observable(data['nom']);
			this.prixht = ko.observable(data['prixht']);
			this.lot = ko.observable(data['lot']);	
			this.centrale = ko.observable(data['centrale']);	
			this.id_select = ko.observable(index);							
			};	
	function isNumber(n) {
		  return !isNaN(parseFloat(n)) && isFinite(n);
		};
		
	function ViewModel() {
			var self = this;

			self.modif_marge_medic =  function(){
				self.paused2(true);
				ko.utils.arrayForEach(self.medic_ajoutes(), function(item) {					
					var value = parseFloat(ko.utils.unwrapObservable(item.prixht()));										
				       if (isNumber(value) && isNumber($('#marge_medic').val()) && isNumber($('#modif_tva').val())) {				    	 
				          item.prixttc(value.toFixed(2));	
				      }				       									
				    		
				});
				self.paused2(false);
					alert(list_text.medicine_rate_change_alert);
			};
			imprimer_tarif2 =  function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.medicine_paper_setting_up+"</h2></span>",
					iconpos : "right",
				    theme: "a"				             	 
				});
				var jsonData = ko.toJS(self.computedData2);
				console.log("mes tarifs ajoutés "+ JSON.stringify(jsonData));
				$.ajax({
	            	type: "POST",
	                url: "php/modificationmembre.php?action=print_tarif2",
	                dataType: "json",
	                cache: false,
	                data:  {
	                    tarifs: jsonData,tva: $('#tva').val(),marge: $('#marge_medic').val()
	                }		                           
	            })
	            .then( function ( response ) {
	            	$.mobile.loading('hide');
	            	window.open('aerogard/'+response);  
	            	
	            	
	            });
			};
			self.modif_prix_medicament =  function(){
				self.paused2(true);
				ko.utils.arrayForEach(self.medic_ajoutes(), function(item) {
					
					var value = parseFloat(ko.utils.unwrapObservable(item.prixht()));
					var total = value;
					
				       if (isNumber(value)) {
				          total = value + (value*$('#modif_prix_medic').val()/100);
				          //console.log("total "+total.toFixed(2));	
				          item.prixht(total.toFixed(2));	
				      }				       									
				    		
				});
				self.paused2(false);
					alert(list_text.percent_medicine_rate_change+$('#modif_prix_medic').val()+"%");
			};
			self.medic_ajoutes = ko.observableArray([]);	
			var objet_medic = $.map(tarif_medoc, function(item,index) {
 			   return new objet_medic_creation(item,index);               
           	});
 		    self.medic_ajoutes(objet_medic);
 		   self.modification_tarifmedoc =  function(){ 	 
	 			  for (var i = 0, j = self.medic_ajoutes().length; i < j; i++) {
	
	 				 if(parseFloat(ko.utils.unwrapObservable(this.id_select))==parseFloat(ko.utils.unwrapObservable(self.medic_ajoutes()[i].id_select))){
	
	  	 				self.medic_ajoutes()[i]['prixht']($('#tarif3_'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val());
	  	 			  
	  	 			  }
	 			  }			   
 			  	   
 			};
 			 self.modification_lotmedoc =  function(){ 	 
	 			  for (var i = 0, j = self.medic_ajoutes().length; i < j; i++) {
	
	 				 if(parseFloat(ko.utils.unwrapObservable(this.id_select))==parseFloat(ko.utils.unwrapObservable(self.medic_ajoutes()[i].id_select))){
	
	  	 				self.medic_ajoutes()[i]['lot']($('#tarif4_'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val());
	  	 			  
	  	 			  }
	 			  }			   
			  	   
			};
 			self.paused2 = ko.observable(false);
			self.computedData2 = ko.computed(function() {
				 if (!self.paused2()) {
			        return ko.utils.arrayMap(self.medic_ajoutes(), function(item) {
			        	var value = parseFloat(ko.utils.unwrapObservable(item.prixht()));
						var total_ht_marge = 0;
						var total_ttc = 0;						
					       if (isNumber(value) && isNumber($('#marge_medic').val()) && isNumber($('#tva').val())) {
					    	   total_ht_marge = value + (value*$('#marge_medic').val()/100);
					    	   total_ttc = total_ht_marge + (total_ht_marge*$('#tva').val()/100);					          
					       }				          
				        return { nom: item.nom(), prixht: item.prixht(), prixttc: total_ttc.toFixed(2), nouveautarifmedoc: ko.observable('<input style="width:100%" type="text" name="tarif3_'+parseFloat(ko.utils.unwrapObservable(item.id_select()))+'" id="tarif3_'+parseFloat(ko.utils.unwrapObservable(item.id_select()))+'">'), lot: item.lot(), nouveaulotmedoc: ko.observable('<input style="width:100%" type="text" name="tarif4_'+parseFloat(ko.utils.unwrapObservable(item.id_select()))+'" id="tarif4_'+parseFloat(ko.utils.unwrapObservable(item.id_select()))+'">'), centrale: item.centrale(), id_select: item.id_select() };
			        });
				 }
		    });
			 
			self.modif_medic = function(){	
				$("#designation_medic").val(ko.utils.unwrapObservable(this.nom));
				$("#prix_medic").val(ko.utils.unwrapObservable(this.prixht));
				$("#lot_medic").val(ko.utils.unwrapObservable(this.lot));
				$("#code_medic").val(ko.utils.unwrapObservable(this.centrale));
			};		
			
			self.ajout_medic = function(){	
					var trouve=false;
					ko.utils.arrayForEach(self.medic_ajoutes(), function(item) {
						if(ko.utils.unwrapObservable(item.nom)==$("#designation_medic").val() && parseFloat(ko.utils.unwrapObservable(item.prixht))==$("#prix_medic").val()){
								trouve=true;
							}
					});
					if(trouve==false){			
						self.medic_ajoutes.unshift({nom : ko.observable($("#designation_medic").val()), prixht : ko.observable($("#prix_medic").val()), lot : ko.observable($("#lot_medic").val()), centrale : ko.observable($("#code_medic").val()), id_select :  ko.observable(self.medic_ajoutes().length)});
					}
				};
			self.supr_medic =  function(){
				var mon_id = parseFloat(ko.utils.unwrapObservable(this.id_select));
				self.medic_ajoutes.remove(function(item) { return parseFloat(ko.utils.unwrapObservable(item.id_select)) == mon_id;});
				
				};




				
			self.arrondir =  function(item, event){
					console.log($(event.target).attr('value'));
					self.paused(true);
					ko.utils.arrayForEach(self.tarif_ajoutes2(), function(item) {
						
						var value = parseFloat(ko.utils.unwrapObservable(item.tarifttc()));
						var total = value;
						
					       if (isNumber(value)) {
					         item.tarifttc(total.toFixed($(event.target).attr('value')));	
					      }				       									
					    		
					});
					self.paused(false);
						alert(list_text.round_act_rate+$(event.target).attr('value')+list_text.number_after_decimal);
						
			}
			self.modif_prix_acte =  function(){
				self.paused(true);
				ko.utils.arrayForEach(self.tarif_ajoutes2(), function(item) {
					
					var value = parseFloat(ko.utils.unwrapObservable(item.tarifttc()));
					var total = value;
					
				       if (isNumber(value)) {
				          total = value + (value*$('#modif_prix').val()/100);
				          //console.log("total "+total.toFixed(2));	
				          item.tarifttc(total.toFixed(2));	
				      }				       									
				    		
				});
				self.paused(false);
					alert(list_text.percent_act_rate_change+$('#modif_prix').val()+"%");
			};
			self.tarif_ajoutes2 = ko.observableArray([]);	
			var objet_tarif2 = $.map(tarif2, function(item,index) {
 			   return new objet_tarif2_creation(item,index);               
           	});
 		    self.tarif_ajoutes2(objet_tarif2);

 		   self.modification_tarif =  function(){ 	 	

 			  for (var i = 0, j = self.tarif_ajoutes2().length; i < j; i++) {

 				 if(parseFloat(ko.utils.unwrapObservable(this.id_select))==parseFloat(ko.utils.unwrapObservable(self.tarif_ajoutes2()[i].id_select))){

  	 				self.tarif_ajoutes2()[i]['tarifttc']($('#tarif2_'+parseFloat(ko.utils.unwrapObservable(this.id_select))).val());
  	 			  
  	 			  }
 			  }
 			   
 			  	   
 			};
 			self.paused = ko.observable(false);
			self.computedData = ko.computed(function() {
				 if (!self.paused()) {
			        return ko.utils.arrayMap(self.tarif_ajoutes2(), function(item) {
				        return { acte: item.acte(), tarifttc: item.tarifttc(), nouveautarif: ko.observable('<input type="text" name="tarif2_'+parseFloat(ko.utils.unwrapObservable(item.id_select()))+'" id="tarif2_'+parseFloat(ko.utils.unwrapObservable(item.id_select()))+'">'), id_select: item.id_select() };
			        });
				 }
		    });
			
			self.modif_acte = function(){	
				$("#designation_acte2").val(ko.utils.unwrapObservable(this.acte));
				$("#prix_acte2").val(ko.utils.unwrapObservable(this.tarifttc));
			};		
			//self.tarif_ajoutes2 = ko.observableArray(tarif2);
			self.ajout_tarif2 = function(){	
					var trouve=false;
					ko.utils.arrayForEach(self.tarif_ajoutes2(), function(item) {
						if(ko.utils.unwrapObservable(item.acte)==$("#designation_acte2").val() && parseFloat(ko.utils.unwrapObservable(item.tarifttc))==$("#prix_acte2").val()){
								trouve=true;
							}
					});
					if(trouve==false){			
						self.tarif_ajoutes2.unshift({acte : ko.observable($("#designation_acte2").val()), tarifttc : ko.observable($("#prix_acte2").val()), id_select :  ko.observable(self.tarif_ajoutes2().length)});
					}
				};
			self.supr_tarif2 =  function(){
				var mon_id = parseFloat(ko.utils.unwrapObservable(this.id_select));
				self.tarif_ajoutes2.remove(function(item) { return parseFloat(ko.utils.unwrapObservable(item.id_select)) == mon_id;});
				
				};
				
			self.tarif_ajoutes = ko.observableArray(tarif);
			self.ajout_tarif = function(){				
				self.tarif_ajoutes.push({acte : $("#designation_acte").val(), tarifttc : $("#prix_acte").val(), taille : $("#taille").val(), id_select :  self.tarif_ajoutes().length});
				};
			self.supr_tarif =  function(){
				self.tarif_ajoutes.remove(this);
				};
			self.conduite = ko.observableArray([]);
		    var objet_conduite = $.map(liste_conduite, function(item,index) {
			   return new objet_conduite_creation(item,index);               
            });
		   self.conduite(objet_conduite);
		   if(veto[0]['conduite_suivre']==''){
			   self.conduite_ajoutees = ko.observableArray([]);
			}else{
				self.conduite_ajoutees = ko.observableArray(JSON.parse(veto[0]['conduite_suivre']));
			}
		   self.ajout_conduite = function(){
				console.log(" ligne liste analyse choisie "+$("#choix_conduite").find(":selected").val());
				self.conduite_ajoutees.push({nom : $("#choix_conduite").find(":selected").text(), id_select :  self.conduite_ajoutees().length});
				console.log("nb d'enregistrement "+self.conduite_ajoutees().length);
				};
			self.supr_conduite =  function(){
				self.conduite_ajoutees.remove(this);
			};  

			self.specialite = ko.observableArray([]);
		    var objet_specialite = $.map(liste_specialite, function(item,index) {
			   return new objet_specialite_creation(item,index);               
            });
		   self.specialite(objet_specialite);		   
		   self.specialite_ajoutees = ko.observableArray([]);
		   self.ajout_specialite2 = $.map(mes_specialite, function(item,index) {
			   self.specialite_ajoutees.push({nom : item['domaine'], id_select :  self.specialite_ajoutees().length});            
           });
		  
		   self.ajout_specialite = function(){
				console.log(" ligne liste analyse choisie "+$("#choix_specialite").find(":selected").val());
				self.specialite_ajoutees.push({nom : $("#choix_specialite").find(":selected").text(), id_select :  self.specialite_ajoutees().length});
				console.log("nb d'enregistrement "+self.specialite_ajoutees().length);
				};
			self.supr_specialite =  function(){
				self.specialite_ajoutees.remove(this);
			};  
			self.resultat_specialiste = ko.observableArray([]);
			self.array_resultat_specialiste = ko.observableArray([]);
			var objet_specialiste = $.map(self.array_resultat_specialiste(), function(item,index) {
 			   return new objet_specialiste_creation(item,index);               
             });
 		   self.resultat_specialiste(objet_specialiste);

 		 	 if(veto[0]['conduite_suivre']==''){
 			 self.specialiste_ajoutees = ko.observableArray([]);
			}else{
				self.specialiste_ajoutees = ko.observableArray(JSON.parse(veto[0]['choix_specialiste']));
			}
   		 	   self.ajout_specialiste  = function(item, event) {						
				self.specialiste_ajoutees.push({nom : $(event.target).text(),domaine : $("#choix_specialiste").find(":selected").text(),id_select :  self.specialiste_ajoutees().length});
				
				};
				
			self.selection_specialiste  = function(item, event) {
					$.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.search+"</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({				 				    
			        	type: "POST",
			        	url: "php/modificationmembre.php?action=recherche",
			        	dataType: "json",
			            cache: false,
			            data:  {
			 			valeur : $("#choix_specialiste").find(":selected").text()   
			            },	
			            success: function(data){
			            	$.mobile.loading('hide');			                       
	                        console.log("retour serveur "+data);

	                        //self.resultat_specialiste = ko.observableArray([]);
	                        var objet_specialiste2 = $.map(data, function(item,index) {
	              			   return new objet_specialiste_creation(item,index);               
	                          });
	              		   self.resultat_specialiste(objet_specialiste2);
	                        
	                        
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
			
				self.supr_specialiste =  function(){
					self.specialiste_ajoutees.remove(this);
				};  
			self.valider = function(){
				var jsonData1 = ko.toJSON(self.conduite_ajoutees);
				 console.log("conduite: " + JSON.stringify(jsonData1));
                var jsonData2 = ko.toJSON(self.specialite_ajoutees);
			    console.log("specialite: " + JSON.stringify(jsonData2));
			    var jsonData3 = ko.toJSON(self.specialiste_ajoutees);
			    console.log("specialiste: " + JSON.stringify(jsonData3));	
			    var formulaire = $('#formveterinaire').serializeFormJSON();
                console.log("formulaire: " + JSON.stringify(formulaire));	
                var jsonData4 = ko.toJSON(self.tarif_ajoutes);
			    console.log("tarif: " + JSON.stringify(jsonData4));
			    var jsonData5 = ko.toJSON(self.tarif_ajoutes2);
			    console.log("tarif2: " + JSON.stringify(jsonData5));
			    var jsonData6 = ko.toJSON(self.medic_ajoutes);
			    console.log("tarifmedic: " + JSON.stringify(jsonData6));
			    var jsonData7 = ko.toJSON(self.rotation_ajoute);
			    console.log("jour indispo: " + JSON.stringify(jsonData7));
			    var jsonData8 = ko.toJSON(self.indispo_1);
			    console.log("vacances: " + JSON.stringify(jsonData8));
                
						
		 		$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.record+"</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});		 		
	 				$.ajax({				 				    
			        	type: "POST",
			        	url: "php/modificationmembre.php?action=enregistrement",
			        	dataType: "json",
			            cache: false,
			            data:  {
			 			id_veto : veto[0]['id'],conduite : jsonData1, specialite : jsonData2, specialiste : jsonData3, formulaire : formulaire, tarif : jsonData4, tarif2 : jsonData5, tarif_medic : jsonData6, indispo : jsonData7, vacances : jsonData8   
			            },	
			            success: function(data){
			            	$.mobile.loading('hide');			                       
	                        console.log("retour serveur "+data);
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
		 		
		 	};
		 	specialite_list= ko.observable("");
		 	conduite_list= ko.observable("");
		 	specialite_list2=ko.observable("");
		 	imprimer_tarif =  function(){
				$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.rate_paper_setting_up+"</h2></span>",
					iconpos : "right",
				    theme: "a"				             	 
				});
				var jsonData = ko.toJS(self.tarif_ajoutes2);
				console.log("mes tarifs ajoutés "+ JSON.stringify(jsonData));
				$.ajax({
	            	type: "POST",
	                url: "php/modificationmembre.php?action=print_tarif",
	                dataType: "json",
	                cache: false,
	                data:  {
	                    tarifs: jsonData
	                }		                           
	            })
	            .then( function ( response ) {
	            	$.mobile.loading('hide');
	            	window.open('aerogard/'+response);  
	            	
	            	
	            });
			};

			self.jourevi2 = ko.observableArray();
			if(info_tour[0]){
				 if(!is_int(info_tour[0]['id'])){
					 self.indispo_1 = ko.observableArray([]);
				   }else{					   
					   self.indispo_1 = ko.observableArray(JSON.parse(info_tour[0]['vacances']));
				   }
				}else{
					self.indispo_1 = ko.observableArray([]);
				}
			self.ajout_indispo2 = function(){
				self.indispo_1.push({login : veto[0]['login'],
					debut :  $('#date_debut13').datebox('getTheDate').clearTime().getTime(),
					  debut2 :  $('#date_debut13').datebox('getTheDate').toString('d/M/yyyy'),					   
					   fin :  $('#date_fin13').datebox('getTheDate').clearTime().getTime(), 
					    fin2 :  $('#date_fin13').datebox('getTheDate').toString('d/M/yyyy'), 
					     id_select :  self.indispo_1().length});
			} 			
			self.supr_indispo_1 =  function(){
				self.indispo_1.remove(this);
			};
			 if(info_tour[0]){
				   if(!is_int(info_tour[0]['id'])){
				  	 self.jour_ajoutees = ko.observableArray([]);
				   }else{				  
				  	 self.jour_ajoutees = ko.observableArray(JSON.parse(info_tour[0]['horaire']));
				   }
			  }else{
				  self.jour_ajoutees = ko.observableArray([]);
			  }
			if(info_tour[0]){
				 if(!is_int(info_tour[0]['id'])){
					 self.rotation_ajoute = ko.observableArray();
				   }else{
					   self.rotation_ajoute = ko.observableArray(JSON.parse(info_tour[0]['participant']));
				   }
				}else{
					self.rotation_ajoute = ko.observableArray();
				}
			self.ajout_rotation2 = function(){
		    	
		    	var total_evi = '';
		    	ko.utils.arrayForEach(self.jourevi2(), function(item) {
		    		total_evi += ko.utils.unwrapObservable(self.jour_ajoutees()[item].nom_jour)+" "+ko.utils.unwrapObservable(self.jour_ajoutees()[item].moment)+"   ";
		        });			    
					self.rotation_ajoute({login : veto[0]['login'],
							jour_evi :  $.parseJSON(ko.toJSON(self.jourevi2)),
						 	jour_evi2 :  total_evi						          															          	
						          });
					
				} 
	};
	ko.applyBindings(new ViewModel());
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
	     
	      return true;
	  } else {
	    
	      return false;
	  }
	}
</script>
<section class="nouveauclient cf">
<?php
$mes_infos2 = json_decode($mes_infos, true);
?>
<h2>Mise à jour de la fiche du vétérinaire : <?php echo $_SESSION['login']; ?></h2>

	<form id="formveterinaire">
			<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="username">Nom de la structure vétérinaire:</label>
				<input type="text" name="nom" id="nom" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['nom']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="adresse">Adresse :</label>
				<textarea cols="40" rows="8" name="adresse" id="adresse" ><?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['adresse']); ?></textarea>
			</fieldset>
			<fieldset data-role="fieldcontain"> 			
				<label for="codepostal">code postal :</label>
				<input type="text" name="codepostal" id="codepostal" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['code']); ?>">		
			</fieldset>
			<fieldset data-role="fieldcontain"> 			
				<label for="commune">Commune :</label>
				<input type="text" name="commune" id="commune" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['commune']); ?>">		
			</fieldset>
			</div>
			<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="telephone">Téléphone clinique:</label>
				<input type="tel" name="telephone" id="telephone" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['tel']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="telephone2">Téléphone perso:</label>
				<input type="tel" name="telephone2" id="telephone2" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['tel2']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="email">Email utilisé pour recevoir les rapports:</label>
				<input type="email" name="email" id="email" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['mail2']); ?>">
			</fieldset>
			</div>
			<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="ordre">Numero d'ordre:</label>
				<input type="tel" name="ordre" id="ordre" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['ordre']); ?>">
			</fieldset>
			</div>
			<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="siret">Numéro SIRET:</label>
				<input type="text" name="siret" id="siret" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['siret']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="numtva">Numéro TVA:</label>
				<input type="text" name="numtva" id="numtva" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['num_tva']); ?>">
			</fieldset>
			</div>
			<div class="paragraphe">
			<fieldset class="ui-grid-a">
           
             		 <div class="ui-block-a" style="width:80%;">       	
             		  <label for="choix_analyse">Conduite à suivre pendant les gardes :</label>				
						 <select id="choix_conduite" data-bind="value: conduite_list, options: conduite, optionsText: 'nom', optionsValue: 'id'">
     						   <!-- <option data-bind="text: nom, click: $parent.selection_analyse, attr: {id: id_selectionne}"></option> --> 
    					</select>
       				 </div>
       				 <div class="ui-block-b" align="center" style="width:20%;">       				 	
       				    	<a href="index.html" data-role="button" data-bind="click: ajout_conduite" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette conduite à suivre</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
					 </div>       				
       	 </fieldset>
          <table data-role="table" id="table_conduite" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-a"><th>Conduite à suivre<th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: conduite_ajoutees">
        		<tr>
            <td data-bind="text: nom"></td>
            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_conduite" class="ui-shadow ui-btn ui-corner-all">-</button></td>
       			</tr>
   				 </tbody>
		 </table>
		</div>		
		<div class="paragraphe">
			<fieldset class="ui-grid-a">
           
             		 <div class="ui-block-a" style="width:80%;">       	
             		  <label for="choix_specialite">Mes Spécialités :</label>				
						 <select id="choix_specialite" data-bind="value: specialite_list, options: specialite, optionsText: 'nom', optionsValue: 'id'">
     						   <!-- <option data-bind="text: nom, click: $parent.selection_analyse, attr: {id: id_selectionne}"></option> --> 
    					</select>
       				 </div>
       				 <div class="ui-block-b" align="center" style="width:20%;">       				 	
       				    	<a href="index.html" data-role="button" data-bind="click: ajout_specialite" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette spécialité</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
					 </div>       				
       	 </fieldset>
          <table data-role="table" id="table_specialite" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-a"><th>Mes spécialités<th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: specialite_ajoutees">
        		<tr>
            <td data-bind="text: nom"></td>
            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_specialite" class="ui-shadow ui-btn ui-corner-all">-</button></td>
       			</tr>
   				 </tbody>
		 </table>
		</div>				
		<div class="paragraphe">
			<fieldset class="ui-grid-a">           
             		 <div class="ui-block-a" style="width:40%;">       	
             		  <label for="choix_specialiste">Mon choix de spécialiste :</label>				
						 <select id="choix_specialiste" data-bind="value: specialite_list2, options: specialite, optionsText: 'nom', optionsValue: 'id', event: { change: selection_specialiste }">
     						    
    					</select>
       				 </div>
       				 <div class="ui-block-b" align="center" style="width:40%;">       				 	
       				    	<ul id="liste_specialiste" data-bind="foreach: resultat_specialiste" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Rechercher..." data-filter-theme="d" data-split-icon="gear">
							<li>
							<a data-bind="click: $parent.ajout_specialiste, attr: {id: id_selectionne}">
		   					<p data-bind="text: nom, attr: {id: id_selectionne}"><strong></strong></p>		   							
		   					</a>
		    				</li>		
		    				</ul>			
					 </div>       				   				
       		 </fieldset>
          <table data-role="table" id="table_specialiste" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-a"><th>Mes choix de spécialiste<th></th></tr>
    			</thead>
   				<tbody data-bind="foreach: specialiste_ajoutees">
        		<tr>
            <td data-bind="text: nom"></td>
            <td data-bind="text: domaine"></td>
            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_specialiste" class="ui-shadow ui-btn ui-corner-all">-</button></td>
       			</tr>
   				 </tbody>
		 </table>
		</div>				
		<div class="paragraphe">
		<fieldset data-role="fieldcontain"> 
				<label for="commentaire">Commentaire sur le renvoi des gardes :</label>
				<textarea cols="40" rows="8" name="commentaire" id="commentaire" ><?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['mention_speciale']); ?></textarea>
				</fieldset>				
		</div>	
		<div class="paragraphe">
				<ul data-role="listview" data-count-theme="c" data-inset="true">
				<li>
					<div data-role="collapsible">
	 				<h2>Ajouter/Modifier les tarifs principaux des actes:</h2>
						<label>Les tarifs principaux apparaissent à gauche de la barre de saisie des médicaments. Vous pouvez cliquer sur le lien pour enregistrer directement l'acte.</label>
						 <fieldset class="ui-grid-c" >
		       				 <div class="ui-block-a" style="width:40%">
		       				  <label>Désignation acte :</label>
		     				  <input type="text" name="designation_acte" id="designation_acte" value="">
		       				 </div>
		       				 <div class="ui-block-b" style="width:20%">
		       				 <label>Prix unitaire :</label>
		     				 <input type="text" name="prix_acte" id="prix_acte" value="">
		       				 </div>
		       				 <div class="ui-block-c" style="width:20%">
		       				 <label>Taille :</label>
		     				 <input type="range" name="taille" id="taille" value="2" min="1" max="5">
		       				 </div>
		       				 <div class="ui-block-d" style="width:15%">
		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: ajout_tarif" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter ce tarif" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce tarif</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 
		       				 </div>
		     			 </fieldset> 
		     			 
		     			  <table data-role="table" id="table_tarif" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
					      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
					    	<thead>
					        <tr class="ui-bar-e"><th>Désignation</th><th>prix unitaire</th><th>importance</th><th></th></tr>
					    	</thead>
				  		 	<tbody data-bind="foreach: tarif_ajoutes">
				      		  <tr>
				            <td data-bind="text: acte"></td>
				            <td data-bind="text: tarifttc"></td>
				            <td data-bind="text: taille"></td>
				            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_tarif" class="ui-shadow ui-btn ui-corner-all">-</button></td>
				      		  </tr>
				   			</tbody>
						</table>    	 						
		           </div>	
		    </li>
			<li>
	 			<div data-role="collapsible">
	 				<h2>Ajouter/Modifier la liste exhaustive des tarifs des actes:</h2>	
	 				<label>Les tarifs exhautifs apparaissent quand vous commencez à saisir le nom d'un acte dans la barre de recherche des actes.</label>
	 					 <fieldset class="ui-grid-b" >
		       				 <div class="ui-block-a" style="width:60%">
		       				  <label>Désignation acte :</label>
		     				  <input type="text" name="designation_acte2" id="designation_acte2" value="">
		       				 </div>
		       				 <div class="ui-block-b" style="width:20%">
		       				 <label>Prix unitaire ttc :</label>
		     				 <input type="text" name="prix_acte2" id="prix_acte2" value="">
		       				 </div>
		       				 <div class="ui-block-c" style="width:15%">
		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: ajout_tarif2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter ce tarif" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce tarif</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 
		       				 </div>
		     			 </fieldset> 
		     			 <button id="print_tarif" data-bind="click: imprimer_tarif" class="ui-shadow ui-btn ui-corner-all">Imprimer les tarifs</button>
		     			 
		     			  <table data-role="table" id="table_tarif2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
					      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
					    	<thead>
					        <tr class="ui-bar-e"><th style="width:35%">Désignation</th><th style="width:15%">prix unitaire</th><th style="width:15%"></th><th style="width:15%"></th><th style="width:15%"></th></tr>
					    	</thead>
				  		 	<tbody data-bind="foreach: computedData">
				      		  <tr>
				            <td data-bind="attr: {id: id_select}, text: acte, click: $parent.modif_acte"></td>
				            <td data-bind="text: tarifttc"></td>
				            <td data-bind="html: nouveautarif()"></td>
				            <td><button data-bind="attr: {id: id_select}, click: $parent.modification_tarif" class="ui-shadow ui-btn ui-corner-all">+</button></td>
				            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_tarif2" class="ui-shadow ui-btn ui-corner-all">-</button></td>
				      		  </tr>
				   			</tbody>
						</table>
			
			    	
		</div>
		</li>	
		<li>
	 			<div data-role="collapsible">
	 				<h2>Modifier l'ensemble des tarifs des actes:</h2>		 				
	 					 <fieldset class="ui-grid-b" >
		       				 <div class="ui-block-a" style="width:60%">
		       				 <label>Augmentation/diminution du tarif des actes :</label>
		     				 </div>
		       				 <div class="ui-block-b" style="width:20%">
		       				 <label>(pour augmenter de 5% inscrire: 5)</label>
		     				 <input type="text" name="modif_prix" id="modif_prix" value="">
		       				 </div>
		       				 <div class="ui-block-c" style="width:15%">		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: modif_prix_acte" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Modifier les tarifs" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Modifier les tarifs</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 </div>
		     			 </fieldset> 
		     			 <fieldset class="ui-grid-b" >
		     			 <div class="ui-block-a">
		     			 <button data-bind="attr: {value: 2}, click: arrondir" class="ui-shadow ui-btn ui-corner-all">Arrondir au centième</button>
		     			 </div>
		     			 <div class="ui-block-b">
		     			 <button data-bind="attr: {value: 1}, click: arrondir" class="ui-shadow ui-btn ui-corner-all">Arrondir au dizième</button>
		     			 </div>
		     			 <div class="ui-block-c">
		     			 <button data-bind="attr: {value: 0}, click: arrondir" class="ui-shadow ui-btn ui-corner-all">Arrondir a l'euro</button>
		     			 </div>
		     			 </fieldset>
		      </div>
	 </li>		 
	 <li>
	 			<div data-role="collapsible">
	 				<h2>Ajouter/Modifier la liste des médicaments:</h2>	
	 				<p>Les médicaments de cette liste apparaissent quand vous commencez à saisir le nom d'un médicament dans la barre de recherche des médicaments. Ils apparaissent avec un onglet bleu qui diffère de l'onglet rouge utilisé pour les autres médicaments.</p>
	 					
	 					 <fieldset class="ui-grid-c" >
		       				 <div class="ui-block-f" style="width:30%">
		       				  <label>Désignation medicament :</label>
		     				  <input type="text" name="designation_medic" id="designation_medic" value="">
		       				 </div>
		       				 <div class="ui-block-b" style="width:15%">
		       				 <label>Prix achat ht :</label>
		     				 <input type="text" name="prix_medic" id="prix_medic" value="">
		       				 </div>
		       				 <div class="ui-block-c" style="width:15%">
		       				 <label>lot :</label>
		     				 <input type="text" name="lot_medic" id="lot_medic" value="">
		       				 </div>
		       				  <div class="ui-block-d" style="width:20%">
		       				 <label>code centrale :</label>
		     				 <input type="text" name="code_medic" id="code_medic" value="">
		       				 </div>
		       				 <div class="ui-block-e" style="width:14%">		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: ajout_medic" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Ajouter ce medicament" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce médicament</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 </div>
		     			 </fieldset> 
		     			 <button id="print_tarif2" data-bind="click: imprimer_tarif2" class="ui-shadow ui-btn ui-corner-all">Imprimer les tarifs</button>
		     			 
		     			  <table data-role="table" id="table_medic" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
					      <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
					    	<thead>
					        <tr class="ui-bar-e"><th style="width:29%">Désignation</th><th style="width:10%">prix ht</th><th style="width:10%"></th><th style="width:5%"></th><th style="width:10%">lot</th><th style="width:10%"></th><th style="width:5%"></th><th style="width:10%">prix ttc</th><th style="width:10%"></th></tr>
					    	</thead>
				  		 	<tbody data-bind="foreach: computedData2">
				      		  <tr>
				            <td data-bind="attr: {id: id_select}, text: nom, click: $parent.modif_medic"></td>
				            <td data-bind="text: prixht"></td>
				            <td data-bind="html: nouveautarifmedoc()"></td>
				            <td><button data-bind="attr: {id: id_select}, click: $parent.modification_tarifmedoc" class="ui-shadow ui-btn ui-corner-all">+</button></td>
				            <td data-bind="text: lot"></td>
				            <td data-bind="html: nouveaulotmedoc()"></td>
				            <td><button data-bind="attr: {id: id_select}, click: $parent.modification_lotmedoc" class="ui-shadow ui-btn ui-corner-all">+</button></td>
				            <td data-bind="text: prixttc"></td>			            
				            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_medic" class="ui-shadow ui-btn ui-corner-all">-</button></td>
				      		  </tr>
				   			</tbody>
						</table>		    	
		</div>
		</li>	
		<li>
	 			<div data-role="collapsible">
	 				<h2>Modifier l'ensemble des tarifs des médicaments:</h2>		 				
	 					 <fieldset class="ui-grid-b" >
		       				 <div class="ui-block-a" style="width:60%">
		       				 <label>Augmentation/diminution du tarif des médicaments :</label>
		     				 </div>
		       				 <div class="ui-block-b" style="width:20%">
		       				 <label>(pour augmenter de 5% inscrire: 5)</label>
		     				 <input type="text" name="modif_prix_medic" id="modif_prix_medic" value="">
		       				 </div>
		       				 <div class="ui-block-c" style="width:15%">		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: modif_prix_medicament" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Modifier les tarifs" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Modifier les tarifs</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 </div>
		     			 </fieldset> 		     			
		      </div>
	 </li>
	 <li>
	 			<div data-role="collapsible">
	 				<h2>Fixer la marge des médicaments:</h2>		 				
	 					 <fieldset class="ui-grid-b" >
		       				 <div class="ui-block-a" style="width:60%">
		       				 <label>Marge devant être appliquée aux médicaments (en %, exemple 50%)</label>
		     				 </div>
		       				 <div class="ui-block-b" style="width:20%">
		       				 <label>(pour fixer 50% inscrire: 50)</label>
		     				 <input type="text" name="marge_medic" id="marge_medic" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['marge']); ?>">
		       				 </div>
		       				 <div class="ui-block-c" style="width:15%">		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: modif_marge_medic" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Mettre à jour tarif des médicaments" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Mettre à jour tarif des médicaments</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 </div>
		     			 </fieldset> 		     			
		      </div>
	 </li>	
	 <li>
	 			<div data-role="collapsible">
	 				<h2>Fixer votre TVA:</h2>		 				
	 					 <fieldset class="ui-grid-b" >
		       				 <div class="ui-block-a" style="width:60%">
		       				 <label>TVA (en %, exemple 20%)</label>
		     				 </div>
		       				 <div class="ui-block-b" style="width:20%">
		       				 <label>(pour fixer 20% inscrire: 20)</label>
		     				 <input type="text" name="tva" id="tva" value="<?php echo ((json_decode($mes_infos)==0) ? "" : $mes_infos2[0]['tva']); ?>">
		       				 </div>
		       				 <div class="ui-block-c" style="width:15%">		       				 
		       				 <a  href="index.html" data-role="button" data-bind="click: modif_marge_medic" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Mettre à jour tarif des médicaments" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Mettre à jour tarif des médicaments</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 </div>
		     			 </fieldset> 		     			
		      </div>
	 </li>		 
		</ul>		
		</div>
		<div class="paragraphe">
			<fieldset data-role="controlgroup" data-type="horizontal" >           
             	<legend>Je veux apparaitre (mon compte) dans la liste des cliniques référentes:</legend>						
       			<input type="radio" name="referent" id="radio1" value="0" <?php echo ((json_decode($mes_infos)==0) ? 'checked="checked"' : ($mes_infos2[0]['referent']==0 ? 'checked="checked"' : ''));?> />
         	<label for="radio1">oui</label>

         	<input type="radio" name="referent" id="radio2" value="1" <?php echo ((json_decode($mes_infos)==0) ? '' : ($mes_infos2[0]['referent']==0 ? '' : 'checked="checked"'));?> />
         	<label for="radio2">non</label>      				   				
       		 </fieldset>
       	</div>
       	<?php if( $garde_dispo_affichage== 1 && ($_SESSION['login']==$_SESSION['login2'])){ ?>
       	<div class="paragraphe">
			 <fieldset class="ui-grid-a">
		       				
		       				 <div class="ui-block-a">
       				<h2>Jours de la semaine où je suis indisponible (2 max):</h2>
	      				 <fieldset data-role="controlgroup" data-type="horizontal" data-bind="foreach: jour_ajoutees">
						    <input data-bind="attr: { value: id_select}, checked: $parent.jourevi2" type="checkbox" data-role="none" data-mini="true" />
						    <label data-role="none" ></label>
					     <span data-bind="text: nom_jour+' '+moment "></span>
					     </fieldset>
					 
					 </div>
					  <div class="ui-block-b" style="width:20%">
						<a href="index.html" data-role="button" data-bind="click: ajout_rotation2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
    				 </div>       				 
       	 		  </fieldset>
					 
					 <label>Indisponibilités à enregistrer dans la base:</label>
					  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
           			<thead>
      			  <tr class="ui-bar-e"><th>jour à éviter</th></tr>
    			</thead>
   				<tbody data-bind="foreach: rotation_ajoute">
        		<tr>
		           <td data-bind="html: jour_evi2"></td>		            
			    </tr>
			    </tbody>
				</table>							
       				<br />
		       		<h2>Noter vos  vacances :</h2>
		 				 <fieldset class="ui-grid-b">
		       				
		       				 <div class="ui-block-a">
		      					 <label for="date_debut13">Date de début indisponilité: </label>
								 <input type="date" data-role="datebox" name="date_debut13" id="date_debut13" data-options='{"mode": "datebox", "showInitialValue": true}' />              
		       				 </div>
		       				 <div class="ui-block-b">
		      					 <label for="date_fin13">Date de fin indisponibilité: </label>
								 <input type="date" data-role="datebox" name="date_fin13" id="date_fin13" data-options='{"mode": "datebox", "showInitialValue": true}' />        	
		   					 </div>
		       				 <div class="ui-block-c">
		       				 	  <a href="index.html" data-role="button" data-bind="click: ajout_indispo2" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter ce choix</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
		       				 </div>
		       	 		  </fieldset>       	 		  
		       	 		  <table data-role="table" id="" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
		          		<thead>
		      			  <tr class="ui-bar-e"><th>date debut indispo</th><th>date fin indispo</th><th></th></tr>
		    			</thead>
		   				<tbody data-bind="foreach: indispo_1">
		        		<tr>
				            <td data-bind="text: debut2"></td>
				            <td data-bind="text: fin2"></td>
				            <td><button data-bind="attr: {id: id_select}, click: $parent.supr_indispo_1" class="ui-shadow ui-btn ui-corner-all">-</button></td>
					    </tr>
					    </tbody>
						</table>
				</div>
       
       	<?php }else if( $garde_dispo_affichage==1){ ?>
       	<div class="paragraphe">			         
             	<legend>Vous devez vous connecter en mode 'clinique perso' pour pouvoir modifier vos préférences pour le tour de garde.</legend>	
       			
       	</div>       	
       	<?php }else{ ?>
       		<div class="paragraphe">			         
             	<legend>Vous n'êtes pas enregistré dans le tour de garde : contactez le responsable du tour pour en faire partie.</legend>	
       			
       	</div> 
       	<?php } ?>      	
		<fieldset data-role="fieldcontain" class="ui-grid-a"> 
				 <div class="ui-block-a"><a data-rel="back" id="retour" name="retour" data-role="button" data-icon="delete" data-theme="a" rel="external">retour</a></div>
				 <div class="ui-block-b"><a id="valid" name="valid" <?php echo( ($_SESSION['login']!=$_SESSION['login2']) ? 'class="ui-disabled"' : '' ); ?> data-role="button" data-icon="plus" data-bind='click: valider' data-theme="a">valider</a></div>
		</fieldset>
		</form>
</section>

<?php render('_footer')?>

