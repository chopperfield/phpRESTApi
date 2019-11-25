<?php
    //require headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age:3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //include database and object files
    include_once "../config/database.php";
    include_once "../objects/product.php";

    //get database connection
    $database = new Database();
    $db = $database->getConnection();

    //prepare product object
    $product = new Product($db);

    //get id of product to be edited
    $data = json_decode(file_get_contents("php://input"));

    //set id property of product to be edited
    $product->id=$data->id;

    //set product property values
    $product->name=$data->name;
    $product->price=$data->price;
    $product->description=$data->description;
    $product->category_id=$data->category_id;

    //update the product
    if($product->update()){
        //set response code -200 OK
        http_response_code(200);

        //tell the user
        echo json_encode(array("message" => "Product was updated."));     
    }
    else{
        //if unable to update the product, tell the user
        //set reponse code -503 service unavailable
        http_response_code(503);

        echo json_encode(array("messsage" => "Unable to update product"));
    }

//-------------------
// {
//     "id" : "106",
//     "name" : "Amazing Pillow 3.0",
//     "price" : "255",
//     "description" : "The best pillow for amazing programmers.",
//     "category_id" : 2,
//     "created" : "2018-08-01 00:35:07"
// }
//----------------------

?>