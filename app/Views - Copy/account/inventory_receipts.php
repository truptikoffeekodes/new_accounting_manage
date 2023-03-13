<?= $this->extend(THEME . 'sub_templets') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Transaction </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('') ?>" class="ajax-form-submit" method="POST">
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Account Balance : <span class="tx-danger">Amount
                                        here</span></label>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="form-label">Document: <span class="tx-danger">*</span></label>
                                <input class="form-control" name="document" value="" required type="text">
                                <!-- <input value="" name="id" type="hidden"> -->
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="form-label">Challan Date: <span class="tx-danger">*</span></label>
                                <input class="form-control" name="challan_date" value="<?= date('Y-m-d') ?>"
                                    id="dateMask" placeholder="YYYY-MM-DD" type="text">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="form-label">Category : <span class="tx-danger">*</span></label>
                                <select class="form-control select2" name="category" required>

                                    <option lable="Select Option"></option>
                                    <option value="Between">None</option>
                                    <option value="Not Between">Normal</option>
                                    <option value="Select Multiple">Manufacturer</option>
                                    <option value="Exclude Multiple">External Party</option>
                                </select>
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="account" id=""
                                                onchange="validate_autocomplete(this,'')" value="" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('master/') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="form-label">ANKIT[ANKIT]</span></label><br>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">GSTIN: <span class="tx-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-7 form-group">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Party Challan: <span
                                                class="tx-danger">*</span></label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <input class="form-control" name="document" value="" required type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Party Challan Date: <span
                                                class="tx-danger"></span></label>
                                    </div>
                                    <div class="col-md-4 form-group">

                                        <input class="form-control" name="challan_date" value="<?= date('Y-m-d') ?>"
                                            id="dateMask" placeholder="YYYY-MM-DD" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Class: <span class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                    <div class="input-group">
                                            <input class="form-control" type="text" name="account" id=""
                                                onchange="validate_autocomplete(this,'')" value="" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('master/') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0" id="product">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Item Name</th>
                                            <th>PCS</th>
                                            <th>CUT</th>
                                            <th>MTS</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">

                                    </tbody>
                                    <tfoot>
                                        <td colspan="7" class="text-right">Total</td>
                                        <td id="total"></td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0" id="selling_case">
                                    <tbody>
                                        <tr class="cards">
                                            <td>Notes</td>
                                            <td><input class="form-control" name="Notes"
                                                    onkeypress="return isNumberKey(event)" type="text"></td>
                                            <td>Net Amount</td>
                                            <td><input class="form-control" name="card_detail" type="text"
                                                    formnovalidate></td>
                                        </tr>
                                        <tr class="cheque">
                                            <td>Goods Rec. By</td>
                                            <td><input class="form-control" name="haste"
                                                    onkeypress="return isNumberKey(event)" type="text" formnovalidate>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <input class="btn btn-space btn-primary btn-product-submit" id="save_data" type="submit"
                                value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>