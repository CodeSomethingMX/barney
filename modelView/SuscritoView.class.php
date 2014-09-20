<?php

class SuscritoView extends AbstractView {

	public function __construct ( Perfil $perfil, Curso $curso, $links, $curso_link ) {

		$this->layout 	=	'suscrito.html.twig';
		$this->addVar( 'perfil', $perfil );
		$this->addVar( 'curso', $curso );
		$this->addVar( 'links', $links );
		$this->addVar( 'curso_link', $curso_link );
	}
}