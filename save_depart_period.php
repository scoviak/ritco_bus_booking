<?php
include('db_connect.php');

extract($_POST);

if (empty($depart_time)) {
	$data = "depart_every = '$depart_every' ";
} else {
	$data = " depart_time = '$depart_time' ";
}
if (empty($id)) {
	$insert = $conn->query("INSERT INTO depart_period set " . $data);
	if ($insert)
		echo 1;
} else {
	$update = $conn->query("UPDATE depart_period set " . $data . " where id =" . $id);
	if ($update)
		echo 1;
}