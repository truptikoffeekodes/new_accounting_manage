<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">

    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>

    <div class="btn btn-list">Sale Item Invoice
        <a href="<?= url('Milling/Add_jobwork') ?>" class="btn ripple btn-primary"><i class="fe fe-external-link"></i>
            Add New</a>
    </div>
</div>

<!-- End Page Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-fw-widget" id="table_list_data"
                            data-id="jobwork_data" data-module="Milling" data-filter_data=''>
                            <thead>
                                <tr>
                                    <th>SR NO</th>
                                    <th>Date</th>
                                    <th>Jobwork/Party Name </th>
                                    <th>Item Name</th>
                                    <th>TAKA</th>
                                    <th>MTR</th>
                                    <th>PCS</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th>SR NO</th>
                                <th>Date</th>
                                <th>Jobwork/Party Name </th>
                                <th>Item Name</th>
                                <th>TAKA</th>
                                <th>MTR</th>
                                <th>PCS</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript">
$(document).ready(function() {
    datatable_load('');
});

function datatable_load(filter_val) {

    $("#table_list_data").DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [
            [0, "desc"]
        ],
        "dom": "<'row be-datatable-header'<'col-sm-2'l><'col-sm-6 text-left'B><'col-sm-4 text-right'f>>" +
            "<'row be-datatable-body'<'col-sm-12'tr>>" +
            "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>",
        "buttons": [
            'copy', 'csv', 'excel', 'pdf'
        ],
        "ajax": {
            "type": "POST",
            "url": PATH + "/" + $("#table_list_data").data('module') + "/GetData/" + $("#table_list_data").data(
                'id') + '?filter_data=' + $("#table_list_data").data('filter_data')
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

}

function editable_remove(data_edit) {
   
    var type = 'Remove';

    var data_val = $(data_edit).data('val');

    var ot_title = $(data_edit).attr('title');
    var pkno = $(data_edit).data('pk');
    swal.fire({
        title: "Are you sure Remove " + ot_title + " ?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel pls!",
       
    }).then((result) => {
     
        if (result.value) {
            _data = $.param({
                pk: pkno
            }) + '&' + $.param({
                val: data_val
            }) + '&' + $.param({
                type: type
            }) + '&' + $.param({
                method: $("#table_list_data").data('id')
            }); 
            if (data_val != undefined && data_val != '') {
                $.post(PATH + "/" + $("#table_list_data").data('module') + "/Action/Update", _data, function(
                    data) {
                    if (data.st == 'success') {
                        datatable_load('');
                        swal.fire("Deleted!", "Your imaginary file has been deleted.", "success");
                    }
                });
            }
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
    });
}

function editable_os(data_edit) {
    var type = 'Status';
    var data_val = $(data_edit).data('val');

    var ot_title = $(data_edit).attr('title');
    var pkno = $(data_edit).data('pk');
    swal.fire({
        title: "Are you sure " + ot_title + " ?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, cancel it!",
        cancelButtonText: "No, cancel pls!",
        //closeOnConfirm: false,
        //closeOnCancel: false
    }).then((result) => {
        // function(isConfirm) {
        if (result.value) {
            _data = $.param({
                pk: pkno
            }) + '&' + $.param({
                val: data_val
            }) + '&' + $.param({
                type: type
            }) + '&' + $.param({
                method: $("#table_list_data").data('id')
            });

            if (data_val != undefined && data_val != '') {
                $.post(PATH + "/" + $("#table_list_data").data('module') + "/Action/Update", _data, function(
                    data) {
                    console.log(data);
                    if (data.st == 'success') {

                        datatable_load('');
                        swal.fire("Cancelled!", "Your imaginary file has been Cancelled.", "success");
                    } else {
                        swal.fire("Error!",data.msg,"error");
                    }

                });
            }

        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
    });
}
</script>

<?= $this->endSection() ?>