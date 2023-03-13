<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

 <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5"> Sales Return </h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a href="<?=url('sales/add_salesreturn')?>"   class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Add New</a>
        </div>
    </div>

<!-- End Page Header -->
<div class="row">
        <div class="col-lg-12">
            <div class="card custom-card">
                <div class="card-header card-header-divider">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id="salesreturn" data-module="sales" data-filter_data=''>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Account</th>
                                        <th>Challan Date</th>
                                        <th>Broker</th>
                                        <th>Net Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                        <th>Id</th>
                                        <th>Account</th>
                                        <th>Challan Date</th>
                                        <th>Broker</th>
                                        <th>Net Amount</th>
                                        <th>Action</th>
                               </tfoot>
                            </table>
                        </div>
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
                "url": PATH + "/" + $("#table_list_data").data('module') + "/Getdata/" + $("#table_list_data").data('id') + '?filter_data=' + $("#table_list_data").data('filter_data')
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
            cancelButtonText: "No, cancel plx!",
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
                    $.post(PATH + "/" + $("#table_list_data").data('module') + "/Action/Update", _data, function(data) {

                        if (data.st == 'success') {
                            datatable_load('');
                            swal.fire("Deleted!", "Your imaginary file has been deleted.", "success");
                        }
                    });
                }

            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
            // })}
        });
    }
</script>

<?= $this->endSection() ?>