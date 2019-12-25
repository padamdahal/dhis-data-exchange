<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
        $this->load->helper("url");
		$this->load->library('dhis');
		$this->load->library('session');
		
		$this->session->set_userdata('LOGIN_VALID', 'TRUE');
		//$this->session->unset_userdata('LOGIN_VALID');
		
		if($this->config->item('method_check_login') == 'session'){
			if(!isset($_SESSION[$this->config->item('_name_to_check_login')])){
				redirect($this->config->item('login_redirect_url').current_url(), 'location');
			}else{
				echo 'Your logged in.';
			}
		}else{
			if(!isset($_COOKIE[$this->config->item('_name_to_check_login')])){
				redirect($this->config->item('login_redirect_url').current_url(), 'location');
			}else{
				echo 'Your logged in.';
			}
		}
		
		$data['reports_json'] = $this->dhis->getReportsJson();
		$this->load->view('home',$data);
	}
}
