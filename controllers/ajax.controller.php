<?php
 ?>

<script type="text/javascript">function submitForm()
    {
    
        $.ajax({type:'POST', url: 'php/identification.php', data:$('#login').serialize(), success: function(response)
        {
            if(html(response)=="ok"){
			<?php 
			require_once "php/config.php";
			require_once "php/connexionmysql.php";
			require_once "controllers/identification.controller.php";
			require_once "controllers/ajax.controller.php";
			require_once "php/helpers.php";
			require_once "controllers/rechercheclient.controller.php";
			try {
			//Rechercheclient::find();
			
			}
			catch(Exception $e) {
			render('error',array('message'=>$e->getMessage()));
			}
			?>
            }else{
            $('h2').html(response).css("background-color", "red");
            }
        }});
        return false;
    }
</script>  

<?php ?>