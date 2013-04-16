<?php
class model_import{


	public static function analize_import_numbers($fileArray){

		$xml = simplexml_load_file($fileArray['tmp_name']);
		if($xml === false)
			throw new exception('Ошибка в xml файле.');
		$houseNode = $xml->attributes();
		$house = new data_house();
		$house->number = (string) $houseNode->number;
		$house->street_name = (string) $houseNode->street;
		$house->city_name = (string) $houseNode->city;
		return [ 'file' => $fileArray,
				'house' => $house];
	}
}