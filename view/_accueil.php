<?php render('_header',array('title'=>$title))?>
<h2></h2>
<script type="text/javascript">
$( document ).ready(  function() {	
	var refreshTime = 1200000; // in milliseconds, so 20 minutes
    window.setInterval( function() {
    	document.location.href="index.php";
    }, refreshTime );
	 var liste_proprio= $( "#listeclient" ).html();
	 var salle_attente = <?php echo $salle_attente;?>;
	 var rapport_ref = <?php echo $rapport_ref;?>;
	 var rapport_redige = <?php echo $rapport_redige;?>;
	 var casrefere = <?php echo $casrefere;?>;
	 var planning = <?php echo $planning;?>;
	 var liste_vetos = <?php echo $liste_vetos;?>;
	 var historique = <?php echo $historique;?>;
	 var perso = <?php echo json_encode($_SESSION['login2']);?>;
	 var list_text = <?php echo TXT_ACCUEIL_JSPARTS;?>;
		 function selectionclient(response, event, ui)
		 {		 	
		 	$('#listeax').html( "" );
		 	 html = "";
		 	$.each( response[$(this).attr('id')], function ( i, val2 ) {
		        html += "<li>" + val2['nom_a'] + "</li>";		        
		    });
	
		 	$('#listeax').html( html );
		 	$('#listeax').selectmenu("refresh"); 
		 }
	
		 function onError2(data, status)
		 {
		  $('h2').html(list_text.erreur1).css("background-color", "red");
		 }
		 function fermeture_fenetre()
		 {
		 	$( "#autocomplete" ).html( "" );		
		 	$('#listeax').html( "" );
		 	 $('#listeax').listview( "refresh" );
		 	 $('#autocomplete').listview( "refresh" );
		 	 $("#champrecherchealpha").val("");
		 	 $("#listealphaax" ).html( "" );
		 	 $('#listealphaax').listview( "refresh" );
		 }
		function objet_mur_creation(data,index) {
			this.valeur = ko.observable("tag"+String(data['importance']));
			this.id=ko.observable(data.id);
			this.texte=ko.observable(data.texte);
		};
	 	function ViewModel() {
		   var self = this;
		   self.liste_mur = ko.observableArray([]);
		   var liste_mur_array = <?php echo $liste_mur; ?>;
		   var objet_mur = $.map(liste_mur_array, function(item,index) {
			   return new objet_mur_creation(item,index);               
           });
		   self.liste_mur(objet_mur);
		   self.supr_liste_mur = function(item, event) {
			   
			   $.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.delete_case+"</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
		 		$.ajax({		    
		        	type: "POST",
		            url: "php/accueil.php?action=suppression3",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			id_mur : $(event.target).attr('value')   
		            },	 
		            success: function(data){
		            	$.mobile.loading('hide');	
		            	$(event.target).parent("li").remove();	 			                       
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
		};
		ko.applyBindings(new ViewModel());
			 
		var html_historique ='';
		 $.each(historique, function(key, val) {
			 html_historique += '<li ><a rel="external" href="'+ val['url']+'" id="liste_historique-' + (key) + '"  data-number="'+ key +'" data-number2="'+ val['id_c'] +'"><img src="'+((val['espece']=='chien') ? "image/icon_chien.png" : "image/icon_chat.png")+'" alt="chat" class="ui-li-icon ui-corner-none">' + val['nom_p'] + " " + val['prenom_p'] + " --> " + val['espece'] + " " + val['nom_a'] + " : " + val['motif'] +'<span class="ui-li-count">'+((null == val['montant']) ? '0' : val['montant'])+'</span></a></li>';
	     });
		$( "#historique" ).html( html_historique );
		$( "#historique" ).listview( "refresh" );
		$( "#historique" ).trigger( "updatelayout");
	 	
		var html_casrefere ='';
	 $.each(casrefere, function(key, val) {
		html_casrefere += '<li ><a rel="external" href="?id_casrefere='+ val['id']+'" id="liste_casrefere-' + (key) + '"  data-number="'+ key +'" data-number2="'+ val['id'] +'">'+list_text.refered_case+" " + val['formatted_date'] + ": " + val['nom_p'] + "/"+ list_text.refered_case2 +" "+ val['veto_origin'] + "/" + val['nom_a'] + "/" + val['resume'] +'</a><a href="#" data-number="'+ val['id'] +'">'+list_text.delete_+'</a></li>';
     });
	$( "#cas_refere" ).html( html_casrefere );
	$( "#cas_refere" ).listview( "refresh" );
	$( "#cas_refere" ).trigger( "updatelayout");
$("#cas_refere span.ui-btn-text").text(list_text.refered_case3+" "+casrefere.length+" "+list_text.refered_case_to_deal);
	
$("#cas_refere li .ui-li-link-alt").on("click", function(){	
	self =$(this);			 		
 		$.mobile.loading( 'show', {
			textonly : "true",
		    textVisible : "true",
		    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.delete_2+"</h2></span>",
			iconpos : "right",
		    theme: "a"
		             	 
		});
 		$.ajax({		    
        	type: "POST",
            url: "php/accueil.php?action=suppression2",
            dataType: "json",
            cache: false,
            data:  {
 			id : $(this).data('number')   
            },	
            success: function(data){
            	$.mobile.loading('hide');	            		 
        		self.parent("li").remove();
        		$("#cas_refere span.ui-btn-text").text(list_text.refered_case3+" "+$("#cas_refere").children().length+" "+list_text.casetotreat);
        		$( "#cas_refere" ).listview( "refresh" );
        		$( "#cas_refere" ).trigger( "updatelayout");	
        	
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
	 var html_rapport_redige ='';
	 $.each(rapport_redige, function(key, val) {
		html_rapport_redige += '<li ><a rel="external" href="?id_rapport_redige='+ val['id']+'" id="liste_rapport_redige-' + (key) + '"  data-number="'+ key +'" data-number2="'+ val['id'] +'">'+list_text.gardedu+" "+ val['formatted_date'] + ": " + val['nom_p'] + list_text.gardedu + val['permission'] + "/" + val['nom_a'] + "/" + val['resume'] +'</a></li>';
     });
	$( "#rapport_redige" ).html( html_rapport_redige );
	$( "#rapport_redige" ).listview( "refresh" );
	$( "#rapport_redige" ).trigger( "updatelayout");
$("#rapportredige span.ui-btn-text").text(list_text.reportdrafted+" "+rapport_redige.length+" "+list_text.reporttodraft);
	


	 var html_rapport_ref ='';
		 $.each(rapport_ref, function(key, val) {
			html_rapport_ref += '<li ><a rel="external" href="?id_rapport_ref='+ val['id']+'" id="liste_rapport_ref-' + (key) + '"  data-number="'+ key +'" data-number2="'+ val['id'] +'">' + val['formatted_date'] + ": " + val['nom_p'] + "/" + val['nom_a'] + "/ vu par :" + val['veto_origin'] + "/" + val['resume'] +'</a></li>';
	     });
		$( "#rapport_ref" ).html( html_rapport_ref );
		$( "#rapport_ref" ).listview( "refresh" );
		$( "#rapport_ref" ).trigger( "updatelayout");
	$("#rapportref span.ui-btn-text").text(list_text.reportreceived+" "+rapport_ref.length+" "+list_text.reporttodraft);
		
	
 		var html_salle_attente ='';
 		 $.each(salle_attente, function(key, val) {
 			
 			html_salle_attente += '<li ><a rel="external" href="?id_salle_attente='+ val['id']+'&valeur_attente=regular" id="liste_salle_attente-' + (key) + '"  data-number="'+ key +'" data-number2="'+ val['id'] +'">' + val['formatted_date'] + ": " + val['nom_p'] + "/" + val['nom_a'] + "/" + val['resume'] +'</a><a href="#" data-number="'+ val['id'] +'" data-number2="'+ val['nom_a'] +'" data-number3="'+ val['nom_p'] +'">'+list_text.delete_+'</a></li>';
 	     });
 		$( "#salle_attente" ).html( html_salle_attente );
 		$( "#salle_attente" ).listview( "refresh" );
 		$( "#salle_attente" ).trigger( "updatelayout");
		$("#salleattente span.ui-btn-text").text(list_text.youhave+salle_attente.length+list_text.animalsinwaitingroom);
 		//$("#salle_attente li a:eq(1)").on("click", function(){
		$("#salle_attente a.ui-li-link-alt").on("click", function(){	
			console.log("ok");
			self =$(this);			 		
		 		$.mobile.loading( 'show', {
					textonly : "true",
				    textVisible : "true",
				    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.deleteinprogress+"</h2></span>",
					iconpos : "right",
				    theme: "a"
				             	 
				});
		 		$.ajax({		    
		        	type: "POST",
		            url: "php/accueil.php?action=suppression",
		            dataType: "json",
		            cache: false,
		            data:  {
		 			id : $(this).data('number'), nom_a : $(this).data('number2'), nom_p : $(this).data('number3')   
		            },	
		            success: function(data){
		            	$.mobile.loading('hide');
		            	self.parent("li").remove();
		        		$("#salleattente span.ui-btn-text").text(list_text.youhave+$("#salle_attente").children().length+list_text.animalsinwaitingroom);
		        		$( "#salle_attente" ).listview( "refresh" );
		        		$( "#salle_attente" ).trigger( "updatelayout");	           
                    
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


 		
	$("#listeclient").change(function() {
	    var selected = $(this).find(":selected").text();	   
	    html="";   
	    if($("#choix_alpha").val() == "proprio"){
	    	axaafficher = <?php echo $client;?>;
	    	var currentTime = new Date();
	    	html += '<li><a rel="external" href="?idpro='+ axaafficher[$(this).val()]['id2'] +'&idani=0">'+list_text.saveotheranimal+'</a></li>';
	    	$.each( axaafficher[$(this).val()], function ( i, val ) {

	    		 html += '<li><a rel="external" href="?idpro3='+ val['id2'] +'&idani='+ val['id'] +'" class='+((val['variable2']==1) ? "situation_normale" : "situation_pb")+' id="listealphaax-' + (i) + '" data-number="'+ i +'" data-number2="'+ val['id'] +'">' + val['nom_a'] + " " + val['espece'] + " " + val['sexe'] + " " + (Math.floor((currentTime.getTime()-val['datenais'])/(1000*60*60*24*365)))+ list_text.yearold +'</a><a href="?idpro='+ val['id2'] +'&idani='+ val['id'] +'">'+list_text.changetheanimalfolder+'</a></li>';

		  	    	});
       	    	
	    }else if($("#choix_alpha").val() == "animaux"){
	    	
	    	axaafficher = <?php echo $animaux;?>;
	    	
	    		
	    	   html += '<li ><a href="?idpro3='+ axaafficher[$(this).find(":selected").data('number')]['id2']+'&idani='+$(this).find(":selected").data('number2')+'" class='+(( axaafficher[$(this).find(":selected").data('number')]['variable']==1) ? "situation_normale" : "situation_pb")+' id="listemanuclient"  data-number="" data-number2="'+  axaafficher[$(this).find(":selected").data('number')]['id2'] +'">' +  axaafficher[$(this).find(":selected").data('number')]['nom'] + " " +  axaafficher[$(this).find(":selected").data('number')]['prenom'] + " " +  axaafficher[$(this).find(":selected").data('number')]['ville'] + '</a><a href="?idpro='+ axaafficher[$(this).find(":selected").data('number')]['id2'] +'">'+list_text.changethecustomerfolder+'</a></li>';
	    		
	    }
	    $("#listealphaax").html( html );
	    $("#listealphaax").listview( "refresh" );
	});
	$("#listeclient").hover(function (e)
			{
				$( "#autocomplete" ).html( "" );		
				$('#listeax').html( "" );
				 $('#listeax').listview( "refresh" );
				 $('#autocomplete').listview( "refresh" );
				$('[id^=listealphaclient]').on("vmouseover", function(){

//alert("cool");
				});
			});
	$("#champrecherchealpha").keyup(function () {
		 var filter = "^"+$(this).val();
		 $("#listeclient option").each(function(){
			 if ($(this).attr('data-recherche').search(new RegExp(filter, "i")) < 0) {
				
			 }else {
				 $(this).attr("selected","selected");
				 $(this).change();
				 return false;
	            }
	        });
		 $('#listeclient').selectmenu( "refresh" );
	});
	$("#choix_alpha").hover(function() {		
		fermeture_fenetre();		
	});
	 $("#choix_alpha").change(function () {
		
		 $( "#listeclient" ).html( "" );
		 $('#listeclient').selectmenu( "refresh" );
		 var listeaafficher;
		 html="";
		 if($(this).val() == "proprio"){
			 html=liste_proprio;
		 }else if($(this).val() == "animaux"){
			 listeaafficher = <?php echo $animaux;?>;
			 html += '<option value="" data-placeholder="true" data-recherche="">'+list_text.chooseananimal+'</option>';
			 $.each( listeaafficher, function ( i, val ) {
			 html += '<option id="listealphaax-' + (i) +'" data-number="'+ i +'" data-number2="'+ val['id'] +'" data-recherche="'+ val['nom_a'] +'">' + val['nom_a'] + " " + val['espece'] + " " + val['sexe'] + "</option>";
			 });
		 }

           	$('#listeclient').html( html );
           	$('#listeclient').selectmenu( "refresh" );	
        	
	 });	
	$("#choix_manu2").hover(function() {
		fermeture_fenetre();
	});
	$("#choix_manu2").change(function() {
		fermeture_fenetre();
		
	});
	$("#choix_manu").hover(function() {
		fermeture_fenetre();
	});
	$("#choix_manu").change(function() {
		fermeture_fenetre();
		
	});
	
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
                           
            })
            .then( function ( response ) {
            	var currentTime = new Date()
                $.each( response, function ( i, val ) {
                    if($('#choix_manu').val()== "client.nom"){
                    html += '<li ><a title="' + val[0]['nom'] + " " + val[0]['prenom'] + " " + val[0]['ville'] + '" class='+((val[0]['variable']==1) ? "situation_normale" : "situation_pb")+' id="listemanuclient-' + (i) + '"  data-number="'+ i +'" data-number2="'+ val[0]['id2'] +'">' + val[0]['nom'] + " " + val[0]['prenom'] + " " + val[0]['ville'] + '</a><a href="index.php?idpro='+ val[0]['id2'] +'">'+list_text.changethecustomerfolder+'</a></li>';
                    }else if($('#choix_manu').val()== "animal.nom_a"){
                    html += '<li><a title="' + val['nom_a'] + " " + val['espece'] + " " + val['sexe'] + " " + (Math.floor((currentTime.getTime()-val['datenais'])/(1000*60*60*24*365)))+ list_text.yearold +'" rel="external" href="?idpro3='+ val['id2'] +'&idani='+ val['id'] +'" class='+((val['variable2']==1) ? "situation_normale" : "situation_pb")+' id="listemanuclient-' + (i) + '" data-number="'+ i +'" data-number2="'+ val['id'] +'">' + val['nom_a'] + " " + val['espece'] + " " + val['sexe'] + " " + (Math.floor((currentTime.getTime()-val['datenais'])/(1000*60*60*24*365)))+ list_text.yearold +'</a><a href="?idpro='+ val['id2'] +'&idani='+ val['id'] +'">'+list_text.changetheanimalfolder+'</a></li>';
                    }
                     });
                $ul.html( html );
                $ul.listview( "refresh" );
                $ul.trigger( "updatelayout");
                $('[id^=listemanuclient]').on("vmouseover", function(){
                $('#listeax').html( "" );
           		html = "";
           	 if($('#choix_manu').val()== "client.nom"){
           		 html += '<li><a title="'+list_text.saveanotheranimal+'" rel="external" href="?idpro='+ $(this).data('number2') +'&idani=0">'+list_text.saveanotheranimal+'</a></li>';
   	 
       	    	$.each( response[$(this).data('number')], function ( i, val2 ) {

                     html += '<li><a title="' + val2['nom_a'] + " " + val2['espece'] + " " + val2['sexe'] + " " + (Math.floor((currentTime.getTime()-val2['datenais'])/(1000*60*60*24*365)))+ list_text.yearold + '" rel="external" href="?idpro3='+ val2['id2'] +'&idani='+ val2['id'] +'" class='+((val2['variable2']==1) ? "situation_normale" : "situation_pb")+' id="listemanuax-' + (i) + '"  data-number3="'+ val2['id'] +'">' + val2['nom_a'] + " " + val2['espece'] + " " + val2['sexe'] + " " + (Math.floor((currentTime.getTime()-val2['datenais'])/(1000*60*60*24*365)))+ list_text.yearold + '</a><a href="?idpro='+ val2['id2'] +'&idani='+ val2['id'] +'">'+list_text.changetheanimalfolder+'</a></li>';
                
            	  });
           	 }else if($('#choix_manu').val()== "animal.nom_a"){
           		html += '<li><a title="' + response[$(this).data('number')]['nom'] + " " +  response[$(this).data('number')]['prenom'] + " " +  response[$(this).data('number')]['ville'] +  '" class='+((response[$(this).data('number')]['variable']==1) ? "situation_normale" : "situation_pb")+' id="listemanuax-' + $(this).data('number') + '"  data-number3="'+ response[$(this).data('number')]['id2'] +'">' + response[$(this).data('number')]['nom'] + " " +  response[$(this).data('number')]['prenom'] + " " +  response[$(this).data('number')]['ville'] +  '</a><a href="?idpro='+ response[$(this).data('number')]['id2'] +'">'+list_text.changethecustomerfolder+'</a></li>';
           	 }
           	$('#listeax').html( html );
           	$('#listeax').listview( "refresh" );
           	$('#listeax').offset({ top: $(this).offset().top });
           	});
            });
        }
    });
    
	$('#calendar2').monthCalendar2({
		shortMonths: list_text.shortMonths,
		shortDays:list_text.shortDays,
		firstDayOfWeek: -1,
		data:planning,
		ma_date:(new Date()).clearTime(),
		date_cre_deb:"",
		date_cre_fin:"",
		perso: perso,
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
					 		url: "php/accueil.php?action=recup_historique",
							dataType: "json",
					            cache: false,
					            data:  {
					            date_debut : (ma_date2.clone().set({ day: 1 }).clearTime().getTime()/1000) , date_fin : (ma_date2.clone().set({ day: 1 }).add({ months: 1 }).clearTime().getTime()/1000)   
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


	//agenda

	var animal = 0;
	var ma_date = Date.today();
	recherche_donne_agenda(Date.today().clone().last().monday(),Date.today().clone().next().monday(),'<?php echo $_SESSION['login'];?>');
	
	function recherche_donne_agenda(debut ,fin, choix){
		
			 $.mobile.loading( 'show', {
			 textonly : "true",
			 textVisible : "true",
		     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.uploadorganizerinprogress+"</h2></span>",
			 iconpos : "right",
			 theme: "a"
		   	     });
	
        		$.ajax({	
			 		type: "POST",
					url: "php/agenda.php?action=recuprdv",
					dataType: "json",
					cache: false,
					data:  {
        				debut : debut.getTime(), fin : fin.getTime()
					       },	
					success: function(data){
					$.mobile.loading('hide');	
					var tempo_id = 0;
					$("#data_source").html('');		                       
					$.each(data, function(key, value) {
						if(choix==key){
						$('<option value="'+key+'" selected="selected">'+key+'</option>').appendTo($("#data_source"));
						}else{
						$('<option value="'+key+'">'+key+'</option>').appendTo($("#data_source"));
						}
						tempo_id++;
					});
					$("#data_source").selectmenu("refresh");

					
					   
                     affiche_agenda(data,choix);
				   
						            
					},
					 error: function(obj,text,error) {
	                       
	                    	$.mobile.loading('hide');	
	                    	           
	                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                        if(obj.status=="400"){
	                        document.location.href="index.php";
	                        }
	                    }  	                           
				});
			}// end recherche_donne_agenda
		$("#recherche_consult").on('click', function () {
	 		$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.consultationsearch+"</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
	 		$.ajax({
	        	type: "POST",
	            url: "php/accueil.php?action=recherche_consult",
	            dataType: "json",
	            cache: false,
	            data:  {
	                consult : $("#numero_consult").val()  
	            }		                           
	        })
	        .then( function ( response ) {
	        	$.mobile.loading('hide');
	        		
	        		if(response==''){
						alert("Cette consultation n'est pas accessible");
	        		}else{
	        			document.location.href="index.php?id_consultation="+$("#numero_consult").val();
	        		}	        	
	        	
	        });	
	 		
	 	}); 
		$('#date_select').change(function() {
			
			$("#calendar").weekCalendar("gotoWeek", $('#date_select').datebox('getTheDate')); 		       
	      });				
		
		function miseajour(calEvent, oldCalEvent, $event) {
        		$.mobile.loading( 'show', {
					 textonly : "true",
					 textVisible : "true",
				     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.update+"</h2></span>",
					 iconpos : "right",
					 theme: "a"
				   	     });
					
						        		$.ajax({	
									 		type: "POST",
											url: "php/agenda.php?action=miseajour_rdv",
											dataType: "json",
											cache: false,
											data:  {
						        			id_rdv : calEvent.id, debut: calEvent.start.getTime(), fin : calEvent.end.getTime()
											       },	
											success: function(data){
											$.mobile.loading('hide');
											oldCalEvent.start = calEvent.start;
											oldCalEvent.end = calEvent.end;    			   						                       
											$('#calendar').weekCalendar('updateEvent', calEvent);      													   
												            
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
		        		


		function affiche_agenda(rendezvous,choix) {

			$('#calendar').weekCalendar({
			      timeslotsPerHour: 4,
			      daysToShow: 1,
			      timeslotHeight: 15,
			      hourLine: true,
			      firstDayOfWeek: 1,
			      dateFormat: "d M Y",
			      allowCalEventOverlap:true,
			      showHeader:false,
			      buttonText:{today : list_text.today, lastWeek : "<", nextWeek : ">"},
			      businessHours: {start: 9, end: 19, limitDisplay: false},
			      use24Hour: true,
			      shortMonths:list_text.shortMonths,
			      longMonths:list_text.longMonths,
			      shortDays:list_text.shortDays,
			      longDays:list_text.longDays,
			      //data: eventData,
			      changedate:function($calendar, newDate){


			    	  $.mobile.loading( 'show', {
							 textonly : "true",
							 textVisible : "true",
						     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.uploadinprogress+"</h2></span>",
							 iconpos : "right",
							 theme: "a"
						   	     });
					
				        		$.ajax({	
							 		type: "POST",
									url: "php/agenda.php?action=recuprdv",
									dataType: "json",
									cache: false,
									data:  {
				        				debut : newDate.clone().last().monday().getTime(), fin : newDate.clone().next().monday().getTime()
									       },	
									success: function(data){
									$.mobile.loading('hide');	        									
									   
									rendezvous = data;
									$("#calendar").weekCalendar("refresh"); 
										            
									},
									 error: function(obj,text,error) {
					                       
					                    	$.mobile.loading('hide');	
					                    	           
					                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
					                        if(obj.status=="400"){
					                        document.location.href="index.php";
					                        }
					                    }  	                           
								});
					//	recherche_donne_agenda(newDate.last().monday(),newDate.next().monday(),choix);
    			     
				},
			      data: function(start, end, callback) {
				          var dataSource = choix;
						  var mon_array = $.parseJSON(rendezvous[dataSource]);
						  
				          for(var i = 0; i < mon_array.length; ++ i){
				        	   mon_array[i].start = Date.parse(mon_array[i].start);
				        	  mon_array[i].end = Date.parse(mon_array[i].end);
				        				rendezvous2 = JSON.stringify(mon_array[i]);					        			
					        			}				        				        			
				        		
				        		var eventData = {
				        		  events : 
				        			  mon_array
				        		};							          
				          callback(eventData);
			      },
			      height: function($calendar) {
			        return $("#liste_choix").height();
			      },
			      timeSeparator:"-",
			      newEventText:"Nouvelle consultation",
			      eventRender : function(calEvent, $event) {
			        if (calEvent.end.getTime() < new Date().getTime()) {
			          $event.css('backgroundColor', '#000000');
			          $event.find('.time').css({'backgroundColor': '#525266', 'border':'1px solid #3D3D4C'});
			        }
			      },
			      eventNew: function(calEvent, $event) {
			        displayMessage('<strong>Added event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
			        
			        		if(choix=='<?php echo $_SESSION['login'];?>'){ 

			        		}else{
			        			$('#calendar').weekCalendar('removeEvent', calEvent.id);
			        			alert(list_text.organizerchange);
			        		} 
				     },
				  eventDrag: function(calEvent, $event) {
    				     
				    		if(choix=='<?php echo $_SESSION['login'];?>'){ 
        			        	
			        		}else{	    			        			  
			        			$("#calendar").weekCalendar("removeEvent", calEvent.id);
    			                $("#calendar").weekCalendar("refresh");
			        			alert(list_text.organizerchange);
			        		}
				    	

				     },
			      eventDrop: function(calEvent, oldCalEvent, $event) {
				   	
			        displayMessage('<strong>Moved Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);

    			        if(choix=='<?php echo $_SESSION['login'];?>'){ 
    			        	miseajour(calEvent, oldCalEvent, $event);
		        		}else{	    			        			  
		        			
		        			alert(list_text.organizerchange);
		        		} 
    			     },
			      eventResize: function(calEvent, oldCalEvent, $event) {
    			    	 	if(choix=='<?php echo $_SESSION['login'];?>'){ 
        			        	miseajour(calEvent, oldCalEvent, $event);
			        		}else{
			        			  $("#calendar").weekCalendar("refresh");
			        			alert(list_text.organizerchange);
			        		} 
			        displayMessage('<strong>Resized Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
			      },
			      eventClick: function(calEvent, $event) {

    			    if(choix=='<?php echo $_SESSION['login'];?>'){  
			        displayMessage('<strong>Clicked Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);

			        $("#popup-2").html('');
					var $popUp2 = $("#popup-2").popup({
					        dismissible: false,
					        theme: "b",
					        overlyaTheme: "e",
					        transition: "pop"
					    }).on("popupafterclose", function () {
					        //remove the popup when closing
					        
					    }).css({
					    	'width': '400px',
				            'height': '300px',
				            'padding': '5px'
					        
					    });
					//create a title for the popup
					  $("<h4/>", {
					        text: list_text.saverendezvous
					    }).appendTo($popUp2);				
						

						 var mon_rdv = $('<fieldset data-role="fieldcontain"><label>'+list_text.sortofrendezvous+'</label><input type="text" name="mon_rdv" id="mon_rdv" value="'+calEvent.title+'"></fieldset>').appendTo( $popUp2 );

							// creat bouton envoyer
						
								$("<a>", {
								        text : list_text.saverendezvous2
										 }).buttonMarkup({
										 inline : true,
										 icon : "check"
										 }).bind("click", function() {
											 $popUp2.popup("close");
										     $.mobile.loading( 'show', {
											 textonly : "true",
											 textVisible : "true",
										     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.saveinprogress+"</h2></span>",
											 iconpos : "right",
											 theme: "a"
										   	     });
											if(calEvent.id){
												var var_tempo = calEvent.id;
											}else{
												var var_tempo = 0;
											}
										        		$.ajax({	
													 		type: "POST",
															url: "php/agenda.php?action=save_rdv",
															dataType: "json",
															cache: false,
															data:  {
										        			rdv : $("#mon_rdv").val(), debut: calEvent.start.getTime(), fin : calEvent.end.getTime(), id_rdv:var_tempo
															       },	
															success: function(data){
															$.mobile.loading('hide');			                       
															
			   						                        $( "#popup-2" ).popup( "close" );       			   						                       
			   						                        calEvent.title = $("#mon_rdv").val();
			   						                     	calEvent.id = data;
			   						                     	$('#calendar').weekCalendar('updateEvent', calEvent);    														   
																            
															},
															 error: function(obj,text,error) {
											                       
											                    	$.mobile.loading('hide');	
											                    	           
											                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
											                        if(obj.status=="400"){
											                        document.location.href="index.php";
											                        }
											                    }  	                           
															});

			   			    }).appendTo($popUp2);
				   			    			    
					    //create a back button
					    if(animal!=0){
					    	 $("<a>", {
							        text : list_text.pasteanimalnews
									 }).buttonMarkup({
									 inline : true,
									 icon : "forward"
									 }).bind("click", function() {
										$("#mon_rdv").val( animal[0]['nom_p']+' '+animal[0]['nom_a']+' '+animal[0]['tel1']+' '+animal[0]['tel2'] );

		 			   			    }).appendTo($popUp2);		     	 			   			    
					    }
					    $("<a>", {
					        text : list_text.deleterendezvous
							 }).buttonMarkup({
							 inline : true,
							 icon : "delete"
							 }).bind("click", function() {
								 $popUp2.popup("close");
							     $.mobile.loading( 'show', {
								 textonly : "true",
								 textVisible : "true",
							     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.deleteinprogress+"</h2></span>",
								 iconpos : "right",
								 theme: "a"
							   	     });

								if(calEvent.id){
								
						   	     													
							        		$.ajax({	
										 		type: "POST",
												url: "php/agenda.php?action=supr_rdv",
												dataType: "json",
												cache: false,
												data:  {
							        			mon_id : calEvent.id
												       },	
												success: function(data){
															
												$.mobile.loading('hide');														   
   						                       $( "#popup-2" ).popup( "close" );													   
													            
												},
												 error: function(obj,text,error) {
								                       
								                    	$.mobile.loading('hide');	
								                    	           
								                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
								                        if(obj.status=="400"){
								                        document.location.href="index.php";
								                        }
								                    }  	                           
												    });

								$('#calendar').weekCalendar('removeEvent', calEvent.id);
								}else{
									$.mobile.loading('hide');	
									$('#calendar').weekCalendar('removeEvent', calEvent.id);
								}

   			    }).appendTo($popUp2);
				//create a back button
				 $("<a>", {
				     text: "Back",
				     "data-rel": "back"
				  }).buttonMarkup({
				     inline: true,
				     theme: "a",
				     icon: "back"
				  }).appendTo($popUp2);

				  
				    $popUp2.popup('open').trigger("create");

    			    }else{
				alert(list_text.organizerchange);
    			    }       					    
			        
			      },
			      eventMouseover: function(calEvent, $event) {
			        displayMessage('<strong>Mouseover Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
			      },
			      eventMouseout: function(calEvent, $event) {
			        displayMessage('<strong>Mouseout Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
			      },
			      noEvents: function() {
			        displayMessage('There are no events for this week');
			      }
			    });

			    function displayMessage(message) {
			      $('#message').html(message).fadeIn();
			    }

			    $('<div id="message" class="ui-corner-all"></div>').prependTo($('body'));
			    
			    
		};
		// fin agenda
	
});

</script>
<!-- postit -->
    <div id="postit"></div>
<section class="accueil cf">
 			<div id="recherche_manuelle">
			<div id="recherche_manuelle_parametre" data-role="fieldcontain">
					<select name="choix_manu" id="choix_manu" data-theme="e" data-icon="gear" data-inline="true">
						<option data-number="essai1" value="client.nom"><?php echo TXT_ACCUEIL_OWNER; ?></option>
						<option data-number="essai2" value="animal.nom_a"><?php echo TXT_ACCUEIL_PET; ?></option>			
					</select>
			</div>
			<div id="recherche_manuelle_parametre2" data-role="fieldcontain">
					<select name="choix_manu2" id="choix_manu2" data-theme="d" data-icon="star" data-inline="true">
						<option value=""><?php echo TXT_ACCUEIL_BEGIN; ?></option>
						<option value="%"><?php echo TXT_ACCUEIL_CONTAIN; ?></option>			
					</select>
			</div>
			<div id="recherche_manuelle_client">
			<ul id="autocomplete" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo (string) $texte_recherche2;?>" data-filter-theme="d" data-split-icon="gear"></ul>
			</div>	
			<div id="recherche_manuelle_ax">
			<ul data-role="listview" name="listeax" id="listeax" data-split-icon="gear"></ul>
			</div>	
			</div>
			<div id="recherche_alpha" data-role="fieldcontain"><div id="recherche_alpha_parametre" data-role="fieldcontain" >
					<select name="choix_alpha" id="choix_alpha" data-theme="e" data-icon="gear" data-inline="true">
						<option value="proprio"><?php echo TXT_ACCUEIL_OWNER; ?></option>
						<option value="animaux"><?php echo TXT_ACCUEIL_PET; ?></option>			
					</select>
				</div>
				<div id="recherche_alpha_texte" data-role="fieldcontain">
				<input type="search" name="search" id="champrecherchealpha" value="" />
				</div>
				<div id="recherche_alpha_client">			
						<select name="listeclient" id="listeclient">
						 <option value="" data-placeholder="true" data-recherche=""><?php echo TXT_ACCUEIL_CHOOSEAOWNER; ?></option>
						<?php foreach(json_decode($client, true) as  $key => $mb) {?>
			<option id="listealphaclient-<?php echo $key; ?>" value="<?php echo $key; ?>"  data-number2="<?php echo $mb[0]['id2']; ?>" data-recherche="<?php echo $mb[0]['nom']; ?>">
			<?php echo $mb[0]['nom']." ".$mb[0]['prenom']." ".$mb[0]['ville'];?> 
			</option>
			<?php } ?>
				</select>
				</div>
				<div id="recherche_alpha_ax">
			<ul data-role="listview" name="listealphaax" id="listealphaax" data-split-icon="gear"></ul>
					</div>
				</div>		
				

						
						<div id="tags">
							<ul id="liste_mur" data-bind="foreach: liste_mur">
							<li data-bind="attr: {class: valeur}"><a href="#" data-bind="text: texte, click: $parent.supr_liste_mur, attr: {value: id}"></a></li>   							
							</ul>
						</div>	
						<br>
<fieldset class="ui-grid-b">
     <div id="liste_choix" class="ui-block-a" style="width:70%">			
	<ul data-role="listview" data-count-theme="c" data-inset="true">
  		<li>
 		 	<div data-role="collapsible" id="salleattente">
 		 		<h2><?php echo TXT_ACCUEIL_SEEWAITINGROOM; ?></h2>
 		 		<ul id="salle_attente" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo (string) $texte_recherche2;?>" data-filter-theme="d" data-split-icon="minus"></ul> 		 		
  			</div>
 		 </li>
 	 </ul>
 	 <?php if( $_SESSION['login']==$_SESSION['login2'] && $_SESSION['login']!=$_SESSION['tour']){ ?>
 	 <ul data-role="listview" data-count-theme="b" data-inset="true">
  		<li>
 		 	<div data-role="collapsible" id="rapportref">
 		 		<h2><?php echo TXT_ACCUEIL_REPORTEMERGENCY; ?></h2>
 		 		<ul id="rapport_ref" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Rechercher un rapport de garde reçu" data-filter-theme="c" data-split-icon="minus"></ul> 		 		
  			</div>
 		 </li>
 	 </ul>
 	  <ul data-role="listview" data-count-theme="a" data-inset="true">
  		<li>
 		 	<div data-role="collapsible" id="rapportredige">
 		 		<h2><?php echo TXT_ACCUEIL_YOURDRAFTREPORT; ?></h2>
 		 		<ul id="rapport_redige" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Rechercher un rapport que vous avez rédigé" data-filter-theme="b" data-split-icon="minus"></ul> 		 		
  			</div>
 		 </li>
 	 </ul>
 	  <ul data-role="listview" data-count-theme="a" data-inset="true">
  		<li>
 		 	<div data-role="collapsible" id="casrefere">
 		 		<h2><?php echo TXT_ACCUEIL_REFERREDCASE; ?></h2>
 		 		<ul id="cas_refere" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Rechercher un cas référé" data-filter-theme="b" data-split-icon="minus"></ul> 		 		
  			</div>
 		 </li>
 	 </ul>
 	 <?php } ?>
 	 <ul data-role="listview" data-count-theme="a" data-inset="true">
  		<li>
 		 	<div data-role="collapsible" id="_historique">
 		 		<h2><?php echo TXT_ACCUEIL_HISTORYCONSULTATION; ?></h2>
 		 		<ul id="historique" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Rechercher dans les 100 dernières consultations" data-filter-theme="b" data-split-icon="minus"></ul> 		 		
  			</div>
 		 </li>
 	 </ul>
 	  <div class="paragraphe">
 	 	<fieldset class="ui-grid-b">
 	 	 <div class="ui-block-a">
 			 <label for="numero_consult"><?php echo TXT_ACCUEIL_CONSULTATIONSEARCH; ?></label>
 			</div>
 		 <div class="ui-block-b">
			 <input type="number" name="numero_consult" id="numero_consult" ">
		 </div>
		 <div class="ui-block-c">
			 <a id="recherche_consult" name="recherche_consult" data-role="button" data-icon="search" data-theme="b" data-inline="true" data-mini="true" data-iconpos="left"><?php echo TXT_ACCUEIL_SEARCH; ?></a>
 	 	</div>
 	 </div>
 </div>
			<div class="ui-block-b" style="width: 29%">
				<div id='calendar'></div>
				<div id="popup-2" data-role="popup"></div>						
				</div>
</fieldset>
<div id="calendar2">	
</div>
</section>

<?php render('_footer2',array(
			'liste_vetos' => $liste_vetos,
			'liste_message_recu_perso' => $liste_message_recu_perso,
			'liste_message_recu_garde' => $liste_message_recu_garde,
			'liste_message_emis' => $liste_message_emis,			
			))?>


