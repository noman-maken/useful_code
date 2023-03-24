<?php

function insertIntoTable($tableName, $data) {
    // Convert data array keys to comma separated column names
    $columns = implode(", ", array_keys($data));
    
    // Build the placeholders for the values
    $placeholders = ":" . implode(", :", array_keys($data));
    
    // Build the SQL query
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
    
    // Prepare the statement
    $stmt = $connect->prepare($sql);
    
    // Bind the parameters
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    // Execute the statement
    try {
        $stmt->execute();
        return "Data inserted successfully.";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


//Usage

$data = array(
    'coupon' => 'COUPONCODE',
    'status' => 'Pending',
    'coupon_discount' => 20.00
);

$result = insertIntoTable('coupon', $data);

echo $result;

?>