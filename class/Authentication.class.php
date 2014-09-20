<?php

class Authentication {

	public function __construct () { }

	public static function createUser ( $username, $email, $password, $level ) {

		$user 				=	new User;
		$salt				=	uniqid();
		$pwd 				=	$password . $salt;
		$hash 				=	hash('sha256', $pwd);
		$user->passwd 		=	$hash;
		$user->salt 		=	$salt;
		$user->level_id 	=	$level;

		if ( $user->save() ) {

			$perfil 			=	new Perfil;
			$perfil->perfil_id 	=	$user->user_id;
			$perfil->email 		=	$email;
			$perfil->username 	=	$username;

			if ( $perfil->save() ) {

				self::loadProfil( $user );
				return true;

			}else return false;


		}else return false;

	}


	public static function Authenticate ( $email, $password, $level_required ) {

		$perfil 	=	Perfil::with('user')->where('email', '=', $email)->get();
		
		if ( count( $perfil ) == 0 ) return false;
		else{

			$salt 	=	$perfil[0]->user->salt;
			$hash	=	hash('sha256', $password . $salt );

			if ( $hash == $perfil[0]->user->passwd ) {

				self::loadProfil( $perfil[0]->user);
				
				if ( self::checkAccessRights( $level_required ) ) return true;
				else return false;

			}else return false;
		}

	}


	public static function loadProfil ( User $user ) {

		//Détruit la session
		if ( isset( $_SESSION['perfil'] ) ) session_destroy();

		//Détruit toutes les variables de session
		//$_SESSION = array();

		//regenerer l'id de session
		//session_regenerate_id();

		//IP du client
		$client_ip = $_SERVER['REMOTE_ADDR'];

		$perfil 		= 	array(
			'username'	=>	$user->perfil->username,
			'email'		=>	$user->perfil->email,
			'user_id'	=>	$user->user_id,
			'level_id'	=>	$user->level_id,
			'level'		=>	$user->level->level,
			'client_ip'	=>	$client_ip
		);

		//Création de la variable session 'profil'
		$_SESSION['perfil'] = $perfil;

		//Fermeture de la session
		//session_write_close();

	}
	

	public static function checkAccessRights ( $level_required ) {

		//session_regenerate_id();
		if ( $_SESSION['perfil']['level'] >= $level_required ) return true;
		else return false;
	}

}

?>