<section id="bg-bus" class="d-flex align-items-center">
    <main id="main">
        <div class="container">
            <div class="col-lg-12">
                <?php if (isset($_SESSION['login_id'])) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <button class="float-right btn btn-primary btn-sm" type="button" id="new_driver">Add New<i
                                class="fa fa-plus"></i></button>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Drivers List</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="driver-field" data-page-length="5"
                                data-order="[[ 1, &quot;asc&quot; ]]">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Mobile</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</section>
<script>
$('#new_driver').click(function() {
    uni_modal('Add New Driver', 'manage_driver.php')
})
window.load_driver = function() {
    $('#driver-field').dataTable().fnDestroy();
    $('#driver-field tbody').html('<tr><td colspan="5" class="text-center">Please wait...</td></tr>')
    $.ajax({
        url: 'load_driver.php',
        error: err => {
            console.log(err)
            alert_toast('An error occured.', 'danger');
        },
        success: function(resp) {
            if (resp) {
                if (typeof(resp) != undefined) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length > 0) {
                        $('#driver-field tbody').html('')
                        var i = 1;
                        Object.keys(resp).map(k => {
                            var tr = $('<tr></tr>');
                            tr.append('<td class="text-center">' + (i++) + '</td>')
                            tr.append('<td class="text-center">' + resp[k].name +
                                '</td>')
                            tr.append('<td class="text-center">' + resp[k].phone +
                                '</td>')
                            tr.append('<td>' + (resp[k].status == 1 ? 'Active' : 'Inactive') +
                                '</td>')
                            if ('<?php echo isset($_SESSION['login_id']) ? 1 : 0 ?>' == 1) {
                                tr.append(
                                    '<td><center><button class="btn btn-sm btn-primary edit_driver mr-2" data-id="' +
                                    resp[k].id +
                                    '">Edit</button><button class="btn btn-sm btn-danger remove_driver" data-id="' +
                                    resp[k].id + '">Delete</button></center></td>')
                            } else {
                                tr.append(
                                    '<td><center><a class="btn btn-sm btn-primary mr-2 text-white" href="#"><strong>Book Now</strong></a></center></td>'
                                )
                            }
                            $('#driver-field tbody').append(tr)

                        })

                    } else {
                        $('#driver-field tbody').html(
                            '<tr><td colspan="5" class="text-center">No data.</td></tr>')
                    }
                }
            }
        },
        complete: function() {
            $('#driver-field').dataTable()
            manage();
        }
    })
}

function manage() {
    $('body').on('click', '.edit_driver', function() {
        uni_modal('Edit New Driver', 'manage_driver.php?id=' + $(this).attr('data-id'))

    })
    $('body').on('click', '.remove_driver', function() {
        _conf('Are you sure to delete this data?', 'remove_driver', [$(this).attr('data-id')])

    })
}

function remove_driver($id = '') {
    start_load()
    $.ajax({
        url: 'delete_driver.php',
        method: 'POST',
        data: {
            id: $id
        },
        error: err => {
            console.log(err)
            alert_toast("An error occured", "danger");
            end_load()
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Data succesfully deleted", "success");
                end_load()
                $('.modal').modal('hide')
                load_driver()
            }
        }
    })
}
$(document).ready(function() {
    load_driver()
})
</script>