<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'config.php'; 
require './vendor/autoload.php';

header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, PATCH, OPTIONS');

$config = ['settings' => ['displayErrorDetails' => true]]; 

$app = new Slim\App($config);

// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });

// $app->add(function ($req, $res, $next) {
//     $response = $next($req, $res);
//     return $response
//             ->withHeader('Access-Control-Allow-Origin', '*')
//             ->withHeader('Access-Control-Allow-Headers', '*')
//             ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
// });
// $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
//     $name = $args['name'];
//     $response->getBody()->write("Hello, $name");

//     return $response;
// });

//Add new customer
$app->post('/add/customer', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    
    $db = getDB();
    $sql = "select * from customer where mobile=".$body['mobile'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"This customer already exists");
    }
    else{
        $insertSql = "insert into customer(name, mobile, email, birthday)values(:name, :mobile, :email, :birthday)";
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam("name", $body['name'], PDO::PARAM_STR);
        $stmtInsert->bindParam("mobile", $body['mobile'], PDO::PARAM_STR);
        $stmtInsert->bindParam("email", $body['email'], PDO::PARAM_STR);
        $stmtInsert->bindParam("birthday", $body['birthday'], PDO::PARAM_STR);
        
        $stmtInsert->execute();
        

        $lastid = $db->lastInsertId();

        if($lastid){
            $response = array("status"=>true, "message"=>"Customer added successfully");
        }
        else{
            $response = array("status"=>false, "message"=>"Error occurred while adding, please try again");
        }
    }

    echo json_encode($response);

});

//Add new staff
$app->post('/add/staff', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    
    $db = getDB();
    $sql = "select * from staff where mobile=".$body['mobile'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"This staff already exists");
    }
    else{
        $insertSql = "insert into staff(name, address, aadhar, mobile)values(:name, :address, :aadhar, :mobile)";
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam("name", $body['name'], PDO::PARAM_STR);
        $stmtInsert->bindParam("address", $body['address'], PDO::PARAM_STR);
        $stmtInsert->bindParam("aadhar", $body['aadhar'], PDO::PARAM_STR);
        $stmtInsert->bindParam("mobile", $body['mobile'], PDO::PARAM_STR);
        
        $stmtInsert->execute();
        

        $lastid = $db->lastInsertId();

        if($lastid){
            $response = array("status"=>true, "message"=>"Staff added successfully");
        }
        else{
            $response = array("status"=>false, "message"=>"Error occurred while adding, please try again");
        }
    }

    echo json_encode($response);

});

//Add new service
$app->post('/add/service', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    
    $db = getDB();
    $sql = "select * from services where name='".$body['name']."'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"This service already exists");
    }
    else{
        $insertSql = "insert into services(name, price)values(:name, :price)";
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam("name", $body['name'], PDO::PARAM_STR);
        $stmtInsert->bindParam("price", $body['price'], PDO::PARAM_STR);
        
        $stmtInsert->execute();

        $lastid = $db->lastInsertId();

        if($lastid){
            $response = array("status"=>true, "message"=>"Service added successfully");
        }
        else{
            $response = array("status"=>false, "message"=>"Error occurred while adding, please try again");
        }
    }

    echo json_encode($response);

});

//Edit service
$app->put('/edit/service', function (Request $request, Response $response, array $args) {

    $body = $request->getParsedBody();
    
    $db = getDB();

    $insertSql = "update services set name=:name, price=:price where id=:id";
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam("name", $body['name'], PDO::PARAM_STR);
    $stmtInsert->bindParam("price", $body['price'], PDO::PARAM_STR);
    $stmtInsert->bindParam("id", $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Service updated successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Error occurred while updating, please try again");
    }

    echo json_encode($response);

});

//Edit staff
$app->put('/edit/staff', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    
    $db = getDB();
  
    $insertSql = "update staff set name=:name, address=:address, aadhar=:aadhar, mobile=:mobile where id=:id";
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam("name", $body['name'], PDO::PARAM_STR);
    $stmtInsert->bindParam("address", $body['address'], PDO::PARAM_STR);
    $stmtInsert->bindParam("aadhar", $body['aadhar'], PDO::PARAM_STR);
    $stmtInsert->bindParam("mobile", $body['mobile'], PDO::PARAM_STR);
    $stmtInsert->bindParam("id", $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Staff updated successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Error occurred while updating, please try again");
    }

    echo json_encode($response);

});

