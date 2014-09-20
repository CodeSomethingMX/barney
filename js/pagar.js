$(document).ready(function (){

	$('.btn_payed').click(function () {

		var button 	=	document.getElementById( $(this).attr('id') );
		var data_perfil 	=	button.getAttribute('data-perfil');
		var article 		=	$('#perfil' + data_perfil );
		var uri				=	$(this).attr('id');
		console.log( uri );
		$.post(uri, function ( data ) {
			
			data 	=	JSON.parse( data );

			if ( data.code == 200 ) {

				alert( data.message );
				article.remove();
				
			}else {
				alert( data.message );
			}

		});

	});

});