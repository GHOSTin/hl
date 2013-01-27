<?php
class model_template{

	private static $twig;

	public static function load_template($path, $args= []){
		list($component, $template_file) = explode('.', $path);
		$file_path = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/'.$template_file.'.tpl';
		if(file_exists($file_path)){
			if(is_null(self::$twig)){
				$loader = new Twig_Loader_String();
				self::$twig = new Twig_Environment($loader);
			}
			$file_string = file_get_contents($file_path);
			print self::$twig->render($file_string, $args);
		}else{
			die('Template '.$path.' not be load!');
		}
	}
}