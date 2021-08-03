<?php
include 'db_connect.php';
$date = date('Y-m-d H:i:s');
$query = $conn->query("SELECT b.*,(s.price * b.qty) as amount from booked b inner join schedule_list s on s.id = b.schedule_id where s.depart_time >=  '" . $date . "' order by date(b.date_updated) desc ");
$data = array();
while ($row = $query->fetch_assoc()) {
	$data[] = $row;
}
echo json_encode($data);