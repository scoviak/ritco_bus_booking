<?php
include('db_connect.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM depart_period where id = " . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $val) {
        $meta[$k] =  $val;
    }
}
?>
<div class="container-fluid">
    <form id="manage_depart_period">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label for="depart_time" class="control-label">Depart At</label>
                <input type="hidden" class="form-control" id="id" name="id"
                    value='<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>' required="">
                <input type="time" class="form-control" id="depart_time" name="depart_time" required
                    value="<?php echo isset($meta['depart_time']) ? $meta['depart_time'] : '' ?>">
            </div>
            <div class="form-group mb-2 text-center">
                <h1>OR</h1>
            </div>
            <div class="form-group mb-2">
                <label for="depart_every" class="control-label">Depart Every</label>
                <input type="number" class="form-control" id="depart_every" name="depart_every" required
                    value="<?php echo isset($meta['depart_every']) ? $meta['depart_every'] : '' ?>">
            </div>
        </div>
    </form>
</div>
<script>
$('#manage_depart_period').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: './save_depart_period.php',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
            end_load()
            alert_toast('An error occured', 'danger');
        },
        success: function(resp) {
            if (resp == 1) {
                end_load()
                $('.modal').modal('hide')
                alert_toast('Period successfully saved', 'success');
                load_location()
            }
        }
    })
})
</script>