<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
/* table.dataTable tbody th,
table.dataTable tbody td {
    white-space: nowrap;
} */
</style>
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>

<div class="responsive-background">
    <div class="navbar-collapse collapse show" id="navbarSupportedContent" style="">
        <div class="advanced-search">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            FROM:
                                        </div>
                                    </div>
                                    <input class="form-control dateMask" id="from_date" name="from_date"
                                        placeholder="DD-MM-YYYY" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            TO:
                                        </div>
                                    </div>
                                    <input class="form-control dateMask" name="to" id="to_date" placeholder="DD-MM-YYYY"
                                        type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="button" id="SearchButton" class="btn btn-primary">Apply</button>
                <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
                    aria-label="Toggle navigation">Reset</a>

            </div>
        </div>
    </div>
</div>
<!-- End Page Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="border">
                            <div class="bg-gray-300 nav-bg">
                                <nav class="nav nav-tabs">
                                    <a class="nav-link active" data-toggle="tab" href="#Gray">Gray</a>
                                    <a class="nav-link" data-toggle="tab" href="#Mill">Mill</a>
                                    <a class="nav-link" data-toggle="tab" href="#Finish">Finish</a>
                                    <a class="nav-link" data-toggle="tab" href="#Jobwork">Jobwork</a>
                                    <a class="nav-link" data-toggle="tab" href="#RecJob">Jobwork Received</a>
                                </nav>
                            </div>

                            <div class="card-body tab-content h-100">
                                <div class="tab-pane active" id="Gray">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-fw-widget"
                                            id="gray_table_list_data" data-id="Gray_ItemStock" data-module="Stock"
                                            data-filter_data=''>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name </th>
                                                    <th>HSN </th>
                                                    <th>TOTAL TAKA</th>
                                                    <th>TOTAL QTY</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>

                                                <th>ID</th>
                                                <th>Name </th>
                                                <th>HSN </th>
                                                <th>TOTAL TAKA</th>
                                                <th>TOTAL QTY</th>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="Mill">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-fw-widget"
                                            id="mill_table_list_data" data-id="Mill_ItemStock" data-module="Stock"
                                            data-filter_data=''>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name </th>
                                                    <th>HSN </th>
                                                    <th>TOTAL TAKA</th>
                                                    <th>TOTAL QTY</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>

                                                <th>ID</th>
                                                <th>Name </th>
                                                <th>HSN </th>
                                                <th>TOTAL TAKA</th>
                                                <th>TOTAL QTY</th>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="Finish">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-fw-widget"
                                            id="finish_table_list_data" data-id="Finish_ItemStock" data-module="Stock"
                                            data-filter_data=''>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name </th>
                                                    <th>HSN </th>
                                                    <th>TOTAL TAKA</th>
                                                    <th>TOTAL QTY</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>

                                                <th>ID</th>
                                                <th>Name </th>
                                                <th>HSN </th>
                                                <th>TOTAL TAKA</th>
                                                <th>TOTAL QTY</th>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="Jobwork">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-fw-widget"
                                            id="jobwork_table_list_data" data-id="Job_ItemStock" data-module="Stock"
                                            data-filter_data=''>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name </th>
                                                    <th>HSN </th>
                                                    <th>TOTAL TAKA</th>
                                                    <th>TOTAL QTY</th>
                                                    <th>TOTAL SORATGE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <th>ID</th>
                                                <th>Name </th>
                                                <th>HSN </th>
                                                <th>TOTAL TAKA</th>
                                                <th>TOTAL QTY</th>
                                                <th>TOTAL SORATGE</th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="RecJob">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-fw-widget"
                                            id="RecJob_table_list_data" data-id="recJob_ItemStock" data-module="Stock"
                                            data-filter_data=''>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name </th>
                                                    <th>HSN </th>
                                                    <th>TOTAL TAKA</th>
                                                    <th>TOTAL QTY</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>

                                                <th>ID</th>
                                                <th>Name </th>
                                                <th>HSN </th>
                                                <th>TOTAL TAKA</th>
                                                <th>TOTAL QTY</th>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    $("#SearchButton").on("click", function(event) {
        datatable_load('');
    });
    $("#SearchButtonReset").on("click", function(event) {
        $('#from_date').val('');
        $('#to_date').val('');

        datatable_load('');
    });


});

function datatable_load(filter_val) {

    $("#RecJob_table_list_data").DataTable({
        "destroy": true,
        "autoWidth": false,
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
            "url": PATH + "/" + $("#RecJob_table_list_data").data('module') + "/GetData/" + $(
                "#RecJob_table_list_data").data(
                'id') + '?filter_data=' + $("#RecJob_table_list_data").data('filter_data'),
            "data": function(data) {
                // Append to data
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

    $("#jobwork_table_list_data").DataTable({
        "destroy": true,
        "autoWidth": false,
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
            "url": PATH + "/" + $("#jobwork_table_list_data").data('module') + "/GetData/" + $(
                "#jobwork_table_list_data").data(
                'id') + '?filter_data=' + $("#jobwork_table_list_data").data('filter_data'),
            "data": function(data) {
                // Append to data
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

    $("#finish_table_list_data").DataTable({
        "destroy": true,
        "autoWidth": false,
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
            "url": PATH + "/" + $("#finish_table_list_data").data('module') + "/GetData/" + $(
                "#finish_table_list_data").data(
                'id') + '?filter_data=' + $("#finish_table_list_data").data('filter_data'),
            "data": function(data) {
                // Append to data
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

    $("#gray_table_list_data").DataTable({
        "destroy": true,
        "autoWidth": false,
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
            "url": PATH + "/" + $("#gray_table_list_data").data('module') + "/GetData/" + $(
                "#gray_table_list_data").data(
                'id') + '?filter_data=' + $("#gray_table_list_data").data('filter_data'),
            "data": function(data) {
                // Append to data
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

    $("#mill_table_list_data").DataTable({
        "destroy": true,
        "processing": true,
        "autoWidth": false,
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
            "url": PATH + "/" + $("#mill_table_list_data").data('module') + "/GetData/" + $(
                "#mill_table_list_data").data(
                'id') + '?filter_data=' + $("#mill_table_list_data").data('filter_data'),
            "data": function(data) {
                // Append to data
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

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
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });
    $('.dateMask').mask('99-99-9999');

}
</script>

<?= $this->endSection() ?>