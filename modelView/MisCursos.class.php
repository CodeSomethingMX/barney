<?php

class MisCursos extends AbstractView {

	public function __construct( $perfil, $isLogged, $links ){
		$this->layout	=	'mis_cursos.html.twig';
		$this->addVar( 'perfil', $perfil );
		$this->addVar( 'isLogged', $isLogged );
		$this->addVar( 'links', $links );
	}
}