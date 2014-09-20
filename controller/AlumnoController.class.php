<?php

class AlumnoController extends Controller {

	protected static $routes = array(
		'index'		=>	'index',
		'cAlumno'	=>	'createAlumno',
		'login'		=>	'login',
		'cView'		=>	'cursosView',
		'settings'	=>	'updateView',
		'update'	=>	'updatePerfil',
		'sView'		=>	'suscribirmeView',
		'sAction'	=>	'suscribirmeAction',
		'susAction'	=>	'suscritoAction',
		'fotoV'		=>	'cambiarFotoView',
		'fotoA'		=>	'cambiarFotoAction'
	);

	public function __construct ( $app ) {
		$this->app 	=	$app;
	}

	public function createAlumno ( Array $post ) {

		//var_dump($post);
		//Sanitisar los valores enviados por el usuario ( POST )
		$username 	=	filter_var( $post['username_input_data'], FILTER_SANITIZE_STRING );
		$mail		=	filter_var( $post['email_input_data'], FILTER_SANITIZE_EMAIL );
		$password 	=	filter_var( $post['password_input_data'], FILTER_SANITIZE_STRING );

		$username 	=	strip_tags( $username );
		$mail 		=	strip_tags( $mail );
		$password 	=	strip_tags( $password );

		//Validar el email
		$mail 		=	filter_var( $mail, FILTER_VALIDATE_EMAIL );

		$longitudPass		=	strlen( $password );
		$perfil 			=	Perfil::where('email', '=', $mail)->get();

		//Verificar que el email sea valido
		if ( !$mail )
			$this->app->redirect( $this->app->urlFor('join') . '?attempt=1');

		//Verificar la longitud del password
		if ( $longitudPass < 8 )
			$this->app->redirect( $this->app->urlFor('join') . '?attempt=2' );

		//Verificar que el email no exista en la base de datos (Perfil)
		if ( count( $perfil ) > 0 ) 
			$this->app->redirect( $this->app->urlFor('join') . '?attempt=3');

		//Verificar que el username no exista en la base de datos
		$perfil 			=	Perfil::where('username', '=', $username)->get();

		if ( count( $perfil ) > 0 ) 
			$this->app->redirect( $this->app->urlFor('join') . '?attempt=4');			

		
		$user 		= 	Authentication::createUser( $username, $mail, $password, 4 );

		if ( $user ){

			if ( isset( $_SESSION['proceso'] ) && $_SESSION['proceso']['proceso'] = 1 )
				$this->app->redirect( '/suscribirme/' . $_SESSION['proceso']['curso'] );
			else 
				$this->app->redirect( '/' . $username );

		}else {

			$this->app->redirect( $this->app->urlFor('join') . '?attempt=5');

		}
			
	}

	public function index ( Array $params ) {

		$params['user']		=	strip_tags( htmlspecialchars( $params['user'] ) );
		$params['user']		=	filter_var( $params['user'], FILTER_SANITIZE_STRING );

		$session 	= 	Utilities::getSession();
		$user 		=	Perfil::with('user', 'cursos', 'cursoPerfil')->where('username','=',$params['user'])->get();
		$user 		=	$user[0];
		$exit 		=	$this->app->urlFor('logout');
		$mis_cursos	=	'/' . $user->username . '/my-courses/';
		$session 	=	Utilities::getSession();

		$links 		=	Utilities::getMenu( $session );	


		$this->view 	=	new HomeUser( $params['userLogged'], $user, $links );		
		$this->view->display();		
		//Utilities::printData( $user );

	}

	public function login ( Array $post ) {

		//var_dump($post);
		$email 		=	strip_tags( htmlspecialchars( $post['email_input_data'] ) );
		$email 		=	filter_var( $post['email_input_data'], FILTER_SANITIZE_EMAIL );
		$email 		=	filter_var( $email, FILTER_VALIDATE_EMAIL );

		$password = strip_tags( htmlspecialchars( $post['password_input_data'] ) );
		$password = filter_var( $password, FILTER_SANITIZE_STRING );

		if ( !$email ) 
			$this->app->redirect( $this->app->urlFor('login') . '?attempt=1' );


		if ( Authentication::Authenticate( $email, $password, 10 ) ) {

			$session 	=	 Utilities::getSession();
			
			//var_dump($_SESSION['proceso']);
			if ( isset($_SESSION['proceso'])){
				$action = 	'/suscribirme/' . $_SESSION['proceso']['curso'];
				$this->app->redirect( $action );
			}
			
			$this->app->redirect( '/' . $session['username'] );

		}else {
			$this->app->redirect( $this->app->urlFor('login') . '?attempt=2' );
		}


	}

	
	public function cursosView ( $username ) {

		$username 	=	strip_tags( htmlspecialchars( $username ));
		$username 	=	filter_var( $username, FILTER_SANITIZE_STRING );

		$perfil 	=	Perfil::with('user', 'cursos')->where('username', '=', $username)->get();
		$perfil 	=	$perfil[0];
		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );
		$logged 	=	null;

