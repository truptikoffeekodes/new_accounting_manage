<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">Transaction</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Purchase/add_debit') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <!-- Row -->
            <div class="row">
                <div class="col-md-6 offset-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Document: <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="document" value="<?=@$debit['document']?>"
                                        placeholder="Enter Document" required="" type="text">
                                    <input name="id" value="<?=@$debit['id']?>" type="hidden">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Date: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" value="<?=@$debit['date']?>"
                                            name="date" placeholder="MM/DD/YYYY" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Status: <span class="tx-danger">*</span></label>
                                    <select class="form-control" name="status" tabindex="-1" aria-hidden="true">
                                        <option label="Select status">
                                        </option>
                                        <option value="1" <?= (@$debit['satatus'] == "0" ? 'selected' : '' ) ?>>
                                            Active
                                        </option>
                                        <option value="0" <?= (@$debit['satatus'] == "0" ? 'selected' : '' ) ?>>
                                            InActive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Class: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="class" id="class"
                                            onchange="validate_autocomplete(this,'class_id')"
                                            value="<?=@$cashrece['class']?>">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a data-toggle="modal" href="<?=url('MasterControllers/add_class')?>"
                                                    data-target="#fm_model" data-title="Add New Class "><i
                                                        style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="class_id" id="class_id" value="">
                                    <div class="dz-error-message tx-danger class_id"></div>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Reason: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="particulrs"
                                            value="<?=@$debit['particlur']?>">
                                    </div>
                                    <input type="hidden" name="jvparticulrs_id" id="jvparticulrs_id" value="">
                                    <div class="dz-error-message tx-danger jvparticulrs_id"></div>

                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="account" id="account"
                                            onchange="validate_autocomplete(this,'account_id')"
                                            value="<?=@$cashrece['account']?>">
                                        <a href="<?=url('Account/add_account')?>"></a>

                                    </div>
                                    <input type="hidden" name="account_id" id="account_id" value="">
                                    <div class="dz-error-message tx-danger account_id"></div>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Notes: <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="notes" value="<?=@$debit['notes']?>"
                                        placeholder="Enter Notes" required="" type="text">
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-12">
                                    <p class="text-right">
                                        <button class="btn btn-space btn-primary" id="save_data type="
                                            submit">Submit</button>
                                        <button class="btn btn-space btn-primary" type="button"
                                            onclick="addinput()">Add</button>
                                        <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    <div>
    </div>
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
                    window.location = "<?=url('purchase/debit_note')?>"
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

    function afterload() {

    }
    $(document).ready(function() {
        $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true
        });
    });

    function addinput() {
        var html = '';
        $('#addinput').append(html);
    }

    function validate_autocomplete(obj, val) {
        if ($('#' + val).val() == '') {
            $('.' + val).html('Option Select from dropdown list')
        } else {
            $('.' + val).html('')
        }
    }
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
    $('#jvparticulrs').autocomplete({
        serviceUrl: '<?= url('MasterControllers/Getdata/jvparticulrs_autocomp') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#jvparticulrs').val(suggestion.value);
            $('#jvparticulrs_id').val(suggestion.data);
        }
    });
    </script>
    <?= $this->endSection() ?>