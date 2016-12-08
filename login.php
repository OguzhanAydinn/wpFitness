<?php 
/*
 * page action çıkış yap giriş yap gibi olayların matematiksel tanımı olacak
 * 9= çıkış demek ör.
 */
class main extends model_controller{
	public function __construct($page_id, $page_action, $data) {
		parent::__construct($page_id, $page_action, $data);
		
		if($page_action==1){
			$error = $this->do_login($data);
			$type="";
			if($error==""){
				$response='İşlem Başarılı';
				$type="success";
			}else{
				$response = $error;
				$type= "error";
			}
			$this->create_json_response($response, $type);
		}else if($page_action==2){
			$error = $this->create_new_account($data);
			$response="";
			$type="";
			if($error==""){
				$response="İşlem Başarılı";
			}else{
				$response = $error;
				$type= "error";
			}
			$this->create_json_response($response, $type);
		}else if($page_action==9){
			$this->do_logout();
		}elseif(!empty($data)){
//			pre($data);
			if($this->check_login($data)){
				$this->redirect_page(1000);
			}else{
				$this->template->addVariable("error", 1);
			}
		}
		
	}
	public function __destruct() {
		parent::__destruct();
	}
	private function create_new_account($data){
		$error=array();
		$username=!empty($data['username'])?$data['username']:"";
		$password=$this->encrypt_password(!empty($data['password'])?$data['password']:"");
		$email=!empty($data['eposta'])?$data['eposta']:"";
		
		$sql=sprintf("select id,username,eposta from users  where  status=1", 
$this->db->Escape($username),$this->db->Escape($email));
		$r=$this->db->Execute($sql);
		if($r->RecordCount()>0){
			$s=$r->FetchNextObject();
			if($s->eposta == $email) {
				return $error ='E-posta Sistemimizde Daha Önce Kullanılmış !';
			}else if($s->username == $username){
				return $error = 'Kullanıcı Adı Kullanılmakta';
			}else {
			$sql=sprintf("insert into users set username='%s',password='%s',eposta='%s',status=1",
$this->db->Escape($username), 
$this->db->Escape($password),
$this->db->Escape($email));
			$r=$this->db->Execute($sql);
		return '';
		
			}
		}
	}
	public function do_login($data){
		$username=!empty($data['username'])?$data['username']:"";
		$password=$this->encrypt_password(!empty($data['password'])?$data['password']:"");
		$sql = sprintf("select * from users  where status =1 and username='%s' 
and password='%s'",$this->db->Escape($username),$this->db->Escape($password) );
		$r = $this->db->Execute($sql);
		$error = $c = array();
		if($r->RecordCount()>0){
			$error = $c = array();
			$s=$r->FetchNextObject();
			$_SESSION['id']=$s->id;
			$_SESSION['username']=$s->username;
			return $error='';
		}else{
			return $error = 'Kullanıcı Adı veya Şifre HATALI';
		}
		
	}
	
	private function do_logout(){
		$_SESSION['id']=0;
		$_SESSION['name']='';
		$this->redirect_page(1000);
	}
	/*
	 * kullanıcıyı verı tabından cek sonra bılgılerı array olarak gerı dondur 
	 * kul adı falan yazdırmak ıcın
	 * burdan cekılebılır
	 * **/
	
	
	private function check_login($data){
		if(!empty($data['username'])){
			if(!empty($data['password'])){
				if($this->check_password(
						$data['username'], 
						$this->encrypt_password($data['password'])
				)){
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * sifreyi kilitler
	 * 
	 * @param String $text
	 * @return String
	 */
	public function encrypt_password($text){
		return md5(base64_encode($text));
	}
	public function decipher_password($text){
		return md5(base64_encode($text));
	}
	
	private function check_password($username, $password){
		$sql=sprintf("select id, concat(name, ' ',surname) as name, date_last_login 
from users  where username='%s' and password='%s' and status=1", 
$this->db->Escape($username), $this->db->Escape($password));
		$r=$this->db->Execute($sql);
		if($r->RecordCount()>0){
			$s=$r->FetchNextObject();
			$_SESSION['admin_user_id']=$s->id;
			$_SESSION['admin_user_name']=$s->name;
			$_SESSION['admin_date_last_login']=$s->date_last_login;
			
			$this->update_last_login();
			return true;
		}
		return false;
	}
	private function update_last_login(){
		$sql=sprintf("update admin_users set date_last_login=%d where id=%d",
time(), $_SESSION['admin_user_id']);
	}
}