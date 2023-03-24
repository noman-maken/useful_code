<?php


function update($tableName, $data, $where, $rules = array(), $successMsg = 'Data updated successfully.') {
    global $connect;
    $setStr = '';
    foreach ($data as $key => $value) {
        $setStr .= "$key = :$key, ";
    }
    $setStr = rtrim($setStr, ', ');
    $sql = "UPDATE $tableName SET $setStr WHERE $where";
    $stmt = $connect->prepare($sql);
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    if (!empty($rules)) {
        foreach ($rules as $key => $rule) {
            $value = $data[$key];
            if (!empty($rule['required']) && empty($value)) {
                return $rule['required'];
            }
            if (!empty($rule['min']) && strlen($value) < $rule['min']) {
                return $rule['min'];
            }
            if (!empty($rule['max']) && strlen($value) > $rule['max']) {
                return $rule['max'];
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



//usage

// Update the 'users' table to set the name and email of user with id=1
// Validation rules are not provided
$updateData = ['name' => 'John Doe', 'email' => 'johndoe@example.com'];
$updateWhere = 'id = 1';
echo update('users', $updateData, $updateWhere);

// Update the 'users' table to set the name and email of user with id=1
// Validation rules are provided
$updateData = ['name' => 'Jane Doe', 'email' => 'janedoe@example.com'];
$updateWhere = 'id = 1';
$updateRules = [
    'name' => ['required' => 'Name is required', 'min' => 2, 'max' => 50],
    'email' => ['required' => 'Email is required', 'min' => 6, 'max' => 100],
];
echo update('users', $updateData, $updateWhere, $updateRules);

?>