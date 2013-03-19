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
		// Página Principal
		'Principal' => array(
			'root' => ROOT .'/modulos/home',
			'status' => true
		),
		// Informações
		'Informações' => array(
			'root' => ROOT .'/modulos/informacoes',
			'status' => true
		),
		// Ranking
		'Ranking' => array(
			'root' => ROOT .'/modulos/rankings',
			'status' => true
		),
	);

?>