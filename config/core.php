<?php
    //show error reporting
    ini_set('display_errors',1);
    error_reporting(E_ALL);

    //home page url
    $home_url = "http://192.168.3.73:8012/NinjaRestAPI/";

    //page given in url parameter, default page is one
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    //set number of records per page
    $records_per_page = 5;
    
    //calculate for the query limit cause
    $from_record_num = ($records_per_page * $page) - $records_per_page;

?>