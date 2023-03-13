<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title "><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Stock</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>
<!-- End Page Header -->

<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="post" id="date_submit">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" name="from" id="from"
                                            placeholder="2020-12-12" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" name="to" id="to"
                                            placeholder="2020-12-12" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">Reset</a>

                </div>
            </form>
        </div>
    </div>
</div>
<!--End Navbar -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-fw-widget" id="table_list_data"
                            data-id="jobwork_stock" data-module="Milling" data-filter_data=''>

                            <thead>
                                <tr style="border-bottom:1px solid black;">
                                    <th rowspan="2">Id</th>
                                    <th rowspan="2">Item /HSN</th>
                                    <th colspan="2">
                                        <center>INWARDS</center>
                                    </th>
                                    <th colspan="2">
                                        <center>OUTWARDS</center>
                                    </th>
                                    <th colspan="2">
                                        <center>OUTSTANDING</center>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <center>PCS</center>
                                    </th>
                                    <th>
                                        <center>MTR</center>
                                    </th>

                                    <th>
                                        <center>PCS</center>
                                    </th>
                                    <th>
                                        <center>MTR</center>
                                    </th>

                                    <th>
                                        <center>PCS</center>
                                    </th>
                                    <th>
                                        <center>MTR</center>
                                    </th>


                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                            <tfoot>

                                <th colspan="2">
                                    <center>Total</center>
                                </th>
                                <th id=""></th>
                                <th id=""></th>
                                <th id=""></th>
                                <th id=""></th>
                                <th id="out_pcs"></th>
                                <th id="out_mtr"></th>
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
    datatable_load([]);

});
$('#date_submit').on('submit', function(e) {

    var fields = $('#date_submit').serializeArray().reduce(function(output, value) {
        output[value.name] = value.value
        return output
    }, {});

    //var fields = $('#date_submit').serializeArray();
    datatable_load(fields);
    return false;
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
            "data": filter_val,
            "url": PATH + "/" + $("#table_list_data").data('module') + "/Getdata/" + $("#table_list_data").data(
                'id') + '?filter_data=' + $("#table_list_data").data('filter_data')
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {}
    });
    // var table = $('#table_list_data').DataTable();
    // var diff_total = table.column(8).data().sum();
    // console.log('diff_total' + diff_total);
    // $('#diff_total').text(diff_total);
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
    var select_input = {
        "1": "Activate",
        "0": "Deactivate"
    };

    swal({
        title: ot_title,
        confirmButtonText: "Save",
        input: "select",
        inputValue: data_val,
        inputOptions: select_input,
        showCancelButton: !0,
        inputValidator: function(e) {
            return !e && "You need to write something!"
        }
    }).then(function(result) {

        _data = $.param({
            pk: pkno
        }) + '&' + $.param({
            val: result.value
        }) + '&' + $.param({
            type: type
        }) + '&' + $.param({
            method: $("#table_list_data").data('id')
        });

        if (result.value != undefined && result.value != '') {
            $.post(PATH + "/" + $("#table_list_data").data('module') + "/Action/Update", _data, function(data) {

                if (data.st == 'success') {
                    var selectdata = result.value;
                    $(data_edit).data('val', selectdata);
                    $(data_edit).html(select_input[selectdata]);
                    swal("success!", "Your update successfully!", "success");

                }

            });
        }
    });
}
</script>
<?= $this->endSection() ?>