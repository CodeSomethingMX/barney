<?php


class Index extends AbstractView {

	public function __construct ( $conexion, $lista_cursos, $links ) {
		$this->layout = 'index.html.twig';
		$this->addVar( 'login', $conexion );
		$this->addVar( 'lista_cursos', $lista_cursos );
		$this->addVar( 'links', $links );
	}
}