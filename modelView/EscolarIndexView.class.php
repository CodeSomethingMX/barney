<?php

class EscolaresIndexView extends AbstractView {

	public function __construct ( $curso_perfil, $links ) {

		$this->layout 	=	'escolar_index.html.twig';
		$this->addVar( 'curso_perfil', $curso_perfil );
		$this->addVar( 'links', $links );
	}
}