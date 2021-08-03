<?php
include 'db_connect.php';

$qry = $conn->query("SELECT *, DATE_FORMAT(depart_time, '%h:%i %p') as date_t FROM depart_period");
$data = array();
while ($row = $qry->fetch_assoc()) {
    if (empty($row['depart_every'])) {
        $row['period'] = $row['date_t'];
    } else {
        $row['period'] = $row['depart_every'] . ' hour(s)';
    }
    $data[] = $row;
}
echo json_encode($data);