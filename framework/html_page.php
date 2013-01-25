<?php
class html_page extends site_page{

	/**
	 * Метод удаляет заголовок страницы
	 * @return void
	 */
	public static function unset_title(){
		parent::unset_unit('title');
	}
	
	/**
	 * Метод удаляет html-блок
	 * @return void
	 */
	public static function unset_html_block($blockName){
		parent::unset_unit('html_'.$blockName);
	}	
	
	/**
	 * Метод возвращает html-блок
	 * @param $blockName
	 * @return string
	 */
	public static function get_html_block($blockName){
		return (string) parent::get_unit('html_'.$blockName);
	}
		
	/**
	 * Метод возвращает линки страницы
	 * @return string
	 */
	public static function get_script_files(){
		return (string) parent::get_unit('script_files');
	}

	/**
	 * Метод возвращает заголовок страницы
	 * @return string
	 */
	public static function get_title(){
		return (string) parent::get_unit('title');
	}
	
	/**
	 * Метод устанавливает html-блоки страницы
	 * @param $blockName
	 */
	public static function set_html_block($blockName, $html){
		parent::set_unit('html_'.$blockName, $html);
	}	

	/**
	 * Метод устанавливает линки страницы
	 */
	public static function set_script_file($path){
		parent::set_unit('script_files', $path);
	}

	/**
	 * Метод устанавливает заголовок страницы
	 */
	public static function set_title($title){
		parent::set_unit('title', $title);
	}
}

abstract class site_page{

	/**
	 * Переменная хранит массив юнитов
	 */
	private static $units = array();

	/**
	 * Метод удаляет блок с именем $unitName
	 *
	 * @param $unitName string имя блока
	 */
	protected static function unset_unit($unitName){
		unset(self::$units[$unitName]);
	}

	/**
	 * Метод возращает содержимое блока $unitName
	 */
	protected static function get_unit($unitName){
		return self::$units[$unitName];
	}

	/**
	 * Метод аписывает в блок $unitName содержимое $value
	 */
	protected static function set_unit($unitName, $value){
		self::$units[strval($unitName)] = (string) $value;
	}
}
?>