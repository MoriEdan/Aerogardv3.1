<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">

$( document ).ready(  function() {

	
	var list_text = <?php echo TXT_NOUVEAUCLIENT_JSPARTS;?>;
	// sécurité pour les sessions
	var refreshTime = 1200000; // in milliseconds, so 20 minutes
    window.setInterval( function() {
    	document.location.href="index.php";
    }, refreshTime );
	$('#map_canvas').hide();
	$('#pano').hide();
	  var map;
      var panorama;
      if (window.google) {
      var sv = new google.maps.StreetViewService();
      }
	$( "#codepostalmasque" ).hide();
	 
if($( "#commune" ).val()==""){
	
	$( "#geoloc" ).hide();	
}
$("#commune").on("input", function(e) {
	$( "#geoloc" ).show();	
});
$( "#codepostal" ).on( "listviewbeforefilter", function ( e, data ) {
	console.log(( $(data.input)).val() );
	   var $ul = $( this ),
       $input = $( data.input ),
       value = $input.val(),
       html = "";
       $ul.html( "" );
	   $('#listeax').listview( "refresh" );
     if ( value && value.length == 5 ) {
     $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
     $ul.listview( "refresh" );
     $.ajax({

     	type: "GET",
         url: "php/nouveauclient.php",
         dataType: "json",
         cache: false,
         data:  {
             recherche: $input.val(), choix: "code" 
         }
                    
     })
     .then( function ( response ) {
    	 console.log(response);
         $.each( response, function ( i, val ) {
            
             html += '<li ><a class='+((val['CODEPAYS']=="FR") ? "situation_normale" : "situation_pb")+' id="listecodepostaux-' + (i) + '"  data-number="'+ i +'" data-number2="'+ val['CP'] +'">' + val['VILLE'] + '</a></li>';
             
              });
         $ul.html( html );
         $ul.listview( "refresh" );
         $ul.trigger( "updatelayout");
         $('[id^=listecodepostaux]').on("click", function(){
          	 
           
         	$('#commune').val( $(this).html() );
         	 $ul.html( "" );
         	$( "#codepostal2" ).val($(data.input).val());
         	$( "#geoloc" ).show();
         	  $ul.listview( "refresh" );
              $ul.trigger( "updatelayout");
        	
        	});
        
     });
 }
});



$("#valid").on("vclick", function(){
$.mobile.loading( 'show', {
	textonly : "true",
    textVisible : "true",
    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>"+list_text.changing_data+"</h2></span>",
	iconpos : "right",
    theme: "a"
             	 
});
var formData = $("#formnouveauclient").serialize();
var choix =  <?php echo $client ; ?>;
if( choix == 0){
$.ajax({
    type: "POST",    
    url: "php/nouveauclient.php?choix=nouveauclient",
    cache: false,
    data: formData,
    success: onSuccess,
    error: onError
});
}else{
	
	$.ajax({
	    type: "POST",
	    url: "php/nouveauclient.php?choix=modifclient&client="+choix[0]['id2'],
	    cache: false,
	    data: formData,
	    success: onSuccess,
	    error: onError
	});

}
return false;
});


function onSuccess(data, status)
{
	var choix2 = '<?php echo $origin ; ?>';
	$.mobile.loading( "hide" );
	data = jQuery.parseJSON(data);	
	if(data.statut=="modifclient"){
		
		if(choix2=="_accueil"){
			document.location.href="index.php";
		}else if(choix2=="consultation"){
			var id_salle_attente = <?php echo $idsalleattente; ?>;
			var valeur_attente = '<?php echo $valeur_attente; ?>';
			if(valeur_attente=='0'){
			document.location.href="index.php?id_salle_attente="+id_salle_attente;
			}else{
			document.location.href="index.php?id_salle_attente="+id_salle_attente+"&valeur_attente="+valeur_attente;
			}
		}
	}else if(data.statut=="nouveauclient"){
		document.location.href="index.php?idpro="+data.id_pro+"&idani=0";
	}	
}

function onError(obj,text,error)
{
	$.mobile.loading('hide');	
    
    alert("erreur "+obj.status+" "+error+" "+obj.responseText);
    if(obj.status=="400"){
    document.location.href="index.php";
    }
}


$("#geoloc").on("vclick", function(){	
	if (window.google) {
		
	if($('#map_canvas').is(':visible')){
		$('#map_canvas').hide();
		$('#pano').hide();
	}else if($('#map_canvas').is(':hidden')){
		$('#map_canvas').show();
		$('#pano').show();
	
	var geocoder;
	geocoder = new google.maps.Geocoder();
	
	var address = $("#adresse").val()+", "+$("#codepostal2").val()+", "+$("#commune").val();
	console.log("adresse a chercher "+address);
	geocoder.geocode( { 'address': address}, function(results, status) {
	    if (status == google.maps.GeocoderStatus.OK) {
	      console.log("localisation effectuée !! "+ results[0].geometry.location);
	     
	      var mapOptions = {
	        zoom: 17,
	        center: results[0].geometry.location,
	        mapTypeId: google.maps.MapTypeId.HYBRID, 
	        streetViewControl: false
	      }
	      map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	    
	      
	      panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'));
	      sv.getPanoramaByLocation(results[0].geometry.location, 50, processSVData);
	      $('#map_canvas').show();
	      
	    } else {
	    	 console.log("pas de localisation possible "+ status);
	     
	    }
	  });
	}
	}else{

alert("géolocalisation impossible car vous êtes déconnectés d'internet");
	}
});
function processSVData(data, status) {
	  if (status == google.maps.StreetViewStatus.OK) {
	    var marker = new google.maps.Marker({
	      position: data.location.latLng,
	      map: map,
	      title: data.location.description
	    });

	    panorama.setPano(data.location.pano);
	    panorama.setPov({
	      heading: 270,
	      pitch: 0
	    });
	    panorama.setVisible(true);

	    google.maps.event.addListener(marker, 'click', function() {
	    	
	      var markerPanoID = data.location.pano;
	      // Set the Pano to use the passed panoID
	      panorama.setPano(markerPanoID);
	      panorama.setPov({
	        heading: 270,
	        pitch: 0
	      });
	      panorama.setVisible(true);
	     
	    });
	  } else {
		  console.log("pas de Street view possible.");
	  }
}

});

