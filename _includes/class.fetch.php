<?php

class FetchDataApi
{
	private $CONFIG;

	public $allFields = [];
	public $itemsArray = [];

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

        if ($err) return "cURL Error #:" . $err;
        else if(!$result) return "Result is empty";
        else {
			
			$result = str_replace(',}', '}', $result);
            $result = str_replace(',]', ']', $result);
            $response = json_decode($result, true);
			if($skip == 0) echo "Total number of configurables: ".$response["count"]."<br/>";
			
			 for ($i = 0; $i < count($response["items"]); $i++) {
				 //var_dump();
				 foreach($response["items"][$i]["items"] as $item ) {
				 $itemArray = [];
				   foreach($response["items"][$i]["_id"] as $fieldKey => $fieldValue ) {	
					$fieldFinal = 'RMI_'.$fieldKey;					 
						if(!in_array($fieldFinal , $this->allFields, true)){
							array_push($this->allFields, $fieldFinal );
						}
						$itemArray[$fieldFinal] = $fieldValue;
					}
					foreach($item as $fieldKey => $fieldValue ) {	
					$fieldFinal = 'RMI_'.$fieldKey;					 
						if(!in_array($fieldFinal , $this->allFields, true)){
							array_push($this->allFields, $fieldFinal );
						}
						$itemArray[$fieldFinal] = $fieldValue;	
						/*
						
						// Custom Fields
						if(strpos($fieldKey, "image_filename") !== false && is_array($fieldValue)) {
							//first image of _D / _C ...
							$fieldFinalImage = $fieldFinal."_first_image";
							 if(!in_array($fieldFinalImage , $this->allFields, true)){
								array_push($this->allFields, $fieldFinalImage );
							}
							$_fieldValue = $fieldValue[0]["img"];
							$itemArray[$fieldFinalImage] = $_fieldValue;	
							
							//first title of _D / _C ...
							$fieldFinalTitle = $fieldFinal."_first_title";
							 if(!in_array($fieldFinalTitle , $this->allFields, true)){
								array_push($this->allFields, $fieldFinalTitle );
							}
							$_fieldValue = isset($fieldValue[0]["dscr"])?$fieldValue[0]["dscr"]:"";
							$itemArray[$fieldFinalTitle] = $_fieldValue;	
						}
						
						*/
					}
				 }
				  $this->itemsArray[] = $itemArray;
			 }
		}

		return count($response["items"]) == 0 ? -1: $skip + $limit;	 
	 }
	 
	
	 public function fetchData()  {
		$skip=$this->CONFIG["API"]["skip"];
		do {			
			$skip = $this->getFields($skip);
		} while($skip > 0); 
	 }
	 
	 
	 
	 
	 
 
 
 
 
			
			
	

}