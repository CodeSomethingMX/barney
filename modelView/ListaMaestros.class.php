<?php

class ListaMaestros extends AbstractView {

	public function __construct ( $maestros ) {
		$this->layout 		=	'maestros.html.twig';
		$this->addVar( 'maestros', $maestros );
	}
}