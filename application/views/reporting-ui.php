<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title . ' : HOME';?></title>
		<script src="resources/assets/jquery.min.js"></script>
		<link rel="stylesheet" href="resources/assets/jquery-ui.css">
		<script src="resources/assets/jquery-ui.js"></script>
		<script src="resources/assets/underscore-min.js"></script>
		<style type="text/css">

		::selection { background-color: #E13300; color: white; }
		::-moz-selection { background-color: #E13300; color: white; }

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#body {
			margin: 0 15px 0 15px;
		}

		p.footer {
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}

		#container {
			margin: 10px;
			border: 1px solid #D0D0D0;
			box-shadow: 0 0 8px #D0D0D0;
		}
		.send, .preview{
			background:green;
			padding:5px;
			border:green;
			text-decoration:none;
			color:#fff;
			margin:10px 15px;
		}
		table{
			border:1px solid #ccc;
			padding: 5px 0;
			width:100%;
		}
		table tbody tr td{
			margin:20px;
		}
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				var serverDate = new Date('<?php echo date("Y-m-d", time());?>');
				var facilities;
				$.ajax({
					type: 'GET',
					url: 'resources/assets/healthfacilities.json',
					contentType: "application/json",
					success: function (output) {
						facilities = output;
						console.log(facilities);
						var provinces = _.keys(_.countBy(output, function(output) { return output.province; }));
						$.each(provinces, function(i, province) {
							$('#province').append('<option>'+province+'</option>');
						});
					},
					error: function(output){
						console.log('error');
					}
				});
					
				var previewDialog = $( "#preview" ).dialog({
					autoOpen: false,
					height: 600,
					width: 850,
					modal: true
				});
				
				$(document).on('change', '#province', function(e){
					var selectedProvince = $(this).val();
					console.log(selectedProvince);
					var temp = facilities;
					var districts = temp.filter(function(row){if(row["province"].replace(/\s+/g,' ').trim() == selectedProvince) return true;});
					
					districts = _.keys(_.countBy(districts, function(districts) { return districts.district; }));
					var options
					$.each(districts, function(i, district) {
						
						options += '<option>'+district+'</option>';
					});
					
					$('#districts').html(options);
				});
				
				$(document).on('change', '#districts', function(e){
					var selectedDistrict = $(this).val();
					console.log(selectedDistrict);
					var temp = facilities;
					var muns = temp.filter(function(row){if(row["district"].replace(/\s+/g,' ').trim() == selectedDistrict) return true;});
					
					muns = _.keys(_.countBy(muns, function(muns) { return muns.municipality; }));
					console.log(muns);
					var options
					$.each(muns, function(i, mun) {
						
						options += '<option>'+mun+'</option>';
					});
					
					$('#mun').html(options);
				});
				
				$(document).on('click', '.preview', function (e) {
					previewDialog.dialog( "open" );
					e.preventDefault();
					var $data = $(this).attr('href');
					var url = $data;
					$.ajax({
						type: 'GET',
						url: url,
						contentType: "application/json",
						success: function (output) {
							$('#preview').html(output);
						},
						error: function(output){
							$('#preview').html("Failed to load the preview.");
						}
					});
				});
				
				$(document).on('click', '.send', function (e) {
					previewDialog.dialog( "open" );
					e.preventDefault();

					var url = $(this).attr('href');
					$.ajax({
						type: 'GET',
						url: url,
						success: function (output) {
							$('#preview').html(output);
						},
						error: function(output){
							$('#preview').html("Failed to load the preview.").modal('show');
						}
					});
				});
			})
		</script>
	</head>
	<body>

		<div id="container">
			<h1><?php echo $page_title;?></h1>

			<div id="body">
				<p>
					<select id='province'>
						<option></option>
					</select>
					
					<select id='districts'>
						<option></option>
					</select>
					Select period:
					<select id="month">
						<option value="01">Baisakh</option>
						<option value="02">Jestha</option>
						<option value="03">Ashad</option>
						<option value="04">Shrawan</option>
					</select>
					<select id='year'>
						<option value="2076">2076</option>
						<option value="2075">2075</option>
					</select>
				</p>
				<p>
					<table>
					
					<?php 
						foreach ($reports_json as $key=>$value){
							$row = '<tr style="padding:10px">';
							$row .= '<td style="width:70%">';
							$row .= $value->name;
							$row .= '</td>';
							
							$row .=  '<td>';
							$row .= '<a class="preview" href="index.php/reporting/preview/'.$key.'/?period=207602&orgUnit=WaY0NFhl8Y3&startDate=2018-05-01&endDate=2018-12-30">Preview</a>';
							$row .= '</td>';
							
							$row .=  '<td>';
							$row .= '<a class="send" href="index.php/reporting/send/'.$key.'/?period=207602&orgUnit=WaY0NFhl8Y3&startDate=2018-05-01&endDate=2018-12-30">Send to HMIS</a>';
							$row .= '</td>';
							$row .= '</tr>';
							echo $row;
						}
					?>
					</table>
				</p>
				<p id="preview" title="Report Preview">Loading...</p>
			</div>
		</div>
	</body>
</html>