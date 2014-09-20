<?php

class CursoController extends Controller {

	protected static $routes = array(

		'index'		=>	'index',
		'cV'		=>	'createCursoView',
		'cc'		=>	'createCursoAction',
		'leccion'	=>	'addFileLeccion',
		'lPost'		=>	'addFileLeccionPost',
		'pregunta'	=>	'addPregunta',
		'respuesta'	=>	'addRespuesta',
		'cursos'	=>	'cursosAction',
		'editV'		=>	'editView',
		'editA'		=>	'editAction'
	);

	public function __construct ( $app ) {
		$this->app 	=	$app;
	}

	public function index ( $curso_nombre ) {

		$curso_nombre 	=	strip_tags( htmlspecialchars( $curso_nombre ) );
		$curso_nombre	=	filter_var( $curso_nombre, FILTER_SANITIZE_STRING );
		$curso 	=	Curso::with('unidades', 'preguntas')->where('nombre', '=', $curso_nombre)->get();

		$isLogged = null;
		$notFound = null;

		if ( count( $curso ) == 0 ) {
			$notFound 	=	true;
			
		} else {
			$curso 	=	$curso[0];
			
			foreach ($curso->unidades as $key => $value) {
				$lecciones 	=	Leccion::with('archivos')->where('unidad_id','=',$value->unidad_id)->get();
				
				if ( count( $lecciones ) > 0 ) {
					foreach ($lecciones as $key => $val) {
						$val->addFile 		=	'/curso/leccion/' . $val->leccion_id . '/file';

						foreach ($val->archivos as $k => $archivo) {
							$archivo->download 	=	'/file/' . $archivo->archivo_id . '/download ';
							$archivo->view 		=	'/pdf.php?file=' . $archivo->archivo_id;
						}
					}
				}

				$value->lecciones 	=	$lecciones;

			}

			foreach ($curso->preguntas as $key => $value) {
				$respuestas 	=	Respuesta::where('pregunta_id','=', $value->pregunta_id)->get();
				$value->respuestas 	=	$respuestas;
			}

		}

		$isLogged 	=	array();
		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );
		$user_id	=	null;

		if ( is_null( $session ) ) {
			$isLogged['logged']	=	false;
			$isLogged['nivel']	=	0;
		}else{
			$isLogged['logged']	=	true;
			$isLogged['nivel']	=	$session['level'];
			$user_id 			=	$session['user_id'];
		}
			

