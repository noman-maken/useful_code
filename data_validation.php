<?php
function validateInput($input, $minLength = null, $maxLength = null) {
    $errors = array();
    foreach ($input as $key => $value) {
        if (empty($value)) {
            $errors[] = "Empty Field: $key is required";
        } else {
            if (!is_null($minLength) && strlen($value) < $minLength) {
                $errors[] = "$key should have minimum $minLength characters";
            }
            if (!is_null($maxLength) && strlen($value) > $maxLength) {
                $errors[] = "$key should have maximum $maxLength characters";
            }
        }
    }
    return $errors;
}

// Example usage:
$input = $_POST;
$errors = validateInput($input, 3, 50);
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
} else {
    // Input is valid, assign input values to variables
    $coupon = $input['coupon'];
    $coupon_discount = $input['coupon_discount'];
    
    // Do something with the input values
}




?>