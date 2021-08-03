<?php
include('db_connect.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM contacts where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $val) {
		$meta[$k] =  $val;
	}
}
?>
<div class="container-fluid">
    <form id="manage_contact">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label for="name" class="control-label">Name</label>
                <input type="hidden" class="form-control" id="id" name="id"
                    value='<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>' required="">
                <input type="text" class="form-control" id="name" name="name" required
                    value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>">
            </div>
            <div class="form-group mb-2">
                <label for="mobile_no" class="control-label">Mobile Number</label>
                <input type="number" class="form-control" id="mobile_no" name="mobile_no" required
                    value="<?php echo isset($meta['mobile_no']) ? $meta['mobile_no'] : '' ?>">
            </div>
            <div class="form-group mb-2">
                <label for="mobile_no" class="control-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <?php
					if (isset($meta['status'])) {
					?>
                    <option value="<?php echo $meta['status'] == 1 ? '1' : '0'  ?>" selected>
                        <?php echo $meta['status'] == 1 ? 'Active' : 'Inactive'  ?>
                    </option>
                    <option value="<?php echo $meta['status'] == 1 ? '0' : '1'  ?>">
                        <?php echo $meta['status'] == 1 ? 'Inactive' : 'Active'  ?></option>
                    <?php
					} else {
					?>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                    <?php
					}
					?>
                </select>
            </div>
        </div>
    </form>
</div>
<script>
$('#manage_contact').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: './save_contact.php',
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
                alert_toast('Contact successfully saved', 'success');
                load_location()
            }
        }
    })
})
</script>