		$this->view 	=	new CursoView( $curso, $isLogged, $notFound, $links, $user_id  );
		$this->view->display();
		//Utilities::printData( $curso );

	}


	public function createCursoView ( $attempt ) {

		$attempt 	=	strip_tags( htmlspecialchars( $attempt ) );
		$attempt 	=	filter_var( $attempt, FILTER_SANITIZE_NUMBER_INT );
		$attempt 	=	filter_var( $attempt, FILTER_VALIDATE_INT );	
		$attempt 	=	intval( $attempt );

		$action 	=	$this->app->urlFor( 'admin-curso-post' );
		$tipo_curso =	TipoCurso::all();

		$maestros 	=	Maestro::all();

		$perfiles 	=	array();

		foreach ($maestros as $key => $value) {
			$perfil 	=	Perfil::find( $value->maestro_id );
			$perfiles[]	=	$perfil;
		}

		$this->view =	new CreateCursoView( $action, Utilities::createToken(), $tipo_curso, $perfiles, $attempt );
		$this->view->display();
		//Utilities::printData($perfiles);
	}


	public function createCursoAction ( $post ) {

		$nombre 	=	strip_tags( htmlspecialchars( $post['nombre_input_data'] ) );
		$nombre 	=	filter_var( $nombre, FILTER_SANITIZE_STRING );

		$tipo_select 	=	strip_tags( htmlspecialchars( $post['tipo_select'] ) );
		$tipo_select	=	intval( $tipo_select );
		$tipo_select	=	filter_var( $tipo_select, FILTER_SANITIZE_NUMBER_INT );
		$tipo_select	=	filter_var( $tipo_select, FILTER_VALIDATE_INT );

		$fecha_inicio	=	strip_tags( htmlspecialchars( $post['fecha_inicio'] ) );
		$fecha_inicio 	=	filter_var( $fecha_inicio, FILTER_SANITIZE_STRING );

		$fecha_termino 	=	strip_tags( htmlspecialchars( $post['fecha_termino'] ) );
		$fecha_termino 	=	filter_var( $fecha_termino, FILTER_SANITIZE_STRING );

		$tipo_select 	=	strip_tags( htmlspecialchars( $post['tipo_select'] ) );
		$tipo_select 	=	intval( $tipo_select );
		$tipo_select 	=	filter_var( $tipo_select, FILTER_VALIDATE_INT );

		$maestro 		=	strip_tags( htmlspecialchars( $post['maestro'] ) );
		$maestro 		=	filter_var( $maestro, FILTER_SANITIZE_STRING );
		$maestro 		=	intval( $maestro );
		$maestro 		=	filter_var( $maestro, FILTER_VALIDATE_INT );

		$descr 			=	strip_tags( htmlspecialchars( $post['descr_input_data'] ) );
		$descr 			=	filter_var( $descr, FILTER_SANITIZE_STRING );

		$file 	=	$_FILES['img_input_data'];
		$c 		=	Curso::where('nombre','=',$nombre)->get();

		if ( !Authentication::checkAccessRights( 1000 ) )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=1' );

		if ( count( $c )  > 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=2' );

		if ( strcmp($nombre, "") == 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=3' );

		if ( strcmp($fecha_inicio, "") == 0 || strcmp($fecha_termino, "") == 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=4' );

		if ( $tipo_select == 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=5' );

		if ( $maestro == 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=6' );

		$fecha_actual	=	date('Y-m-d');
		$fecha_actual	=	date_create( $fecha_actual );

		$f1 			=	$fecha_inicio;
		$fecha_inicio	=	date( $fecha_inicio );
		$fecha_inicio 	=	date_create( $fecha_inicio );

		$f2 			=	$fecha_termino;
		$fecha_termino	=	date( $fecha_termino );
		$fecha_termino 	=	date_create( $fecha_termino );

		$interval 		=	date_diff( $fecha_actual, $fecha_inicio );
		$interval2		=	date_diff( $fecha_inicio, $fecha_termino );

		$tipo_curso 	=	TipoCurso::find( $tipo_select );
		$modelMaestro 	=	Maestro::find( $maestro ); 

		if ( $interval->invert == 1 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=7' );

		if ( $interval2->invert == 1 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=8' );

		if ( count( $tipo_curso ) == 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=9' );

		if ( count( $modelMaestro ) == 0 )
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=10' );
		

		if ( $file['error'] == 0 ) {

			$paths 	=	array('image/jpg', 'image/jpeg', 'image/png');
			
			if ( $file['size'] < 100000000000000  ) {

				$finfo 	=	finfo_open( FILEINFO_MIME_TYPE );
				$mime 	=	finfo_file( $finfo, $file['tmp_name'] );
				
				if ( in_array( $mime, $paths ) ) {

					$dir 	=	'uploads/image_curso/';
					$name 	=	uniqid() . $file['name'];
					$uploadfile 	=	$dir . $name;
					
					if ( move_uploaded_file( $file['tmp_name'], $uploadfile ) ) {

						$archivo 	=	new Archivo;
						$archivo->tipo_id 	=	2;
						$archivo->nombre 	=	$name;
						$archivo->peso 		=	$file['size'];
						$archivo->extension =	$mime;
						$archivo->save();

					}else {
						$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=14' );
					}

				}else {
					$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=13' );
				}


			}else {
				$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=12' );
			}

			
		}else {
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=11' );
		}


		$curso  	=	new Curso;
		$curso->tipoCurso 	=	$tipo_select;
		$curso->nombre 		=	$nombre;
		$curso->fechaInicio 	=	$f1;
		$curso->fechaTermino 	=	$f2;
		$curso->status 			=	1;
		$curso->descripcion 	=	$descr;
		$curso->ImagenCurso 	=	$archivo->archivo_id;

		if ( $curso->save() ) {
			
			$curso_maestro 		=	new CursoMaestro;
			$curso_maestro->curso_id 	=	$curso->curso_id;
			$curso_maestro->maestro_id	=	$modelMaestro->maestro_id;
			$curso_maestro->save();

			//self::createUnidades( $tipo_select, $curso->curso_id, $nombre, $this->app );
			$html 	=	array(
				'unidad 1' => array(
					'descripcion'	=>	'Es la primer unidad del curso',
					'lecciones'		=>	array(
						array('nombre' => 'Algo de Historia', 'descripcion' => 'una descripcion de leccion'),
						array('nombre' => 'Conceptos Basicos', 'descripcion' => 'una descripcion de leccion'),
						array('nombre' => 'Primer plantilla', 'descripcion' => 'una descripcion de leccion'),
					)
				),
				'unidad 2' => array(
					'descripcion'	=>	'Es la segunda unidad del curso',
					'lecciones'		=>	array(
						array('nombre' => 'Algo de Historia', 'descripcion' => 'una descripcion de leccion'),
						array('nombre' => 'Conceptos Basicos', 'descripcion' => 'una descripcion de leccion'),
						array('nombre' => 'Primer plantilla', 'descripcion' => 'una descripcion de leccion'),
					)
				),
				'unidad 3' => array(
					'descripcion'	=>	'Es la segunda unidad del curso',
					'lecciones'		=>	array(
						array('nombre' => 'Algo de Historia', 'descripcion' => 'una descripcion de leccion'),
						array('nombre' => 'Conceptos Basicos', 'descripcion' => 'una descripcion de leccion'),
						array('nombre' => 'Primer plantilla', 'descripcion' => 'una descripcion de leccion'),
					)
				)
			);
			
			foreach ($html as $key => $value) {
				$unidad 	=	new Unidad;
				$unidad->nombre 		=	$key;
				$unidad->descripcion 	=	$value['descripcion'];
				$unidad->curso_id 		=	$curso->curso_id;
				$unidad->save();
				
				if ( $unidad->save() ) {
					foreach ($value['lecciones'] as $k => $val) {
						$leccion 	=	new Leccion;
						$leccion->nombre 		=	$val['nombre'];
						$leccion->descripcion	=	$val['descripcion'];
						$leccion->unidad_id 	=	$unidad->unidad_id;
						$leccion->save();
						
					}
				}

			}


			$action 	=	'/curso/' . $nombre;
			$this->app->redirect(  $action );

		
		}else {
			$this->app->redirect( $this->app->urlFor('admin-curso') . '?attempt=15' );
		}

		
	}


	public static function createUnidades( $opcion, $curso_id, $nombre, $app ) {

		$result 	=	false;

		$html 	=	array(
			'unidad 1' => array(
				'descripcion'	=>	'Es la primer unidad del curso',
				'lecciones'		=>	array(
					array('nombre' => 'Algo de Historia', 'descripcion' => 'una descripcion de leccion'),
					array('nombre' => 'Conceptos Basicos', 'descripcion' => 'una descripcion de leccion'),
					array('nombre' => 'Primer plantilla', 'descripcion' => 'una descripcion de leccion'),
				)
			),
			'unidad 2' => array(
				'descripcion'	=>	'Es la segunda unidad del curso',
				'lecciones'		=>	array(
					array('nombre' => 'Algo de Historia', 'descripcion' => 'una descripcion de leccion'),
					array('nombre' => 'Conceptos Basicos', 'descripcion' => 'una descripcion de leccion'),
					array('nombre' => 'Primer plantilla', 'descripcion' => 'una descripcion de leccion'),
				)
			),
			'unidad 3' => array(
				'descripcion'	=>	'Es la segunda unidad del curso',
				'lecciones'		=>	array(
					array('nombre' => 'Algo de Historia', 'descripcion' => 'una descripcion de leccion'),
					array('nombre' => 'Conceptos Basicos', 'descripcion' => 'una descripcion de leccion'),
					array('nombre' => 'Primer plantilla', 'descripcion' => 'una descripcion de leccion'),
				)
			)
		);

		if ( $opcion == 1 ) {

			foreach ($html as $key => $value) {
				$unidad 	=	new Unidad;
				$unidad->nombre 		=	$key;
				$unidad->descripcion 	=	$value['descripcion'];
				$unidad->curso_id 		=	$curso_id;
				$unidad->save();
				
				if ( $unidad->save() ) {
					foreach ($value['lecciones'] as $k => $val) {
						$leccion 	=	new Leccion;
						$leccion->nombre 		=	$val['nombre'];
						$leccion->descripcion	=	$val['descripcion'];
						$leccion->unidad_id 	=	$unidad->unidad_id;
						$leccion->save();
						
					}
					$result =	true;
				}

			}

		}

		return $result;

	}


	public function addFileLeccion ( $params ) {

		$attempt 		=	0;
		$leccion_id 	=	strip_tags( htmlspecialchars( $params['leccion'] ) );
		$leccion_id 	=	intval( $leccion_id );
				
		if ( !$leccion_id )
			$this->app->redirect( $this->app->urlFor('Index') );
		

		$action 	=	'/curso/leccion/' . $leccion_id . '/file';

		if ( array_key_exists('get', $params) ) 
			$attempt =	strip_tags( htmlspecialchars( $params['get'] ) );

		if ( isset( $_SESSION['upload'] ) )
			if ( $_SESSION['upload'] == 1 ){
				unset( $_SESSION['upload'] );
				$attempt 	=	5;
			}


		$this->view 	=	new LeccionFileView( $action, Utilities::createToken(), $attempt );
		$this->view->display();
		

	}

	public function addFileLeccionPost ( $leccion_id ) {

		$file 	=	$_FILES['file'];
		
		foreach ($file['error'] as $key => $error) {
			
			if ( $error == 0 ) {
				$finfo 		=	finfo_open( FILEINFO_MIME_TYPE );
				$mime 		=	finfo_file( $finfo, $file['tmp_name'][$key] );

				if ( strcmp($mime, 'application/pdf') == 0 ) {

					$dirname 	=	'uploads/lecciones/';
					$filename	=	uniqid() . '.pdf';
					$uploadfile =	$dirname . $filename;
					
					if ( move_uploaded_file( $file['tmp_name'][$key], $uploadfile ) ) {

						$archivo 	=	new Archivo;
						$archivo->tipo_id	=	3;
						$archivo->nombre 	=	$filename;
						$archivo->peso 		=	$file['size'][$key];
						$archivo->extension =	$file['type'][$key];
						$archivo->save();

						$archivo_leccion 	=	new ArchivoLeccion;
						$archivo_leccion->archivo_id 	=	$archivo->archivo_id;
						$archivo_leccion->leccion_id	=	$leccion_id;
						$archivo_leccion->save();

						$_SESSION['upload']	=	1;

					}else {
						$action 	=	'/curso/leccion/' . $leccion_id . '/file?attempt=2';
						$this->app->redirect( $action );		
					}
				}

			}else {
				$action 	=	'/curso/leccion/' . $leccion_id . '/file?attempt=' . $error;
				$this->app->redirect( $action );
			}		
				
		}


		$action 	=	'/curso/leccion/' . $leccion_id . '/file';
		$this->app->redirect( $action );
		
	}


	public function addPregunta ( $post ) {

		$curso_id 	= 	strip_tags( htmlspecialchars( $post['curso'] ) );
		$curso_id 	=	intval( $curso_id );
		$curso_id 	=	filter_var( $curso_id, FILTER_SANITIZE_NUMBER_INT );
		$curso_id 	=	filter_var( $curso_id, FILTER_VALIDATE_INT );

		$user_id 	=	strip_tags( htmlspecialchars( $post['user'] ) );
		$user_id 	=	intval( $user_id );
		$user_id 	=	filter_var( $user_id, FILTER_SANITIZE_NUMBER_INT );
		$user_id 	=	filter_var( $user_id, FILTER_VALIDATE_INT ); 

		$asunto			=	strip_tags( htmlspecialchars( $post['post']['asunto'] ) );
		$descripcion	=	strip_tags( htmlspecialchars( $post['post']['pregunta'] ) );

		$respuesta 	=	array(
							'code'		=>	404,
							'message'	=>	'recurso no encontrado'
						);


		if ( !$curso_id || !$user_id ) {
			$respuesta['message']	=	'Curso_id / user_id no valido';
		}
		else {

			$session 	=	Utilities::getSession();
			if ( is_null( $session ) ) {
				$respuesta['message']	=	'no hay una session activa';
			}else {

				$curso 		=	Curso::find( $curso_id );
				$perfil 	=	Perfil::find( $user_id );

				if ( count( $curso ) == 0 || count( $perfil ) == 0 ){
					$respuesta['message']	=	'curso/usuario no encontrado';
				}else {

					$fecha_actual 	=	date( 'Y-m-d' );
					$pregunta 		=	new Pregunta;
					$pregunta->curso_id 	=	$curso_id;
					$pregunta->perfil_id	=	$user_id;
					$pregunta->asunto		=	$asunto;
					$pregunta->descripcion	=	$descripcion;
					$pregunta->fechaEntrada	=	$fecha_actual;

					if ( $pregunta->save() ) {

						$respuesta['code'] 		=	200;
						$respuesta['message']	=	'Se guardo correctamente la pregunta';
						$respuesta['reply']		=	'/pregunta/' . $pregunta->pregunta_id . '/respuesta';
						$respuesta['pregunta']	=	$pregunta;

					}else {
						$respuesta['message']	=	'No se pudo guardar la pregunta';
					}

				}
			}
			
		}


		return json_encode( $respuesta );

	}


	public function addRespuesta ( $post ) {

		$respuesta 		=	array(
								'code'		=>	404,
								'message'	=>	'recurso no encontrado'
							);

		$pregunta_id 	=	strip_tags( htmlspecialchars( $post['pregunta'] ) );
		$pregunta_id 	=	intval( $pregunta_id );
		$pregunta_id 	=	filter_var( $pregunta_id, FILTER_VALIDATE_INT );

		$descripcion	=	strip_tags( htmlspecialchars( $post['post']['descripcion'] ) );
		$session 		=	Utilities::getSession();

		

		if ( is_null( $session ) ) {
			$respuesta['message']	=	'No se encontro alguna sesion activa :s';
		}else {

			if ( !$pregunta_id ) {
				$respuesta['message']	=	'Pregunta no valiad';
			}else {

				if ( strcmp($descripcion, '') == 0 ){
					$respuesta['message']	=	'Debe escribir una respuesta';
				}else {

					
					$pregunta 	=	Pregunta::find( $pregunta_id );

					if ( count( $pregunta ) == 0 ) {
						$respuesta['message']	=	'La pregunta no existe';
					}else {

						$fecha_actual 	=	date( 'Y-m-d' );

						$reply 					=	new Respuesta;
						$reply->perfil_id 		=	$session['user_id'];
						$reply->pregunta_id 	=	$pregunta->pregunta_id;
						$reply->descripcion		=	$descripcion;
						$reply->fechaRespuesta	=	$fecha_actual;

						if ( $reply->save() ) {
							$respuesta['code']				=	200;
							$respuesta['message']			=	'Se guardo correctamente';
							$respuesta['username']			=	$session['username'];
							$respuesta['respuesta']			=	$reply;

						}else {
							$respuesta['message']	=	'Lo sentimos, no se pudo guardar su respuesta';
						}

					}

				}
			}


		}
		
		return json_encode( $respuesta );

	}


	public function cursosAction () {

		$cursos 	=	Curso::where('status', '=', 1)->get();

		foreach ($cursos as $key => $value) {
			$value->edit 	=	'/curso/' . $value->curso_id . '/edit';
			$value->view 	=	'/curso/' . $value->nombre;
			$value->delete 	=	'/curso/' . $value->curso_id . '/delete';
		}

		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );

		$this->view 	=	new ListCurso( $links, $cursos );
		$this->view->display();

	}


	public function editView ( $params ) {

		$curso_id 	=	strip_tags( htmlspecialchars( $params['curso'] ) );
		$curso_id 	=	intval( $curso_id );
		$curso_id 	=	filter_var( $curso_id, FILTER_VALIDATE_INT );

		$attempt 	=	0;
		$session 	=	Utilities::getSession();
		$links 		=	Utilities::getMenu( $session );

		if ( isset( $params['attempt'] ) && is_int( $params['attempt'] ) ) {
			$attempt 	=	strip_tags( htmlspecialchars( $params['attempt'] ) );
			$attempt 	=	intval( $attempt );
			$attempt 	=	filter_var( $attempt, FILTER_VALIDATE_INT );
		}

		if ( !$curso_id )
			$this->app->redirect( $this->app->urlFor('Index') );


		$curso 		=	Curso::find( $curso_id );

		if ( count( $curso ) == 0 )
			$this->app->redirect( $this->app->urlFor('Index') );

		$action 	=	'/curso/' . $curso->curso_id . '/edit';
		$tipo_curso =	TipoCurso::all();
		$maestros 	=	Maestro::with('perfil')->get();

		$this->view 	=	new CursoEditView( $links, $curso, $tipo_curso, $maestros, $action, $attempt );
		$this->view->display();
		//Utilities::printData($maestros);

	}


	public function editAction ( $params ) {

		$curso_id 	=	strip_tags( htmlspecialchars( $params['curso'] ) );
		$curso_id 	=	intval( $curso_id );
		$curso_id 	=	filter_var( $curso_id, FILTER_VALIDATE_INT );

		$maestro_id 	=	strip_tags( htmlspecialchars( $params['maestro'] ) );
		$maestro_id		=	intval( $maestro_id );
		$maestro_id 	=	filter_var( $maestro_id, FILTER_VALIDATE_INT );

		$nombre 		=	strip_tags( htmlspecialchars( $params['post']['nombre_curso'] ) );

		$nombre_curso 	=	strip_tags( htmlspecialchars( $params['post']['nombre_curso'] ) );
		$descripcion 	=	strip_tags( htmlspecialchars( $params['post']['descr_curso'] ) );

		$fecha_termino 	=	strip_tags( htmlspecialchars( $params['post']['fecha_termino'] ) );


		if ( !$curso_id )
			$this->app->redirect( $this->app->urlFor('Index') );


		$curso 			=	Cuso::find( $curso_id );
		$maestro 		=	Maestro::find( $maestro_id );

		if ( count( $curso ) == 0 )
			$this->app->redirect( $this->app->urlFor('Index') );


		if ( count( $maestro ) == 0 )
			$this->app->redirect( $this->app->urlFor('Index') );

		if ( strcmp($nombre, '') == 0 )
			$this->app->redirect( '/curso/' . $curso->curso_id . '/edit?attempt=1' );

		

	}
	

}