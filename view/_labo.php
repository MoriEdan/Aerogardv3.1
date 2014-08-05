<?php render('_header',array('title'=>$title))?>
<script type="text/javascript">
$( document ).ready(  function() {
//	$('#second_part').hide();
	
	var choix_medic = <?php echo json_encode($liste_tournures);?>;
	var liste_cat_delivre = <?php echo json_encode($liste_cat_delivre);?>;
	var info_veto = <?php echo $info_veto;?>;
	var ma_date2 = Date.today();

	
	
	$("#ordonnance").on("vclick", function(){
		$('#mon_ordonnance').ordonnance({
			choix_medic:choix_medic,
			liste_cat_delivre:liste_cat_delivre,
			info_veto:info_veto,
			veto:<?php echo json_encode($_SESSION['login2']);?>,
			ma_date:ma_date2,
			mes_horaires:"Consultations d'urgence du lundi au vendredi de 19h à 8h. Le samedi et le dimanche ouverture toute la journée et la nuit.",
			mes_competences:"MEDECINE - CHIRURGIE - RADIOLOGIE - ECHOGRAPHIE - ANALYSES - HOSPITALISATION",
			ordo_complete: function(e, ui){ 								
				window.open('aerogard/'+ui.valeur);
			}
		});
		
	});

});

</script>

<ul data-role="listview" data-count-theme="c" data-inset="true">
	<li>
	 <div data-role="collapsible">
	 <h2>Créer une ordonnance rapide sans dossier:</h2>
	 <a id="ordonnance" name="ordonnance" data-role="button" data-icon="alert" data-theme="a">générateur ordonnance</a>
	<div id="mon_ordonnance"></div>
	</li>	
</ul>
<?php render('_footer')?>