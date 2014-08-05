(function($) {

	
	
		$.widget('ui.ordonnance', {
			
			options: {
				choix_medic : [],
				liste_cat_delivre : [],
				mon_array_medoc : [],
				info_veto : [],
				largeur : 1100,
				hauteur : 560,
				veto : '',
				mon_logo:'./image/logo/essai1.jpg',
				ma_date:new Date(),
				mes_horaires:'',
				mes_competences:'',
				ordo_actu2:0,
				mon_id:'ordo_rapide',
				animal:''
			        
				}, // fin option
			_create: function() {  
					var self = this;  
					o = self.options;  
					el = self.element;
					
					var renderRow;
					renderRow = '<div id=\"popup_ordo\" data-role=\"popup\"></div>';
					$(renderRow).appendTo($(self.element));					
					
					self._formulaire_ordonnance();
					
			},// fin _create
			_init: function() { 
				var self = this;  
				o = self.options;  
				el = self.element;
				self._formulaire_ordonnance();				
			}, // fin _init
			
			/**
	         * sets div inside the popup with good css
	         * @param {mon_texte} just un exemple.
	         * @return open the popup.
	         */
			_formulaire_ordonnance: function() {
				var renderRow, self = this;	
				o = self.options;
							
				 $("#popup_ordo").html('');
		    	 var $popup_ordo = $("#popup_ordo").popup({
				        dismissible: false,
				        theme: "b",
				        overlyaTheme: "e",
				        transition: "pop"
				    }).on("popupafterclose", function () {
				       
				    }).css({
				        'width': "'+(o.largeur+100)+'px",
				        'height': "'+(o.hauteur+50)+'px",
				        'padding': '5px'
				    });
				    //create a title for the popup
				    $("<h3/>", {
				        text: "Rédaction ordonnance"
				    }).appendTo($popup_ordo);
				    $("<div id='popup_ordo_ensemble' style='overflow:auto;'></div>").appendTo($popup_ordo);
				    var $popup_ordo_ensemble = $("#popup_ordo_ensemble");
				    $("<div/>", {
				        html: self._formulaire_ordo(),
				    	style: "width:"+(o.largeur/2)+"px;float:left;padding: 10px;"
				    }).appendTo($popup_ordo_ensemble);
				    $("<div/>", {
				        html: self._canvas_ordo(),
				        style: "float:left;width:"+(o.largeur/2)+"px;margin: 13px 12px 5px 12px;position:relative;"
				    }).appendTo($popup_ordo_ensemble);	
				   
				    //create a back button
				    $("<fieldset class='ui-grid-b'><div id='zone1' class='ui-block-a' style='width:33%'></div><div id='zone2' class='ui-block-b' style='width:33%'></div><div id='zone3' class='ui-block-c' style='width:33%'></div></fieldset>").appendTo($popup_ordo);
					$("<a>", {
				        text: "Back",
				         "data-rel": "back"
				    }).buttonMarkup({
				        inline: false,
				        mini: true,
				        theme: "e",
				        icon: "back"
				    }).appendTo($("#zone1"));
				   // $("<a>", {
				   //     text: "Imprimer"				         
				   // }).buttonMarkup({
				   //     inline: false,
				   //     mini: true,
				   //     theme: "c",
				   //    icon: "back"
				   // }).on("click", function(){
				   // 	    var canvas=document.getElementById("mon_canvas");
				   // 	 	var win=window.open();
				   // 	    win.document.write("<br><img src='"+canvas.toDataURL()+"'/>");
				   //	    win.print();
				   // 	    win.location.reload();
				   // }).appendTo($("#zone2"));
				    $("<a>", {
				        text: "Enregistrer"				         
				    }).buttonMarkup({
				        inline: false,
				        mini: true,
				        theme: "c",
				        icon: "back"
				    }).on("click", function(){
				    	    var canvas=document.getElementById("mon_canvas");
				    	    var dataURL = canvas.toDataURL();
				    	    $.mobile.loading( 'show', {
								textonly : "true",
							    textVisible : "true",
							    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Enregistrement en cours...</h2></span>",
								iconpos : "right",
							    theme: "a"
							             	 
							});
				    	    $.ajax({
				    	    	  type: "POST",
				    	    	  url: "php/labo.php?action=save",
				    	    	  dataType: "json",
					              cache: false,
				    	    	  data: { 
				    	    	    // imgBase64: dataURL, id : o.mon_id
				    	    		  animal_id: o.mon_id, veto: o.info_veto, animal:o.animal,
				    	    		  horaires:o.mes_horaires, competences:o.mes_competences,
				    	    		  logo:o.mon_logo, medoc:o.mon_array_medoc,
				    	    		  ordo_commentaire:$("#ordo_commentaire2").val()			    	    		  
				    	    	  }
				    	    	}).done(function(p) {
				    	    	  console.log('saved'); 
				    	    	  console.log("retour serveur "+p);
				    	    	  $.mobile.loading('hide');
				    	    	  
				    	    	  var proceed = self._trigger( "ordo_complete", null, { 'valeur': p, 'id_animal' : o.mon_id} );
				    	    	  
				    	    	  
				    	    //	 window.open('aerogard/'+p);
				    	    //	 
				    	    //	 $('#dossier').fileTree({
			                //     	 	root: '../../sauvegarde/animaux/'+o.mon_id+'/',
			                 //    	 	script: './js/connectors/jqueryFileTree.php'
			                //     	 	
			                //            }, function(file) { 
			                //            	//alert(file.substr(6));			                            	
			                //            	window.location.href = file.substr(6);
			                 //           });
				    	    	 
				    	    	// window.open(''+p);
				    	    	 $popup_ordo.popup("close");	
				    	    	 
				    	    	  // If you want the file to be visible in the browser 
				    	    	  // - please modify the callback in javascript. All you
				    	    	  // need is to return the url to the file, you just saved 
				    	    	  // and than put the image in your browser.
				    	    	});
				    }).appendTo($("#zone3"));
				   
				    $popup_ordo.popup('open').trigger("create");
				    
				    self._affiche_exemple_ordo();
				    
				    // choose medic
				    $( "#designation_medic2" ).on( "listviewbeforefilter", function ( e, data ) {
				    	
				 	     var $ul = $( this ),
				         $input = $( data.input ),
				         value = $input.val(),
				         html = "";
				 	    console.log(value);
				     	 $ul.html( "" );
				     	 // if more 3 letter
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
				                        
				         })// end ajax
				         .then( function ( response ) {
				        	 console.log(JSON.stringify(response));
				         	  		 $.each( response, function ( i, val ) {                     
				                     html += '<li ><a class='+((val['lot']=="") ? "situation_pb" : "situation_normale")+' id="listemedic-' + (i) + '"  data-number="'+ i +'" data-number2="'+ val['id'] +'" data-number3="'+ val['centrale'] +'" data-number4="'+ val['cip'] +'" data-number5="'+ val['prixht'] +'" data-number6="'+ val['lot'] +'">' + val['nom'] + '</a></li>';
				                     
				                      });
				                 $ul.html( html );
				                 $ul.listview( "refresh" );
				                 $ul.trigger( "updatelayout");
				                 $('[id^=listemedic]').on("click", function(){
				                  	
				                	 console.log("id selectionné actuel = " +$(this).attr('id'));  
				                     console.log("medic selectionné = " +$(this).html());
				                     console.log("medicament selectionné = " +$(this).attr('data-number5'));  
				                   
				                 	//$('#lot_medic').val( $(this).attr('data-number6') );
				                 	$( "#designation_medic2" ).prev().find("input[data-type='search']").val($(this).html());
				                 	$ul.html( "" );
				                 	$ul.listview( "refresh" );
				                    $ul.trigger( "updatelayout");                	
				                	});// end click
				                
				             }); // end then
				        
				        } // end id sup 3 letter
					}); // end listview selector
				$("#choix_medic").change(function () {
					$("#parametre_medic").html("");
					html="";
					montexte="";
					var mon_nombre=$(this).prop("selectedIndex");
					var res = o.choix_medic[mon_nombre]['defaut'].split("");
					console.log(JSON.stringify(res));
					var variable = 0;
					$.each(o.choix_medic[$(this).prop("selectedIndex")], function(key, value) {						
						if($.isArray(value)){
							if(key=="nombre" || key=="dilution2"){								
								html += '<input name=\"detail_'+key+mon_nombre+'\" id=\"detail_'+key+mon_nombre+'\" style=\"max-width:50px\" value=\"'+value[1]+'\" type=\"text\">';								
								montexte+=" "+value[1];
							}else{
							html += '<select name=\"detail_'+key+mon_nombre+'\" id=\"detail_'+key+mon_nombre+'\" style=\"max-width:100px\" data-theme=\"d\" data-icon=\"gear\" data-inline=\"true\">';
							$.each(value, function(key2, value2) {
								
								html += '<option value=\"'+value2+'\" '+(res[variable]==String(key2) ? "selected=selected" : "")+'>'+value2+'</option>';
								if(res[variable]==String(key2)){
									montexte+=" "+value2;									
								}
								
							});//end each : second one
							html += '</select>';
							}// end else for exclusion of sliders
							variable++;
							
						}// end isArray
						else{
							if(key=="seq1"){	
							montexte+=" "+value;
							}//end if key==
							
						}// end is not an array					
						
					});	// end each choix _medic
					$("#parametre_medic").html(html);
					self._affiche_texte(montexte);
					$('[id^=detail_]').change(function () {	
						montexte="";						
						$.each(o.choix_medic[$("#choix_medic").prop("selectedIndex")], function(key, value) {						
							if($.isArray(value)){
								if(key=="nombre" || key=="dilution2"){								
									montexte+=" "+$("#detail_"+key+mon_nombre).val();
								}else{
									
									montexte+=" "+$("#detail_"+key+mon_nombre).find(":selected").text();							
										
								}// end else for exclusion of sliders
																
							}// end isArray
							else{
								if(key=="seq1"){	
								montexte+=" "+value;
								}//end if key==
								
							}// end is not an array
							
							
							
						});	// end each choix _medic						
						
						self._affiche_texte(montexte);
					});// end $('[id^=detail_]').change
					
					
					
				});// fin #choix_medic change
				$("#supr_list").on('click', function () {
					$( "#designation_medic2" ).html("");					
					
				}); //end supr_list click
				$("#analyse_pdf2").on('click', function () {
					var nb_delivre = $("#nb_delivr").val()+" "+$("#choix_condi").find(":selected").text();
					o.mon_array_medoc.push({nom : $( "#designation_medic2" ).prev().find("input[data-type='search']").val(), qte : nb_delivre, texte : $("#ordo_commentaire").val()});
					self._affiche_medoc();		
					
					
				
				}); //end analyse_pdf2 click			
				
				$( "#autocomplete" ).on( "listviewbeforefilter", function ( e, data ) {
			    	
			    	   var $ul = $( this ),
			            $input = $( data.input ),
			            value = $input.val(),
			            html = "";
			        $ul.html( "" );
			        $('#listeax').html( "" );
			        $('#listeax').listview( "refresh" );
			        if ( value && value.length > 2 ) {
			            $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
			            $ul.listview( "refresh" );
			            $.ajax({

			            	type: "GET",
			                url: "php/accueil.php?action=recherche",
			                dataType: "json",
			                cache: false,
			                data:  {
			                    recherche: $input.val(), choix: ((typeof($('#choix_manu').val()) == 'undefined') ? "client.nom" : $('#choix_manu').val()), choix2: ((typeof($('#choix_manu2').val()) == 'undefined') ? "" : $('#choix_manu2').val())  
			                }
			                           
			            })// end ajax
			            .then( function ( response ) {
			            	var currentTime = new Date()
			                $.each( response, function ( i, val ) {			                   
			                    html += '<li ><a class='+((val[0]['variable']==1) ? "situation_normale" : "situation_pb")+' id="listemanuclient-' + (i) + '"  data-number6="'+ i +'" data-number="'+ val[0]['nom'] +'" data-number2="'+ val[0]['prenom'] +'" data-number3="'+ val[0]['adresse'] +'" data-number4="'+ val[0]['code'] +'" data-number5="'+ val[0]['ville'] +'">' + val[0]['nom'] + " " + val[0]['prenom'] + " " + val[0]['ville'] + '</a></li>';			                   
			                     });
			                $ul.html( html );
			                $ul.listview( "refresh" );
			                $ul.trigger( "updatelayout");
			                $('[id^=listemanuclient]').on("vmouseover", function(){
			                var self2 = this;
			                console.log("scroll actuel = " +$(this).offset().top);            	
			              
			            	$('#listeax').html( "" );
			           		 html = "";			           	
			           		 $.each( response[$(this).data('number6')], function ( i, val2 ) {
			                     html += '<li><a rel="external" href="" class='+((val2['variable2']==1) ? "situation_normale" : "situation_pb")+' id="listemanuax-' + (i) + '" data-number2="'+ val2['espece'] +'" data-number3="'+ val2['nom_a'] +'" data-number4="'+ val2['id'] +'">' + val2['nom_a'] + " " + val2['espece'] + " " + val2['sexe'] + " " + (Math.floor((currentTime.getTime()-val2['datenais'])/(1000*60*60*24*365)))+ " an(s)" + '</a></li>';
			                
			            	  });
			           				       	    	
			           	$('#listeax').html( html );
			           	$('#listeax').listview( "refresh" );
			           	$('#listeax').offset({ top: $(this).offset().top });
			           	$('[id^=listemanuax]').on("click", function(){
			           			o.mon_id = $(this).data('number4');
			           			$("#animal_choice").val("pour le "+$(this).data('number2')+" "+$(this).data('number3').toUpperCase()+" de "+$(self2).data('number').toUpperCase()+" "+$(self2).data('number2').replace(/^\w/, function($0) { return $0.toUpperCase(); })+" "+$(self2).data('number3')+" "+$(self2).data('number4')+" "+$(self2).data('number5').replace(/^\w/, function($0) { return $0.toUpperCase(); })).keyup();
			           			o.animal = $("#animal_choice").val();
			           			self._affiche_medoc();	
		           		});
			           	});//end vmouseover
			            }); // end then
			        }// end value >2
			    });// end autocomplete
				$("#animal_choice").on("change",  function(){
					o.animal = $(this).val();
					self._affiche_medoc();					
				});
				$("#animal_choice").keyup(function(){
					o.animal = $(this).val();
					self._affiche_medoc();					
				});
				$("#ordo_commentaire2").on("change",  function(){
					self._affiche_medoc();					
				});
				$("#ordo_commentaire2").keyup(function(){
					self._affiche_medoc();					
				});
								
			},//fin _formulaire_ordonnance
			 /**
	         * create the form
	         * @return {String} the form.
	         */
			_formulaire_ordo: function() {
				var renderRow, self = this;  
				o = self.options;  
				el = self.element;
				
				renderRow = '<div id=\"formulaire_ordo\">';
				renderRow += '<label for=\"designation_medic2\">Choix animal:</label>';				
				renderRow +='<fieldset class=\"ui-grid-b\">';
				renderRow +='<div class=\"ui-block-a\" style="width:50%">';
				renderRow += '<ul id=\"autocomplete\" data-role=\"listview\" data-inset=\"true\" data-filter=\"true\" data-filter-placeholder=\"Nom proprio\" data-filter-theme=\"d\" data-split-icon=\"gear\"></ul>';
				renderRow +='</div>';
				renderRow +='<div class=\"ui-block-b\" style="width:50%">';
				renderRow += '<ul data-role=\"listview\" name=\"listeax\" id=\"listeax\" data-split-icon=\"gear\"></ul>';
				renderRow += '</div>';		
				renderRow +='</fieldset>';	
				renderRow +='<label for=\"animal_choice\">animal traité :</label>';
				renderRow +='<textarea name=\"animal_choice\" id=\"animal_choice\"></textarea>';
				renderRow += '<label for=\"designation_medic2\">Choix médicament:</label>';
				renderRow +='<fieldset class=\"ui-grid-a\">';
				renderRow +='<div class=\"ui-block-a\" style="width:80%">';
				renderRow += '<ul id=\"designation_medic2\" data-role=\"listview\" data-inset=\"true\" data-filter=\"true\" data-filter-placeholder=\"medicament\" data-filter-theme=\"d\" data-split-icon=\"gear\"></ul>';
				renderRow +='</div>';
				renderRow +='<div class=\"ui-block-b\" style="width:15%">';
				renderRow +='<a href=\"#\" name=\"supr_list\" id=\"supr_list\" data-role=\"button\" data-icon=\"delete\" data-iconpos=\"notext\" data-inline=\"true\">Supprimer liste</a>';
				renderRow +='</div>';	
				renderRow +='</fieldset>';
				renderRow += '<label>Quantité délivrée</label>';
				renderRow +='<fieldset class=\"ui-grid-a\">';
				renderRow +='<div class=\"ui-block-a\" style="width:20%">';
				renderRow +='<input type=\"text\" name=\"nb_delivr\" id=\"nb_delivr\" value=\"1\">';
				renderRow +='</div>';
				renderRow +='<div class=\"ui-block-b\" style=\"width:40%\">';
				renderRow += '<select name=\"choix_condi\" id=\"choix_condi\" data-theme=\"d\" data-icon=\"gear\" data-inline=\"true\">';
				$.each(o.liste_cat_delivre, function(key, value) {
					renderRow += '<option value=\"'+value+'\">'+value+'</option>';
				});	
				renderRow +='</select>';
				renderRow +='</div>';
				renderRow +='</fieldset>';				
				renderRow += '<label for=\"choix_medic\">Caractéristiques :</label>';
				renderRow += '<select name=\"choix_medic\" id=\"choix_medic\" data-theme=\"d\" data-icon=\"gear\" data-inline=\"true\">';
				$.each(o.choix_medic, function(key, value) {
					renderRow += '<option value=\"mon_choix_medic_'+key+'\">'+value.nom+'</option>';
				});	
				renderRow +='</select>';
				renderRow +='<div id=\"parametre_medic\"></div>';
				renderRow +='<label for=\"ordo_commentaire\">intitulé prescription :</label>';
				renderRow +='<textarea name=\"ordo_commentaire\" id=\"ordo_commentaire\"></textarea>';
				renderRow +='<button id=\"analyse_pdf2\" class=\"ui-shadow ui-btn ui-corner-all\">Enregistrer  cet intitulé</button>';
				renderRow +='<div id=\"div_tableau_medic\"><table id=\"tableau_medic\" style=\"width:100%\"><thead><tr><th>Medicament</th><th>Supprimer</th></tr></thead>';
				renderRow +='<tbody></tbody></table></div>';
				renderRow +='<label for=\"ordo_commentaire2\">commentaire :</label>';
				renderRow +='<textarea name=\"ordo_commentaire2\" id=\"ordo_commentaire2\"></textarea>';
				
				//renderRow +='<fieldset class=\"ui-grid-c\">';
				//renderRow +='<label for=\"choix_analyse\">Rédaction ordonnance :</label>';
				//renderRow +='<div class=\"ui-block-a\">';
				//renderRow +='<select name=\"choix_medic\" id=\"choix_medic\" data-theme=\"d\" data-icon=\"gear\" data-inline=\"true\">'
				//			 <select id="choix_analyse" data-bind="options: analyse, optionsText: 'nom', optionsValue: 'id_selectionne2', event: { change: selection_analyse }">
	     		//				   <!-- <option data-bind="text: nom, click: $parent.selection_analyse, attr: {id: id_selectionne}"></option> --> 
	    		//			</select>
	       		//		 </div>
	       		//		 <div class="ui-block-b" data-bind="visible: unite">
	       		//		 	
	       		//		    	<input type="text" name="choix_analyse2" id="choix_analyse2" placeholder="Entrer la valeur de l'analyse">
				//				<span data-bind="text: unite" id="choix_analyse4" ></span>
				//					
				//		 </div>  
				//		 <div class="ui-block-c" data-bind="visible: unite">
				//				<input type="date" data-role="datebox" name="choix_analyse3" id="choix_analyse3" data-options='{"mode": "datebox", "showInitialValue": true}' />
				//			</div>   
				//		 <div class="ui-block-d" data-bind="visible: unite">
				//				<a href="index.html" data-role="button" data-bind="click: ajout_analyse" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text">Ajouter cette analyse</span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>
				//				</div>       				
	       	 //</fieldset>
	       	 
	       	 
	       	 
				renderRow += '</div>'; // fin formulaire_ordo	
				
				return renderRow
				
			},//fin _formulaire_ordo
			/**
	         * add value of parameter and return the string
	         * @param {montexte} String text to add.
	         * @return string : sum of value for medicine.
	         */
			_affiche_texte: function(montexte) {
				var renderRow, self = this;  
				o = self.options;  
				el = self.element;
				
				
				$("#ordo_commentaire").val(montexte).keyup();
				
				//$('[id^=detail_]').each( function(){
				//} );
				
			},
			// end _affiche_texte
			/**
	         * add add vcalue to array , put it in a table and show on the canvas
	         * @param {???} no param for the moment.
	         * @return nothing just show and modify array
	         */
			_affiche_medoc: function() {
				var renderRow, self = this;  
				o = self.options;  
				el = self.element;
				$('#mon_canvas').clearCanvas();
				self._affiche_exemple_ordo();
				var tbody = $('#tableau_medic tbody'), props = ["nom"];
				tbody.html("");
					$.each(o.mon_array_medoc, function(i, medoc) {
							var tr = $('<tr>');
								$.each(props, function(j, prop) {
									$('<td>').html(medoc[prop]).appendTo(tr);
									$('<td>').html('<button id=\"supr_medoc_'+i+'\" data-numero=\"'+i+'\"class=\"ui-shadow ui-btn ui-corner-all\" style=\"width:100%\">-</button>').appendTo(tr);   
								}); // each props
							tbody.append(tr);
							
							
							 
							 $('#mon_canvas').drawText({
							    	layer: true,
							    	 name: 'mes_medoc_nom_'+i,
							    	 fillStyle: '#000',
							    	  fontSize: '10pt',
							    	  fontFamily: 'Trebuchet MS, sans-serif',
							    	  text: medoc['nom'],
							    	  fromCenter: false,
							    	  x: 10, y: o.ordo_actu2,
							    	  align: 'left',
							    	  maxWidth: 300
							    	});
							 $('#mon_canvas').drawText({
							    	layer: true,
							    	 name: 'mes_medoc_qte_'+o.mon_array_medoc.length,
							    	 fillStyle: '#000',
							    	  fontSize: '10pt',
							    	  fontFamily: 'Trebuchet MS, sans-serif',
							    	  text: medoc['qte'],
							    	  fromCenter: false,
							    	  x: o.largeur/2-100, y: o.ordo_actu2,
							    	  align: 'right',
							    	  maxWidth: 200
							    	});
							    o.ordo_actu2 += $('#mon_canvas').measureText('mes_medoc_nom_'+i).height + 15;
							    $('#mon_canvas').drawText({
							    	layer: true,
							    	 name: 'mes_medoc_text_'+i,
							    	 fillStyle: '#000',
							    	  fontSize: '10pt',
							    	  fontFamily: 'Trebuchet MS, sans-serif',
							    	  text: medoc['texte'],
							    	  fromCenter: false,
							    	  x: 10, y: o.ordo_actu2,
							    	  align: 'left',
							    	  maxWidth: o.largeur-20
							    	});
							    o.ordo_actu2 += $('#mon_canvas').measureText('mes_medoc_text_'+i).height + 15;

							    
							    
						});//each mon_array_medoc
					$('#mon_canvas').drawText({
				    	layer: true,
				    	 name: 'mes_medoc_text_p2',
				    	 fillStyle: '#000',
				    	  fontSize: '10pt',
				    	  fontFamily: 'Trebuchet MS, sans-serif',
				    	  text: 'commentaire',
				    	  fromCenter: false,
				    	  x: 10, y: o.ordo_actu2,
				    	  align: 'left',
				    	  maxWidth: 50
				    	});
					$('#mon_canvas').drawText({
				    	layer: true,
				    	 name: 'mes_medoc_text2',
				    	 fillStyle: '#000',
				    	  fontSize: '10pt',
				    	  fontFamily: 'Trebuchet MS, sans-serif',
				    	  text: $("#ordo_commentaire2").val(),
				    	  fromCenter: false,
				    	  x: $('#mon_canvas').measureText('mes_medoc_text_p2').width+20+10, y: o.ordo_actu2,
				    	  align: 'left',
				    	  maxWidth: o.largeur-20-60
				    	});
				    o.ordo_actu2 += $('#mon_canvas').measureText('mes_medoc_text2').height + 15;

					
					$("[id^=supr_medoc]").on('click', function () {	
						console.log($(this).data('numero'));	
						o.mon_array_medoc.splice($(this).data('numero'), 1);
						console.log(JSON.stringify(o.mon_array_medoc));	
						self._affiche_medoc();
						
					});
					
					
				
			},// end _affiche_medoc
			/**
	         * create canvas to show medecine exmemple
	         * @param {???} no param for the moment.
	         * @return string
	         */
			_canvas_ordo: function() {
				var renderRow2, self = this;  
				o = self.options;  
				el = self.element;
				renderRow2 = '<div id=\"exemple_ordo\">';
				renderRow2 += '<canvas name=\"mon_canvas\" id=\"mon_canvas\" width=\"'+(o.largeur/2)+'\" height=\"'+o.hauteur+'\"></canvas>';
				renderRow2 += '</div>';
				
				return renderRow2
				
			},// END _canvas_ordo	
			/**
	         * texte of the canvas at the beginning
	         * @param {???} no param for the moment.
	         * @return nothing just initialise the text of the canvas
	         */
			_affiche_exemple_ordo: function() {
				self = this;  
				o = self.options;  
				el = self.element;	
				var ordo_depart = 30;
				var ordo_actu=0;
				$('canvas').drawRect({
					  fillStyle: '#FFF',
					  x: 0, y: 0,
					  width: o.largeur/2,
					  height: o.hauteur,
					  cornerRadius: 10,
					  fromCenter: false
					})
				$('#mon_canvas').drawImage({
				 layer: true,
				  source: o.mon_logo,
				  x: -30, y: -30,
				  scale: 0.6,
				  fromCenter: false
				});
					console.log("info_veto "+JSON.stringify(o.info_veto));
			    $('#mon_canvas').drawText({
			    	 layer: true,
			    	 name: 'mon_nom',
			    	 fillStyle: '#000',
			    	  fontStyle: 'bold',
			    	  fontSize: '12pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.info_veto[0]['nom'],
			    	  x: (o.largeur/4), y: ordo_depart,
			    	  align: 'center',
			    	  maxWidth: 300
			    	});
			    ordo_actu += ordo_depart + $('#mon_canvas').measureText('mon_nom').height;
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'mon_adresse',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.info_veto[0]['adresse'],
			    	  x: (o.largeur/4), y: ordo_actu,
			    	  align: 'center',
			    	  maxWidth: 300
			    	});
			    ordo_actu += $('#mon_canvas').measureText('mon_adresse').height;
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'mon_code',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.info_veto[0]['code']+' '+o.info_veto[0]['commune'],
			    	  x: (o.largeur/4), y: ordo_actu,
			    	  align: 'center',
			    	  maxWidth: 300
			    	});
			    ordo_actu += $('#mon_canvas').measureText('mon_code').height;
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'mon_tel',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.info_veto[0]['tel'],
			    	  x: (o.largeur/4), y: ordo_actu,
			    	  align: 'center',
			    	  maxWidth: 300
			    	});
			    ordo_actu += $('#mon_canvas').measureText('mon_tel').height + 30;
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'le_veto',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: 'Dr vétérinaire :'+o.veto,
			    	  fromCenter: false,
			    	  x: 10, y: ordo_actu,
			    	  align: 'left',
			    	  maxWidth: 300
			    	});
			    ordo_actu += $('#mon_canvas').measureText('le_veto').height + 30;
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'valeur_date',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: "le "+String(o.ma_date.toString("d MMM yyyy")),
			    	  fromCenter: false,
			    	  x: 10, y: ordo_actu,
			    	  align: 'left',
			    	  maxWidth: 300
			    	});
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'mon_animal',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.animal,
			    	  fromCenter: false,
			    	  x: o.largeur/2-200, y: ordo_actu,
			    	  align: 'right',
			    	  maxWidth: 200
			    	});			    
			    ordo_actu += $('#mon_canvas').measureText('mon_animal').height + 40;
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'mes_horaires',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.mes_horaires,
			    	  x: (o.largeur/4),y: o.hauteur-60,
			    	  align: 'center',
			    	  maxWidth: 300
			    	});
			    $('#mon_canvas').drawText({
			    	layer: true,
			    	 name: 'mes_competences',
			    	 fillStyle: '#000',
			    	  fontSize: '10pt',
			    	  fontFamily: 'Trebuchet MS, sans-serif',
			    	  text: o.mes_competences,
			    	  x: (o.largeur/4),y: o.hauteur-20,
			    	  align: 'center',
			    	  maxWidth: 300,
			    	});
			    
			    o.ordo_actu2 = ordo_actu;
			    
			    
			    
			    
			}// end _affiche_exemple_ordo
		
		}//		
		)
	
})(jQuery);