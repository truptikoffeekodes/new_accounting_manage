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
        <a href="<?=url('bank/add_banktrans')?>" class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Add
            Bank Transaction</a>
        <a href="<?=url('bank/add_cashtrans')?>" class="btn ripple btn-secondary"><i class="fe fe-external-link"></i>
            Add Cash Transaction</a>
    </div>

</div>

<div class="row">

    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="<?= url('bank/add_contratrans') ?>" class="ajax-form-submit-contra" method="post"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12">

                                  
                                    <div class="row">
                                        <div class="col-lg-12  form-group">
                                            <label class="form-label">Date<span class="tx-danger">*</span></label>
                                            <input class="form-control fc-datepicker" name="receipt_date"
                                                value="<?=@$contratrans['receipt_date'] ? $contratrans['receipt_date'] : ''; ?>"
                                                placeholder="YYYY-MM-DD" type="text" id="" required>
                                            <input name="id" value="<?=@$contratrans['id']?>" type="hidden">
                                            <input name="pay_type" value="contra" type="hidden">
                                            <input name="mode" type="hidden" value="<?=@$contratrans['mode']?>">
                                        </div>
                                    </div>

                                    <div class="row" id="bank_detail">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Account <b>(DR)</b>: <span
                                                    class="tx-danger">*</span>
                                                <a data-toggle="modal" href="<?=url('master/add_account')?>"
                                                    data-target="#fm_model" data-title="Add Account ">
                                                    <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                            class="fa fa-plus"></i></i></a>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-control" id="account" name='account' required>
                                                    <?php if(@$contratrans['account_name']) { ?>
                                                    <option value="<?=@$contratrans['account']?>">
                                                        <?=@$contratrans['account_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <div class="input-group-prepend" id="chk_btn"
                                                    style="display:<?php echo (@$contratrans['mode'] == 'Payment') ? 'none;' : 'block;' ?>">
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



                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Particular <b>(CR)</b>: <span
                                                    class="tx-danger">*</span>
                                                <a data-toggle="modal" href="<?=url('master/add_account')?>"
                                                    data-target="#fm_model" data-title="Add Account ">
                                                    <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                            class="fa fa-plus"></i></i></a>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-control" id="particular" onchange="calculate()"
                                                    name='particular'>
                                                    <?php if(@$contratrans['particular_name']) { ?>
                                                    <option value="<?=@$contratrans['particular']?>">
                                                        <?=@$contratrans['particular_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="state" value="<?=@$contratrans['acc_state']?>">
                                        </div>
                                    </div>

                                    <div class="row" id="transaction_type"
                                        style="display:<?=(@$contratrans['cash_type']=='cheque' || @$contratrans['cash_type']=='efund' || @$contratrans['cash_type']=='other') ?  'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Transaction Type :<span
                                                    class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name='trans_type'>
                                                    <option value="cheque"
                                                        <?=@$contratrans['cash_type'] == 'cheque' ? 'selected' : ''?>>
                                                        Cheque</option>
                                                    <option value="efund"
                                                        <?=@$contratrans['cash_type'] == 'efund' ? 'selected' : ''?>>
                                                        e-Fund Transfer</option>
                                                    <option value="other"
                                                        <?=@$contratrans['cash_type'] == 'other' ? 'selected' : ''?>>
                                                        Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="check_detail"
                                        style="display:<?php echo (@$contratrans['mode'] == 'Payment') ? 'none;' : 'block'; ?>">
                                        <div class="col-lg-6 form-group">
                                            <label class="form-label">cheque No.:</label>
                                            <input class="form-control" type="text" name="checkno"
                                                placeholder="Enter Check No" id="check_no"
                                                value="<?=@$contratrans['check_no']?>">
                                        </div>
                                        <?php 
                                        
                                        if(!empty($banktrans)){
                                            if(isset($banktrans['check_date']) && $banktrans['check_date'] != '0000-00-00'){
                                                $check_date=$banktrans['check_date'];
                                                
                                            }else{
                                                $check_date='';
                                            }
                                            
                                        }
                                        ?>
                                        <div class="col-lg-6 form-group">
                                            <label class="form-label">cheque Date:</label>
                                            <input class="form-control  fc-datepicker" autocomplete="off" id="chk_date"
                                                placeholder="YYYY-MM-DDY" type="text" name="chk_date"
                                                value="<?=@$check_date?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Amount: <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="amount" type="text" required
                                                onkeyup="calculate()" placeholder="Enter Amount"
                                                value="<?=@$contratrans['amount']?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label"><b
                                                    class="cash_type"><?=@$contratrans['cash_type']?></b></label>
                                            <input class="form-control" type="hidden" name="cash_type"
                                                value="<?=@$contratrans['cash_type']?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Narration:</label>
                                            <input class="form-control" type="text" name="narration"
                                                placeholder="Enter Narration" value="<?=@$contratrans['narration']?>">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="tx-danger error-msg-contra"></div>
                                <div class="tx-success form_proccessing_contra"></div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-6">
                                    <p class="text-left">
                                        <button class="btn btn-space btn-primary" id="save_data_contra"
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

<script>
<?php if(@$contratrans['stat_adj'] == 1) {?>
calculate();
<?php } ?>

$('.ajax-form-submit-contra').on('submit', function(e) {
    $('#save_data_contra').prop('disabled', true);
    $('.error-msg-contra').html('');
    $('.form_proccessing_contra').html('Please wait...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                window.location = "<?=url('Bank/contra_transaction')?>"
            } else {
                $('.form_proccessing_contra').html('');
                $('#save_data_contra').prop('disabled', false);
                $('.error-msg-contra').html(response.msg);
            }
        },
        error: function() {
            $('#save_data_contra').prop('disabled', false);
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
$(document).ready(function() {


    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    
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

        var parti = $("#particular option:selected").text();
        var account = data.text;

        var trans_type = document.getElementById("transaction_type");

        if (parti != '' && account != '' && parti != 'undefined' && account != 'undefined') {
            if (account == 'Cash' && parti != 'Cash') {
                $('.error-msg').html('');
                $('#save_data').prop('disabled', false);

                $("input[name='cash_type']").val('');
                $(".cash_type").html('');
                $("input[name='mode']").val('');


                $("input[name='cash_type']").val('Cash Withdraw');
                $(".cash_type").html('Cash Withdraw');

                $("input[name='mode']").val('Payment');
                trans_type.style.display = "none";


            } else if (account != 'Cash' && parti == 'Cash') {
                
                $('.error-msg').html('');
                $('#save_data').prop('disabled', false);

                $("input[name='cash_type']").val('');
                $("input[name='mode']").val('');
                $(".cash_type").html('');

                $("input[name='cash_type']").val('Cash Deposite');
                $("input[name='mode']").val('Receipt');
                $(".cash_type").html('Cash Deposite');
                trans_type.style.display = "none";


            } else if (account != 'Cash' && parti != 'Cash') {


                $('.error-msg').html('');
                $('#save_data').prop('disabled', false);

                $("input[name='cash_type']").val('');
                $("input[name='mode']").val('');
                $(".cash_type").html('');

                $("input[name='cash_type']").val('Fund Transfer');
                $("input[name='mode']").val('Receipt');
                $(".cash_type").html('Fund Transfer');
                trans_type.style.display = "flex";


                //**Bank to Bank Disable in Comment **//

                // $("input[name='cash_type']").val('');
                // $(".cash_type").html('');
                // $("input[name='mode']").val('');

                // $('.error-msg').html('Please Change Account or Particular..!!');
                // $('#save_data').prop('disabled', true);

            } else {
                $("input[name='cash_type']").val('');
                $(".cash_type").html('');
                $("input[name='mode']").val('');


                $('.error-msg').html('Please Change Account or Particular..!!');
                $('#save_data').prop('disabled', true);
                trans_type.style.display = "none";

            }
        }
    });


    $("#particular").select2({
        width: '100%',
        placeholder: 'Type Particular',
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

    $('#particular').on('select2:select', function(e) {
        var data = e.params.data;
        var state = data.state;
        $('input[name="state"]').val(state);
        $('#adjustment').val(null).trigger('change');
        //$('#invoices').select2({data:['text']});
        $('#invoices').empty();
        $('#invoices').val(null).trigger('change');

        var check_detail = document.getElementById("check_detail");
        var trans_type = document.getElementById("transaction_type");

        var parti = data.text;
        var account = $("#account option:selected").text();

        if (parti != '' && account != '' && parti != 'undefined' && account != 'undefined') {

            if (account == 'Cash' && parti != 'Cash') {
                $('.error-msg').html('');
                $('#save_data').prop('disabled', false);

                $("input[name='cash_type']").val('');
                $(".cash_type").html('');
                $("input[name='mode']").val('');


                $("input[name='cash_type']").val('Cash Withdraw');
                $(".cash_type").html('Cash Withdraw');

                $("input[name='mode']").val('Payment');

                trans_type.style.display = "none";

            } else if (account != 'Cash' && parti == 'Cash') {
                $('.error-msg').html('');
                $('#save_data').prop('disabled', false);

                $("input[name='cash_type']").val('');
                $("input[name='mode']").val('');
                $(".cash_type").html('');

                $("input[name='cash_type']").val('Cash Deposite');
                $("input[name='mode']").val('Receipt');
                $(".cash_type").html('Cash Deposite');

                trans_type.style.display = "none";


            } else if (account != 'Cash' && parti != 'Cash') {

                $('.error-msg').html('');
                $('#save_data').prop('disabled', false);

                $("input[name='cash_type']").val('');
                $("input[name='mode']").val('');
                $(".cash_type").html('');

                $("input[name='cash_type']").val('Fund Transfer');
                $("input[name='mode']").val('Receipt');
                $(".cash_type").html('Fund Transfer');

                trans_type.style.display = "flex";

                //**Bank to Bank Disable in Comment **//

                // $("input[name='cash_type']").val('');
                // $(".cash_type").html('');
                // $("input[name='mode']").val('');


                // $('.error-msg').html('Please Change Account or Particular..!!');
                // $('#save_data').prop('disabled', true);
            } else {
                $("input[name='cash_type']").val('');
                $(".cash_type").html('');
                $("input[name='mode']").val('');

                trans_type.style.display = "none";

                $('.error-msg').html('Please Change Account or Particular..!!');
                $('#save_data').prop('disabled', true);
            }
        }
    });

    $("select[name='trans_type']").select2({
        width: '100%',
        placeholder: 'Type Particular'
    });

    $("select[name='trans_type']").on('select2:select', function(e) {
        var data = e.params.data;
        var type = data.id;
        var check_detail = document.getElementById("check_detail");
        var checkrangebtn = document.getElementById("chk_btn");

        if (type == 'efund') {
            $(".cash_type").html('e-Fund Transfer');
            checkrangebtn.style.display = "none";
            check_detail.style.display = "none";
        }

        if (type == 'cheque') {
            $(".cash_type").html('Cheque');
            check_detail.style.display = "flex";
            checkrangebtn.style.display = "flex";
        }

        if (type == 'other') {
            $(".cash_type").html('Other');

            check_detail.style.display = "none";
            checkrangebtn.style.display = "none";
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

    $('#adjustment').on('select2:select', function(e) {
        var adjust = $('#adjustment').val();
        var particular_id = $('#particular').val();

        var invoice_div = document.getElementById("invoice_div");
        if (adjust == 'agains_reference') {
            $('#invoices').prop("disabled", false);
            invoice_div.style.display = "flex";


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