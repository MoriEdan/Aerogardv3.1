<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">

function calcul_age(maDate)
{
	var date_actu = new Date();
	var date_choisie= maDate;
	var enddate = new Date(date_choisie.getFullYear(), date_choisie.getMonth(), date_choisie.getDate(), date_choisie.getHours(), date_choisie.getMinutes(), date_choisie.getSeconds(), 0);
	var ageseconde = date_actu.getTime() - enddate.getTime();
	var years = Math.floor(ageseconde / ( 1000 * 60 * 60 * 24 * 365));
	ageseconde -= years * (1000 * 60 * 60 * 24 * 365);

	var month = Math.floor(ageseconde / (1000 * 60 * 60 * 24 * 30)); 
	ageseconde -= month * (1000 * 60 * 60 * 24 * 30);
	
	var days = Math.floor(ageseconde / (1000 * 60 * 60 * 24)); 
	ageseconde -= days * (1000 * 60 * 60 * 24);

	return years+" an(s) "+month+" mois "+days+" jour(s)";

}
function openFile(file) {
    // do something with file
	console.log("fichier découvert"+file);
	 window.location = file;
}
$( document ).on( "pageinit", "#pageencours", function( event ) {
	// sécurité pour les sessions
	var refreshTime = 1200000; // in milliseconds, so 20 minutes
    window.setInterval( function() {
    	document.location.href="index.php";
    }, refreshTime );
	 $("#race2").hide();
	var id_animal= <?php echo $id_ani;?>;
		 
	 var date_nais = <?php echo $datenaissance; ?>;
	 date_nais = new Date(date_nais);
	 $( "#datenais" ).val($('#datenais').datebox('callFormat', '%d/%m/%Y', date_nais));


	 $('#dossier').fileTree({
	 	 	root: '../../sauvegarde/animaux/'+id_animal+'/',
	 	 	script: './js/connectors/jqueryFileTree.php'
	 	 	
	        }, function(file) { 
	        	//window.location = '/aerogard3/WebContent'+file;
	        	window.location.href = file.substr(6);
		 });
		
	if($("input[name=species]").is(":checked")){
		 $("#race2").show();
		 $('#race2 :input[data-type="search"]').val('<?php $mavar = json_decode($animal, true); echo(($id_ani==0) ? "" : ( $mavar[0]['race']));?>');
		 $('#race2 :input[data-type="search"]').trigger("updatelayout");

		 $.mobile.loading( 'show', {
				text: 'chargement...',
				textVisible: true,
				theme: 'd',
				html: ""
			});
			html = "";
			console.log($("input[name=species]:checked").val());
			$.ajax({
	            url: "txt/"+$("input[name=species]:checked").val()+".txt",
	            async: false,
	            success: function (data){
				console.log(data);
				$.mobile.loading('hide');
				$( "#race" ).html(data);
				$( "#race" ).trigger( "updatelayout");
				$( "#race" ).listview( "refresh" );

				$('#race li').on('click', function () {
					 console.log("choix de recherche race: " + $('input[data-type="search"]').val());
					 console.log("resultat choisi: " +  $(this).find('a').attr('data-number'));
					 $('input[data-type="search"]').val( $(this).find('a').attr('data-number'));
					 $('input[data-type="search"]').trigger("change");
					 
				    });	

			    
				}
	        });
	        
	}
	
	if($( "#datenais" ).val()==""){
			console.log("passé par la");  
			$( "#age_div" ).hide();	
	}else{
		 $( "#age_div" ).show();
		 $('#age').val(calcul_age(date_nais));
	}
		$("#datenais").on('change', function(){				
				 $( "#age_div" ).show();	
				 $('#age').val(calcul_age($("#datenais").datebox('getTheDate')));

		});
		$( "input[name='species']" ).change(function(){
			 $("#race2").show();
			$.mobile.loading( 'show', {
				text: 'chargement...',
				textVisible: true,
				theme: 'd',
				html: ""
			});
			html = "";
			console.log($(this).val());
			$.ajax({
	            url: "txt/"+$(this).val()+".txt",
	            async: false,
	            success: function (data){
				console.log(data);
				$.mobile.loading('hide');
				$( "#race" ).html(data);
				$( "#race" ).trigger( "updatelayout");
				$( "#race" ).listview( "refresh" );

				$('#race li').on('click', function () {
					 console.log("choix de recherche race: " + $('input[data-type="search"]').val());
					 console.log("resultat choisi: " +  $(this).find('a').attr('data-number'));
					 $('input[data-type="search"]').val( $(this).find('a').attr('data-number'));
					 $('input[data-type="search"]').trigger("change");
					 
				    });	

			    
				}
	        });
			

		});
$("#valid").on("vclick", function(){
			console.log("valid cliqué");
			$.mobile.loading( 'show', {
				textonly : "true",
			    textVisible : "true",
			    html : "<span class='ui-bar ui-shadow ui-overlay-d ui-corner-all'><img src='./image/logo/essai1.gif'><h2>Modification des données...</h2></span>",
				iconpos : "right",
			    theme: "a"
			             	 
			});
			var formData = $("#formnouveauanimal").serialize();
			console.log("resultat form "+formData);
			var choix =  <?php echo $id_pro;?>;
			if( id_animal == 0){
			$.ajax({
			    type: "POST",    
			    url: "php/nouveauanimal.php?choix=nouveauanimal&client="+choix,
			    cache: false,
			    data: formData+"&race="+$('#race2 :input[data-type="search"]').val(),
			    success: onSuccess,
			    error: onError
			});
			}else{
				
				$.ajax({
				    type: "POST",
				    url: "php/nouveauanimal.php?choix=modifanimal&client="+choix+"&ani="+id_animal,
				    cache: false,
				    data: formData+"&race="+$('#race2 :input[data-type="search"]').val(),
				    success: onSuccess,
				    error: onError
				});

			}
			return false;
			});			
function onSuccess(data, status)
{
	var choix =  <?php echo $id_pro ; ?>;
	var choix2 = '<?php echo $origin ; ?>';
	$.mobile.loading( "hide" );
	console.log("retour ajax "+data);
	if( id_animal == 0){
		console.log("nouveauclient "+data);
		document.location.href="index.php?idpro3="+choix+"&idani="+data;
		}
	else if(choix2=="consultation"){
		var id_salle_attente = <?php echo json_encode($id_salle_attente); ?>;
		console.log("enregistré --> direction recup_salle_attente");
		var valeur_attente = '<?php echo $valeur_attente; ?>';
		if(valeur_attente=='0'){
		document.location.href="index.php?id_salle_attente="+id_salle_attente;
		}else{
		document.location.href="index.php?id_salle_attente="+id_salle_attente+"&valeur_attente="+valeur_attente;
		}
		
	}else{
		console.log("modifclient");
		history.back();
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
});
</script>
<section class="nouveauclient cf">
<?php
 if(json_decode($id_ani)==0){
 $client2 = json_decode($client, true);
 ?>
<legend><b><?php echo TXT_NOUVEAUANIMAL_CREATEANIMALCARD; ?></b> <?php echo TXT_NOUVEAUANIMAL_FORTHECLIENT." ".$client2[0]['nom']." ".$client2[0]['prenom']." ".TXT_NOUVEAUANIMAL_IDCLIENT.$id_pro; ?></legend>
<?php 
   }else{
$client2 = json_decode($client, true);
$animal2 = json_decode($animal, true);
	?>
<legend><?php echo TXT_NOUVEAUANIMAL_UPDATEANIMALCARD; ?> <b><?php echo $animal2[0]['nom_a']."</b> ".TXT_NOUVEAUANIMAL_NUMBERID." ".$id_ani." ".TXT_NOUVEAUANIMAL_OWNED." ".$client2[0]['nom']." ".$client2[0]['prenom']." ".TXT_NOUVEAUANIMAL_IDCLIENT.$id_pro; ?></legend>
<?php }?>
		<form id="formnouveauanimal">
		<div class="paragraphe">
			<fieldset data-role="fieldcontain"> 
				<label for="aniname"><?php echo TXT_NOUVEAUANIMAL_NAME; ?></label>
				<input type="text" name="aniname" id="aniname" value="<?php echo ((json_decode($id_ani)==0) ? "" : $animal2[0]['nom_a']); ?>">		
			</fieldset>
		</div>
		<div class="paragraphe">
			<fieldset data-role="controlgroup"> 			
  				  <legend><?php echo TXT_NOUVEAUANIMAL_SPECIES; ?></legend>
  				  <?php foreach ($race as $key => $value){
  				  	?>
  				 	 <input type="radio" name="species" id="species<?php echo $key; ?>" value="<?php echo $value; ?>" <?php echo ((json_decode($id_ani)!=0 && $animal2[0]['espece']==$value) ? "checked='checked'" : ''); ?>/>
       				 <label for="species<?php echo $key; ?>"><?php echo $value; ?></label>	  
  				  <?php 
  				  }?>       				
			</fieldset>
			<fieldset data-role="fieldcontain" > 
			<div id="race2">
			<ul id="race" name="race"  data-role="listview" data-filter="true" data-filter-reveal="true" data-filter-placeholder="Recherche race..." data-inset="true"></ul>
			</div>
			</fieldset>
			
			</div>
			<div class="paragraphe">
			<fieldset data-role="controlgroup">
			<legend><?php echo TXT_NOUVEAUANIMAL_GENDER; ?></legend>
     			<input type="radio" name="sexe" id="sexe1" value="<?php echo TXT_NOUVEAUANIMAL_FEMALE; ?>" <?php echo ((json_decode($id_ani)!=0 && $animal2[0]['sexe']==TXT_NOUVEAUANIMAL_FEMALE)  ? "checked='checked'" : ''); ?>/>
     			<label for="sexe1"><?php echo TXT_NOUVEAUANIMAL_FEMALE; ?></label>

     			<input type="radio" name="sexe" id="sexe2" value="<?php echo TXT_NOUVEAUANIMAL_FEMALESTERILIZED; ?>" <?php echo ((json_decode($id_ani)!=0 && $animal2[0]['sexe']==TXT_NOUVEAUANIMAL_FEMALESTERILIZED) ? "checked='checked'" : ''); ?> />
     			<label for="sexe2"><?php echo TXT_NOUVEAUANIMAL_FEMALESTERILIZED; ?></label>

     			<input type="radio" name="sexe" id="sexe3" value="<?php echo TXT_NOUVEAUANIMAL_MALE; ?>" <?php echo ((json_decode($id_ani)!=0 && $animal2[0]['sexe']==TXT_NOUVEAUANIMAL_MALE) ? "checked='checked'" : ''); ?> />
     			<label for="sexe3"><?php echo TXT_NOUVEAUANIMAL_MALE; ?></label>

     			<input type="radio" name="sexe" id="sexe4" value="<?php echo TXT_NOUVEAUANIMAL_MALESTERILIZED; ?>" <?php echo ((json_decode($id_ani)!=0 && $animal2[0]['sexe']==TXT_NOUVEAUANIMAL_MALESTERILIZED) ? "checked='checked'" : ''); ?> />
     			<label for="sexe4"><?php echo TXT_NOUVEAUANIMAL_MALESTERILIZED; ?></label>
			</fieldset>
			</div>	
			<div class="paragraphe">
			<fieldset data-role="fieldcontain">
			<label for="datenais"><?php echo TXT_NOUVEAUANIMAL_BIRTHDAY; ?></label>
			<input type="date" data-role="datebox" name="datenais" id="datenais" data-options='{"mode": "datebox"}' />
  		    <div id=age_div>
  		    <label for="age"><?php echo TXT_NOUVEAUANIMAL_AGE; ?></label>
			<input name="age" id="age" type="text" />
			</div>			
  		    </fieldset>
			</div>	
			<div class="paragraphe">
			<fieldset data-role="fieldcontain">
		<label for="puce"><?php echo TXT_NOUVEAUANIMAL_CHIPSNUMBER; ?></label>
		<input type="text" name="puce" id="puce" value="<?php echo ((json_decode($id_ani)==0) ? "" : $animal2[0]['num_p']); ?>">		
  			</fieldset>
  			<fieldset data-role="fieldcontain">
  		<label for="tatou"><?php echo TXT_NOUVEAUANIMAL_TATOONUMBER; ?></label>
		<input type="text" name="tatou" id="tatou" value="<?php echo ((json_decode($id_ani)==0) ? "" : $animal2[0]['num_t']); ?>">		
  			</fieldset>
  			<fieldset data-role="fieldcontain">
  		<label for="passeport"><?php echo TXT_NOUVEAUANIMAL_PASSNUMBER; ?></label>
		<input type="text" name="passeport" id="passeport" value="<?php echo ((json_decode($id_ani)==0) ? "" : $animal2[0]['num_pa']); ?>">		
  		    </fieldset>
			</div>	
			<div class="paragraphe">
			<fieldset data-role="fieldcontain">
				 <label for="variable2" style="padding:0px"><?php echo TXT_NOUVEAUANIMAL_DANGEROUSANIMAL; ?></label>
					<select name="variable2" id="variable2" data-role="slider" style="padding:0px">
						<option value=1 <?php echo ((($id_ani!=0 && $animal2[0]['variable2']==1) || $id_ani==0) ? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUANIMAL_NO; ?></option>
						<option value=2 <?php echo (($id_ani!=0 && $animal2[0]['variable2']==2) ? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUANIMAL_YES; ?></option>
				</select>	
  			</fieldset>
  			</div>	
  			<div class="paragraphe">
			<fieldset data-role="fieldcontain">
				 <label for="repro" style="padding:0px"><?php echo TXT_NOUVEAUANIMAL_AVAILABLEFORBREEDING; ?></label>
					<select name="repro" id="repro" data-role="slider" style="padding:0px">
						<option value=1 <?php echo ((($id_ani!=0 && $animal2[0]['repro']==1) || $id_ani==0) ? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUANIMAL_NO; ?></option>
						<option value=2 <?php echo (($id_ani!=0 && $animal2[0]['repro']==2) ? "selected=selected" : ""); ?>><?php echo TXT_NOUVEAUANIMAL_YES; ?></option>
				</select>	
  			</fieldset>
  			</div>
  			<div class="paragraphe">
			<fieldset data-role="fieldcontain">
			<label for="datemort"><?php echo TXT_NOUVEAUANIMAL_DATEOFDEATH; ?></label>
			<input type="date" data-role="datebox" name="datemort" id="datemort" data-options='{"mode": "datebox"}' />
  		    </fieldset>
			</div>	
  			<fieldset data-role="fieldcontain" class="ui-grid-a"> 
				 <div class="ui-block-a"><a  data-rel="back" id="retour" name="retour" <?php echo( ($origin=='consultation' ) ? 'class="ui-disabled"' : '' ); ?> data-role="button" data-icon="delete" data-theme="a" rel="external"><?php echo TXT_NOUVEAUANIMAL_BACK; ?></a></div>
				 <div class="ui-block-b"><a id="valid" name="valid" data-role="button" data-icon="plus" data-theme="a" data-chargementtheme="<?php echo $themechargement?>" data-chargementtexte="<?php echo $textechargement?>"><?php echo TXT_NOUVEAUANIMAL_OK; ?></a></div>
			</fieldset>
		</form>
		<div class="paragraphe">
			<h2><?php echo TXT_NOUVEAUANIMAL_ANIMALFILES; ?></h2>
			<div id="dossier" class="explorateur_fichier"></div>
		</div>
</section>
<?php render('_footer')?>

