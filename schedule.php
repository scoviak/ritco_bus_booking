<style>
td {
    font-size: 12px;
}

.btn {
    font-size: 10px !important;
}
</style>

<section id="bg-bus" class="d-flex align-items-center">
    <main id="main">
        <div class="container-fluid">
            <div class="col-lg-12">
                <?php if (isset($_SESSION['login_id'])) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <button class="float-right btn btn-primary btn-sm" type="button" id="new_schedule">Add New <i
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
                                <h2>Active Scheduled Departures</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="schedule-field" data-page-length="5">
                                <colgroup>
                                    <col width="5%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Route</th>
                                        <th class="text-center">Bus Plate</th>
                                        <th class="text-center">Assigned Driver</th>
                                        <th class="text-center">Departure Time</th>
                                        <th class="text-center">Arrival Time</th>
                                        <th class="text-center">Available No. Seats</th>
                                        <th class="text-center">Price</th>
                                        <?php echo isset($_SESSION['login_id']) ? '
                                        <th class="text-center">Scheduled By</th>' : null ?>
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
$('#new_schedule').click(function() {
    uni_modal('Add New Schedule', 'manage_schedule.php')
})
window.load_schedule = function() {
    $('#schedule-field').dataTable().fnDestroy();
    $('#schedule-field tbody').html('<tr><td colspan="7" class="text-center">Please wait...</td></tr>')
    $.ajax({
        url: 'load_schedule.php',
        error: err => {
            console.log(err)
            alert_toast('An error occured.', 'danger');
        },
        success: function(resp) {
            if (resp) {
                if (typeof(resp) != undefined) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length > 0) {
                        $('#schedule-field tbody').html('')
                        var i = 1;
                        Object.keys(resp).map(k => {
                            var tr = $('<tr></tr>');
                            tr.append('<td class="text-center">' + (i++) + '</td>')
                            tr.append('<td class="">' + resp[k].route_name + '</td>')
                            tr.append('<td class="">' + resp[k].bus_number + '</td>')
                            tr.append('<td class="">' + resp[k].driver_name + ' ( ' + resp[k]
                                .driver_phone + ')</td>')
                            tr.append('<td>' + resp[k].depart_time + '</td>')
                            tr.append('<td>' + resp[k].arrival_time + '</td>')
                            tr.append('<td>' + resp[k].total_booked + '</td>')
                            tr.append('<td>' + resp[k].price +
                                '</td>')
                            if (
                                '<?php echo isset($_SESSION['login_id']) ? 1 : 0 ?>' ==
                                1) {
                                tr.append('<td>' + resp[k].user_name + '</td>')
                            }
                            if ('<?php echo isset($_SESSION['login_id']) ? 1 : 0 ?>' ==
                                1) {
                                tr.append(
                                    '<td><center><button class="btn btn-sm btn-primary edit_schedule mr-2" data-id="' +
                                    resp[k].id +
                                    '">Edit</button><button class="btn btn-sm btn-danger remove_schedule" data-id="' +
                                    resp[k].id + '">Delete</button></center></td>')
                            } else {
                                tr.append(
                                    resp[k].seats > 0 ?
                                    '<td><center><button class="btn btn-sm btn-primary mr-2 text-white book_now" data-id="' +
                                    resp[k].id +
                                    '"><strong>Book Now</strong></button></center></td>' :
                                    '<td><center>N/A</center></td>')
                            }
                            $('#schedule-field tbody').append(tr)

                        })

                    } else {
                        $('#schedule-field tbody').html(
                            '<tr><td colspan="7" class="text-center">No data.</td></tr>')
                    }
                }
            }
        },
        complete: function() {
            $('#schedule-field').dataTable()
            manage();
            $('body').on('click', '.book_now', function() {
                uni_modal('Book Details', 'customer_book.php?id=' + $(this).attr(
                    'data-id'), 1)
            })
        }
    })
}

function manage() {
    $('body').on('click', '.edit_schedule', function() {
        uni_modal('Edit New Schedule', 'manage_schedule.php?id=' + $(this).attr('data-id'))

    })
    $('body').on('click', '.remove_schedule', function() {
        _conf('Are you sure to delete this data?', 'remove_schedule', [$(this).attr('data-id')])

    })
}

function remove_schedule($id = '') {
    start_load()
    $.ajax({
        url: 'delete_schedule.php',
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
                load_schedule()
            }
        }
    })
}
$(document).ready(function() {
    load_schedule()
})
</script>