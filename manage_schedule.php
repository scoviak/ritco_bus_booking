<?php
include('db_connect.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM schedule_list where id = " . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $val) {
        $meta[$k] =  $val;
    }
}
$bus = $conn->query("SELECT * FROM bus where status = 1");
$departure = $conn->query("SELECT * FROM departures where `status` = 1");
$driver = $conn->query("SELECT * FROM drivers where `status` = 1");
?>
<div class="container-fluid">
    <form id="manage_schedule">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label for="id" class="control-label">Route</label>
                <input type="hidden" class="form-control" id="id" name="id"
                    value='<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>' required="">
                <select name="depart_id" id="route_id" class="form-control" required>
                    <option value="" <?php echo isset($meta['id']) && $meta['id'] > 0 ? '' : 'selected'  ?> disabled="">
                        Select Here</option>
                    <?php while ($row = $departure->fetch_assoc()) {
                        $df = $row['departing_from'];
                        $lt = $row['landing_to'];
                        $qry = $conn->query("SELECT
    GROUP_CONCAT(DISTINCT `name`
        SEPARATOR ' - ') AS `route`
FROM
    location
WHERE
    id IN ($df , $lt);");
                        $row2 = $qry->fetch_assoc()
                    ?>
                    <option value="<?php echo $row['id'] ?>"
                        <?php echo isset($meta['id']) && $meta['id'] == $row['id'] ? 'selected' : ''  ?>>
                        <?php echo strtoupper($row2['route']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="bus_id" class="control-label">Bus Name</label>
                <select name="bus_id" id="bus_id" class="form-control" required>
                    <option value="" <?php echo isset($meta['bus_id']) && $meta['bus_id'] > 0 ? '' : 'selected'  ?>
                        disabled="">Select Here</option>
                    <?php while ($row = $bus->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id'] ?>"
                        <?php echo isset($meta['bus_id']) && $meta['bus_id'] == $row['id'] ? 'selected' : ''  ?>>
                        <?php echo strtoupper($row['bus_number']) . ' (' . $row['seats'] . ' seats)'; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="driver_id" class="control-label">Assign Driver</label>
                <select name="driver_id" id="driver_id" class="form-control" required>
                    <option value=""
                        <?php echo isset($meta['driver_id']) && $meta['driver_id'] > 0 ? '' : 'selected'  ?> disabled>
                        Select Here</option>
                    <?php while ($row = $driver->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id'] ?>"
                        <?php echo isset($meta['driver_id']) && $meta['driver_id'] == $row['id'] ? 'selected' : ''  ?>>
                        <?php echo $row['name']  ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="depart_time" class="control-label">Departure Time</label>
                <?php
                $dt = new DateTime(); // Date object using current date and time
                $dt = $dt->format('Y-m-d H:i');
                ?>
                <input type="datetime-local" class="form-control" id="depart_time" name="depart_time"
                    value="<?php echo isset($meta['depart_time']) ? date('Y-m-d\TH:i:s', strtotime($meta['depart_time'])) : '' ?>"
                    min="<?php echo date('Y-m-d\TH:i', strtotime($dt)); ?>">
            </div>
            <div class="form-group mb-2">
                <label for="estimated_arrival_time" class="control-label">Estimated Arrival Time</label>
                <input type="datetime-local" class="form-control" id="estimated_arrival_time"
                    name="estimated_arrival_time"
                    value="<?php echo isset($meta['estimated_arrival_time']) ? date('Y-m-d\TH:i:s', strtotime($meta['estimated_arrival_time'])) : '' ?>">
            </div>
            <div class="row">
                <div class="col-md-6 form-group mb-2">
                    <label for="seats" class="control-label">No. of Seats</label>
                    <input type="number" class="form-control" id="seats" name="seats"
                        value="<?php echo isset($meta['seats']) ? $meta['seats'] : '' ?>">
                </div>
                <div class="col-md-6 form-group mb-2">
                    <label for="price" class="control-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price"
                        value="<?php echo isset($meta['price']) ? $meta['price'] : '' ?>">
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$('#manage_schedule').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: './save_schedule.php',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
            console.log(err)
            end_load()
            alert_toast('An error occured', 'danger');
        },
        success: function(resp) {
            if (resp == 1) {
                end_load()
                $('.modal').modal('hide')
                alert_toast('Data successfully saved', 'success');
                load_schedule()
            }
        }
    })
})
$('#depart_time').blur(function(e) {
    let arrival = $('#depart_time');
    let route = $('#route_id').val();
    $.ajax({
        url: './load_blured_data.php',
        method: 'POST',
        data: {
            route_id: route
        },
        error: err => {
            alert_toast('An error occured', 'danger');
        },
        success: function(resp) {
            resp = JSON.parse(resp)
            $("#price").val(resp[0].price)
        }
    })
})
$('#bus_id').blur(function(e) {
    let bus_id = $('#bus_id');
    $.ajax({
        url: './load_blured_data.php',
        method: 'POST',
        data: {
            seats_id: bus_id.val()
        },
        error: err => {
            alert_toast('An error occured', 'danger');
        },
        success: function(resp) {
            resp = JSON.parse(resp)
            $("#seats").val(resp[0].seats);
        }
    })
})
$('.datetimepicker').datetimepicker({
    format: 'Y-m-d H:i',
    startDate: '+3d',
    minDate: "+1"
});
</script>