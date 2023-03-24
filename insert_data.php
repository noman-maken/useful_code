<?php

function insert($tableName, $data, $successMsg = 'Data inserted successfully.') {
    global $connect;
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
    $stmt = $connect->prepare($sql);
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    try {
        $stmt->execute();
        return $successMsg;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
echo insert('coupon', ['coupon' => 'DISCOUNT10', 'status' => 'Pending', 'coupon_discount' => 10]);
// Output: Data inserted successfully.

echo insert('coupon', ['coupon' => 'DISCOUNT20', 'status' => 'Approved', 'coupon_discount' => 20], 'Coupon inserted successfully and approved.');
// Output: Coupon inserted successfully and approved.

?>