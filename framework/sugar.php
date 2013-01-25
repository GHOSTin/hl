<?php
function build_sql_in_operator($array){
	if(!empty($array)){
		foreach ($array as $value)
			$string .= "'".$value."',";
		return substr($string,0,-1);
	}
	return '';
}

/**
 * $time
 */
function get_strong_getdate($time){
	(empty($time))?
		$time = getdate():
		$time = getdate((int) $time);
	if($time['minutes'] < 10) $time['minutes']	= '0'.$time['minutes'];
	if($time['mday'] < 10) $time['mday']		= '0'.$time['mday'];
	if($time['mon'] < 10) $time['mon']			= '0'.$time['mon'];
	return $time;
}

function dump($var){
	var_dump($var);
	exit();
}
?>