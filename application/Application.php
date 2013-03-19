<?php


	/**
	  * @arquivo: Application.php
	  * @versão: 1.0
	  * @descrição: responsável por salvar as configurações em um arquivo
	  */
	  
	/// Definições
	// Pasta root do sistema
	DEFINE( 'ROOT', str_replace("\\","/",getcwd()) );
	// Endereço HTTP onde está instalado o sistema
	DEFINE( 'HTTP', "http://".$_SERVER['SERVER_NAME'] );
	// Pasta de funções
	DEFINE( 'FUNCTIONS_URL', ROOT .'/application/functions' );
	// Pasta do smarty
	DEFINE( 'SMARTY_TEMPLATE_DIR', FUNCTIONS_URL .'/smarty' );
	// Pasta do smarty
	DEFINE( 'THEMES_DIR', ROOT .'/temas' );
	// Pasta do smarty
	DEFINE( 'THEMES_DIR_C', ROOT .'/temas_c' );
	
	// Configurações
	$config = array(
		'app' => require_once( 'application/Global.php' ),
		'modulos' => require_once( 'application/Modulos.php' ),
		'mysql' => require_once( 'application/MySql.php' ),
		'account' => require_once( 'application/Account.php' ),
		'class' => require_once( 'application/Classes.php' )
	);

?>