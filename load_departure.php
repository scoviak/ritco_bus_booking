<?php
include 'db_connect.php';

$qry = $conn->query("SELECT * FROM departures where status = 1");
$data = array();
while ($row = $qry->fetch_assoc()) {
	$df = $row['departing_from'];
	$lt = $row['landing_to'];
	$qry1 = $conn->query("SELECT
    GROUP_CONCAT(DISTINCT `name`
	ORDER BY FIELD(id, $df, $lt)
        SEPARATOR ' - ') AS `route`
FROM
    location
WHERE
    id IN ($df , $lt);");
	$row1 = $qry1->fetch_assoc();
	$row['route'] = $row1['route'];

	$qry2 = $conn->query("SELECT *, DATE_FORMAT(depart_time, '%h:%i %p') as date_t FROM depart_period where id='" . $row['depart_time'] . "'");
	$row2 = $qry2->fetch_assoc();
	if (empty($row2['depart_every'])) {
		$row['period'] = $row2['date_t'];
	} else {
		$row['period'] = $row2['depart_every'] . ' hour(s)';
	}
	$data[] = $row;
}
echo json_encode($data);