<!DOCTYPE html>
<html>
<head>
<title><?php echo formatTitle($title)?></title>

 <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" href="./js/normalize.css">
<link rel='stylesheet' type='text/css' href='./js/libs/jquery-ui-1.8.11.custom.css' />
  <link rel="stylesheet" href="./js/jquery.mobile-1.3.2.css" />
  
	<!--<script src="./js/jquery-2.0.3.min.js"></script> -->
	<script src="./js/jquery-1.9.1.min.js"></script>
	<script type='text/javascript' src='./js/jquery-ui-1.10.3.custom.min.js'></script>
	 <script src="./js/jquery.mobile-1.3.2.js"></script>
	
	 <script type="text/javascript" src="./ui/jquery.ui.map.js"></script>
	<script type="text/javascript" src="./ui/jquery.ui.map.services.js"></script>
	<script type="text/javascript" src="./ui/jquery.ui.map.extensions.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> 
	
	
	<link rel="stylesheet" type="text/css" href="./js/jqm-datebox.min.css" /> 
	<script type="text/javascript" src="./js/jqm-datebox.core.min.js"></script>
	<script type="text/javascript" src="./js/jqm-datebox.mode.datebox.min.js"></script>
	<script type="text/javascript" src="./js/jqm-datebox.mode.calbox.min.js"></script>
	<script src="./js/jquery.mobile.datebox.i18n.fr.utf8.js"></script>
	
	<script type="text/javascript" src="./js/jqm-datebox.mode.custombox.min.js"></script>
	
	
	
	<script src="./js/jqueryFileTree.js" type="text/javascript"></script>
	<link href="./js/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
	
	<script src="./js/jquery.easing.js" type="text/javascript"></script>
		
	<script type='text/javascript' src='./js/knockout-2.3.0.js'></script>

	<link rel="stylesheet" href="./js/jquery.mobile.iscrollview.css"/>
    <link rel="stylesheet" href="./js/jquery.mobile.iscrollview-pull.css"/>
	<script type="text/javascript" src="./js/iscroll.js"></script>
	<script src="./js/jquery.mobile.iscrollview.js"></script>
	
	<script type="text/javascript" src="./js/tinysort.js"></script>
	
	<script type="text/javascript" src="./js/date.js"></script>
	
	<script type="text/javascript" src="./js/tablesorter/jquery.tablesorter.js"></script>
	<!-- tablesorter widgets (optional) -->
	<script type="text/javascript" src="./js/tablesorter/jquery.tablesorter.widgets.js"></script>
	
	
	<link rel="stylesheet" href="./js/tablesorter/addons/pager/jquery.tablesorter.pager.css">
	<script src="./js/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
	
	<script type="text/javascript" src="./js/raphael-min.js"></script>
	
	<script type="text/javascript" src="./js/morris.min.js"></script>
	
	<!-- tiny MCE editeur de texte-->
	<script type="text/javascript" src="./js/tinymce/tiny_mce.js"></script>
	
	
	<!-- Agenda week agenda -->
	
	<script type='text/javascript' src='./js/jquery.weekcalendar.js'></script>
	<!-- Calendrier page accueil -->
	<script type='text/javascript' src='./js/jquery.monthcalendar.js'></script>
	<link rel='stylesheet' type='text/css' href='./js/jquery.monthcalendar.css' />
	
	<!-- Postit -->
	<link rel="stylesheet" href="./js/postit/jquery.postitall.css">
	<script src="./js/postit/jquery.postitall.js"></script>
	
	
  	<link rel='stylesheet' type='text/css' href='./js/libs/jquery.weekcalendar.css' />	
	
		
	<link rel="stylesheet" href="./js/tablesorter/theme.default.css">
	
<link rel="stylesheet" type="text/css" href="./css/style.css" />
</head>
<body> 

<div id="pageencours" data-role="page">
	 
	<div data-role="header" data-theme="b">
	    <a href="./" data-icon="home" data-iconpos="notext" data-transition="fade" rel="external">Recharger</a>
		<h1><?php echo $title?></h1>	
<?php if (!empty($_SESSION['id'])){ ?>
	  <a href="php/unload2.php" class="ui-btn-right" data-ajax="false" data-role="button" data-mini="true" data-icon="delete">Déconnexion</a>
	
		 
		<?php } ?>
	</div><!-- /header -->

	<div data-role="content">
	