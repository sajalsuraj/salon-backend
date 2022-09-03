<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

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
$app->post('/add/customer', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();
    $sql = 'select * from customer where mobile=' . $body['mobile'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'This customer already exists',
        ];
    } else {
        $insertSql =
            'insert into customer(name, mobile, email, birthday)values(:name, :mobile, :email, :birthday)';
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam('name', $body['name'], PDO::PARAM_STR);
        $stmtInsert->bindParam('mobile', $body['mobile'], PDO::PARAM_STR);
        $stmtInsert->bindParam('email', $body['email'], PDO::PARAM_STR);
        $stmtInsert->bindParam('birthday', $body['birthday'], PDO::PARAM_STR);

        $stmtInsert->execute();

        $lastid = $db->lastInsertId();

        if ($lastid) {
            $response = [
                'status' => true,
                'message' => 'Customer added successfully',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Error occurred while adding, please try again',
            ];
        }
    }

    echo json_encode($response);
});

//Add new staff
$app->post('/add/staff', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();
    $sql = 'select * from staff where mobile=' . $body['mobile'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'This staff already exists',
        ];
    } else {
        $insertSql =
            'insert into staff(name, address, aadhar, mobile)values(:name, :address, :aadhar, :mobile)';
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam('name', $body['name'], PDO::PARAM_STR);
        $stmtInsert->bindParam('address', $body['address'], PDO::PARAM_STR);
        $stmtInsert->bindParam('aadhar', $body['aadhar'], PDO::PARAM_STR);
        $stmtInsert->bindParam('mobile', $body['mobile'], PDO::PARAM_STR);

        $stmtInsert->execute();

        $lastid = $db->lastInsertId();

        if ($lastid) {
            $response = [
                'status' => true,
                'message' => 'Staff added successfully',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Error occurred while adding, please try again',
            ];
        }
    }

    echo json_encode($response);
});

//Add new product
$app->post('/add/product', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();
    $sql =
        "select * from inventory where product_name='" .
        $body['product_name'] .
        "'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'This product already exists',
        ];
    } else {
        $insertSql =
            'insert into inventory(product_name, quantity, price)values(:name, :quantity, :price)';
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam('name', $body['product_name'], PDO::PARAM_STR);
        $stmtInsert->bindParam('quantity', $body['quantity'], PDO::PARAM_STR);
        $stmtInsert->bindParam('price', $body['price'], PDO::PARAM_STR);

        $stmtInsert->execute();

        $lastid = $db->lastInsertId();

        if ($lastid) {
            $response = [
                'status' => true,
                'message' => 'Product added successfully',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Error occurred while adding, please try again',
            ];
        }
    }

    echo json_encode($response);
});

//Add new service
$app->post('/add/service', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();
    $sql = "select * from services where name='" . $body['name'] . "'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'This service already exists',
        ];
    } else {
        $insertSql = 'insert into services(name, price)values(:name, :price)';
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam('name', $body['name'], PDO::PARAM_STR);
        $stmtInsert->bindParam('price', $body['price'], PDO::PARAM_STR);

        $stmtInsert->execute();

        $lastid = $db->lastInsertId();

        if ($lastid) {
            $response = [
                'status' => true,
                'message' => 'Service added successfully',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Error occurred while adding, please try again',
            ];
        }
    }

    echo json_encode($response);
});

//Add new expense
$app->post('/add/expense', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $insertSql =
        'insert into expenses(expense_name, price, created_at)values(:name, :price, :created_at)';
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam('name', $body['expense_name'], PDO::PARAM_STR);
    $stmtInsert->bindParam('price', $body['price'], PDO::PARAM_STR);
    $stmtInsert->bindParam('created_at', $body['created_at'], PDO::PARAM_STR);

    $stmtInsert->execute();

    $lastid = $db->lastInsertId();

    if ($lastid) {
        $response = [
            'status' => true,
            'message' => 'Expense added successfully',
        ];
    } else {
        $response = [
            'status' => false,
            'message' => 'Error occurred while adding, please try again',
        ];
    }

    echo json_encode($response);
});

//Edit service
$app->put('/edit/service', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $insertSql = 'update services set name=:name, price=:price where id=:id';
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam('name', $body['name'], PDO::PARAM_STR);
    $stmtInsert->bindParam('price', $body['price'], PDO::PARAM_STR);
    $stmtInsert->bindParam('id', $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Service updated successfully',
        ];
    } else {
        $response = [
            'status' => false,
            'message' => 'Error occurred while updating, please try again',
        ];
    }

    echo json_encode($response);
});

//Edit expense
$app->put('/edit/expense', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $insertSql =
        'update expenses set expense_name=:name, price=:price, created_at=:created_at where id=:id';
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam('name', $body['expense_name'], PDO::PARAM_STR);
    $stmtInsert->bindParam('price', $body['price'], PDO::PARAM_STR);
    $stmtInsert->bindParam('created_at', $body['created_at'], PDO::PARAM_STR);
    $stmtInsert->bindParam('id', $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Expense updated successfully',
        ];
    } else {
        $response = [
            'status' => false,
            'message' => 'Error occurred while updating, please try again',
        ];
    }

    echo json_encode($response);
});

