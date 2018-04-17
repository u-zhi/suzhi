<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

	
	/*
 * Excel library for Code Igniter applications Author: Derek Allard, Dark Horse
 * Consulting, www.darkhorse.to, April 2006
 */
function array_to_excel($array_name, $array, $filename = 'exceloutput') {
	//$filename = iconv ( "UTF-8", "gbk", $filename );
	$headers = ''; // just creating the var for field headers to append to below
	$data = ''; // just creating the var for field data to append to below
	
	$obj = & get_instance ();
	// $fields = $array->field_data();
	if (count ( $array ) == 0) {
		echo '<p>The table appears to have no data.</p>';
	} else {
		foreach ( $array_name as $field ) {
			//$headers .= iconv ( "utf-8", "GBK//TRANSLIT", $field ) . "\t";
			$headers .= $field . "\t";
		}
		foreach ( $array as $row ) {
			$line = '';
			foreach ( $row as $value ) {
				if ((! isset ( $value )) or ($value == "")) {
					$value = "\t";
				} else {
					$value = str_replace ( '"', '""', $value );
					$value = '"' . $value . '"' . "\t";
				}
				$line .= $value;
			}
			$data .= trim ( $line ) . "\n";
		}
		
		//$data = str_replace ( "\r", "", iconv ( "utf-8", "GBK//TRANSLIT", $data ) );
		$data = str_replace ( "\r", "",  $data  );
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/download" );
		header ( "Content-type: application/x-msdownload" );
		header ( "Content-type:charset=utf-8" );
		header ( "Content-Disposition: attachment; filename=" . $filename . ".xls" );
		echo "$headers\n$data";
	}
}  




// 生成CSV格式
function array_to_csv($array_name, $array, $filename = 'exceloutput') {
	$filename = iconv ( "UTF-8", "gbk", $filename );
	$headers = ''; // just creating the var for field headers to append to below
	$data = ''; // just creating the var for field data to append to below
	$obj = & get_instance ();
	// $fields = $array->field_data();
	if (count ( $array ) == 0) {
		echo '<p>The table appears to have no data.</p>';
	} else {
		foreach ( $array_name as $field ) {
			$headers .= iconv ( "utf-8", "GBK//TRANSLIT", $field ) . ",";
		}
		$headers = substr($headers, 0 , strlen($headers) -1);
		foreach ( $array as $row ) {
			$line = '';
			foreach ( $row as $value ) {
				if ((! isset ( $value )) or ($value == "")) {
					$value = ",";
				} else {
					$value = str_replace ( '"', '""', $value );
					$value = '"' . $value . '"' . ",";
				}
				$line .= $value;
			}
			$line = str_replace ( '"', '', $line );
			//$line = substr($line, 0 , strlen($line)-1);
			$data .= trim ( $line ) . "\r\n";
		}
		$data =  iconv ( "utf-8", "GBK//TRANSLIT", $data ) ;
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/download" );
		header ( "Content-type: application/x-msdownload" );
		header ( "Content-type:charset=utf-8" );
		header ( "Content-Disposition: attachment; filename=$filename.csv" );
		echo "$headers\r\n$data";
	}
}


//用exportdata组件到处excel表
function export_data_excel( $head_array, $tabledata, $filename= 'exceloutput' ){
	require_once 'export_data_helper.php';
	
	$filename = iconv ( "UTF-8", "gbk", $filename );

	$exporter = new ExportDataExcel('browser', $filename.".xls");
	$exporter->initialize();
	$exporter->addRow($head_array);
	foreach($tabledata as $row) {
		$exporter->addRow( $row );
	}	
	$exporter->finalize(); 
	exit();
	
	
	$filename = iconv ( "UTF-8", "gbk", $filename );
	$excel = new ExportDataExcel('browser');
	$excel->filename = $filename;
	$excel->initialize();
	$excel->addRow( $head_array );
	foreach($tabledata as $row) {
		$excel->addRow( $row );
	}
	$excel->finalize();
	//echo json_encode($table);
}