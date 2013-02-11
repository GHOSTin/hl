<?php 
class controller_profile{

	public static function private_show_default_page(){
		return view_profile::private_show_default_page($_SESSION);
	}
}