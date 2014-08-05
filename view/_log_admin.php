<?php render('_header_agenda',array('title'=>$title))?>
<script type="text/javascript">
$( document ).on( "pageinit", function( event ) { 
	function onSuccess(data, status)
    {
    	if(data=='true'){
    		document.location.href="index.php";
		}else{
			$.mobile.loading('hide');
			 $('#frmLogin').show();
			 $('h2').html(data).css("background-color", "red");
			}        
    }

    function onError(obj,text,error)
    {
    	$('#frmLogin').show();
    	$.mobile.loading('hide');	
        
        alert("erreur "+obj.status+" "+error+" "+obj.responseText);
        if(obj.status=="400"){
        document.location.href="index.php";
        }
    }       


	
	
	$(".btnLogin").click(function(){
    	
    	 var $this = $( this );
    	 $.mobile.loading( 'show', {
 			textonly : "true",
 		    textVisible : "true",
 		    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Vérification en cours...</h2></span>",
 			iconpos : "right",
 		    theme: "a"
 		             	 
 		});
                    
    var formData = $("#frmLogin").serialize();

    $.ajax({
        type: "POST",
        url: "php/identification2.php",
        cache: false,
        data: formData,
        success: onSuccess,
        error: onError
    });
    $('#frmLogin').hide();
    
    });
});

</script>
<section class="nouveauclient cf">
<form id="frmLogin">
	<h2>Espace Administration :</h2>
      <div data-role="fieldcontain">
        <label for="email">
          <em>* </em> Email: </label>
          <input type="text" id="email" 
            name="email" class="required email" />
      </div>
            
      <div data-role="fieldcontain">
        <label for="password"> 
          <em>* </em>Password: </label>
          <input type="password" id="password" 
            name="password" class="required" />
      </div>
            
      <div class="ui-body ui-body-b">
        <button class="btnLogin" type="submit" 
          data-theme="a">Login</button>
      </div>
    </form>
</section>

<?php render('_footer')?>

