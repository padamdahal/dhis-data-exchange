<?php 

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Base_model extends CI_Model {
		
		/* function creates the sql query based on the array read from the file*/
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
		
		public function executeSql($finalSql){
			$this->load->database();
			$result = $this->db->query($finalSql);
			return $result;
		}
		
		public function prepareDataValueSet($dhisConfig, $data){
			foreach ($dhisConfig as $key=>$value){

			}
			
		}
	}