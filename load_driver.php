<?php
include 'db_connect.php';

$qry = $conn->query("SELECT * FROM drivers where status = 1");
$data = array();
while ($row = $qry->fetch_assoc()) {
	$data[] = $row;
}
echo json_encode($data);