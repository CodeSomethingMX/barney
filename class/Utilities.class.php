<?php

class Utilities {

	public function __construct () { }

	public static function createToken () {


		$token 	=	hash("sha256", uniqid());
		
		$client_ip  = $_SERVER['REMOTE_ADDR'];
		
		$params_token = array(
							'token'		=>	$token,
							'client_ip'	=>	$client_ip,
							'time'		=>	0
						);
		
		//session_regenerate_id();
		$_SESSION['token'] 	=	$params_token;

		return $token;
	}

	public static function getSessionToken () {
		//session_regenerate_id();
		if ( isset( $_SESSION['token'] ) )
			return $_SESSION['token'];
	}

	public static function getSession () {
		//session_regenerate_id();
		if ( isset( $_SESSION['perfil'] ) )
			return $_SESSION['perfil'];
	}

	public static function logout ( $app ) {
		$session = self::getSession();
		$_SESSION 	= 	array();
		session_destroy();

		$app->redirect( $app->urlFor('join') );
	}

	public static function makePDF ( Perfil $perfil, Curso $curso ) {

		$result 	=	array();

		//$html = file_get_contents( $nombre_html );

		$html =
	  	'<html><body>'.
	  	'<p>Put your html here, or generate it with your favourite '.
	  	'templating system.</p>'.
	  	'</body></html>';

		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$output =	$dompdf->output();

		$file_name 		=	$perfil->username . '-suscripcion-curso-' . $curso->curso_id . '.pdf';
		$file_to_save 	=	'uploads/suscripciones/' . $file_name;

		if ( !file_put_contents( $file_to_save, $output ) ) {
			$result['error'] 		=	1;
			$result['message']		=	"No se pudo crear el archivo";
		}else {
			$result['error'] 	=	0;
			$result['message']	=	"Se creo el archivo correctamente";
			$result['file_name']	=	$file_name;
		}
		//$dompdf->stream( "sample.pdf", array("Attachment" => false ) );

		return $result;

	}

	public static function sendMail ( Perfil $perfil, Curso $curso ) {

		//Create a new PHPMailer instance
		$mail = new PHPMailer();

		//Tell PHPMailer to use SMTP
		$mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 2;

		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';

		//Set the hostname of the mail server
		$mail->Host = 'smtp.gmail.com';

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;

		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = "aaronlopezsosa@gmail.com";

		//Password to use for SMTP authentication
		$mail->Password = "losa9304";

		//Set who the message is to be sent from
		$mail->setFrom('administracion@codesomething.com', 'First Last');

		//Set an alternative reply-to address
		$mail->addReplyTo('administracion@codesomething.com', 'First Last');

		//Set who the message is to be sent to
		$mail->addAddress('alsvader@hotmail.com', 'Aaron Lopez Sosa');

		//Set the subject line
		$mail->Subject = 'PHPMailer GMail SMTP test';

		//HTML content
		$html =
	  	'<html><body>'.
	  	'<p>Put your html here, or generate it with your favourite '.
	  	'templating system.</p>'.
	  	'</body></html>';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($html);

		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';

		//Attach an image file
		$file_name =	'uploads/suscripciones/usuario-suscripcion-curso-1.pdf';
		$mail->addAttachment($file_name);

		//send the message, check for errors
		if (!$mail->send()) 
		    return false;
		else 
		    return true;
		
	}

	public static function printData( $data ) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

	public static function getMenu( $session ) {

		if ( $session['level_id'] == 1 ) {

		}elseif ( $session['level_id']== 2) {
		
			$links 		=	array(
				'Usuarios'	=>	array(
									'href'	=>	'/admin/' . $session['username'] . '/users/',
									'label'	=>	'Usuarios'
								),
				'index'		=>	array(
									'href'	=>	'/admin/' . $session['username'],
									'label'	=>	'Index'
								),
				'Maestro'	=>	array(
									'href'	=>	'/admin/maestro/',
									'label'	=>	'Agregar un Nuevo Maestro'
								),
				'Cursos'	=>	array(
									'href'	=>	'/admin/cursos/',
									'label'	=>	'cursos'
								),
				'Nuevo curso'	=>	array(
										'href'	=>	'/admin/curso/',
										'label'	=>	'Agregar un nuevo curso'
									),
				'salir' 	=>	array(
								'href'	=>	'/logout',
								'label'	=>	'salir'
							)
			);

		}elseif ( $session['level_id'] == 3 ){

			$links 		=	array(

				'index'		=>	array(
									'href'	=>	'/admin/maestro/' . $session['username'],
									'label'	=>	'Index'
								),
				'cursos'	=>	array(
									'href'	=>	'/admin/maestro/' . $session['username'] . '/cursos/',
									'label'	=>	'cursos'
								),
				'salir' 	=>	array(
								'href'	=>	'/logout',
								'label'	=>	'salir'
							)
			);

		}elseif ( $session['level_id'] == 4 ){
			
			$links 		=	array(
				'index'			=>	array(
										'href'	=>	'/',
										'label'	=>	'index'
									),
				'perfil'		=>	array(
									'href'	=>	'/' . $session['username'],
									'label'	=>	'perfil'
								),
				'salir' 	=>	array(
									'href'	=>	'/logout',
									'label'	=>	'salir'
								),
				'mis_cursos'	=>	array(
										'href'	=>	'/' . $session['username'] . '/my-courses/',
										'label'	=>	'misCursos'
									)
			);

		}else {
			$links 		=	array(
				'index'	=>	array(
								'href'	=>	'/',
								'label'	=>	'Index'
						)
			);
		}

		return $links;
		
	}
	
}