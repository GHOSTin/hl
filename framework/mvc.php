<?php
class mvc{
	
	public static function get_controller($component_method, information $ctl){
		$import           = self::parse_name($component_method);
		$import['folder'] = 'controllers';
		return self::load_code($import, $ctl);
	}
	/**
	 * Грузит код из директории компонента
	 */
	private static function load_code($import, information $ctl){
		$calls    = [];
		$call     = function($ctl, $__PATH__){
			return require $__PATH__;
		};
		$call_key = self::build_call_key($import);
		try{
			if(isset($calls[$call_key])){
				return $calls[$call_key];
			}else{
				$__PATH__ = self::build_file_path($import);
				#print $__PATH__.'<br>';
				if(file_exists($__PATH__)){
					$calls[$call_key] = $call($ctl, $__PATH__);
					return $calls[$call_key];
				}else{
					throw new exception($call_key.' not found');
				}
			}
		}catch(exception $e){
			die($e->getMessage());
		}
	}	

	private static function exists_file($path){
		return (file_exists($path))? true: false;
	}


	private static function return_function($import_path, information $ctl){
	}

	private static function parse_name($component_method){
		/*

			controller default_page.private.show_default_page
			model environment.get_style_content
			view flat.build_flat_page
			view flat.private.flat_page


		*/	
		$import_array = explode('.', $component_method);
		switch(count($import_array)){
			case 3:
				list($import['component_name'], $import['prefix'], $import['component_method']) = $import_array;
			break;
			case 2:
				list($import['component_name'], $import['component_method']) = $import_array;
			break;
			default:
				die('mvc fail');
		}
		return $import;
	}

	private static function build_file_path($import){
		return (isset($import['prefix']))?
			ROOT.'/'.framework_configuration::application_folder.'/'.$import['component_name'].'/'.$import['folder'].'/'.$import['prefix'].'.'.$import['component_method'].'.php':
			ROOT.'/'.framework_configuration::application_folder.'/'.$import['component_name'].'/'.$import['folder'].'/'.$import['component_method'].'.php';
	}

	private static function build_call_key($import){
		return (isset($import['prefix']))?
			$import['component_name'].'.'.$import['folder'].'.'.$import['prefix'].'.'.$import['component_method']:
			$import['component_name'].'.'.$import['folder'].'.'.$import['component_method'];
	}	
	

	
	public static function get_model($component_method, information $ctl){
		$import           = self::parse_name($component_method);
		$import['folder'] = 'models';
		return self::load_code($import, $ctl);
	}
	
	public static function get_view($component_method, information $ctl){
		$import           = self::parse_name($component_method);
		$import['folder'] = 'views';
		return self::load_code($import, $ctl);
	}	

	/**
	 * Выводит trace
	 */
	private static function print_trace(exception $e, $import_path){
		die($import_path.' ('.$e->getMessage().')');
	}		
}