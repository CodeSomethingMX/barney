<?php

class SuscripcionView extends AbstractView {

	public function __construct ( Perfil $perfil, Curso $curso, $token, $action ) {

		$this->layout 	=	'suscripcion.html.twig';
		$this->addVar( 'perfil', $perfil );
		$this->addVar( 'curso', $curso );
		$this->addVar( 'token', $token );
		$this->addVar( 'action', $action );
	}
}