<?php    
    class Category{
        //database connection and table name    
        private $conn;
        private $table_name = "categories";

        //object properties
        public $id;
        public $name;
        public $description;
        public $created;

        public function __construct($db){
            $this->conn = $db;
        }

        //used by select drop-down-list
        public function read(){
            //select all data
            $query = "SELECT id, name, description,created  
                        FROM ".$this->table_name." 
                        ORDER BY name";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function read_id($id){
            //select all data
            $query = "SELECT id, name, description,created  
                        FROM ".$this->table_name." where id = ?
                        ORDER BY name";
            
            $stmt = $this->conn->prepare($query);

            //bind param
            $stmt->bindParam(1, $id);                       
         
            $stmt->execute();
            return $stmt;
        }

        public function create(){
             //query to insert record
             $query = "INSERT INTO " . $this->table_name . " 
             SET
                name=:name, description=:description, created=:created";

            //prepare query
            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));            
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->created=htmlspecialchars(strip_tags($this->created));

            //bind values
            $stmt->bindParam(":name",$this->name);
            $stmt->bindParam(":description",$this->description);
            $stmt->bindParam(":created",$this->created);

            //execute query
            if($stmt->execute()){
                return true;
            }
            return false;            
        }

        public function update(){            
            // update query
                $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        name =:name,
                        description =:description
                    WHERE
                        id = :id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->id=htmlspecialchars(strip_tags($this->id));

            // bind new values
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':id', $this->id);

            // execute the query
            if($stmt->execute()){
            return true;
            }

            return false;                           
        }        

        public function delete($id){
            
            $query = "DELETE FROM " .$this->table_name. " where id = ?";

            $stmt = $this->conn->prepare($query);                
            $this->id =htmlspecialchars(strip_tags($id));
            $stmt->bindParam(1, $id);            
            // execute the query
            if($stmt->execute()){
                return true;
            }
                return false;                  
        }

    }       
?>
