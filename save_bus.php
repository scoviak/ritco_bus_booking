<?php
include('db_connect.php');

extract($_POST);
$bus = strtoupper($bus_number);
$data = "bus_number = '$bus' ";
$data .= ", seats = '$seats' ";
if (empty($id)) {

	$insert = $conn->query("INSERT INTO bus set " . $data);
	if ($insert)
		echo 1;
} else {
	$update = $conn->query("UPDATE bus set " . $data . " where id =" . $id);
	if ($update)
		echo 1;
}