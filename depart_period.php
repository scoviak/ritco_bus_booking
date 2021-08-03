<section id="bg-bus" class="d-flex align-items-center">
    <main id="main">
        <div class="container">
            <div class="col-lg-12">
                <?php if (isset($_SESSION['login_id'])) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <button class="float-right btn btn-primary btn-sm" type="button" id="new_depart_period">Add
                            New <i class="fa fa-plus"></i></button>
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
                                <h2>Depart Period List</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="depart_period-field"
                                data-page-length="5">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Period</th>
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
$('#new_depart_period').click(function() {
    uni_modal('Add New Period', 'manage_depart_period.php')
})
window.load_depart_period = function() {
    $('#depart_period-field').dataTable().fnDestroy();
    $('#depart_period-field tbody').html('<tr><td colspan="5" class="text-center">Please wait...</td></tr>')
    $.ajax({
        url: 'load_depart_period.php',
        error: err => {
            console.log(err)
            alert_toast('An error occured.', 'danger');
        },
        success: function(resp) {
            if (resp) {
                if (typeof(resp) != undefined) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length > 0) {
                        $('#depart_period-field tbody').html('')
                        var i = 1;
                        Object.keys(resp).map(k => {
                            var tr = $('<tr></tr>');
                            tr.append('<td class="text-center">' + (i++) + '</td>')
                            tr.append('<td class="text-center">Every <b>' + resp[k].period +
                                '</b></td>')
                            tr.append(
                                '<td><center><button class="btn btn-sm btn-primary edit_depart_period mr-2" data-id="' +
                                resp[k].id +
                                '">Edit</button><button class="btn btn-sm btn-danger remove_depart_period" data-id="' +
                                resp[k].id + '">Delete</button></center></td>')
                            $('#depart_period-field tbody').append(tr)

                        })

                    } else {
                        $('#depart_period-field tbody').html(
                            '<tr><td colspan="5" class="text-center">No data.</td></tr>')
                    }
                }
            }
        },
        complete: function() {
            $('#depart_period-field').dataTable()
            manage();
        }
    })
}

function manage() {
    $('body').on('click', '.edit_depart_period', function() {
        uni_modal('Edit New depart_period', 'manage_depart_period.php?id=' + $(this).attr('data-id'))

    })
    $('body').on('click', '.remove_depart_period', function() {
        _conf('Are you sure to delete this data?', 'remove_depart_period', [$(this).attr('data-id')])

    })
}

function remove_depart_period($id = '') {
    start_load()
    $.ajax({
        url: 'delete_depart_period.php',
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
                load_depart_period()
            }
        }
    })
}
$(document).ready(function() {
    load_depart_period()
})
</script>