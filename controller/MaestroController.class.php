	<?php

class MaestroController extends Controller {

	protected static $routes = array(
		'index'		=>	'index',
		'cMaestro'	=>	'createMaestro',
		'list'		=>	'listMaestros',
		'lView'		=>	'loginView',
		'lM'		=>	'loginPost'
	);

	public function __construct ( $app ) {
		$this->app 	=	$app;
	}

	public function index ( $username ) { 

		$username 	=	strip_tags( htmlspecialchars( $username ) );
		$perfil 	=	Perfil::where('username', '=', $username)->get();

		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );
		$logged		=	false;
		$maestro 	=	null;


		if ( count( $perfil ) > 0 ) {

			$maestro 	=	Maestro::with('cursos', 'perfil')->where('maestro_id', '=', $perfil[0]->perfil_id)->get();
			
			if ( count( $maestro ) > 0 ) {

				$maestro 	=	$maestro[0];

				foreach ($maestro->cursos as $key => $value) {
					$action 	=	'/curso/' . $value->nombre;
					$value->action 	=	$action;
				}
			}
			else
				$maestro = null;

		}

		if ( strcmp($username, $session['username'] ) )
			$logged =	true;

		$this->view 	=	new MaestroView( $maestro, $links, $logged );
		$this->view->display();
		//Utilities::printData( $maestro );

	}

	public function createMaestro ( $post ) {

		//Sanitisar los valores enviados por el usuario ( POST )
		$username 	=	filter_var( $post['username_input_data'], FILTER_SANITIZE_STRING );
		$mail		=	filter_var( $post['email_input_data'], FILTER_SANITIZE_EMAIL );
		$password 	=	filter_var( $post['password_input_data'], FILTER_SANITIZE_STRING );

		$username 	=	strip_tags( htmlspecialchars( $username ) );
		$mail 		=	strip_tags( htmlspecialchars( $mail ) );
		$password 	=	strip_tags( htmlspecialchars( $password ) );

		//Validar el email
		$mail 		=	filter_var( $mail, FILTER_VALIDATE_EMAIL );

		$longitudPass		=	strlen( $password );
		$perfil 			=	Perfil::where('email', '=', $mail)->get();

		//Verificar que el email sea valido
		if ( !$mail )
			$this->app->redirect( $this->app->urlFor('admin-maestro') . '?attempt=1');

		//Verificar la longitud del password
		if ( $longitudPass < 8 )
			$this->app->redirect( $this->app->urlFor('admin-maestro') . '?attempt=2' );

		//Verificar que el email no exista en la base de datos (Perfil)
		if ( count( $perfil ) > 0 ) 
			$this->app->redirect( $this->app->urlFor('admin-maestro') . '?attempt=3');

		//Verificar que el username no exista en la base de datos
		$perfil 			=	Perfil::where('username', '=', $username)->get();

		if ( count( $perfil ) > 0 ) 
			$this->app->redirect( $this->app->urlFor('join') . '?attempt=4');			
		
		//$user 		= 	Authentication::createUser( $username, $mail, $password, 100 );

		$user 				=	new User;
		$salt				=	uniqid();
		$pwd 				=	$password . $salt;
		$hash 				=	hash('sha256', $pwd);
		$user->passwd 		=	$hash;
		$user->salt 		=	$salt;
		$user->level_id 	=	3;

		if ( $user->save() ) {

			$perfil 			=	new Perfil;
			$perfil->perfil_id 	=	$user->user_id;
			$perfil->email 		=	$mail;
			$perfil->username 	=	$username;

			$maestro 				=	new Maestro;
			$maestro->maestro_id 	=	$user->user_id;	

			if ( $perfil->save() && $maestro->save() ) {

				$action 	=	$this->app->urlFor( 'admin-maestro' );
				$this->app->redirect( $action . '?attempt=7');

			}

		}else {
			$action 	=	$this->app->urlFor( 'admin-maestro' );
			$this->app->redirect( $action . '?attempt=5');
		}

	}

	public function listMaestros () {

		$maestros 	=	Maestro::with('perfil')->get();

		foreach ($maestros as $key => $value) {
			$action 	=	'/admin/maestro/' . $value->perfil->username;
			$value->perfil->action =	$action;
		}
		
		//Utilities::printData($maestros);
		$this->view 	=	new ListaMaestros( $maestros );
		$this->view->display();
	}

	public function loginView ( $attempt = 0 ) {

		$attempt 	=	strip_tags( htmlspecialchars( $attempt ) );
		$attempt 	=	filter_var( $attempt, FILTER_SANITIZE_STRING );
		$attempt	=	intval( $attempt );
		$attempt 	=	filter_var( $attempt, FILTER_VALIDATE_INT );

		$action 	=	$this->app->urlFor( 'maestro-login-post' );
		$join 		=	$this->app->urlFor( 'join' );

		$this->view 	=	new LoginView( $action, Utilities::createToken(), $join, $attempt );
		$this->view->display();

	}

	public function loginPost ( $post ) {

		$email 		=	strip_tags( htmlspecialchars( $post['email_input_data'] ) );
		$password 	= 	strip_tags( htmlspecialchars( $post['password_input_data'] ) );

		$email 		=	filter_var( $email, FILTER_SANITIZE_EMAIL );
		$email 		=	filter_var( $email, FILTER_VALIDATE_EMAIL );

		if ( !$email )
			$this->app->redirect( $this->app->urlFor('maestro-login') . '?attempt=1' );

		if ( Authentication::Authenticate( $email, $password, 100 ) ) {

			$session 	=	Utilities::getSession();
			$action 	=	'/admin/maestro/' . $session['username'];
			$this->app->redirect( $action );

		}else {
			$this->app->redirect( $this->app->urlFor('maestro-login') . '?attempt=2' );
		}

	}



}