//Edit staff
$app->put('/edit/staff', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $insertSql =
        'update staff set name=:name, address=:address, aadhar=:aadhar, mobile=:mobile where id=:id';
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam('name', $body['name'], PDO::PARAM_STR);
    $stmtInsert->bindParam('address', $body['address'], PDO::PARAM_STR);
    $stmtInsert->bindParam('aadhar', $body['aadhar'], PDO::PARAM_STR);
    $stmtInsert->bindParam('mobile', $body['mobile'], PDO::PARAM_STR);
    $stmtInsert->bindParam('id', $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Staff updated successfully',
        ];
    } else {
        $response = [
            'status' => false,
            'message' => 'Error occurred while updating, please try again',
        ];
    }

    echo json_encode($response);
});

//Edit customer
$app->put('/edit/customer', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $insertSql =
        'update customer set name=:name, mobile=:mobile, email=:email, birthday=:birthday where id=:id';
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam('name', $body['name'], PDO::PARAM_STR);
    $stmtInsert->bindParam('email', $body['email'], PDO::PARAM_STR);
    $stmtInsert->bindParam('birthday', $body['birthday'], PDO::PARAM_STR);
    $stmtInsert->bindParam('mobile', $body['mobile'], PDO::PARAM_STR);
    $stmtInsert->bindParam('id', $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customer details updated successfully',
        ];
    } else {
        $response = [
            'status' => false,
            'message' => 'Error occurred while updating, please try again',
        ];
    }

    echo json_encode($response);
});

//Edit Product
$app->put('/edit/product', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $insertSql =
        'update inventory set product_name=:name, price=:price, quantity=:quantity where id=:id';
    $stmtInsert = $db->prepare($insertSql);
    $stmtInsert->bindParam('name', $body['product_name'], PDO::PARAM_STR);
    $stmtInsert->bindParam('quantity', $body['quantity'], PDO::PARAM_STR);
    $stmtInsert->bindParam('price', $body['price'], PDO::PARAM_STR);
    $stmtInsert->bindParam('id', $body['id'], PDO::PARAM_STR);
    $stmtInsert->execute();

    $rowCount = $stmtInsert->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Service updated successfully',
        ];
    } else {
        $response = [
            'status' => false,
            'message' => 'Error occurred while updating, please try again',
        ];
    }

    echo json_encode($response);
});

