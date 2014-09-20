<?php

class CreateCursoView extends AbstractView {

	public function __construct ( $action, $token, $tipo_curso, $maestros, $attempt ) {
		$this->layout 	=	'create_curso.html.twig';
		$this->addVar( 'action', $action );
		$this->addVar( 'token', $token );
		$this->addVar( 'attempt', $attempt );
		$this->addVar( 'tipo_curso', $tipo_curso );
		$this->addVar( 'maestros', $maestros );
	}
}