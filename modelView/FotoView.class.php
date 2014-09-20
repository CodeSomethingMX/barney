<?php

class FotoView extends AbstractView {

	public function __construct ( $action, $token, Perfil $usuario, $links, $error ) {

		$this->layout 	=	'foto_view.html.twig';
		$this->addVar( 'action', $action );
		$this->addVar( 'token', $token );
		$this->addVar( 'usuario', $usuario );
		$this->addVar( 'links', $links );
		$this->addVar( 'error', $error );

	}
}