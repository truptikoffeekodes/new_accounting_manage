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
</div>

<!-- End Page Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-fw-widget" id="table_list_data"
                            data-id="sendMill_ItemWise" data-module="MillingReport" data-filter_data=''>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name </th>
                                    <th>HSN </th>
                                    <th>SEND JOB UNIT</th>
                                    <th>SEND JOB TAKA</th>
                                    <th>SEND JOB QTY(MTR)</th>
                                    <th>RETURN JOB UNIT</th>
                                    <th>RETURN JOB PCS</th>
                                    <th>RETURN JOB QTY(MTR)</th>
                                    <th>RECEIVED JOB PCS</th>
                                    <th>RECEIVED JOB QTY(MTR)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                    <th>ID</th>
                                    <th>Name </th>
                                    <th>HSN </th>
                                    <th>SEND JOB UNIT</th>
                                    <th>SEND JOB TAKA</th>
                                    <th>SEND JOB QTY(MTR)</th>
                                    <th>RETURN JOB UNIT</th>
                                    <th>RETURN JOB PCS</th>
                                    <th>RETURN JOB QTY(MTR)</th>
                                    <th>RECEIVED JOB PCS</th>
                                    <th>RECEIVED JOB QTY(MTR)</th>
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
            "url": PATH + "/" + $("#table_list_data").data('module') + "/GetData/" + $("#table_list_data").data(
                'id') + '?filter_data=' + $("#table_list_data").data('filter_data'),
            "data": function(data) {
                // Append to data
                data.sku_code = $('#sku_code').val();
                data.account = $('#account').val();
                data.market = $('#market').val();
                data.pcondition = $('#pcondition').val();
                data.status = $('#status').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

}


</script>

<?= $this->endSection() ?>