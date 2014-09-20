<?php

class CursoEditView extends AbstractView {

	public function __construct ( $links, $curso, $tipo_curso, $maestros, $action, $attempt ) {
		$this->layout 	=	'curso_edit.html.twig';
		$this->addVar( 'links', $links );
		$this->addVar( 'curso', $curso );
		$this->addVar( 'tipo_curso', $tipo_curso );
		$this->addVar( 'maestros', $maestros );
		$this->addVar( 'action', $action );
		$this->addVar( 'attempt', $attempt );

	}
}