//Edit Bill
$app->put('/edit/bill', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();

    $db = getDB();

    $sql =
        "select * from customer where mobile='" . $body['customerMobile'] . "'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $customerData = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $customerId = $customerData->id;
    } else {
        $customerSql =
            'insert into customer(name, mobile)values(:name, :mobile)';
        $stmtCustomer = $db->prepare($customerSql);
        $stmtCustomer->bindParam('name', $body['customerName'], PDO::PARAM_STR);
        $stmtCustomer->bindParam(
            'mobile',
            $body['customerMobile'],
            PDO::PARAM_STR
        );

        $stmtCustomer->execute();
        $customerId = $db->lastInsertId();
    }

    $isValid = true;
    $products = json_decode($body['products']);

    foreach ($products as $k => $v) {
        if ($v->quantity > (int) $v->product_used->quantity) {
            $isValid = false;
            $response = [
                'status' => false,
                'message' =>
                    'Total number of ' .
                    $v->product_used->product_name .
                    ' in stock is ' .
                    $v->product_used->quantity .
                    ". You can't add more than that.",
            ];
            break;
        } else {
            $quantityLeft =
                (int) $v->product_used->quantity - (int) $v->quantity;
            $updateProductSql =
                'update inventory set quantity=:quantity where id=:id';
            $stmtUpdateProduct = $db->prepare($updateProductSql);
            $stmtUpdateProduct->bindParam(
                'quantity',
                $quantityLeft,
                PDO::PARAM_STR
            );
            $stmtUpdateProduct->bindParam(
                'id',
                $v->product_used->id,
                PDO::PARAM_STR
            );
            $stmtUpdateProduct->execute();
        }
    }

    if ($isValid) {
        if($body['created_at']){
            $updateBillingSql =
            'update billing set customerId = :customerId, services = :services, products = :products, totalamount = :totalamount, service_total=:service_total, product_total=:product_total, discount_applied = :discount_applied, payment_mode = :payment_mode, created_at=:created_at where id = :id';
        }
        else{
            $updateBillingSql =
            'update billing set customerId = :customerId, services = :services, products = :products, totalamount = :totalamount, service_total=:service_total, product_total=:product_total, discount_applied = :discount_applied, payment_mode = :payment_mode where id = :id';
        }
        
        $stmtInsert = $db->prepare($updateBillingSql);

        $stmtInsert->bindParam('customerId', $customerId, PDO::PARAM_STR);
        $stmtInsert->bindParam('services', $body['services'], PDO::PARAM_STR);
        if (count($products) > 0) {
            $stmtInsert->bindParam(
                'products',
                $body['products'],
                PDO::PARAM_STR
            );
        } else {
            $stmtInsert->bindValue('products', '', PDO::PARAM_STR);
        }
        if($body['created_at']){
            $stmtInsert->bindParam('created_at', $body['created_at'], PDO::PARAM_STR);
        }
        $stmtInsert->bindParam(
            'totalamount',
            $body['totalamount'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam(
            'service_total',
            $body['service_total'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam(
            'product_total',
            $body['product_total'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam(
            'discount_applied',
            $body['discount_applied'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam(
            'payment_mode',
            $body['payment_mode'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam('id', $body['id'], PDO::PARAM_STR);
        $stmtInsert->execute();

        $rowCount = $stmtInsert->rowCount();
        if ($rowCount > 0) {
            $response = [
                'status' => true,
                'message' => 'Bill updated successfully',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Error occurred while adding, please try again',
            ];
        }
    }

    echo json_encode($response);
});

//Add bill
$app->post('/add/bill', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();
    $isValid = true;

    $db = getDB();

    $sql =
        "select * from customer where mobile='" . $body['customerMobile'] . "'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $customerData = $stmt->fetch(PDO::FETCH_OBJ);
    $customerId = '';

    if ($rowCount > 0) {
        $customerId = $customerData->id;
        if (
            $customerData->birthday === '' &&
            $body['customerBirthday'] !== ''
        ) {
            $insertSql = 'update customer set birthday=:birthday where id=:id';
            $stmtInsert = $db->prepare($insertSql);
            $stmtInsert->bindParam(
                'birthday',
                $body['customerBirthday'],
                PDO::PARAM_STR
            );
            $stmtInsert->bindParam('id', $customerId, PDO::PARAM_STR);
            $stmtInsert->execute();
        }
    } else {
        $customerSql =
            'insert into customer(name, mobile, birthday)values(:name, :mobile, :birthday)';
        $customerName = ucfirst($body['customerName']);
        $stmtCustomer = $db->prepare($customerSql);
        $stmtCustomer->bindParam('name', $customerName, PDO::PARAM_STR);
        $stmtCustomer->bindParam(
            'mobile',
            $body['customerMobile'],
            PDO::PARAM_STR
        );
        $stmtCustomer->bindParam(
            'birthday',
            $body['customerBirthday'],
            PDO::PARAM_STR
        );

        $stmtCustomer->execute();
        $customerId = $db->lastInsertId();
    }

    $products = json_decode($body['products']);

    foreach ($products as $k => $v) {
        if ($v->quantity > (int) $v->product_used->quantity) {
            $isValid = false;
            $response = [
                'status' => false,
                'message' =>
                    'Total number of ' .
                    $v->product_used->product_name .
                    ' in stock is ' .
                    $v->product_used->quantity .
                    ". You can't add more than that.",
            ];
            break;
        } else {
            $quantityLeft =
                (int) $v->product_used->quantity - (int) $v->quantity;
            $updateProductSql =
                'update inventory set quantity=:quantity where id=:id';
            $stmtUpdateProduct = $db->prepare($updateProductSql);
            $stmtUpdateProduct->bindParam(
                'quantity',
                $quantityLeft,
                PDO::PARAM_STR
            );
            $stmtUpdateProduct->bindParam(
                'id',
                $v->product_used->id,
                PDO::PARAM_STR
            );
            $stmtUpdateProduct->execute();
        }
    }

    if ($isValid) {
        if($body['created_at']){
            $insertSql =
            'insert into billing(customerId, services, products, totalamount, service_total, product_total, discount_applied, payment_mode, created_at)values(:customerId, :services, :products, :totalamount, :service_total, :product_total, :discount_applied, :payment_mode, :created_at)';
        }
        else{
            $insertSql =
            'insert into billing(customerId, services, products, totalamount, service_total, product_total, discount_applied, payment_mode)values(:customerId, :services, :products, :totalamount, :service_total, :product_total, :discount_applied, :payment_mode)';
        }
        
        $stmtInsert = $db->prepare($insertSql);
        $stmtInsert->bindParam('customerId', $customerId, PDO::PARAM_STR);
        $stmtInsert->bindParam('services', $body['services'], PDO::PARAM_STR);
        if($body['created_at']){
            $stmtInsert->bindParam('created_at', $body['created_at'], PDO::PARAM_STR);
        }
        if (count($products) > 0) {
            $stmtInsert->bindParam(
                'products',
                $body['products'],
                PDO::PARAM_STR
            );
        } else {
            $stmtInsert->bindValue('products', '', PDO::PARAM_STR);
        }

        $service_total =
            (int) $body['service_total'] - (int) $body['discount_applied'];

        $stmtInsert->bindParam(
            'totalamount',
            $body['totalamount'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam('service_total', $service_total, PDO::PARAM_STR);
        $stmtInsert->bindParam(
            'product_total',
            $body['product_total'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam(
            'discount_applied',
            $body['discount_applied'],
            PDO::PARAM_STR
        );
        $stmtInsert->bindParam(
            'payment_mode',
            $body['payment_mode'],
            PDO::PARAM_STR
        );

        $stmtInsert->execute();

        $lastid = $db->lastInsertId();

        if ($lastid) {
            $response = [
                'status' => true,
                'last_bill_id' => $lastid,
                'message' => 'Bill added successfully',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Error occurred while adding, please try again',
            ];
        }
    }

    echo json_encode($response);
});

//Get all customers
$app->get('/get/customers', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from customer order by created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $customersList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customers list',
            'data' => $customersList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Customers doesn't exist"];
    }

    echo json_encode($response);
});

//Get customers page wise
$app->get('/get/customers/{size}/{range}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql =
        'select * from customer order by created_at desc limit ' .
        $args['range'] .
        ',' .
        $args['size'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $customersList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customers list',
            'data' => $customersList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Customers doesn't exist"];
    }

    echo json_encode($response);
});

//Get all staffs
$app->get('/get/staffs', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from staff order by created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffsList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Staff list',
            'data' => $staffsList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Staff doesn't exist"];
    }

    echo json_encode($response);
});

//Get all services
$app->get('/get/services', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from services order by created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Services list',
            'data' => $servicesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Services doesn't exist"];
    }

    echo json_encode($response);
});

//Get all expenses
$app->get('/get/expenses', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from expenses order by created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $expensesList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Expenses list',
            'data' => $expensesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Expenses doesn't exist"];
    }

    echo json_encode($response);
});

function getBirthdayList($date, $month)
{
    $db = getDB();
    $sql =
        'select c.*, count(b.customerId) as total_services from customer c left join billing b on c.id = b.customerId where month(c.birthday)=:month and dayofmonth(c.birthday)=:day group by c.id order by c.created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->bindParam('day', $date, PDO::PARAM_STR);
    $stmt->bindParam('month', $month, PDO::PARAM_STR);
    $stmt->execute();
    $todayBirthdayList = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $todayBirthdayList;
}

//Get customer birthdays by month
$app->get('/get/birthdays/{month}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql =
        'select c.*, count(b.customerId) as total_services from customer c left join billing b on c.id = b.customerId where month(c.birthday)=:month group by c.id order by dayofmonth(c.birthday)';
    $stmt = $db->prepare($sql);
    $stmt->bindParam('month', $args['month'], PDO::PARAM_STR);
    $stmt->execute();
    $birthdayList = $stmt->fetchAll(PDO::FETCH_OBJ);

    $response = [
        'status' => true,
        'message' => 'Birthdays list',
        'data' => $birthdayList,
    ];

    echo json_encode($response);
});

//Get customers by birthdays
$app->get('/get/birthdays', function (
    Request $request,
    Response $response,
    array $args
) {
    $currentMonth = date('m');
    $currentDate = date('d');

    $todayBirthdayList = getBirthdayList($currentDate, $currentMonth);

    $nextDay = date('d', strtotime(' +1 day'));
    $nextMonth = date('m', strtotime(' +1 day'));

    $tomorrowBirthdayList = getBirthdayList($nextDay, $nextMonth);

    $prevDay = date('d', strtotime(' -1 day'));
    $prevMonth = date('m', strtotime(' -1 day'));

    $yesterdayBirthdayList = getBirthdayList($prevDay, $prevMonth);

    $response = [
        'status' => true,
        'message' => 'Birthdays list',
        'today' => $todayBirthdayList,
        'yesterday' => $yesterdayBirthdayList,
        'tomorrow' => $tomorrowBirthdayList,
    ];

    echo json_encode($response);
});

//Get all products
$app->get('/get/products', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from inventory order by created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Product list',
            'data' => $servicesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Products doesn't exist"];
    }

    echo json_encode($response);
});

