<?php

function select($tableName, $columns = '*', $where = '1', $orderBy = '', $limit = '') {
    global $connect;
    $sql = "SELECT $columns FROM $tableName WHERE $where $orderBy $limit";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

//usage
// Select all rows from the 'users' table
$rows = select('users');

// Loop through the rows and output HTML
foreach ($rows as $row) {
    echo '<div class="user">';
    echo '<h2>' . $row['name'] . '</h2>';
    echo '<p>Email: ' . $row['email'] . '</p>';
    echo '<p>Phone: ' . $row['phone'] . '</p>';
    echo '</div>';
}

?>