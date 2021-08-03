<?php
include 'db_connect.php';
if (isset($_GET['df']) && !empty($_GET['df'])) {
	$df = $_GET['df'];
	$lt = $_GET['lt'];
	$qry = $conn->query("SELECT
    GROUP_CONCAT(DISTINCT `name`
	ORDER BY FIELD(id, $df, $lt)
        SEPARATOR ' - ') AS `route`
FROM
    location
WHERE
    id IN ($df , $lt);");
} else {
	$qry = $conn->query("SELECT * FROM location where status = 1");
}

$data = array();
while ($row = $qry->fetch_assoc()) {
	$data[] = $row;
}
echo json_encode($data);