//Get service by ID
$app->get('/get/service/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from services where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Service data',
            'data' => $servicesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Services doesn't exist"];
    }

    echo json_encode($response);
});

//Get expense by ID
$app->get('/get/expense/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from expenses where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Expense data',
            'data' => $servicesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Expense doesn't exist"];
    }

    echo json_encode($response);
});

//Get product by ID
$app->get('/get/product/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from inventory where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Inventory data',
            'data' => $servicesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Inventory doesn't exist"];
    }

    echo json_encode($response);
});

//Get bill by id
$app->get('/get/bill/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql =
        'select b.*, c.name, c.mobile from billing b left join customer c on c.id = b.customerId where b.id=' .
        $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffData = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Billing data',
            'data' => $staffData,
        ];
    } else {
        $response = ['status' => false, 'message' => "Billing doesn't exist"];
    }

    echo json_encode($response);
});

//Get customer order history
$app->get('/get/orderhistory/{customerid}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql =
        'select b.*, c.name, c.mobile from billing b left join customer c on c.id = b.customerId where c.id=' .
        $args['customerid'] .
        ' order by b.created_at desc';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffData = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customer order history data',
            'data' => $staffData,
        ];
    } else {
        $response = [
            'status' => false,
            'message' => "Customer order history doesn't exist",
        ];
    }

    echo json_encode($response);
});

//Get staff by id
$app->get('/get/staff/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from staff where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffData = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Staff data',
            'data' => $staffData,
        ];
    } else {
        $response = ['status' => false, 'message' => "Staff doesn't exist"];
    }

    echo json_encode($response);
});

//Get customer by id
$app->get('/get/customer/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from customer where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $staffData = $stmt->fetch(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customer data',
            'data' => $staffData,
        ];
    } else {
        $response = ['status' => false, 'message' => "Customer doesn't exist"];
    }

    echo json_encode($response);
});

