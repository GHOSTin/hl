<?php
function get_component_css(){
	$file = htmlspecialchars($_GET['css']);
	$file = ROOT.'/'.framework_configuration::application_folder.'/'.http_router::get_component_name().'/views/component.css';
	if(file_exists($file)){
		header('Content-type: text/css');
		include($file);
	}	
}

function get_component_js(){
	$file = htmlspecialchars($_GET['js']);
	$file = ROOT.'/'.framework_configuration::application_folder.'/'.http_router::get_component_name().'/views/component.js';
	if(file_exists($file)) include($file);
}