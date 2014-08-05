<?php render('_header_agenda',array('title'=>$title))?>
<script type="text/javascript">

$( document ).ready(  function() {
				var list_text = <?php echo TXT_AGENDA_JSPARTS;?>;
				var animal = 0;
				var ma_date = Date.today();
				recherche_donne_agenda(Date.today().clone().last().monday(),Date.today().clone().next().monday(),'<?php echo $_SESSION['login'];?>');
				
				function recherche_donne_agenda(debut ,fin, choix){
					console.log("debut2 "+debut+" fin2 "+fin);
						 $.mobile.loading( 'show', {
						 textonly : "true",
						 textVisible : "true",
					     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.download_organizer+"</h2></span>",
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
					
	        		$('#date_select').change(function() {
	        			$("#calendar").weekCalendar("gotoWeek", $('#date_select').datebox('getTheDate')); 		       
				      });				
	        		
	        		function miseajour(calEvent, oldCalEvent, $event) {
			        		$.mobile.loading( 'show', {
								 textonly : "true",
								 textVisible : "true",
							     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.up_date+"</h2></span>",
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
	        			      daysToShow: 3,
	        			      timeslotHeight: 15,
	        			      hourLine: true,
	        			      firstDayOfWeek: 1,
	        			      dateFormat: "d M Y",
	        			      allowCalEventOverlap:true,
	        			      buttonText:{today : list_text.today, lastWeek : "<", nextWeek : ">"},
	        			      businessHours: {start: 9, end: 19, limitDisplay: false},
	        			      use24Hour: true,
	        			      shortMonths: list_text.shortMonths,
	        			      longMonths: list_text.longMonths,
	        			      shortDays: list_text.shortDays,
	        			      longDays: list_text.longDays,
	        			      //data: eventData,
	        			      changedate:function($calendar, newDate){


	        			    	  $.mobile.loading( 'show', {
	        							 textonly : "true",
	        							 textVisible : "true",
	        						     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.upload_+"</h2></span>",
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
	        			        return $(window).height() - $('h1').outerHeight(true);
	        			      },
	        			      timeSeparator:"-",
	        			      newEventText: list_text.new_event_text,
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

	        			        $("#popup-1").html('');
	        					var $popUp2 = $("#popup-1").popup({
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
	        										     html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.send_message+"</h2></span>",
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
	        															
	        			   						                        $( "#popup-1" ).popup( "close" );       			   						                       
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
	 								        text : list_text.pasteanimaldata
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



					
	});

</script>


	        	    <label for="date_select"><?php echo TXT_AGENDA_SELECTDATE; ?></label>
				 	<input type="date" data-role="datebox" name="date_select" id="date_select" data-options='{"mode": "calbox", "showInitialValue": true}' />              
    
       
<div id='calendar'></div>
<div id="popup-1" data-role="popup"></div>

<?php render('_footer')?>