 <section id="bg-bus" class="d-flex align-items-center">
     <main id="main">
         <div class="container">
             <div class="col-lg-12">
                 <div class="row">
                     <div class="col-md-12">
                         <button class="float-right btn btn-primary btn-sm" type="button" id="new_bus">Add New <i
                                 class="fa fa-plus"></i></button>
                     </div>
                 </div>
                 <div class="row">
                     &nbsp;
                 </div>
                 <div class="row">
                     <div class="card col-md-12">
                         <div class="card-header">
                             <div class="card-title">
                                 <h2>Bus List</h2>
                             </div>
                         </div>
                         <div class="card-body">
                             <table class="table table-striped table-bordered" id="bus-field" data-page-length="5">
                                 <thead>
                                     <tr>
                                         <th class="text-center">#</th>
                                         <th class="text-center">Bus No.</th>
                                         <th class="text-center">Bus Seats No.</th>
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
$('#new_bus').click(function() {
    uni_modal('Add New Bus', 'manage_bus.php')
})
window.load_bus = function() {
    $('#bus-field').dataTable().fnDestroy();
    $('#bus-field tbody').html('<tr><td colspan="4" class="text-center">Please wait...</td></tr>')
    $.ajax({
        url: 'load_bus.php',
        error: err => {
            console.log(err)
            alert_toast('An error occured.', 'danger');
        },
        success: function(resp) {
            if (resp) {
                if (typeof(resp) != undefined) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length > 0) {
                        $('#bus-field tbody').html('')
                        var i = 1;
                        Object.keys(resp).map(k => {
                            var tr = $('<tr></tr>');
                            tr.append('<td class="text-center">' + (i++) + '</td>')
                            tr.append('<td class="text-center">' + resp[k].bus_number
                                .toUpperCase() + '</td>')
                            tr.append('<td>' + resp[k].seats + '</td>')
                            tr.append(
                                '<td><center><button class="btn btn-sm btn-primary edit_bus mr-2" data-id="' +
                                resp[k].id +
                                '">Edit</button><button class="btn btn-sm btn-danger remove_bus" data-id="' +
                                resp[k].id + '">Delete</button></center></td>')
                            $('#bus-field tbody').append(tr)

                        })

                    } else {
                        $('#bus-field tbody').html(
                            '<tr><td colspan="4" class="text-center">No data.</td></tr>')
                    }
                }
            }
        },
        complete: function() {
            $('#bus-field').dataTable()
            manage();
        }
    })
}

function manage() {
    $('body').on('click', '.edit_bus', function() {
        uni_modal('Edit New Bus', 'manage_bus.php?id=' + $(this).attr('data-id'))

    })
    $('body').on('click', '.remove_bus', function() {
        _conf('Are you sure to delete this data?', 'remove_bus', [$(this).attr('data-id')])

    })
}

function remove_bus($id = '') {
    start_load()
    $.ajax({
        url: 'delete_bus.php',
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
                load_bus()
            }
        }
    })
}
$(document).ready(function() {
    load_bus()
})
 </script>