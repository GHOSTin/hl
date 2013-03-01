<?php
class view_error{

	public static function get_error_message($message){
		return load_template('error.private_get_error_message', ['message' => $message]);
	}
}