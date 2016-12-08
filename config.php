<?php
//  DB Connection settings
define("DBTYPE", "mysql");
define("DBHOST",'localhost');
define("DBUSER",'root');
define("DBPASS",'Oguzhan.18/*');
define("DBNAME",'dbfitness');

// Debug const
define("DEBUG", true);

// URL
define("URL", "http://localhost/wpfitness/trunk/");

// File locations
define("BASE", "/wamp/www/wpFitness/trunk/");
define("LIBS", BASE.'libs/');
define("MODEL", BASE.'model_controller/');
define("VIEW", BASE.'views/');

function pre($str){
	echo "\n<pre>===================================================================================\n";
	print_r ($str);
	echo "\n===================================================================================</pre>\n";
}

function pred($str){
	pre($str);
	die();
}