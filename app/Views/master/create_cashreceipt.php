<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Cash Receipt</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Cash Receipt</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Cash Recepit</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_cashrece') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6  form-group">
                                    <label class="form-label">DayBook: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="daybook" id="daybook"
                                            onchange="validate_autocomplete(this,'daybook')" placeholder="Select Daybook"
                                            value="<?=@$cashrece['daybk']?>" required>
                                    </div>
                                    <input type="hidden" name="daybook_id" id="daybook_id" value="<?=@$cashrece['daybook']?>">
                                    <div class="dz-error-message tx-danger daybook_id"></div>
                                </div>
                                <div class="col-lg-6  form-group">
                                    <label class="form-label">Date<span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" name="date"
                                            placeholder="MM/DD/YYYY" type="text" id="dp1599912508714"
                                            value="<?=@$cashrece['date']?>">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="account" placeholder="Select Account" id="account"
                                            onchange="validate_autocomplete(this,'account_id')"
                                            value="<?=@$cashrece['account']?>">
                                    </div>
                                    <input type="hidden" name="account_id" id="account_id" value="<?=@$cashrece['account']?>">
                                    <div class="dz-error-message tx-danger account_id"></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Class: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="class" id="class"
                                            onchange="validate_autocomplete(this,'class_id')" placeholder="Select Class"
                                            value="<?=@$cashrece['class']?>">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a data-toggle="modal" href="<?=url('Master/add_class')?>"
                                                    data-target="#fm_model" data-title="Add New Class "><i
                                                    style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="class_id" id="class_id" value="<?=@$cashrece['class']?>">
                                    <div class="dz-error-message tx-danger class_id"></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Received By/Sub Ledger: <span
                                            class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="receby_sub"
                                            value="<?=@$cashrece['receby_sub']?>" id="received" placeholder="Select Sub Ledger"
                                            onchange="validate_autocomplete(this,'category')" required=""
                                            autocomplete="off">
                                    </div>
                                    <input type="hidden" name="received_id" id="received_id" value="<?=@$cashrece['receby_sub']?>">
                                    <div class="dz-error-message tx-danger received_id"></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Particulars: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="particulrs" placeholder="Enter Particulars"
                                            value="<?=@$cashrece['particulrs']?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="">Amount</label>
                                    <input class="form-control" name="amount" onkeypress="return isNumberKey(event)"
                                        value="<?=@$cashrece['amount']?>" required="" type="text">
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
                window.location = "<?=url('Master/cashreceipts')?>";
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

    $('#daybook').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_daybook') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#daybook').val(suggestion.value);
            $('#daybook_id').val(suggestion.data);
        }
    });

    $('#received').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_account') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#received').val(suggestion.value);
            $('#received_id').val(suggestion.data);
        }
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
   
});
</script>


<?= $this->endSection() ?>