<?php 
$pr = '<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
	<link rel="stylesheet" href="/templates/default/css/libs.css" >
    <link rel="stylesheet" href="/templates/default/css/default.css" >
	<link rel="stylesheet" href="/?css=component.css&p='.http_router::get_component_name().'" >
</head>
<body data-spy="scroll">';
$user = environment::get('user');
$pr .= (empty($user))?
		'<div class="container-fluid"><section class="main">'.html_page::get_html_block('component').'</section></div>':
		html_page::get_html_block('menu').'<div class="container-fluid"><section class="main component">'.html_page::get_html_block('component').'</section></div>';			
$pr .= '<script src="/templates/default/js/libs.js"></script>
	<script src="/?js=component.js&p='.http_router::get_component_name().'"></script>
    <script src="/templates/default/js/jcanvas.min.js"></script>
    <script src="/templates/default/js/ajaxupload.js"></script>
    <script src="/templates/default/js/default.js"></script>
    </body>
</html>';
return $pr;