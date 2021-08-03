<?php
session_start();
include('db_connect.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $qry = $conn->query(
        "SELECT sl.*, d.departing_from, d.landing_to FROM schedule_list AS sl join departures AS d ON sl.depart_id = d.id where sl.id = " . $_GET['id'] . " and sl.`status` = 1"
    )->fetch_array();
    foreach ($qry as $k => $val) {
        $meta[$k] =  $val;
    }
    $qry1 = $conn->query("SELECT * FROM bus where id = '" . $meta['bus_id'] . "' and status = 1");
    $bus = $qry1->fetch_assoc();
    $qry1 = $conn->query("SELECT * FROM departures where id = '" . $meta['depart_id'] . "' and status = 1");
    $depart_row = $qry1->fetch_assoc();
    $df = $depart_row['departing_from'];
    $lt = $depart_row['landing_to'];
    $qry1 = $conn->query("SELECT * FROM drivers where id = '" . $meta['driver_id'] . "' and status = 1");
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
    $qry1 = $conn->query("SELECT * FROM users where id = '" . $meta['user_id'] . "' and status = 1");
    $users_row = $qry1->fetch_assoc();
    $row['user_name'] = $users_row['name'];
    $count = $conn->query("SELECT SUM(qty) as sum from booked where schedule_id =" . $meta['id'])->fetch_array()['sum'];
}

if (isset($_SESSION['login_id']) && isset($_GET['bid'])) {
    $booked = $conn->query("SELECT * FROM booked where id=" . $_GET['bid'])->fetch_array();
    foreach ($booked as $k => $val) {
        $bmeta[$k] =  $val;
    }
}
?>
<div class="container-fluid">
    <form id="manage_book">
        <div class="col-md-12">
            <p><b>Bus:</b> <?php echo strtoupper($bus['bus_number']) ?></p>
            <p><b>Route:</b> <?php echo strtoupper($location_row['route']) ?></p>
            <p><b>Departure Time</b>: <?php echo date('Y-m-d H:i A', strtotime($meta['depart_time'])) ?></p>
            <!-- <p><b>Estimated Time of Arrival:</b>
                <?php
                //echo strtotime($meta['estimated_arrival_time']) > 0 || !empty($meta['estimated_arrival_time']) ? date('Y-m-d H:i A', strtotime($meta['estimated_arrival_time'])) : "N/A" 
                ?>
            </p> -->
            <?php if (($count < $meta['seats']) || isset($_SESSION['login_id'])) : ?>
            <input type="hidden" class="form-control" id="sid" name="sid"
                value='<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>' required="">
            <input type="hidden" class="form-control" id="sid" name="bid"
                value='<?php echo isset($_GET['bid']) ? $_GET['bid'] : '' ?>' required="">

            <div class="form-group mb-2">
                <label for="name" class="control-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo isset($bmeta['name']) ? $bmeta['name'] : '' ?>">
            </div>
            <div class="form-group mb-2">
                <label for="phone" class="control-label">Phone</label>
                <input type="number" class="form-control" id="phone" name="phone"
                    value="<?php echo isset($bmeta['phone']) ? $bmeta['phone'] : '' ?>">
            </div>
            <div class="form-group mb-2">
                <label for="qty" class="control-label">Quantity</label>
                <input type="number" max="4" class="form-control" id="qty" name="qty"
                    value="<?php echo isset($bmeta['qty']) ? $bmeta['qty'] : '' ?>">
            </div>
            <?php if (isset($_SESSION['login_id'])) : ?>
            <div class="form-group mb-2">
                <label for="qty" class="control-label">Status</label>
                <select class="form-control" id="status" name="status"
                    value="<?php echo isset($bmeta['qty']) ? $bmeta['qty'] : '' ?>">
                    <option value="1" <?php echo isset($bmeta['status']) && $bmeta['status'] == 1 ? "selected" : '' ?>>
                        Paid</option>
                    <option value="0" <?php echo isset($bmeta['status']) && $bmeta['status'] == 0 ? "selected" : '' ?>>
                        Unpaid</option>
                </select>
            </div>
            <?php endif; ?>
            <?php else : ?>
            <h3>No Available seat</h3>
            <style>
            .uni_modal .modal-footer {
                display: none;
            }
            </style>
            <?php endif; ?>
        </div>
    </form>
</div>


<script>
$('#manage_book').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: './book_now.php',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
            console.log(err)
            end_load()
            alert_toast('An error occured', 'danger');
        },
        success: function(resp) {
            resp = JSON.parse(resp)
            if (resp.status == 1) {
                end_load()
                $('.modal').modal('hide')
                alert_toast('Payment Confirmed', 'success');
                if ('<?php echo !isset($_SESSION['login_id']) ?>' == 1) {
                    $('#book_modal .modal-body').html('<div class="text-center"><p><strong><h3>' +
                        resp.ref +
                        '</h3></strong></p><small>Reference Number</small><br/><small>Copy or Capture your Reference number </small></div>'
                    )
                    $('#book_modal').modal('show')
                } else {
                    load_booked();
                }
            }
        }
    })
})
$('.datetimepicker').datetimepicker({
    format: 'Y/m/d H:i',
    startDate: '+3d'
});
</script>