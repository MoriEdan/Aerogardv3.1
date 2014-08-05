<?php render('_header',array('title'=>$title))?>
<script>  

    $(  "#pageencours" ).on( "pageinit",  function() {

    	var list_text = <?php echo TXT_IDENTIFICATION_JSPARTS;?>;




    	$("#pageencours").keypress(function(event) {
    	    if (event.which == 13) {
    	        event.preventDefault();
		    	    var $this = $( this );
			           	 $.mobile.loading( 'show', {
			        			textonly : "true",
			        		    textVisible : "true",
			        		    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.check+"</h2></span>",
			        			iconpos : "right",
			        		    theme: "a"
			        		             	 
			        		});		                           
		             var formData = $("#ajaxForm").serialize();		
				           $.ajax({
				               type: "POST",
				               url: "php/identification.php",
				               cache: false,
				               data: formData,
				               success: onSuccess,
				               error: onError
				           });
		           $('#mes_formulaires').hide();
    	   		 }
    		});
		    	
    	function onSuccess(data, status)
        {
        	if(data=="ok"){
        		document.location.href="index.php";
    		}else if(data=="agenda"){
        		document.location.href="index.php?agenda2=0";
    		}else{
    			 $.mobile.loading( "hide" );
    			 $('#mes_formulaires').show();
    			 $('h2').html(data).css("background-color", "red");
    			}        
        }

        function onError(data, status)
        {
        	 $('#mes_formulaires').show();
        	 $.mobile.loading( "hide" );
             $('h2').html(list_text.erreur1).css("background-color", "red");
        } 
    	$('.QapTcha').QapTcha({
    	      autoSubmit : true,
    	      autoRevert : true,
    	      txtLock : list_text.QapTcha_txtLock,
  			  txtUnlock : list_text.QapTcha_txtUnlock,
    	      PHPfile : './php/motdepasseperdu.php'
    	    });
    	$("#pass_oubli").click(function(){
    		$("#mon_oubli").show();
    	});	
        $("#submit").click(function(){
        	
        	 var $this = $( this );
        	 $.mobile.loading( 'show', {
     			textonly : "true",
     		    textVisible : "true",
     		    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.check+"</h2></span>",
     			iconpos : "right",
     		    theme: "a"
     		             	 
     		});
                        
        var formData = $("#ajaxForm").serialize();

        $.ajax({
            type: "POST",
            url: "php/identification.php",
            cache: false,
            data: formData,
            success: onSuccess,
            error: onError
        });
        $('#mes_formulaires').hide();
        
        });
    });
</script>    


<section class="loginform cf">
		<div id="mes_formulaires">
			<h2><?php echo $action?></h2>
		
				<form id ="ajaxForm">
				
					<label for="usermail"><?php echo TXT_IDENTIFICATION_EMAIL; ?></label>
					<input type="email" id="usermail" name="usermail" placeholder="<?php echo TXT_IDENTIFICATION_EMAIL2; ?>" required size=34 >
				
					<label for="password"><?php echo TXT_IDENTIFICATION_PASS; ?></label>
					<input type="password" id="password" name="password" placeholder="<?php echo TXT_IDENTIFICATION_PASS; ?>" required></li>
				
					 <fieldset class="ui-grid-a">
	     			 <div class="ui-block-a" style="width:49%">
	       				 <select name="liste_choix" id="liste_choix">  	       				 	       				 
	       				 	<?php foreach($liste_choix as  $key => $mb) {?>
							<option id="choix-<?php echo $key; ?>" value="<?php echo $mb['cas']; ?>"  data-number2="<?php echo $mb['cas']; ?>" data-recherche="<?php echo $mb['nom']; ?>">
							<?php echo $mb['nom'];?> 
							</option>
							<?php } ?>								
						 </select>	       				 
	      			 </div>
	       			 <div class="ui-block-b" style="width:49%">
	       			 <a id="submit" name="submit" data-role="button" data-icon="plus" data-theme="b"><?php echo $textechargement?></a>
	       			 </div>
	       			 </fieldset>
	       			  <fieldset class="ui-grid-a">
	     			 <div class="ui-block-a" style="width:49%">
	     			 </div>
	       			 <div class="ui-block-b" style="width:49%; text-align: right;">
	       			 <a id="pass_oubli" name="pass_oubli"  style="font-size: 12px; color: black;"><?php echo TXT_IDENTIFICATION_PASS_FORGOTTEN; ?></a>
	       			 </div>
	       			 </fieldset>
	       			 </form>	
	       			 <div id="mon_oubli" class="paragraphe" style="display:none;">
	       			 <form method="post" data-ajax=false action="./php/motdepasseperdu2.php">
	       			 	<p><?php echo TXT_IDENTIFICATION_PASS_FORGOTTEN2; ?></p>
	       			 	 <fieldset class="ui-grid-a">
        					<div class="ui-block-a" style="width:30%;">
	        					 <label for="email2"><em>* </em><?php echo TXT_IDENTIFICATION_PASS_FORGOTTEN3; ?> </label>
        					</div>
        					<div class="ui-block-b" style="width:69%;">
        					<input type="email" id="email2" name="email2" placeholder="<?php echo TXT_IDENTIFICATION_EMAIL2; ?>" required size=34/>
        					</div>	
        				</fieldset>  
        				<div class="clr"></div> 
        				 <fieldset class="ui-grid-a">
        				 <div class="ui-block-a" style="width:70%;">
        					<div class="QapTcha"></div>  
        					</div>
        					<div class="ui-block-b" style="width:29%;">
        					 <input type="submit" data-role="none" name="envoy" value="<?php echo TXT_IDENTIFICATION_PASS_SUBMIT; ?>" />
        					 </div>	
        				</fieldset> 
        					</form>	 			 
	       			 </div>
	       			 
	       			 
        		
			</div>
	</section>
	
 
<?php render('_footer')?>