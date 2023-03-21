<?php
function count_records($table, $condition_type = "AND", ...$conditions) {
    $sql = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

    $query = "SELECT COUNT(*) FROM $table";

    if (!empty($conditions)) {
        $query .= " WHERE ";
        $i = 0;
        foreach ($conditions as $condition) {
            if (is_array($condition)) {
                $j = 0;
                foreach ($condition as $field => $value) {
                    $query .= "$field = '$value'";
                    if ($j < count($condition) - 1) {
                        $query .= " $condition_type ";
                    }
                    $j++;
                }
            } else {
                $query .= $condition;
            }
            if ($i < count($conditions) - 1) {
                $query .= " $condition_type ";
            }
            $i++;
        }
    }

    $result = $sql->query($query);
    $count = $result->fetch_row()[0];

    $sql->close();

    return $count;
}

count_records('my_table', ['field1' => 'value1'], ['field2' => 'value2']);

count_records('my_table', 'OR', ['field1' => 'value1'], ['field2' => 'value2']);

count_records('my_table', 'AND', ['field1' => 'value1'], 'OR', ['field2' => 'value2']);


?>