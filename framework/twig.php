<?php
class twig{

	private static $instance;
	private static $twig;
	private function __construct(){}
	private function __clone(){} 
	private function __wakeup(){}
    
	public static function get_instance(){
        if(is_null(self::$instance)){
          self::$instance = new twig();
          if(is_null(self::$twig))
          	self::$instance->load_twig();
        }
        return self::$instance;
    }

    public function load_twig(){
        $options = ['cache' => ROOT.'/cache', 'auto_reload' => true];
		$loader = new Twig_Loader_Filesystem(ROOT.'/templates/');
		self::$twig = new Twig_Environment($loader, $options);
    }

    public function get_twig(){
    	return self::$twig;
    }

    public function load_template($name, $args = []){
    	list($component, $template) = explode('.', $name, 2);
    	$template_dir = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/';
    	$loader = self::get_instance()->get_twig()->getLoader();
    	$loader->prependPath($template_dir, $component);
    	return self::get_instance()->get_twig()->render('@'.$component.'/'.$template.'.tpl', $args);
    }
}

function load_template($name, array $args = []){
	return twig::get_instance()->load_template($name, $args);
}