<?php
include('db_connect.php');

extract($_POST);
$data = " name = '$name' ";
$data .= ", status = '$status' ";
if (empty($id)) {

	$insert = $conn->query("INSERT INTO location set " . $data);
	if ($insert)
		echo 1;
} else {
	$update = $conn->query("UPDATE location set " . $data . " where id =" . $id);
	if ($update)
		echo 1;
}