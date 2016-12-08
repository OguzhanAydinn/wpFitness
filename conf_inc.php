<?php
 
//uri
define("URL", "http://localhost/phpmyadmin");
define("SURL", "http://localhost/phpmyadmin/");
define("UPLOAD_URL", "http://localhost/incoming/");

// lokasyon
define("ROOTLOC", "C:\wamp\www\wpFitness\trunk");
define("LOC", ROOTLOC."vias/");
define("LIB", LOC."lib/");

// db ayarlari
define("DBTYPE", "mysql");
define("DBNAME", "dbfitness");
//define("DBHOST", "192.241.141.163");
define("DBHOST", "localhost");
define("DBUSER", "root");
//define("DBPASS", "");
define("DBPASS", "Oguzhan.18/*");


// upload ayarları
define("UPLOAD_AREA", ROOTLOC."incoming/");
define("MAX_FILE_SIZE", 10240000);

// temp location
define("TEMP_LOC", ROOTLOC."temp/");

// sabit degerler
define("MaxListSize", 50);

//resim URL yolu
define('IMAGES_URL', 'http://192.241.141.163/vias//images/'); 


@ini_set("display_errors", true);
@ini_set("error_reporting", E_ALL);

/**
 * debug modu
 */
define("DEBUG_MODE", true);
?>