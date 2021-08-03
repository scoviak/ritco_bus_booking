<?php
include('db_connect.php');

$sql = "SELECT * FROM contacts
			WHERE `name` LIKE '%" . $_GET['query'] . "%'";
$result = $conn->query($sql);


$json = [];
while ($row = $result->fetch_assoc()) {
    $json[] = (object) ["id" => $row['id'], "name" => $row['name']];
}


echo json_encode($json);