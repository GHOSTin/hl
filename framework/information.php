<?php
/**
 * Класс создает информационный контейнер с метаинформацией
 * 
 * @author Eugene Nekrasov (nekrasov@mlsco.ru)
 * @version 2.4
 */
final class information{

	private $error;
	private $errorcode;
	private $data;
	
	public function __construct($error = true, $errorcode = 'unknown', $data = null){
		$this->set_error($error);
		($error)? $this->set_errorcode($errorcode): $this->unset_errorcode();
		$this->set_self($data);
	}
	
	public function __call($method, $arguments){
		try{
			throw new exception('Not exist method "'.$method.'"!');
		}catch(exception $e){
			$trace = '';
			foreach($e->getTrace() as $value){
				$trace .= '<div>'.$value['class'].':'.$value['function'].' '.$value['line'].'</div>';
			}
			die($e->getFile().' '.$e->getLine().'('.$e->getMessage().')<div>'.$trace.'</div>');
		}		
	}
	
	public function __get($property){
		try{
			throw new exception('You can not get access a property "'.$property.'"!');
		}catch(exception $e){
			$trace = '';
			foreach($e->getTrace() as $value){
				$trace .= '<div>'.$value['class'].':'.$value['function'].' '.$value['line'].'</div>';
			}
			die($e->getFile().' '.$e->getLine().'('.$e->getMessage().')<div>'.$trace.'</div>');
		}	
	} 
	
	public function __isset($property){
		try{
			throw new exception('You can not get access a property "'.$property.'"!');
		}catch(exception $e){
			$trace = '';
			foreach($e->getTrace() as $value){
				$trace .= '<div>'.$value['class'].':'.$value['function'].' '.$value['line'].'</div>';
			}
			die($e->getFile().' '.$e->getLine().'('.$e->getMessage().')<div>'.$trace.'</div>');
		}		
	}	
	
	public function __set($property, $value){
		try{
			throw new exception('You set not create a property "'.$property.'"!');
		}catch(exception $e){
			$trace = '';
			foreach($e->getTrace() as $value){
				$trace .= '<div>'.$value['class'].':'.$value['function'].' '.$value['line'].'</div>';
			}
			die($e->getFile().' '.$e->getLine().'('.$e->getMessage().')<div>'.$trace.'</div>');
		}		
	} 	
	
	public function __unset($property){
	
		try{
				
			throw new exception('You can not get access a property "'.$property.'"!');
				
		}catch(exception $e){
				
			$trace = '';

			foreach($e->getTrace() as $value){
				
				$trace .= '<div>'.$value['class'].':'.$value['function'].' '.$value['line'].'</div>';
			}
			
			die($e->getFile().' '.$e->getLine().'('.$e->getMessage().')<div>'.$trace.'</div>');
		}		
	}	
	
	/**
	 * Строит дамп контейнера
	 */
	public function dump(){
		
		($this->get_error())? $error = 'true': $error = 'false';

		print '<div><span style="font-weight:900;">error: </span>'.$error.'</div>
				<div><span style="font-weight:900;">errorcode: </span>'.$this->get_errorcode().'</div>';
		
		if(!empty($this->data)){
				
			print '<div style="font-weight:900;">data:</div>
					<div style="padding:10px 0px 20px 20px;">';

			foreach ($this->data as $key => $value){

				print '<div style="padding:10px;"><b>'.$key.':</b> ';

				var_dump($value);
					
				print '</div>';
			}
				
			print '</div>';
		}

		exit();
	}	
	
	/**
	 * Возвращает записанные данные из контейнера по ключу $key
	 * @param $key
	 * @return mixed
	 */
	public function get_data($key){
		
		return $this->data[(string) $key];
	}	
	
	/**
	 * Возвращает статуc ошибки контейнера
	 * @return bolean
	 */
	public function get_error(){
		
		if(!is_bool($this->error)) die('Error must be boolean');
		return $this->error;
	}		
	
	/**
	 * Возвращает код ошибки контейнера
	 */
	public function get_errorcode(){
		
		return (string) $this->errorcode;
	}		
	
	/**
	 * Возвращает данные из дефолтного контейнера self
	 * @return mixed
	 */
	public function get_self(){
			
		return $this->get_data('self');
	}
	
	/**
	 * Записывает данные в контейнер по ключу $key
	 * @param string $key
	 * @param mixed $value
	 */
	public function set_data($key, $value){
		
		$this->data[(string) $key] = $value;
	}	
	 
	/**
	 * Устанавливает статус ошибки контейнера
	 * @param bolean $error
	 */
	public function set_error($error){
		
		if(!is_bool($error)) die('Error must be boolean');
		if(!$error) $this->unset_errorcode();
		$this->error = $error;
	}	
	
	/**
	 * Устанавливает код ошибки контейнера
	 * @param string $errorcode
	 */
	public function set_errorcode($errorcode){
		
		$this->errorcode = (string) $errorcode;
	}	
	
	/**
	 * Записывает данные в дефолтный контейнер self
	 */
	public function set_self($value){
			
		$this->set_data('self', $value);
	}	
	
	/**
	 * Записывает null в код ошибки контейнера
	 */
	public function unset_errorcode(){
	
		$this->set_errorcode(null);
	}	
	
	/**
	 * Записывает null в контейнер по ключу $key
	 */
	public function unset_data($key){
	
		$this->set_data($key, null);
	}	
	
	/**
	 * Записывает null в дефолтный контейнер self
	 */
	public function unset_self(){
	
		$this->unset_data('self');
	}
}
?>