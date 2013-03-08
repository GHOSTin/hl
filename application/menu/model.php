<?php
class model_menu{
	public static function get_hot_menu($component, $controller){
		$hot_menu[] = ['href' => $component, 'title' => $controller::name];
		if($_SESSION['hot_menu'][0])
			$hot_menu[] = $_SESSION['hot_menu'][0];
		if($_SESSION['hot_menu'][1])
			$hot_menu[] = $_SESSION['hot_menu'][1];
		$_SESSION['hot_menu'] = $hot_menu;
	}
}