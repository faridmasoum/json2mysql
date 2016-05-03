<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');  
// require
require_once '_includes/class.fetch.php';
require_once 'db/class.mysql.php';
 
$CONFIG = parse_ini_file("./_config/config.ini", true);
	
	// mysql object
	$mysqlOb = new Mysql($CONFIG); 
	
	// create table and fields
	$mysqlOb->install();

	// fetch object
	$fetchObj = new FetchDataApi;
	$fetchObj->fetchData();
	$itemsArray = $fetchObj->itemsArray; // all items array
	 
	// if the array exists 
	if(count($itemsArray) > 0)
	 $mysqlOb->insertToDatabase($itemsArray);
	else
		echo "No Item";
	 
	
 