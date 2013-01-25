<?php
class environment{
	/**
	 * Переменная хранит массив юнитов
	 */
	private static $units = array();
	/**
	 * Метод удаляет блок с именем $unitName
	 *
	 * @param $unitName string имя блока
	 */
	public static function clear($unitName){
		unset(self::$units[$unitName]);
	}
	/**
	 * Метод возращает содержимое блока $unitName
	 */
	public static function get($unitName){
		return self::$units[$unitName];
	}
	/**
	 * Метод записывает в блок $unitName содержимое $value
	 */
	public static function set($unitName, $value){
		self::$units[strval($unitName)] = $value;
	}
}