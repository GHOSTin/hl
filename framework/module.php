<?php
class module{
	
	public static function get_controller($componentMethod, information $ctl){
		
		try{
		
			$ctl = self::load_code('controllers.'.$componentMethod, $ctl);
		
		}catch(exception $e){
		
			self::print_trace($e, 'controllers.'.$componentMethod);
		}	
		
		return $ctl;
	}
	
	/**
	 * Грузит код из директории компонента
	 */
	private static function load_code($folderComponentMethod, information $ctl){
		
		if(empty($folderComponentMethod) OR !is_string($folderComponentMethod)) throw new exception('folder not exist');
			
		$route = explode('.', $folderComponentMethod);

		if($route[0] === 'controllers')
			if(!($route[2] === 'private' OR $route[2]=== 'public'))
				throw new exception('Bad prefix for controller method');
	
		if(count($route) > 3){

			list($mvc, $component, $prefix, $method) = $route;
			$path =	ROOT.'/framework/modules/'.$component.'/'.$mvc.'/'.$prefix.'.'.$method.'.php';

		}else{

			list($mvc, $component, $method) = $route;
			$path =	ROOT.'/framework/modules/'.$component.'/'.$mvc.'/'.$method.'.php';
		}

		

		if(file_exists($path)){

			require $path;

		}else{

			throw new exception('file not found');
		}

		return $ctl;
	}	
	
	/**
	 * Выводит trace
	 */
	private static function print_trace(exception $e, $folderComponentMethod){
			
		die($folderComponentMethod.' ('.$e->getMessage().')');
	}	
	
	public static function get_model($componentMethod, information $ctl){

		try{
			
			$ctl = self::load_code('models.'.$componentMethod, $ctl);
			
		}catch(exception $e){
			
			self::print_trace($e, 'models.'.$componentMethod);
		}
		
		return $ctl;
	}
	
	public static function get_view($componentMethod, information $ctl){

		try{
				
			$ctl = self::load_code('views.'.$componentMethod, $ctl);
				
		}catch(exception $e){
				
			self::print_trace($e, 'views.'.$componentMethod);
		}
		
		return $ctl;
	}	
}
?>