<?php
include 'db_connect.php';
extract($_POST);

$data = ' schedule_id = ' . $sid . ' ';
$data .= ', name = "' . $name . '" ';
$data .= ', phone = "' . $phone . '"';
$data .= ', qty ="' . $qty . '" ';
if (!empty($bid)) {
	$data .= ', status ="' . $status . '" ';
	$chkCust = $conn->query("SELECT * FROM booked where id=" . $bid);
	$cust_row = $chkCust->fetch_assoc();
	$ref_no = $cust_row['ref_no'];
	$update = $conn->query("UPDATE booked set " . $data . " where id =" . $bid);
	if ($update) {
		$msg = "Dear " . $name . ", You have successfully reserved your place. Please use Ref No: " . $ref_no . " when you get to bus. Thank you for using RITCO Ticket Booking platform!!!";
		$data = array(
			"sender" => '+250780674459',
			"recipients" => $phone,
			"message" => $msg,
		);

		$url = "https://www.intouchsms.co.rw/api/sendsms/.json";
		$data = http_build_query($data);
		$username = "julesntare";
		$password = "ju.jo.123.its";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		echo json_encode(array('status' => 1));
	}
	exit;
}
$i = 1;
$ref = '';
while ($i == 1) {
	$ref = date('Ymd') . mt_rand(1, 9999);
	$data .= ', ref_no = "' . $ref . '" ';
	$chk = $conn->query("SELECT * FROM booked where ref_no=" . $ref)->num_rows;
	if ($chk <= 0)
		$i = 0;
}

// echo "INSERT INTO booked set ".$data;
$insert = $conn->query("INSERT INTO booked set " . $data);
if ($insert) {
	echo json_encode(array('status' => 1, 'ref' => $ref));
}