//delete customer by id
$app->delete('/delete/customer/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'delete from customer where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customer deleted successfully',
        ];
    } else {
        $response = ['status' => false, 'message' => "Customer doesn't exist"];
    }

    echo json_encode($response);
});

//delete staff by id
$app->delete('/delete/staff/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'delete from staff where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Staff deleted successfully',
        ];
    } else {
        $response = ['status' => false, 'message' => "Staff doesn't exist"];
    }

    echo json_encode($response);
});

//delete service by id
$app->delete('/delete/service/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'delete from services where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Service deleted successfully',
        ];
    } else {
        $response = ['status' => false, 'message' => "Service doesn't exist"];
    }

    echo json_encode($response);
});

//delete product by id
$app->delete('/delete/expense/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'delete from expenses where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Expenses deleted successfully',
        ];
    } else {
        $response = ['status' => false, 'message' => "Expenses doesn't exist"];
    }

    echo json_encode($response);
});

//delete product by id
$app->delete('/delete/product/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'delete from inventory where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Product deleted successfully',
        ];
    } else {
        $response = ['status' => false, 'message' => "Product doesn't exist"];
    }

    echo json_encode($response);
});

//delete bill by id
$app->delete('/delete/bill/{id}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'delete from billing where id=' . $args['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Billing deleted successfully',
        ];
    } else {
        $response = ['status' => false, 'message' => "Billing doesn't exist"];
    }

    echo json_encode($response);
});

$app->get('/get/billings', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql =
        'select table1.* from (select billing.*, customer.name as customer, customer.mobile as customer_mobile from billing left join customer on billing.customerId=customer.id) as table1 ORDER BY table1.created_at DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $servicesList = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Bill list',
            'data' => $servicesList,
        ];
    } else {
        $response = ['status' => false, 'message' => "Billings doesn't exist"];
    }

    echo json_encode($response);
});

//Search by customer
$app->post('/search/customer', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $body = $request->getParsedBody();
    $search = $body['query'];
    $sqlsearch =
        "select * from customer where mobile like '%" . $search . "%' limit 10";
    $stmtsearch = $db->prepare($sqlsearch);

    $stmtsearch->execute();
    $rowCount = $stmtsearch->rowCount();
    $searchData = $stmtsearch->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Customers list',
            'data' => $searchData,
        ];
    } else {
        $response = ['status' => false, 'message' => "Customer doesn't exist"];
    }

    echo json_encode($response);
});

//Search staff
$app->post('/search/staff', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $body = $request->getParsedBody();
    $search = $body['query'];
    $sqlsearch =
        "select * from staff where name like '%" . $search . "%' limit 10";
    $stmtsearch = $db->prepare($sqlsearch);
    $stmtsearch->execute();
    $rowCount = $stmtsearch->rowCount();
    $searchData = $stmtsearch->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Staffs list',
            'data' => $searchData,
        ];
    } else {
        $response = ['status' => false, 'message' => "Staff doesn't exist"];
    }

    echo json_encode($response);
});

//Search all services
$app->post('/search/service', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $body = $request->getParsedBody();
    $search = $body['query'];
    $sqlsearch = 'select * from services where name like ?';
    $params = ["%$search%"];
    $stmtsearch = $db->prepare($sqlsearch);
    $stmtsearch->execute($params);
    $rowCount = $stmtsearch->rowCount();
    $searchData = $stmtsearch->fetchAll(PDO::FETCH_OBJ);

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Services list',
            'data' => $searchData,
        ];
    } else {
        $response = ['status' => false, 'message' => "Service doesn't exist"];
    }

    echo json_encode($response);
});

function checkIfObjectExistInArray($obj, $arr)
{
    if (empty($arr)) {
        return false;
    }
    foreach ($arr as $key => $value) {
        if ($value->id === $obj->id) {
            return true;
        }
    }
}

