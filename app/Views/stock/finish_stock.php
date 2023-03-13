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
           
            <div class="row align-items-center mb-4">
                <div class="col-md-4">
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
                <div class="col-md-4">
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

                <div class="col-md-4">
                    <div class="form-group mb-lg-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    WAREHOUSE:
                                </div>
                            </div>
                            <select class="form-control" id="warehouse" name='warehouse'>
                                </select>
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
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-fw-widget" id="table_list_data"
                            data-id="Finish_ItemStock" data-module="Stock" data-filter_data=''>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript">
$(document).ready(function() {
    datatable_load('');
    $('.dateMask').mask('99-99-9999');

    $("#warehouse").select2({
        width: 'resolve',
        placeholder: 'Type Warehouse Account',
        ajax: {
            url: PATH + "Master/Getdata/search_warehouse",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#SearchButton").on("click", function(event) {
        datatable_load('');
    });
    $("#SearchButtonReset").on("click", function(event) {
        $('#from_date').val('');
        $('#to_date').val('');
        $('#warehouse').val('');


        datatable_load('');
    });
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
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
                data.warehouse = $('#warehouse').val();
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