//Edit customer
$app->put('/edit/customer', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    
    $db = getDB();
  
    $insertSql = "update customer set name=:name, mobile=:mobile, email=:email, birthday=:birthday where id=:id";
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam("name", $body['name'], PDO::PARAM_STR);
    $stmtInsert->bindParam("email", $body['email'], PDO::PARAM_STR);
    $stmtInsert->bindParam("birthday", $body['birthday'], PDO::PARAM_STR);
    $stmtInsert->bindParam("mobile", $body['mobile'], PDO::PARAM_STR);
    $stmtInsert->bindParam("id", $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Customer details updated successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Error occurred while updating, please try again");
    }

    echo json_encode($response);

});

//Add bill
$app->post('/add/bill', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    
    $db = getDB();
    
    $insertSql = "insert into billing(customerId, staffId, services, totalamount, discount_applied)values(:customerId, :staffId, :services, :totalamount, :discount_applied)";
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam("customerId", $body['customerId'], PDO::PARAM_STR);
    $stmtInsert->bindParam("staffId", $body['staffId'], PDO::PARAM_STR);
    $stmtInsert->bindParam("services", $body['services'], PDO::PARAM_STR);
    $stmtInsert->bindParam("totalamount", $body['totalamount'], PDO::PARAM_STR);
    $stmtInsert->bindParam("discount_applied", $body['discount_applied'], PDO::PARAM_STR);
    
    $stmtInsert->execute();

    $lastid = $db->lastInsertId();

    if($lastid){
        $response = array("status"=>true, "message"=>"Bill added successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Error occurred while adding, please try again");
    }
    

    echo json_encode($response);

});

//Get all customers
$app->get('/get/customers', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from customer order by created_at desc";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $customersList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Customers list", "data"=>$customersList);
    }
    else{
        $response = array("status"=>false, "message"=>"Customers doesn't exist");
    }

    echo json_encode($response);

});

//Get customers page wise
$app->get('/get/customers/{size}/{range}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from customer order by created_at desc limit ".$args['range'].",".$args['size'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $customersList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Customers list", "data"=>$customersList);
    }
    else{
        $response = array("status"=>false, "message"=>"Customers doesn't exist");
    }

    echo json_encode($response);

});

//Get all staffs
$app->get('/get/staffs', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from staff order by created_at desc";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffsList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Staff list", "data"=>$staffsList);
    }
    else{
        $response = array("status"=>false, "message"=>"Staff doesn't exist");
    }

    echo json_encode($response);

});

//Get all services
$app->get('/get/services', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from services order by created_at desc";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Services list", "data"=>$servicesList);
    }
    else{
        $response = array("status"=>false, "message"=>"Services doesn't exist");
    }

    echo json_encode($response);

});

//Get service by ID
$app->get('/get/service/{id}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from services where id=".$args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetch(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Service data", "data"=>$servicesList);
    }
    else{
        $response = array("status"=>false, "message"=>"Services doesn't exist");
    }

    echo json_encode($response);

});

//Get staff by id
$app->get('/get/staff/{id}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from staff where id=".$args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffData = $stmt->fetch(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Staff data", "data"=>$staffData);
    }
    else{
        $response = array("status"=>false, "message"=>"Staff doesn't exist");
    }

    echo json_encode($response);

});

//Get customer by id
$app->get('/get/customer/{id}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "select * from customer where id=".$args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffData = $stmt->fetch(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Customer data", "data"=>$staffData);
    }
    else{
        $response = array("status"=>false, "message"=>"Customer doesn't exist");
    }

    echo json_encode($response);

});

//delete customer by id
$app->delete('/delete/customer/{id}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "delete from customer where id=".$args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Customer deleted successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Customer doesn't exist");
    }

    echo json_encode($response);

});

//delete staff by id
$app->delete('/delete/staff/{id}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "delete from staff where id=".$args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Staff deleted successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Staff doesn't exist");
    }

    echo json_encode($response);

});

