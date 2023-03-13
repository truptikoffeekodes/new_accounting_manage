<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Cash </a></li>
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

                                            <label class="form-label">Cash Mode: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="tran_mode" name="mode" required>
                                                    <option value="">None</option>
                                                    <option
                                                        <?= ( @$cashtrans['mode'] == "Receipt" ? 'selected' : '' ) ?>
                                                        value="Receipt">Receipt</option>
                                                    <option
                                                        <?= ( @$cashtrans['mode'] == "Payment" ? 'selected' : '' ) ?>
                                                        value="Payment">Payment</option>
                                                </select>
                                                <input type="hidden" name="pay_type" value="cash">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12  form-group">
                                            <label class="form-label">Date<span class="tx-danger">*</span></label>
                                            <input class="form-control fc-datepicker" name="receipt_date"
                                                value="<?=@$cashtrans['receipt_date'] ? $cashtrans['receipt_date'] : date('Y-m-d'); ?>"
                                                placeholder="MM/DD/YYYY" type="text" id="" required>
                                            <input name="id" value="<?=@$cashtrans['id']?>" type="hidden">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="account" name='account' required>
                                                    <?php if(@$cashtrans['account_name']) { ?>
                                                    <option value="<?=@$cashtrans['account']?>">
                                                        <?=@$cashtrans['account_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Particular: <span
                                                    class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="particular" name='particular'>
                                                    <?php if(@$cashtrans['particular_name']) { ?>
                                                    <option value="<?=@$cashtrans['particular']?>">
                                                        <?=@$cashtrans['particular_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="row">
                                        
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Method of Adjustment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="adjustment" name="adj_method" required>
                                                    <option value="">None</option>
                                                    <option
                                                        <?= ( @$cashtrans['adj_method'] == "Advanced" ? 'selected' : '' ) ?>
                                                        value="Advanced">Advanced</option>
                                                    <option
                                                        <?= ( @$cashtrans['adj_method'] == "agains_reference" ? 'selected' : '' ) ?>
                                                        value="agains_reference">Agains Reference</option>
                                                    <option
                                                        <?= ( @$cashtrans['adj_method'] == "new_reference" ? 'selected' : '' ) ?>
                                                        value="new_reference">New Reference</option>
                                                    <option 
                                                        <?= ( @$cashtrans['adj_method'] == "on_account" ? 'selected' : '' ) ?>
                                                        value="on_account">On Account</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row" id="invoice_div" style = "display:<?=!empty($cashtrans['invoice']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Invoice : <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="invoices" name="invoice" >
                                                    <?php if(@$cashtrans['invoice_name']) { ?>
                                                    <option value="<?=@$cashtrans['invoice']?>">
                                                        <?=@$cashtrans['invoice_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="invoice_table" value="<?=@$cashtrans['invoice_tb']?>" id="invoice_tb">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Amount: <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="amount" type="text" required
                                                placeholder="Enter Amount" value="<?=@$cashtrans['amount']?>">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Narration:</label>
                                            <input class="form-control" type="text" name="narration"
                                                placeholder="Enter Narration" value="<?=@$cashtrans['narration']?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="custom-switch">
                                                <input type="checkbox" name="stat_adj" onchange="check_stat()"
                                                    class="custom-switch-input"
                                                    <?= ( @$cashtrans['stat_adj'] == "1" ? 'checked' : '' ) ?> value="<?=@$cashtrans['stat_adj'] ?>">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Stat Adjustment</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row nature_pay" style="display:<?=!empty(@$cashtrans['nature_pay']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Payment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="nature_pay">
                                                    <option
                                                        <?= ( @$cashtrans['nature_pay'] == "1" ? 'selected' : '' ) ?>
                                                        value="1">Not Applicable</option>
                                                    <option
                                                        <?= ( @$cashtrans['nature_pay'] == "2" ? 'selected' : '' ) ?>
                                                        value="2">Advanced Payment Under Reserve Charge</option>
                                                    <option
                                                        <?= ( @$cashtrans['nature_pay'] == "3" ? 'selected' : '' ) ?>
                                                        value="3">Payment Under Reserve Charge</option>
                                                    <option
                                                        <?= ( @$cashtrans['nature_pay'] == "4" ? 'selected' : '' ) ?>
                                                        value="4">Refund of Advance Receipt</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row nature_rec" style="display:<?=!empty(@$cashtrans['nature_rec']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Receipt: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="nature_rec">
                                                    <option
                                                        <?= ( @$cashtrans['nature_rec'] == "1" ? 'selected' : '' ) ?>
                                                        value="1">Not Applicable</option>
                                                    <option
                                                        <?= ( @$cashtrans['nature_rec'] == "2" ? 'selected' : '' ) ?>
                                                        value="2">Advanced Receipt</option>
                                                    <option
                                                        <?= ( @$cashtrans['nature_rec'] == "3" ? 'selected' : '' ) ?>
                                                        value="3">Refund of Advance Receipt</option>
                                                </select>
                                            </div>
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

                window.location = "<?=url('Bank/cash_transaction')?>"
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
            $('.error-msg').html('Please Select Bank Transaction Mode..!');
            $('input[name="stat_adj"]').prop('checked',false);
            $('input[name="stat_adj"]').val('0');
        }
    }
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

    $('#adjustment').on('select2:select', function(e) {
        var adjust = $('#adjustment').val();
        var particular_id = $('#particular').val();

        var invoice_div = document.getElementById("invoice_div");
        
        if (adjust == 'agains_reference') {
            $('#invoices').prop("disabled",false);
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
        }else{
            var invoice_div =document.getElementById("invoice_div");
            $('#invoices').prop("disabled",true);
            invoice_div.style.display = "none";
        }
    });

    $('#invoices').on('select2:select', function(e) {
        var data = e.params.data;
       
        $('#invoice_tb').val(data.table);
    });

    $("#account").select2({
        width: '100%',
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

    $('#particular').on('select2:select', function(e) {
        var data = e.params.data;
        $('#adjustment').val(null).trigger('change');
        //$('#invoices').select2({data:['text']});
        $('#invoices').empty();
        $('#invoices').val(null).trigger('change');

    });

    $("#bank").select2({
        width: 'resolve',
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

    $('#bank').on('select2:select', function(e) {
        var data = e.params.data;
        var bankid = data.id;
        var href = $('#checkrng').attr("href"); 
        var url=href+'?bank_id='+bankid;
        $("#checkrng").attr("href",url);
        
    });



    $('#tran_mode').on('select2:select', function(e) {
        $('input[name="stat_adj"]').prop('checked',false);
        $('.nature_pay').css('display','none');
        $('.nature_rec').css('display','none');
        $('input[name="stat_adj"]').val('0');
    });

});

</script>

<?= $this->endSection() ?>