<?php

class UpdateView extends AbstractView {

	public function __construct( Perfil $perfil, $action, $token, $ok = 0 ){
		$this->layout 	=	'UpdateView.html.twig';
		$this->addVar( 'perfil', $perfil );
		$this->addVar( 'action', $action );
		$this->addVar( 'token', $token );
		$this->addVar( 'ok', $ok );
	}
}