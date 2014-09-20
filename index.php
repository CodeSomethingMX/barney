<?php

date_default_timezone_set('America/Mexico_City');

require_once 'vendor/autoload.php';
require_once 'vendor/dompdf/dompdf/dompdf_config.inc.php';
require('vendor/itbz/fpdf/src/fpdf/FPDF.php');
require('vendor/itbz/fpdi/src/fpdi/FPDI.php');


ini_set('display_errors', true);
error_reporting(E_ALL);


//session_cache_limiter( false );
session_start();


function isValidateToken () {

	$app 		= 	\Slim\Slim::getInstance();
	$token_post	=	$app->request->post('token');
	$client_ip 	=	$_SERVER['REMOTE_ADDR'];
	$token 		=	 Utilities::getSessionToken();

	if ( $token_post != $token['token']) $app->redirect( $app->urlFor('join') );
}


function isUserLogged () {

	session_regenerate_id();

	if ( isset( $_SESSION['perfil'] ) )
		return true;
	else
		return false;
}


function userLogged () {

	$app 	=	\Slim\Slim::getInstance();
	session_regenerate_id();

	if ( isset( $_SESSION['perfil'] ) ) {

		$session 	=	Utilities::getSession();
		$action 	=	'';

		if ( $session['level'] <= 10 )
			$app->redirect( '/' . $session['username'] );
		else 
			$app->redirect( '/admin/' . $session['username'] );
		
	}
}


function conectado () {

	$app 	=	\Slim\Slim::getInstance();
	session_regenerate_id();

	if ( !isset( $_SESSION['perfil'] ) )
		$app->redirect( $app->urlFor('Index') );
}


$config 	=	array('debug' => true, 'mode' => 'desarrollo');
$app = new Slim\Slim( $config );


//Ruta principal => Index de la aplicacion
$app->get('/', function () use ( $app ){
	$controller = new GuestController( $app );
	$controller->callAction( 'index' );
})->name('Index');

$app->get('/prueba', function () use ( $app ) {
	
});




//Ruta para mostrar el formulario de registro de un usuario normal
$app->get('/join', 'userLogged', function () use ( $app ) {
	
	$controller = new GuestController( $app );
	if ( $app->request->get('attempt') )
		$controller->callAction( 'jView', $app->request->get('attempt') );
	else
		$controller->callAction( 'jView' );

})->name('join');


/*
Ruta en la cual se realizara el proceso de creacion de un 
usuario de tipo Alumno 
*/
$app->post('/join', 'isValidateToken', function () use ( $app ) {
	$controller 	=	new AlumnoController( $app );
	$controller->callAction( 'cAlumno', $app->request->post() );
})->name('joinPost');


/*
	Ruta que muestra el formulario de login
	=> Email
	=> Password
*/
$app->get('/login', 'userLogged', function () use ( $app ) {
	
	$controller 	=	new GuestController( $app );
	
	$params['attempt'] 	=	null;
	$params['admin']	=	false;

	if ( isset( $_SESSION['proceso'] ) )
		$params['admin'] 	=	true;

	if ( $app->request->get('attempt') )
		//$controller->callAction( 'lView', $app->request->get('attempt') );
		$params['attempt'] 	=	$app->request->get('attempt');
	/*else
		$controller->callAction( 'lView' );*/
	
	$controller->callAction( 'lView', $params );
	//Utilities::printData( $_SESSION['proceso'] );


})->name('login');


/*Ruta en la cual se realizara el proceso de autentificacion*/
$app->post('/login', 'isValidateToken', function () use ( $app ) {
	$controller 	=	new 	AlumnoController( $app );
	$controller->callAction( 'login', $app->request->post() );
})->name('loginPost');



//Ruta para cerrar una sesion
$app->get('/logout', function () use ( $app ) {
	Utilities::logout( $app );
})->name('logout');


/*
Ruta para mostrar el perfil de un usuario conectado / no contectado
*/
$app->get('/:username', function ( $username ) use ( $app ){

	$controller 	=	new AlumnoController( $app );
	$params['user']	=	$username;
	$params['userLogged']	=	null;

	if ( isUserLogged() ) 
		$params['userLogged']	=	true;
		//$controller->callAction( 'index', true );
	else 
		$params['userLogged']	=	false;
		//$controller->callAction( 'index' );

	//Utilities::printData($params);
	$controller->callAction( 'index', $params );

})->name('home');


