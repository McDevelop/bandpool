/**
 * @name scene.js
 * @authors Kjell Weibrecht
 * @copyright Copyright 2014 Newcomerszene
 *
 */
 
this.scene = {};

scene = {
	/**
	* Initialisierung
	*/
	init: function() {


	}, // Ende scene.init

	formular: {

		init: function() {		    
			scene.formular.initOeffnen();
			scene.formular.initSchliessen();			
			scene.formular.initAbschicken();			
		}, // Ende scene.formular.init

		
		initOeffnen: function() {
			$(document).off('click', '.beFormularOeffnen').on('click', '.beFormularOeffnen', function(e) {
			    
				log('jaa');
			
				e.preventDefault();
				

				var elem			= $(this),
					elemData		= elem.data(),
					zielelement		= $(elemData.zielelement),
					originalData	= zielelement.data('original');

				// Den Inhalt des Zielelements zwischenspeichern. Nur wenn original noch nicht gesetzt.
				if(originalData === undefined) {
					zielelement.data('original', zielelement.html());
				}

				// Wenn eine uri gesetzt ist, dann soll per Ajax von dort der Inhalt geladen werden.
				if (elemData.uri) {
					$.ajax({
						url: elemData.uri,
						cache: false,
						beforeSend: function() {
							scene.zeug.ladeBildEntfernen(document);
							scene.zeug.ladeBild(zielelement);
							scene.zeug.entferneHighlight( elem );
							scene.zeug.setzeHighlight( zielelement );
						},
						statusCode: {
							404: function() {
								window.location.reload();
							},
							0: function() {
								window.location.reload();
							}
						},
						success: function(data) {
							try {
								var dataObj = jQuery.parseJSON(data);
								if (dataObj.status === 'logout') {
									window.location.reload();
									return;
								}
							} catch (e) {
								zielelement.html(data).addClass('inBearbeitung');
								scene.formular.initFormElemente();
								scene.zeug.verlassenWarnung.on();
							}
						},
						complete: function() {
							scene.zeug.ladeBildEntfernen(document);

							var scrollContainerSelektor = window.opera?'html':'html, body';

							if ($(zielelement).offset() !==  null) {
								// Wieder nach oben scrollen zum Formular
								$(scrollContainerSelektor).animate({scrollTop: $(zielelement).offset().top - 20}, 500);
							}
						}
					});
				}				
			});
		}, // Ende scene.formular.initOeffnen

		initSchliessen: function() {
			$(document).off('click', '.beFormularSchliessen').on('click', '.beFormularSchliessen', function(e) {
				e.preventDefault();

				// Original-Inhalt wieder laden
				var elem = $(this).parents('.boxHighlight');
				elem.html(elem.data('original')).removeClass('inBearbeitung').parents('.inBearbeitung').removeClass('inBearbeitung');
				if (elem.hasClass('versteckt')) elem.hide();

				scene.zeug.entferneHighlight( elem );
				scene.zeug.verlassenWarnung.off();
			});
		}, // Ende scene.formular.initOeffnen

		initAbschicken: function() {			
			$(document).off('submit', '.beFormular').on('submit', '.beFormular', function(e) {
				e.preventDefault();
				scene.formular.abschicken( $(this) );
			});			
		}, // Ende scene.formular.initAbschicken

		// Parameter: that - JQuery Object des Formulars
		abschicken: function(that) {
			// tinyMCE Inhalt in textarea übernehmen
			tinyMCE.triggerSave();
			// alle disabled-Attribute auf Dropdowns entfernen
			that.find('select[disabled]').removeAttr('disabled');

			var thatButton = that.find('.beFormularAbschicken');
			var zielelement = $(thatButton.data('zielelement'));
			if (!zielelement.length) { zielelement = thatButton.parents('.boxHighlight'); }

			scene.zeug.ladeBildEntfernen(document);
			scene.zeug.ladeBild(zielelement);

			// Wenn das Formular per Ajax abgeschickt werden soll.
			if (thatButton.data('uri')) {
				$.ajax({
					url: thatButton.data('uri'),
					data: that.serialize(),
					type: 'POST',
					cache: false,
					statusCode: {
						404: function() {
							window.location.reload();
						},
						0: function() {
							window.location.reload();
						}
					},
					success: function(data) {
						// Wenn ein Objekt zurückkommt, dann wird die Seite neu geladen.
						try {
							var dataObj = jQuery.parseJSON(data);

							// Redirect wenn ausgeloggt
							if (dataObj.status === 'logout') {
								window.location.reload();
							}

							if (dataObj.status === 'ok') {
								scene.zeug.verlassenWarnung.off();

								// Redirect, when set. Else reload
								if (typeof dataObj.redirect === 'undefined' || dataObj.redirect === null || dataObj.redirect === '') {
									window.location.reload();
								} else {									
									window.location = dataObj.redirect;									
								}
							} else {
								$('.error:not(.limitZeichenError)').remove();
								var errorDiv = '<div class="error">';
								if (dataObj.status === 'error') {
									errorDiv += dataObj.error;
								} else {
									errorDiv += 'Es ist ein Fehler aufgetreten.';
								}
								errorDiv += '/<div>';
								zielelement.prepend(errorDiv);

								scene.zeug.verlassenWarnung.off();
								scene.zeug.ladeBildEntfernen(document);
								scene.formular.initLimitZeichen();
							}
						// Sonst wird das html-zeug in die Box geschrieben
						} catch(e) {

							zielelement.html(data);

							scene.zeug.verlassenWarnung.off();
							scene.zeug.ladeBildEntfernen(document);
							scene.formular.initFormElemente();
						}
					}
				});			
			}
			// Ansonsten das Formular so abschicken
			} else  {
				that.submit();
			}
		}, // Ende scene.formular.abschicken

		
	}, // Ende scene.formular

	zeug: {

		initToggle: function() {
			$('.beToggle').unbind('click').click(function(){
				$($(this).data('zielelement')).toggle();
			});
		}, // Ende scene.zeug.initToggle		

		setzeHighlight: function(elem) {
			elem.before('<div class="overlay" style="display: none;"></div>').addClass('boxHighlight');			
			elem.add('.overlay').fadeIn('400');
			
		}, // Ende scene.zeug.setzeHighlight

		entferneHighlight: function(elem) {
			$('.overlay').fadeOut('400', function() {
				$(this).remove();
			});
			elem.removeClass('boxHighlight');
		}, // Ende scene.zeug.setzeHighlight
        
		// Ein LadeBild, wenn irgendwas Passiert.
		ladeBild: function (elem) {
			elem.append('<div class="ladeBild"><img src="http://bandpool/_Resources/Static/Packages/Newcomerscene.Bandpool/img/ajax-loader.gif" /></div>');
		}, // Ende scene.zeug.ladeBild

		// Das Ladebild wieder entfernen
		ladeBildEntfernen: function (elem) {
			$('.ladeBild', elem).remove();
		}, // Ende scene.zeug.ladeBildEntfernen
       

		// Warnung, wenn die Seite mit offenen Formularen verlassen wird.
		verlassenWarnung: {
			on: function() {				
			}, // Ende scene.zeug.verlassenWarnung.on

			off: function() {
				$(window).unbind('beforeunload');
			} // Ende scene.zeug.verlassenWarnung.off
		} // Ende scene.zeug.verlassenWarnung
	}, // Ende scene.zeug	
};

function log(message){
    if (console.log) {
		console.log(message);
    } else {
		alert(message);
   }
}



