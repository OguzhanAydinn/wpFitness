<?php
require_once LIBS.'dbapi.php';
include_once(LIBS.'template_manager.php');

class model_controller{
	protected $db;
	public $page_id;
	public $page_action;
	public $data;
	public $lang;
	public $lang_id;
	public $canonical;
	
	protected $template;
	
	public function __construct($page_id, $page_action, $data) {
		$this->page_id=$page_id;
		$this->page_action=$page_action;
		$this->data=$data;
		
		// db baglan
		$this->db = new dbapi();
		if(!$this->db->Connect(DBNAME, DBHOST, DBUSER, DBPASS)){
			/**
			 * @todo 
			 */
			die("oldum bittim");
		}
		// veritabanina unicode kullanacagimizi soyluyoruz
		$this->db->Execute("SET NAMES 'utf8'");
		
		// dil ile ilgili ayarlari cekiyoruz
	//	$this->get_language();
		
		
		// sayfayi yukle
		$this->load_page();
	}
	public function __destruct() {
		// db kopar
		if(!empty($this->db)){
			$this->db->Close();
		}
	}	
	
	public function display_page(){
		// sayfa dil dosyalarini yukle
//		$this->assign_lang_vars();
				
		// sayfayi goster
		$this->template->displayPage();
		die();
	}
	
	protected function load_page(){
		$this->template = new template_manager($this->page_id);
		$this->template->addVariable("URL", URL);
		$this->template->addVariable("USER_ID", empty($_SESSION['id'])?0:$_SESSION['id']);
		$this->template->addVariable("NAME", empty($_SESSION['username'])?'':$_SESSION['username']);
	}
	
	public function create_json_response($response="",$type=""){
		ini_set('display_errors', 0);
		error_reporting(FALSE);
		$this->template = new template_manager("jsonResponse");
		$this->template->addVariable("response_txt",$response);
		$this->template->addVariable("error_txt",$type);
	}
	
	protected function redirect_page($page_id){
		header("Location: index.php?p=".$page_id);
	}
}