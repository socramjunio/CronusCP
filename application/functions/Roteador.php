<?php

/**
* @arquivo: Roteador.php
* @versão: 1.0
* @descrição: responsável pelo processo das páginas em Url amigáveis
* @autor: Socramjunio
*/
	 
// Checagem (caso o index.php não carregue as funções corretamente)
require_once( ROOT .'/application/functions/Core.php' );

class Roteador extends mysql
{

    public $uri = array();
    public $controlador;
    public $acao;
	public $tipo;
	
    public function __construct()
    {
		$this->uri = ( isset( $_GET['p'] ) )
            ? explode( '/', $_GET['p'] )
            : array('');
    }

    public function controlador()
    {	
        @$this->controlador = ( $this->uri[0] == NULL )
            ? 'home'
            : $this->uri[0] ;
			
        $limp = ( is_string( $this->controlador ) )
            ? $this->controlador
            : 'home';
		$limpou = parent::ant_sql($limp);
		return $limpou;
    }
	
    public function acao($number=1)
    {
		$limp = parent::ant_sql($this->uri[$number]);
        return $limp;
    }
	
	public function url_amigavel($string)
	{
	$table = array( 
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 
        'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 
    ); 

    // Traduz os caracteres em $string, baseado no vetor $table 
    $string = strtr($string, $table); 

    // converte para minúsculo 
    $string = strtolower($string); 

    // remove caracteres indesejáveis (que não estão no padrão) 
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string); 

    // Remove múltiplas ocorrências de hífens ou espaços 
    $string = preg_replace("/[\s-]+/", " ", $string); 

    // Transforma espaços e underscores em hífens 
    $string = preg_replace("/[\s_]/", "-", $string); 

    // retorna a string 
    return $string; 
	}
	
}

class controleAccess extends Roteador 
{

    function __construct()
    {
        $this->home = HTTP;
        $this->diretorioControlador =  ROOT .'/modulos';
        $this->CaminhoControladorError = THEMES_DIR ."404.php";
        $this->master = $this->root->config("nomeMaster");
        $this->subjetc_fail = $this->root->config("mailMaster");;
        $this->msg_fail = "Erro ao abrir página";
    }

    function Instalar()
    {
        $htaccess = ".htaccess";
        $conteudo = "RewriteEngine On";
        $conteudo .= "\nRewriteCond %{SCRIPT_FILENAME} !-f";
        $conteudo .= "\nRewriteCond %{SCRIPT_FILENAME} !-d";
        $conteudo .= "\nRewriteRule ^(.*)$ index.php?p=$1";

        fopen($htaccess, "w+");

        if (is_writable($htaccess)) {
            if (!$handle = fopen($htaccess, 'a')) {
                echo "Não foi possível abrir o arquivo ($htaccess)";
                exit;
            }

            if (fwrite($handle, $conteudo) === false) {
                echo "Não foi possível escrever no arquivo ($htaccess)";
                exit;
            }

            fclose($handle);
        } else {
            echo "O arquivo $htaccess não pode ser alterado";
        }
    }
	
	function addRulesHtaccess($rules,$coments)
    {
        $htaccess = ".htaccess";
		if(!$rules) exit;
		$conteudo = "\n";
		$conteudo .= "\n";
		$conteudo .= "# $coments\n";
		$conteudo .= $rules;

        if (is_writable($htaccess)) {
            if (!$handle = fopen($htaccess, 'a')) {
                echo "Não foi possível abrir o arquivo ($htaccess)";
                exit;
            }

            if (fwrite($handle, $conteudo) === false) {
                echo "Não foi possível escrever no arquivo ($htaccess)";
                exit;
            } else {
				echo "Sucesso, arquivo ($htaccess) alterado!";
			}

            fclose($handle);
        } else {
            echo "O arquivo $htaccess não pode ser alterado";
        }
    }
	
	function Carrega($modulos)
    {
        $directory = $this->diretorioControlador;
        $page = $directory . $modulos[parent::controlador()]['root'] . "/index.php";
		
		if(preg_match("/(http|www|@|jpg|bmp|exe|png|gif)/",$page)){
			trigger_error('Você está executando um módulo inexistente, operação anulada');
		}
		
        if (file_exists($page)) {
			require_once($page);
        } else {
			require_once($this->CaminhoControladorError);
        }
    }
}
?>