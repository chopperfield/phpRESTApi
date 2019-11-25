<?php
    
    //required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    //get database connection
    include_once '../config/database.php';

    //instantiate product object
    include_once '../objects/product.php';

    $database = new Database();
    $db = $database->getConnection();

    $product = new Product($db);

    //get posted data
    $data = json_decode(file_get_contents("php://input"));

    //make sure data is not empty
    if(
        !empty($data->name) &&
        !empty($data->price) &&
        !empty($data->description) &&
        !empty($data->category_id)
    )
    {
        //set product property values
        $product->name=$data->name;
        $product->price=$data->price;
        $product->description=$data->description;
        $product->category_id=$data->category_id;
        $product->created = date('Y-m-d H:i:s');

        //create product
        if($product->create()){
            //set response code -201 created
            http_response_code(201);

            //tell the user
            echo json_encode(array("message"=>"product was created"));
        }
        else{
            //if unable to create the product, tell the user
            //set response code -503 service unavailable
            http_response_code(503);
            //tell the user
            echo json_encode(array("message" => "Unable to create product" ));
        }
    }
    else{
        //tell the user data incomplete
        //set response code - 400 bad request
        http_response_code(400);

        //tell the user
        echo json_encode(array("message" => "Unable to create product. Data is incomplete"));
    }


    //-------------------
    //{
    // "name" : "Amazing Pillow 2.0",
    // "price" : "199",
    // "description" : "The best pillow for amazing programmers.",
    // "category_id" : 2,
    // "created" : "2018-06-01 00:35:07"
    //}
    //--------------------




?>