<?php
include 'db_connect.php';

$qry = $conn->query("SELECT * FROM contacts order by `name`");
$data = array();
while ($row = $qry->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);