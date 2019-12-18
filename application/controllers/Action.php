<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action extends CI_Controller {

	public function index(){
		exit("Proper action required");
	}
	
	public function preview($report_index = null){
		
		echo "Loading preview".$report_index;
	}
	
	public function send($report_index = null){
		$this->load->helper("url");
		$reports_json = file_get_contents(base_url().$this->config->item('reports_json_path'));
		
		$data['reports_json']  = json_decode($reports_json);
		$sqlPath = $data['reports_json'][$report_index]->config->sqlPath;
		
		if(!file_exists($sqlPath)){
			exit('The resource does not exist');
		}
		
		$this->load->model("base_model");
		
		$parameters = $_GET;
				
		$sqlArray = file($sqlPath);
		$sql = $this->base_model->parseSqlFromArray($sqlArray,$parameters);
		
		if(!$sql['error']){
			$result['error'] = false;
			$result['message'] = 'SUCCESS';
			$result['data'] = $this->base_model->executeSql($sql['data']);
		}else{
			$result['error'] = true;
			$result['message'] = $sql['message'];
			$result['data'] = 'null';
		}
		//echo json_encode($result['data']->result());
		//print_r($result['data']->result());
		
		// Generate datavalue
		
		$dhisConfig = file_get_contents(base_url().$data['reports_json'][$report_index]->config->dhisConfig);
		//print_r($config);
		
		$this->base_model->prepareDataValueSet($dhisConfig, json_encode($result['data']->result()));
	}
	
	
}
