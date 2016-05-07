<html>
<head>
<?php 
require_once('_includes/installation.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$sampleData = array(
                'API' => array(
                    'url' => (isset($_POST['api_url']) && strlen(trim($_POST['api_url'])) > 0 )?trim($_POST['api_url']):"http://api.rmdatalink.com/api/product/config",
					'token' => (isset($_POST['api_token']))?trim($_POST['api_token']):"",
					'limit' => (isset($_POST['api_limit']) && $_POST['api_limit'] <= 300 && strlen(trim($_POST['api_limit'])) > 0)?trim($_POST['api_limit']):300,
					'skip' => 0,
                ),
                'MYSQL' => array(
                    'dbname' => (isset($_POST['db-name']))?trim($_POST['db-name']):"",
                    'username' => (isset($_POST['db-user']))?trim($_POST['db-user']):"",
                    'password' => (isset($_POST['db-password']))?trim($_POST['db-password']):"",
                    'host' => (isset($_POST['db-host']))?trim($_POST['db-host']):"",
                ));
	writeIniFile($sampleData, './_config/config.ini', true);
	writeIniFile([], './_config/install', true); //installed
  echo ' <META http-equiv="refresh" content="0;URL=complete.php">';
}
/*
if( checkIsInstall())  {
	 
}
*/
 ?>
<title>
Installation
 </title>
 
 <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<style type="text/css">
.form-style-10{
    width:450px;
    padding:30px;
    margin:40px auto;
    background: #FFF;
    border-radius: 10px;
    -webkit-border-radius:10px;
    -moz-border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
    -moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
    -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
}
.form-style-10 .inner-wrap{
    padding: 30px;
    background: #F8F8F8;
    border-radius: 6px;
    margin-bottom: 15px;
}
.form-style-10 h1{
    background: #2A88AD;
    padding: 20px 30px 15px 30px;
    margin: -30px -30px 30px -30px;
    border-radius: 10px 10px 0 0;
    -webkit-border-radius: 10px 10px 0 0;
    -moz-border-radius: 10px 10px 0 0;
    color: #fff;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
    font: normal 30px 'Bitter', serif;
    -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
    -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
    box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
    border: 1px solid #257C9E;
}
.form-style-10 h1 > span{
    display: block;
    margin-top: 2px;
    font: 13px Arial, Helvetica, sans-serif;
}
.form-style-10 label{
    display: block;
    font: 13px Arial, Helvetica, sans-serif;
    color: #888;
    margin-bottom: 15px;
}
.form-style-10 input[type="text"],
.form-style-10 input[type="date"],
.form-style-10 input[type="datetime"],
.form-style-10 input[type="email"],
.form-style-10 input[type="number"],
.form-style-10 input[type="search"],
.form-style-10 input[type="time"],
.form-style-10 input[type="url"],
.form-style-10 input[type="password"],
.form-style-10 textarea,
.form-style-10 select {
    display: block;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    width: 100%;
    padding: 8px;
    border-radius: 6px;
    -webkit-border-radius:6px;
    -moz-border-radius:6px;
    border: 2px solid #fff;
    box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
    -moz-box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
    -webkit-box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
}

.form-style-10 .section{
    font: normal 20px 'Bitter', serif;
    color: #2A88AD;
    margin-bottom: 5px;
}
.form-style-10 .section span {
    background: #2A88AD;
    padding: 5px 10px 5px 10px;
    position: absolute;
    border-radius: 50%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border: 4px solid #fff;
    font-size: 14px;
    margin-left: -45px;
    color: #fff;
    margin-top: -3px;
}
.form-style-10 input[type="button"], 
.form-style-10 input[type="submit"]{
    background: #2A88AD;
    padding: 8px 20px 8px 20px;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    color: #fff;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
    font: normal 30px 'Bitter', serif;
    -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
    -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
    box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
    border: 1px solid #257C9E;
    font-size: 15px;
}
.form-style-10 input[type="button"]:hover, 
.form-style-10 input[type="submit"]:hover{
    background: #2A6881;
    -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
    -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
    box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
}
.form-style-10 .privacy-policy{
    float: right;
    width: 250px;
    font: 12px Arial, Helvetica, sans-serif;
    color: #4D4D4D;
    margin-top: 10px;
    text-align: right;
}

input.invalid, textarea.invalid{
	border: 2px solid red;
}

input.valid, textarea.valid{
	border: 2px solid green;
}

.error{
	display: none;
	margin-left: 10px;
}		

.error_show{
	color: red;
	margin-left: 10px;
}

</style>
 <script>
 var fields = {
	 'Token':'api_token',
	 'Databse Host':'db-host',
	 'Databse Name':'db-name',
	 'Databse User':'db-user',
	 };
	 $( document ).ready(function() {
	  $('form').submit(function( event ) {
		$.each(fields, function( key, value ) {
		  if($('#'+value).val() == "") {
			   alert(key+" is empty!");
			   $('#'+value).focus();
			   $('#'+value).css('background-color' , 'red');
			   event.preventDefault();
			  return false;
		  }
		  else {
			   $('#'+value).css('background-color' , 'white');
		  }  
		});
	  });
	});
 </script>
 
</head>
<body>
<div class="form-style-10">
<h1>RMI Json to MySql Convertor - Getting Started</span></h1>
<form name="configs" id="form-config" method="post">
    <div class="section"><span>1</span>API</div>
    <div class="inner-wrap">
        <label>Token Url <sub>default = leave blank</sub><input type="text" name="api_url" id="api_url" placeholder="http://api.rmdatalink.com/api/product/config" /></label>	
		<label>Token Code <input type="text" name="api_token" id="api_token"  /></label><span class="error">A Token is required</span>	
       
    </div>

    <div class="section"><span>2</span>MySql Database</div>
    <div class="inner-wrap">
	<label>DB Host  <input type="text" name="db-host"  id="db-host" /></label><span class="error">A Host is required</span>	
        <label>DB Name <input type="text" name="db-name" id="db-name" /></label><span class="error">A DB Name is required</span>	
        <label>DB User  <input type="text" name="db-user" id="db-user" /></label><span class="error">A DB User required</span>	
		 <label>DB Pass  <input type="password" name="db-password"  id="db-password" /></label><span class="error">A DB Pass is required</span>	
    </div>

    <div class="section"><span>3</span>API Requests Limits (default and maximum: 300)</div>
        <div class="inner-wrap">
        <label>Limit <input type="text" name="api_limit" id="api_limit" placeholder="300" /></label>
  </div>
  <div class="button-section">
   <input type="submit" name="Install" value="install" />
  </div>
</form>
</div>
</body>
</html>


 