$app->get('/get/summary', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();

    $sql = "select year(created_at) as current_year,month(created_at) as current_month,sum(totalamount) as total, sum(service_total) as service_total, sum(product_total) as product_total, sum(discount_applied) as total_discount
    from billing where year(created_at)=:year and month(created_at)=:month
    group by year(created_at),month(created_at)
    order by year(created_at),month(created_at)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam('year', date('Y'), PDO::PARAM_STR);
    $stmt->bindParam('month', date('m'), PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    $serviceSql =
        'select services from billing where year(created_at)=:year and month(created_at)=:month';
    $serviceStmt = $db->prepare($serviceSql);
    $serviceStmt->bindParam('year', date('Y'), PDO::PARAM_STR);
    $serviceStmt->bindParam('month', date('m'), PDO::PARAM_STR);
    $serviceStmt->execute();

    $summary = $stmt->fetch(PDO::FETCH_OBJ);

    $services = $serviceStmt->fetchAll(PDO::FETCH_OBJ);

    $expenseSql =
        'select sum(price) as totalexpense, year(created_at) as year, month(created_at) as month from expenses where year(created_at)=:year and month(created_at)=:month group by year(created_at),month(created_at) order by year(created_at),month(created_at)';
    $expenseStmt = $db->prepare($expenseSql);
    $expenseStmt->bindParam('year', date('Y'), PDO::PARAM_STR);
    $expenseStmt->bindParam('month', date('m'), PDO::PARAM_STR);
    $expenseStmt->execute();

    $expenses = $expenseStmt->fetch(PDO::FETCH_OBJ);

    if ($summary && $expenses) {
        $summary->total =
            (float) $summary->total - (float) $expenses->totalexpense;
    }

    if($expenses){
        $expenses->month = date(
            'F',
            mktime(0, 0, 0, $expenses->month, 10)
        );
    }

    $serArr = [];
    $empWiseServices = [];
    foreach ($services as $key => $value) {
        $serArr[] = json_decode($value->services);
    }
    $summary->current_month = date(
        'F',
        mktime(0, 0, 0, $summary->current_month, 10)
    );

    $tempEmpService = [];
    foreach ($serArr as $key => $value) {
        foreach ($value as $k1 => $v1) {
            if (is_object($v1->staffId)) {
                if (
                    !checkIfObjectExistInArray($v1->staffId, $empWiseServices)
                ) {
                    $empWiseServices[] = $v1->staffId;
                }
            }
            $tempEmpService[] = $v1;
        }
    }

    foreach ($empWiseServices as $ky => $val) {
        if ($val != null) {
            $empService = [];
            foreach ($tempEmpService as $k => $v) {
                if ($val->id === $v->staffId->id) {
                    $serObj = (object) [];
                    $serObj->service_used = $v->service_used;
                    $serObj->price =
                        (int) $v->service_used->price * (int) $v->quantity;
                    if ($v->discount) {
                        $serObj->discount = (int) $v->discount;
                    } else {
                        $serObj->discount = 0;
                    }
                    $empService[] = $serObj;
                }
            }

            if (count($empService) > 0) {
                $empWiseServices[$ky]->services = $empService;
                $totalAmount = 0;
                $totalDiscount = 0;
                foreach ($empWiseServices[$ky]->services as $k2 => $v2) {
                    $totalAmount += (int) $v2->price;
                    $totalDiscount += (int) $v2->discount;
                }
                $empWiseServices[$ky]->total_discount = $totalDiscount;
                $empWiseServices[$ky]->total_amount_earned =
                    $totalAmount - $totalDiscount;
            }
        }
    }

    if ($empWiseServices[0] == null) {
        $empWiseServices = [];
    }

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Summary',
            'expenses' => $expenses,
            'data' => $summary,
            'emp_data' => $empWiseServices,
        ];
    } else {
        $response = [
            'status' => false,
            'expenses' => $expenses,
            'message' => "Summary doesn't exist",
        ];
    }

    echo json_encode($response);
});

//Get summary by date and month
$app->get('/get/summary/{month}/{year}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = "select year(created_at) as current_year,month(created_at) as current_month,sum(totalamount) as total, sum(service_total) as service_total, sum(product_total) as product_total, sum(discount_applied) as total_discount
    from billing where year(created_at)=:year and month(created_at)=:month
    group by year(created_at),month(created_at)
    order by year(created_at),month(created_at)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam('year', $args['year'], PDO::PARAM_STR);
    $stmt->bindParam('month', $args['month'], PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $summary = $stmt->fetch(PDO::FETCH_OBJ);

    $serviceSql =
        'select services from billing where year(created_at)=:year and month(created_at)=:month';
    $serviceStmt = $db->prepare($serviceSql);
    $serviceStmt->bindParam('year', $args['year'], PDO::PARAM_STR);
    $serviceStmt->bindParam('month', $args['month'], PDO::PARAM_STR);
    $serviceStmt->execute();

    $summary->current_month = date(
        'F',
        mktime(0, 0, 0, $summary->current_month, 10)
    );

    $services = $serviceStmt->fetchAll(PDO::FETCH_OBJ);

    $expenseSql =
        'select sum(price) as totalexpense, year(created_at) as year, month(created_at) as month from expenses where year(created_at)=:year and month(created_at)=:month group by year(created_at),month(created_at) order by year(created_at),month(created_at)';
    $expenseStmt = $db->prepare($expenseSql);
    $expenseStmt->bindParam('year', $args['year'], PDO::PARAM_STR);
    $expenseStmt->bindParam('month', $args['month'], PDO::PARAM_STR);
    $expenseStmt->execute();

    $expenses = $expenseStmt->fetch(PDO::FETCH_OBJ);

    if ($summary && $expenses) {
        $summary->total =
            (float) $summary->total - (float) $expenses->totalexpense;
    }

    if($expenses){
        $expenses->month = date(
            'F',
            mktime(0, 0, 0, $expenses->month, 10)
        );
    }

    $serArr = [];
    foreach ($services as $key => $value) {
        $serArr[] = json_decode($value->services);
    }

    $tempEmpService = [];
    $empWiseServices = [];
    foreach ($serArr as $key => $value) {
        foreach ($value as $k1 => $v1) {
            if (is_object($v1->staffId)) {
                if (
                    !checkIfObjectExistInArray($v1->staffId, $empWiseServices)
                ) {
                    $empWiseServices[] = $v1->staffId;
                }
            }
            $tempEmpService[] = $v1;
        }
    }

    if (!empty($empWiseServices)) {
        foreach ($empWiseServices as $ky => $val) {
            if ($val != null) {
                $empService = [];
                foreach ($tempEmpService as $k => $v) {
                    if ($val->id === $v->staffId->id) {
                        $serObj = (object) [];
                        $serObj->service_used = $v->service_used;
                        $serObj->price =
                            (int) $v->service_used->price * (int) $v->quantity;
                        if ($v->discount) {
                            $serObj->discount = (int) $v->discount;
                        } else {
                            $serObj->discount = 0;
                        }
                        $empService[] = $serObj;
                    }
                }
                if (count($empService) > 0) {
                    $empWiseServices[$ky]->services = $empService;
                    $totalAmount = 0;
                    $totalDiscount = 0;
                    foreach ($empWiseServices[$ky]->services as $k2 => $v2) {
                        $totalAmount += (int) $v2->price;
                        $totalDiscount += (int) $v2->discount;
                    }
                    $empWiseServices[$ky]->total_discount = $totalDiscount;
                    $empWiseServices[$ky]->total_amount_earned =
                        $totalAmount - $totalDiscount;
                }
            }
        }
    }

    if ($empWiseServices[0] == null) {
        $empWiseServices = [];
    }

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Summary',
            'data' => $summary,
            'expenses' => $expenses,
            'emp_data' => $empWiseServices,
        ];
    } else {
        $response = ['status' => false, 'expenses' => $expenses, 'message' => "Summary doesn't exist"];
    }

    echo json_encode($response);
});

