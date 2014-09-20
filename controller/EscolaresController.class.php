<?php

class EscolaresController extends Controller {

	protected 	static $routes 	=	array(
		'index'		=>	'index',
		'login'		=>	'login',
		'users'		=>	'usersList',
		'jV'		=>	'joinMaestroView',
		'pago'		=>	'pagarCursoAction'
	);

	public function __construct ( $app ) {
		$this->app 	=	$app;
	}

	public function index ( ) { 
		

		$curso_perfil 		=	CursoPerfil::with('perfil', 'curso')->where('payed', '=', 0)->orderBy('perfil_id')->get();

		$session 	=	Utilities::getSession();
		
		$links 		=	Utilities::getMenu( $session );

		$this->view 	=	new EscolaresIndexView( $curso_perfil, $links );
		$this->view->display();

		//Utilities::printData( $curso_perfil );

	}
	
	public function login ( $post ) {

		$email 		=	strip_tags( htmlspecialchars( $post['email_input_data'] ) );
		$email 		=	filter_var( $email, FILTER_SANITIZE_EMAIL );
		$email 		=	filter_var( $email, FILTER_VALIDATE_EMAIL );

		$password 	=	strip_tags( htmlspecialchars( $post['password_input_data'] ) );
		$password 	=	filter_var( $password, FILTER_SANITIZE_STRING );

		if ( !$email )
			$this->app->redirect( $this->app->urlFor('admin-login') . '?attempt=1');


		if ( Authentication::Authenticate( $email, $password, 1000 ) ) {

			$session 	=	Utilities::getSession();
			$action 	=	'/admin/' . $session['username'];
			$this->app->redirect( $action );

		}else {
			$this->app->redirect( $this->app->urlFor('admin-login') . '?attempt=2');
		}

	}

	public function usersList ( $username ) {

		$session 	=	Utilities::getSession();
		$username	=	strip_tags( htmlspecialchars( $username ) );
		$username 	=	filter_var( $username, FILTER_SANITIZE_STRING );

		if ( $session['username'] == $username ) {

			$users 		=	User::with('perfil', 'level')->where('level_id','=',4)->get();
			$links 		=	Utilities::getMenu( $session );
			foreach ($users as $key => $value) {
				$action 	=	'/' . $value->perfil->username;
				$value->perfil->action 	=	$action;
			}

			$this->view 	=	new UsersListView( $users, $links );
			$this->view->display();

		}else {

			$this->app->redirect( $app->urlFor('admin-login') );
		}

	}

	public function joinMaestroView ( $attempt = 0 ) {

		$attempt 	=	strip_tags( htmlspecialchars( $attempt ) );
		$attempt 	=	filter_var( $attempt, FILTER_SANITIZE_NUMBER_INT );
		$attempt 	=	filter_var( $attempt, FILTER_VALIDATE_INT );

		$attempt 	=	intval( $attempt );

		$this->view = new JoinView( $this->app->urlFor('admin-maestro-post'), Utilities::createToken(), $attempt );
		$this->view->display();

	}

	public function pagarCursoAction ( $params ) {

		$resultado 	=	array(
							'code'		=>	404,
							'message'	=>	'recurso no encontrado'
						);

		$user_id 	=	strip_tags( htmlspecialchars( $params['user'] ) );
		$user_id 	=	intval( $user_id );
		$user_id 	=	filter_var( $user_id, FILTER_VALIDATE_INT );

		$curso_id 	=	strip_tags( htmlspecialchars( $params['curso'] ) );
		$curso_id 	=	intval( $curso_id );
		$curso_id 	=	filter_var( $curso_id, FILTER_VALIDATE_INT );

		if ( !$user_id || !$curso_id ){
			$resultado['message']	=	'valores invalidos';
			
		}else {

			
			$DB 	=	Connection::getDB();
			$result 	=	$DB::table('perfil')
								->join('curso_perfil', 'perfil.perfil_id', '=', 'curso_perfil.perfil_id')
								->join('curso', 'curso_perfil.curso_id', '=', 'curso.curso_id')
								->select('perfil.perfil_id','perfil.username', 'perfil.email','curso.nombre')
								->where('curso_perfil.perfil_id','=',$user_id)
								->where('curso_perfil.curso_id','=',$curso_id)
								->get();

			if ( count( $result ) == 0 ){
				$resultado['message'] 	=	'usuario y curso no coinciden XD';
			}else {

				$value =	$DB::table('curso_perfil')
								->where('curso_id','=',$curso_id)
								->where('perfil_id','=',$user_id)
								->update( array( 'payed' => 1 ) );

				if ( $value ) {
					$resultado['code']			=	200;
					$resultado['message']		=	'se actualizo correctamente';
					$resultado['curso_perfil']	=	$result[0];
				}else {
					$resultado['message']		=	'no se pudo actualizar XD';
				}
					
			}


		}

		return json_encode( $resultado );

	}



}