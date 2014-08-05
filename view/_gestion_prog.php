<?php render('_header_admin',array('title'=>$title))?>
<script type="text/javascript">
$( document ).on( "pageinit", function( event ) { 
	// sécurité pour les sessions
	var refreshTime = 1200000; // in milliseconds, so 20 minutes
    window.setInterval( function() {
    	document.location.href="index.php";
    }, refreshTime );
	var tour = <?php echo $tour; ?>;
	var membre = <?php echo $membre; ?>;
	var membre_sup = <?php echo $membre_sup; ?>;
	var list_text = <?php echo TXT_GESTION_PROG_JSPARTS;?>;
	function ViewModel() {
		   var self = this;
		   self.modif_tour_membre_liste_selectionne = ko.observable();
		   self.modif_tour_tour_liste_selectionne = ko.observable();
		   self.reactiv_membre_liste_selectionne = ko.observable();
		   self.sous_membre_liste_selectionne = ko.observable();
		   self.sous_tour_liste_selectionne = ko.observable();
		   self.modif_groupe_membre_liste_selectionne = ko.observable();
		   self.modif_groupe_groupe_liste_selectionne = ko.observable();
		   self.modif_mail_membre_liste_selectionne = ko.observable();
		   self.modif_pass_membre_liste_selectionne = ko.observable();

		   //ajout tour de garde
		   self.liste_tour = ko.observableArray(tour);
		   self.ajout_tour =  function(){
			   if($("#ajout_tour_pass2").val() != $("#ajout_tour_pass1").val() ){
				   affiche_popup1(list_text.error_writting_pass1, list_text.error_writting_pass2);   
	 				    
			   }	//fermeture verification egalité des mots de passe
			   else{
				   $.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.login_check+"</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/gestion_prog.php?action=verif_login",
			            dataType: "json",
			            cache: false,
			            data:  {
						login : $('#ajout_tour_login').val(), pass : $('#ajout_tour_pass1').val(), e_mail : $('#ajout_tour_mail').val() 
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	if(data!='true'){
			            		 affiche_popup1(list_text.login_already_use1, list_text.login_already_use2);
			            	}
			            	else{

			            		self.liste_tour.push({login2 : $('#ajout_tour_login').val(), tour : '', groupe : '', delete2 : ''});

			            	}
			            	
			            	 
			            },
			            error: function(obj,text,error) {
		                       
	                    	$.mobile.loading('hide');	
	                    	           
	                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                        if(obj.status=="400"){
	                        document.location.href="index.php";
	                        }
	                    } 	                           
			        });// fin ajax

			   } 	//fermeture else
			   
		   }
		   // dissolution d'un tour de garde
		   self.sous_tour =  function(){

				if(self.sous_tour_liste_selectionne()){
						   $("#mon_popup").html('');
						   var $popUp = $("#mon_popup").popup({
						        dismissible: false,
						        theme: "b",
						        overlyaTheme: "e",
						        transition: "pop"
						    }).on("popupafterclose", function () {
						       
						    }).css({
						        'width': '370px',
						            'height': '200px',
						            'padding': '5px'
						    });
						    //create a title for the popup
						    $("<h4/>", {
						        text: list_text.delete_care1
						    }).appendTo($popUp);
						  //Create a submit button(fake)
						    $("<a>", {
						        text : list_text.yes_
						    }).buttonMarkup({
						        inline : true,
						        icon : "check"
						    }).bind("click", function() {
						    	$popUp.popup("close");
								    	$.mobile.loading( 'show', {
											textonly : "true",
										    textVisible : "true",
										    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.delete_care2+"</h2></span>",
											iconpos : "right",
										    theme: "a"
										             	 
										});
								    	$.ajax({	
						 				    
								        	type: "POST",
								        	url: "php/gestion_prog.php?action=supr_tour",
								        	dataType: "json",
								            cache: false,
								            data:  {
								    		login : $('#sous_tour_liste').find(":selected").text()  
								            },	
								            success: function(data2){
								            	  $.mobile.loading('hide');			                       
							                      var match = ko.utils.arrayFirst(self.liste_tour(), function(item) {
							                    	  return item.login2 == $('#sous_tour_liste').find(":selected").text();
							                    	});
												  match.delete2 = $('#sous_tour_liste').find(":selected").text();
												  match.login2 = '';
							                      self.liste_supr.push(match);
							                      self.liste_tour.remove(function(someItem) { return someItem.delete2 == $('#sous_tour_liste').find(":selected").text(); });
							                      									            
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
						        text : list_text.no_
						       
						    }).buttonMarkup({
						        inline : true,
						        icon : "back"
						    }).bind("click", function() {
						        $popUp.popup("close");
						     
						    }).appendTo($popUp);
						    		        			   
						    $popUp.popup('open').trigger("create");

				}//fermeture du if

		   }// fermeture sous_tour
		   
		 //ajout membre
		   self.liste_membre = ko.observableArray(membre);
		   self.ajout_membre =  function(){
			   if($("#ajout_membre_pass1").val() != $("#ajout_membre_pass2").val() ){
				   affiche_popup1(list_text.error_writting_pass1, list_text.error_writting_pass2);   
	 				    
			   }	//fermeture verification egalité des mots de passe
			   else{
				   $.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.login_check+"</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/gestion_prog.php?action=verif_login2",
			            dataType: "json",
			            cache: false,
			            data:  {
						login : $('#ajout_membre_login').val(), pass : $('#ajout_membre_pass1').val(), e_mail : $('#ajout_membre_mail').val() 
			            },	 
			            success: function(data){
			            	$.mobile.loading('hide');
			            	if(data!='true'){
			            		 affiche_popup1(list_text.login_already_use1, list_text.login_already_use2);
			            	}
			            	else{

			            		self.liste_membre.push({login2 : $('#ajout_membre_login').val(), tour : '0', groupe : '', delete2 : ''});

			            	}
			            	
			            	 
			            },
			            error: function(obj,text,error) {
		                       
	                    	$.mobile.loading('hide');	
	                    	           
	                        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
	                        if(obj.status=="400"){
	                        document.location.href="index.php";
	                        }
	                    } 	                           
			        });// fin ajax

			   } 	//fermeture else
			   
		   }// fermeture add membre

		   // dissolution d'un tour de garde
		   self.sous_membre =  function(){

				if(self.sous_membre_liste_selectionne()){
					   $("#mon_popup").html('');
					   var $popUp = $("#mon_popup").popup({
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
					        text: list_text.delete_user
					    }).appendTo($popUp);
					  //Create a submit button(fake)
					    $("<a>", {
					        text : list_text.yes_
					    }).buttonMarkup({
					        inline : true,
					        icon : "check"
					    }).bind("click", function() {
					    	$popUp.popup("close");
							    	$.mobile.loading( 'show', {
										textonly : "true",
									    textVisible : "true",
									    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.delete_in_progress+"</h2></span>",
										iconpos : "right",
									    theme: "a"
									             	 
									});
							    	$.ajax({	
					 				    
							        	type: "POST",
							        	url: "php/gestion_prog.php?action=supr_membre",
							        	dataType: "json",
							            cache: false,
							            data:  {
							    		login : $('#sous_membre_liste').find(":selected").text()  
							            },	
							            success: function(data2){
							            	  $.mobile.loading('hide');			                       
						                      var match = ko.utils.arrayFirst(self.liste_membre(), function(item) {
						                    	  return item.login2 ==  $('#sous_membre_liste').find(":selected").text();
						                    	});
											  
											  match.delete2 = $('#sous_membre_liste').find(":selected").text();
											  match.login2 = '';

											  self.liste_supr.push(match);
						                      self.liste_membre.remove(function(someItem) { return someItem.delete2 == $('#sous_membre_liste').find(":selected").text() });
						                      								            
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
					        text : list_text.no_
					       
					    }).buttonMarkup({
					        inline : true,
					        icon : "back"
					    }).bind("click", function() {
					        $popUp.popup("close");
					     
					    }).appendTo($popUp);
					    		        			   
					    $popUp.popup('open').trigger("create");

				}

		   }// fermeture sous_tour


		// réactivation d'un tour de garde ou d'un membre
		   self.liste_supr = ko.observableArray(membre_sup);
		   self.reactiv_membre =  function(){

				if(self.reactiv_membre_liste_selectionne()){
						   $("#mon_popup").html('');
						   var $popUp = $("#mon_popup").popup({
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
						        text: list_text.reborn1
						    }).appendTo($popUp);
						  //Create a submit button(fake)
						    $("<a>", {
						        text : list_text.yes_
						    }).buttonMarkup({
						        inline : true,
						        icon : "check"
						    }).bind("click", function() {
						    	$popUp.popup("close");
								    	$.mobile.loading( 'show', {
											textonly : "true",
										    textVisible : "true",
										    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.reborn2+"</h2></span>",
											iconpos : "right",
										    theme: "a"
										             	 
										});
								    	$.ajax({	
						 				    
								        	type: "POST",
								        	url: "php/gestion_prog.php?action=activ_membre",
								        	dataType: "json",
								            cache: false,
								            data:  {
								    		login : $('#reactiv_membre_liste').find(":selected").text()  
								            },	
								            success: function(data2){
								            	  $.mobile.loading('hide');			                       
							                      var match = ko.utils.arrayFirst(self.liste_supr(), function(item) {
							                    	  return item.delete2 == $('#reactiv_membre_liste').find(":selected").text();
							                    	});
												    
												  if(match.tour == ''){
													  match.login2 = $('#reactiv_membre_liste').find(":selected").text();
													  match.delete2 = '';
													  self.liste_tour.push(match);
													  self.liste_supr.remove(function(someItem) { return someItem.login2 == $('#reactiv_membre_liste').find(":selected").text() });			                     
															
												  }else{
													  match.login2 = $('#reactiv_membre_liste').find(":selected").text();
													  match.delete2 = '';
													  self.liste_membre.push(match);
													  self.liste_supr.remove(function(someItem) { return someItem.login2 == $('#reactiv_membre_liste').find(":selected").text() });			                     
														 
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
			
			
							            
						    }).appendTo($popUp);
						    //create a back button
						    $("<a>", {
						        text : list_text.no_
						       
						    }).buttonMarkup({
						        inline : true,
						        icon : "back"
						    }).bind("click", function() {
						        $popUp.popup("close");
						     
						    }).appendTo($popUp);
						    		        			   
						    $popUp.popup('open').trigger("create");

				}//fermeture if

		   }// fermeture reactivation
		// modif tour membre
		   self.modif_tour_membre_liste_selectionne_donnee = ko.observableArray([]);
		   self.modif_tour_membre_liste_selectionne_donnee2 = function() {
				if(self.modif_tour_membre_liste_selectionne()){
					   var match = ko.utils.arrayFirst(self.liste_membre(), function(item) {
		             	  return item.login2 == $('#modif_tour_membre_liste').find(":selected").text();
		             	});   	
		            	
		            	self.modif_tour_membre_liste_selectionne_donnee(match);
		          //  	self.modif_groupe_membre_liste_selectionne_donnee(match);
				}
            	
			};
		   
		   self.modif_tour =  function(){
			  if( $('#modif_tour_tour_liste').find(":selected").text() =='' ) {
					ma_var_tempo = 0;
			  }else{
				  ma_var_tempo = $('#modif_tour_tour_liste').find(":selected").text();
			  }
				if(self.modif_tour_membre_liste_selectionne()){
						   $("#mon_popup").html('');
						   var $popUp = $("#mon_popup").popup({
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
						        text: list_text.change_user_care
						    }).appendTo($popUp);
						  //Create a submit button(fake)
						    $("<a>", {
						        text : list_text.yes_
						    }).buttonMarkup({
						        inline : true,
						        icon : "check"
						    }).bind("click", function() {
						    	$popUp.popup("close");
								    	$.mobile.loading( 'show', {
											textonly : "true",
										    textVisible : "true",
										    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.change_user_care2+"</h2></span>",
											iconpos : "right",
										    theme: "a"
										             	 
										});
								    	$.ajax({	
						 				    
								        	type: "POST",
								        	url: "php/gestion_prog.php?action=modif_tour",
								        	dataType: "json",
								            cache: false,
								            data:  {
								    		login : $('#modif_tour_membre_liste').find(":selected").text(), tour : ma_var_tempo
								            },	
								            success: function(data2){
								            	  $.mobile.loading('hide');			                       
							                      ko.utils.arrayForEach(self.liste_membre(), function(item) {
							                    	 if( item.login2 == $('#modif_tour_membre_liste').find(":selected").text() ){

							                    		 item.tour = $('#modif_tour_tour_liste').find(":selected").text();
							                    		
							                    	 }
							                      });
							                      self.modif_tour_membre_liste_selectionne_donnee2();
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
						        text : list_text.no_
						       
						    }).buttonMarkup({
						        inline : true,
						        icon : "back"
						    }).bind("click", function() {
						        $popUp.popup("close");
						     
						    }).appendTo($popUp);
						    		        			   
						    $popUp.popup('open').trigger("create");

				}//fermeture if

		   }// fermeture modif_tour

		   //modification d un groupe dans le quel est un membre
		   self.modif_groupe_membre_liste_selectionne_donnee = ko.observableArray([]);
		   self.modif_groupe_membre_liste_selectionne_donnee2 = function() {
			   
				  var match = ko.utils.arrayFirst(self.liste_membre(), function(item) {
					  if(item.login2 == $('#modif_groupe_membre_liste').find(":selected").text()){
						  return item;
					  }             	 
             	});
            	
			   self.modif_groupe_membre_liste_selectionne_donnee(match);
			 //  self.modif_tour_membre_liste_selectionne_donnee(match);
			};
		   
		   self.modif_groupe =  function(){

				if(self.modif_groupe_membre_liste_selectionne()){
						   $("#mon_popup").html('');
						   var $popUp = $("#mon_popup").popup({
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
						        text: list_text.group_to_membre
						    }).appendTo($popUp);
						  //Create a submit button(fake)
						    $("<a>", {
						        text : list_text.yes_
						    }).buttonMarkup({
						        inline : true,
						        icon : "check"
						    }).bind("click", function() {
						    	$popUp.popup("close");
								    	$.mobile.loading( 'show', {
											textonly : "true",
										    textVisible : "true",
										    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.changing_group+"</h2></span>",
											iconpos : "right",
										    theme: "a"
										             	 
										});
								    	$.ajax({	
						 				    
								        	type: "POST",
								        	url: "php/gestion_prog.php?action=modif_groupe",
								        	dataType: "json",
								            cache: false,
								            data:  {
								    		login : $('#modif_groupe_membre_liste').find(":selected").text(), groupe : $('#modif_groupe_groupe_liste').find(":selected").text()
								            },	
								            success: function(data2){
								            	  $.mobile.loading('hide');			                       
							                      ko.utils.arrayForEach(self.liste_membre(), function(item) { 

							                    		if( item.login2 == $('#modif_groupe_membre_liste').find(":selected").text() ){

							                    			 item.groupe = $('#modif_groupe_groupe_liste').find(":selected").text();
							                    			 
							                    		}						                    	 
							                    	 
							                      });
							                      self.modif_groupe_membre_liste_selectionne_donnee2();
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
						        text : list_text.no_
						       
						    }).buttonMarkup({
						        inline : true,
						        icon : "back"
						    }).bind("click", function() {
						        $popUp.popup("close");
						     
						    }).appendTo($popUp);
						    		        			   
						    $popUp.popup('open').trigger("create");

				}//fermeture if

		   }// fermeture modif_groupe

		   //modification du mail d'un membre
		   self.modif_mail_membre_liste_selectionne_donnee = ko.observableArray([]);
		   self.modif_mail_membre_liste_selectionne_donnee2 = function() {

				  var match = ko.utils.arrayFirst(self.liste_membre(), function(item) {
					  if(item.login2 == $('#modif_mail_membre_liste').find(":selected").text()){
             	 		 return item;
					  }
             	});
            	
			   self.modif_mail_membre_liste_selectionne_donnee(match);
			  // self.modif_tour_membre_liste_selectionne_donnee(match);
			};
		   
		   self.modif_mail =  function(){

				if(self.modif_mail_membre_liste_selectionne()){
						   $("#mon_popup").html('');
						   var $popUp = $("#mon_popup").popup({
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
						        text: list_text.mail_to_membre
						    }).appendTo($popUp);
						  //Create a submit button(fake)
						    $("<a>", {
						        text : list_text.yes_
						    }).buttonMarkup({
						        inline : true,
						        icon : "check"
						    }).bind("click", function() {
						    	$popUp.popup("close");
								    	$.mobile.loading( 'show', {
											textonly : "true",
										    textVisible : "true",
										    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.changing_mail+"</h2></span>",
											iconpos : "right",
										    theme: "a"
										             	 
										});
								    	$.ajax({	
						 				    
								        	type: "POST",
								        	url: "php/gestion_prog.php?action=modif_mail",
								        	dataType: "json",
								            cache: false,
								            data:  {
								    		login : $('#modif_mail_membre_liste').find(":selected").text(), mail : $('#modif_mail_mail_liste').val()
								            },	
								            success: function(data2){
								            	  $.mobile.loading('hide');			                       
							                      ko.utils.arrayForEach(self.liste_membre(), function(item) { 

							                    		if( item.login2 == $('#modif_mail_membre_liste').find(":selected").text() ){

							                    			 item.mail = $('#modif_mail_mail_liste').val();
							                    			 
							                    		}						                    	 
							                    	 
							                      });
							                      self.modif_mail_membre_liste_selectionne_donnee2();
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
						        text : list_text.no_
						       
						    }).buttonMarkup({
						        inline : true,
						        icon : "back"
						    }).bind("click", function() {
						        $popUp.popup("close");
						     
						    }).appendTo($popUp);
						    		        			   
						    $popUp.popup('open').trigger("create");

				}//fermeture if

		   }// fermeture modif_mail







		 //modif pass
		   self.liste_membre = ko.observableArray(membre);
		   self.modif_pass =  function(){
			   if($("#modif_pass_mail_liste").val() != $("#modif_pass_mail_liste2").val() ){
				   affiche_popup1(list_text.error_writting_pass1, list_text.error_writting_pass2);   
	 				    
			   }	//fermeture verification egalité des mots de passe
			   else{
				   $.mobile.loading( 'show', {
						textonly : "true",
					    textVisible : "true",
					    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.changing_pass+"</h2></span>",
						iconpos : "right",
					    theme: "a"
					             	 
					});
					$.ajax({		    
			        	type: "POST",
			            url: "php/gestion_prog.php?action=modif_pass",
			            dataType: "json",
			            cache: false,
			            data:  {
						login : $('#modif_pass_membre_liste').find(":selected").text(), pass : $('#modif_pass_mail_liste').val() 
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
			        });// fin ajax

			   } 	//fermeture else
			   
		   }// fermeture modif


		   
		   
		   
	}
	ko.applyBindings(new ViewModel());
	// affiche un popup avec un bouton retour
	function affiche_popup1(titre, texte){
				$("#mon_popup").html('');
		    	 var $popUp = $("#mon_popup").popup({
				        dismissible: false,
				        theme: "b",
				        overlyaTheme: "e",
				        transition: "pop"
				    }).on("popupafterclose", function () {
				       
				    }).css({
				        'width': '300px',
				            'height': '300px',
				            'padding': '5px'
				    });
				    //create a title for the popup
				    $("<h3/>", {
				        text: titre
				    }).appendTo($popUp);
				    $("<div/>", {
				        html: texte
				    }).appendTo($popUp);	
				   
				    //create a back button
				    $("<a>", {
				        text: "Back",
				            "data-rel": "back"
				    }).buttonMarkup({
				        inline: false,
				        mini: true,
				        theme: "e",
				        icon: "back"
				    }).appendTo($popUp);	 				   
				    $popUp.popup('open').trigger("create");

				}
	
});

</script>
<section class="nouveauclient cf">
<legend><?php echo TXT_GESTION_PROG_SOFTWAREMANAGEMENT; ?></legend>
<div id="mon_popup" data-role="popup"></div>
<ul data-role="listview" data-count-theme="c" data-inset="true">
  <li>
  <div data-role="collapsible" id="ajout_tour">
            <h2><?php echo TXT_GESTION_PROG_ADDCARE; ?></h2>
 			<fieldset class="ui-grid-d">
                	 <div class="ui-block-a">
       				 	 	<label for="ajout_tour_login"><?php echo TXT_GESTION_PROG_LOGINNEWCARE; ?></label>
       				    	<input type="text" name="ajout_tour_login" id="ajout_tour_login" placeholder="login...">							
					 </div>
					 <div class="ui-block-b">
       				 	 	<label for="ajout_tour_mail"><?php echo TXT_GESTION_PROG_EMAILNEWCARE; ?></label>
       				    	<input type="email" name="ajout_tour_mail" id="ajout_tour_mail" placeholder="mail...">							
					 </div>  
					 <div class="ui-block-c">
							<label for="ajout_tour_pass1"><?php echo TXT_GESTION_PROG_PASSNEWCARE; ?></label>
       				    	<input type="text" name="ajout_tour_pass1" id="ajout_tour_pass1" placeholder="pass...">							
						</div>   
					 <div class="ui-block-d">
							<label for="ajout_tour_pass2"><?php echo TXT_GESTION_PROG_REWRITTEPASSNEWCARE; ?></label>
       				    	<input type="text" name="ajout_tour_pass2" id="ajout_tour_pass2" placeholder="pass...">			
       				 </div> 
       				  <div class="ui-block-e">
							<a href="index.html" data-role="button" data-bind="click: ajout_tour" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_ADDTHISCARE; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>       				
       	   </fieldset>
       	    <select id="ajout_tour_liste" data-bind="options: liste_tour, optionsText: 'login2'">
     					
    		</select>
    		</div>
    		</li>
   <li>
   <div data-role="collapsible" id="sous_tour">
            <h2><?php echo TXT_GESTION_PROG_DELETEACARE; ?></h2>
 			<fieldset class="ui-grid-a">
                	 <div class="ui-block-a">
       				 	 	<label for="sous_tour_liste"><?php echo TXT_GESTION_PROG_CHOOSEACARETODELETE; ?></label>
       				    	<select id="sous_tour_liste" name="sous_tour_liste" data-bind="options: liste_tour, optionsText: 'login2', optionsCaption: 'Select...', value: sous_tour_liste_selectionne">     					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<a href="index.html" data-role="button" data-bind="click: sous_tour" data-icon="minus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_DELETEACARE; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_DELETEACARE; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>       				
       	   </fieldset>
       	    
    		</div>
    		</li>    		
    		<li>   		
    		
   <div data-role="collapsible" id="add_membre">
            <h2><?php echo TXT_GESTION_PROG_ADDUSER; ?></h2>
 			<fieldset class="ui-grid-d">
                	 <div class="ui-block-a">
       				 	 	<label for="ajout_membre_login"><?php echo TXT_GESTION_PROG_USERLOGIN; ?></label>
       				    	<input type="text" name="ajout_membre_login" id="ajout_membre_login" placeholder="login...">							
					 </div> 
					  <div class="ui-block-b">
       				 	 	<label for="ajout_membre_mail"><?php echo TXT_GESTION_PROG_USEREMAIL; ?></label>
       				    	<input type="email" name="ajout_membre_mail" id="ajout_membre_mail" placeholder="mail...">							
					 </div>  
					 <div class="ui-block-c">
							<label for="ajout_membre_pass1"><?php echo TXT_GESTION_PROG_USERPASS; ?></label>
       				    	<input type="text" name="ajout_membre_pass1" id="ajout_membre_pass1" placeholder="pass...">							
						</div>   
					 <div class="ui-block-d">
							<label for="ajout_membre_pass2"><?php echo TXT_GESTION_PROG_USERREPASS; ?></label>
       				    	<input type="text" name="ajout_membre_pass2" id="ajout_membre_pass2" placeholder="pass...">			
       				 </div> 
       				  <div class="ui-block-e">
							<a href="index.html" data-role="button" data-bind="click: ajout_membre" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="Plus" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_ADDUSER; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>       				
       	    </fieldset>
       	    <select id="ajout_membre_liste" data-bind="options: liste_membre, optionsText: 'login2'">     					
    		</select>
    		</div>
    		</li>
    		<li>   		    		
   <div data-role="collapsible" id="remove_membre">
            <h2><?php echo TXT_GESTION_PROG_DELETEUSER; ?></h2>
 			<fieldset class="ui-grid-a">
                	 <div class="ui-block-a">
       				 	 	<label for="sous_membre_liste"><?php echo TXT_GESTION_PROG_CHOOSEUSERTODELETE; ?></label>
       				    	<select id="sous_membre_liste" data-bind="options: liste_membre, optionsText: 'login2', optionsCaption: 'Select...' , value: sous_membre_liste_selectionne">     					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<a href="index.html" data-role="button" data-bind="click: sous_membre" data-icon="minus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_DELETEUSER2; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_DELETEUSER2; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>       				
       	   </fieldset>
    		</div>
    		</li>
    			<li>   		    		
   			<div data-role="collapsible" id="activ_membre">
            <h2><?php echo TXT_GESTION_PROG_USERREBORN; ?></h2>
 			<fieldset class="ui-grid-a">
                	 <div class="ui-block-a">
       				 	 	<label for="reactiv_membre_liste"><?php echo TXT_GESTION_PROG_CHOOSEUSERORCARETOREBORN; ?></label>
       				    	<select id="reactiv_membre_liste" data-bind="options: liste_supr, optionsText: 'delete2', optionsCaption: 'Select...' , value: reactiv_membre_liste_selectionne">     					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<a href="index.html" data-role="button" data-bind="click: reactiv_membre" data-icon="plus" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_CHOOSEUSERORCARETOREBORN2; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_CHOOSEUSERORCARETOREBORN2; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>       				
       	   </fieldset>
    		</div>
    		</li>
    		<li>   		    		
   <div data-role="collapsible" id="modif_tour">
            <h2><?php echo TXT_GESTION_PROG_CHANGECAREOFUSER; ?></h2>
 			<fieldset class="ui-grid-b">
                	 <div class="ui-block-a">
       				 	 	<label for="modif_tour_membre_liste"><?php echo TXT_GESTION_PROG_CHOOSEUSER; ?></label>
       				    	<select id="modif_tour_membre_liste" data-bind="options: liste_membre, optionsText: 'login2', value: modif_tour_membre_liste_selectionne, event: { change: modif_tour_membre_liste_selectionne_donnee2 }">     					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<label for="modif_tour_tour_liste"><?php echo TXT_GESTION_PROG_SELECTCARE; ?></label>
       				    	<select id="modif_tour_tour_liste" data-bind="options: liste_tour, optionsText: 'login2', optionsCaption: 'Aucun', value: modif_tour_tour_liste_selectionne">     					
    						</select>       				
    				 </div> 
       				 <div class="ui-block-c">
							<a href="index.html" data-role="button" data-bind="click: modif_tour" data-icon="refresh" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_CHANGECAREUSER; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_CHANGECAREUSER; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>        				       				
       	   </fieldset>
       	 
       	 
       	   <table data-role="table" id="table_modif" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th><?php echo TXT_GESTION_PROG_LOGIN; ?></th><th><?php echo TXT_GESTION_PROG_CARE; ?></th>
      			  
      			  </tr>
    			</thead>
   				<tbody data-bind="with: modif_tour_membre_liste_selectionne_donnee">
        <tr>
            <td data-bind="text: login2"></td>
            <td data-bind="text: tour"></td>
            
        </tr>
    </tbody>
</table>
    		</div>
    		</li>
    			<li>   		    		
   			<div data-role="collapsible" id="modif_groupe">
            <h2><?php echo TXT_GESTION_PROG_CHANGEGROUPUSER; ?></h2>
 			<fieldset class="ui-grid-b">
                	 <div class="ui-block-a">
       				 	 	<label for="modif_groupe_membre_liste"><?php echo TXT_GESTION_PROG_CHOOSEUSER2; ?></label>
       				    	<select id="modif_groupe_membre_liste" data-bind="options: liste_membre, optionsText: 'login2', value: modif_groupe_membre_liste_selectionne, event: { change: modif_groupe_membre_liste_selectionne_donnee2 } ">      					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<label for="modif_groupe_groupe_liste"><?php echo TXT_GESTION_PROG_CHOOSEGROUPTOSELECT; ?></label>
       				    	<select id="modif_groupe_groupe_liste" data-bind="options: liste_membre, optionsText: 'login2', optionsCaption: 'Aucun', value: modif_groupe_groupe_liste_selectionne">     					
    						</select>      				
    				 </div> 
       				 <div class="ui-block-c">
							<a href="index.html" data-role="button" data-bind="click: modif_groupe" data-icon="refresh" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_CHANGEGROUPUSER2; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_CHANGEGROUPUSER2; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>        				       				
       	   </fieldset>
       	 
       	 
       	   <table data-role="table" id="table_modif2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th><?php echo TXT_GESTION_PROG_LOGIN; ?></th><th><?php echo TXT_GESTION_PROG_GROUP; ?></th>
      			  
      			  </tr>
    			</thead>
   				<tbody data-bind="with: modif_groupe_membre_liste_selectionne_donnee">
        <tr>
            <td data-bind="text: login2"></td>
            <td data-bind="text: groupe"></td>
        </tr>
    </tbody>
</table>
    		</div>
    		</li>
    		<li>   		    		
   			<div data-role="collapsible" id="modif_mail">
            <h2><?php echo TXT_GESTION_PROG_CHANGEEMAIL; ?></h2>
 			<fieldset class="ui-grid-b">
                	 <div class="ui-block-a">
       				 	 	<label for="modif_mail_membre_liste"><?php echo TXT_GESTION_PROG_CHOOSEUSER2; ?></label>
       				    	<select id="modif_mail_membre_liste" data-bind="options: liste_membre, optionsText: 'login2', value: modif_mail_membre_liste_selectionne, event: { change: modif_mail_membre_liste_selectionne_donnee2 } ">      					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<label for="modif_mail_mail_liste"><?php echo TXT_GESTION_PROG_NEWMAIL; ?></label>
       				    	<input type="email" name="modif_mail_mail_liste" id="modif_mail_mail_liste" placeholder="mail...">							
							      				
    				 </div> 
       				 <div class="ui-block-c">
							<a href="index.html" data-role="button" data-bind="click: modif_mail" data-icon="refresh" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_CHANGEEMAIL2; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_CHANGEEMAIL2; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>        				       				
       	   </fieldset>
       	 
       	 
       	   <table data-role="table" id="table_modif3" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive">
          <!--    data-column-btn-theme="b" data-column-btn-text="Colonne à afficher" data-column-popup-theme="a" -->
    			<thead>
      			  <tr class="ui-bar-e"><th><?php echo TXT_GESTION_PROG_LOGIN; ?></th><th><?php echo TXT_GESTION_PROG_EMAIL; ?></th>
      			  
      			  </tr>
    			</thead>
   				<tbody data-bind="with: modif_mail_membre_liste_selectionne_donnee">
        <tr>
            <td data-bind="text: login2"></td>
            <td data-bind="text: mail"></td>            
        </tr>
    </tbody>
</table>
    		</div>
    		</li>
    		<li>   		    		
   			<div data-role="collapsible" id="modif_mail">
            <h2><?php echo TXT_GESTION_PROG_CHANGEPASS; ?></h2>
 			<fieldset class="ui-grid-c">
                	 <div class="ui-block-a">
       				 	 	<label for="modif_pass_membre_liste"><?php echo TXT_GESTION_PROG_CHOOSEUSER2; ?></label>
       				    	<select id="modif_pass_membre_liste" data-bind="options: liste_membre, optionsText: 'login2', value: modif_pass_membre_liste_selectionne">      					
    						</select>							
					 </div>  
					 <div class="ui-block-b">
							<label for="modif_pass_mail_liste"><?php echo TXT_GESTION_PROG_NEWPASS; ?></label>
       				    	<input type="password" name="modif_pass_mail_liste" id="modif_pass_mail_liste" placeholder="pass...">							
							      				
    				 </div> 
    				 <div class="ui-block-c">
							<label for="modif_pass_mail_liste2"><?php echo TXT_GESTION_PROG_NEWPASS; ?></label>
       				    	<input type="password" name="modif_pass_mail_liste2" id="modif_pass_mail_liste2" placeholder="pass...">							
							      				
    				 </div> 
       				 <div class="ui-block-d">
							<a href="index.html" data-role="button" data-bind="click: modif_pass" data-icon="refresh" data-iconpos="notext" data-theme="c" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" title="<?php echo TXT_GESTION_PROG_CHANGEPASS2; ?>" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-icon-notext ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><?php echo TXT_GESTION_PROG_CHANGEPASS2; ?></span><span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span></span></a>		
       				 </div>        				       				
       	   </fieldset>      	 
       	 
    		</div>
    		</li>
</ul>
</section>

<?php render('_footer')?>