$app->post('/get/summary/date', function (
    Request $request,
    Response $response,
    array $args
) {
    $body = $request->getParsedBody();
    $db = getDB();

    $sql =
        'select t1.*, year(t1.date_selected) as current_year, month(t1.date_selected) as current_month from (select sum(totalamount) as total, sum(discount_applied) as total_discount, sum(service_total) as service_total, sum(product_total) as product_total, date(created_at) as date_selected from billing where date(created_at)=:date group by date(created_at) order by date(created_at) desc) t1';
    $stmt = $db->prepare($sql);
    $stmt->bindParam('date', $body['date'], PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    $summary = $stmt->fetch(PDO::FETCH_OBJ);

    $serviceSql = 'select services from billing where date(created_at)=:date';
    $serviceStmt = $db->prepare($serviceSql);
    $serviceStmt->bindParam('date', $body['date'], PDO::PARAM_STR);
    $serviceStmt->execute();

    $services = $serviceStmt->fetchAll(PDO::FETCH_OBJ);
    $expenseSql =
        'select t1.totalexpense, year(t1.date_selected) as year, month(t1.date_selected) as month from (select sum(price) as totalexpense, date(created_at) as date_selected from expenses where date(created_at)=:date group by date(created_at) order by date(created_at)) t1';
    $expenseStmt = $db->prepare($expenseSql);
    $expenseStmt->bindParam('date', $body['date'], PDO::PARAM_STR);
    $expenseStmt->execute();

    $expenses = $expenseStmt->fetch(PDO::FETCH_OBJ);

    if ($summary && $expenses) {
        $summary->total =
            (float) $summary->total - (float) $expenses->totalexpense;
    }

    if($expenses){
        $expenses->month = date(
            'F',
            mktime(0, 0, 0, $expenses->month, 10)
        );
    }
    $serArr = [];
    $empWiseServices = [];
    foreach ($services as $key => $value) {
        $serArr[] = json_decode($value->services);
    }
    $summary->current_month = date(
        'F',
        mktime(0, 0, 0, $summary->current_month, 10)
    );

    $tempEmpService = [];
    foreach ($serArr as $key => $value) {
        foreach ($value as $k1 => $v1) {
            if (is_object($v1->staffId)) {
                if (
                    !checkIfObjectExistInArray($v1->staffId, $empWiseServices)
                ) {
                    $empWiseServices[] = $v1->staffId;
                }
            }
            $tempEmpService[] = $v1;
        }
    }

    foreach ($empWiseServices as $ky => $val) {
        if ($val != null) {
            $empService = [];
            foreach ($tempEmpService as $k => $v) {
                if ($val->id === $v->staffId->id) {
                    $serObj = (object) [];
                    $serObj->service_used = $v->service_used;
                    $serObj->price =
                        (int) $v->service_used->price * (int) $v->quantity;
                    if ($v->discount) {
                        $serObj->discount = (int) $v->discount;
                    } else {
                        $serObj->discount = 0;
                    }
                    $empService[] = $serObj;
                }
            }
            if (count($empService) > 0) {
                $empWiseServices[$ky]->services = $empService;
                $totalAmount = 0;
                $totalDiscount = 0;
                foreach ($empWiseServices[$ky]->services as $k2 => $v2) {
                    $totalAmount += (int) $v2->price;
                    $totalDiscount += (int) $v2->discount;
                }
                $empWiseServices[$ky]->total_discount = $totalDiscount;
                $empWiseServices[$ky]->total_amount_earned =
                    $totalAmount - $totalDiscount;
            }
        }
    }

    if ($empWiseServices[0] == null) {
        $empWiseServices = [];
    }

    if ($rowCount > 0) {
        $response = [
            'status' => true,
            'message' => 'Summary',
            'data' => $summary,
            'expenses' => $expenses,
            'emp_data' => $empWiseServices,
        ];
    } else {
        $response = ['status' => false, 'expenses' => $expenses, 'message' => "Summary doesn't exist"];
    }

    echo json_encode($response);
});

$app->get('/get/backup', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'show tables';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $allTables = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo json_encode($allTables);
});

