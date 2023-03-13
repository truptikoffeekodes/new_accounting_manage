<?= $this->extend(THEME . 'sub_templets') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Transaction</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">JV</a></li>
            <li class="breadcrumb-item active" aria-current="page">JV</li>
        </ol>
    </div>
</div>


<div class="row">

    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">Document:</label>
                                    <input class="form-control" name="code" value="" placeholder="" required=""
                                        type="text">
                                    <input value="" name="id" type="hidden">
                                </div>


                                <div class=" col-lg-4 form-group">
                                    <label class="form-label">Date<span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" placeholder="MM/DD/YYYY"
                                            type="text" id="dp1599912508714">
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">Amount:</label>
                                    <input class="form-control" name="code" value="" placeholder="" required=""
                                        type="text">
                                    <input value="" name="id" type="hidden">
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Type:</label>
                                    <div class="SumoSelect sumo_somename" tabindex="0" role="button"
                                        aria-expanded="true"><select name="somename"
                                            class="form-control SlectBox SumoUnder" onclick="console.log($(this).val())"
                                            onchange="console.log('change is firing')" tabindex="-1">
                                            <!--placeholder-->
                                            <option title="Volvo is a car" value="volvo">Credit</option>
                                            <option value="saab">Debit</option>

                                        </select></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Class:</label>
                                    <input class="form-control" name="code" value="" placeholder="" required=""
                                        type="text">
                                    <input value="" name="id" type="hidden">
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Account:</label>
                                    <input class="form-control" name="code" value="" placeholder="" required=""
                                        type="text">
                                    <input value="" name="id" type="hidden">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Particulars:</label>
                                    <input class="form-control" name="code" value="" placeholder="" required=""
                                        type="text">
                                    <input value="" name="id" type="hidden">
                                </div>
                            </div>
                            <button class="btn ripple btn-main-primary">Submit</button>
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
</script>

<?= $this->endSection() ?>