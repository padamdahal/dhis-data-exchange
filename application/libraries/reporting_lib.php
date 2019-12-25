<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	// Library for reporting
	class Reporting_lib {
		
		public function __construct(){
            $this->CI =& get_instance();
        }
		
		public function getReportsJson(){
			$reports_json = file_get_contents($this->CI->config->item('base_url').$this->CI->config->item('reports_json_path'));
			return json_decode($reports_json);
		}
		
		public function getDataValue($reportName, $row, $colKey){
			//return json_decode(json_encode($this->finalData[$row]), True)[$colKey];
			return json_decode(json_encode($this->CI->finalData[$reportName][$row],True))->$colKey;
		}
		
		public function checkLogin(){	
			
			// debug
			$this->CI->session->set_userdata('LOGIN_VALID', 'TRUE');
			//$this->CI->session->unset_userdata('LOGIN_VALID');
		
			if($this->CI->config->item('method_check_login') == 'session'){
				if(!isset($_SESSION[$this->CI->config->item('_name_to_check_login')])){
					redirect($this->CI->config->item('login_redirect_url').current_url(), 'location');
				}
			}else{
				if(!isset($_COOKIE[$this->CI->config->item('_name_to_check_login')])){
					redirect($this->CI->config->item('login_redirect_url').current_url(), 'location');
				}
			}
		}
	}