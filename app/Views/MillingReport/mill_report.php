<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.mill_report {
    width: 180%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 5px;
}

.table-responsive::-webkit-scrollbar {
    width: 3px;
    height: 12px;
    transition: .3s background;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #e1e6f1;
}
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
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-fw-widget">
                                <tr>
                                    <td>
                                        <span style="size:20px;"><b><?=$title?></b></span>
                                    </td>
                                </tr>
                                <tr colspan="4">
                                </tr>
                            </table>
                        </div>
                        <table class="table table-striped table-hover table-fw-widget mill_report" id="table_list_data"
                            data-id="mill_report" data-module="MillingReport" data-filter_data=''>
                            <thead>
                                <tr>
                                    <th>Mill Challan SR.No</th>
                                    <th>Gray Challan No</th>
                                    <th>Gray Invoice No</th>
                                    <th>Weaver A/C No</th>
                                    <th>Date</th>
                                    <th>Mill A/C</th>
                                    <th>Item</th>
                                    <th>HSN </th>
                                    <th>SEND TAKA</th>
                                    <th>SEND QTY(MTR)</th>
                                    <th>RETURN TAKA</th>
                                    <th>RETURN QTY(MTR)</th>
                                    <th>TOTAL TAKA</th>
                                    <th>TOTAL QTY(MTR)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                    <th>Mill Challan SR.No</th>
                                    <th>Gray Challan No</th>
                                    <th>Gray Invoice No</th>
                                    <th>Weaver A/C No</th>
                                    <th>Date</th>
                                    <th>Mill A/C</th>
                                    <th>Item</th>
                                    <th>HSN </th>
                                    <th>SEND TAKA</th>
                                    <th>SEND QTY(MTR)</th>
                                    <th>RETURN TAKA</th>
                                    <th>RETURN QTY(MTR)</th>
                                    <th>TOTAL TAKA</th>
                                    <th>TOTAL QTY(MTR)</th>
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

    $("#SearchButton").on("click", function(event) {
        datatable_load('');
    });
    $("#SearchButtonReset").on("click", function(event) {
        $('#from_date').val('');
        $('#to_date').val('');

        datatable_load('');
    });
});
$('.dateMask').mask('99-99-9999');

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

}
</script>

<?= $this->endSection() ?>