$app->get('/print/{billid}', function (
    Request $request,
    Response $response,
    array $args
) {
    $db = getDB();
    $sql = 'select * from billing where id=' . $args['billid'];
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $billData = $stmt->fetch(PDO::FETCH_OBJ);
    $totalAmount = 0;

    $services = json_decode($billData->services);
    $products = json_decode($billData->products);

    try {
        $connector = new WindowsPrintConnector('EPSONTM-T82ReceiptSA4');
        $printer = new Printer($connector);

        $printer->text("------------------------------------------------\n");
        $printer->text("                CURLS AND WAVES \n");
        $printer->text("                PROFESSIONAL SALON \n");
        $printer->text("            3RD FLOOR, MAHENDRA ARCADE\n");
        $printer->text("                DALTONGANJ-822101\n");
        $printer->text("              +91 6201662427\n");
        $printer->text("------------------------------------------------\n");
        $printer->text(
            ' Date:  ' . date('d-m-Y') . '   Slip No:' . $billData->id . "\n\n"
        );

        $printer->text("------------------------------------------------\n");
        $printer->text(
            '| ' .
                str_pad('S.No.', 0, '') .
                '| ' .
                str_pad('DESCRIPTION', 17, ' ', STR_PAD_BOTH) .
                ' | ' .
                str_pad('QTY', 2, ' ', STR_PAD_BOTH) .
                ' | ' .
                str_pad('PRICE', 2, ' ', STR_PAD_BOTH) .
                ' | ' .
                str_pad('AMT', 2, ' ', STR_PAD_BOTH) .
                "|\n"
        );
        $printer->text("------------------------------------------------\n");

        foreach ($services as $key => $val) {
            $printer->text(
                '  ' .
                    str_pad($key + 1, 0, '') .
                    '  ' .
                    str_pad($val->service_used->name, 25, ' ', STR_PAD_RIGHT) .
                    ' ' .
                    str_pad($val->quantity, 2, ' ', STR_PAD_BOTH) .
                    '  ' .
                    str_pad($val->service_used->price, 3, ' ', STR_PAD_BOTH) .
                    '  ' .
                    str_pad(
                        (float) $val->service_used->price * $val->quantity,
                        2,
                        ' ',
                        STR_PAD_BOTH
                    ) .
                    "\n"
            );
            $totalAmount =
                $totalAmount +
                (float) $val->service_used->price * $val->quantity;
        }

        foreach ($products as $key => $val) {
            if ($val != null) {
                $printer->text(
                    '  ' .
                        str_pad($key + 1, 0, '') .
                        '  ' .
                        str_pad(
                            $val->product_used->product_name,
                            25,
                            ' ',
                            STR_PAD_RIGHT
                        ) .
                        ' ' .
                        str_pad($val->quantity, 2, ' ', STR_PAD_BOTH) .
                        '  ' .
                        str_pad(
                            $val->product_used->price,
                            3,
                            ' ',
                            STR_PAD_BOTH
                        ) .
                        '  ' .
                        str_pad(
                            (float) $val->product_used->price * $val->quantity,
                            2,
                            ' ',
                            STR_PAD_BOTH
                        ) .
                        "\n"
                );
                $totalAmount =
                    $totalAmount +
                    (float) $val->service_used->price * $val->quantity;
            }
        }

        $printer->text("\n\n");
        $printer->text(
            ' Total Amount                       ' .
                str_pad(number_format($totalAmount, 2), 10, ' ', STR_PAD_LEFT) .
                "\n"
        );
        $printer->text(
            ' Total Discount                     ' .
                str_pad(
                    number_format($billData->discount_applied, 2),
                    10,
                    ' ',
                    STR_PAD_LEFT
                ) .
                "\n"
        );
        $printer->text(
            ' Net Amount                       ' .
                str_pad(
                    'Rs. ' . number_format($billData->totalamount, 2),
                    10,
                    ' ',
                    STR_PAD_LEFT
                ) .
                "\n"
        );
        $printer->text("------------------------------------------------\n");
        $printer->text("                 Have a nice day          \n");
        $printer->text("------------------------------------------------\n");

        $printer->cut();
        $printer->pulse();

        /* Close printer */
        $printer->close();
    } catch (Exception $e) {
        echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
    }

    // if($rowCount > 0){
    //     $response = array("status"=>true, "message"=>"Billing data", "data"=>$billData);
    // }
    // else{
    //     $response = array("status"=>false, "message"=>"Billing doesn't exist");
    // }

    // echo json_encode($response);
});

$app->run();
