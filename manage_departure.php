<?php
include('db_connect.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM departures where id = " . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $val) {
        $meta[$k] =  $val;
    }
}
?>
<div class="container-fluid">
    <form id="manage_departure">
        <div class="col-md-12">
            <?php
            $departure = $conn->query("SELECT * FROM location where status = 1");
            $departperiod = $conn->query("SELECT *, DATE_FORMAT(depart_time, '%h:%i %p') as date_t FROM depart_period order by depart_every");
            ?>
            <div class="form-group mb-2">
                <input type="hidden" class="form-control" id="id" name="id" required=""
                    value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
                <label for="departing_from" class="control-label">Departing From</label>
                <select name="departing_from" id="departing_from" class="form-control" required>
                    <option value=""
                        <?php echo isset($meta['departing_from']) && $meta['departing_from'] > 0 ? '' : 'selected'  ?>
                        disabled="">Select Here</option>
                    <?php while ($row2 = $departure->fetch_assoc()) { ?>
                    <option value="<?php echo $row2['id'] ?>"
                        <?php echo isset($meta['departing_from']) && $meta['departing_from'] == $row2['id'] ? 'selected' : ''  ?>>
                        <?php echo $row2['name']  ?></option>
                    <?php } ?>
                </select>
            </div>

            <?php
            $departure = $conn->query("SELECT * FROM location where status = 1");
            ?>
            <div class="form-group mb-2">
                <label for="landing_to" class="control-label">Landing To</label>
                <select name="landing_to" id="landing_to" class="form-control" required>
                    <option value=""
                        <?php echo isset($meta['landing_to']) && $meta['landing_to'] > 0 ? '' : 'selected'  ?>
                        disabled="">Select Here</option>
                    <?php while ($row2 = $departure->fetch_assoc()) {
                    ?>
                    <option value="<?php echo $row2['id'] ?>"
                        <?php echo isset($meta['landing_to']) && $meta['landing_to'] == $row2['id'] ? 'selected' : ''  ?>>
                        <?php echo $row2['name']  ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="depart_time" class="control-label">Departure time(every)</label>
                <select name="depart_time" id="depart_time" class="form-control" required>
                    <option value=""
                        <?php echo isset($meta['depart_time']) && $meta['depart_time'] > 0 ? '' : 'selected'  ?>
                        disabled="">Select Here</option>
                    <?php while ($row2 = $departperiod->fetch_assoc()) {
                    ?>
                    <option value="<?php echo $row2['id'] ?>"
                        <?php echo isset($meta['depart_time']) && $meta['depart_time'] == $row2['id'] ? 'selected' : ''  ?>>
                        <?php echo empty($row2['depart_time']) ? $row2['depart_every'] . ' hour(s)' :  $row2['date_t'] ?>
                    </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="price" class="control-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" required=""
                    value="<?php echo isset($meta['price']) ? $meta['price'] : '' ?>">
            </div>
        </div>
    </form>
</div>
<script>
$('#manage_departure').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: './save_departure.php',
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
                load_departure()
            }
        }
    })
})
</script>