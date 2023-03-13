<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">Credit Note</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Transaction</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Sales/add_cnote') ?>" class="ajax-form-submit" method="post" enctype="multipart/form-data">
            <!-- Row -->
            <div class="row">
                <div class="col-md-6 offset-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Document: <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="document" value="<?=@$c_note['document']?>" placeholder="Enter Document"
                                        required="" type="text">
                                    <input class="form-control" name="id" value="<?=@$c_note['id']?>" type="hidden">
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Date: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="date" value="<?=@$c_note['date']?>" placeholder="MM/DD/YYYY"
                                            type="text">
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Status: <span class="tx-danger">*</span></label>
                                    <select class="form-control" tabindex="-1" name="status" aria-hidden="true">
                                        <option label="Select status" >
                                        </option>
                                        <option <?= ( @$c_note['status'] == "1" ? 'selected' : '' ) ?> value="1" >
                                            Active
                                        </option>
                                        <option <?= ( @$c_note['status'] == "0" ? 'selected' : '' ) ?> value="0" >
                                            InActive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Class: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="class" onchange="validate_autocomplete(this,'cls_id')" name="class" value="<?=@$c_note['class']?>" 
                                            required="" autocomplete="off">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            <a data-toggle="modal" href="<?= url('MasterController/create_class') ?>" data-title="Add Class" data-target="#fm_model"
                                                        ><i style="font-size:20px;"
                                                            class="fe fe-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="cls_id" id="cls_id" >
                                            <div class="dz-error-message tx-danger cls_id"></div>
                                </div>


                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="account" id="account" onchange="validate_autocomplete(this,'acc_id')" value="<?=@$c_note['account']?>" 
                                            required="" autocomplete="off">
                                        
                                    </div>
                                    <input type="hidden" name="acc_id" id="acc_id" >
                                            <div class="dz-error-message tx-danger acc_id"></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Particular: <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text"  name="particular" value="<?=@$c_note['particular']?>" 
                                            required="" autocomplete="off">
                                    </div>
                                    <input type="hidden" name="part_id" id="part_id" >
                                            <div class="dz-error-message tx-danger part_id"></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Amount: <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="amount" onkeypress="return isDesimalNumberKey(event)" value="<?=@$c_note['amount']?>" placeholder="Enter Amount"
                                        required="" type="text">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Notes: <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="notes" value="<?=@$c_note['notes']?>" placeholder="Enter Notes"
                                        required="" type="text">
                                </div>


                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-12">
                                    <p class="text-right">
                                        <button class="btn btn-space btn-primary" id="save_data"
                                            type="submit">Submit</button>
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
        </form>
    </div>
</div>
<script>
function afterload() {}
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .autocomplete-suggestions {
        border: 1px solid #999;
        background: #FFF;
        overflow: auto;
    }

    .autocomplete-suggestion {
        padding: 2px 5px;
        white-space: nowrap;
        overflow: hidden;
    }

    .autocomplete-selected {
        background: #F0F0F0;
    }

    .autocomplete-suggestions strong {
        font-weight: normal;
        color: #3399FF;
    }

    .autocomplete-group {
        padding: 2px 5px;
    }

    .autocomplete-group strong {
        display: block;
        border-bottom: 1px solid #000;
    }
</style>
<script>

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
                $('#acc').val(suggestion.value);
                $('#acc_id').val(suggestion.data);
            }
    });
    $('#class').autocomplete({
            serviceUrl: '<?= url('Master/Getdata/search_class') ?>',
            type: 'POST',
            showNoSuggestionNotice: true,
            onSelect: function(suggestion) {
                $('#cls').val(suggestion.value);
                $('#cls_id').val(suggestion.data);
            }
    });
    
});

function addinput() {
    var html = '';
    $('#addinput').append(html);
}

$('.ajax-form-submit').on('submit', function(e) {
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wai...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                //$('#fm_model').modal('toggle');
                //swal("success!", "Your update successfully!", "success");
                window.location="<?= url('Sales/credit_note') ?>"
                datatable_load('');
                $('#save_data').prop('disabled', false);
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
</script>

<?= $this->endSection() ?>