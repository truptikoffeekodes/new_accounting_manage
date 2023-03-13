<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="row pt-3">
    <div class="col-xl-3 col-lg-12 d-none d-xl-block custom-leftnav">
        <div class="main-content-left-components">
            <div class="card custom-card">
                <div class="card-header custom-card-header">
                    <h6 class="card-title mb-0">#<?= $client['UserName'] ?> (<?= $client['UserMobile'] ?>)</h6>
                </div>
                <div class="nav flex-column">
                    <a class="nav-link p-0 active" data-toggle="tab" href="#invoices">
                        <div class="list d-flex align-items-center p-3 border-top">
                            <span class="peity-donut"><i class="fa fa-file-invoice menu-icon"
                                    aria-hidden="true"></i></span>
                            <div class="wrappe ml-3">
                                <h6 class="mb-1">Invoice</h6>
                            </div>
                        </div>
                    </a>
                    <a class="nav-link p-0" data-toggle="tab" href="#transactions">
                        <div class="list d-flex align-items-center p-3 border-top">
                            <span class="peity-donut"><i class="fa fa-chart-line menu-icon"
                                    aria-hidden="true"></i></span>
                            <div class="wrappe ml-3">
                                <h6 class="mb-1">Transactions</h6>
                            </div>
                        </div>
                    </a>
                    <a class="nav-link p-0" data-toggle="tab" href="#statement">
                        <div class="list d-flex align-items-center p-3 border-top">
                            <span class="peity-donut"><i class="fa fa-chart-area menu-icon"
                                    aria-hidden="true"></i></span>
                            <div class="wrappe ml-3">
                                <h6 class="mb-1">Statement</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-12">
        <div class="tab-content">
            <div class="tab-pane active" id="invoices">
                <div class="card custom-card">
                    <div class="card-header custom-card-header">
                        <h4>Invoice</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-fw-widget" id="invoice_list_data"
                                data-id="Invoice" data-module="Home" data-filter_data='<?= $client['id'] ?>'>
                                <thead>
                                    <tr>
                                        <th>InvoiceNo</th>
                                        <th>Total Amount</th>
                                        <th>Payment Received</th>
                                        <th>Outstanding</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>InvoiceNo</th>
                                        <th>Total Amount</th>
                                        <th>Payment Received</th>
                                        <th>Outstanding</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="transactions">
                <div class="card custom-card">
                    <div class="card-header custom-card-header">
                        <h4>Transaction</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-fw-widget" style="width:100%"
                                id="transaction_list_data" data-id="Transaction" data-module="Home"
                                data-filter_data='<?= $client['id'] ?>'>
                                <thead>
                                    <tr>
                                        <th>InvoiceNo</th>
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>InvoiceNo</th>
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="statement">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="card-header custom-card-header">
                            <h4>Statement</h4>
                        </div>
                        <form method="post" action="<?= url('home/statement') ?>" class="mt-3">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">FromDate: <span class="tx-danger">*</span></label>
                                    <div class="input-group">

                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" name="fromDate"
                                            placeholder="YYYY/MM/DD" type="text">
                                    
                                    <input value="<?= @$id; ?>" name="id" type="hidden">
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="form-label">ToDate: <span class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fe fe-calendar lh--9 op-6"></i>
                                        </div>
                                    </div><input class="form-control fc-datepicker" name="toDate"
                                        placeholder="YYYY/MM/DD" type="text">
                                </div>

                            </div>
                    </div>
                    <div class="col-lg-12 form-group">
                        <div class="input-group-btn">
                            <input class="btn btn-space btn-primary btn-product-submit" id="save_data" type="submit"
                                value="Submit">
                        </div>
                    </div>
                </div>
                </form>
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
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    datatable_load('');
});

function datatable_load(filter_val) {

    $("#invoice_list_data").DataTable({
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
            "url": PATH + "/" + $("#invoice_list_data").data('module') + "/Getdata/" + $("#invoice_list_data")
                .data('id') + '?filter_data=' + $("#invoice_list_data").data('filter_data')
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"]
        ],
        "footerCallback": function(row, data, start, end, display) {

        }
    });

    $("#transaction_list_data").DataTable({
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
            "url": PATH + "/" + $("#transaction_list_data").data('module') + "/Getdata/" + $(
                "#transaction_list_data").data('id') + '?filter_data=' + $("#transaction_list_data").data(
                'filter_data')
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