<?php
function checkRecordExists($tableName, $conditions) {
    global $connect;

    $sql = "SELECT COUNT(*) as count FROM $tableName WHERE ";
    $whereClauses = [];

    foreach ($conditions as $condition) {
        $column = $condition['column'];
        $operator = $condition['operator'];
        $value = $condition['value'];
        $logic = isset($condition['logic']) ? $condition['logic'] : 'AND';

        $whereClauses[] = "$column $operator ?";
        $params[] = $value;
        $sql .= "$logic ";
    }

    $sql .= implode(" $logic ", $whereClauses);

    $stmt = $connect->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
}


//usage

$conditions = [
    [
        'column' => 'email',
        'operator' => '=',
        'value' => $email
    ],
    [
        'column' => 'username',
        'operator' => '=',
        'value' => $username,
        'logic' => 'OR'
    ],
    [
        'column' => 'age',
        'operator' => '>=',
        'value' => $age,
        'logic' => 'AND'
    ]
];

if (checkRecordExists('users', $conditions)) {
    // Record exists
} else {
    // Record does not exist
}



?>