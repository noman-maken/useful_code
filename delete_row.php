<?php
function delete($tableName, $where, $successMsg = 'Data deleted successfully.') {
    global $connect;
    $sql = "DELETE FROM $tableName WHERE $where";
    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute();
        return $successMsg;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


//usage

echo delete('users', 'id = 1');

?>