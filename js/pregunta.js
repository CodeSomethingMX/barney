$(window).ready(function () {

	$('.addPregunta').click(function () {

		var url 	=	$(this).attr('id');
		var pregunta 	=	$('#pregunta').val();
		var asunto 		=	$('#asunto').val();

		if ( pregunta == "" || asunto == "" ) {
			alert('debe escribir algo');
		}else {
			
			var post 	= '{ "pregunta": "' + pregunta + '" , "asunto": "' + asunto + '" }';
			post 		=	JSON.parse( post );

			$.post( url, post, function ( data ) {

				data 	=	JSON.parse( data );
				console.log( data );

				if ( data.code == 200 ) {

					$('#title_pregunta').text('Preguntas');
					var html 	=	'<article class = "pregunta"><h4>' + data.pregunta.asunto + '</h4>';
					html += '<p>' + data.pregunta.descripcion + '</p><strong>' + data.pregunta.fechaEntrada;
					html += '</strong></article>';
					html += '<textarea id = "'+ data.pregunta.pregunta_id +'"></textarea>';
					html +=	'<button class = "reply" id = "/pregunta/' +data.pregunta.pregunta_id+ '" ';
					html +=	'data-reply="'+ data.pregunta.pregunta_id + '">Agregar respuesta</button>';
					html +=	'<div id = "r' + data.pregunta.pregunta_id + '"></div>';
					
					$('#preguntas').append( html );
					
				}else {
					alert( data.message );
				}

			});

		}

	}); 

	$('.reply').click(function () {

		var uri		=	$(this).attr('id');
		var button 	=	document.getElementById( $(this).attr('id') );
		var div_id 	=	'r' + button.getAttribute('data-reply');
		var divRespuesta 	=	$('#' + div_id);
		var descripcion 	=	$('#descripcion' + button.getAttribute('data-reply') ).val();
		
		if ( descripcion == "" ) {
			alert( 'debes escribir una respuesta XD' );
		}else {

			var post 	=	'{"descripcion": "' + descripcion + '"}';
			post 		=	JSON.parse( post );

			$.post( uri, post, function ( data ) {
				//console.log( data );
				data 	=	JSON.parse( data );
				
				if ( data.code == 200 ) {

					var html 	='<div class = "respuesta"><h5>' + data.respuesta.descripcion + '</h5><strong>' + data.respuesta.fechaRespuesta + '</strong></div>'
					divRespuesta.append( html );

				}else {
					alert( data.message );
				}

			});

		}

		
	});

});