<?= $this->extend(THEME . 'sub_templets') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">Transaction</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
    </div>
</div>
<div class="col-md-6 offset-md-3">
    <div class="card custom-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <hr>
                    <h5>Challan Details</h5>
                    <hr>
                </div>
                
                <div class="col-md-10 form-group">
                    <label class="form-group">Daybook</label>
                    <div class="input-group">
                        <input class="form-control" name="group" value="" placeholder="Enter Group" required=""
                            type="text">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <a data-toggle="modal" href="#" data-target="#fm_model" data-title="Enter Detail "><i
                                        style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-6 form-group">
                    <label class="form-group"><b>Date</b> From:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fe fe-calendar lh--9 op-6"></i>
                            </div>
                        </div>
                        <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text"
                            id="dp1599905288109">
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label class="form-group">To:</label>
                    <div class="input-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                </div>
                            </div>
                            <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text"
                                id="dp1599905288109">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-group"><b>Chllan</b> From #:</label>
                    <input class="form-control" name="challan_from" value="" placeholder="Challan From" required=""
                        type="text">
                </div>

                <div class="col-md-6 form-group">
                    <label class="form-group">To #:</label>
                        <input class="form-control" name="Challan_to" value="" placeholder="Challan To" required=""
                            type="text">
                </div>
                <div class="col-md-12 form-group">
                    <label class="form-group">Account</label>
                    <div class="input-group">
                        <input class="form-control" name="account" value="" placeholder="Select Account" required=""
                            type="text">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <a data-toggle="modal" href="#" data-target="#fm_model" data-title="Enter Detail "><i
                                        style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <hr>
                    <h5>Invoice Details</h5>
                    <hr>
                </div>
                <hr>
                <div class="col-md-12 form-group">
                    <label class="form-group">Daybook</label>
                    <div class="input-group">
                        <input class="form-control" name="daybook" value="" placeholder="Select Daybook" required=""
                            type="text">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <a data-toggle="modal" href="#" data-target="#fm_model" data-title="Enter Detail "><i
                                        style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                <label class="form-group">Invoice Date</label>
                    <div class="input-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                </div>
                            </div>
                            <input class="form-control fc-datepicker" name="invoice_date" placeholder="MM/DD/YYYY" type="text"
                                id="dp1599905288109">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4 form-group">
            <button class="btn ripple btn-primary">Generate</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#wizard1').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>'
    });
    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    function afterload() {
        $('.select2').select2({
            placeholder: "Select Option"
        });
    }
});
</script>
<?= $this->endSection() ?>