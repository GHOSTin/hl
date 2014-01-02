<?php

class application_configuration{

	const templateName = 'default';
	// database option
	const database_name 		= 'mshc2_test';
	const database_host 		= 'localhost';
	const database_user 		= 'mshc2_test';
	const database_password 	= 'mshc2_test';
	// auth option
	const authSalt 			= 'GbpltwGjkysqFYtGfhjkm';	
	//chat
	const chat_enable = false;
	const chat_host = 'mshc2.local';
	const chat_port = 3000;
	// twig
	const twig_reload_template = true;
	const twig_cache = '/cache';
	// php
	const php_timezone = 'Asia/Yekaterinburg';
}