<?php

$imagen_id 	=	$_GET['imagen_id'];
$imagen_id 	=	strip_tags( htmlspecialchars( $imagen_id ) );
$imagen_id 	=	intval( $imagen_id );
$imagen_id 	=	filter_var( $imagen_id, FILTER_VALIDATE_INT );

$dirname 	=	'imagen/';
$file 		=	'imagen/avatar.jpg';

if ( !$imagen_id || $imagen_id == 0 ) {
	$file 	=	'imagen/avatar.jpg';
}
else {

	$archivo 	=	Archivo::find( $imagen_id );

	if ( count( $archivo ) > 0 ) 
		$file 	=	'uploads/avatar/' . $archivo->nombre;
	
}

if ( file_exists( $file ) ) {

	$filename 	=	$dirname . $file;
	$ressource = finfo_open( FILEINFO_MIME_TYPE );
	$type = finfo_file( $ressource, $file );
	finfo_close( $ressource );
	header( "Content-type: $type" );

	readfile($file);
}


?>