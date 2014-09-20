<?php

class CursoView extends AbstractView {

	public function __construct ( $curso, $isLogged, $notFound, $links, $user_id ) {

		$this->layout 	=	'curso.html.twig';
		$this->addVar( 'curso', $curso );
		$this->addVar( 'isLogged', $isLogged );
		$this->addVar( 'notFound', $notFound );
		$this->addVar( 'links', $links );
		$this->addVar( 'user_id', $user_id );
	}
}