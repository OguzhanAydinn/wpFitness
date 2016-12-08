<?php
class main extends model_controller{
	public function __construct($page_id, $page_action, $data) {
		parent::__construct($page_id, $page_action, $data);
	}
	public function __destruct() {
		parent::__destruct();
	}
	
	private function get_products(){
		$result=array();
		$sql=sprintf("select * from products");
		$r=$this->db->Execute($sql);
		if($r->RecordCount()>0){
			while($s=$r->FetchNextObject()){
				$result[]=$s;
			}
		}
		return $result;
	}
}