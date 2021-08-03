<?php
include('db_connect.php');

extract($_POST);
$data = " departing_from = '$departing_from' ";
$data .= ", landing_to = '$landing_to' ";
$data .= ", depart_time = '$depart_time' ";
$data .= ", price = '$price' ";
if (empty($id)) {

	$insert = $conn->query("INSERT INTO departures set " . $data);
	if ($insert)
		echo 1;
} else {
	$update = $conn->query("UPDATE departures set " . $data . " where id =" . $id);
	if ($update)
		echo 1;
}