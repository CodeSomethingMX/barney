<?php

class ListCurso extends AbstractView {

	public function __construct ( $links, $cursos ) {
		$this->layout 	=	'list_curso.html.twig';
		$this->addVar( 'links', $links );
		$this->addVar( 'cursos', $cursos );
	}
}