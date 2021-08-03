<?php
include 'db_connect.php';

if (isset($_POST['route_id'])) {
	$route_id = $_POST['route_id'];
	$qry = $conn->query("SELECT * FROM departures where id = '$route_id' and status = 1");
}
if (isset($_POST['seats_id'])) {
	$seats_id = $_POST['seats_id'];
	$qry = $conn->query("SELECT * FROM bus where id = '$seats_id' and status = 1");
}
$data = array();
while ($row = $qry->fetch_assoc()) {
	$data[] = $row;
}
echo json_encode($data);