<?= $this->extend(THEME . 'templete') ?>
<?= $this->section('content') ?>

<!-- Page Header -->

<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>

<!--Start Navbar -->

<div class="responsive-background">
    <div class="collapse navbar-collapse show" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="post" id="date_submit" class="">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row mb-2">

                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker_from"  name="from"
                                            placeholder="YYYY-MM-DD" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker_to"  name="to"
                                            placeholder="YYYY-MM-DD" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">Select Bank:</label>
                                    <div class="input-group">
                                        <select class="form-control" id="account" name='account' required>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">Reset</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b><?=@$account_name?></b></span>
                                    <br>
                                    <b><?=@$from ? user_date(@$from) : '' ?></b> to
                                    <b><?=@$to ? user_date(@$to) : '' ?></b>

                                    <a href="<?=url('Bank/unlink_reconsilation/').@$from.'/'.@$to.'/'.@$account_id?>"
                                        class="btn btn-primary">Unlink</a>

                                    <!--<a href="<?=url('Bank/Statement/').@$from.'/'.@$to.'/'.@$account_id?>" class="btn btn-primary">Statement</a>
                                     -->
                                    <h5 style="float:right;">Reconcilation - Balance B/F :
                                        <?=@(float)$opening_bal > 0 ? (float)$opening_bal.' DR' : ((float)$opening_bal * -1).' CR'?></h5>
                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <form method="post" action="<?=url('bank/update_recons')?>" class="ajax-form-submit">
                    <div class="table-responsive">
                        <table class="table table mg-b-0">
                            <thead>
                                <tr>
                                    <th>ID.</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Payment Mode</th>
                                    <th>Check No.</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Reconcilate</th>
                                    <th>UNReconcilate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_credit =0;
                                $total_debit =0;
                                if(!empty($bank)){
                                    foreach($bank as $row) {
                                        $date = date_create($row['date']);
                                        $fdate = date_format($date,"d-m-Y");
                                ?>
                                <tr class="base">
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$fdate;?></td>
                                    <td><?=(@$row['cash_type'] == 'Cash Withdraw' || $row['cash_type'] == 'Cash Deposite') ? $row['cash_type'] : @$row['account_name'];?></td>
                                    <td class="mode"><?=@$row['payment_type'] == 'contra' ? 'Contra' : @$row['mode'];?></td>
                                    <td><?=@$row['check_no'];?></td>
                                    <?php 
                                        if($row['mode']=='Receipt')
                                        {
                                          
                                    ?>
                                    <td class="amount"><?=@$row['amount'];?></td>
                                    <input type="hidden" class="deb" value="">

                                    <td></td>
                                    <?php
                                    }else{

                                       
                                    ?>
                                    <td></td>
                                    <td class="amount"><?=@$row['amount'];?></td>
                                    <input type="hidden" class="cred" value="">
                                    <?php
                                    }
                                    ?>
                                    <td>
                                        <a href="#" class="recon_month" data-name="<?=@$row['ct_id']?>" data-type="text" data-pk="<?=$row['id']?>"
                                            data-title="Enter username"><?=@$month[$row['recons_date']]?></a>
                                    </td>

                                    <td><button class="btn btn-link unlink"
                                            onclick="return unlink_reconsilaion(this,'<?=$row['id']?>','<?=@$row['ct_id']?>')">Unlink</button>
                                    </td>

                                </tr>
                                <?php }  ?>

                                

                                <tr>
                                    <td colspan="5"></td>
                                    <td><b>Opening</b>
                                        
                                    </td>
                                    <td><b><?=number_format(@$prev_opening,2)?></b>

                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><b id="tot_debit"><?=@$total['bankdebit_total']?> DB</b>
                                        <input type="hidden" class="tot_deb"
                                            value="<?=number_format(@$total['bankdebit_total'],2,'.','')?>">
                                    </td>
                                    <td><b id="tot_credit"><?=@$total['bankcredit_total']?> CR</b>
                                        <input type="hidden" class="tot_cred"
                                            value="<?=number_format(@$total['bankcredit_total'],2,'.','')?>">
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="5"></td>
                                    <td><b> Books: </b> </td>
                                    <td><b><?=($opening_bal) < 0 ? ((float)$opening_bal) * -1  .' CR' : $opening_bal .' DB'?></b></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <?php  
                                    $bankbk =0;
                                ?>

                                <tr>
                                    <td colspan="5"></td>
                                    <td><b> BankBK: </b> </td>
                                    <td><b id="bankbk"><?=@$opening_bal > 0 ? @$opening_bal.' DR' : ((float)$opening_bal * -1 ).' CR'?></b></td>

                                    <td></td>
                                    <td></td>
                                    <input type="hidden" name="bankbk_input" value="<?=@$opening_bal?>"></b></td>
                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection() ?>

<?= $this->section('scripts') ?>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css"
    href="<?=ASSETS?>/plugins/x-editable/jqueryui-editable/css/jqueryui-editable.css">
<script type="text/javascript" src="<?=ASSETS?>/plugins/x-editable/jqueryui-editable/js/jqueryui-editable.min.js">

</script>
<script type="text/javascript">
$.fn.editable.defaults.mode = 'inline';

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var num = $('.recon_val').val();
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) || (num > 10)) {
        if (num > 12) {
            alert('Choose Month from 1-12');
        }
        return false;
    } else {
        return true;
    }
}

