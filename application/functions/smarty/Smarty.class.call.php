<?php

class temas extends Smarty {
	
	$app public;
	
	function temas($app)
	{
		$this->Smarty();
		$this->template_dir = THEMES_DIR .'/'.$app['tema'];
		$this->compile_dir = THEMES_DIR_C .'/'.$app['tema'];
		$this->config_dir = SMARTY_TEMPLATE_DIR ."/configs";
		$this->config_dir = SMARTY_TEMPLATE_DIR ."/cache";
		$this->force_compile = $app['smarty_force_compile'];
		$this->debugging = $app['smarty_debugging'];
		$this->caching = $app['smarty_caching'];
		$this->cache_lifetime = $app['smarty_cache_lifetime'];
	
		$this->assign('key',$key);
		$this->assign('app',$app);
		$this->assign('modulos',$modulos);
		$this->assign('class',$class);
		if(isset($_GET['pagina']))
		$this->assign('modulos',$class);
   }

}
?>