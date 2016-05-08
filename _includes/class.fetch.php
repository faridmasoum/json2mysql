<?php

class FetchDataApi
{
	private $CONFIG;

	public $allFields = array();
	public $itemsArray = array();
	public $fieldLenght = array();

    function __construct()
    {
        $this->CONFIG = parse_ini_file("./_config/config.ini", true);
    }

	 public function getFields($skip)  {
        $limit = $this->CONFIG["API"]["limit"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "80",
            CURLOPT_URL =>  $this->CONFIG["API"]["url"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "x-inno-request: {\"skip\":".$skip.",\"limit\":".$this->CONFIG["API"]["limit"]."}",
                "x-inno-token: ". $this->CONFIG["API"]["token"] 
            ),
        ));
        curl_setopt($curl, CURLOPT_TIMEOUT,500);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) return "cURL Error #:" . $err."<br/>";
        else if(!$result) return "Result is empty"."<br/>";
        else {
			
			$result = str_replace(',}', '}', $result);
            $result = str_replace(',]', ']', $result);
            $response = json_decode($result, true);
			 if($skip == 0) echo "<b>Fetching ".$response["count"]." Product ...... Please Wait....</b><br/>";
			
			 for ($i = 0; $i < count($response["items"]); $i++) {
				 foreach($response["items"][$i]["items"] as $item ) {
				 $itemArray = array();
				   foreach($response["items"][$i]["_id"] as $fieldKey => $fieldValue ) {	
					$fieldFinal = 'RMI_'.$fieldKey;					 
						if(!in_array($fieldFinal , $this->allFields, true)){
							array_push($this->allFields, $fieldFinal );
						}
						$itemArray[$fieldFinal] = $fieldValue;
						
						// count field size
						$stringFieldValue =   (string)(is_array($fieldValue)?$fieldValue[0]["img"]:$fieldValue);
						if(isset($this->fieldLenght[$fieldFinal])) {
							if(strlen($stringFieldValue) > strlen($this->fieldLenght[$fieldFinal]) ) {
								$this->fieldLenght[$fieldFinal] = strlen($stringFieldValue);
							}
						}
						else {
							$this->fieldLenght[$fieldFinal] = strlen($stringFieldValue);
						}
					}
					foreach($item as $fieldKey => $fieldValue ) {	
					$fieldFinal = 'RMI_'.$fieldKey;					 
						if(!in_array($fieldFinal , $this->allFields, true)){
							array_push($this->allFields, $fieldFinal );
						}
						$itemArray[$fieldFinal] = $fieldValue;	
						
						// count field size
						$stringFieldValue = (string)(is_array($fieldValue)?$fieldValue[0]["img"]:$fieldValue);
						if(isset($this->fieldLenght[$fieldFinal])) {
							if(strlen($stringFieldValue) > strlen($this->fieldLenght[$fieldFinal]) ) {
								$this->fieldLenght[$fieldFinal] = strlen($stringFieldValue);
							}
						}
						else {
							$this->fieldLenght[$fieldFinal] = strlen($stringFieldValue);
						}
						
					}
				 }
				  $this->itemsArray[] = $itemArray;
			 }
		}
 
		return count($response["items"]) == 0 ? -1: $skip + $limit;	 
	 }
	 
	
	 public function fetchData()  {
		$skip=0;
		do {			
			$skip = $this->getFields($skip);
		} while($skip > 0); 
	 }
	 
}