<?php 

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Reporting_model extends CI_Model {
		
		// Form sql query based on the array read from the file
		public function parseSqlFromArray($sqlArray, $params){
			$return = [];
			$query = '';
			$startWith = '';
			$endWith = '';
			foreach ($sqlArray as $line){
				$startWith = substr(trim($line), 0 ,2);
				$endWith = substr(trim($line), -1 ,1);
				
				if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
					continue;
				}
				
				foreach($params as $key => $value){
					$line = str_replace("{{".$key."}}", $value, $line);
				}
				
				$query = $query . $line;
			}
			
			if ($endWith == ';') {
				$return['error'] = false;
				$return['message'] = 'Success';
				$return['data'] = $query;
				return $return;	
			}else{
				$return['error'] = true;
				$return['message'] = 'End of SQL query not found.';
				$return['data'] = 'null';
				return $return;
			}
		}
		
		// Execute the sql and return the output
		public function executeSql($finalSql){
			$this->load->database();
			$result = $this->db->query($finalSql);
			return $result;
		}
		
		// Post the dhis json payload to IOL
		public function postData($json){
			$url = $this->config->item('iol_url');
			$username = $this->config->item('iol_username');
			$password = $this->config->item('iol_password');
		
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($json))                                                                       
			);
			
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			$result = curl_exec($ch);
			
			if(!curl_errno($ch)){
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			}

			curl_close ($ch);
			$data = json_decode($result, true);
			return $result;
		}
	
		public function getReportsJson(){
			$reports_json = file_get_contents($this->config->item('base_url').$this->config->item('reports_json_path'));
			return json_decode($reports_json);
		}
	}