<?php
include('db_connect.php');
$bus = $conn->query("SELECT * FROM bus where status = 1");
$date = date('Y-m-d H:i:s');
$departure = $conn->query("SELECT * FROM departures where `status` = 1");
?>
<div class="container-fluid">
    <form id="find_schedule">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label for="id" class="control-label">Select Departure</label>
                <select name="depart_id" id="route_id" class="form-control" required>
                    <option value="" <?php echo isset($meta['id']) && $meta['id'] > 0 ? '' : 'selected'  ?> disabled="">
                        Select Here</option>
                    <?php while ($row = $departure->fetch_assoc()) {
                        $df = $row['departing_from'];
                        $lt = $row['landing_to'];
                        $qry = $conn->query("SELECT
    GROUP_CONCAT(DISTINCT `name`
    ORDER BY FIELD(id, $df, $lt)
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
                <?php
                $dt = new DateTime(); // Date object using current date and time
                $dt = $dt->format('Y-m-d H:i');
                ?>
                <label for="departure_time" class="control-label">Departure Time</label>
                <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" value=""
                    autocomplete="off" min="<?php echo date('Y-m-d\TH:i', strtotime($dt)); ?>">
            </div>
        </div>
    </form>
</div>
<script>
$('#find_schedule').submit(function(e) {
    e.preventDefault()
    var get = '';
    $('input,select').each(function() {
        get += '&' + $(this).attr('name') + '=' + $(this).val();
    })
    location.replace("index.php?page=scheduled_list" + get)

})
$('.datetimepicker').datepicker({
    format: 'Y-m-d H:i',
    startDate: '+1d'
});
</script>