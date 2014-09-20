<?php

class GuestController extends Controller {

	protected static $routes = array(

		"index" =>	"index",
		"jView"	=>	"joinView",
		"lView"	=>	'loginView'
	);

	public function __construct ( $app ) {
		$this->app 	=	$app;
	}

	public function index() {
		
		date_default_timezone_set('America/Mexico_City');

		$total_alumnos 	=	12;
		$fecha_actual 	=	date('Y-m-d');
		$fecha_actual 	=	date_create( $fecha_actual );

		$cursos 	=	Curso::where('status', '=', 1)->where('total_alumnos', '<', $total_alumnos)->get();

		$lista_cursos = array();
		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );

		foreach ($cursos as $key => $value) {
			$fechaInicio 	=	date_create( $value->fechaInicio );
			$interval 		=	date_diff( $fecha_actual, $fechaInicio);

			if ( $interval->invert == 0 && ($interval->d >= 0 )  ) {

				$value->action 		=	'/suscribirme/' . $value->curso_id;
				$lista_cursos[] 	=	$value;
			}
				
	
		}
		
		//Utilities::printData( $lista_cursos );
		$this->view = new Index( $this->app->urlFor('join'), $lista_cursos, $links );
		$this->view->display();
	}

	public function joinView ( $attemp = 0 ) {

		$attemp 	=	strip_tags( htmlspecialchars( $attemp ) );
		$attemp 	=	filter_var( $attemp, FILTER_SANITIZE_NUMBER_INT );
		$attemp 	=	filter_var( $attemp, FILTER_VALIDATE_INT );

		$attemp 	=	intval( $attemp );

		/*if ( $attemp == 0 )
			$this->view = new LoginView( $this->app->urlFor('joinPost'), Utilities::createToken() );
		elseif ( $attemp == 1 )
			$this->view = new LoginView( $this->app->urlFor('joinPost'), Utilities::createToken(), $attemp );*/
		
		$this->view = new JoinView( $this->app->urlFor('joinPost'), Utilities::createToken(), $attemp );
		$this->view->display();

	}

	public function loginView ( $params ) {

		$attempt 	=	strip_tags( htmlspecialchars( $params['attempt'] ) );
		$attempt 	=	filter_var( $attempt, FILTER_SANITIZE_STRING );
		$attempt	=	filter_var( $attempt, FILTER_VALIDATE_INT );
		$attempt 	=	intval( $attempt );

		$join 		=	$this->app->urlFor('join');
		$action 	=	$this->app->urlFor('loginPost');

		/*if ( $params['admin'] )
			$action 	=	$this->app->urlFor('admin-login-post');
		else
			$action 	=	$this->app->urlFor('loginPost');*/


		$this->view 	=	new LoginView( $action, Utilities::createToken(), $join, $attempt );
		$this->view->display();
		//Utilities::printData( $params );
		//var_dump($_SESSION['proceso']);

	}

}