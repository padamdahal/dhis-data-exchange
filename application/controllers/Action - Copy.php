<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action extends CI_Controller {
	public $report;
	public $finalData;
	
	public function __construct(){
        parent::__construct();
		
        $this->load->helper("url");
		$reports_json = file_get_contents('http://localhost:8008/dhis-dx/'.$this->config->item('reports_json_path'));
		$this->report = json_decode($reports_json);
    }
		
	public function index(){
		exit("Sorry! but system is unable to perfrom any action.");
	}
	
	public function getDataValue($row, $colKey){
		return json_decode(json_encode($this->finalData[$row]), True)[$colKey];
	}
	
	public function preview($report_index = null){
		//exit(print_r($this->report[$report_index]->reports));
		$result;
		$this->load->model("base_model");
		$parameters = $_GET;
		
		foreach ($this->report[$report_index]->reports as $reportName => $reportConfig){
			$sqlPath = $reportConfig->sqlPath;
			
			if(!file_exists($sqlPath)){
				exit('The sql file not found, can not continue');
			}
			
			if(!file_exists($sqlPath)){
				exit('The sql file not found, can not continue');
			}
			
			$configPath = $reportConfig->dhisConfig;
			
			echo $configPath;
		}
		exit('end');
		$sqlPath = $this->report[$report_index]->config->sqlPath;
		
		
		
		
		
		
				
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
		
		// print_r($parameters);
		echo json_encode($result['data']->result());
		
		return json_encode($result['data']->result());
	}
	
	public function send($report_index = null){
		$this->load->model("base_model");
		
		$sqlPath = $this->report[$report_index]->config->sqlPath;
		
		if(!file_exists($sqlPath)){
			exit('The resource does not exist');
		}
		
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
		$this->finalData = $result['data']->result();
				
		// Generate datavalue
		$dhisConfigFile = $this->report[$report_index]->config->dhisConfig;
		include($dhisConfigFile);
		
		// For debug
		print_r(json_encode($dhisPayload));
		
		$status = $this->base_model->postData(json_encode($dhisPayload));

		if($status == 200){
			echo 'Success';
		}else{
			echo 'Failed: '.$status;
		};
		
	}

}
