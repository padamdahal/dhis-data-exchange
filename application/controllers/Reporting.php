<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporting extends CI_Controller {
	public $report;
	public $finalData;
	
	public function __construct(){
        parent::__construct();
		
		$this->load->helper("url");
		$this->load->library("session");
		
		$this->load->library("reporting_lib");
		$this->reporting_lib->checkLogin();
		
		$this->load->model("reporting_model");
		$this->report = $this->reporting_model->getReportsJson();
    }
		
	public function index(){	
		$data['reports_json'] = $this->reporting_lib->getReportsJson();
		$data['page_title'] = $this->config->item('header_text');
		$this->load->view('reporting-ui', $data);
	}
	
	public function preview($report_index = null){
		$result;
		$parameters = $_GET;
		
		foreach ($this->report[$report_index]->reports as $reportName => $reportConfig){
			$sqlPath = $reportConfig->sqlPath;
			
			if(!file_exists($sqlPath)){
				exit('The sql file not found, can not continue.');
			}
			
			// Read sql file into array and replace parameters value to get the executable query
			$sqlArray = file($sqlPath);
			$sql = $this->reporting_model->parseSqlFromArray($sqlArray,$parameters);
			$result[$reportName] = $this->reporting_model->executeSql($sql['data'])->result();
		}
		
		echo json_encode($result);
	}
	
	public function send($report_index = null){
		$result;
		$parameters = $_GET;
		
		foreach ($this->report[$report_index]->reports as $reportName => $reportConfig){
			$sqlPath = $reportConfig->sqlPath;
			
			if(!file_exists($sqlPath)){
				exit('The sql file not found, can not continue.');
			}
			
			// Read sql file into array and replace parameters value to get the executable query
			$sqlArray = file($sqlPath);
			$sql = $this->reporting_model->parseSqlFromArray($sqlArray,$parameters);
			$result[$reportName] = $this->reporting_model->executeSql($sql['data'])->result();
		}
		
		$this->finalData = $result;
				
		// Generate datavalue
		$dhisConfigFile = $this->report[$report_index]->dhisConfig;
		
		if(!file_exists($sqlPath)){
			exit('The sql file not found, can not continue.');
		}
		
		include_once($dhisConfigFile);
		
		// For debug
		print_r(json_encode($dhisPayload));
		
		$status = $this->reporting_model->postData(json_encode($dhisPayload));

		if($status == 200){
			echo 'Success';
		}else{
			echo 'Failed: '.$status;
		};
		
	}

	public function serverdate(){
		echo date("Y-m-d", time());
	}
}