/*
	Ruta que muestra los cursos asociados a un alumno (perfil)
*/
$app->get('/:username/my-courses/', 'conectado', function ( $username ) use ( $app ) {
	$controller 	=	new AlumnoController( $app );
	$controller->callAction('cView', $username );
})->name('my-courses');


/*
	Ruta que muestra las preferencias de un usuario
*/
$app->get('/:username/settings', 'conectado', function ( $username ) use ( $app ) {
	$controller 	=	new AlumnoController( $app );
	$controller->callAction('settings', $username );
})->name('user-settings');


/*
	Ruta para actualizar el perfil de usuario
*/
$app->post('/:username/settings', 'isValidateToken', 'conectado', function ( $username ) use ( $app ) {
	$controller 		=	new AlumnoController( $app );
	$params 			=	$app->request->post();
	$params['username'] 	=	$username;
	$controller->callAction( 'update', $params);
})->name('update-perfil');


/*
	Ruta para la suscripcion a un curso
*/
$app->get('/suscribirme/:curso_id', function ( $curso_id ) use ( $app ) {

	$params 	=	array(
		'proceso' 	=>	1,
		'curso'		=>	$curso_id
	);

	$_SESSION['proceso'] 	=	$params;

	if ( !isset( $_SESSION['perfil'] ) )
		$app->redirect( $app->urlFor('login') . '?attempt=3' );

	$controller 	=	new AlumnoController( $app );
	$controller->callAction( 'sView', $curso_id );

})->name('suscripcion-curso');


/*
	Ruta para la suscripcion a un curso
*/
$app->post('/suscribirme/:curso_id', 'conectado', function ( $curso_id ) use ( $app ) {
	
	if ( isset( $_SESSION['proceso'] ) ) {

		$controller 	=	new AlumnoController( $app );
		$controller->callAction( 'sAction', $curso_id);

	}else {
		$app->redirect( $app->urlFor('Index') );
	}

})->name('suscripcion-curso-post');


/*
	Ruta que muestra 
*/
$app->get('/:username/suscrito/:curso_id', 'conectado', function ( $username, $curso_id ) use ( $app ) {

	if ( !isset( $_SESSION['proceso'] ) )
		$app->redirect( $app->urlFor('Index') ); 


	$params 		=	array("username" => $username, "curso" => $curso_id);
	$controller 	=	new AlumnoController( $app );
	$controller->callAction( 'susAction', $params );

});



/*
	Ruta que muestra un curso con nombre :curso_nombre
*/
$app->get('/curso/:curso_nombre', 'conectado', function ( $curso_nombre ) use ( $app ) {
	$controller 	=	new CursoController( $app );
	$controller->callAction( 'index', $curso_nombre );
})->name('curso_name');


$app->get('/curso/:curso_nombre/complete', function ( $curso_nombre ) use ( $app ) {
	echo 'el curso ya esta completo<br/>';
	$link 	=	'<a href="' . $app->urlFor('Index') . '">Index</a>';

})->name('curso-completo');



//Formulario para el envio de archivos a una leccion
$app->get('/curso/leccion/:leccion_id/file', function ( $leccion_id ) use ( $app ) {
	$controller 	=	new CursoController( $app );

	$params['leccion']	=	$leccion_id;

	if ( $app->request->get('attempt') )
		$params['get']	=	$app->request->get('attempt');

	$controller->callAction( 'leccion', $params );

})->name('leccion-file-view');


//Guardar archivos de la leccion
$app->post('/curso/leccion/:leccion_id/file', function ( $leccion_id ) use ( $app ) {
	$controller 	=	new CursoController( $app );
	$controller->callAction( 'lPost', $leccion_id );
})->name('leccion-file-post');



//Formulario de autentificacion para el nivel de administracion ( escolare, maestros, admin)
$app->get('/admin/login', 'userLogged', function () use ( $app ) {
	
	$params		=	array();
	$params['admin']	=	true;

	if ( $app->request->get('attempt') )
		$params['attempt'] 	=	$app->request->get('attempt');
	else
		$params['attempt'] 	=	0;

	$controller 	=	new GuestController( $app );
	$controller->callAction( 'lView', $params );

})->name("admin-login");


