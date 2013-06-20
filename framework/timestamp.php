<?php
class timestamp{

	private static $timestamps = array();

	static public function create_timestamp(){
		self::$timestamps[] = microtime(true);
	}

	static public function get_timestamp_counter(){
		return count(self::$timestamps);
	} 
	
	static public function print_html_report(){
		if(empty(self::$timestamps))
			print('No timestamps.');
		else{
			$pr = '<div>';
			foreach(self::$timestamps as $timestamp){
				if(isset($previousTimestamp)) $delta = ' +'.round($timestamp - $previousTimestamp, 4);
				$pr .= '<div>'.$timestamp.$delta.'</div>';
				$previousTimestamp = $timestamp;
			}
			$pr .= '<div>'.self::get_timestamp_counter().' timestamps. Runnig time: '.round($timestamp - self::$timestamps[0], 4).' </div></div>';
			print($pr);
		}
	}
}