//delete service by id
$app->delete('/delete/service/{id}', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $sql = "delete from services where id=".$args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Service deleted successfully");
    }
    else{
        $response = array("status"=>false, "message"=>"Service doesn't exist");
    }

    echo json_encode($response);

});

$app->get('/get/billings', function (Request $request, Response $response, array $args) {

    $db = getDB(); 
    $sql = "select table1.*, staff.name as staff from (select billing.*, customer.name as customer, customer.mobile as customer_mobile from billing left join customer on billing.customerId=customer.id) as table1, staff where table1.staffId=staff.id";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Bill list", "data"=>$servicesList);
    }
    else{
        $response = array("status"=>false, "message"=>"Billings doesn't exist");
    }

    echo json_encode($response);

});

//Search by customer
$app->post('/search/customer', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $body = $request->getParsedBody();
    $search = $body['query'];
    $sqlsearch = "select * from customer where mobile like '%".$search."%' limit 10";
    $stmtsearch = $db->prepare($sqlsearch);
  
    $stmtsearch->execute();
    $rowCount = $stmtsearch->rowCount();
    $searchData = $stmtsearch->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Customers list", "data"=>$searchData);
    }
    else{
        $response = array("status"=>false, "message"=>"Customer doesn't exist");
    }

    echo json_encode($response);

});

//Search staff
$app->post('/search/staff', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $body = $request->getParsedBody();
    $search = $body['query'];
    $sqlsearch = "select * from staff where name like '%".$search."%' limit 10";
    $stmtsearch = $db->prepare($sqlsearch);
    $stmtsearch->execute();
    $rowCount = $stmtsearch->rowCount();
    $searchData = $stmtsearch->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Staffs list", "data"=>$searchData);
    }
    else{
        $response = array("status"=>false, "message"=>"Staff doesn't exist");
    }

    echo json_encode($response);
});

//Search all services
$app->post('/search/service', function (Request $request, Response $response, array $args) {

    $db = getDB();
    $body = $request->getParsedBody();
    $search = $body['query'];
    $sqlsearch = "select * from services where name like ?";
    $params = array("%$search%");
    $stmtsearch = $db->prepare($sqlsearch);
    $stmtsearch->execute($params);
    $rowCount = $stmtsearch->rowCount();
    $searchData = $stmtsearch->fetchAll(PDO::FETCH_OBJ);

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Services list", "data"=>$searchData);
    }
    else{
        $response = array("status"=>false, "message"=>"Service doesn't exist");
    }

    echo json_encode($response);

});

$app->get('/get/summary', function (Request $request, Response $response, array $args) {

    $db = getDB(); 
    $sql = "select year(created_at) as current_year,month(created_at) as current_month,sum(totalamount) as total
    from billing where year(created_at)=:year and month(created_at)=:month
    group by year(created_at),month(created_at)
    order by year(created_at),month(created_at)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam("year", date("Y"), PDO::PARAM_STR);
    $stmt->bindParam("month", date("m"), PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $summary = $stmt->fetch(PDO::FETCH_OBJ);

    $summary->current_month = date('F', mktime(0, 0, 0, $summary->current_month, 10));

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Summary", "data"=>$summary);
    }
    else{
        $response = array("status"=>false, "message"=>"Summary doesn't exist");
    }

    echo json_encode($response);

});

//Get summary by date and month
$app->get('/get/summary/{month}/{year}', function (Request $request, Response $response, array $args) {

    $db = getDB(); 
    $sql = "select year(created_at) as current_year,month(created_at) as current_month,sum(totalamount) as total
    from billing where year(created_at)=:year and month(created_at)=:month
    group by year(created_at),month(created_at)
    order by year(created_at),month(created_at)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam("year", $args['year'], PDO::PARAM_STR);
    $stmt->bindParam("month", $args['month'], PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $summary = $stmt->fetch(PDO::FETCH_OBJ);

    $summary->current_month = date('F', mktime(0, 0, 0, $summary->current_month, 10));

    if($rowCount > 0){
        $response = array("status"=>true, "message"=>"Summary", "data"=>$summary);
    }
    else{
        $response = array("status"=>false, "message"=>"Summary doesn't exist");
    }

    echo json_encode($response);

});

$app->run();