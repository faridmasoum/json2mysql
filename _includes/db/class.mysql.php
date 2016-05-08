<?php
 
require_once './_includes/class.fetch.php';

class Mysql
{
    public $connection;
    public $queryText = "";
    public $CONFIG ="" ;
    public $error = array() ;

    public function __construct($CONFIG)
    {
        $this->CONFIG = $CONFIG;		
		$servername = $CONFIG["MYSQL"]["host"];
        $username = $CONFIG["MYSQL"]["username"];
        $password = $CONFIG["MYSQL"]["password"];
        $database = $CONFIG["MYSQL"]["dbname"];
		
		try {
			$this->connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "Connected successfully"."<br/>";
			}
		catch(PDOException $e)
			{
			echo "Connection failed: " . $e->getMessage()."<br/>";
			}
    }
	
	 public function rowCount()
    {
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
		$sqlCountRows = "select count(id) from $databaseName.rmi";
            
		return $this->connection->query($sqlCountRows) ;
	}


    public function insertToDatabase($itemsArray)
    {
        $databaseName = $this->CONFIG["MYSQL"]["dbname"];
		foreach($itemsArray as $itemArray) {
				$fields=array_keys($itemArray); 
				$values=array_values($itemArray);
				foreach($values as $key => $value) {
					if(is_array($value)) {
						$values[$key] = $value[0]["img"];
					}
				}
				$fieldlist=implode(',',$fields); 
				$qs=str_repeat("?,",count($fields)-1);
				$sql="insert into $databaseName.rmi($fieldlist) VALUES (${qs}?) ";
				$q=$this->connection->prepare($sql);
				$q->execute($values);
		}
		return true;
    }


    public function installAddField($fields, $fieldLenght)
    {
	$databaseName = $this->CONFIG["MYSQL"]["dbname"];
  
        foreach($fields as $field)
        {
			$fieldSize = $fieldLenght[$field];
            $sqlAddField = "ALTER TABLE $databaseName.rmi ADD $databaseName.rmi.$field VARCHAR($fieldSize) NULL";
            if(!$this->connection->query($sqlAddField)) return "ERROR ON CREATING '".$field."' FIELD. TRY AGAIN!"."<br/>";
        }
    return true;
    }

    public function install()
    {
		//delete table
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
		
		$sqlDeleteTable = "DROP TABLE IF EXISTS $databaseName.rmi";
		$this->connection->query($sqlDeleteTable);

		//create table
		$sqlCreateTable = "CREATE TABLE $databaseName.rmi (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB;";
        $this->connection->query($sqlCreateTable);
        
		$fetchObj = new FetchDataApi;
		$fetchObj->fetchData();
		
		//install fields
		$this->installAddField($fetchObj->allFields, $fetchObj->fieldLenght);
		
		return $fetchObj;
    }
	
	public function reportCount()
	{
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
		$sqlReportCount = "SELECT count(id) from $databaseName.rmi";
		return  $this->connection->query($sqlReportCount)->fetchColumn();
	}
	
	public function reportVendors()
	{
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
		$sqlVendors = "Select
						  $databaseName.rmi.RMI_vendor_uid
						From
						  $databaseName.rmi
						Group By
						  $databaseName.rmi.RMI_vendor_uid";
		return  $this->connection->query($sqlVendors);	
	}
	
	public function reportDesignes()
	{
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
		$sqlDesignes = "Select
						  $databaseName.rmi.RMI_design_name
						From
						  $databaseName.rmi
						Group By
						  $databaseName.rmi.RMI_design_name";
		return  $this->connection->query($sqlDesignes);
	}
	
		public function reportCollections()
	{
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
		$sqlDesignes = "Select
						  $databaseName.rmi.RMI_collection_name
						From
						  $databaseName.rmi
						Group By
						  $databaseName.rmi.RMI_collection_name";
		return  $this->connection->query($sqlDesignes);
		
	}
 
	
 

}