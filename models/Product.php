<?php
class Product
{
    private $conn;
    private $table = 'products';
    private $unit_table = 'units';
    private $relation_table = 'products_units';

    public $id;
    public $name;
    public $price;
    public $units;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($search = '')
    {
        $query = "SELECT  id,
                          name,
                          price
                  FROM $this->table";
        if(!empty($search)) {
            $query .= ' WHERE name LIKE "%'. $search .'%"';
        }
//        $query .= "ORDER BY name DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

}