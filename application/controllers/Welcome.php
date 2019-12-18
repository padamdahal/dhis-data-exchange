<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index(){
		$this->load->helper("url");
		$reports_json = file_get_contents(base_url().$this->config->item('reports_json_path'));
		$data['reports_json']  = json_decode($reports_json);
		$this->load->view('welcome_message',$data);
	}
}
