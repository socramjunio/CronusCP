<?php

/**
* @arquivo: ProcessModulos.php
* @versão: 1.0
* @descrição: responsável por processar os módulos
* @autor: Socramjunio
*/
	 
// Checagem (caso o index.php não carregue as funções corretamente)
require_once( ROOT .'/application/functions/Core.php' );

class Modulos
{

	$modulos protected;
	global $cc;
	
	function Modulos(){
		## PREPARANDO O MÓDULO EM LEITURA ##
		$this->execute('preparar_estado');
		## CRIANDO AS INSTÂNCIAS DO MENU ##
		$this->menu_();
	}
	
	function execute($switch){
		switch($switch){
			case 'preparar_estado':
				$roteador = new Roteador;
				$modulo->root = ROOT .'/modulos/'.$roteador->acao(1);
				try {
					if(!is_dir($modulo->root))  throw new Exception('Erro ao ler o módulo.');
						else
					if(!is_file($modulo->root .'/app/Menu.php')) throw new Exception('Erro ao ler o menu do módulo.');
						else
					if(!is_file($modulo->root .'/app/Acesso.php')) throw new Exception('Erro ao ler o arquivo de permissao do modulo.');
						else
					$modulos->variaveis = (object) array(
						'menu'=>include($modulo->root .'/app/Menu.php'),
						'permissao'=>include($modulo->root .'/Permissao.php'),
						'instalar'=>include($modulos->root .'/sql-files/Sql_instalacoes.php'),
						'sqls_dir'=>include($modulos->root .'/sql-files/instalacao')
					);
					if(Stuff::group_id < $modulos->variaveis->permissao[$roteador->acao(1)]) throw new Exception('Você não tem permissão para acessar este módulo.');
					// Verificando se as tabelas existem e instalando.
					foreach($modulos->variaveis->instalar as $sqlMods){
						//Explodindo os arquivos e setando suas atuais versões
						if($sqlMods['files'] == ''){
							# Não executa nenhuma função de verificação MySQL caso não haja arquivos
							//throw new Exception('');
						} else {
							$filesSql = explode(",",$sqlMods['files']);
							foreach($filesSql as $sqlFile){
								if( !is_file($modulos->variaveis .'/'.$sqlFile) ){
									throw new Exception('Arquivo de instalação sql do módulo inexistente, contate o administrador');
								} elseif( !$this->instalou_sql($modulos->variaveis .'/'.$sqlFile) ){
									throw new Exception('A parte sql deste módulo não foi instalado ou ocorreu um erro inesperado');
								}
							}
						}
					}
				} catch (MyException $e) {
					trigger_error($e->getMessage());
				}
			break;
		}
	}
	
	//Verificando se os arquivos .sql estão instalados no banco de dados
	function instalou_sql($sqlf){
		$mysql = new mysql;
		$arquivo = file($sqlf);
		$i = 0;
		$dados = array();
		foreach($arquivo as $linha){
			$linha = trim($linha);
			if(empty($linha) || (substr($linha, 0, 1) == '#' || (substr($linha, 0, 1) == '--'))){
				continue;
			}  
			$dados[$i] .= "\n" . $linha;
			if(substr(rtrim($linha), -1, 1) == ';'){
				++$i;
			}
		}
		if(sizeof($dados)){
			foreach($dados as $atual){
				$atual = substr($atual, 0, strlen($atual) - 1);
				if(preg_match("/CREATE TABLE (\S+)/i", $atual, $resultado)){
                	$this->limpa_acento($resultado[1]);
					$this->mysqlQuery("SELECT * FROM {$resultado['1']}");
                	if( $this->mysqlExecute() ){
						return true;
					} else {
						return false;
					}
				}
			}
		} else {
			return false;
		}
	}
	
	// Limpando acentos
	function limpa_acento(&$dados){
		$dados = preg_replace("/^\`/i", "", $dados);
		// Final da string
		$dados = preg_replace("/\`$/i", "", $dados);
	}
	
	function menu_($pasta = ROOT .'/modulos'){ 
		while ($item = readdir($pasta)){
			if ($pasta.'/'.$item == '.' || $pasta.'/'.$item == '..'){
				continue;
			}
			
			if (is_dir("{$pasta}/{$item}")){
				$this->menu("{$pasta}/{$item}");
			}else{
				if( $pasta.'/'.$item == $pasta.'/Menu.php' ){
					$this->menu = array(include_once($pasta.'/'.$item));
				}
			}
		}
	}
	
	function menu(){
		return $this->menu();
	}
}
?>