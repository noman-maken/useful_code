<?php
function insert($tableName, $data, , $rules = [], $successMsg = 'Data inserted successfully.') {
    global $connect;

    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
    $stmt = $connect->prepare($sql);

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    // Check validation rules for specific fields
    foreach ($rules as $field => $fieldRules) {
        foreach ($fieldRules as $rule => $param) {
            switch ($rule) {
                case 'required':
                    if ($param && empty($data[$field])) {
                        return "Error: $field is required.";
                    }
                    break;
                case 'min':
                    if (strlen($data[$field]) < $param) {
                        return "Error: $field must be at least $param characters long.";
                    }
                    break;
                case 'max':
                    if (strlen($data[$field]) > $param) {
                        return "Error: $field cannot be more than $param characters long.";
                    }
                    break;
            }
        }
    }

    try {
        $stmt->execute();
        return $successMsg;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
$data = [
    'coupon' => 'DISCOUNT10',
    'status' => 'Pending',
    'coupon_discount' => 10
];
$rules = [
    'coupon' => [
        'required' => true,
        'min' => 5
    ],
    'status' => [
        'required' => true
    ],
    'coupon_discount' => [
        'required' => true,
        'min' => 1,
        'max' => 2
    ]
];

echo insert('coupon', $data, $rules, 'Data inserted successfully.');


?>