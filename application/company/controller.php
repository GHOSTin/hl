<?php
class controller_company{

	public static function private_show_default_page(){
		return ['companies' => (new model_company)->get_companies()];
	}
}