<!DOCTYPE html>
<html>
<head>
<title><?php echo formatTitle($title)?></title>

	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" href="./js/normalize.css">
	<link rel='stylesheet' type='text/css' href='./js/libs/jquery-ui-1.8.11.custom.css' />
	<link rel="stylesheet" href="./js/jquery.mobile-1.3.2.css" />	
	
	<!-- <script src="./js/jquery-1.9.1.min.js"></script>  -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>    window.jQuery || document.write('<script src="./js/jquery-1.9.1.min.js"><\/script>')</script> 
	<!-- <script type='text/javascript' src='./js/jquery-ui-1.10.3.custom.min.js'></script> -->
	 <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script>    window.jQuery.ui || document.write('<script src="./js/jquery-ui-1.10.3.custom.min.js"><\/script>')</script> 
	<!-- <script src="./js/jquery.mobile-1.3.2.js"></script> -->
	 <script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>	
	<script>    window.jQuery.mobile || document.write('<script src="./js/jquery.mobile-1.3.2.js"><\/script>')</script> 
	
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> 
	
	
	<link rel="stylesheet" type="text/css" href="./js/jqm-datebox.min.css" /> 
	
	<!--  <script type="text/javascript" src="./js/jqm-datebox.core.min.js"></script> -->
	<script src="//dev.jtsage.com/cdn/datebox/1.4.0/jqm-datebox-1.4.0.core.min.js"></script>	
	<script>    window.jQuery.mobile.datebox || document.write('<script src="./js/jqm-datebox.core.min.js"><\/script>')</script>
	
	<!--  <script type="text/javascript" src="./js/jqm-datebox.mode.datebox.min.js"></script> -->
	<script src="//dev.jtsage.com/cdn/datebox/1.4.0/jqm-datebox-1.4.0.mode.datebox.min.js"></script>	
	<script>    window.jQuery.mobile.datebox.prototype._build.datebox || document.write('<script src="./js/jqm-datebox.mode.datebox.min.js"><\/script>')</script>
	
	
	<!--  <script type="text/javascript" src="./js/jqm-datebox.mode.calbox.min.js"></script>-->
	<script src="//dev.jtsage.com/cdn/datebox/1.4.0/jqm-datebox-1.4.0.mode.calbox.min.js"></script>	
	<script>    window.jQuery.mobile.datebox.prototype._build.calbox || document.write('<script src="./js/jqm-datebox.mode.calbox.min.js"><\/script>')</script>
	
	
	<!--  <script src="./js/jquery.mobile.datebox.i18n.fr.utf8.js"></script> -->
	<script src="//dev.jtsage.com/cdn/datebox/i18n/jquery.mobile.datebox.i18n.fr.utf8.js"></script>	
	<script>    window.jQuery.mobile.datebox.prototype._build.custombox || document.write('<script src="./js/jquery.mobile.datebox.i18n.fr.utf8.js"><\/script>')</script>
	
	
		<!--  <script type="text/javascript" src="./js/jqm-datebox.mode.custombox.min.js"></script> -->
	<script src="//dev.jtsage.com/cdn/datebox/1.4.0/jqm-datebox-1.4.0.mode.custombox.min.js"></script>	
	<script>    window.jQuery.mobile.datebox.prototype.options.lang.fr || document.write('<script src="./js/jqm-datebox.mode.custombox.min.js"><\/script>')</script>
	
	
	<link href="./js/jqueryFileTree.css" rel="stylesheet" />
	<script src="./js/jqueryFileTree.js" type="text/javascript"></script>
	
	<!--  <script src="./js/jquery.easing.js" type="text/javascript"></script> -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>	
	<script>    window.jQuery.easing || document.write('<script src="./js/jquery.easing.js"><\/script>')</script>
	
	
	<!--  <script type='text/javascript' src='./js/knockout-2.3.0.js'></script> -->
	<script src="//knockoutjs.com/downloads/knockout-2.3.0.js"></script>	
	<script>    window.ko || document.write('<script src="./js/knockout-2.3.0.js"><\/script>')</script>
	
	<link rel="stylesheet" href="./js/jquery.mobile.iscrollview.css"/>
    <link rel="stylesheet" href="./js/jquery.mobile.iscrollview-pull.css"/>
	
	<script type="text/javascript" src="./js/iscroll.js"></script>
	<script src="./js/jquery.mobile.iscrollview.js"></script>
	
	
	<!--  <script type="text/javascript" src="./js/tinysort.js"></script>	 -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/tinysort/1.5.6/jquery.tinysort.js"></script>	
	<script>    window.jQuery.tinysort || document.write('<script src="./js/tinysort.js"><\/script>')</script>
	
	<script type="text/javascript" src="./js/date.js"></script>
		
	<script type="text/javascript" src="./js/tablesorter/jquery.tablesorter.js"></script>
	<!-- tablesorter widgets (optional) -->
	<script type="text/javascript" src="./js/tablesorter/jquery.tablesorter.widgets.js"></script>	
	<link rel="stylesheet" href="./js/tablesorter/addons/pager/jquery.tablesorter.pager.css">
	<script src="./js/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
	
	<script type="text/javascript" src="./js/raphael-min.js"></script>	
	<script type="text/javascript" src="./js/morris.min.js"></script>
	
	<!-- tiny MCE editeur de texte-->
	<!-- <script type="text/javascript" src="./js/tinymce/tiny_mce.js"></script>	 -->
	<script src="//cdn.jsdelivr.net/tinymce/3.5.10/jquery.tinymce.js"></script>	
	<script>    window.jQuery.tinymce || document.write('<script src="./js/tinymce/tiny_mce.js"><\/script>')</script>
		
	<!-- Agenda week agenda -->	
	<script type='text/javascript' src='./js/jquery.weekcalendar.js'></script>
	<!-- Calendrier page accueil 
	<script type='text/javascript' src='./js/jquery.monthcalendar.js'></script>
	<link rel='stylesheet' type='text/css' href='./js/jquery.monthcalendar.css' />-->
	
	<!-- Calendrier labo -->
	<script type='text/javascript' src='./js/jquery.monthcalendar2.js'></script>
	<link rel='stylesheet' type='text/css' href='./js/jquery.monthcalendar2.css' /> 
	
	
	
	<!-- jcanvas -->
	<script type='text/javascript' src='./js/jcanvas.min.js'></script>
	
	<!--  Creation ordonnance -->
	<script type='text/javascript' src='./js/jquery.ordonnance.js'></script>
	<link rel='stylesheet' type='text/css' href='./js/jquery.ordonnance.css' />
	
	<!-- Postit -->
	<link rel="stylesheet" href="./js/postit/jquery.postitall.css">
	<script src="./js/postit/jquery.postitall.js"></script>
	
	<!-- captcha -->
	
	<link rel="stylesheet" type="text/css" href="./js/captcha/QapTcha.jquery.css" media="screen" />
 
	<!-- jQuery files -->

	<script type="text/javascript" src="./js/captcha/QapTcha.jquery.js"></script>	
  	<link rel='stylesheet' type='text/css' href='./js/libs/jquery.weekcalendar.css' />			
	<link rel="stylesheet" href="./js/tablesorter/theme.default.css">
	
	
	
