<?php
function get_component_css(){
	header('Content-type: text/css');
	$file = htmlspecialchars($_GET['css']);
	$file = ROOT.'/'.framework_configuration::application_folder.'/'.http_router::get_component_name().'/templates/component.css';
	if(file_exists($file))
		include($file);
}

function get_component_js(){
	$file = htmlspecialchars($_GET['js']);
	$file = ROOT.'/'.framework_configuration::application_folder.'/'.http_router::get_component_name().'/templates/component.js';
	if(file_exists($file))
		include($file);
}