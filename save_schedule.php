<?php
session_start();
include('db_connect.php');

extract($_POST);
$data = " bus_id = '$bus_id' ";
$data .= ", depart_id = '$depart_id' ";
$data .= ", driver_id = '$driver_id' ";
$data .= ", depart_time = '$depart_time' ";
$data .= ", estimated_arrival_time = '$estimated_arrival_time' ";
$data .= ", seats = '$seats' ";
$data .= ", price = '$price' ";
$data .= ", user_id = '" . $_SESSION['login_id'] . "' ";
if (empty($id)) {

	$insert = $conn->query("INSERT INTO schedule_list set " . $data);
	if ($insert)
		echo 1;
} else {
	$update = $conn->query("UPDATE schedule_list set " . $data . " where id =" . $id);
	if ($update)
		echo 1;
}