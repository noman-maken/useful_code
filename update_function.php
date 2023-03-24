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
        $errorMessages = array();
        foreach ($rules as $key => $rule) {
            $value = $data[$key];
            if (!empty($rule['required']) && empty($value)) {
                $errorMessages[$key] = ucfirst($key) . ' field is required.';
            } else if (!empty($rule['min']) && strlen($value) < $rule['min']) {
                $errorMessages[$key] = ucfirst($key) . ' field must contain at least ' . $rule['min'] . ' characters.';
            } else if (!empty($rule['max']) && strlen($value) > $rule['max']) {
                $errorMessages[$key] = ucfirst($key) . ' field cannot exceed ' . $rule['max'] . ' characters.';
            }
        }
        if (!empty($errorMessages)) {
            $errorMsg = implode(' ', $errorMessages);
            return $errorMsg;
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