</script>
<section class="nouveauclient cf">
<?php
 if(json_decode($client)==0){?>
<h2><?php echo TXT_NOUVEAUCLIENT_NEWCLIENT; ?></h2>
<?php }else{
$client2 = json_decode($client, true);
	?>
<h2><?php echo TXT_NOUVEAUCLIENT_UPDATECLIENTCARD; ?> <?php echo $client2[0]['id2']; ?></h2>
<?php }?>
		<form id="formnouveauclient">
		<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="veto_ref"><?php echo TXT_NOUVEAUCLIENT_VETREFERRING; ?></label>
				
				<select name="veto_ref" id="veto_ref">
			    <option value="0"><?php echo TXT_NOUVEAUCLIENT_NOTAPPICABLE; ?></option>
			<?php foreach(json_decode($vetos, true) as  $key => $mb) {?>
				<option value="<?php echo $mb['id']; ?>"<?php echo ((json_decode($client)!=0 && $client2[0]['ref']==$mb['id']) ? 'selected="selected"' : ""); ?>><?php echo $mb['commune'].' '.$mb['login'].' '.$mb['nom'];?></option>
			<?php } ?>
				</select>
			</fieldset>
		</div>
		<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="username"><?php echo TXT_NOUVEAUCLIENT_LASTNAME; ?></label>
				<input type="text" name="username" id="username" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['nom']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain" > 
				<label for="surname"><?php echo TXT_NOUVEAUCLIENT_FIRSTNAME; ?></label>
				<input type="text" name="surname" id="surname" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['prenom']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="adresse"><?php echo TXT_NOUVEAUCLIENT_ADDRESS; ?></label>
				<textarea cols="40" rows="8" name="adresse" id="adresse" ><?php echo ((json_decode($client)==0) ? "" : $client2[0]['adresse']); ?></textarea>
			</fieldset>
			<fieldset data-role="fieldcontain" class="ui-grid-a"> 
				 <div class="ui-block-a"><label><?php echo TXT_NOUVEAUCLIENT_ZIP; ?></label></div>
				 <div class="ui-block-b"><ul id="codepostal" name="codepostal" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?php echo ((json_decode($client)==0) ? "Code postal" : $client2[0]['code']); ?>"></ul></div>
			</fieldset>
			<fieldset data-role="fieldcontain" id="codepostalmasque"> 			
				<label for="codepostal2"><?php echo TXT_NOUVEAUCLIENT_ZIP; ?></label>
				<input type="text" name="codepostal2" id="codepostal2" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['code']); ?>">		
			</fieldset>
			<fieldset data-role="fieldcontain"> 			
				<label for="commune"><?php echo TXT_NOUVEAUCLIENT_TOWN; ?></label>
				<input type="text" name="commune" id="commune" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['ville']); ?>">		
			</fieldset>
			</div>
			<div id="geoloc">
				 <input id="geoloc" type="button" value="<?php echo TXT_NOUVEAUCLIENT_GEOLOCATION; ?>" data-icon="grid" data-theme="a">
			</div>	
			<div id="map_canvas" style="width:100%;height:300px;"></div>
			<div id="pano" style="width:100%;height:300px;"></div>
     		  <div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="telephone"><?php echo TXT_NOUVEAUCLIENT_PHONENUMBER; ?> 1 :</label>
				<input type="tel" name="telephone" id="telephone" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['tel1']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="telephone2"><?php echo TXT_NOUVEAUCLIENT_PHONENUMBER; ?> 2 :</label>
				<input type="tel" name="telephone2" id="telephone2" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['tel2']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="email"><?php echo TXT_NOUVEAUCLIENT_EMAIL; ?></label>
				<input type="email" name="email" id="email" value="<?php echo ((json_decode($client)==0) ? "" : $client2[0]['mail']); ?>">
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="relancemail"><?php echo TXT_NOUVEAUCLIENT_SENDREMINDERSBYMAIL; ?></label>
				<select name="relancemail" id="relancemail" data-role="slider">
   					 <option value="non" <?php echo ((json_decode($client)==0)? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUCLIENT_NO; ?></option>
   					 <option value="oui" <?php echo ((json_decode($client)!=0 && $client2[0]['envoimail']=="oui")? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUCLIENT_YES; ?></option>
				</select>
			</fieldset>
			</div>
			<div class="paragraphe">
			<div class="tableau">			
				 <label for="variable1" style="padding:0px"><?php echo TXT_NOUVEAUCLIENT_UNWANTED; ?></label>
					<select name="variable1" id="variable1" data-role="slider" style="padding:0px">
						<option value="0" <?php echo ((json_decode($client)!=0 && $client2[0]['variable']=="2")? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUCLIENT_YES; ?></option>
						<option value="1" <?php echo ((json_decode($client)==0 || $client2[0]['variable']=="1")? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUCLIENT_NO; ?></option>
					</select>
			 <label for="variable3"><?php echo TXT_NOUVEAUCLIENT_DATA; ?> 1 :</label>
					<select name="variable3" id="variable3" data-role="slider">
						<option value="1" <?php echo ((json_decode($client)==0)? "selected=selected" : ""); ?>>1</option>
						<option value="2" <?php echo ((json_decode($client)!=0 && $client2[0]['variable3']=="2")? "selected=selected" : ""); ?>>2</option>
					</select>				 
			</div>
			<div class="tableau">	
			<fieldset data-role="fieldcontain"> 
			<div style="padding:10px"><label for="variable4"><?php echo TXT_NOUVEAUCLIENT_DATA; ?> 2:</label>
				<input type="range" name="variable4" id="variable4"  value="<?php echo ((json_decode($client)==0) ? "50" : $client2[0]['variable4']); ?>" min="0" max="100" /></div>
			</fieldset>
			</div>
			</div>
			<fieldset data-role="fieldcontain" class="ui-grid-a"> 
				 <div class="ui-block-a"><a  data-rel="back" <?php echo( ($origin=='consultation' ) ? 'class="ui-disabled"' : '' ); ?>  id="retour" name="retour" data-role="button" data-icon="delete" data-theme="a" rel="external"><?php echo TXT_NOUVEAUCLIENT_BACK; ?></a></div>
				 <div class="ui-block-b"><a id="valid" name="valid" data-role="button" data-icon="plus" data-theme="a" data-chargementtheme="<?php echo $themechargement?>" data-chargementtexte="<?php echo $textechargement?>"><?php echo TXT_NOUVEAUCLIENT_OK; ?></a></div>
			</fieldset>
		<form>
</section>

<?php render('_footer')?>