		foreach ($perfil->cursos as $key => $value) { $value->action 	=	'/curso/' . $value->nombre;	}

		if ( is_null( $session ) )
			$logged 	=	false;
		else
			$logged 	=	true;
			

		$this->view =	new MisCursos( $perfil, $logged, $links);	
		$this->view->display();
		
	}

	
	public function updateView ( $username ) {

		$username 	=	strip_tags( htmlspecialchars( $username ));
		$username 	=	filter_var( $username, FILTER_SANITIZE_STRING );

		$perfil 	=	Perfil::where('username', '=', $username)->get();
		$action 	=	'/' . $perfil[0]->username . '/settings';
		$perfil 	=	$perfil[0];

		if ( isset( $_SESSION['updated']) ) {

			if ( $_SESSION['updated'] == 1 )
				$this->view 	=	new UpdateView( $perfil, $action, Utilities::createToken(), 1 );
			else 
				$this->view 	=	new UpdateView( $perfil, $action, Utilities::createToken(), 2 );

			unset( $_SESSION['updated'] );
			
		}else {

			$this->view 	=	new UpdateView( $perfil, $action, Utilities::createToken() );
		}

		$this->view->display();		

	}

	public function updatePerfil ( $params ) {

		foreach ($params as $key => $value) {
			$value 	=	strip_tags( htmlspecialchars( $value ));
			$value	=	filter_var( $value, FILTER_SANITIZE_STRING );
		}

		$session 	=	Utilities::getSession();

		$perfil 	=	Perfil::where('perfil_id', '=', $session['user_id'])->get();
		$perfil 	=	$perfil[0];

		$perfil->apellidoPaterno 	=	$params['aP_input_data'];
		$perfil->apellidoMaterno	=	$params['aM_input_data'];
		$perfil->telefono 			=	$params['tel_input_data'];
		$perfil->institucion		=	$params['inst_input_data'];
		$perfil->descripcion		=	$params['descr_input_data'];

		if ( $perfil->save() ) 
			$_SESSION['updated'] = 1;
		else 
			$_SESSION['updated'] = 0;


		$this->app->redirect( '/' . $perfil->username . '/settings' );

	}

	public function suscribirmeView ( $curso_id ) {

		$session 		=	Utilities::getSession();
		$perfil 		=	Perfil::where('perfil_id', '=', $session['user_id'])->get();
		$perfil 		=	$perfil[0];
		$curso 			=	Curso::find( $curso_id );
		$action 		=	'/suscribirme/' . $_SESSION['proceso']['curso'];
		$curso_perfil	=	CursoPerfil::where('curso_id', '=', $curso_id)->where('perfil_id','=',$perfil->perfil_id)->get();


		if ( count( $curso_perfil ) > 0 ) {
			unset( $_SESSION['proceso'] );
			$action 	=	'/curso/' . $curso->nombre;
			$this->app->redirect( $action );
		}
			
		$this->view 	=	new SuscripcionView( $perfil, $curso, Utilities::createToken(), $action );
		$this->view->display();
		//echo 'mostrar view suscribirse';
		//echo 'estoy por mostrar la vista de suscripcion';
		//var_dump($_SESSION['proceso']);
	}


	public function suscribirmeAction ( $curso_id ) {

		$curso_id 	=	filter_var( $curso_id, FILTER_SANITIZE_NUMBER_INT );
		$curso_id 	=	filter_var( $curso_id, FILTER_VALIDATE_INT );

		$session 		=	Utilities::getSession();
		$curso 			=	Curso::find( $curso_id );
		$perfil 		=	Perfil::find( $session['user_id'] );
		$curso_perfil 	=	CursoPerfil::where('perfil_id', '=', $perfil->perfil_id)->where('curso_id', '=', $curso_id)->get();
		
		if ( $curso->total_alumnos ==  12 ) {
			$action 	=	'/curso/' . $curso->nombre . '/complete'; 
			$this->app->redirect( $action );
		}

		echo 'aqui voy';
		/*if ( count( $curso_perfil ) == 0 ) {

			if ( count( $curso ) == 0 )
				$this->app->redirect( $this->app->urlFor('join') );

			$curso_perfil  				=	new CursoPerfil;
			$curso_perfil->curso_id 	=	$curso_id;
			$curso_perfil->perfil_id	=	$perfil->perfil_id;
			$curso_perfil->beca_id 		=	1;
			$curso_perfil->payed 		=	0;

			$curso->total_alumnos 		=	$curso->total_alumnos + 1;

			if ( $curso_perfil->save() && $curso->save() ) {

				$params 	=	Utilities::makePDF( $perfil, $curso );

				$archivo			=	new Archivo;
				$archivo->tipo_id 	=	1;
				$archivo->nombre 	=	$params['file_name'];
				$archivo->extension =	'pdf';
				$archivo->save();

				$fecha_actual	=	date('Y-m-d H:m:s');
				$archivo_perfil 				=	new ArchivoPerfil;
				$archivo_perfil->perfil_id		=	$perfil->perfil_id;
				$archivo_perfil->curso_id 		=	$curso->curso_id;
				$archivo_perfil->archivo_id 	=	$archivo->archivo_id;
				$archivo_perfil->fechaExp 		=	$fecha_actual;
				$archivo_perfil->save();

				if ( $params['error'] == 0 ) {

					Utilities::sendMail( $perfil, $curso );
					$continue = '/' . $perfil->username . '/suscrito/' . $curso->curso_id;
				
					$this->app->redirect( $continue );

				}else {

				}

			}else {
				$this->app->redirect( $this->app->urlFor('Index') );
			}
	
		}else {
			$action 	=	'/curso/' . $curso->nombre;
			$this->app->redirect( $action );
		}*/
		
		//echo 'Realizar la funcion suscribirmeAction<br/>';
		//var_dump($_SESSION['proceso']);
	}

	
	public function suscritoAction ( $params ) {

		$username 		=	strip_tags( htmlspecialchars( $params['username'] ) );
		$username 		=	filter_var( $username, FILTER_SANITIZE_STRING );

		$curso_id 		=	strip_tags( htmlspecialchars( $params['curso'] ) );
		$curso_id 		=	filter_var( $curso_id, FILTER_SANITIZE_NUMBER_INT );
		$curso_id 		=	filter_var( $curso_id, FILTER_VALIDATE_INT );

		$session 		=	Utilities::getSession();
		$perfil 		=	Perfil::find( $session['user_id'] );
		$curso 			=	Curso::find( $curso_id );

		$curso_link		=	'/curso/' . $curso->nombre;
		$links 			=	Utilities::getMenu( $session );

		if ( isset( $_SESSION['proceso'] ) )
			unset( $_SESSION['proceso'] );

		$this->view 	=	new SuscritoView( $perfil, $curso, $links, $curso_link );
		$this->view->display();
	}


	public function cambiarFotoView ( $params ) {

		$usuario_id 	=	strip_tags( htmlspecialchars( $params['usuario'] ) );
		$usuario_id 	=	intval( $usuario_id );
		$usuario_id 	=	filter_var( $usuario_id, FILTER_VALIDATE_INT );

		$error = 0;

		if ( !$usuario_id || $usuario_id == 0 )
			$error = 1;

		$usuario 	=	Perfil::find( $usuario_id );

		if ( count( $usuario ) == 0 )
			$error = 2;

		$action 	=	'/usuario/' . $usuario_id . '/foto/';
		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );

		$this->view 	=	new FotoView( $action, Utilities::createToken(), $usuario, $links, $error );
		$this->view->display();

	}


	public function cambiarFotoAction ( $usuario_id ) {

		$usuario_id 	=	strip_tags( htmlspecialchars( $usuario_id ) );
		$usuario_id 	=	intval( $usuario_id );
		$usuario_id 	=	filter_var( $usuario_id, FILTER_VALIDATE_INT );

		if ( !$usuario_id )
			$this->app->redirect( '/usuario/0/foto/' );

		$usuario 	=	Perfil::find( $usuario_id );

		if ( count( $usuario ) == 0 ) {
			$action  	=	'/usuario/' . $usuario_id . '/foto/';
			$this->app->redirect( $action );
		}

		$file 	=	$_FILES['file'];


		foreach ($file['error'] as $key => $error) {
			
			if ( $error == 0 ) {
				$finfo 		=	finfo_open( FILEINFO_MIME_TYPE );
				$mime 		=	finfo_file( $finfo, $file['tmp_name'][$key] );

				if ( strcmp($mime, 'image/jpg') == 0 ) {

					$dirname 	=	'uploads/avatar/';
					$filename	=	uniqid() . '.jpg';
					$uploadfile =	$dirname . $filename;
					
					if ( move_uploaded_file( $file['tmp_name'][$key], $uploadfile ) ) {

						$archivo 	=	new Archivo;
						$archivo->tipo_id	=	4;
						$archivo->nombre 	=	$filename;
						$archivo->peso 		=	$file['size'][$key];
						$archivo->extension =	$file['type'][$key];
						$archivo->save();

						$usuario->fotoPerfil 	=	$archivo->archivo_id;
						$usuario->save();

						$_SESSION['upload']	=	1;

					}else {
						$action 	=	'/usuario/' . $usuario->usuario_id . '/foto/?attempt=2';
						$this->app->redirect( $action );		
					}
				}

			}else {
				$action 	=	'/usuario/' . $usuario->usuario_id . '/foto/?attempt=3';
				$this->app->redirect( $action );
			}		
				
		}



	}



}