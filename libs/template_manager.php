<?php
class template_manager{
	public $page;
	private $template;
	private $variables;
	
	public function __construct($page_id){
		$this->page = $page_id;
	}
	
	/**
	 * reads the contents of a page
	 * puts the included files inside
	 * evaluates the php codes
	 * replaces the variables
	 * 
	 * @param string $page (page name in pagename.html format)
	 */ 

	public function loadPage($page){
		$this->template = $this->readFile(VIEW.$page.'.html');
		
		## include
		$this->template = preg_replace_callback('/\{\{include file="(.+)"\}\}/', array($this, 'includePage'), $this->template);
		## vars
		$this->template = preg_replace_callback('/\{\{\$([a-zA-Z_>\-\[\]\'\"0-9]+)\}\}/', array($this,'getVariable'), $this->template);
		## php codu
		$this->template=$this->php_cevir($this->template);
	
	}
	/**
	 * verilen sayfa içerisindeki {{php}} ... {{/php}} tagları arasındaki alanları 
	 * php de compile eder. 
	 * sonra sayfanın değişmiş halini geri yollar
	 *
	 * @param string $sayfa
	 * @return string $sayfa 
	 * @author guner
	 */
	private function php_cevir($sayfa){
		$oteleme=0;
		$temp=$sayfa;
		while(1){
			$php_bas_bul = strpos($sayfa, '{{php}}',$oteleme);
			$php_son_bul = strpos($sayfa, '{{/php}}',$oteleme);
			if ($php_bas_bul===false) {
				break;
			}else{
				$metin=substr($sayfa,($php_bas_bul+7),($php_son_bul-$php_bas_bul-7));
				$oteleme=(int) ($php_son_bul+7);

				$degisecek=$this->evalPhp($metin);

				$temp = str_replace($metin, $degisecek, $temp );

			}
		}
		$temp = str_replace('{{php}}', '', $temp );
		$temp = str_replace('{{/php}}', '', $temp );
		return $temp;
	}
	
	public function displayPage($send_var=0){
		$this->loadPage($this->page);
		if ($send_var){
			return $this->template;
		}else{
			echo $this->template;
			return null;
		}
	}
	
	/**
	 * adds a variable to the class property variables
	 *
	 * @param string $var
	 * @param string $value
	 */
	public function addVariable($var, $value){
		$this->variables[$var]=$value;
	}
	
	/**
	 * reads a file and returns contents 
	 * if the file does not exists returns empty string
	 *
	 * @param string $file
	 * @return string
	 */
	private function readFile($filename){
		if (file_exists($filename)){
			$handle = fopen($filename, "r");
			$contents = fread($handle, filesize($filename));
			fclose($handle);
			return $contents;
		}else{
			return '';
		}
	}
	
	/**
	 * evaluates the php code 
	 * also replaces the variables
	 *
	 * @param unknown_type $php
	 * @return unknown
	 */
	private function evalPhp($php){
		//$php = $php[1];
		// icindeki degiskenleri duzenle
		$php = preg_replace_callback('/\$([a-zA-Z_]+)/', array($this,'getVariableLiteral'), $php);
		//pred($php);
		ob_start();
		eval($php);
		$php=ob_get_contents();
		ob_end_clean();	
		return $php;
	}
	
	/**
	 * reads the variables in template end replace them
	 * with variables defined bu setVariable
	 *
	 * @param str $var
	 * @return str
	 */
	private function getVariableLiteral($var){
		$var = $var[1];
		if(key_exists($var, $this->variables)){
			return '$this->variables["'.$var.'"]';
		}else{
			return '$'.$var;
		}
	}
	
	/**
	 * reads the variables in template end replace them
	 * with variables defined bu setVariable
	 *
	 * @param str $var
	 * @return str
	 */
	private function getVariable($var){
		$var = $var[1];
		$type=0;
		// match object aa_sdf3->Asdf_asdf24
		if (preg_match('/([a-zA-Z_0-9]+)->([a-zA-Z_0-9]+)/', $var, $regs)) {
			$var = $regs[1];
			$var2 = $regs[2];
			$type=1;
		// match array  asd_df2['asdf_asdf3']  asd_df2["asdf_asdf3"]
		}elseif (preg_match('/([a-zA-Z_0-9]+)\[[\'|"]([a-zA-Z_0-9]+)[\'|"]\]/', $var, $regs)) {
			$var = $regs[1];
			$var2 = $regs[2];
			$type=2;
		}
		if(key_exists($var, $this->variables)){
			if($type==1)
				return $this->variables[$var]->$var2;
			elseif($type==2)
				return $this->variables[$var][$var2];
			else 
				return $this->variables[$var];
		}else{
			return '';
		}
	}
	
	/**
	 * include function
	 *
	 * @param string $page
	 * @return string
	 */
	private function includePage($page){
		$page = $this->readFile(VIEW.$page[1]);
		return $page;
	}
}
?>