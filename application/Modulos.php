<?php

	/**
	  * @arquivo: Modulos.php
	  * @versão: 1.0 - Socramjunio
	  * @descrição: configurações dos módulos
		#	Arquivo de configuração de módulos
		#	@crcp->modulos->config('menu'[return menu array]);
		#	@crcp->modulos->config['arquivos'['return arquivos do módulo e suas versões']];
	  */
	return array(
		// Módulo contendo arquivos da página inicial
		'home' => array(
			'root' => ROOT .'/modulos/home',
			'status' => true
		),
		// Informações
		'informacoes' => array(
			'root' => ROOT .'/modulos/informacoes',
			'status' => true
		),
		// Ranking
		'ranking' => array(
			'root' => ROOT .'/modulos/rankings',
			'status' => true
		),
	);

?>