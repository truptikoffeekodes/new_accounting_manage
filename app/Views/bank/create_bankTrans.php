<?= $this->extend(THEME . 'templete') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Bank </a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>

    <div class="btn btn-list">
        <a href="<?=url('bank/add_contratrans')?>" class="btn ripple btn-primary"><i class="fe fe-external-link"></i>
            Add Contra Transaction</a>
        <a href="<?=url('bank/add_cashtrans')?>" class="btn ripple btn-secondary"><i class="fe fe-external-link"></i>
            Add Cash Transaction</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="<?= url('bank/add_banktrans') ?>" class="ajax-form-submit-bank" method="post"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Bank Transaction Mode: <span
                                                    class="tx-danger"></span></label>
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <div class="input-group">
                                                <select class="form-control parti_select2" id="tran_mode" name="mode"
                                                    required>
                                                    <option value="">None</option>
                                                    <option
                                                        <?= ( @$banktrans['mode'] == "Receipt" ? 'selected' : '' ) ?>
                                                        value="Receipt">Receipt</option>
                                                    <option
                                                        <?= ( @$banktrans['mode'] == "Payment" ? 'selected' : '' ) ?>
                                                        value="Payment">Payment</option>
                                                </select>
                                                <input type="hidden" name="pay_type" value="bank">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12  form-group">
                                            <label class="form-label">Date<span class="tx-danger">*</span></label>
                                            <input class="form-control fc-datepicker" name="receipt_date"
                                                value="<?=@$banktrans['receipt_date'] ? $banktrans['receipt_date'] : date('Y-m-d'); ?>"
                                                placeholder="YYYY-MM-DD" type="text" id="" required>
                                            <input name="id" value="<?=@$banktrans['id']?>" type="hidden">
                                        </div>
                                    </div>

                                    <div class="row" id="bank_detail">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Account: <span class="tx-danger">*</span>
                                                <a data-toggle="modal" href="<?=url('master/add_account/bank')?>"
                                                    data-target="#fm_model" data-title="Add GL Group ">
                                                    <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                            class="fa fa-plus"></i></i></a>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-control" id="account" name='account' required>
                                                    <?php if(@$banktrans['account_name']) { ?>
                                                    <option value="<?=@$banktrans['account']?>">
                                                        <?=@$banktrans['account_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <div class="input-group-prepend" id="chk_btn"
                                                    style="display:<?php echo (@$banktrans['mode'] == 'Receipt') ? 'none;' : 'block;' ?>">
                                                    <div class="input-group-text">
                                                        <a id="checkrng" data-toggle="modal" data-target="#fm_model"
                                                            data-title="Check Range"
                                                            href="<?=url('Bank/add_checkrange')?>"><i
                                                                style="font-size:12px;">ADD RANGE</i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="check_detail"
                                        style="display:<?php !empty($banktrans['check_no']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-6 form-group">
                                            <label class="form-label">Cheque No.:</label>
                                            <input class="form-control" type="text" name="checkno"
                                                placeholder="Enter Check No" id="check_no"
                                                value="<?=@$banktrans['check_no']?>">
                                        </div>
                                        <?php 
                                        
                                        if(!empty($banktrans)){
                                            if(isset($banktrans['check_date']) && $banktrans['check_date'] != '0000-00-00'){
                                                $check_date=user_date($banktrans['check_date']);
                                                
                                            }else{
                                                $check_date='';
                                            }
                                            
                                        }
                                        ?>
                                        <div class="col-lg-6 form-group">
                                            <label class="form-label">Cheque Date:</label>
                                            <input class="form-control  fc-datepicker" autocomplete="off" id="chk_date"
                                                placeholder="YYYY-MM-DDY" type="text" name="chk_date"
                                                value="<?=@$check_date?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Particular: <span class="tx-danger">*</span>
                                                <a data-toggle="modal" href="<?=url('master/add_account')?>"
                                                    data-target="#fm_model" data-title="Add Account ">
                                                    <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                            class="fa fa-plus"></i></i></a>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-control" id="particular" onchange="calculate()"
                                                    name='particular'>
                                                    <?php if(@$banktrans['particular_name']) { ?>
                                                    <option value="<?=@$banktrans['particular']?>">
                                                        <?=@$banktrans['particular_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="state" value="<?=@$banktrans['acc_state']?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Method of Adjustment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control parti_select2" id="adjustment"
                                                    name="adj_method" required>
                                                    <option value="">None</option>
                                                    <option
                                                        <?= ( @$banktrans['adj_method'] == "Advanced" ? 'selected' : '' ) ?>
                                                        value="Advanced">Advanced</option>
                                                    <option
                                                        <?= ( @$banktrans['adj_method'] == "agains_reference" ? 'selected' : '' ) ?>
                                                        value="agains_reference">Agains Reference</option>
                                                    <option
                                                        <?= ( @$banktrans['adj_method'] == "new_reference" ? 'selected' : '' ) ?>
                                                        value="new_reference">New Reference</option>
                                                    <option
                                                        <?= ( @$banktrans['adj_method'] == "on_account" ? 'selected' : '' ) ?>
                                                        value="on_account">On Account</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="invoice_div"
                                        style="display:<?=!empty(@$banktrans['bill']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Invoice : <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="invoices" name="invoice">

                                                </select>
                                            </div>
                                            <input type="hidden" name="invoice_table" id="invoice_tb"
                                                value="<?=@$banktrans['invoice_tb']?>">

                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mg-b-0" id="bills">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Account</th>
                                                        <th>Total</th>
                                                        <th>Paid</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <div class="form-group">
                                                    <div class="tx-danger tbl-error-msg"></div>
                                                </div>
                                                <tbody class="tbody">
                                                    <?php if(isset($banktrans['bill']) && !empty($banktrans['bill'])){ 
                                                            for($i=0; $i<count($banktrans['bill']) ; $i++){
                                                        ?>
                                                    <tr class="item_row">
                                                        <td><input type="hidden" name="against_id[]"
                                                                value="<?=$banktrans['bill'][$i]['id']?>"> <a
                                                                class="tx-danger btnDelete" title="0"><i
                                                                    class="fa fa-times tx-danger"></i></a></td>
                                                        <input type="hidden" name="vch_id[]"
                                                                value="<?=$banktrans['bill'][$i]['vch_id']?>">
                                                        
                                                        <td><input type="hidden" name="date[]"
                                                                value="<?=$banktrans['bill'][$i]['date']?>"><?=user_date($banktrans['bill'][$i]['date'])?>
                                                        </td>
                                                        <td><input type="hidden" name="ac_id[]"
                                                                value="<?=$banktrans['bill'][$i]['ac_id']?>"><input
                                                                type="text" class="form-control input-sm"
                                                                name="ac_name[]"
                                                                value="<?=$banktrans['bill'][$i]['ac_name']?>" readonly>
                                                        </td>
                                                        <td><input type="text" class="form-control input-sm"
                                                                name="net_amt[]"
                                                                value="<?=$banktrans['bill'][$i]['net_amt']?>" readonly>
                                                        </td>
                                                        <td><input type="text" class="form-control input-sm"
                                                                name="total_paid[]"
                                                                value="<?=$banktrans['bill'][$i]['total_paid']?>"
                                                                readonly>
                                                        </td>
                                                        <td><input type="text" onkeyup="calc()"
                                                                class="form-control input-sm" name="vch_amt[]"
                                                                value="<?=$banktrans['bill'][$i]['vch_amt']?>">
                                                        </td>
                                                        <input type="hidden" class="form-control input-sm"
                                                                name="voucher_name[]"
                                                                value="<?=$banktrans['bill'][$i]['voucher_name']?>"
                                                                readonly>
                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Amount: <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="amount" type="text" required
                                                onkeyup="calculate()" placeholder="Enter Amount" onkeypress="return isDesimalNumberKey(event)"
                                                value="<?=@$banktrans['amount']?>">
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Narration:</label>
                                            <input class="form-control" type="text" name="narration"
                                                placeholder="Enter Narration" value="<?=@$banktrans['narration']?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="custom-switch">
                                                <input type="checkbox" name="stat_adj" onchange="check_stat()"
                                                    class="custom-switch-input"
                                                    <?= ( @$banktrans['stat_adj'] == "1" ? 'checked' : '' ) ?>
                                                    value="<?=@$banktrans['stat_adj'] ?>">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Stat Adjustment</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row nature_pay"
                                        style="display:<?=(@$banktrans['stat_adj'] == "1" && @$banktrans['mode'] =='Payment') ? 'block;' : 'none;' ?>">

                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Payment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control parti_select2" name="nature_pay">
                                                    <option
                                                        <?= ( @$banktrans['nature_pay'] == "1" ? 'selected' : '' ) ?>
                                                        value="1">Not Applicable</option>
                                                    <option
                                                        <?= ( @$banktrans['nature_pay'] == "2" ? 'selected' : '' ) ?>
                                                        value="2">Advanced Payment Under Reserve Charge</option>
                                                    <option
                                                        <?= ( @$banktrans['nature_pay'] == "3" ? 'selected' : '' ) ?>
                                                        value="3">Payment Under Reserve Charge</option>
                                                    <option
                                                        <?= ( @$banktrans['nature_pay'] == "4" ? 'selected' : '' ) ?>
                                                        value="4">Refund of Advance Receipt</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row nature_rec"
                                        style="display:<?=(@$banktrans['stat_adj'] == "1" && @$banktrans['mode'] =='Receipt' ) ? 'block;' : 'none;' ?>">
                                        <hr>
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Receipt: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control parti_select2" name="nature_rec">
                                                    <option
                                                        <?= ( @$banktrans['nature_rec'] == "1" ? 'selected' : '' ) ?>
                                                        value="1">Not Applicable</option>
                                                    <option
                                                        <?= ( @$banktrans['nature_rec'] == "2" ? 'selected' : '' ) ?>
                                                        value="2">Advanced Receipt</option>
                                                    <option
                                                        <?= ( @$banktrans['nature_rec'] == "3" ? 'selected' : '' ) ?>
                                                        value="3">Refund of Advance Receipt</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row advance_recDiv"
                                        style="display:<?=(@$banktrans['nature_rec'] == 2 || @$banktrans['nature_pay'] == 2) ? 'flex;' : 'none;' ?>">
                                        <div class="col-md-12 form-group">
                                            <label class="form-label">Select Item: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="item" name="item">
                                                    <?php if(@$banktrans['item_name']) { ?>
                                                    <option value="<?=@$banktrans['item']?>">
                                                        <?=@$banktrans['item_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group igst">
                                            <label class="form-label">IGST : <span class="tx-danger"></span></label>
                                            <input class="form-control" onkeyup="calculate()" name="igst"
                                                value="<?=@$banktrans['igst'] ? $banktrans['igst'] : ''; ?>"
                                                placeholder="Enter Item Gst" type="text">
                                        </div>
                                        <div class="col-md-6 form-group cgst">
                                            <label class="form-label">CGST : <span class="tx-danger"></span></label>
                                            <input class="form-control" onkeyup="calculate()" name="cgst"
                                                value="<?=@$banktrans['cgst'] ? $banktrans['cgst'] : ''; ?>"
                                                placeholder="Enter Item CGST" type="text">
                                        </div>
                                        <div class="col-md-6 form-group sgst">
                                            <label class="form-label">SGST : <span class="tx-danger"></span></label>
                                            <input class="form-control" onkeyup="calculate()" name="sgst"
                                                value="<?=@$banktrans['sgst'] ? $banktrans['sgst'] : ''; ?>"
                                                placeholder="Enter Item SGST" type="text">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Taxable Amount : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" name="taxable"
                                                value="<?=@$banktrans['taxable'] ? $banktrans['taxable'] : ''; ?>"
                                                placeholder="" type="text">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">GST Amount : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" name="gst_amt"
                                                value="<?=@$banktrans['gst_amt'] ? $banktrans['gst_amt'] : ''; ?>"
                                                placeholder="Total Gst Amount" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="tx-danger error-msg-bank"></div>
                                <div class="tx-success form_proccessing_bank"></div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-6">
                                    <p class="text-left">
                                        <button class="btn btn-space btn-primary" id="save_data_bank"
                                            type="submit">Submit</button>
                                    </p>
                                </div>
                            </div>
                        </form>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
<?php if(@$banktrans['stat_adj'] == 1) {?>
calculate();
<?php } ?>



$('.ajax-form-submit-bank').on('submit', function(e) {
    $('#save_data_bank').prop('disabled', true);
    $('.error-msg-bank').html('');
    $('.form_proccessing_bank').html('Please wait...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    //console.log($(this).serialize());
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                window.location = "<?=url('Bank/bank_transaction')?>"
            } else {
                $('.form_proccessing_bank').html('');
                $('#save_data_bank').prop('disabled', false);
                $('.error-msg-bank').html(response.msg);
            }
        },
        error: function() {
            $('#save_data_bank').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});

function check_stat() {
    var mode = $('#tran_mode').val();
    if ($('input[name="stat_adj"]').is(':checked')) {
        $('.error-msg').html('');
        $('input[name="stat_adj"]').val('1');
        if (mode != '' && mode != 'undefined') {
            if (mode == 'Receipt') {
                $('.nature_rec').css('display', 'block');
                $('.nature_pay').css('display', 'none');
            } else {
                $('.nature_rec').css('display', 'none');
                $('.nature_pay').css('display', 'block');
            }
        } else {
            $('.error-msg').html('Please Select Cash Mode..!');
            $('input[name="stat_adj"]').prop('checked', false);
            $('input[name="stat_adj"]').val('0');
        }
    } else {
        $('.nature_rec').css('display', 'none');
        $('.nature_pay').css('display', 'none');
        $('input[name="stat_adj"]').val('0');
    }
}

function calculate() {
    $('.error-msg').html('');
    var parti = $('select[name="particular"]').val();

    if (parti == '' || parti == null || parti == 'undefined' || parti == 'NaN') {
        $('.error-msg').html('Please select Particular..!! ');
        return;
    }

    var amount = $('input[name="amount"]').val();
    var cgst = $('input[name="cgst"]').val();
    var sgst = $('input[name="sgst"]').val();
    var acc_state = $('input[name="state"]').val();
    var com_state = parseInt(<?= session('state') ?>);

    if (amount == 'undefined' || amount == 'NaN' || amount == '') {
        $('.error-msg').html('Please Enter The Amount');
    }
    var igst = $('input[name="igst"]').val();
    var taxable = 0;
    var gst_amt = 0;

    if (com_state == acc_state) {
        gst_amt = amount * ((cgst * 2) / 100);
        taxable = amount - gst_amt;

        $('.igst').css('display', 'none');
        $('.cgst').css('display', 'block');
        $('.sgst').css('display', 'block');

    } else {
        gst_amt = amount * (igst / 100);
        taxable = amount - gst_amt;
        $('.igst').css('display', 'block');
        $('.cgst').css('display', 'none');
        $('.sgst').css('display', 'none');
    }
    $('input[name="taxable"]').val(taxable);
    $('input[name="gst_amt"]').val(gst_amt);



}

function calc() {
    var vch_amt = $('input[name="vch_amt[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var net_amt = $('input[name="net_amt[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var total_paid = $('input[name="total_paid[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var total = 0;

    for (var i = 0; i < vch_amt.length; i++) {

        if ((net_amt[i] - total_paid[i]) < vch_amt[i] || (net_amt[i] - total_paid[i]) <= 0) {
            $('.error-msg-bank').html("Please Change Amount Of Invoice");
            $('#save_data_bank').attr('disabled', true);
        } else {
            $('.error-msg-bank').html("");
            $('#save_data_bank').attr('disabled', false);
        }

        if (isNaN(vch_amt[i])) {
            vch_amt[i] = 0;
        }
        total += vch_amt[i];
    }
    $('input[name="amount"]').val(total.toFixed(2));

}
$(document).ready(function() {

    <?php
    if(isset($banktrans['id']))
    {?>
    var particular_id = $('#particular').val();

    $("#invoices").select2({
       // alert("jkchdkj");
        width: '100%',
        placeholder: 'Choose Invoice',
        // minimumInputLength: 1,
        ajax: {
            url: PATH + "bank/getdata/search_invoice",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    id: particular_id,
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

    $('#invoices').on('select2:select', function(e) {
        //alert("jfhkrej");
        var suggestion = e.params.data.data;
        
        var vch_name = $('input[name="voucher_name[]"]').val();
        var vch_id = $('input[name="vch_id[]"]').val();
        //console.log(vch_id);
        if(vch_id != undefined){
           
            for (i = 0; i <= vch_id.length; i++) {
                 if (suggestion.id == vch_id[i] && suggestion.voucher_name == vch_name[i]) {
                     $('.tbl-error-msg').html('Voucher was Already Added..!');
                    return;
                }
            }
        }
      

        var id_inp = '<input type="hidden" name ="vch_id[]" value="' + suggestion.id + '">';
        var tds = '<tr class="item_row">';
        tds +=
            '<td><input type="hidden" name="against_id[]" value=""><a class="tx-danger btnDelete" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
        tds += id_inp;
        tds +=  '<td>  <input type ="hidden" class="form-control input-sm" name="date[]" value="' +
                        suggestion.invoice_date + '" readonly>' +  moment(suggestion.invoice_date).format("DD-MM-YYYY")+
                    '</td>';
        tds += '<td><input type="hidden" name ="ac_id[]" value="' + suggestion.account +
            '"><input type ="text" class="form-control input-sm" name="ac_name[]" value="' + suggestion
            .party_name + '" readonly></td>';
        tds += '<td><input type ="text" class="form-control input-sm"   name="net_amt[]" value="' +
            suggestion
            .net_amount + '" readonly></td>';
        tds += '<td><input type ="text" class="form-control input-sm"   name="total_paid[]" value="' +
            suggestion
            .total_paid + '" readonly></td>';
        tds +=
            '<td><input class="form-control input-sm"  required name="vch_amt[]"  onchange="calc()" onkeypress="return isDesimalNumberKey(event)" value=""  type="text"></td>';
        tds +=
            ' <input type ="hidden" class="form-control input-sm"   name="voucher_name[]" value="' +
            suggestion
            .voucher_name + '" readonly>';

        $('.tbody').append(tds);
        $('#invoices').val('');

        calc();

    });

    <?php } ?>

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.parti_select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });


    $("#account").select2({
        width: '80%',
        placeholder: 'Type Account',
        ajax: {
            url: PATH + "Master/Getdata/search_bank_account_data",
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

    $('#account').on('select2:select', function(e) {
        var data = e.params.data;

        var bankid = data.id;
        var check_no = data.check;

        $('#check_no').val(check_no);

        var href = $('#checkrng').attr("href");
        var url = href + '?bank_id=' + bankid;
        $("#checkrng").attr("href", url);

        if (data.text == 'Cash') {
            $('#check_detail').css('display', 'none');
            $('#chk_btn').css('display', 'none');
        } else {
            $('#check_detail').css('display', 'flex');
            $('#chk_btn').css('display', 'flex');
        }

    });


    $("#particular").select2({
        width: '100%',
        placeholder: 'Type Particular',
        ajax: {
            url: PATH + "Master/Getdata/search_bank_particular",
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

    $("#bank").select2({
        width: '80%',
        placeholder: 'Type Bank',
        ajax: {
            url: PATH + "Master/Getdata/search_bank",
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

    $("#bills").on('click', '.btnDelete', function() {

        $(this).closest('tr').remove();
        calc();
    });

    $('#particular').on('select2:select', function(e) {
        var data = e.params.data;
        var state = data.state;
        $('input[name="state"]').val(state);
        $('#adjustment').val(null).trigger('change');
        // $('#invoices').select2({data:['text']});
        $('#invoices').empty();
        $('#invoices').val(null).trigger('change');

    });

    //$('#adjustment').on('select2:select', function(e) {
    $('#adjustment').off('select2:select').on('select2:select', function(e) {
        var adjust = $('#adjustment').val();
        var particular_id = $('#particular').val();

        var invoice_div = document.getElementById("invoice_div");
        if (adjust == 'agains_reference') {
            $('#invoices').prop("disabled", false);
            invoice_div.style.display = "flex";


            if (particular_id != undefined && particular_id != '') {

                <?php 
                if(!isset($banktrans['id']))
                {?>
                $("#invoices").select2({
                    width: '100%',
                    placeholder: 'Choose Invoice',
                    ajax: {
                        url: PATH + "bank/getdata/search_invoice",
                        type: "post",
                        allowClear: true,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                id: particular_id,
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

               // $('#invoices').on('select2:select', function(e) {
                $('#invoices').off('select2:select').on('select2:select', function(e) {
                    var suggestion = e.params.data.data;
                   
                    var vch_name = $('input[name="voucher_name[]"]').map(function() {
                                        return this.value;
                                    }).get();

                    var vch_id = $('input[name="vch_id[]"]').map(function() {
                                        return this.value;
                                    }).get();
                    
                                   // console.log(vch_name);
                    if (typeof vch_id !== "undefined" ) {
                        for (i = 0; i <= vch_id.length; i++) {
                            if (suggestion.id == vch_id[i] && suggestion.voucher_name == vch_name[i]) {
                                $('.tbl-error-msg').html('Voucher was Already Added..!');return;
                            }
                        }
                    }else{
                        $('.tbl-error-msg').html('');
                    }

                    var id_inp = '<input type="hidden" name ="vch_id[]" value="' + suggestion
                        .id + '">';
                    var tds = '<tr class="item_row">';
                    tds +=
                        '<td><input type="hidden" name="against_id[]" value=""><a class="tx-danger btnDelete" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                    tds += id_inp;
                    tds +=
                        '<td>  <input type ="hidden" class="form-control input-sm" name="date[]" value="' +
                        suggestion.invoice_date + '" readonly>' +  moment(suggestion.invoice_date).format("DD-MM-YYYY") +
                    '</td>';
                    tds += '<td><input type="hidden" name ="ac_id[]" value="' + suggestion
                        .account +
                        '"><input type ="text" class="form-control input-sm" name="ac_name[]" value="' +
                        suggestion
                        .party_name + '" readonly></td>';
                    tds +=
                        '<td><input type ="text" class="form-control input-sm"   name="net_amt[]" value="' +
                        suggestion
                        .net_amount + '" readonly></td>';

                    tds +=
                        '<td><input type ="text" class="form-control input-sm"   name="total_paid[]" value="' +
                        suggestion
                        .total_paid + '" readonly></td>';

                    tds +=
                        '<td><input class="form-control input-sm"  required name="vch_amt[]"  onchange="calc()" onkeypress="return isDesimalNumberKey(event)" value=""  type="text"></td> <input type ="hidden" class="form-control input-sm"   name="voucher_name[]" value="' +
                        suggestion.voucher_name + '" readonly>';

                    $('.tbody').append(tds);
                    $('#invoices').val('');

                    calc();

                });

                <?php } ?>

            }
        } else {
            var invoice_div = document.getElementById("invoice_div");
            $('#invoices').prop("disabled", true);
            invoice_div.style.display = "none";

        }
    });

    $('#invoices').on('select2:select', function(e) {
        var data = e.params.data;

        $('#invoice_tb').val(data.table);
    });

    $('#tran_mode').on('select2:select', function(e) {

        var trasaction = $('#tran_mode').val();
        var checkrangebtn = document.getElementById("chk_btn");
        var check_detail = document.getElementById("check_detail");
        var bank_detail = document.getElementById("bank_detail");
        $('input[name="stat_adj"]').prop('checked', false);
        $('.nature_pay').css('display', 'none');
        $('.nature_rec').css('display', 'none');
        $('input[name="stat_adj"]').val('0');

        if (trasaction == 'Receipt') {
            checkrangebtn.style.display = "none";
            // check_detail.style.display = "none";
            // $('#chk_date').attr('disabled');

        } else {
            checkrangebtn.style.display = "flex";
            check_detail.style.display = "flex";
            bank_detail.style.display = "flex";
        }
    });

    $('select[name="nature_rec"]').on('select2:select', function(e) {
        var nature_rec = $('select[name="nature_rec"]').val();
        if (nature_rec == 2) {
            $('.advance_recDiv').css('display', 'flex');
        } else {
            $('.advance_recDiv').css('display', 'none');
        }
    });

    $("#item").select2({
        width: '100%',
        placeholder: 'Type Item Name ',
        ajax: {
            url: PATH + "Sales/Getdata/Item",
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

    $('#item').on('select2:select', function(e) {
        var suggestion = e.params.data;
        var gst = suggestion.price.igst;
        var cgst = gst / 2;
        var sgst = gst / 2;

        var acc_state = $('input[name="state"]').val();
        var com_state = parseInt(<?= session('state') ?>);
        if (acc_state == com_state) {
            $('input[name="cgst"]').val(cgst);
            $('input[name="sgst"]').val(sgst);
            $('input[name="igst"]').val('');
        } else {
            $('input[name="cgst"]').val('');
            $('input[name="sgst"]').val('');
            $('input[name="igst"]').val(gst);
        }
        calculate();

    });


});
</script>

<?= $this->endSection() ?>