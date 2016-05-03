<?php
 
require_once '/_includes/class.fetch.php';

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
			echo "Connected successfully"; 
			}
		catch(PDOException $e)
			{
			echo "Connection failed: " . $e->getMessage();
			}
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


    public function installAddField($fields)
    {
	$databaseName = $this->CONFIG["MYSQL"]["dbname"];
  
        foreach($fields as $field)
        {
            $sqlAddField = "ALTER TABLE $databaseName.rmi ADD $databaseName.rmi.$field VARCHAR(1000) NULL";
            if(!$this->connection->query($sqlAddField)) return "ERROR ON CREATING FIELD. TRY AGAIN!";
        }
    return true;
    }

    public function install()
    {
		//delete table
		$databaseName = $this->CONFIG["MYSQL"]["dbname"];
        $sqlDeleteTable = "DROP TABLE $databaseName.rmi";
		$this->connection->query($sqlDeleteTable);

		//create table
		$sqlCreateTable = "CREATE TABLE $databaseName.rmi (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)";
        $this->connection->query($sqlCreateTable);
        
		$fetchObj = new FetchDataApi;
		$skip=$this->CONFIG["API"]["skip"];
		
			do {			
			$skip = $fetchObj->getFields($skip);
			echo "<br>".$skip."<br>";
		} while($skip > 0);
		
		if(!$this->installAddField($fetchObj->allFields)) return "ERROR ON CREATE FIELDS";
		
		return true;
    }
	
 
 
	
 

}