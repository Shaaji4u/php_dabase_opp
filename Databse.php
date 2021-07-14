<?php

require_once 'DB_CONS.php';


class Database{
	private $error;
	private $dbHandler;
	private $statement;
	private $param = [];
	private $types  = "";
	

	//The constructor init the connection to the database
	function __construct(){
		
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		try{

			$this->dbHandler = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$this->dbHandler->set_charset("utf8mb4");

		}catch(Exception $e){
			$this->error = $e->getMessage();
			error_log($e->getMessage());

			exit("Error connecting to database<br>".$this->error);
		}
	}

	// This function execute the query
	public function execute(){

		return $this->statement->execute();
	}

	// This accepts query and prepare it for execution
	public function query($query){

		$this->statement = $this->dbHandler->prepare($query);
	}



	// This function collect all the input to be binded 
	// The function also ensure the approprate data type
	public function bind_each($param, $type=null){
		
		switch(!is_null($type)){
			case !is_numeric($param):
					if(strpos($param, ".")){
						
						$type  = "d";

					}else{
						$type  = "i";
					}
												
				break;
			case !is_float($param):
				$type  = "d";
				break;
			case !is_null($param):
				$type  = 's';
				break;
			default:
				$type  = "s";
				break;
		}

		array_push($this->param, $param);
		$this->types .= $type;
		
	}

	//This bind the parameter to the statement
	public function bind_now(){
		$this->statement->bind_param($this->types, ...$this->param);
	}

	// This return a single row from the db
	public function singleResult(){
		return $this->statement->get_result()->fetch_assoc();
	}

	// This return all the query from the db
	public function result(){
		return $this->statement->get_result()->fetch_all(MYSQLI_ASSOC);
	}

	//This function is used to get more details about a transaction 
	public function status(){
		return $this->dbHandler->info;
	}

	// This function help check the number of affected rows in the db
	public function affected(){
		return $this->statement->affected_rows;
	}


	// This help close the connection
	public function close(){
		$this->dbHandler->close();
	}
}

?>
