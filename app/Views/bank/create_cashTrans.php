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
    <div class="btn btn-list">
        <a href="<?=url('bank/add_banktrans')?>" class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Add
            Bank Transaction</a>
        <a href="<?=url('bank/add_contratrans')?>" class="btn ripple btn-secondary"><i class="fe fe-external-link"></i>
            Add Contra Transaction</a>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="<?= url('bank/add_banktrans') ?>" class="ajax-form-submit-cash" method="post"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12">

                                    <div class="row">
                                        <div class="col-lg-12 form-group">

                                            <label class="form-label">Cash Mode: <span class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control parti_select2" id="tran_mode" name="mode"
                                                    required>
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
                                            <label class="form-label">Account: </label>
                                            <div class="input-group">
                                                <input class="form-control" name="account_name"
                                                    value="<?=@$cash_account['name'] ?>"
                                                    placeholder="<?=@$cash_account['name'] ?>" type="text" id=""
                                                    readonly>
                                                <input name="account" value="<?=@$cash_account['id']?>" type="hidden">
                                            </div>
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
                                                <select class="form-control parti_select2" id="adjustment"
                                                    name="adj_method" required>
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

                                    <div class="row" id="invoice_div"
                                        style="display:<?=!empty(@$cashtrans['bill']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Select Invoice : <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="invoices" name="invoice">
                                                </select>
                                            </div>
                                            <input type="hidden" name="invoice_table"
                                                value="<?=@$cashtrans['invoice_tb']?>" id="invoice_tb">
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered mg-b-0" id="bills">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>No</th>
                                                        <th>Date</th>
                                                        <th>Account</th>
                                                        <th>Total</th>
                                                        <th>Total Paid</th>
                                                        <th>Amount</th>
                                                        <th>Voucher Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody">
                                                    <?php if(isset($cashtrans['bill']) && !empty($cashtrans['bill'])){ 
                                                            for($i=0; $i<count($cashtrans['bill']) ; $i++){
                                                        ?>
                                                    <tr class="item_row">
                                                        <td><input type="hidden" name="against_id[]"
                                                                value="<?=$cashtrans['bill'][$i]['id']?>"> <a
                                                                class="tx-danger btnDelete" title="0"><i
                                                                    class="fa fa-times tx-danger"></i></a></td>
                                                        <td><input type="hidden" name="vch_id[]"
                                                                value="<?=$cashtrans['bill'][$i]['vch_id']?>"><?=$cashtrans['bill'][$i]['vch_id']?>
                                                        </td>
                                                        <td><input type="hidden" name="date[]"
                                                                value="<?=$cashtrans['bill'][$i]['date']?>"><?=user_date($cashtrans['bill'][$i]['date'])?>
                                                        </td>
                                                        <td><input type="hidden" name="ac_id[]"
                                                                value="<?=$cashtrans['bill'][$i]['ac_id']?>"><input
                                                                type="text" class="form-control input-sm"
                                                                name="ac_name[]"
                                                                value="<?=$cashtrans['bill'][$i]['ac_name']?>" readonly>
                                                        </td>
                                                        <td><input type="text" class="form-control input-sm"
                                                                name="net_amt[]"
                                                                value="<?=$cashtrans['bill'][$i]['net_amt']?>" readonly>
                                                        </td>
                                                        <td><input type="text" class="form-control input-sm"
                                                                name="total_paid[]"
                                                                value="<?=$cashtrans['bill'][$i]['total_paid']?>"
                                                                readonly>
                                                        </td>
                                                        <td><input type="text" onkeyup="calc()"
                                                                class="form-control input-sm" name="vch_amt[]"
                                                                value="<?=$cashtrans['bill'][$i]['vch_amt']?>">
                                                        </td>
                                                        <td><input type="text" class="form-control input-sm"
                                                                name="voucher_name[]"
                                                                value="<?=$cashtrans['bill'][$i]['voucher_name']?>"
                                                                readonly></td>
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
                                                    <?= ( @$cashtrans['stat_adj'] == "1" ? 'checked' : '' ) ?>
                                                    value="<?=@$cashtrans['stat_adj'] ?>">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Stat Adjustment</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row nature_pay"
                                        style="display:<?=!empty(@$cashtrans['nature_pay']) ? 'block;' : 'none;' ?>">
                                        <div class="col-lg-12 form-group">
                                            <label class="form-label">Nature of Payment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control parti_select2" name="nature_pay">
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

                                    <div class="row nature_rec"
                                        style="display:<?=!empty(@$cashtrans['nature_rec']) ? 'block;' : 'none;' ?>">
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
                                <div class="tx-danger error-msg-cash"></div>
                                <div class="tx-success form_proccessing_cash"></div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-6">
                                    <p class="text-left">
                                        <button class="btn btn-space btn-primary" id="save_data_cash"
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


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
$('.ajax-form-submit-cash').on('submit', function(e) {
    $('#save_data_cash').prop('disabled', true);
    $('.error-msg-cash').html('');
    $('.form_proccessing_cash').html('Please wait...');
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
                $('.form_proccessing_cash').html('');
                $('#save_data_cash').prop('disabled', false);
                $('.error-msg-cash').html(response.msg);
            }
        },
        error: function() {
            $('#save_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});

$('.fc-datepicker').datepicker({
    dateFormat: 'yy-mm-dd',
    showOtherMonths: true,
    selectOtherMonths: true
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
            $('input[name="stat_adj"]').prop('checked', false);
            $('input[name="stat_adj"]').val('0');
        }
    }
}


function calculate() {

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
            $('.error-msg-cash').html("Please Change Amount Of Invoice");
            $('#save_data_cash').attr('disabled', true);
        } else {
            $('.error-msg-cash').html("");
            $('#save_data_cash').attr('disabled', false);
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
    if(isset($cashtrans['id']))
    {?>

    var particular_id = $('#particular').val();

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

    $('#invoices').on('select2:select', function(e) {
        var suggestion = e.params.data.data;

        var id_inp = '<input type="hidden" name ="vch_id[]" value="' + suggestion
            .id + '">';
        var tds = '<tr class="item_row">';
        tds +=
            '<td><input type="hidden" name="against_id[]" value=""><a class="tx-danger btnDelete" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
        tds += '<td>' + suggestion.id + id_inp + '</td>';
        tds +=
            '<td>  <input type ="hidden" class="form-control input-sm"   name="date[]" value="' +
            suggestion.invoice_date + '" readonly>' + moment(suggestion
                .invoice_date).format('DD-MM-YYYY'); +
        '</td>';
        tds += '<td><input type="hidden" name ="ac_id[]" value="' + suggestion
            .account +
            '"><input type ="text" class="form-control input-sm" name="ac_name[]" value="' +
            suggestion.account_name + '" readonly></td>';
        tds +=
            '<td><input type ="text" class="form-control input-sm"   name="net_amt[]" value="' +
            suggestion.net_amount + '" readonly></td>';
        tds += '<td><input type ="text" class="form-control input-sm"   name="total_paid[]" value="' +
            suggestion.total_paid + '" readonly></td>';
        tds +=
            '<td><input class="form-control input-sm"  required name="vch_amt[]"  onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="' +
            suggestion.net_amount - suggestion.total_paid + '"  type="text"></td>';
        tds +=
            '<td> <input type ="text" class="form-control input-sm"   name="voucher_name[]" value="' +
            suggestion.voucher_name + '" readonly></td>';

        $('.tbody').append(tds);
        $('#invoices').val('');

        calculate();
    });


    <?php } ?>

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $("#bills").on('click', '.btnDelete', function() {

        $(this).closest('tr').remove();
        calculate();
    });

    $('.parti_select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#adjustment').on('select2:select', function(e) {
        var adjust = $('#adjustment').val();
        var particular_id = $('#particular').val();

        var invoice_div = document.getElementById("invoice_div");

        if (adjust == 'agains_reference') {
            $('#invoices').prop("disabled", false);
            invoice_div.style.display = "flex";


            if (particular_id != undefined && particular_id != '') {

                <?php 
                if(!isset($cashtrans['id']))
                {?>

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

                $('#invoices').on('select2:select', function(e) {
                    var suggestion = e.params.data.data;

                    console.log(suggestion);
                    var id_inp = '<input type="hidden" name ="vch_id[]" value="' + suggestion
                        .id + '">';
                    var tds = '<tr class="item_row">';
                    tds +=
                        '<td><input type="hidden" name="against_id[]" value=""><a class="tx-danger btnDelete" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                    tds += '<td>' + suggestion.id + id_inp + '</td>';
                    tds +=
                        '<td>  <input type ="hidden" class="form-control input-sm"   name="date[]" value="' +
                        suggestion.invoice_date + '" readonly>' + moment(suggestion
                            .invoice_date).format('DD-MM-YYYY'); +
                    '</td>';
                    tds += '<td><input type="hidden" name ="ac_id[]" value="' + suggestion
                        .account +
                        '"><input type ="text" class="form-control input-sm" name="ac_name[]" value="' +
                        suggestion.account_name + '" readonly></td>';
                    tds +=
                        '<td><input type ="text" class="form-control input-sm"   name="net_amt[]" value="' +
                        suggestion.net_amount + '" readonly></td>';
                    tds +=
                        '<td><input type ="text" class="form-control input-sm"   name="total_paid[]" value="' +
                        suggestion
                        .total_paid + '" readonly></td>';
                    tds +=
                        '<td><input class="form-control input-sm"  required name="vch_amt[]"  onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="' +
                        (suggestion.net_amount - suggestion.total_paid) +
                        '"  type="text"></td>';
                    tds +=
                        '<td> <input type ="text" class="form-control input-sm"   name="voucher_name[]" value="' +
                        suggestion.voucher_name + '" readonly></td>';

                    $('.tbody').append(tds);
                    $('#invoices').val('');

                    calculate();
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

    $("#account").select2({
        width: '100%',
        placeholder: 'Type Account',
        ajax: {
            url: PATH + "Master/Getdata/search_cashtrans_account",
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
        var url = href + '?bank_id=' + bankid;
        $("#checkrng").attr("href", url);

    });

    $('#tran_mode').on('select2:select', function(e) {
        $('input[name="stat_adj"]').prop('checked', false);
        $('.nature_pay').css('display', 'none');
        $('.nature_rec').css('display', 'none');
        $('input[name="stat_adj"]').val('0');
    });
});
</script>

<?= $this->endSection() ?>