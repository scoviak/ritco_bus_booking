<?php
include 'db_connect.php';
$date = date('Y-m-d H:i:s');
$qry = $conn->query("SELECT sl.*, d.departing_from, d.landing_to FROM schedule_list AS sl join departures AS d ON sl.depart_id = d.id where sl.depart_time >=  '" . $date . "' AND sl.`status` = 1");
$data = array();
while ($row = $qry->fetch_assoc()) {
	$qry1 = $conn->query("SELECT * FROM bus where id = '" . $row['bus_id'] . "' and status = 1");
	$bus_row = $qry1->fetch_assoc();
	$row['bus_number'] = $bus_row['bus_number'];
	$qry1 = $conn->query("SELECT * FROM departures where id = '" . $row['depart_id'] . "' and status = 1");
	$depart_row = $qry1->fetch_assoc();
	$df = $depart_row['departing_from'];
	$lt = $depart_row['landing_to'];
	$qry1 = $conn->query("SELECT * FROM drivers where id = '" . $row['driver_id'] . "' and status = 1");
	$driver_row = $qry1->fetch_assoc();
	$row['driver_name'] = $driver_row['name'];
	$row['driver_phone'] = $driver_row['phone'];
	$qry1 = $conn->query("SELECT
    GROUP_CONCAT(DISTINCT `name`
        SEPARATOR ' - ') AS `route`
FROM
    location
WHERE
    id IN ($df , $lt);");
	$location_row = $qry1->fetch_assoc();
	$row['route_name'] = $location_row['route'];
	$qry1 = $conn->query("SELECT * FROM users where id = '" . $row['user_id'] . "' and status = 1");
	$users_row = $qry1->fetch_assoc();
	$row['user_name'] = $users_row['name'];
	$qry1 = $conn->query("SELECT sum(qty) as total_booked FROM booked where schedule_id = '" . $row['id'] . "' and status = 1");
	$booked_row = $qry1->fetch_assoc();
	if ($booked_row['total_booked'] == null) {
		$row['total_booked'] = $row['seats'];
	} else {
		$row['total_booked'] = $row['seats'] - $booked_row['total_booked'];
	}
	$row['depart_time'] = date('Y-m-d H:i', strtotime($row['depart_time']));
	$row['arrival_time'] = strtotime($row['estimated_arrival_time']) < 1 || empty($row['estimated_arrival_time']) ? 'N/A' : date('Y-m-d H:i', strtotime($row['estimated_arrival_time']));
	$data[] = $row;
}
echo json_encode($data);