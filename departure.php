<section id="bg-bus" class="d-flex align-items-center">
    <main id="main">
        <div class="container">
            <div class="col-lg-12">
                <?php if (isset($_SESSION['login_id'])) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <button class="float-right btn btn-primary btn-sm" type="button" id="new_departure">Add New <i
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
                                <h2>Departure List</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="departure-field" data-page-length="5">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Route Name</th>
                                        <th class="text-center">Departure Time</th>
                                        <th class="text-center">Price</th>
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
$('#new_departure').click(function() {
    uni_modal('Add New Departure', 'manage_departure.php')
})
window.load_departure = function() {
    $('#departure-field').dataTable().fnDestroy();
    $('#departure-field tbody').html('<tr><td colspan="5" class="text-center">Please wait...</td></tr>')
    $.ajax({
        url: 'load_departure.php',
        error: err => {
            console.log(err)
            alert_toast('An error occured.', 'danger');
        },
        success: function(resp) {
            if (resp) {
                if (typeof(resp) != undefined) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length > 0) {
                        $('#departure-field tbody').html('')
                        var i = 1;
                        Object.keys(resp).map(k => {
                            var tr = $('<tr></tr>');
                            tr.append('<td class="text-center">' + (i++) +
                                '</td>')
                            tr.append('<td class="text-center">' + resp[k]
                                .route + '</td>')
                            tr.append('<td>Every <b>' + resp[k]
                                .period +
                                '</b></td>')
                            tr.append('<td>' + resp[k].price + ' Rwf</td>')
                            if ('<?php echo isset($_SESSION['login_id']) ? 1 : 0 ?>' ==
                                1) {
                                tr.append(
                                    '<td><center><button class="btn btn-sm btn-primary edit_departure mr-2" data-id="' +
                                    resp[k].id +
                                    '">Edit</button><button class="btn btn-sm btn-danger remove_departure" data-id="' +
                                    resp[k].id +
                                    '">Delete</button></center></td>')
                            } else {
                                tr.append(
                                    '<td><center><a class="btn btn-sm btn-primary mr-2 text-white" href="#"><strong>Book Now</strong></a></center></td>'
                                )
                            }
                            $('#departure-field tbody').append(tr)

                        })

                    } else {
                        $('#departure-field tbody').html(
                            '<tr><td colspan="5" class="text-center">No data.</td></tr>')
                    }
                }
            }
        },
        complete: function() {
            $('#departure-field').dataTable()
            manage();
        }
    })
}

function manage() {
    $('body').on('click', '.edit_departure', function() {
        uni_modal('Edit departure', 'manage_departure.php?id=' + $(this).attr('data-id'))

    })
    $('body').on('click', '.remove_departure', function() {
        _conf('Are you sure to delete this data?', 'remove_departure', [$(this).attr('data-id')])

    })
}

function remove_departure($id = '') {
    start_load()
    $.ajax({
        url: 'delete_departure.php',
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
                load_departure()
            }
        }
    })
}
$(document).ready(function() {
    load_departure()
})
</script>