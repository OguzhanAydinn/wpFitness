<?php
// sayfa utf8
//header('Content-Type: text/html; charset=utf-8');

// load conf file
require_once 'config.php';

// sayfa icinde hatalari goster
if(DEBUG){
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}else{
	ini_set('display_errors', 0);
	error_reporting(FALSE);
}

// session 
session_start();

// model-controller sinifini cagir
require_once MODEL.'model_controller.php';

// p = page_id
if(!empty($_REQUEST['p'])){
	$page_id = $_REQUEST['p'];
}
else{
	$page_id=1000;
}

// a = page_action
$page_action = empty($_REQUEST['a'])?0:$_REQUEST['a'];

// d = data
// ajaxdan post edilen post data
//if($_POST){
//	foreach ($_REQUEST as $key=>$value)
//$data = empty($_REQUEST['post_data'])?'':$_REQUEST['post_data'];
//}
//d olarak gelen datalar
$data = empty($_REQUEST['d'])?'':$_REQUEST['d'];

switch ($page_id){
	// anasayfa
	case 1:
		require MODEL.'login.php';
		$site = new main($page_id, $page_action, $data);
		$site->display_page();
		break;
	
	// anasayfa
	case 1000:
	default:
		require MODEL.'home.php';
		$site = new main($page_id, $page_action, $data);
		$site->display_page();
		break;
}
?>