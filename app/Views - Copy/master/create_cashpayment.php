<?= $this->extend(THEME . 'templete') ?>

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
        <form action="<?= url('master/add_cashpayment') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class=" col-lg-12 form-group">
                                    <label class="">Account Balance:<span class="tx-danger">Zero</span></label>
                                </div>
                                <div class=" col-lg-6 form-group">
                                    <label class="">Document:<span class="tx-danger">*</span></label>
                                    <input class="form-control" name="document" value="<?=@$cashpayment['document']?>"
                                        required="" type="text">
                                </div>
                                <div class="col-lg-6  form-group">
                                    <label class="form-label">Date<span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker"
                                            value="<?=@$cashpayment['date']?>" name="date" placeholder="MM/DD/YYYY"
                                            type="text" id="dp1599912508714">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="account"
                                            onchange="validate_autocomplete(this,'account_id')" name="account"
                                            value="<?=@$cashpayment['account']?>">
                                        <input type="hidden" name="account_id" id="account_id"
                                            value="<?=@$cashpayment['account']?>">
                                        <div class="dz-error-message tx-danger account_id"></div>
                                        <input type="hidden" name="id" value="<?=@$cashpayment['id']?>">
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Class: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" name="class" id="class"
                                            onchange="validate_autocomplete(this,'class_id')" type="text"
                                            value="<?=@$cashpayment['class']?>">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a data-toggle="modal" href="<?=url('master/add_class')?>"
                                                    data-target="#fm_model" data-title=" "><i style="font-size:20px;"
                                                        class="fe fe-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Paid To/Sub Ledger: <span
                                            class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="paid_to" id="paid"
                                            value="<?=@$cashpayment['paid_to_sub']?>"
                                            onchange="validate_autocomplete(this,'paid')" required="">
                                        <input type="hidden" name="paid_id" id="paid_id" value="">
                                        <div class="dz-error-message tx-danger paid_id"></div>

                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Particulars: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" name="particulars" type="text"
                                            value="<?=@$cashpayment['particulrs']?>">

                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="">Amount</label>
                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="amount"
                                        value="<?=@$cashpayment['amount']?>" required="" type="text">
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
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    $('.form_proccessing').html('Please wail...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                // $('#fm_model').modal('toggle');
                //swal("success!", "Your update successfully!", "success");
                //datatable_load('');
                //$('#save_data').prop('disabled', false);
                window.location = "<?=url('Master/cashpayment')?>";
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

// function afterload(){

// }
function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}
$(document).ready(function() {

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('#account').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_account') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#account').val(suggestion.value);
            $('#account_id').val(suggestion.data);
        }
    });
    $('#class').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_class') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#class').val(suggestion.value);
            $('#class_id').val(suggestion.data);
        }
    });
    $('#paid').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_account') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#paid').val(suggestion.value);
            $('#paid_id').val(suggestion.data);
        }
    });
    $('#jvparticulrs').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/jvparticulrs_autocomp') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#jvparticulrs').val(suggestion.value);
            $('#jvparticulrs_id').val(suggestion.data);
        }
    });
});

function addinput() {
    var html = '';
    $('#addinput').append(html);
}
</script>

<?= $this->endSection() ?>