<?php

	/**
	  * @arquivo: Mysql.php
	  * @versão: 1.0
	  * @descrição: responsável pelas funções do mysql
	  */
	 
	// Checagem (caso o index.php não carregue as funções corretamente)
	require_once( ROOT .'/application/functions/Core.php' );
	
	// Classe
	class	mysql	extends	core
	{
		
		private
			$a,
			$b,
			$c;
		
		/**
		  *
		  */
		public	function	__construct( $config )
		{
			$this->db = $config;
			$this->dbConn();
		}
		/**
		  * @nome: dbConn
		  * @parametros: ()
		  * @retorno: void
		  * @descrição: s
		  */
		public	function	dbConn( )
		{
			$sql = $this->db;
			$this->conn = mysql_connect( $sql[ 'mysql_host' ], $sql[ 'mysql_user' ], $sql[ 'mysql_pass' ] ) or die( mysql_error() ); 
			$this->conn = mysql_select_db( $sql[ 'mysql_database' ], $this->conn  ) or die( mysql_error() );
			return;
		}
		
		/**
		  * @nome: close
		  * @parametros: ()
		  * @retorno: void
		  * @descrição: Fecha a conexão com MySQL
		  */
		public function close()
		{
			if($this->conn){
				return mysql_close($this->conn);
			} else {
				return false;
			}
		}
		
		/**
		  * @nome: mysqlQuery
		  * @parametros: ( <query a ser guardada > )
		  * @retorno: (int) -> true/false
		  * @descrição: prepara uma query para ser executada
		  */
		public	function	mysqlQuery( $query )
		{
			$this->query = $query;
		}
		
		/**
		  * @nome: mysqlExecute
		  * @parametros: ( < query a ser executada > ) - parâmetro opcional
		  * @retorno: (int) -> true/false
		  * @descrição: executa uma query
		  */
		public	function	mysqlExecute( $execQuery = 0 )
		{
			
			if( !$execQuery )
			{
				if( isset( $this->query ) )
				{
					$this->query = mysql_query( $this->query ) or die( mysql_error() );
				}
			}
			else
			{
				$this->query = mysql_query( $execQuery ) or die( mysql_error() );
			}
			return $this->query;
			
		}
		
		/**
		  * @nome: mysql_countrows
		  * @parametros: ()
		  * @retorno: (int)
		  * @descrição: retorna a quantidade de tabelas afetadas
		  */
		public	function	mysql_countrows()
		{
			return mysql_num_rows($this->query);
		}
		
		/**
		  * @nome: mysql_fetchassoc
		  * @parametros: ()
		  * @retorno: (int)
		  * @descrição: retorna o resultado do mysql
		  */
		public	function	mysql_fetchassoc()
		{
			return mysql_fetch_assoc($this->query);
		}
		
		/**
		  * @nome: mysql_insertid
		  * @parametros: ()
		  * @retorno: (int)
		  * @descrição: retorna o resultado do mysql
		  */
		public	function	mysql_insertid()
		{
			return mysql_insert_id();
		}
		
		/**
		  * @nome: mysql_insertrun
		  * @parametros: ()
		  * @retorno: string array
		  * @uso: Colocar em array os arquivos
		  * @descrição: Isere arquivos no banco de dados MySQL e retorna string array('erro') e array('sucesso')
		  * @autor: Socramjunio
		  */
		public function mysql_insertrun($mysqlInstallArq)
		{
		 if(count($mysqlInstallArq)){
			foreach($mysqlInstallArq as $arq){
				if( !preg_match("/.sql/i",$arq) ) continue;
				$arquivo = file($arq);
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
			}
			if(sizeof($dados)){
				foreach($dados as $atual){
					$atual = substr($atual, 0, strlen($atual) - 1);
					if(preg_match("/CREATE TABLE (\S+)/i", $atual, $resultado)){
                		$this->limpa_acento($resultado[1]);
                		if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Tabela {$resultado[1]} criada";
						} else {
							$this->msg[]['erro'] = "Tabela {$resultado[1]} não pode ser criada";
						}
					}elseif(preg_match("/INSERT INTO (\S+)/i", $atual, $resultado)){
						$this->limpa_acento($resultado[1]);
						if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Iserção de linhas no banco {$resultado[1]} executada";
						} else {
							$this->msg[]['erro'] = "Iserção de linhas no banco {$resultado[1]} não pode ser executada";
						}
            		}elseif(preg_match("/DROP TABLE (IF EXISTS )?(\S+)/i", $atual, $resultado)){
                		$this->limpa_acento($resultado[2]);
                		if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Tabela {$resultado[2]} excluida";
						} else {
							$this->msg[]['erro'] = "Tabela {$resultado[2]} não pode ser excluida";
						}
            		}elseif(preg_match("/DROP DATABASE (IF EXISTS )?(\S+)/i", $atual, $resultado)){
                		$this->limpa_acento($resultado[2]);
                		if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Banco de dados {$resultado[1]} excluido";
						} else {
							$this->msg[]['erro'] = "Banco de dados {$resultado[1]} não pode ser excluido";
						}
            		}elseif(preg_match("/CREATE DATABASE (\S+)/i", $atual, $resultado)){
                		$this->limpa_acento($resultado[1]);
                		if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Banco de dados {$resultado[1]} excluido";
						} else {
							$this->msg[]['erro'] = "Banco de dados {$resultado[1]} não pode ser excluido";
						}
            		}elseif(preg_match("/ALTER TABLE (\S+)/i", $atual, $resultado)){
                		$this->limpa_acento($resultado[1]);
                		if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Banco dados {$resultado[1]} excluido";
						} else {
							$this->msg[]['erro'] = "Banco dados {$resultado[1]} não pode ser excluido";
						}
            		} else {
						if($this->mysqlExecute($atual)){
							$this->msg[]['sucesso'] = "Uma consulta desconhecida foi executada";
						} else {
							$this->msg[]['erro'] = "Uma consulta desconhecida não pode ser executada";
						}
					}
				}
			}
		}
		return $this->msg;
	  }
		
	}

?>