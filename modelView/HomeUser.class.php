<?php

class HomeUser extends AbstractView {

	public function __construct ( $userLogged, $user, $links ) {
		$this->layout 	=	'homeUser.html.twig';
		$this->addVar( 'userLogged', $userLogged );
		$this->addVar( 'user', $user );
		$this->addVar( 'links', $links );
	}
}