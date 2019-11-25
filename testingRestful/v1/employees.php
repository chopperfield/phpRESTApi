<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Methods: PUT");

    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once ("../database.php");
    include_once ("category.php");

    $database = new Database();
    $db = $database->getConnection();

    $category = new Category($db);


    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method){
        case 'GET':
            //retrieve
             if(!empty($_GET["id"])){
                 $id=intval($_GET["id"]);                
                 $stmt = $category->read_id($id);
                 $num = $stmt->rowCount();
                if($num>0){
                    $categories_arr = array();
                    $categories_arr["records"] = array();
            
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);
            
                        $categories_item = array(
                            "id" => $id,
                            "name" => $name,
                            "description" => html_entity_decode($description),
                            "created" => $created,
                            "type" => $request_method
                        );
                        array_push($categories_arr["records"],$categories_item);
                    }                    
                    http_response_code(200);
                    echo json_encode($categories_arr);            
                }
                else{
                    http_response_code(404);
                    echo json_encode(array("message"=>"No categories found."));
                }
                
            }else{
                $stmt =  $category->read();
                $num = $stmt->rowCount();
                if($num>0){
                    $categories_arr = array();
                    $categories_arr["records"] = array();
            
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);
            
                        $categories_item = array(
                            "id" => $id,
                            "name" => $name,
                            "description" => html_entity_decode($description),
                            "created" => $created,
                            "type" => $request_method
                        );
                        array_push($categories_arr["records"],$categories_item);
                    }                    
                    http_response_code(200);
                    echo json_encode($categories_arr);            
                }
                else{
                    http_response_code(404);
                    echo json_encode(array("message"=>"No categories found."));
                }
            }
        break;
        
        case 'POST':
            //get posted data            
            $data = json_decode(file_get_contents("php://input"));
            //$data = json_decode(file_get_contents("php://input"),true);
            //retrieve
            if(                
                !empty($data->name) &&
                !empty($data->description)   
                //!empty($data["name"]) &&
                //!empty($data["description"])              
            )
            {
                //set property values
                $category->name=$data->name;                
                $category->description=$data->description;
                $category->created = date('Y-m-d H:i:s');     

                 //create category
                if($category->create()){
                    //set response code -201 created
                    http_response_code(201);
                    //tell the user
                     echo json_encode(array("message"=>"category was created"));
                }
                else{
                    //if unable to create the product, tell the user
                    //set response code -503 service unavailable
                    http_response_code(503);
                    //tell the user
                    echo json_encode(array("message" => "Unable to create category"));
                }
            }
            else{
                //tell the user data incomplete
                //set response code - 400 bad request
                http_response_code(400);                
                //tell the user
                echo json_encode(array("message" => "Unable to create product. Data is incomplete"));
            }                                 
        break;

        case 'PUT':
              $data = json_decode(file_get_contents("php://input"));
              if(                
                !empty($data->id) &&
                !empty($data->name) &&
                !empty($data->description)   
              )
              {
                //set property values
                $category->id = $data->id;
                $category->name=$data->name;               
                $category->description=$data->description;

                 //create category
                 if($category->update()){
                //     //set response code -201 created
                     http_response_code(201);
                //     //tell the user
                       echo json_encode(array("message"=>"category was updated","as" =>  $data->id.", ". $data->name.", ".$data->description));
                 }
                 else{
                //     //if unable to create the product, tell the user
                //     //set response code -503 service unavailable
                     http_response_code(503);
                //     //tell the user
                     echo json_encode(array("message" => "Unable to update category"));
                 }
            }
            else{
                //tell the user data incomplete
                //set response code - 400 bad request
                http_response_code(400);                
                //tell the user
                echo json_encode(array("message" => "Unable to update product. Data is incomplete"));
            }        
        break;

        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"));
            if(                
                !empty($data->id)
            )
            {
                $id=intval($data->id);                                
                if($category->delete($id)){
                    //set response code -201 deleted
                    http_response_code(201);
                    //tell the user
                    echo json_encode(array("message"=>"category was deleted"));
                }
                else{
                    //if unable to create the product, tell the user
                    //set response code -503 service unavailable
                    http_response_code(503);
                    //tell the user
                    echo json_encode(array("message" => "Unable to delete category"));
                }
            }
            else{
                http_response_code(400);
                echo json_encode(array("message"=>"Unable to delete product. Data is incomplete"));
            }          
        break;    

        default:
        //invalid request method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }


    

   

?>