<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Product.php';

    $database = new Database();
    $db = $database->connect();

    //Get search parameter
    $search = '';
    if(isset($_GET['s']) && !empty($_GET['s'])) {
        $search = $_GET['s'];
    }

    $product = new Product($db);
    $products = $product->read($search)->fetchAll(PDO::FETCH_ASSOC);
    if(empty($products)) {
        echo json_encode(
            array('message' => 'No Products Found')
        );
    }
    else {
        foreach ($products as $ind => $product) {
            $query = "SELECT units.name, product_units.qty, product_units.is_default
                      FROM `product_units`
                      INNER JOIN units ON units.id = product_units.unit_id
                      WHERE product_units.product_id = " . $product['id'];
            $stmt = $db->prepare($query);
            $stmt->execute();
            $products[$ind]['units'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(
            $products
        );
    }