//autentificacion para el nivel de administracion ( escolare, maestros, admin)
$app->post('/admin/login', 'isValidateToken', function () use ( $app ) {
	$controller 	=	new EscolaresController( $app );
	$controller->callAction( 'login', $app->request->post() );
})->name("admin-login-post");



//Index de un usuario administrador
$app->get('/admin/:username', 'conectado', function ( $username ) use ( $app ) {

	$session 	=	Utilities::getSession();

	if ( $session['level'] == 100 )
		$controller 	=	new MaestroController( $app );
	elseif ( $session['level'] == 1000 )
		$controller 	=	new EscolaresController( $app );
	elseif ( $session['level'] == 10000 )
		$controller 	=	new AdminController( $app );

	$controller->callAction( 'index' );

})->name('admin-user-index');



//Ruta para mostrar todos alumnos que existen en la plataforma BarneyPlatform
$app->get('/admin/:username/users/', 'conectado', function ( $username ) use ( $app ) {
	$controller 	=	new EscolaresController( $app );
	$controller->callAction( 'users', $username );
})->name('admin-list-user');



//Mostrar los cursos activos
$app->get('/admin/cursos/', 'conectado', function () use ( $app ){
	$controller 	=	new CursoController( $app );
	$controller->callAction( 'cursos' );
})->name('cursos');


//Editar un curso :curso_id
$app->get('/curso/:curso_id/edit', function ( $curso_id ) use ( $app ){

	$controller 	=	new CursoController( $app );
	$params['curso']	=	$curso_id;

	if ( $app->request->get('attempt') )
		$params['attempt']	=	$app->request->get('attempt');

	$controller->callAction( 'editV', $params );

})->name('view-edit-cours');


$app->post('/curso/:curso_id/edit', function ( $curso_id ) use ( $app ) {

	$params['curso']	=	$curso_id;
	$params['post']		=	$app->request->post();

	$controller 	=	new CursoController( $app );
	$controller->callAction( 'editA', $params );
	
})->name('edit-cours-post');

//Ruta para pagar un curso
$app->post('/payed/:user_id/:curso_id', function ( $user_id, $curso_id ) use ( $app ) {
	
	$params['user']		=	$user_id;
	$params['curso']	=	$curso_id;

	$controller 	=	new EscolaresController( $app );
	echo $controller->callAction('pago', $params);

});



//Formulario para la creacion de un usuario => Maestro
$app->get('/admin/maestro/', function () use ( $app ) {

	$controller 	=	new EscolaresController( $app );

	if ( $app->request->get('attempt') )
		$controller->callAction( 'jV', $app->request->get('attempt') );
	else
		$controller->callAction( 'jV' );

})->name('admin-maestro');



//Action Post Maestro ( new )
$app->post('/admin/maestro', 'isValidateToken', function () use ( $app ){
	//Utilities::printData($app->request->post());
	$controller 	=	new MaestroController( $app );
	$controller->callAction( 'cMaestro', $app->request->post() );
})->name('admin-maestro-post');



//Login maestro GET
$app->get('/admin/maestro/login', function () use ( $app ) {
	$controller 	=	new MaestroController( $app );
	
	if ( $app->request->get('attempt') )
		$controller->callAction( 'lView', $app->request->get('attempt') );
	else 
		$controller->callAction( 'lView' );

})->name('maestro-login');



//Login maestro POST
$app->post('/admin/maestro/login', 'isValidateToken', function () use ( $app ) {
	$controller 	=	new MaestroController( $app );
	$controller->callAction( 'lM', $app->request->post() );
})->name( 'maestro-login-post' );



//Action para mostrar el perfil de un Maestro
$app->get('/admin/maestro/:username', function ( $username ) use ( $app ) {
	$controller 	=	new MaestroController( $app );
	/*$params['user']			=	$username;
	$params['userLogged']	=	true;

	if ( isUserLogged() )
		$controller->callAction( 'index', $params );
	else
		$app->redirect( $app->urlFor( 'admin-login' ) );*/

	$controller->callAction( 'index', $username );

})->name('maestro');



