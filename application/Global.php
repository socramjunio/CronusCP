<?php

	/**
	  * @arquivo: Global.php
	  * @versão: 1.0
	  * @descrição: configurações gerais
	  */
	return array(
		'title' => 'CronusCP', 					// Título
		'user_online_count' => true, 			// Mostrar quantidade de usuários online ?
		'tema' => 'padrao', 					// Tema padrão
		## CONFIGURAÇÃO DO SMARTY TEMPLATE
			'smarty_force_compile' => true, 	// Forçar o smarty a compilar sempre os temas ?
			'smarty_debugging' => true, 		// Modo de debugação do Smarty habilitado ?
			'smarty_caching' => true, 			// Habilitar cachê do Smarty ?
			'smarty_cache_lifetime' => 120 		// Tempo de vida do cachê dos temas
		##
	);

?>