function calc() {

    var debit = parseFloat($('.tot_deb').val());
    var credit = parseFloat($('.tot_cred').val());
    var cred = deb = 0;

    $(".deb").map(function(i, el) {
        if (!isNaN(parseFloat($(el).val()))) {
            deb += parseFloat($(el).val());
        }
    }).get();

    $(".cred").map(function(i, el) {
        if (!isNaN(parseFloat($(el).val()))) {
            console.log("val:" + $(el).val());

            cred += parseFloat($(el).val());
        }
    }).get();

    var total_debit = debit - deb;
    var total_credit = credit - cred;

    $('#tot_debit').html(total_debit.toFixed(2) + ' DB');
    $('#tot_credit').html(total_credit.toFixed(2) + ' CR');

}

function unlink_reconsilaion(obj, id, ct_id) {
    var aurl = '<?=url('bank/single_unlink')?>';
    
    $.ajax({
        type: "POST",
        url: aurl,
        data: {
            'id': id,
            'ct_id': ct_id,
        },
        success: function(response) {

            if (response.st == 'success') {
                // console.log(response.data);return;
                ('');
                var bankbk = parseFloat($('#bankbk').html());
                var bankbk_input = $('input[name="bankbk_input"]').val();


                var mode = response.data.mode;
                var amount = response.data.amount;

                if (mode == 'Receipt') {
                    $(obj).closest(".base").find('.deb').val('')
                }
                if (mode == 'Payment') {
                    $(obj).closest(".base").find('.cred').val('')
                }

                if (mode == 'Receipt') {
                    if (bankbk_input == '' || bankbk_input == 'NaN') {
                        bankbk_input = 0;
                    }
                    bankbk_input = parseFloat(bankbk_input) - parseFloat(amount);
                }

                if (mode == 'Payment') {
                    if (bankbk_input == '' || bankbk_input == 'NaN') {
                        bankbk_input = 0;
                    }
                    bankbk_input = parseFloat(bankbk_input) + parseFloat(amount);
                    
                }
                $('input[name="bankbk_input"]').val(bankbk_input);

                if (bankbk_input < 0) {
                    $('#bankbk').html((parseFloat(bankbk_input) * -1) + ' CR');
                }

                if (bankbk_input > 0) {
                    $('#bankbk').html(bankbk_input + ' DB');
                }

                if (bankbk_input == 0) {
                    $('#bankbk').html(bankbk_input );
                }

                calc();


                $(obj).closest(".base").find('.recon_month').addClass('editable-empty');
                $(obj).closest(".base").find('.recon_month').html('Empty');
                $(obj).closest(".base").find('.unlink').html('');
            } else {
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            $('#save_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
}

$(document).ready(function() {

    $('.fc-datepicker_from').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.fc-datepicker_to').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    var status_source = [{
        id: '1',
        text: 'January',
    }, {
        id: '2',
        text: 'February',
    }, {
        id: '3',
        text: 'March',
    }, {
        id: '4',
        text: 'April',
    }, {
        id: '5',
        text: 'May',
    }, {
        id: '6',
        text: 'June',
    }, {
        id: '7',
        text: 'July',
    }, {
        id: '8',
        text: 'Auguest',
    }, {
        id: '9',
        text: 'September',
    }, {
        id: '10',
        text: 'October',
    }, {
        id: '11',
        text: 'November',
    }, {
        id: '12',
        text: 'December',
    }];


    $('.recon_month').editable({
        tpl: "<input type='text' class='recon_val' onkeyup ='return isNumberKey(event)'>",
        url: '<?=url('bank/update_recons')?>',
        type: 'text',
        title: 'Enter username',
        showbuttons: false,
        //onkeypress:"return isNumberKey(event)",
        validate: function(value) {
            if ($.isNumeric(value) == '') {
                return 'Only numbers are allowed';
            }
        },
        display: function(value) {
            if (!value) {
                $(this).empty();
                return;
            }
            if (!isNaN(parseInt(value))) {
                var html = status_source[parseInt(value) - 1].text;
                $(this).html(html);
            }
        },
        success: function(data, config) {

            var bankbk = parseFloat($('#bankbk').html());
            var bankbk_input = $('input[name="bankbk_input"]').val();

            var debit = parseFloat($('#tot_debit').html());
            var credit = parseFloat($('#tot_credit').html());

            var mode = $(this).closest(".base").find('.mode').html();
            $(this).closest(".base").find('.unlink').html('Unlink');
            var amount = parseFloat($(this).closest(".base").find('.amount').html());

            if (mode == 'Receipt') {
                if (bankbk_input == '' || bankbk_input == 'NaN') {
                    bankbk_input = 0;
                }
                bankbk_input = parseFloat(bankbk_input) + parseFloat(amount);

                debit -= amount;
            }

            if (mode == 'Payment') {
                if (bankbk_input == '' || bankbk_input == 'NaN') {
                    bankbk_input = 0;
                }
                bankbk_input = parseFloat(bankbk_input) - parseFloat(amount);
                credit -= amount;
            }
            $('input[name="bankbk_input"]').val(bankbk_input);

            if (bankbk_input < 0) {
                $('#bankbk').html((parseFloat(bankbk_input) * -1) + ' CR');
            }

            if (bankbk_input > 0) {
                $('#bankbk').html(bankbk_input + ' DB');
            }

            $('#tot_debit').html(debit + ' DB');
            $('#tot_credit').html(credit + ' CR');

        }

    });

    $('.status').editable({
        tpl: '<select name="month"></select>',
        source: status_source,
        showbuttons: false,
        select2: {
            placeholder: 'Select Month',
            width: '200px',
            matcher: function(params, data) {

                if ($.trim(params.term) === '') {
                    return data;
                }
                if (typeof data.id === 'undefined') {
                    return null;
                }

                if (data.id.indexOf(params.term) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    return modifiedData;
                }

                return null;
            },
        },
        success: function(data, config) {
            var bankbk = parseFloat($('#bankbk').html());
            var bankbk_input = $('input[name="bankbk_input"]').val();


            var debit = parseFloat($('#tot_debit').html());
            var credit = parseFloat($('#tot_credit').html());

            var mode = $(this).closest(".base").find('.mode').html();
            $(this).closest(".base").find('.unlink').html('Unlink');
            var amount = parseFloat($(this).closest(".base").find('.amount').html());

            if (mode == 'Receipt') {
                if (bankbk_input == '' || bankbk_input == 'NaN') {
                    bankbk_input = 0;
                }
                bankbk_input = parseFloat(bankbk_input) + parseFloat(amount);
                debit -= amount;
            }

            if (mode == 'Payment') {
                if (bankbk_input == '' || bankbk_input == 'NaN') {
                    bankbk_input = 0;
                }
                bankbk_input = parseFloat(bankbk_input) - parseFloat(amount);
                credit -= amount;
            }
            $('input[name="bankbk_input"]').val(bankbk_input);

            if (bankbk_input < 0) {
                $('#bankbk').html((parseFloat(bankbk_input) * -1) + ' CR');
            }

            if (bankbk_input > 0) {
                $('#bankbk').html(bankbk_input + ' DB');
            }

            $('#tot_debit').html(debit + ' DB');
            $('#tot_credit').html(credit + ' CR');

            var index = status_source.findIndex(obj => status_source.id == data.id);
            status_source[index].selected = false;
        }
    });

    $('.date').editable({
        type: 'text',
        method: 'Post',
        name: 'name',
        url: PATH + 'Master/editable_update',
        title: 'Enter Update',
    }).on('shown', function(e, editable) {
        editable.input.$input.mask('99-99-9999');
    });

    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
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
});
</script>
<?= $this->endsection() ?>