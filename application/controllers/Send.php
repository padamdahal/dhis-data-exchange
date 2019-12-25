<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send extends CI_Controller {

	public $report;
	public $finalData;
	
	public function __construct(){
        parent::__construct();
		$this->load->model("base_model");
        $this->load->helper("url");
		$this->load->library("dhis");
		$this->report = $this->base_model->getReportsJson();
    }
	
	function _remap($report_index) {
		$this->index($report_index);
	}
	
	public function index($report_index = false){
		$result;
		$parameters = $_GET;
		
		foreach ($this->report[$report_index]->reports as $reportName => $reportConfig){
			$sqlPath = $reportConfig->sqlPath;
			
			if(!file_exists($sqlPath)){
				exit('The sql file not found, can not continue.');
			}
			
			// Read sql file into array and replace parameters value to get the executable query
			$sqlArray = file($sqlPath);
			$sql = $this->base_model->parseSqlFromArray($sqlArray,$parameters);
			$result[$reportName] = $this->base_model->executeSql($sql['data'])->result();
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
		
		$status = $this->base_model->postData(json_encode($dhisPayload));

		if($status == 200){
			echo 'Success';
		}else{
			echo 'Failed: '.$status;
		};
	}
}
