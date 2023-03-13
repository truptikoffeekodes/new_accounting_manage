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
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="<?= url('bank/add_banktrans') ?>" class="ajax-form-submit" method="post"
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
                                                <select class="form-control select2" id="tran_mode" name="mode"
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
                                    <?php 
                                        $tdt = date_create(date('Y-m-d'));
                                        $today = date_format($tdt,'d-m-Y'); 
                                        if(!empty(@$banktrans)){
                                            if(isset($banktrans['receipt_date']) && $banktrans['receipt_date'] != '0000-00-00'){
                                                $dt = date_create($banktrans['receipt_date']);
                                                $date = date_format($dt,'d-m-Y');
                                            }else{
                                                $dt = date_create(date('Y-m-d'));
                                                $date = date_format($tdt,'d-m-Y'); 
                                            }
                                        }
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-12  form-group">
                                            <label class="form-label">Date<span class="tx-danger">*</span></label>
                                            <input class="form-control dateMask" name="receipt_date"
                                                value="<?=@$banktrans['receipt_date'] ? $date : $today; ?>"
                                                placeholder="DD-MM-YYYY" type="text" id="" required>
                                            <input name="id" value="<?=@$banktrans['id']?>" type="hidden">
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="account" name='account' required>
                                                    <?php if(@$banktrans['account_name']) { ?>
                                                    <option value="<?=@$banktrans['account']?>">
                                                        <?=@$banktrans['account_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row" id="bank_detail">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Account: <span
                                                    class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="account" name='account' required>
                                                    <?php if(@$banktrans['account_name']) { ?>
                                                    <option value="<?=@$banktrans['account']?>">
                                                        <?=@$banktrans['account_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <div class="input-group-prepend" id="chk_btn" style="display:<?php echo (@$banktrans['mode'] == 'Receipt') ? 'none;' : 'block;' ?>">
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
                                            <label class="form-label">Check No.:</label>
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
                                            <label class="form-label">Check Date:</label>
                                            <input class="form-control  dateMask" autocomplete="off" id="chk_date" placeholder = "DD-MM-YYYYY" required
                                                type="text" name="chk_date" 
                                                value="<?=@$check_date?>">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Particular: <span
                                                    class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="particular" onchange ="calculate()" name='particular'>
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
                                                <select class="form-control select2" id="adjustment" name="adj_method"
                                                    required>
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
                                        style="display:<?=!empty($banktrans['invoice']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Invoice : <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="invoices" name="invoice">
                                                    <?php if(@$banktrans['invoice_name']) { ?>
                                                    <option selected value="<?=@$banktrans['invoice']?>">
                                                        <?=@$banktrans['invoice_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="invoice_table" id="invoice_tb"
                                                value="<?=@$banktrans['invoice_tb']?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Amount: <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="amount" type="text" required onkeyup = "calculate()"
                                                placeholder="Enter Amount" value="<?=@$banktrans['amount']?>">
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
                                                    <?= ( @$banktrans['stat_adj'] == "1" ? 'checked' : '' ) ?> value="<?=@$banktrans['stat_adj'] ?>">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Stat Adjustment</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="row nature_pay" style="display:<?=(@$banktrans['stat_adj'] == "1" && @$banktrans['mode'] =='Payment') ? 'block;' : 'none;' ?>">
                                    
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Payment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="nature_pay">
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

                                    <div class="row nature_rec" style="display:<?=(@$banktrans['stat_adj'] == "1" && @$banktrans['mode'] =='Receipt' ) ? 'block;' : 'none;' ?>">
                                    <hr>
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Receipt: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="nature_rec">
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

                                    <div class="row advance_recDiv" style="display:<?=(@$banktrans['nature_rec'] == 2 || @$banktrans['nature_pay'] == 2) ? 'flex;' : 'none;' ?>">
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
                                            <label class="form-label">IGST : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" onkeyup = "calculate()" name="igst"
                                                value="<?=@$banktrans['igst'] ? $banktrans['igst'] : ''; ?>"
                                                placeholder="Enter Item Gst" type="text" >
                                        </div>
                                        <div class="col-md-6 form-group cgst">
                                            <label class="form-label">CGST : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" onkeyup = "calculate()" name="cgst"
                                                value="<?=@$banktrans['cgst'] ? $banktrans['cgst'] : ''; ?>"
                                                placeholder="Enter Item CGST" type="text" >
                                        </div>
                                        <div class="col-md-6 form-group sgst">
                                            <label class="form-label">SGST : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" onkeyup = "calculate()" name="sgst"
                                                value="<?=@$banktrans['sgst'] ? $banktrans['sgst'] : ''; ?>"
                                                placeholder="Enter Item SGST" type="text" >
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Taxable Amount : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" name="taxable" 
                                                value="<?=@$banktrans['taxable'] ? $banktrans['taxable'] : ''; ?>"
                                                placeholder="" type="text" >
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">GST Amount : <span
                                                    class="tx-danger"></span></label>
                                            <input class="form-control" name="gst_amt"
                                                value="<?=@$banktrans['gst_amt'] ? $banktrans['gst_amt'] : ''; ?>"
                                                placeholder="Total Gst Amount" type="text" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="tx-danger error-msg"></div>
                                <div class="tx-success form_proccessing"></div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-6">
                                    <p class="text-left">
                                        <button class="btn btn-space btn-primary" id="save_data"
                                            type="submit">Submit</button>
                                        <!-- <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button> -->
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

<script>
<?php if(@$banktrans['stat_adj'] == 1) {?>
    calculate();
<?php } ?>
$('.ajax-form-submit').on('submit', function(e) {
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wait...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                window.location = "<?=url('Bank/bank_transaction')?>"
            } else {
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            $('#save_data').prop('disabled', false);
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
            $('input[name="stat_adj"]').prop('checked',false);
            $('input[name="stat_adj"]').val('0');
        }
    }else{
        $('.nature_rec').css('display', 'none');
        $('.nature_pay').css('display', 'none');
        $('input[name="stat_adj"]').val('0');
    }
}

function  calculate(){
        $('.error-msg').html('');
        var parti = $('select[name="particular"]').val();
        
        if(parti == '' || parti == null || parti == 'undefined' || parti == 'NaN'){
            $('.error-msg').html('Please select Particular..!! ');
            return;
        }

        var amount = $('input[name="amount"]').val();
        var cgst = $('input[name="cgst"]').val();
        var sgst = $('input[name="sgst"]').val();
        var acc_state = $('input[name="state"]').val();
        var com_state = parseInt(<?= session('state') ?>);
        
        if(amount == 'undefined' || amount =='NaN' || amount == ''){
            $('.error-msg').html('Please Enter The Amount');   
        }
        var igst = $('input[name="igst"]').val();
        var taxable =0;
        var gst_amt =0;
         
        if (com_state == acc_state) {
            gst_amt = amount * ((cgst*2) / 100);
            taxable = amount - gst_amt;
            
            $('.igst').css('display', 'none');
            $('.cgst').css('display', 'block');
            $('.sgst').css('display', 'block');
            
        }else{
            gst_amt = amount * (igst / 100);
            taxable = amount - gst_amt;
            $('.igst').css('display', 'block');
            $('.cgst').css('display', 'none');
            $('.sgst').css('display', 'none');
        }
        $('input[name="taxable"]').val(taxable);
        $('input[name="gst_amt"]').val(gst_amt);
        
    }
$(document).ready(function() {
    // $('.fc-datepicker').datepicker({
    //     dateFormat: 'yy-mm-dd',
    //     showOtherMonths: true,
    //     selectOtherMonths: true
    // });
    $('.dateMask').mask('99-99-9999');

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });


    $("#account").select2({
        width: '80%',
        placeholder: 'Type Account',
        ajax: {
            url: PATH + "Master/Getdata/search_banktrans_account",
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
    
    $('#account').on('select2:select', function(e) {
        var data = e.params.data;
        var bankid = data.id;
        var check_no = data.check;

        $('#check_no').val(check_no);

        var href = $('#checkrng').attr("href");
        var url = href + '?bank_id=' + bankid;
        $("#checkrng").attr("href", url);

    });

    $('#particular').on('select2:select', function(e) {
        var data = e.params.data;
        var state = data.state;
        $('input[name="state"]').val(state);
        $('#adjustment').val(null).trigger('change');
        //$('#invoices').select2({data:['text']});
        $('#invoices').empty();
        $('#invoices').val(null).trigger('change');
        

    });


    $('#adjustment').on('select2:select', function(e) {
        var adjust = $('#adjustment').val();
        var particular_id = $('#particular').val();

        var invoice_div = document.getElementById("invoice_div");
        if (adjust == 'agains_reference') {
            $('#invoices').prop("disabled", false);
            invoice_div.style.display = "flex";
            // _data = $.param({
            //     id: particular_id
            //     searchTerm: 
            // });

            if (particular_id != undefined && particular_id != '') {
                $("#invoices").select2({
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
                // $.post(PATH + "/bank/getdata/search_invoice", _data, function(data) {
                //     // console.log(data.data);
                //     if (data.st == 'success') {
                //         $("#invoices").select2({
                //             placeholder:'Choose Invocie',
                //             data: data.data
                //         });
                //     }
                // });
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
        $('input[name="stat_adj"]').prop('checked',false);
        $('.nature_pay').css('display','none');
        $('.nature_rec').css('display','none');
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
        if(nature_rec == 2){
            $('.advance_recDiv').css('display', 'flex');
        }else{
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
        var cgst = gst/2;
        var sgst = gst/2;

        var acc_state = $('input[name="state"]').val();
        var com_state = parseInt(<?= session('state') ?>);
        if(acc_state ==  com_state){
            $('input[name="cgst"]').val(cgst);    
            $('input[name="sgst"]').val(sgst);
            $('input[name="igst"]').val('');    
        }else{
            $('input[name="cgst"]').val('');    
            $('input[name="sgst"]').val('');
            $('input[name="igst"]').val(gst);
        }
        calculate();

    });


});
</script>

<?= $this->endSection() ?>