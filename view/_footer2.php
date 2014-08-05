<!-- Attention les variables récupérés par javascript et venant de php sont transmis non pas du controller comme les autres fichiers view mais de _accueil.php car c'est un footer -->
<script type="text/javascript">
$( document ).ready(  function() {	
	 var liste_vetos = <?php echo $liste_vetos;?>;
	 var liste_message_recu_garde = <?php echo $liste_message_recu_garde;?>;
	 var liste_message_recu_perso = <?php echo $liste_message_recu_perso;?>;
	 var liste_message_emis = <?php echo $liste_message_emis;?>;
	 var garde = <?php echo json_encode($_SESSION['login']);?>;
	 var list_text2 = <?php echo TXT_FOOTER2_JSPARTS;?>;
	 var nb_message_non_lus=0;
	 $.each(liste_message_recu_garde, function( key, value ) {
		 if(value['lu']==1){
		 nb_message_non_lus++;
		 }
	});
	 var liste_message_recu_perso = <?php echo $liste_message_recu_perso;?>;
	 var nb_message_non_lus2=0;
	 $.each(liste_message_recu_perso, function( key, value ) {
		 if(value['lu']==1){
		 nb_message_non_lus2++;
		 }
	});
		
	 $("#liste_bas").append("<ul id='abc'></ul>");
	 $("#abc").append('<li><a href="#m_garde" id="message_garde">'+list_text2.care_you_have+nb_message_non_lus+list_text2.new_message+'</a></li>');
	 $("#abc").append('<li><a href="#m_perso" id="message_perso">'+list_text2.personal_you_have+nb_message_non_lus2+list_text2.new_message+'</a></li>');
	 $("#abc").append('<li><a href="#m_emis" data-params="emis" id="send_message" >'+list_text2.message_list+'</a></li>');
	 $("#abc").append('<li><a href="#" id="write" >'+list_text2.write_message+'</a></li>');
	 $("#liste_bas").navbar();
	 $('#liste_bas').trigger('create');


	 $( "#m_perso" ).panel({
		  open: function( event, ui ) {
		  
		 recup_message("perso",liste_message_recu_perso);
		  }
		});
	 $( "#m_garde" ).panel({
		  open: function( event, ui ) {
		  
		 recup_message("garde",liste_message_recu_garde);
		  }
		});
	 $( "#m_emis" ).panel({
		  open: function( event, ui ) {
		  
		 recup_message("emis",liste_message_emis);
		  }
		});

	 $("#message_perso").on("vclick", function(){
		 recup_message("perso",liste_message_recu_perso);
	 });
	 $("#message_garde").on("vclick", function(){
		 recup_message("garde",liste_message_recu_garde);
	 });
	 $("#send_message").on("vclick", function(){
		 alert("cool");
		 recup_message("emis",liste_message_emis);
	 });

	function recup_message(choix, valeur){
			$.mobile.loading( 'show', {
				 textonly : "true",
				 textVisible : "true",
			     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text2.download_message+"</h2></span>",
				 iconpos : "right",
				 theme: "a"
			   	     });
			$.ajax({	
					 type: "POST",
					url: "php/footer2.php?action=recupmessage",
					dataType: "json",
					cache: false,
					data:  {
		        	choix : choix    
						    },	
					success: function(data){
					$.mobile.loading('hide');		


					if(choix=='perso'){
						 $("#choix_message_perso").text(list_text2.private_message);
						 }else if(choix=='garde'){
						 $("#choix_message_garde").text(list_text2.care_message);
						 }else if(choix=='emis'){
						 $("#choix_message_emis").text(list_text2.message_you_send);
						 }	 
					                       
					 html='';
					 $.each( valeur, function( key, value ) {		 
						 
						 
				         html += '<li ><a class='+((value['lu']==0) ? "situation_normale" : "situation_pb")+' id="listemessage-' + value['id_s'] + '"  data-number="'+ value['id_s'] +'" data-number2="'+ value['id_m'] +'" data-number3="'+ choix +'" data-number4="'+ value['lu'] +'">'
				         +'<p><strong>'+list_text2.send_by+ value['envoye_par'] + '</strong></p>'
				         +'<p><strong>'+list_text2.bound+ value['desti'] + '</strong></p>'
				         + '<p>'+ value['titre'] + '</p>'
				         + "<p class='ui-li-aside'>" + value['ma_date'] + '</p></a></li>';         
				          });

					 		if(choix=='perso'){
								 $("#liste_m_perso").html( html );
							     $("#liste_m_perso").listview( "refresh" );
							     $("#liste_m_perso").trigger( "updatelayout");
							 }else if(choix=='garde'){
								 $("#liste_m_garde").html( html );
							     $("#liste_m_garde").listview( "refresh" );
							     $("#liste_m_garde").trigger( "updatelayout");
							 }else if(choix=='emis'){
								 $("#liste_m_emis").html( html );
							     $("#liste_m_emis").listview( "refresh" );
							     $("#liste_m_emis").trigger( "updatelayout");
							 }		


					 		 $('[id^=listemessage]').on("vclick", function(){

						 		 if($(this).data('number4') == 1 && ($(this).data('number3') == 'perso' || $(this).data('number3') == 'garde')){

						 			$.mobile.loading( 'show', {
										 textonly : "true",
										 textVisible : "true",
									     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text2.download_mes+"</h2></span>",
										 iconpos : "right",
										 theme: "a"
									   	     });
									$.ajax({	
											 type: "POST",
											url: "php/footer2.php?action=marquelu",
											dataType: "json",
											cache: false,
											data:  {
								        	message : $(this).data('number2'), choix : choix   
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
												
						 		 }							 		 
					 			console.log("ecouteur activé "+JSON.stringify(data));
					 				$("#popup-1").html('');
					 		    	 var $popUp2 = $("#popup-1").popup({
					 				        dismissible: false,
					 				        theme: "b",
					 				        overlyaTheme: "e",
					 				        transition: "pop"
					 				    }).on("popupafterclose", function () {
					 				       
					 				    }).css({
					 				        
					 				    });
					 				    //create a title for the popup
					 				    $("<h3/>", {
					 				        text: list_text2.message_send_by+data[$(this).data('number')]['envoye_par']+list_text2.at+data[$(this).data('number')]['ma_date']
					 				    }).appendTo($popUp2);
					 				    $("<h4/>", {
					 				        text: data[$(this).data('number')]['titre']
					 				    }).appendTo($popUp2);
					 				    $("<div/>", {
					 				        html: data[$(this).data('number')]['message']					 				        
					 				    }).css({
					 				    	'background-color': 'white'
					 				    }).appendTo($popUp2);	
					 				   $("<br/>").appendTo($popUp2);
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

	 
	$("#write").on("vclick", function(){
		
		$("#popup-1").html('');
		 var $popUp2 = $("#popup-1").popup({
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
		        text: list_text2.send_message
		    }).appendTo($popUp2);				
			 mon_destinataire_liste='';
				$.each( liste_vetos, function( key, value ) {
					mon_destinataire_liste+='<option value="'+value['login']+'">'+value['login']+' '+value['commune']+' '+value['nom']+'</option>';
				}
				);
	
			 var mon_destinataire = $('<fieldset data-role="fieldcontain"><label for="mondestinataire">'+list_text2.recipient+'</label>'+
					'<select name="mondestinataire" id="mondestinataire">'+mon_destinataire_liste+
					'<option value="'+garde+'">'+garde+'</option>'+
					'<option value="0">Tous</option></select></fieldset>').appendTo( $popUp2 );
	
			
			 var mon_titre = $('<fieldset data-role="fieldcontain"><label for="montitre">'+list_text2.title_message+'</label><input type="text" name="montitre" id="montitre"></fieldset>').appendTo( $popUp2 );
	
			 var mon_message = $('<form method="post" action="somepage"><textarea name="content" style="width:100%"></textarea></form>').appendTo( $popUp2 );
			// creat bouton envoyer
			
					$("<a>", {
					        text : list_text2.send_message2
							 }).buttonMarkup({
							 inline : true,
							 icon : "check"
							 }).bind("click", function() {
								 $popUp2.popup("close");
							     $.mobile.loading( 'show', {
								 textonly : "true",
								 textVisible : "true",
							     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text2.send_message3+"</h2></span>",
								 iconpos : "right",
								 theme: "a"
							   	     });
								       		$.ajax({	
										 		type: "POST",
												url: "php/footer2.php?action=envoi_message",
												dataType: "json",
												cache: false,
												data:  {
							        			destinataire : $("#mondestinataire  option:selected").val(), titre : $("#montitre").val(), message : tinyMCE.get('content').getContent(), liste_vetos : liste_vetos    
												       },	
												success: function(data){
												$.mobile.loading('hide');			                       
											    
   						                       $( "#popup-1" ).popup( "close" );
											    
													            
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
		    $("<a>", {
		        text: "Back",
		            "data-rel": "back"
		    }).buttonMarkup({
		        inline: true,
		        mini: true,
		        theme: "e",
		        icon: "back"
		    }).appendTo($popUp2);
		   
		    $popUp2.popup('open').trigger("create");

		    tinyMCE.init({
		        // General options
		        mode : "textareas",
		        theme : "advanced",
		        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		        // Theme options
		        theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		        theme_advanced_toolbar_location : "top",
		        theme_advanced_toolbar_align : "left",
		        theme_advanced_statusbar_location : "bottom",
		        theme_advanced_resizing : true,

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

			

		    

	});
	 $('#admin').click(function() {
		 document.location.href="index.php?admin=1";
	 });
	 $('#block').click(function() {
	      $('#postit').postitall({
	        'newPostit'       : false,
	        'position'        : 'relative',
	        'posX'            : '5px',                    //top position
	    	'posY'            : '5px',
	    	'changeoptions'   : false,                 //left position
	    	
	      });
	  });
	if(localStorage.texte){
		$('#postit').postitall({
	        'newPostit'       : false,
	        'position'        : 'relative',
	        'posX'            : '5px',                    //top position
	    	'posY'            : '5px',
	    	'changeoptions'   : false,                 //left position
	    	
	      });
	}
     

});
</script>
	</div>

		<div data-role="footer" data-position="fixed" data-tap-toggle="false">
			
		    <h4 style="text-align:center;" data-inline="true"><a href="" id="block" data-role="button" data-icon="plus" data-inline="true"><?php echo TXT_FOOTER2_PAD; ?></a>    <?php echo $GLOBALS['defaultFooter']?><a href="" id="admin" data-role="button" data-icon="grid" data-inline="true"><?php echo TXT_FOOTER2_MANAGEMENT; ?></a></h4>
		    <div id="liste_bas" name="liste_bas">
		       
		    </div><!-- /navbar -->
		</div>
		
		<div data-role="panel" id="m_perso" data-display="overlay" data-position="right">
				<label id="choix_message_perso"></label>
       			<ul id="liste_m_perso" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo TXT_FOOTER2_SEARCHMESSAGE; ?>" data-filter-theme="d" data-split-icon="gear"></ul>

    	</div><!-- /panel droite-->
    	<div data-role="panel" id="m_garde" data-display="overlay" data-position="right">
				<label id="choix_message_garde"></label>
       			<ul id="liste_m_garde" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo TXT_FOOTER2_SEARCHMESSAGE; ?>" data-filter-theme="d" data-split-icon="gear"></ul>

    	</div>
    	<div data-role="panel" id="m_emis" data-display="overlay" data-position="right">
				<label id="choix_message_emis"></label>
       			<ul id="liste_m_emis" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo TXT_FOOTER2_SEARCHMESSAGE; ?>" data-filter-theme="d" data-split-icon="gear"></ul>

    	</div>
    	
	    <div data-role="panel" id="left-panel" data-display="overlay" data-position="left">
	    			<label for="liste_message_perso"><?php echo TXT_FOOTER2_PERSONALMESSAGE; ?></label>
	       			<ul id="liste_message_perso" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo TXT_FOOTER2_SEARCHMESSAGE; ?>" data-filter-theme="d" data-split-icon="gear"></ul>
	
	   	 </div><!-- /panel gauche-->
    
    <div id="popup-1" data-role="popup"></div><!--  -->
    		
    
</div>

</body>
</html>