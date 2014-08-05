(function($) {

		$.widget('ui.monthCalendar', (function() {

		return {
			options: {
			date: new Date(),
			shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			firstDayOfWeek: 0,
			data:{},
			data2:{},
			liste:{},
			perso:''			
			},
			_create: function() {  
					var self = this;  
					o = self.options;  
					el = self.element;
					var days = [0, 1, 2, 3, 4, 5, 6];
					self._rotate(days, o.firstDayOfWeek);
					self._creation_calendrier(o.date, days);
			},
			_creation_calendrier: function(date_encours, ordre_jour) {
			    var renderRow, self = this;
			    o = self.options;  
				
				
				renderRow = '<div id=\"calcontainer\">';
				renderRow += '<div id=\"calheader\">';
				renderRow += '<h2><input type="button" class="button_pre" />'+o.shortMonths[date_encours.getMonth()]+' '+date_encours.getFullYear()+'<input type="button" class="button_sui" /></h2>';
				renderRow += '</div>';// fin calheader
				renderRow += '<div id=\"daysweek\">';
				for (var j = 0; j < ordre_jour.length; j++) {
					renderRow += '<div class=\"dayweek\"><p>'+o.shortDays[ordre_jour[j]]+'</p></div>';				
					}
				renderRow += '</div>';// fin daysweek  
				renderRow += '<div id=\"daysmonth\">';
				// first add empty block on the first week
				renderRow += '<div class=\"week\">';
				for (var i = 0; i < ordre_jour.length; i++) {
						if(date_encours.clone().moveToFirstDayOfMonth().getDay() == ordre_jour[i]){
						 break;
						}else{
							renderRow += '<div class=\"day\">';
							renderRow += '<div class=\"daybar\"><p></p></div>';
							renderRow += '<div class=\"dots\">';
							renderRow += '</div>';
							renderRow += '<div class=\"open2\">';
							renderRow += '</div>';
							renderRow += '</div>';						
						}
				
				}
				
						var date_actu = new Date();
						console.log("valeur o.data"+JSON.stringify(o.data));
						console.log("valeur o.data"+JSON.stringify(o.data2));
						for (var j = 1; j <= Date.getDaysInMonth(date_encours.getFullYear(),date_encours.getMonth()) ; j++) {
						
							renderRow += '<div class=\"day\">';
							if(date_actu.getDate() == j && date_actu.getFullYear()==date_encours.getFullYear() && date_actu.getMonth()==date_encours.getMonth()){
							renderRow += '<div class=\"daybar\"><p class="blue">'+j+'</p></div>';
							}else{
							renderRow += '<div class=\"daybar\"><p>'+j+'</p></div>';
							}
							
							renderRow += '<div class=\"dots\">';
								renderRow += '<ul>';
									if(typeof o.data[j] !== "undefined"){
										if(o.data[j]['login']==o.perso){
											renderRow +='<li id=\"marqueur'+j+'\" class=\"red\"></li>';
										}else if(o.data[j]['login']=='???'){
											renderRow +='<li id=\"marqueur'+j+'\" class=\"yellow\"></li>';
										}else{
											renderRow +='<li id=\"marqueur'+j+'\" class=\"green\"></li>';
										}
									}else{
										renderRow +='<li id=\"marqueur'+j+'\" class=\"yellow\"></li>';
									}
									if(typeof o.data2[j] !== "undefined"){
										if(o.data2[j]['login']==o.perso){
											renderRow +='<li id=\"2marqueur'+j+'\" class=\"red\"></li>';
										}else if(o.data2[j]['login']=='???'){
											renderRow +='<li id=\"2marqueur'+j+'\" class=\"yellow\"></li>';
										}else{
											renderRow +='<li id=\"2marqueur'+j+'\" class=\"green\"></li>';
										}
									}else{
										renderRow +='<li id=\"2marqueur'+j+'\" class=\"yellow\"></li>';
									}
								renderRow += '</ul>';
							renderRow += '</div>'; // fin dots
							renderRow += '<div class=\"open2\">';
							renderRow +='<p id=\"veto_choisi'+j+'\">1er :'+(o.data.hasOwnProperty(j) ? o.data[j]['login'] : '???')+'</p>';							
							renderRow +='<select id=\"selection_veto'+j+'\" name=\"selection_veto'+j+'\" number=\"'+j+'\">';
							renderRow += '<option value=\"???\">???</option>';
								$.each(o.liste, function(i, value) {
										renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
								});							
							renderRow += '</select>';
							renderRow += '<button id=\"bouton'+j+'\" number=\"'+j+'\">ok</button>';							
							renderRow +='<p id=\"veto2_choisi'+j+'\">2d :'+(o.data2.hasOwnProperty(j) ? o.data2[j]['login'] : '???')+'</p>';							
							renderRow +='<select id=\"selection2_veto'+j+'\" name=\"selection2_veto'+j+'\" number=\"'+j+'\">';
							renderRow += '<option value=\"???\">???</option>';
								$.each(o.liste, function(i, value) {
										renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
								});							
							renderRow += '</select>';
							renderRow += '<button id=\"2bouton'+j+'\" number=\"'+j+'\">ok</button>';
							renderRow += '</div>'; // fin open2
							renderRow += '</div>'; // fin day
						
						
							if(date_encours.clone().moveToFirstDayOfMonth().addDays(j-1).getDay()==ordre_jour[(ordre_jour.length-1)]){
								renderRow += '</div>'; //fin week
								renderRow += '<div class=\"week\">';
							}						
						}
						var comptage_jour = false;
						var nb_jour = 0;
						for (var i = 0; i < ordre_jour.length; i++) {
								if(comptage_jour){
								nb_jour++;
								}
								if(date_encours.clone().moveToLastDayOfMonth().getDay() == ordre_jour[i]){
								comptage_jour = true;								
								}														
						}					
						for (var i = 0; i < nb_jour; i++) {
						
							renderRow += '<div class=\"day\">';
							renderRow += '<div class=\"daybar\"><p></p></div>';
							renderRow += '<div class=\"dots\">';
							renderRow += '</div>';
							renderRow += '<div class=\"open\">';
							renderRow += '</div>';
							renderRow += '</div>';				
				
							}
				
				
				renderRow += '</div>'; // fin week						
				renderRow += '</div>'; // fin daysmonth
				renderRow += '</div>';
				$(renderRow).appendTo($(self.element));
				
				$(self.element).find('.day').addClass("clickable");
				$(self.element).find('.day').hover(function(){window.status = $(this)}, function(){window.status = ""});
				
				$(self.element).find('.open2').hide();
				
				$(self.element).find('.dots').click(
					function() {
						$(this).parents('div:eq(0)').find('.open2').slideToggle('fast');	
					}
				);	
				
				
				renderRow +='<p id=\"veto2_choisi'+j+'\">2d :'+(o.data2.hasOwnProperty(j) ? o.data2[j]['login'] : '???')+'</p>';							
				renderRow +='<select id=\"selection2_veto'+j+'\" name=\"selection2_veto'+j+'\" number=\"'+j+'\">';
				renderRow += '<option value=\"???\">???</option>';
					$.each(o.liste, function(i, value) {
							renderRow += '<option value=\"'+value['login']+'\">'+value['login']+'</option>';
					});							
				renderRow += '</select>';
				renderRow += '<button id=\"2bouton'+j+'\" number=\"'+j+'\">ok</button>';
				
				$(self.element).find('[id^=2bouton]').click(
						function() {
						$("#veto2_choisi"+$(this).attr('number')).text($("#selection2_veto"+$(this).attr('number')).val());						
						if($("#selection2_veto"+$(this).attr('number')).val()==o.perso){
							$("#2marqueur"+$(this).attr('number')).attr('class', 'red');
						}else if($("#selection2_veto"+$(this).attr('number')).val()=='???'){
							$("#2marqueur"+$(this).attr('number')).attr('class', 'yellow');
						}else{
							$("#2marqueur"+$(this).attr('number')).attr('class', 'green');
								}										
						
						var proceed = self._trigger('changement_veto', null, {
							'jour': $(this).attr('number'),
							'date_depart': o.date,
							'veto': $("#selection2_veto"+$(this).attr('number')).val(),
							'valeur':2
							});
						$(this).parents('div:eq(1)').find('.open2').slideToggle('fast');
				
						}
				
				);
				
				
				$(self.element).find('[id^=bouton]').click(
						function() {
						//alert($(this).attr('number') );
						//alert($("#selection_veto"+$(this).attr('number')).val());
						$("#veto_choisi"+$(this).attr('number')).text($("#selection_veto"+$(this).attr('number')).val());						
						
						if($("#selection_veto"+$(this).attr('number')).val()==o.perso){
							$("#marqueur"+$(this).attr('number')).attr('class', 'red');
						}else if($("#selection_veto"+$(this).attr('number')).val()=='???'){
							$("#marqueur"+$(this).attr('number')).attr('class', 'yellow');
						}else{
							$("#marqueur"+$(this).attr('number')).attr('class', 'green');
								}										
						
						var proceed = self._trigger('changement_veto', null, {
							'jour': $(this).attr('number'),
							'date_depart': o.date,
							'veto': $("#selection_veto"+$(this).attr('number')).val(),
							'valeur':1
							});
						$(this).parents('div:eq(1)').find('.open2').slideToggle('fast');
				
						}
				
				);
				
				$(self.element).find('.button_pre').click(
						function() {
						
								var proceed = self._trigger('rechargement', null, {
									'date_ref': o.date.clone().add({ months: -1 }).getTime()
									});						
						
						}				
				);
				$(self.element).find('.button_sui').click(
						function() {
						
								var proceed = self._trigger('rechargement', null, {
									'date_ref': o.date.clone().add({ months: 1 }).getTime()
									});						
						
						}				
				);
				
				
			},// end fonction _creation_calendrier
			raffraichir: function(donnees,donnees2, date_centrage) {
				var renderRow, self = this;
			    o = self.options;  
			o.date = new Date(date_centrage);
			o.data = $.parseJSON(donnees);
			o.data2 = $.parseJSON(donnees2);
			
			$(this.element).html('');
			
			var days = [0, 1, 2, 3, 4, 5, 6];
			this._rotate(days, o.firstDayOfWeek);
			this._creation_calendrier(o.date, days);

			},
	  
			_rotate: function(a /*array*/, p /* integer, positive integer rotate to the right, negative to the left... */) {
					for (var l = a.length, p = (Math.abs(p) >= l && (p %= l), p < 0 && (p += l), p), i, x; p; p = (Math.ceil(l / p) - 1) * p - l + (l = p)) {
					for (i = l; i > p; x = a[--i], a[i] = a[i - p], a[i - p] = x) {}
					}
					return a;
			},// end function _rotate
			
			
		}; // end of widget function return
		})() //end of widget function closure execution
		);
		
			$.extend($.ui.monthCalendar, {
				  version: '1.0-dev'
			});

})(jQuery);
