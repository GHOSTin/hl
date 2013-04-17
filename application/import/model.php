<?php
class model_import{


	public static function analize_import_numbers($fileArray){
		try{
			if(!file_exists($fileArray['tmp_name']))
				throw new e_model('Файла для обработки не существует.');
			$xml = simplexml_load_file($fileArray['tmp_name']);
			if($xml === false)
				throw new e_model('Ошибка в xml файле.');
			if(count($xml->flat) < 1)
				throw new e_model('Нечего импортировать.');
			$house_node = $xml->attributes();
			$house = new data_house();
			$house->number = (string) $house_node->number;
			$house->street_name = (string) $house_node->street;
			$house->city_name = (string) $house_node->city;
			foreach($xml->flat as $flat_node)
				if(count($flat_node->number) > 0)
					foreach($flat_node as $number_node){
						$number_attr = $number_node->attributes();
						$number = new data_number();
						$number->fio = $number_attr->fio;
						$numbers[] = $number;
					}
			return ['file' => $fileArray,
					'house' => $house,
					'numbers' => $numbers ];
		}catch(exception $e){
			if($e instanceof e_model)
				return ['error' => $e->getMessage()];
			else
				return ['error' => 'Неизвестная ошибка.'];
		}
	}
}