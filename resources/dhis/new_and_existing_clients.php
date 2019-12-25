<?php
	// Variable name $dhisPayload is used
	// Data values for new and existing_clients report
	$dhisPayload['period'] = $parameters['period'];
	$dhisPayload['orgUnit'] = $parameters['orgUnit'];
	$dhisPayload['dataSetComplete'] = 'true';
	
	////// Data Elements /////////////////////////////////////////////////////////////
	// $variable_name['dataElement'] = 'data element id';
	// $variable_name['categoryOptionCombo'] = "category option combo id";
	// $variable_name['value'] = $this->reporting_lib->getDataValue('reportName',rowIndex,"valueKey");
	//////////////////////////////////////////////////////////////////////////////////
	
	// Data Element 1 : New clients female
	$val_new_clients_female['dataElement'] = "HscG3R78Jzc";
	$val_new_clients_female['categoryOptionCombo'] = "I1gylzOskBs";
	$val_new_clients_female['value'] = $this->reporting_lib->getDataValue('one', 0, "New Clients Female");
	
	// Data Element 2 : New clients male
	$val_new_clients_male['dataElement'] = "HscG3R78Jzc";
	$val_new_clients_male['categoryOptionCombo'] = "TTNFd2X49S6";
	$val_new_clients_male['value'] = $this->reporting_lib->getDataValue('two', 0, "New Clients Female");
	
	// Data Element 3 : IPD duration
	$val_total_ipd_days['dataElement'] = "HscG3R78Jzc";
	$val_total_ipd_days['categoryOptionCombo'] = "TTNFd2X49S6";
	$val_total_ipd_days['value'] = $this->reporting_lib->getDataValue('three', 0, "IPD Duration");
	
	
	// Add All data elements to dhisPayload
	$dhisPayload['dataValues'] = [
		$val_new_clients_female,
		$val_new_clients_male,
		$val_total_ipd_days
	];