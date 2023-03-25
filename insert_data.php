<?php
function insert($tableName, $data, $rules = [], $successMsg = 'Data inserted successfully.') {
    global $connect;

    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
    $stmt = $connect->prepare($sql);

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    // Check validation rules for specific fields
    $errors = []; // initialize empty array to store errors
    foreach ($rules as $field => $fieldRules) {
        foreach ($fieldRules as $rule => $param) {
            if ($rule === 'required') {
                if ($param && empty($data[$field])) {
                    $errors[$field] = "$field is required.";
                }
            } else if ($rule === 'min') {
                if (strlen($data[$field]) < $param) {
                    $errors[$field] = "$field must be at least $param characters long.";
                }
            } else if ($rule === 'max') {
                if (strlen($data[$field]) > $param) {
                    $errors[$field] = "$field cannot be more than $param characters long.";
                }
            }
        }
    }

    // Check if there are errors
    if (!empty($errors)) {
        return $errors; // return array of errors
    }

    try {
        $stmt->execute();
        return $successMsg;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
$register_user_data = array(
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'password' => $user_encrypted_password,
                    'role' => $role,
                    'contact' => $contact,
                    'activation_code' => $activation_code,
                    'email_status' => 0,
                    'user_status' => 0
                );

$register_user_rules = array(
                    'name' => array('required' => true, 'min' => 3, 'max' => 20),
                    'email' => array('required' => true, 'min' => 5, 'max' => 25),
                    'username' => array('required' => true, 'min' => 3, 'max' => 20)
                );

$result = array();

if(!empty($email)){
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result[] = "Invalid email";
    }
}
else{
     $result[] = "Email is required";
}


$result = insert('user', $register_user_data, $register_user_rules, 'Successfully Register');

 $alert = '';
        $alert_message = '';
        if (is_array($result)) {
            foreach ($result as $error) {
                $alert_message .= $error . '<br>';
                $alert = 'danger';
            }

        } else {
            $alert = 'success';
            $alert_message = $result;
           
        }

       $attempt_alert =  "<div class='alert alert-$alert' style='text-align:center;'>$alert_message</div>";


?>