<link rel="stylesheet" type="text/css" href="./css/style.css" />

</head>
<body> 

<div id="pageencours" data-role="page">
	 
	<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
	    <a href="./" data-icon="home" data-iconpos="notext" data-transition="fade" rel="external">Home</a>
		<h1><?php echo $title?></h1>	
<?php if (!empty($_SESSION['id'])){ ?>

		<div class="ui-grid-a ui-btn-right" style="width:300px">
	    <div class="ui-block-a"><h2>Compte: <?php echo $_SESSION['login2']?></h2></div>
	    <div class="ui-block-b"><a href="php/unload.php" data-ajax="false" data-role="button" data-mini="true" data-icon="delete"><?php echo TXT_HEADER_DISCONNECTION; ?></a></div>
		</div>
		 <div data-role="navbar" data-iconpos="bottom">
        <ul>
        <?php if ($_SESSION['tour']=='0'){ ?>
           	<li><a href="?idpro=0" id="nouveauclient" rel="external"><?php echo TXT_HEADER_NEWCUSTOMER; ?></a></li>
            <li><a href="index.php?gestion_membre=0" id="modif_compte" rel="external"><?php echo TXT_HEADER_YOURSETTINGS; ?></a></li>
            <li><a href="index.php?reglage=0" id="modif_reglage" rel="external"><?php echo TXT_HEADER_CLINICMANAGEMENT; ?></a></li>
            <li><a href="index.php?agenda=0" id="modif_agenda" rel="external"><?php echo TXT_HEADER_ORGANIZER; ?></a></li>
            <li><a href="index.php?labo=0" id="labo" rel="external"><?php echo TXT_HEADER_LABORATORY; ?></a></li>       
        
        <?php }else{ ?>
        	<li style="width: 16.66%;clear: none;"><a href="?idpro=0" id="nouveauclient" rel="external"><?php echo TXT_HEADER_NEWCUSTOMER; ?></a></li>
            <li style="width: 16.66%;clear: none;"><a href="index.php?gestion_membre=0" id="modif_compte" rel="external"><?php echo TXT_HEADER_YOURSETTINGS; ?></a></li>
            <li style="width: 16.66%;clear: none;"><a href="index.php?reglage=0" id="modif_reglage" rel="external"><?php echo TXT_HEADER_CLINICMANAGEMENT; ?></a></li>
            <li style="width: 16.66%;clear: none;"><a href="index.php?agenda=0" id="modif_agenda" rel="external"><?php echo TXT_HEADER_ORGANIZER; ?></a></li>
            <li style="width: 16.66%;clear: none;"><a href="index.php?tourdegarde=0" id="tourdegarde_header" rel="external"><?php echo TXT_HEADER_TURN_DUTY; ?></a></li>
            <li style="width: 16.66%;clear: none;"><a href="index.php?labo=0" id="labo" rel="external"><?php echo TXT_HEADER_LABORATORY; ?></a></li>
            
        <?php }?>
        </ul>
    </div><!-- /navbar -->
		<?php } ?>
	</div><!-- /header -->

	<div data-role="content" <?php echo($_SESSION['login']==$_SESSION['login2'] ? 'class=pattern_perso' : 'class=pattern_garde' );?> >
	