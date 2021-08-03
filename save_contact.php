<?php
include('db_connect.php');

extract($_POST);
$data = " name = '$name' ";
$data .= ", mobile_no = '$mobile_no' ";
$data .= ", status = '$status' ";
if (empty($id)) {

	$insert = $conn->query("INSERT INTO contacts set " . $data);
	if ($insert)
		echo 1;
} else {
	$update = $conn->query("UPDATE contacts set " . $data . " where id =" . $id);
	if ($update)
		echo 1;
}