//Lista de todos los usuarios de tipo "Maestro"
$app->get('/admin/maestros/', function () use ( $app ) {
	$controller 	=	new MaestroController( $app );
	$controller->callAction( 'list' );
})->name('maestro-lista');



//Crear un nuevo curso
$app->get('/admin/curso/', function () use ( $app ) {
	$controller 	=	new CursoController( $app );

	if ( $app->request->get('attempt') )
		$controller->callAction( 'cV', $app->request->get('attempt') );
	else
		$controller->callAction( 'cV' );

})->name('admin-curso');



//Action crear un nuevo curso
$app->post('/admin/curso/', 'isValidateToken', function () use ( $app ) {
	//Utilities::printData($app->request->post());
	$controller 	=	new CursoController( $app );
	$controller->callAction( 'cc', $app->request->post() );
})->name('admin-curso-post');



/*
	Rutas para visualizar y descargar archivos de una leccion
*/

//Descarga de un archivo => file_id 
$app->get('/file/:file_id/download', function ( $file_id ) use ( $app ) {

	Connection::conecting();

	$file_id    =   strip_tags( htmlspecialchars( $file_id ) );
	$file_id    =   filter_var( $file_id, FILTER_SANITIZE_NUMBER_INT );
	$file_id    =   filter_var( $file_id, FILTER_VALIDATE_INT );

	if ( !$file_id ) {
		echo 'no es un id valido';
	}else {

		$archivo = Archivo::find( $file_id );

		if ( count( $archivo ) == 0 ) {
			echo 'no existe el archivo';
		}else {

			$dirname 	=	'uploads/lecciones/';
			$filename 	=	$dirname . $archivo->nombre;

			if ( file_exists( $filename ) ) {

				header( 'Content-Descripcion: File Transfer' );
				header( 'Content-Type: application/octet-stream' );
				header( 'Content-Descripcion: File Transfer' );
				header( 'Content-Disposition: attachment; filename=leccion-' . $archivo->nombre );
				header( 'Content-Transfer-Enconding: binary' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
				header( 'Program: public =' );
				header( 'Content-Length: ' . filesize( $filename ) );

				ob_clean();
				flush();
				readfile( $filename );
				exit;

			}else {
				echo 'lo sentimos, no disponemos de este archivo por el momento';
			}
		}
	}

})->name('download-file');


//Agregar una pregunta a un curso
$app->post('/curso/:curso_id/prefil/:user_id/pregunta', function ( $curso_id, $user_id ) use ( $app ) {
	$params['curso']	=	$curso_id;
	$params['user']		=	$user_id;
	$params['post']		=	$app->request->post();

	$controller 	=	new CursoController( $app );
	$result 	=	$controller->callAction( 'pregunta' , $params );
	echo $result;

})->name('add-pregunta');


//Agregar una respuesta / reply a una pregunta de un curso
$app->post('/pregunta/:pregunta_id/respuesta', function ( $pregunta_id ) use ( $app ) {
	//Utilities::printData( $app->request->post() );
	$params['pregunta']	=	$pregunta_id;
	$params['post']		=	$app->request->post();

	$controller 	=	new CursoController( $app );
	$result = $controller->callAction( 'respuesta', $params );
	echo $result;

})->name('add-respuesta');


$app->get('/usuario/:usuario_id/foto/', function ( $usuario_id ) use ( $app ) {

	$controller 	=	new AlumnoController( $app );
	$params['usuario']	=	$usuario_id;
	$params['attempt'] 	=	null;

	if ( $app->request->get('attempt') )
		$params['attempt'] 	=	$app->request->get('attempt');

	$controller->callAction('fotoV', $params );

})->name('show-photo');

$app->post('/usuario/:usuario_id/foto/', 'isValidateToken', 'conectado', function ( $usuario_id ) use ( $app ) {
	$controller 	=	new AlumnoController( $app ); 
	$controller->callAction('fotoA', $usuario_id );
});


/*
	Ruta para los recursos no encontrados ( codigo 404 )
*/
$app->notFound(function () use ( $app ) {
	echo 'recurso no encontrado';
});



$app->run();


?>