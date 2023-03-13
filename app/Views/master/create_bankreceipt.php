<?= $this->extend(THEME . 'sub_templets') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Dashboard</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <div>

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="row">
                                    <div class=" col-lg-12 form-group">
                                        <label class="">Account Balance:<span class="tx-danger">Zero</span></label>
                                    </div>
                                    <div class=" col-lg-6 form-group">
                                        <label class="">Document:<span class="tx-danger">*</span></label>
                                        <input class="form-control" name="document" required="" type="text">
                                    </div>


                                    <div class="col-lg-6  form-group">
                                        <label class="form-label">Date<span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                                </div>
                                            </div><input class="form-control fc-datepicker" name="date"
                                                placeholder="MM/DD/YYYY" type="text" id="dp1599912508714">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" id="account"
                                                    onchange="validate_autocomplete(this,'account_id')" name="account" value="">
                                                    <input type="hidden" name="account_id" id="account_id"
                                                    value="<?=@$bankreceipt['account']?>">
                                                <div class="dz-error-message tx-danger account_id"></div>
                                                <input type="hidden" name="id" value="<?=@$bankreceipt['id']?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Bank: <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="bank" value="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="#" data-target="#fm_model"
                                                        data-title=" "><i style="font-size:20px;"
                                                            class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Bank Branch: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" name="bank_branch" type="text" value="">
                                    </div>
                                    <div class="col-lg-7 form-group">
                                        <label class="form-label">Cheque No : <span class="tx-danger">*</span></label>
                                        <input class="form-control" name="cheque" type="text" value="">
                                    </div>
                                    <div class="col-lg-5 form-group">
                                        <label class="form-label">Cheque Date : <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                                </div>
                                            </div><input class="form-control fc-datepicker" name="cheque_date"
                                                placeholder="MM/DD/YYYY" type="text" id="dp1599912508714">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Slip No.</label>
                                        <input class="form-control" name="slip_no" required="" type="text">
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Class</label>
                                        <div class="input-group">
                                            <input class="form-control" name="class" type="text" name="bank" value="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="#" data-target="#fm_model"
                                                        data-title=" "><i style="font-size:20px;"
                                                            class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Received By/Sub Ledger</label>
                                        <div class="input-group">
                                            <input class="form-control" name="received" type="text" name="bank" value="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="#" data-target="#fm_model"
                                                        data-title=" "><i style="font-size:20px;"
                                                            class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="form-label">Particulars</label>
                                        <div class="input-group">
                                            <input class="form-control" name="particulars" type="text" name="bank" value="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="#" data-target="#fm_model"
                                                        data-title=" "><i style="font-size:20px;"
                                                            class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="form-label">Amount</label>
                                        <input class="form-control" name="amount" type="text" name="bank" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                                <div class="col-sm-12">
                                    <p class="text-right">
                                        <button class="btn btn-space btn-primary" type="submit">Submit</button>
                                        <button class="btn btn-space btn-primary" type="button"
                                            onclick="addinput()">Add</button>
                                    </p>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
function afterload() {}
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
});

function addinput() {
    var html = '';
    $('#addinput').append(html);
}
</script>

<?= $this->endSection() ?>